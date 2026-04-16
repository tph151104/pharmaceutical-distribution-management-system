<?php

namespace App\Http\Controllers;

use App\Models\TonKho;
use App\Models\Thuoc;
use App\Models\TonKhoKhuVuc;
use App\Services\InventoryLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Tính toán Dashboard (Thống kê tổng quan)
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
                                ->sum(DB::raw('ton_kho.so_luong_ton * chi_tiet_phieu_nhap.don_gia_nhap'));

        // 2. Query danh sách
        $query = TonKho::with(['thuoc', 'phieuNhap.nhaCungCap', 'chiTietPhieuNhap']);

        // Không show các lô hàng nếu 100% tồn kho nằm ở kho loại bỏ KV05
        $query->whereRaw("so_luong_ton > COALESCE((SELECT SUM(so_luong) FROM ton_kho_khu_vuc WHERE ton_kho_khu_vuc.ma_thuoc = ton_kho.ma_thuoc AND ton_kho_khu_vuc.ma_phieu_nhap = ton_kho.ma_phieu_nhap AND ton_kho_khu_vuc.so_lo = ton_kho.so_lo AND ma_khu_vuc = 'KV05_LOAI_BO'), 0)");

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

        return view('admin.inventory.batches.index', compact(
            'ton_khos', 
            'tongSoLo', 
            'loSapHetHan', 
            'giaTriTonKho'
        ));
    }

    /**
     * Chuyển trạng thái sang Ngưng bán và chuyển kho sang KV04
     */
    public function stopSelling(Request $request)
    {
        $request->validate([
            'ma_thuoc' => 'required',
            'ma_phieu_nhap' => 'required',
            'so_lo' => 'required',
            'so_luong_chuyen' => 'required|integer|min:0',
            'ly_do' => 'required|string|max:500'
        ]);

        $tonKho = TonKho::where('ma_thuoc', $request->ma_thuoc)
                        ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                        ->where('so_lo', $request->so_lo)
                        ->firstOrFail();

        if ($tonKho->trang_thai_lo !== 'dang_ban') {
            return back()->withErrors(['error' => 'Chỉ có thể ngưng bán lô đang ở trạng thái Đang bán.']);
        }

        $soLuongChuyen = (int)$request->so_luong_chuyen;
        $maxChuyen = $tonKho->sl_co_the_ban;

        if ($maxChuyen < $soLuongChuyen) {
            return back()->withErrors(['error' => "Số lượng chuyển ($soLuongChuyen) vượt quá số lượng có thể bán ($maxChuyen) đang nằm ở kho thành phẩm."]);
        }

        DB::beginTransaction();
        try {
            // Thay đổi trạng thái lô sang Ngưng bán nếu chuyển hết hoặc chọn 0 (chỉ đổi trạng thái)
            if ($soLuongChuyen === 0 || $soLuongChuyen >= $maxChuyen) {
                $tonKho->trang_thai_lo = 'ngung_ban';
                $tonKho->save();
            }

            // Chuyển kho nếu có số lượng > 0
            if ($soLuongChuyen > 0) {
                // Trừ từ KV03_THANH_PHAM
                $tkvNguon = TonKhoKhuVuc::where('ma_thuoc', $tonKho->ma_thuoc)
                    ->where('ma_phieu_nhap', $tonKho->ma_phieu_nhap)
                    ->where('so_lo', $tonKho->so_lo)
                    ->where('ma_khu_vuc', 'KV03_THANH_PHAM')
                    ->first();

                if (!$tkvNguon || $tkvNguon->so_luong < $soLuongChuyen) {
                     DB::rollBack();
                     return back()->withErrors(['error' => 'Không đủ số lượng trong KV03_THANH_PHAM để chuyển.']);
                }

                $tkvNguon->so_luong -= $soLuongChuyen;
                $tkvNguon->save();

                // Tăng vào KV04
                $tkvDich = TonKhoKhuVuc::where('ma_thuoc', $tonKho->ma_thuoc)
                    ->where('ma_phieu_nhap', $tonKho->ma_phieu_nhap)
                    ->where('so_lo', $tonKho->so_lo)
                    ->where('ma_khu_vuc', 'KV04_CHO_XU_LY')
                    ->first();

                if ($tkvDich) {
                    $tkvDich->so_luong += $soLuongChuyen;
                    $tkvDich->save();
                } else {
                    TonKhoKhuVuc::create([
                        'ma_thuoc' => $tonKho->ma_thuoc,
                        'ma_phieu_nhap' => $tonKho->ma_phieu_nhap,
                        'so_lo' => $tonKho->so_lo,
                        'ma_khu_vuc' => 'KV04_CHO_XU_LY',
                        'so_luong' => $soLuongChuyen
                    ]);
                }
            }

            // Ghi log
            InventoryLogService::logMovement(
                $tonKho->ma_thuoc,
                $tonKho->so_lo,
                auth()->id() ?? 'NV001',
                $tonKho->ma_phieu_nhap,
                'dieu_chinh',
                'kiem_kho',
                $soLuongChuyen,
                $tonKho->so_luong_ton,
                $tonKho->so_luong_ton,
                0,
                '[Ngưng bán] ' . $request->ly_do . ($soLuongChuyen > 0 ? " | Đã chuyển $soLuongChuyen vào KV04" : '')
            );

            DB::commit();
            return back()->with('success', 'Lô đã được chuyển sang trạng thái Ngưng bán và đưa vào chờ xử lý.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi xử lý: ' . $e->getMessage()]);
        }
    }

}

