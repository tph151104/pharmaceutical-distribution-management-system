<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhachTraHang;
use App\Models\ChiTietTraHang;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use App\Models\ChiTietPhieuXuat;
use App\Models\ChiTietDonHang;
use App\Models\NhaCungCap;
use App\Models\DonHang;
use App\Models\KhachHang;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryLogService;

class CustomerReturnsController extends Controller
{
    /**
     * Danh sách Yêu cầu trả hàng
     */
    public function index()
    {
        $traHangs = KhachTraHang::with(['khachHang', 'donHang'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.inventory.returns.index', compact('traHangs'));
    }

    /**
     * Chi tiết Yêu cầu
     */
    public function show($id)
    {
        $traHang = KhachTraHang::with(['khachHang', 'donHang', 'chiTiet.thuoc'])->findOrFail($id);
        
        // Tìm phiếu nhập liên quan để kiểm tra điều kiện hoàn tác
        $phieuNhap = PhieuNhap::where('ma_phieu_nhap', 'LIKE', 'PN_TRA_%')
            ->where('tieu_lieu_lien_quan', 'LIKE', '%' . $id . '%')
            ->with('chiTiet')
            ->first();

        $canUndo = false;
        $receivedCount = 0;
        if ($phieuNhap && $traHang->trang_thai === 'da_duyet_nhap_kho') {
            $receivedCount = $phieuNhap->chiTiet->sum('so_luong_thuc_te');
            if ($receivedCount == 0) {
                $canUndo = true;
            }
        }

        return view('admin.inventory.returns.show', compact('traHang', 'phieuNhap', 'canUndo', 'receivedCount'));
    }

    /**
     * Từ chối Yêu cầu
     */
    public function reject(Request $request, $id)
    {
        $traHang = KhachTraHang::findOrFail($id);
        if ($traHang->trang_thai !== 'cho_duyet') return back()->withErrors(['error' => 'Trạng thái không hợp lệ.']);

        $traHang->trang_thai = 'tu_choi';
        $traHang->ghi_chu_admin = $request->ly_do;
        $traHang->nguoi_duyet = auth()->id();
        $traHang->ngay_duyet = now()->toDateString();
        $traHang->save();

        return back()->with('success', 'Đã từ chối yêu cầu trả hàng.');
    }
    /**
     * Phê duyệt & Nhập kho (KV04) & Hoàn tiền
     */
    public function approve(Request $request, $id)
    {
        $traHang = KhachTraHang::with('chiTiet')->findOrFail($id);
        if ($traHang->trang_thai !== 'cho_duyet') return back()->withErrors(['error' => 'Yêu cầu này đã được xử lý.']);

        DB::beginTransaction();
        try {
            $phieuXuatCu = PhieuXuat::where('ma_don_hang', $traHang->ma_don_hang)->first();
            $maPxCu = $phieuXuatCu ? $phieuXuatCu->ma_phieu_xuat : null;

            // Tìm nhà cung cấp từ chi tiết lô gốc đầu tiên
            $maNcc = null;
            if ($maPxCu) {
                $ctPx = ChiTietPhieuXuat::where('ma_phieu_xuat', $maPxCu)->first();
                if ($ctPx) {
                    $tkCu = TonKho::where('so_lo', $ctPx->so_lo)->where('ma_thuoc', $ctPx->ma_thuoc)->first();
                    if ($tkCu) {
                        $pnCu = PhieuNhap::find($tkCu->ma_phieu_nhap);
                        if ($pnCu) $maNcc = $pnCu->ma_ncc;
                    }
                }
            }

            if (!$maNcc) {
                $nccTraHang = NhaCungCap::firstOrCreate(
                    ['ma_ncc' => 'NCC_TRAHANG'],
                    ['ten_ncc' => 'Khách Hàng Trả Lại', 'so_dien_thoai' => '0000000000', 'dia_chi' => 'Hệ thống tự động', 'trang_thai' => 1]
                );
                $maNcc = $nccTraHang->ma_ncc;
            }

            // 1. Sinh mã phiếu nhập PN_TRA_YYYYMMDD_xxxx
            $prefix = 'PN_TRA_' . date('Ymd') . '_';
            $latest = PhieuNhap::where('ma_phieu_nhap', 'LIKE', $prefix . '%')
                ->orderBy('ma_phieu_nhap', 'desc')
                ->first();
            $newNum = $latest ? ((int) substr($latest->ma_phieu_nhap, -4)) + 1 : 1;
            $maPhieuNhap = $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);

            $phieuNhap = PhieuNhap::create([
                'ma_phieu_nhap' => $maPhieuNhap,
                'ma_ncc' => $maNcc,
                'nguoi_nhap' => auth()->id() ?? 'NV001',
                'ngay_nhap' => now(),
                'tong_tien' => $traHang->tong_tien_hoan_tra,
                'trang_thai_tt' => 'chua_tt', 
                'trang_thai_phieu_nhap' => 'doi_hang_ve', // Đợi hàng về, y như phiếu nhập thường
                'image1' => '',
                'giay_to_lien_quan' => '',
                'tieu_lieu_lien_quan' => "[MA_TRA:{$traHang->ma_tra_hang}] Khách hàng trả hàng đơn " . $traHang->ma_don_hang
            ]);

            // 2. Chuyển chi tiết theo ds xuất (phân bổ số lượng trả dựa trên các lô đã xuất thực tế)
            foreach ($traHang->chiTiet as $ct) {
                $slTraCon = $ct->so_luong_tra;
                
                $chiTietXuats = ChiTietPhieuXuat::where('ma_phieu_xuat', $maPxCu)
                    ->where('ma_thuoc', $ct->ma_thuoc)
                    ->get();

                // Lặp qua các lô của mã thuốc này từ phiếu xuất để "hồi lại"
                foreach ($chiTietXuats as $ctx) {
                    if ($slTraCon <= 0) break;
                    
                    $slPhanBo = min($slTraCon, $ctx->so_luong_xuat);
                    $slTraCon -= $slPhanBo;

                    // Lấy lại chi tiết nhập cũ để có so_lo_sx, so_dang_ky...
                    $ctpnCu = ChiTietPhieuNhap::where('ma_thuoc', $ctx->ma_thuoc)
                        ->where('so_lo', $ctx->so_lo)
                        ->first();

                    $soLoSx = $ctpnCu && $ctpnCu->so_lo_sx ? $ctpnCu->so_lo_sx : 'LSX_UNKNOWN';
                    $ngaySx = $ctpnCu && $ctpnCu->ngay_san_xuat ? $ctpnCu->ngay_san_xuat : now()->toDateString();
                    $soDk   = $ctpnCu && $ctpnCu->so_dang_ky ? $ctpnCu->so_dang_ky : null;

                    ChiTietPhieuNhap::create([
                        'ma_phieu_nhap' => $maPhieuNhap,
                        'ma_thuoc' => $ctx->ma_thuoc,
                        'so_lo' => $ctx->so_lo,         // Giữ y số lô cũ
                        'so_lo_sx' => $soLoSx,          // Giữ y lô sản xuất
                        'ngay_san_xuat' => $ngaySx,
                        'so_dang_ky' => $soDk,
                        'han_su_dung' => $ctx->han_su_dung, // Giữ y HSD thực tế lúc xuất
                        'so_luong_nhap' => $slPhanBo,
                        'so_luong_thuc_te' => 0, // Bằng 0 vì trạng thái đợi hàng về chờ kiểm đếm
                        'don_gia_nhap' => $ct->don_gia_tra,
                        'thanh_tien' => $slPhanBo * $ct->don_gia_tra,
                    ]);

                    TonKho::create([
                        'ma_thuoc' => $ctx->ma_thuoc,
                        'so_lo' => $ctx->so_lo,
                        'ma_phieu_nhap' => $maPhieuNhap,
                        'ngay_san_xuat' => $ngaySx,
                        'ngay_nhap_lo' => null, // Đợi hàng về
                        'han_su_dung' => $ctx->han_su_dung,
                        'so_luong_ton' => 0, 
                        'so_luong_da_xuat' => 0,
                        'trang_thai_lo' => 'cho_duyet', // Đợi thẩm định vào kho
                        'image1' => '',
                    ]);
                }
            }

            // 3. Đổi trạng thái Yêu cầu Trả hàng (KHÔNG tự động hoàn tiền)
            $traHang->trang_thai = 'da_duyet_nhap_kho';
            $traHang->trang_thai_hoan_tien = 'chua_hoan';
            $traHang->ghi_chu_admin = $request->ghi_chu;
            $traHang->nguoi_duyet = auth()->id();
            $traHang->ngay_duyet = now()->toDateString();
            $traHang->save();

            DB::commit();
            return back()->with('success', 'Đã duyệt yêu cầu thành công. Vui lòng vào Thanh toán → "Hoàn trả đơn hàng (KH)" để hoàn tiền cho khách.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi xử lý: ' . $e->getMessage()]);
        }
    }

