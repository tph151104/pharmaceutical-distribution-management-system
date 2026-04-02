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

        return view('admin.inventory.payments.index', compact('phieuNhaps', 'phieuXuats'));
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

        return view('admin.inventory.payments.show', compact('thanhToan'));
    }

    /**
     * Lịch sử thanh toán
     */
    public function history(Request $request)
    {
        $tab = $request->get('tab', 'xuat'); // xuat hoặc nhap
        $groupBy = $request->get('group_by', 'false');
        $search = $request->get('search');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = ThanhToan::where('loai_thanh_toan', $tab);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ma_thanh_toan', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_nhap', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_xuat', 'like', "%{$search}%");
            });
        }
        if ($fromDate) {
            $query->whereDate('ngay_thanh_toan', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('ngay_thanh_toan', '<=', $toDate);
        }

        if ($tab === 'nhap') {
            $query->with('phieuNhap.nhaCungCap');
        } else {
            $query->with('phieuXuat.khachHang');
        }

        $query->orderBy('ngay_thanh_toan', 'desc');

        // Phân trang bằng pagination, nếu group by thì group trên tập kết quả trang hiện tại hoặc dùng collection
        if ($groupBy === 'true') {
            // Gom nhóm tất cả kết quả thỏa mãn filter để tránh vỡ group khi pagination
            $unpaginated = $query->get();
            $transactions = $unpaginated->groupBy($tab === 'nhap' ? 'ma_phieu_nhap' : 'ma_phieu_xuat');
        } else {
            $transactions = $query->paginate(20)->withQueryString();
        }

        return view('admin.inventory.payments.history', compact('transactions', 'tab', 'groupBy', 'search', 'fromDate', 'toDate'));
    }

    /**
     * Xuất Excel Lịch sử thanh toán
     */
    public function exportHistory(Request $request)
    {
        $tab = $request->get('tab', 'xuat');
        $search = $request->get('search');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = ThanhToan::where('loai_thanh_toan', $tab);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ma_thanh_toan', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_nhap', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_xuat', 'like', "%{$search}%");
            });
        }
        if ($fromDate) {
            $query->whereDate('ngay_thanh_toan', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('ngay_thanh_toan', '<=', $toDate);
        }

        if ($tab === 'nhap') {
            $query->with('phieuNhap.nhaCungCap');
        } else {
            $query->with('phieuXuat.khachHang');
        }

        $transactions = $query->orderBy('ngay_thanh_toan', 'asc')->get();

        $fileName = 'Lich_Su_Thanh_Toan_' . ($tab == 'nhap' ? 'Phai_Tra' : 'Phai_Thu') . '_' . date('Y_m_d_H_i') . '.xls';
        return response(view('admin.inventory.payments.history_export', compact('transactions', 'tab')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
    /**
     * Xuất Excel Công nợ Phải trả (NCC)
     */
    public function exportSuppliers()
    {
        $phieuNhaps = PhieuNhap::with('nhaCungCap')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_nhap', 'desc')
            ->get()
            ->map(function ($pn) {
                $pn->so_tien_con_no = $pn->tong_tien - ($pn->so_tien_da_tra ?? 0);
                return $pn;
            });

        $fileName = 'Cong_No_Phai_Tra_NCC_' . date('Y_m_d_H_i') . '.xls';
        return response(view('admin.inventory.payments.export_debts', ['phieus' => $phieuNhaps, 'type' => 'nhap']))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Xuất Excel Công nợ Phải thu (Khách Hàng)
     */
    public function exportCustomers()
    {
        $phieuXuats = PhieuXuat::with('khachHang')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_xuat', 'desc')
            ->get()
            ->map(function ($px) {
                $px->so_tien_con_no = $px->tong_tien - ($px->so_tien_da_tra ?? 0);
                return $px;
            });

        $fileName = 'Cong_No_Phai_Thu_KH_' . date('Y_m_d_H_i') . '.xls';
        return response(view('admin.inventory.payments.export_debts', ['phieus' => $phieuXuats, 'type' => 'xuat']))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
