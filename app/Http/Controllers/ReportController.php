<?php

namespace App\Http\Controllers;

use App\Models\TonKho;
use App\Models\LichSuKho;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use Illuminate\Http\Request;

class ReportController extends Controller
{
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
        $tonKho = TonKho::with('thuoc')
            ->where('so_luong_ton', '>', 0)
            ->orderBy('han_su_dung', 'asc')
            ->paginate(50);

        return view('admin.inventory.reports.stock', compact(
            'tonKho', 
            'today', 
            'threeMonthsFromNow', 
            'sixMonthsFromNow'
        ));
    }

    /**
     * Báo cáo lịch sử xuất nhập kho (Audit Trail)
     */
    public function movements(Request $request)
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

        // Filter by source
        if ($request->has('nguon_gd') && $request->nguon_gd != '') {
            $query->where('nguon_giao_dich', $request->nguon_gd);
        }

        $logs = $query->orderBy('thoi_gian', 'desc')->paginate(30);

        return view('admin.inventory.reports.movements', [
            'logs' => $logs
        ]);
    }

    /**
     * Helper cho query công nợ
     */
    private function buildDebtsQuery(Request $request)
    {
        $query = ThanhToan::with(['phieuNhap.nhaCungCap', 'phieuXuat.khachHang'])
            ->where('so_tien_con_no', '>', 0);

        if ($request->has('loai') && $request->loai != '') {
            $query->where('loai_thanh_toan', $request->loai);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_phieu_nhap', 'like', "%$search%")
                  ->orWhere('ma_phieu_xuat', 'like', "%$search%")
                  ->orWhereHas('phieuNhap.nhaCungCap', function($qNcc) use ($search) {
                      $qNcc->where('ten_ncc', 'like', "%$search%");
                  })
                  ->orWhereHas('phieuXuat.khachHang', function($qKh) use ($search) {
                      $qKh->where('ho_ten_kh', 'like', "%$search%");
                  });
            });
        }

        return $query;
    }

    /**
     * Báo cáo Công Nợ & Doanh Thu
     */
    public function debts(Request $request)
    {
        // Danh sách công nợ phân trang
        $query = $this->buildDebtsQuery($request);
        $debts = $query->orderBy('ngay_thanh_toan', 'desc')->paginate(20);

        // --- Data cho Biểu đồ Công nợ (Tròn) ---
        // Phải thu = xuat (Khách nợ mình), Phải trả = nhap (Mình nợ NCC)
        $tongPhaiThu = ThanhToan::where('so_tien_con_no', '>', 0)
            ->where('loai_thanh_toan', 'xuat')->sum('so_tien_con_no');
            
        $tongPhaiTra = ThanhToan::where('so_tien_con_no', '>', 0)
            ->where('loai_thanh_toan', 'nhap')->sum('so_tien_con_no');

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
        $query = $this->buildDebtsQuery($request);
        $debts = $query->orderBy('ngay_thanh_toan', 'desc')->get();

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
                $chungTu = $d->loai_thanh_toan == 'nhap' ? $d->ma_phieu_nhap : $d->ma_phieu_xuat;
                
                $doiTuong = '';
                if ($d->loai_thanh_toan == 'nhap' && $d->phieuNhap && $d->phieuNhap->nhaCungCap) {
                    $doiTuong = $d->phieuNhap->nhaCungCap->ten_ncc;
                } elseif ($d->loai_thanh_toan == 'xuat' && $d->phieuXuat && $d->phieuXuat->khachHang) {
                    $doiTuong = $d->phieuXuat->khachHang->ho_ten_kh;
                }

                fputcsv($file, [
                    $loai,
                    $chungTu,
                    $d->ngay_thanh_toan ? \Carbon\Carbon::parse($d->ngay_thanh_toan)->format('d/m/Y') : '',
                    $doiTuong,
                    $d->tong_tien,
                    $d->so_tien_tt,
                    $d->so_tien_con_no
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
