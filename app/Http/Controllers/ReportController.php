<?php

namespace App\Http\Controllers;

use App\Models\TonKho;
use App\Models\LichSuKho;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use App\Models\PhieuNhap;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Helper cho query tồn kho
     */
    private function buildStockQuery(Request $request)
    {
        $query = TonKho::with(['thuoc', 'chiTietKhuVuc.khuVuc'])
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
        $khuVucs = \App\Models\KhuVucKho::where('trang_thai', true)->get();

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

        $fileName = 'ton_kho_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($tonKho) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM

            fputcsv($file, [
                'Ma Thuoc',
                'Ten Thuoc',
                'So Lo',
                'Han Su Dung',
                'So Luong Ton',
                'Don Vi Tinh',
                'Ngay Nhap'
            ]);

            foreach ($tonKho as $item) {
                fputcsv($file, [
                    $item->ma_thuoc,
                    $item->thuoc->ten_thuoc ?? 'N/A',
                    $item->so_lo,
                    $item->han_su_dung ? \Carbon\Carbon::parse($item->han_su_dung)->format('d/m/Y') : '',
                    $item->so_luong_ton,
                    $item->thuoc->don_vi_tinh ?? '',
                    $item->ngay_nhap ? \Carbon\Carbon::parse($item->ngay_nhap)->format('d/m/Y') : ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Xuất Excel (CSV) Lịch Sử Kho
     */
    public function exportMovements(Request $request)
    {
        $query = $this->buildMovementsQuery($request);
        $logs = $query->orderBy('thoi_gian', 'desc')->get();

        $fileName = 'lich_su_kho_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM

            fputcsv($file, [
                'Thoi Gian',
                'Ma Log',
                'Nguoi Thao Tac',
                'Ma Chung Tu',
                'Nguon',
                'Ma Thuoc',
                'Ten Thuoc',
                'So Lo',
                'Loai GD',
                'So Luong',
                'Ton Truoc',
                'Ton Sau'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->thoi_gian->format('d/m/Y H:i:s'),
                    $log->ma_log,
                    $log->nguoiDung->ho_ten ?? $log->nguoi_thuc_hien,
                    $log->ma_chung_tu,
                    $log->nguon_giao_dich,
                    $log->ma_thuoc,
                    $log->thuoc->ten_thuoc ?? 'N/A',
                    $log->so_lo,
                    $log->loai_giao_dich,
                    $log->so_luong,
                    $log->ton_truoc,
                    $log->ton_sau
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $debts = new \Illuminate\Pagination\LengthAwarePaginator(
            $allDebts->forPage($page, $perPage)->values(), 
            $allDebts->count(), 
            $perPage, 
            $page, 
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
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

        $fileName = 'cong_no_' . date('Ymd_His') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($debts) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel to read UTF-8 correctly
            fputs($file, "\xEF\xBB\xBF");
            
            // Header row
            fputcsv($file, [
                'Loai Cong No',
                'So Chung Tu',
                'Ngay Phat Sinh',
                'Doi Tuong',
                'Tong Tien',
                'Da Thanh Toan',
                'Con No'
            ]);

            foreach ($debts as $d) {
                $loai = $d->loai_thanh_toan == 'nhap' ? 'Phải Trả (NCC)' : 'Phải Thu (KH)';
                
                fputcsv($file, [
                    $loai,
                    $d->ma_chung_tu,
                    $d->ngay_gd ? \Carbon\Carbon::parse($d->ngay_gd)->format('d/m/Y') : '',
                    $d->doi_tuong,
                    $d->tong_tien,
                    $d->so_tien_da_tra ?? 0,
                    $d->so_tien_con_no
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
