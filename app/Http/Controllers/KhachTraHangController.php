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
use Illuminate\Support\Facades\DB;
use App\Services\InventoryLogService;

class KhachTraHangController extends Controller
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
        return view('admin.inventory.returns.show', compact('traHang'));
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
            // Đảm bảo có một Nhà cung cấp (Nhập trả) để không bị lỗi ma_ncc null
            $nccTraHang = \App\Models\NhaCungCap::firstOrCreate(
                ['ma_ncc' => 'NCC_TRAHANG'],
                [
                    'ten_ncc' => 'Khách Hàng Trả Lại',
                    'so_dien_thoai' => '0000000000',
                    'dia_chi' => 'Hệ thống tự động',
                    'trang_thai' => 1
                ]
            );

            // 1. Tạo Phiếu Nhập đặc biệt mang tính chất Trả hàng
            $maPhieuNhap = 'PN_TRA_' . time();
            $phieuNhap = PhieuNhap::create([
                'ma_phieu_nhap' => $maPhieuNhap,
                'ma_ncc' => $nccTraHang->ma_ncc,
                'nguoi_nhap' => auth()->id() ?? 'NV001',
                'ngay_nhap' => now(),
                'tong_tien' => $traHang->tong_tien_hoan_tra,
                'trang_thai_tt' => 'da_tt', 
                'trang_thai_phieu_nhap' => 'da_nhap_kho',
                'image1' => '', 
                'image2' => '',
                'giay_to_lien_quan' => '',
                'tieu_lieu_lien_quan' => 'Khách hàng trả hàng đơn ' . $traHang->ma_don_hang
            ]);

            // 2. Chuyển hàng vào KV04_CHO_XU_LY
            $soLoTraSuffix = date('Ymd_His');
            foreach ($traHang->chiTiet as $ct) {
                // Dùng chung 1 biến số lô để tránh lệch dữ liệu
                $soLoTra = 'LO_TRA_' . $soLoTraSuffix . '_' . $ct->ma_thuoc;

                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'so_lo_sx' => 'LSX_TRA_' . $ct->ma_thuoc,
                    'ngay_san_xuat' => now()->toDateString(),
                    'so_dang_ky' => 'DK_TRA',
                    'han_su_dung' => now()->addYear()->toDateString(),
                    'so_luong_nhap' => $ct->so_luong_tra,
                    'so_luong_thuc_te' => $ct->so_luong_tra,
                    'don_gia_nhap' => $ct->don_gia_tra,
                    'thanh_tien' => $ct->thanh_tien,
                ]);

                TonKho::create([
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ngay_san_xuat' => now()->toDateString(),
                    'ngay_nhap_lo' => now()->toDateString(),
                    'han_su_dung' => now()->addYear()->toDateString(),
                    'so_luong_ton' => $ct->so_luong_tra,
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet',
                    'image1' => '',
                    'image2' => '',
                    'image3' => '',
                ]);

                TonKhoKhuVuc::create([
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ma_khu_vuc' => 'KV04_CHO_XU_LY',
                    'so_luong' => $ct->so_luong_tra
                ]);

                InventoryLogService::logMovement(
                    $ct->ma_thuoc,
                    $soLoTra,
                    auth()->id() ?? 'NV001',
                    $maPhieuNhap,
                    'nhap', // Sử dụng giá trị enum hợp lệ
                    'phieu_nhap', // Sử dụng giá trị enum hợp lệ
                    $ct->so_luong_tra,
                    0,
                    $ct->so_luong_tra,
                    0,
                    '[HÀNG TRẢ VỀ] Đã tự động cách ly vào KV04_CHO_XU_LY để chờ đánh giá lại.'
                );
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
            $prefix = 'TTR';
            $lastTT = \App\Models\ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();
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
            \App\Models\ThanhToan::create([
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
}
