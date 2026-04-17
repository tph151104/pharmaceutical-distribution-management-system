<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThanhToan;
use App\Models\PhieuNhap;
use App\Models\PhieuXuat;
use App\Models\KhachTraHang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentsController extends Controller
{
    /**
     * Hiển thị danh sách công nợ (cả Phải thu, Phải trả và Hoàn trả đơn hàng KH).
     */
    public function index(Request $request)
    {
        // 1. Công nợ Nhà Cung Cấp (Phải trả)
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
        $phieuXuats = PhieuXuat::with('khachHang')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan'])
            ->orderBy('ngay_xuat', 'desc')
            ->get()
            ->map(function ($px) {
                $px->so_tien_con_no = $px->tong_tien - ($px->so_tien_da_tra ?? 0);
                return $px;
            });

        // 3. Hoàn trả đơn hàng cho KH (đã duyệt, chưa hoàn tiền đủ)
        $donTraHangs = KhachTraHang::with(['khachHang', 'donHang'])
            ->withSum('thanhToans as so_tien_da_hoan', 'so_tien_tt')
            ->where('trang_thai', 'da_duyet_nhap_kho')
            ->whereIn('trang_thai_hoan_tien', ['chua_hoan', 'mot_phan'])
            ->orderBy('ngay_duyet', 'desc')
            ->get()
            ->map(function ($dth) {
                $dth->so_tien_con_hoan = $dth->tong_tien_hoan_tra - ($dth->so_tien_da_hoan ?? 0);
                return $dth;
            });

        $activeTab = request('tab', 'supplier');
        return view('admin.inventory.payments.index', compact('phieuNhaps', 'phieuXuats', 'donTraHangs', 'activeTab'));
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

            if ($soTienTT > $soTienConNoHienTai + 0.01) {
                return back()->withInput()->withErrors(['error' => 'Số tiền thanh toán (' . number_format($soTienTT) . ') không được lớn hơn số tiền còn nợ (' . number_format($soTienConNoHienTai) . ').']);
            }

            $prefix = $loai == 'nhap' ? 'TTN' : 'TTX';
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', $prefix . '%')->orderBy('ma_thanh_toan', 'desc')->first();

            $nextId = 1;
            if ($lastTT && preg_match('/' . $prefix . '(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }

            $maTT = $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $imagePath = null;
            if ($request->hasFile('minh_chung_tt_image')) {
                $file = $request->file('minh_chung_tt_image');
                $name = time() . '_giayphep.' . $file->extension();
                $file->move(public_path('uploads/payments'), $name);
                $imagePath = 'uploads/payments/' . $name;
            }

            $conNoMoi = $soTienConNoHienTai - $soTienTT;

            ThanhToan::create([
                'ma_thanh_toan'    => $maTT,
                'loai_thanh_toan'  => $loai,
                'ma_phieu_nhap'    => $loai == 'nhap' ? $maPhieu : null,
                'ma_phieu_xuat'    => $loai == 'xuat' ? $maPhieu : null,
                'tong_tien'        => $tongTienCT,
                'so_tien_tt'       => $soTienTT,
                'so_tien_con_no'   => $conNoMoi,
                'trang_thai_tt'    => $conNoMoi <= 0.01 ? 'da_du' : 'con_no',
                'phuong_thuc_tt'   => $request->phuong_thuc_tt,
                'ngay_thanh_toan'  => now(),
                'minh_chung_tt_image' => $imagePath,
                'ghi_chu'          => $request->ghi_chu,
            ]);

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
        } elseif ($thanhToan->loai_thanh_toan == 'xuat') {
            $thanhToan->load('phieuXuat.khachHang');
        } else {
            $thanhToan->load('khachTraHang.khachHang');
        }

        return view('admin.inventory.payments.show', compact('thanhToan'));
    }

    /**
     * Lịch sử thanh toán
     */
    public function history(Request $request)
    {
        $tab = $request->get('tab', 'xuat'); // xuat, nhap, tra_hang
        $groupBy = $request->get('group_by', 'false');
        $search = $request->get('search');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = ThanhToan::where('loai_thanh_toan', $tab);

        if ($search) {
            $query->where(function ($q) use ($search, $tab) {
                $q->where('ma_thanh_toan', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_nhap', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_xuat', 'like', "%{$search}%");
                if ($tab === 'tra_hang') {
                    $q->orWhere('ma_tra_hang', 'like', "%{$search}%");
                }
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
        } elseif ($tab === 'xuat') {
            $query->with('phieuXuat.khachHang');
        } else {
            $query->with('khachTraHang.khachHang');
        }

        $query->orderBy('ngay_thanh_toan', 'desc');

        if ($groupBy === 'true') {
            $unpaginated = $query->get();
            if ($tab === 'nhap') {
                $transactions = $unpaginated->groupBy('ma_phieu_nhap');
            } elseif ($tab === 'xuat') {
                $transactions = $unpaginated->groupBy('ma_phieu_xuat');
            } else {
                $transactions = $unpaginated->groupBy('ma_tra_hang');
            }
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
            $query->where(function ($q) use ($search, $tab) {
                $q->where('ma_thanh_toan', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_nhap', 'like', "%{$search}%")
                  ->orWhere('ma_phieu_xuat', 'like', "%{$search}%");
                if ($tab === 'tra_hang') {
                    $q->orWhere('ma_tra_hang', 'like', "%{$search}%");
                }
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
        } elseif ($tab === 'xuat') {
            $query->with('phieuXuat.khachHang');
        } else {
            $query->with('khachTraHang.khachHang');
        }

        $transactions = $query->orderBy('ngay_thanh_toan', 'asc')->get();

        $tabLabel = match($tab) {
            'nhap'     => 'Phai_Tra',
            'xuat'     => 'Phai_Thu',
            default    => 'Hoan_Tra_KH',
        };
        $fileName = 'Lich_Su_Thanh_Toan_' . $tabLabel . '_' . date('Y_m_d_H_i') . '.xls';
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

    /**
     * Xuất Excel Danh sách hoàn trả đơn hàng (KH) đang chờ hoàn tiền
     */
    public function exportReturnRefunds()
    {
        $donTraHangs = KhachTraHang::with(['khachHang', 'donHang'])
            ->withSum('thanhToans as so_tien_da_hoan', 'so_tien_tt')
            ->where('trang_thai', 'da_duyet_nhap_kho')
            ->whereIn('trang_thai_hoan_tien', ['chua_hoan', 'mot_phan'])
            ->orderBy('ngay_duyet', 'desc')
            ->get()
            ->map(function ($dth) {
                $dth->so_tien_con_hoan = $dth->tong_tien_hoan_tra - ($dth->so_tien_da_hoan ?? 0);
                return $dth;
            });

        $fileName = 'Hoan_Tra_Don_Hang_KH_' . date('Y_m_d_H_i') . '.xls';
        return response(view('admin.inventory.payments.export_debts', ['phieus' => $donTraHangs, 'type' => 'tra_hang']))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