    /**
     * Thủ công hoàn tiền cho 1 đơn trả hàng (gọi từ trang Payments)
     */
    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'so_tien_tt'   => 'required|numeric|min:1',
            'phuong_thuc_tt' => 'required|string',
            'minh_chung_tt_image' => 'nullable|image|max:2048',
        ]);

        $traHang = KhachTraHang::with('thanhToans')->findOrFail($id);

        if ($traHang->trang_thai !== 'da_duyet_nhap_kho') {
            return back()->withErrors(['error' => 'Đơn trả hàng chưa được duyệt, không thể hoàn tiền.']);
        }

        // Kiểm tra xem hàng đã về đủ chưa
        $phieuNhap = PhieuNhap::where('ma_phieu_nhap', 'LIKE', 'PN_TRA_%')
            ->where('tieu_lieu_lien_quan', 'LIKE', '%' . $traHang->ma_tra_hang . '%')
            ->first();

        if (!$phieuNhap || $phieuNhap->trang_thai_phieu_nhap !== 'da_nhap_kho') {
            return back()->withErrors(['error' => 'Hàng chưa về đủ kho, không thể thực hiện hoàn tiền. Phiếu nhập liên quan phải ở trạng thái "Thành công".']);
        }

        if ($traHang->trang_thai_hoan_tien === 'da_hoan') {
            return back()->withErrors(['error' => 'Đơn này đã hoàn tiền đầy đủ.']);
        }

        DB::beginTransaction();
        try {
            $tongDaHoan = $traHang->thanhToans->sum('so_tien_tt');
            $conLai = $traHang->tong_tien_hoan_tra - $tongDaHoan;
            $soTienTT = floatval($request->so_tien_tt);

            if ($soTienTT > $conLai + 0.01) {
                return back()->withErrors(['error' => 'Số tiền hoàn (' . number_format($soTienTT) . ') vượt quá số tiền còn phải hoàn (' . number_format($conLai) . ').']);
            }

            // Sinh mã thanh toán
            $prefix = 'TTHTK';
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();
            $nextId = 1;
            if ($lastTT && preg_match('/' . $prefix . '(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maTT = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Xử lý ảnh minh chứng
            $imagePath = null;
            if ($request->hasFile('minh_chung_tt_image')) {
                $imagePath = $request->file('minh_chung_tt_image')->store('payments', 'public');
            }

            $conNoMoi = $conLai - $soTienTT;

            // Lưu giao dịch hoàn tiền
            ThanhToan::create([
                'ma_thanh_toan'    => $maTT,
                'loai_thanh_toan'  => 'tra_hang',
                'ma_tra_hang'      => $traHang->ma_tra_hang,
                'tong_tien'        => $traHang->tong_tien_hoan_tra,
                'so_tien_tt'       => $soTienTT,
                'so_tien_con_no'   => max(0, $conNoMoi),
                'trang_thai_tt'    => $conNoMoi <= 0.01 ? 'da_du' : 'con_no',
                'phuong_thuc_tt'   => $request->phuong_thuc_tt,
                'ngay_thanh_toan'  => now(),
                'minh_chung_tt_image' => $imagePath,
                'ghi_chu'          => $request->ghi_chu ?? ('Hoàn tiền đơn trả hàng ' . $traHang->ma_tra_hang),
            ]);

            // Cập nhật trạng thái hoàn tiền của đơn trả
            $traHang->trang_thai_hoan_tien = $conNoMoi <= 0.01 ? 'da_hoan' : 'mot_phan';
            $traHang->save();

            DB::commit();
            return redirect()->route('payments.index', ['tab' => 'tra_hang'])
                ->with('success', 'Đã ghi nhận hoàn tiền ' . number_format($soTienTT) . 'đ cho đơn trả hàng ' . $traHang->ma_tra_hang . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Hoàn tác duyệt đơn trả hàng
     * Chỉ thực hiện khi chưa có hàng thực tế nào về kho
     */
    public function undoApprove($id)
    {
        $traHang = KhachTraHang::findOrFail($id);

        if ($traHang->trang_thai !== 'da_duyet_nhap_kho') {
            return back()->withErrors(['error' => 'Đơn này không ở trạng thái có thể hoàn tác.']);
        }

        // Tìm phiếu nhập liên quan
        $phieuNhap = PhieuNhap::where('ma_phieu_nhap', 'LIKE', 'PN_TRA_%')
            ->where('tieu_lieu_lien_quan', 'LIKE', '%' . $id . '%')
            ->with(['chiTiet', 'cacThanhToan'])
            ->first();

        if ($phieuNhap) {
            // Kiểm tra hàng về
            $received = $phieuNhap->chiTiet->sum('so_luong_thuc_te');
            if ($received > 0) {
                return back()->withErrors(['error' => 'Không thể hoàn tác do đã có hàng được tiếp nhận tại kho.']);
            }

            // Kiểm tra thanh toán (thực tế processRefund đã chặn, nhưng check cho chắc)
            if ($traHang->thanhToans()->count() > 0) {
                return back()->withErrors(['error' => 'Đơn đã có phát sinh hoàn tiền, không thể hoàn tác.']);
            }

            DB::beginTransaction();
            try {
                $maPN = $phieuNhap->ma_phieu_nhap;

                // Xóa Tồn kho khu vực (nếu lỡ có) và Tồn kho (lô ảo mới tạo)
                TonKhoKhuVuc::where('ma_phieu_nhap', $maPN)->delete();
                TonKho::where('ma_phieu_nhap', $maPN)->delete();
                
                // Xóa Chi tiết và Phiếu nhập
                ChiTietPhieuNhap::where('ma_phieu_nhap', $maPN)->delete();
                $phieuNhap->delete();

                // Lùi trạng thái đơn trả
                $traHang->trang_thai = 'cho_duyet';
                $traHang->nguoi_duyet = null;
                $traHang->ngay_duyet = null;
                $traHang->ghi_chu_admin = null;
                $traHang->save();

                DB::commit();
                return redirect()->route('admin.returns.show', $id)->with('success', 'Đã hoàn tác duyệt đơn trả thành công.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Lỗi khi hoàn tác: ' . $e->getMessage()]);
            }
        }

        return back()->withErrors(['error' => 'Không tìm thấy phiếu nhập liên quan để hoàn tác.']);
    }

    /**
     * Form tạo đơn trả hàng (NV bán hàng tạo thay khách hàng)
     */
    public function create()
    {
        // Lấy đơn hàng đã hoàn thành (bao gồm cả những đơn đã từng trả hàng)
        $donHangs = DonHang::with('khachHang')
            ->where('trang_thai_dh', 'da_hoan_thanh')
            ->orderBy('created_at', 'desc')
            ->get();

        $khachHangs = KhachHang::where('trang_thai_tk', 'hoat_dong')->get();

        return view('admin.inventory.returns.create', compact('donHangs', 'khachHangs'));
    }

    /**
     * AJAX: Lấy chi tiết sản phẩm của đơn hàng (để NV xem trước khi tạo đơn trả)
     */
    public function getOrderItems($id)
    {
        $donHang = DonHang::with(['chiTiet.thuoc.donViTinh', 'khachHang'])->findOrFail($id);
        
        $items = $donHang->chiTiet->map(function ($ct) use ($id) {
            // Tính số lượng đã trả trước đó cho sản phẩm này (bỏ qua các yêu cầu đã bị từ chối)
            $slDaTra = ChiTietTraHang::whereHas('khachTraHang', function($q) use ($id) {
                $q->where('ma_don_hang', $id)
                  ->where('trang_thai', '!=', 'tu_choi');
            })->where('ma_thuoc', $ct->ma_thuoc)->sum('so_luong_tra');
            
            $soLuongCoTheTra = max(0, $ct->so_luong - $slDaTra);

            return [
                'ma_thuoc' => $ct->ma_thuoc,
                'ten_thuoc' => $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc,
                'don_vi_tinh' => $ct->thuoc->donViTinh->ten_dvt ?? 'Đơn vị',
                'so_luong_mua' => $ct->so_luong,
                'so_luong_da_tra' => $slDaTra,
                'so_luong_co_the_tra' => $soLuongCoTheTra,
                'don_gia' => $ct->don_gia,
                'thanh_tien' => $ct->so_luong * $ct->don_gia,
            ];
        });

        return response()->json([
            'ma_don_hang' => $donHang->ma_don_hang,
            'ma_kh' => $donHang->ma_kh,
            'ten_kh' => $donHang->khachHang->ten_kh ?? 'N/A',
            'tong_tien' => $donHang->tong_tien,
            'items' => $items,
        ]);
    }

    /**
     * Lưu đơn trả hàng do NV bán hàng tạo thay khách hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_don_hang' => 'required|exists:don_hang,ma_don_hang',
            'ly_do_chung' => 'required|string|max:500',
            'minh_chung_image' => 'nullable|image|max:5120',
            'items' => 'required|array|min:1',
            'items.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'items.*.so_luong_tra' => 'required|integer|min:0',
        ], [
            'ly_do_chung.required' => 'Vui lòng nhập lý do trả hàng.',
            'items.required' => 'Vui lòng chọn sản phẩm cần trả.',
        ]);

        $donHang = DonHang::with('chiTiet')->findOrFail($request->ma_don_hang);

        if ($donHang->trang_thai_dh !== 'da_hoan_thanh') {
            return back()->withInput()->withErrors(['error' => 'Chỉ có thể tạo đơn trả cho đơn hàng đã hoàn thành.']);
        }

        // Cho phép trả nhiều lần, miễn là số lượng trả không vượt quá số lượng mua trừ đi số lượng đã trả trước đó

        $processedItems = [];
        $totalRefund = 0;

        foreach ($request->items as $item) {
            $slTra = intval($item['so_luong_tra']);
            if ($slTra <= 0) continue;

            // Kiểm tra số lượng tối đa
            $chiTietMua = $donHang->chiTiet->where('ma_thuoc', $item['ma_thuoc'])->first();
            if (!$chiTietMua) continue;

            // Tính số lượng đã trả trước đó
            $slDaTra = ChiTietTraHang::whereHas('khachTraHang', function($q) use ($donHang) {
                $q->where('ma_don_hang', $donHang->ma_don_hang)
                  ->where('trang_thai', '!=', 'tu_choi');
            })->where('ma_thuoc', $item['ma_thuoc'])->sum('so_luong_tra');
            
            $soLuongCoTheTra = max(0, $chiTietMua->so_luong - $slDaTra);

            if ($slTra > $soLuongCoTheTra) {
                return back()->withInput()->withErrors([
                    'error' => 'Số lượng trả của "' . ($chiTietMua->thuoc->ten_thuoc ?? $item['ma_thuoc']) 
                        . '" vượt quá số lượng có thể trả (' . $soLuongCoTheTra . ').'
                ]);
            }

            $donGia = $chiTietMua->don_gia;
            $thanhTien = $slTra * $donGia;
            $totalRefund += $thanhTien;

            $processedItems[] = [
                'ma_thuoc' => $item['ma_thuoc'],
                'so_luong_tra' => $slTra,
                'don_gia_tra' => $donGia,
                'thanh_tien' => $thanhTien,
                'ly_do_chi_tiet' => $item['ly_do'] ?? '',
            ];
        }

        if (empty($processedItems)) {
            return back()->withInput()->withErrors(['error' => 'Vui lòng nhập số lượng trả lớn hơn 0 cho ít nhất 1 sản phẩm.']);
        }

        DB::beginTransaction();
        try {
            $maTraHang = 'TH_' . date('Ymd_His');

            // Xử lý ảnh minh chứng
            $imagePath = null;
            if ($request->hasFile('minh_chung_image')) {
                $file = $request->file('minh_chung_image');
                $name = time() . '_minhchung.' . $file->extension();
                $file->move(public_path('uploads/returns'), $name);
                $imagePath = 'uploads/returns/' . $name;
            }

            $khachTra = KhachTraHang::create([
                'ma_tra_hang' => $maTraHang,
                'ma_don_hang' => $donHang->ma_don_hang,
                'ma_kh' => $donHang->ma_kh,
                'ngay_yeu_cau' => now()->toDateString(),
                'ly_do_chung' => $request->ly_do_chung,
                'tong_tien_hoan_tra' => $totalRefund,
                'trang_thai' => 'cho_duyet',
                'nguoi_tao' => auth()->id(),
                'minh_chung_image' => $imagePath,
            ]);

            foreach ($processedItems as $item) {
                ChiTietTraHang::create(array_merge($item, ['ma_tra_hang' => $maTraHang]));
            }

            DB::commit();
            return redirect()->route('admin.returns.index')
                ->with('success', 'Đã tạo yêu cầu trả hàng thành công! Mã: ' . $maTraHang);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi tạo đơn trả hàng: ' . $e->getMessage()]);
        }
    }
}
