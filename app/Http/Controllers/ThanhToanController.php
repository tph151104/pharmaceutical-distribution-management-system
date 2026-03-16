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
        // Lấy các phiếu nhập chưa thanh toán hết (chua_tt hoặc mot_phan)
        $phieuNhaps = PhieuNhap::with('nhaCungCap')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_nhap', 'desc')
            ->get()
            ->map(function ($pn) {
                $pn->so_tien_con_no = $pn->tong_tien - ($pn->so_tien_da_tra ?? 0);
                return $pn;
            });

        // 2. Công nợ Khách Hàng (Phải thu)
        // Lấy các phiếu xuất chưa thanh toán hết
        $phieuXuats = PhieuXuat::with('khachHang')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_xuat', 'desc')
            ->get()
            ->map(function ($px) {
                $px->so_tien_con_no = $px->tong_tien - ($px->so_tien_da_tra ?? 0);
                return $px;
            });

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
            'minh_chung_tt_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $loai = $request->loai_thanh_toan;
            $maPhieu = $request->ma_phieu;
            $soTienTT = floatval($request->so_tien_tt);

            // Tìm phiếu và tính nợ hiện tại
            if ($loai == 'nhap') {
                $phieu = PhieuNhap::findOrFail($maPhieu);
                $tongDaTra = ThanhToan::where('ma_phieu_nhap', $maPhieu)->sum('so_tien_tt');
            } else {
                $phieu = PhieuXuat::findOrFail($maPhieu);
                $tongDaTra = ThanhToan::where('ma_phieu_xuat', $maPhieu)->sum('so_tien_tt');
            }

            $tongTienCT = floatval($phieu->tong_tien);
            $soTienConNoHienTai = $tongTienCT - $tongDaTra;

            // Kiểm tra ràng buộc: không vượt quá số nợ
            if ($soTienTT > $soTienConNoHienTai + 0.01) { 
                return back()->withInput()->withErrors(['error' => 'Số tiền thanh toán (' . number_format($soTienTT) . ') không được lớn hơn số tiền còn nợ (' . number_format($soTienConNoHienTai) . ').']);
            }

            // Sinh mã thanh toán
            $prefix = $loai == 'nhap' ? 'TTN' : 'TTX';
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();//tìm mã thanh toán cuối cùng

            $nextId = 1;
            if ($lastTT && preg_match('/' . $prefix . '(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1; //tự động tăng mã thanh toán lên 1
            }

            $maTT = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);//tạo mã thanh toán mới

            // Xử lý ảnh
            $imagePath = null;
            if ($request->hasFile('minh_chung_tt_image')) {
                $imagePath = $request->file('minh_chung_tt_image')->store('payments', 'public');
            }

            $conNoMoi = $soTienConNoHienTai - $soTienTT;

            // Lưu lịch sử
            ThanhToan::create([
                'ma_thanh_toan' => $maTT,
                'loai_thanh_toan' => $loai,
                'ma_phieu_nhap' => $loai == 'nhap' ? $maPhieu : null,
                'ma_phieu_xuat' => $loai == 'xuat' ? $maPhieu : null,
                'tong_tien' => $tongTienCT,
                'so_tien_tt' => $soTienTT,
                'so_tien_con_no' => $conNoMoi,
                'trang_thai_tt' => $conNoMoi <= 0.01 ? 'da_du' : 'con_no',
                'phuong_thuc_tt' => $request->phuong_thuc_tt,
                'ngay_thanh_toan' => now(),
                'minh_chung_tt_image' => $imagePath,
                'ghi_chu' => $request->ghi_chu,
            ]);

            // Cập nhật trạng thái phiếu gốc
            $phieu->update([
                'trang_thai_tt' => $conNoMoi <= 0.01 ? 'da_tt' : 'mot_phan'
            ]);

            DB::commit();
            return redirect()->route('payments.index')->with('success', 'Đã lưu giao dịch thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
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
