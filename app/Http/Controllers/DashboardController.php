<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TonKho;
use App\Models\DonHang;
use App\Models\KhachTraHang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tính giá trị tồn kho
        $tonKhos = TonKho::with('chiTietPhieuNhap')->get();
        $giaTriTonKho = TonKho::join('chi_tiet_phieu_nhap', function($join) {
                                    $join->on('ton_kho.ma_phieu_nhap', '=', 'chi_tiet_phieu_nhap.ma_phieu_nhap')
                                         ->on('ton_kho.ma_thuoc', '=', 'chi_tiet_phieu_nhap.ma_thuoc')
                                         ->on('ton_kho.so_lo', '=', 'chi_tiet_phieu_nhap.so_lo');
                                })
                                ->where('ton_kho.so_luong_ton', '>', 0)
                                ->sum(DB::raw('ton_kho.so_luong_ton * chi_tiet_phieu_nhap.don_gia_nhap'));


        // 2. Lô sắp hết hạn
        $loList = TonKho::with('thuoc')
            ->where('han_su_dung', '<=', Carbon::now()->addDays(60))
            ->where('so_luong_ton', '>', 0)
            ->orderBy('han_su_dung', 'asc')
            ->get();
            
        $soLoSapHetHan = $loList->where('han_su_dung', '>=', Carbon::now()->toDateString())->count();
        $soLoDaHetHan = $loList->where('han_su_dung', '<', Carbon::now()->toDateString())->count();
        $topLoHSD = $loList->take(5);

        // 3. Doanh số bán sỉ
        $doanhSoThangNay = DonHang::where('trang_thai_dh', 'da_hoan_thanh')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('tong_tien');

        $doanhSoThangTruoc = DonHang::where('trang_thai_dh', 'da_hoan_thanh')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('tong_tien');
            
        if ($doanhSoThangTruoc > 0) {
            $tyLeTangTruong = (($doanhSoThangNay - $doanhSoThangTruoc) / $doanhSoThangTruoc) * 100;
        } else {
            $tyLeTangTruong = $doanhSoThangNay > 0 ? 100 : 0;
        }

        // 4. Sự kiện cần xử lý
        $donHangChoDuyet = DonHang::where('trang_thai_dh', 'cho_xu_ly')->count();
        $traHangChoDuyet = KhachTraHang::where('trang_thai', 'cho_duyet')->count();
        $phieuNhapDoiHang = \App\Models\PhieuNhap::where('trang_thai_phieu_nhap', 'doi_hang_ve')->count();

        // Biểu đồ dữ liệu (6 tháng gần nhất) - Giả lập hoặc lấy thực tế
        $chartData = $this->getChartData();

        return view('dashboard', compact(
            'giaTriTonKho', 
            'soLoSapHetHan', 
            'soLoDaHetHan', 
            'topLoHSD', 
            'doanhSoThangNay', 
            'tyLeTangTruong',
            'donHangChoDuyet',
            'traHangChoDuyet',
            'phieuNhapDoiHang',
            'chartData'
        ));
    }

    private function getChartData()
    {
        $months = [];
        $sales = [];
        $imports = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = 'T' . $month->format('m/Y');
            
            // Doanh số xuất kho
            $sale = DonHang::where('trang_thai_dh', 'da_hoan_thanh')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('tong_tien');
            $sales[] = $sale;
                
            // Giá trị nhập kho
            $import = \App\Models\PhieuNhap::whereIn('trang_thai_phieu_nhap', ['cho_nhap_kho', 'da_nhap_kho'])
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('tong_tien');
            $imports[] = $import;
        }

        return [
            'labels' => $months,
            'sales' => $sales,
            'imports' => $imports
        ];
    }
}
