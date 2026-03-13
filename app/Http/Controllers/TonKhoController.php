<?php

namespace App\Http\Controllers;

use App\Models\TonKho;
use App\Models\Thuoc;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TonKhoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Tính toán Dashboard (Thống kê tổng quan)
        // Đây chỉ là query cơ bản, trong hệ thống lớn nên cache hoặc tính toán định kỳ
        $tongSoLo = TonKho::where('so_luong_ton', '>', 0)->count();
        
        $ngayHetHanCanhBao = Carbon::now()->addDays(60);
        $loSapHetHan = TonKho::where('so_luong_ton', '>', 0)
                                ->where('han_su_dung', '<=', $ngayHetHanCanhBao)
                                ->where('han_su_dung', '>=', Carbon::now())
                                ->count();

        // Tính tổng giá trị tồn kho 
        // Lệnh join này gom `ton_kho` với `chi_tiet_phieu_nhap` để nhân so_luong_ton * don_gia_nhap
        $giaTriTonKho = TonKho::join('chi_tiet_phieu_nhap', function($join) {
                                    $join->on('ton_kho.ma_phieu_nhap', '=', 'chi_tiet_phieu_nhap.ma_phieu_nhap')
                                         ->on('ton_kho.ma_thuoc', '=', 'chi_tiet_phieu_nhap.ma_thuoc')
                                         ->on('ton_kho.so_lo', '=', 'chi_tiet_phieu_nhap.so_lo');
                                })
                                ->where('ton_kho.so_luong_ton', '>', 0)
                                ->sum(\DB::raw('ton_kho.so_luong_ton * chi_tiet_phieu_nhap.don_gia_nhap'));

        // 2. Query danh sách
        $query = TonKho::with(['thuoc', 'phieuNhap.nhaCungCap', 'chiTietPhieuNhap']);

        // Xử lý tìm kiếm 
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->whereHas('thuoc', function($q) use ($searchTerm) {
                $q->where('ma_thuoc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ten_thuoc', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('so_lo') && $request->so_lo != '') {
            $query->where('so_lo', 'like', '%' . $request->so_lo . '%');
        }

        if ($request->has('han_tu') && $request->han_tu != '') {
            $query->where('han_su_dung', '>=', $request->han_tu);
        }

        if ($request->has('han_den') && $request->han_den != '') {
            $query->where('han_su_dung', '<=', $request->han_den);
        }

        if ($request->has('tinh_trang') && $request->tinh_trang != '') {
            $now = Carbon::now()->toDateString();
            $warningDate = Carbon::now()->addDays(60)->toDateString();

            if ($request->tinh_trang == 'normal') {
                $query->where('han_su_dung', '>', $warningDate);
            } elseif ($request->tinh_trang == 'warning') {
                $query->where('han_su_dung', '<=', $warningDate)
                      ->where('han_su_dung', '>=', $now);
            } elseif ($request->tinh_trang == 'expired') {
                $query->where('han_su_dung', '<', $now);
            }
        }

        // Ưu tiên hiển thị lô gần hết hạn lên trên, rồi đến lượt lô mới
        $ton_khos = $query->orderBy('han_su_dung', 'asc')->paginate(15);

        return view('inventory.batches.index', compact(
            'ton_khos', 
            'tongSoLo', 
            'loSapHetHan', 
            'giaTriTonKho'
        ));
    }

    /**
     * Cập nhật trạng thái lô hàng
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'ma_thuoc' => 'required',
            'ma_phieu_nhap' => 'required',
            'so_lo' => 'required',
            'trang_thai_lo' => 'required|in:cho_duyet,dang_ban,het_han,ngung_ban'
        ]);

        $tonKho = TonKho::where('ma_thuoc', $request->ma_thuoc)
                        ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                        ->where('so_lo', $request->so_lo)
                        ->firstOrFail();
                        
        $tonKho->trang_thai_lo = $request->trang_thai_lo;
        $tonKho->save();

        return back()->with('success', 'Kho cập nhật trạng thái lô thành công.');
    }
}
