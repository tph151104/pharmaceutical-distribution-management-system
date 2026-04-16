<?php

namespace App\Http\Controllers;

use App\Models\TonKho;
use App\Models\LichSuKho;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use App\Models\PhieuNhap;
use App\Models\KhuVucKho;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    /**
     * Helper cho query tồn kho
     */
    private function buildStockQuery(Request $request)
    {
        // $query = TonKho::with(['thuoc', 'chiTietKhuVuc.khuVuc'])
        $query = TonKho::with(['thuoc'])
            ->where('so_luong_ton', '>', 0);
        
        // Lọc theo tên thuốc hoặc số lô
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('thuoc', function($qThuoc) use ($search) {
                    $qThuoc->where('ten_thuoc', 'like', "%$search%");
                })->orWhere('so_lo', 'like', "%$search%");
            });
        }

        // Lọc theo trạng thái lô
        if ($request->has('trang_thai_lo') && $request->trang_thai_lo != '') {
            $query->where('trang_thai_lo', $request->trang_thai_lo);
        }

        // Lọc theo khu vực
        if ($request->has('ma_khu_vuc') && $request->ma_khu_vuc != '') {
            $query->whereHas('chiTietKhuVuc', function($q) use ($request) {
                $q->where('ma_khu_vuc', $request->ma_khu_vuc)
                  ->where('so_luong', '>', 0);
            });
        }

        return $query;
    }

    /**
     * Báo cáo tồn kho theo lô hàng và cảnh báo hạn sử dụng
     */
    public function stock(Request $request)
    {
        // Tính toán các mốc thời gian cảnh báo
        $today = now();
        $threeMonthsFromNow = now()->addMonths(3);
        $sixMonthsFromNow = now()->addMonths(6);

        // Lấy danh sách tồn kho có số lượng > 0, xếp lô gần hết hạn ưu tiên lên đầu
        $query = $this->buildStockQuery($request);
        $tonKho = $query->orderBy('han_su_dung', 'asc')
            ->paginate(50);

        // Lấy danh sách khu vực để làm bộ lọc
        $khuVucs = KhuVucKho::where('trang_thai', true)->get();

        return view('admin.inventory.reports.stock', compact(
            'tonKho', 
            'today', 
            'threeMonthsFromNow', 
            'sixMonthsFromNow',
            'khuVucs'
        ));
    }

    /**
     * Helper cho query lịch sử kho
     */
    private function buildMovementsQuery(Request $request)
    {
        $query = LichSuKho::with(['thuoc', 'nguoiDung']);

        // Filter by date range
        if ($request->has('tu_ngay') && $request->tu_ngay != '') {
            $query->whereDate('thoi_gian', '>=', $request->tu_ngay);
        }
        if ($request->has('den_ngay') && $request->den_ngay != '') {
            $query->whereDate('thoi_gian', '<=', $request->den_ngay);
        }

        // Filter by transaction type
        if ($request->has('loai_gd') && $request->loai_gd != '') {
            $query->where('loai_giao_dich', $request->loai_gd);
        }

        // Search by Tên thuốc or Số lô
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so_lo', 'like', "%$search%")
                  ->orWhereHas('thuoc', function($qThuoc) use ($search) {
                      $qThuoc->where('ten_thuoc', 'like', "%$search%");
                  });
            });
        }

        return $query;
    }

    /**
     * Báo cáo lịch sử xuất nhập kho (Audit Trail)
     */
    public function movements(Request $request)
    {
        $query = $this->buildMovementsQuery($request);
        $logs = $query->orderBy('thoi_gian', 'desc')->paginate(30);

        return view('admin.inventory.reports.movements', [
            'logs' => $logs
        ]);
    }

    /**
     * Xuất Excel (CSV) Tồn Kho
     */
    public function exportStock(Request $request)
    {
        $query = $this->buildStockQuery($request);
        $tonKho = $query->orderBy('han_su_dung', 'asc')->get();

        $fileName = 'Bao_Cao_Ton_Kho_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.reports.export_stock', compact('tonKho')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Xuất Excel (CSV) Lịch Sử Kho
     */
    public function exportMovements(Request $request)
    {
        $query = $this->buildMovementsQuery($request);
        $logs = $query->orderBy('thoi_gian', 'desc')->get();

        $fileName = 'Lich_Su_Xuat_Nhap_Kho_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.reports.export_movements', compact('logs')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Helper list debts collection
     */
    private function getDebtsData(Request $request)
    {
        $search = $request->search;
        $loai = $request->loai;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $pnQuery = PhieuNhap::with('nhaCungCap')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan']);

        $pxQuery = PhieuXuat::with('khachHang')
            ->withSum('cacThanhToan as so_tien_da_tra', 'so_tien_tt')
            ->whereIn('trang_thai_tt', ['chua_tt', 'mot_phan']);

        if ($fromDate) {
            $pnQuery->whereDate('ngay_nhap', '>=', $fromDate);
            $pxQuery->whereDate('ngay_xuat', '>=', $fromDate);
        }
        if ($toDate) {
            $pnQuery->whereDate('ngay_nhap', '<=', $toDate);
            $pxQuery->whereDate('ngay_xuat', '<=', $toDate);
        }

        if ($search) {
            $pnQuery->where(function($q) use ($search) {
                $q->where('ma_phieu_nhap', 'like', "%{$search}%")
                  ->orWhereHas('nhaCungCap', function($ncc) use ($search) {
                      $ncc->where('ten_ncc', 'like', "%{$search}%");
                  });
            });

            $pxQuery->where(function($q) use ($search) {
                $q->where('ma_phieu_xuat', 'like', "%{$search}%")
                  ->orWhereHas('khachHang', function($kh) use ($search) {
                      $kh->where('ten_kh', 'like', "%{$search}%");
                  });
            });
        }

        $all = collect();
        if (!$loai || $loai == 'nhap') {
            $all = $all->merge($pnQuery->get()->map(function($item) {
                $item->loai_thanh_toan = 'nhap';
                $item->so_tien_con_no = $item->tong_tien - ($item->so_tien_da_tra ?? 0);
                $item->doi_tuong = $item->nhaCungCap->ten_ncc ?? 'N/A';
                $item->sdt = $item->nhaCungCap->so_dien_thoai ?? '';
                $item->ma_chung_tu = $item->ma_phieu_nhap;
                $item->ngay_gd = $item->ngay_nhap;
                return $item;
            }));
        }

        if (!$loai || $loai == 'xuat') {
            $all = $all->merge($pxQuery->get()->map(function($item) {
                $item->loai_thanh_toan = 'xuat';
                $item->so_tien_con_no = $item->tong_tien - ($item->so_tien_da_tra ?? 0);
                $item->doi_tuong = $item->khachHang->ten_kh ?? 'N/A';
                $item->sdt = $item->khachHang->so_dien_thoai ?? '';
                $item->ma_chung_tu = $item->ma_phieu_xuat;
                $item->ngay_gd = $item->ngay_xuat;
                return $item;
            }));
        }

        return $all->sortByDesc('ngay_gd');
    }

    /**
     * Báo cáo Công Nợ & Doanh Thu
     */
    public function debts(Request $request)
    {
        $allDebts = $this->getDebtsData($request);

        // Paginate manually
        $page = Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $debts = new LengthAwarePaginator(
            $allDebts->forPage($page, $perPage)->values(), 
            $allDebts->count(), 
            $perPage, 
            $page, 
            ['path' => Paginator::resolveCurrentPath()]
        );
        $debts->appends($request->all());

        // --- Data cho Biểu đồ Công nợ (Tròn) ---
        // Nếu muốn chart tổng thật sự (không phụ thuộc filter) thì query raw,
        // Nhưng thường họ muốn chart ăn theo filter.
        $tongPhaiThu = $allDebts->where('loai_thanh_toan', 'xuat')->sum('so_tien_con_no');
        $tongPhaiTra = $allDebts->where('loai_thanh_toan', 'nhap')->sum('so_tien_con_no');

        // --- Data cho Biểu đồ Doanh Thu (Cột - 6 tháng gần nhất) ---
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = $month->format('m/Y');
            
            // Tính tổng tiền các phiếu xuất trong tháng
            $sum = PhieuXuat::whereYear('ngay_xuat', $month->year)
                ->whereMonth('ngay_xuat', $month->month)
                ->where('trang_thai_phieu_xuat', '!=', 'dang_chuan_bi')
                ->sum('tong_tien');
                
            $revenueData[] = (float) $sum;
        }

        return view('admin.inventory.reports.debts', [
            'debts' => $debts, 
            'tongPhaiThu' => $tongPhaiThu, 
            'tongPhaiTra' => $tongPhaiTra, 
            'revenueLabels' => $revenueLabels, 
            'revenueData' => $revenueData
        ]);
    }

    /**
     * Xuất Excel (CSV) Công Nợ
     */
    public function exportDebts(Request $request)
    {
        $debts = $this->getDebtsData($request);

        $fileName = 'Bao_Cao_Cong_No_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.reports.export_debts', compact('debts')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
