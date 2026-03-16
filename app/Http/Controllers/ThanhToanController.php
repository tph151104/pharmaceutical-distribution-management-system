<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThanhToan;
use App\Models\PhieuNhap;
use App\Models\PhieuXuat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThanhToanController extends Controller
{
    /**
     * Hiển thị danh sách công nợ (cả Phải thu và Phải trả).
     */
    public function index(Request $request)
    {
        // 1. Công nợ Nhà Cung Cấp (Phải trả)
        // Lấy tất cả các phiếu nhập chưa thanh toán hết.
        $phieuNhaps = PhieuNhap::with('nhaCungCap')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            // ->whereIn('trang_thai_phieu_nhap', ['da_nhap_kho']) // Tùy nghiệp vụ, có thể chỉ thanh toán khi đã nhập kho. Bỏ comment nếu cần.
            ->orderBy('ngay_nhap', 'desc')
            ->get();

        foreach ($phieuNhaps as $pn) {
            $tongDaTra = ThanhToan::where('ma_phieu_nhap', $pn->ma_phieu_nhap)->sum('so_tien_tt');
            $pn->so_tien_da_tra = $tongDaTra;
            $pn->so_tien_con_no = $pn->tong_tien - $tongDaTra;
        }

        // 2. Công nợ Khách Hàng (Phải thu)
        // Lấy tất cả các phiếu xuất chưa thanh toán hết.
        $phieuXuats = PhieuXuat::with('khachHang')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_xuat', 'desc')
            ->get();

        foreach ($phieuXuats as $px) {
            $tongDaTra = ThanhToan::where('ma_phieu_xuat', $px->ma_phieu_xuat)->sum('so_tien_tt');
            $px->so_tien_da_tra = $tongDaTra;
            $px->so_tien_con_no = $px->tong_tien - $tongDaTra;
        }

        return view('payments.index', compact('phieuNhaps', 'phieuXuats'));
    }

    /**
     * Store a newly created payment transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loai_thanh_toan' => 'required|in:nhap,xuat',
            'ma_phieu' => 'required|string',
            'so_tien_tt' => 'required|numeric|min:1',
            'phuong_thuc_tt' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $loai = $request->loai_thanh_toan;
            $maPhieu = $request->ma_phieu;
            $soTienTT = floatval($request->so_tien_tt);

            $tongTienCT = 0;
            $tongDaTra = 0;
            $phieu = null;

            if ($loai == 'nhap') {
                $phieu = PhieuNhap::findOrFail($maPhieu);
                $tongTienCT = floatval($phieu->tong_tien);
                $tongDaTra = ThanhToan::where('ma_phieu_nhap', $maPhieu)->sum('so_tien_tt');
            } else {
                $phieu = PhieuXuat::findOrFail($maPhieu);
                $tongTienCT = floatval($phieu->tong_tien);
                $tongDaTra = ThanhToan::where('ma_phieu_xuat', $maPhieu)->sum('so_tien_tt');
            }

            $soTienConNoHienTai = $tongTienCT - $tongDaTra;

            if ($soTienTT > $soTienConNoHienTai) {
                return back()->withInput()->withErrors(['error' => 'Số tiền thanh toán (' . number_format($soTienTT) . ') không được lớn hơn số tiền còn nợ hiện tại (' . number_format($soTienConNoHienTai) . ').']);
            }

            // Tạo mã thanh toán tự động TTN001 (nhập) hoặc TTX001 (xuất)
            $prefix = $loai == 'nhap' ? 'TTN' : 'TTX';
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')
                ->orderBy('ma_thanh_toan', 'desc')
                ->first();
            
            $nextId = 1;
            if ($lastTT && preg_match('/^' . $prefix . '(\d+)$/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maTT = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $conNoMoi = $soTienConNoHienTai - $soTienTT;

            ThanhToan::create([
                'ma_thanh_toan' => $maTT,
                'loai_thanh_toan' => $loai,
                'ma_phieu_nhap' => $loai == 'nhap' ? $maPhieu : null,
                'ma_phieu_xuat' => $loai == 'xuat' ? $maPhieu : null,
                'tong_tien' => $tongTienCT,
                'so_tien_tt' => $soTienTT,
                'so_tien_con_no' => $conNoMoi,
                'trang_thai_tt' => $conNoMoi <= 0 ? 'da_du' : 'con_no',
                'phuong_thuc_tt' => $request->phuong_thuc_tt,
                'ngay_thanh_toan' => Carbon::now(),
                'ghi_chu' => $request->ghi_chu,
            ]);

            // Cập nhật trạng thái phiếu nhập/xuất
            $trangThaiMoi = $conNoMoi <= 0 ? 'da_tt' : 'mot_phan';
            $phieu->update(['trang_thai_tt' => $trangThaiMoi]);

            DB::commit();

            return redirect()->route('payments.index')->with('success', 'Đã lưu lịch sử thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi thanh toán: ' . $e->getMessage()]);
        }
    }

    /**
     * Xem chi tiết / in biên lai
     */
    public function show($id)
    {
        $thanhToan = ThanhToan::findOrFail($id);
        
        if ($thanhToan->loai_thanh_toan == 'nhap') {
            $thanhToan->load('phieuNhap.nhaCungCap');
        } else {
            $thanhToan->load('phieuXuat.khachHang');
        }

        return view('payments.show', compact('thanhToan'));
    }

    /**
     * Lịch sử thanh toán
     */
    public function history()
    {
        $transactions = ThanhToan::orderBy('created_at', 'desc')->paginate(20);
        return view('payments.history', compact('transactions'));
    }
}
