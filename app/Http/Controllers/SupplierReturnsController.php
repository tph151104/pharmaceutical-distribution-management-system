<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhieuTraNcc;
use App\Models\ChiTietPhieuTraNcc;
use App\Models\TonKhoKhuVuc;
use App\Models\TonKho;
use App\Models\LichSuDichChuyenKho;
use App\Models\ThanhToan;
use App\Models\NhaCungCap;
use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SupplierReturnsController extends Controller
{
    public function index(Request $request)
    {
        $query = PhieuTraNcc::with(['nhaCungCap', 'nguoiTao']);

        if ($request->filled('ma_phieu')) {
            $query->where('ma_phieu_tra_ncc', 'like', '%' . $request->ma_phieu . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_tao', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_tao', '<=', $request->den_ngay);
        }

        $phieuTras = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.inventory.supplier-returns.index', compact('phieuTras'));
    }

    public function create(Request $request)
    {
        $thuoc = null;
        $tonKhoKv = null;
        
        if ($request->filled(['ma_thuoc', 'so_lo', 'ma_phieu_nhap'])) {
            $tonKhoKv = TonKhoKhuVuc::with(['thuoc', 'phieuNhap.nhaCungCap'])
                ->where('ma_thuoc', $request->ma_thuoc)
                ->where('so_lo', $request->so_lo)
                ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                ->where('ma_khu_vuc', 'KV04_CHO_XU_LY')
                ->first();
                
            if (!$tonKhoKv) {
                return redirect()->route('transfers.index')->withErrors(['error' => 'Không tìm thấy lô hàng trong Kho chờ xử lý.']);
            }
        } else {
            return redirect()->route('transfers.index')->withErrors(['error' => 'Vui lòng chọn lô hàng từ khu vực Chờ xử lý.']);
        }

        return view('admin.inventory.supplier-returns.create', compact('tonKhoKv'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_ncc' => 'required|exists:nha_cung_cap,ma_ncc',
            'ma_thuoc' => 'required',
            'ma_phieu_nhap' => 'required',
            'so_lo' => 'required',
            'so_luong_tra' => 'required|integer|min:1',
            'ly_do_tra' => 'required|string',
            'ghi_chu' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $tonKhoKv = TonKhoKhuVuc::where('ma_thuoc', $request->ma_thuoc)
                ->where('so_lo', $request->so_lo)
                ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                ->where('ma_khu_vuc', 'KV04_CHO_XU_LY')
                ->first();

            if (!$tonKhoKv || $tonKhoKv->so_luong < $request->so_luong_tra) {
                throw new \Exception('Số lượng tồn trong kho chờ xử lý không đủ.');
            }

            // Tính đơn giá từ phiếu nhập
            $donGia = 0;
            $ctPhieuNhap = ChiTietPhieuNhap::where('ma_phieu_nhap', $request->ma_phieu_nhap)
                ->where('ma_thuoc', $request->ma_thuoc)
                ->where('so_lo', $request->so_lo)
                ->first();
            if ($ctPhieuNhap) {
                $donGia = $ctPhieuNhap->don_gia_nhap;
            }

            $thanhTien = $donGia * $request->so_luong_tra;

            $todayStr = now()->format('Ymd');
            $lastPhieu = PhieuTraNcc::where('ma_phieu_tra_ncc', 'like', "PTNCC_{$todayStr}_%")
                ->orderBy('ma_phieu_tra_ncc', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastPhieu) {
                $lastMa = $lastPhieu->ma_phieu_tra_ncc;
                $parts = explode('_', $lastMa);
                $lastNumber = (int) end($parts);
                $nextNumber = $lastNumber + 1;
            }
            $maPhieu = "PTNCC_{$todayStr}_" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $phieuTra = PhieuTraNcc::create([
                'ma_phieu_tra_ncc' => $maPhieu,
                'ma_ncc' => $request->ma_ncc,
                'nguoi_tao' => auth()->user()->ma_nguoi_dung,
                'ngay_tao' => now(),
                'tong_tien' => $thanhTien,
                'trang_thai' => 'cho_duyet',
                'ly_do_tra' => $request->ly_do_tra,
                'ghi_chu' => $request->ghi_chu,
            ]);

            ChiTietPhieuTraNcc::create([
                'ma_phieu_tra_ncc' => $maPhieu,
                'ma_thuoc' => $request->ma_thuoc,
                'ma_phieu_nhap' => $request->ma_phieu_nhap,
                'so_lo' => $request->so_lo,
                'so_luong_tra' => $request->so_luong_tra,
                'don_gia' => $donGia,
                'thanh_tien' => $thanhTien,
            ]);

            DB::commit();
            return redirect()->route('supplier-returns.index')->with('success', 'Tạo phiếu trả nhà cung cấp thành công (Chờ duyệt).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi tạo phiếu: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $phieuTra = PhieuTraNcc::with(['nhaCungCap', 'nguoiTao', 'chiTiet.thuoc'])->findOrFail($id);
        return view('admin.inventory.supplier-returns.show', compact('phieuTra'));
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $phieuTra = PhieuTraNcc::lockForUpdate()->findOrFail($id);
            
            if ($phieuTra->trang_thai !== 'cho_duyet') {
                throw new \Exception('Chỉ có thể duyệt phiếu đang ở trạng thái chờ duyệt.');
            }

            foreach ($phieuTra->chiTiet as $ct) {
                // Trừ tồn kho khu vực KV04
                $tkv = TonKhoKhuVuc::where('ma_thuoc', $ct->ma_thuoc)
                    ->where('so_lo', $ct->so_lo)
                    ->where('ma_phieu_nhap', $ct->ma_phieu_nhap)
                    ->where('ma_khu_vuc', 'KV04_CHO_XU_LY')
                    ->first();
                
                if (!$tkv || $tkv->so_luong < $ct->so_luong_tra) {
                    throw new \Exception("Số lượng lô {$ct->so_lo} trong Kho chờ xử lý không đủ để trả.");
                }

                $tkv->so_luong -= $ct->so_luong_tra;
                if ($tkv->so_luong <= 0) {
                    $tkv->delete();
                } else {
                    $tkv->save();
                }

                // Trừ tổng tồn kho bảng ton_kho
                $tonKho = TonKho::where('ma_thuoc', $ct->ma_thuoc)
                    ->where('so_lo', $ct->so_lo)
                    ->where('ma_phieu_nhap', $ct->ma_phieu_nhap)
                    ->first();

                if ($tonKho) {
                    $tonKho->so_luong_ton -= $ct->so_luong_tra;
                    if ($tonKho->so_luong_ton < 0) $tonKho->so_luong_ton = 0;
                    
                    // Kiểm tra xem còn ở kho nào khác không
                    $hangReusables = TonKhoKhuVuc::where('ma_thuoc', $ct->ma_thuoc)
                        ->where('so_lo', $ct->so_lo)
                        ->where('ma_khu_vuc', '!=', 'KV05_LOAI_BO')
                        ->sum('so_luong');
                    
                    if ($hangReusables == 0) {
                        $tonKho->trang_thai_lo = 'ngung_ban';
                    }
                    $tonKho->save();
                }

                // Ghi log dịch chuyển: KV04 -> NCC (ngoài kho)
                LichSuDichChuyenKho::create([
                    'ma_phieu_chuyen' => 'CHCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                    'ma_thuoc' => $ct->ma_thuoc,
                    'ma_phieu_nhap' => $ct->ma_phieu_nhap,
                    'so_lo' => $ct->so_lo,
                    'tu_khu_vuc' => 'KV04_CHO_XU_LY',
                    'den_khu_vuc' => 'XUAT_TRA_NCC',
                    'so_luong_chuyen' => $ct->so_luong_tra,
                    'nguoi_thuc_hien' => auth()->user()->ma_nguoi_dung,
                    'ngay_chuyen' => Carbon::now(),
                    'ly_do_chuyen' => 'Trả hàng NCC - Phiếu ' . $phieuTra->ma_phieu_tra_ncc,
                ]);
            }

            // Tạo bản ghi hoàn tiền (ThanhToan)
            if ($phieuTra->tong_tien > 0) {
                $prefix = 'TTHTNCC';
                $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();
                $nextId = 1;
                if ($lastTT && preg_match('/' . $prefix . '(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                    $nextId = intval($matches[1]) + 1;
                }
                $maTT = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);

                ThanhToan::create([
                    'ma_thanh_toan' => $maTT,
                    'loai_thanh_toan' => 'tra_hang',
                    'ma_phieu_tra_ncc' => $phieuTra->ma_phieu_tra_ncc,
                    'tong_tien' => $phieuTra->tong_tien,
                    'so_tien_tt' => 0,
                    'so_tien_con_no' => $phieuTra->tong_tien,
                    'trang_thai_tt' => 'con_no',
                    'ngay_thanh_toan' => Carbon::now(),
                    'ghi_chu' => 'Tiền NCC cần hoàn do trả hàng (Phiếu ' . $phieuTra->ma_phieu_tra_ncc . ')',
                ]);
            }

            $phieuTra->trang_thai = 'da_duyet';
            $phieuTra->save();

            DB::commit();
            return redirect()->route('supplier-returns.show', $id)->with('success', 'Đã duyệt phiếu trả NCC thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi duyệt phiếu: ' . $e->getMessage()]);
        }
    }

    public function complete($id)
    {
        DB::beginTransaction();
        try {
            $phieuTra = PhieuTraNcc::findOrFail($id);
            if ($phieuTra->trang_thai !== 'da_duyet') {
                throw new \Exception('Chỉ có thể hoàn thành phiếu đã được duyệt.');
            }
            $phieuTra->trang_thai = 'da_hoan_thanh';
            $phieuTra->save();
            DB::commit();
            return redirect()->route('supplier-returns.show', $id)->with('success', 'Đã đánh dấu hoàn thành trả hàng NCC.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function cancel($id, Request $request)
    {
        $request->validate(['ly_do_huy' => 'required|string']);
        
        DB::beginTransaction();
        try {
            $phieuTra = PhieuTraNcc::findOrFail($id);
            if ($phieuTra->trang_thai !== 'cho_duyet') {
                throw new \Exception('Chỉ có thể hủy phiếu đang ở trạng thái chờ duyệt.');
            }
            $phieuTra->trang_thai = 'da_huy';
            $phieuTra->ghi_chu = $phieuTra->ghi_chu . "\nLý do hủy: " . $request->ly_do_huy;
            $phieuTra->save();
            DB::commit();
            return redirect()->route('supplier-returns.show', $id)->with('success', 'Đã hủy phiếu trả NCC.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi hủy phiếu: ' . $e->getMessage()]);
        }
    }

    public function processRefund($id, Request $request)
    {
        $request->validate([
            'so_tien_tt' => 'required|numeric|min:1',
            'phuong_thuc_tt' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'minh_chung_tt_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $phieuTra = PhieuTraNcc::withSum('thanhToans as so_tien_da_nhan', 'so_tien_tt')
                ->lockForUpdate()->findOrFail($id);
            
            $conLai = $phieuTra->tong_tien - ($phieuTra->so_tien_da_nhan ?? 0);
            $soTienTT = floatval($request->so_tien_tt);

            if ($soTienTT > $conLai + 0.01) {
                return back()->withErrors(['error' => 'Số tiền nhận (' . number_format($soTienTT) . ') vượt quá số tiền còn nợ (' . number_format($conLai) . ').']);
            }

            // Sinh mã thanh toán
            $prefix = 'TTHTNCC';
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();
            $nextId = 1;
            if ($lastTT && preg_match('/' . $prefix . '(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maTT = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Xử lý ảnh minh chứng
            $imagePath = null;
            if ($request->hasFile('minh_chung_tt_image')) {
                $file = $request->file('minh_chung_tt_image');
                $name = time() . '_tra_ncc.' . $file->extension();
                $file->move(public_path('uploads/payments'), $name);
                $imagePath = 'uploads/payments/' . $name;
            }

            $conNoMoi = $conLai - $soTienTT;

            // Lưu giao dịch hoàn tiền
            ThanhToan::create([
                'ma_thanh_toan'    => $maTT,
                'loai_thanh_toan'  => 'tra_hang',
                'ma_phieu_tra_ncc' => $phieuTra->ma_phieu_tra_ncc,
                'tong_tien'        => $phieuTra->tong_tien,
                'so_tien_tt'       => $soTienTT,
                'so_tien_con_no'   => max(0, $conNoMoi),
                'trang_thai_tt'    => $conNoMoi <= 0.01 ? 'da_du' : 'con_no',
                'phuong_thuc_tt'   => $request->phuong_thuc_tt,
                'ngay_thanh_toan'  => now(),
                'minh_chung_tt_image' => $imagePath,
                'ghi_chu'          => $request->ghi_chu ?? ('Nhận tiền hoàn từ NCC cho phiếu ' . $phieuTra->ma_phieu_tra_ncc),
            ]);

            DB::commit();
            return redirect()->route('payments.index', ['tab' => 'tra_hang'])
                ->with('success', 'Đã ghi nhận nhận tiền hoàn ' . number_format($soTienTT) . 'đ cho phiếu trả NCC ' . $phieuTra->ma_phieu_tra_ncc . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
}
