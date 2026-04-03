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

        return view('admin.inventory.batches.index', compact(
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
                        
        $oldStatus = $tonKho->trang_thai_lo;
        $tonKho->trang_thai_lo = $request->trang_thai_lo;
        $tonKho->save();

        if ($oldStatus != $tonKho->trang_thai_lo) {
            \App\Services\InventoryLogService::logMovement(
                $tonKho->ma_thuoc,
                $tonKho->so_lo,
                'NV001', // Tạm fix
                $tonKho->ma_phieu_nhap,
                'dieu_chinh',
                'kiem_kho',
                0,
                $tonKho->so_luong_ton,
                $tonKho->so_luong_ton,
                0,
                "Trạng thái lô thay đổi từ {$oldStatus} sang {$tonKho->trang_thai_lo}"
            );
        }

        return back()->with('success', 'Kho cập nhật trạng thái lô thành công.');
    }

    /**
     * Điều chỉnh tồn kho (kiểm kê / thất thoát / sai lệch)
     */
    public function adjustStock(Request $request)
    {
        $request->validate([
            'ma_thuoc' => 'required',
            'ma_phieu_nhap' => 'required',
            'so_lo' => 'required',
            'so_luong_moi' => 'required|integer|min:0',
            'ly_do' => 'required|string|max:500',
        ], [
            'so_luong_moi.required' => 'Vui lòng nhập số lượng tồn thực tế.',
            'so_luong_moi.min' => 'Số lượng tồn thực tế không thể âm.',
            'ly_do.required' => 'Vui lòng nhập lý do điều chỉnh.',
        ]);

        $tonKho = TonKho::where('ma_thuoc', $request->ma_thuoc)
                        ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                        ->where('so_lo', $request->so_lo)
                        ->firstOrFail();

        $tonTruoc = $tonKho->so_luong_ton;
        $tonSau = (int) $request->so_luong_moi;
        $chenhLech = $tonSau - $tonTruoc;

        // Không làm gì nếu số lượng không đổi
        if ($chenhLech === 0) {
            return back()->with('info', 'Số lượng tồn kho không thay đổi, không cần điều chỉnh.');
        }

        $tonKho->so_luong_ton = $tonSau;
        $tonKho->save();

        // Ghi log điều chỉnh
        \App\Services\InventoryLogService::logMovement(
            $tonKho->ma_thuoc,
            $tonKho->so_lo,
            auth()->id() ?? 'NV001',
            $tonKho->ma_phieu_nhap,
            'dieu_chinh',
            'kiem_kho',
            abs($chenhLech),
            $tonTruoc,
            $tonSau,
            0,
            '[Điều chỉnh tồn kho] ' . $request->ly_do
                . ' | Chênh lệch: ' . ($chenhLech > 0 ? '+' : '') . $chenhLech
        );

        $loaiDieuChinh = $chenhLech > 0 ? 'tăng' : 'giảm';
        return back()->with('success', "Đã điều chỉnh tồn kho lô {$tonKho->so_lo}: {$loaiDieuChinh} " . abs($chenhLech) . " (Từ {$tonTruoc} → {$tonSau}).");
    }

    /**
     * Cách ly GSP (Tách một số hàng bị lỗi/thu hồi sang KV04 hoăc KV05)
     */
    public function isolate(Request $request)
    {
        $request->validate([
            'ma_thuoc' => 'required',
            'ma_phieu_nhap' => 'required',
            'so_lo' => 'required',
            'so_luong_cach_ly' => 'required|integer|min:1',
            'ma_khu_vuc_den' => 'required|in:KV04_CHO_XU_LY,KV05_LOAI_BO',
            'ly_do' => 'required|string|max:500'
        ]);

        $tonKho = TonKho::where('ma_thuoc', $request->ma_thuoc)
                        ->where('ma_phieu_nhap', $request->ma_phieu_nhap)
                        ->where('so_lo', $request->so_lo)
                        ->firstOrFail();

        $soLuongIsolate = (int)$request->so_luong_cach_ly;

        if ($tonKho->so_luong_ton < $soLuongIsolate) {
            return back()->withErrors(['error' => 'Số lượng cách ly vượt quá tổng tồn của lô này.']);
        }

        // Ưu tiên trừ từ KV03, nếu không đủ thì trừ từ các khu vực khác
        $tonKhuVucs = \App\Models\TonKhoKhuVuc::where('ma_thuoc', $tonKho->ma_thuoc)
            ->where('ma_phieu_nhap', $tonKho->ma_phieu_nhap)
            ->where('so_lo', $tonKho->so_lo)
            ->where('so_luong', '>', 0)
            ->orderByRaw("CASE WHEN ma_khu_vuc = 'KV03_THANH_PHAM' THEN 1 ELSE 2 END")
            ->get();

        $remainingToIsolate = $soLuongIsolate;

        // Trừ dần ở các khu vực đang có
        foreach ($tonKhuVucs as $tkv) {
            if ($remainingToIsolate <= 0) break;
            
            $tru = min($remainingToIsolate, $tkv->so_luong);
            $tkv->so_luong -= $tru;
            $tkv->save();
            
            $remainingToIsolate -= $tru;
        }

        if ($remainingToIsolate > 0) {
            return back()->withErrors(['error' => 'Không đủ số lượng trong kho vật lý để cách ly.']);
        }

        // Tăng vào khu vực mới (KV04/KV05)
        $tkvDich = \App\Models\TonKhoKhuVuc::where('ma_thuoc', $tonKho->ma_thuoc)
            ->where('ma_phieu_nhap', $tonKho->ma_phieu_nhap)
            ->where('so_lo', $tonKho->so_lo)
            ->where('ma_khu_vuc', $request->ma_khu_vuc_den)
            ->first();

        if ($tkvDich) {
            $tkvDich->so_luong += $soLuongIsolate;
            $tkvDich->save();
        } else {
            \App\Models\TonKhoKhuVuc::create([
                'ma_thuoc' => $tonKho->ma_thuoc,
                'ma_phieu_nhap' => $tonKho->ma_phieu_nhap,
                'so_lo' => $tonKho->so_lo,
                'ma_khu_vuc' => $request->ma_khu_vuc_den,
                'so_luong' => $soLuongIsolate
            ]);
        }

        // Ghi log
        \App\Services\InventoryLogService::logMovement(
            $tonKho->ma_thuoc,
            $tonKho->so_lo,
            auth()->id() ?? 'NV001',
            $tonKho->ma_phieu_nhap,
            'dieu_chinh',
            'kiem_kho',
            $soLuongIsolate,
            $tonKho->so_luong_ton,
            $tonKho->so_luong_ton,
            0,
            '[Cách ly GSP] ' . $request->ly_do . ' | Chuyển đến ' . $request->ma_khu_vuc_den
        );

        return back()->with('success', 'Kho đã được cách ly thành công.');
    }
}
