<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TonKhoKhuVuc;
use App\Models\KhuVucKho;
use App\Models\TonKho;
use App\Models\LichSuDichChuyenKho;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InventoryTransferController extends Controller
{
    /**
     * Hiển thị danh sách tồn kho theo khu vực lưu trữ
     */
    public function index(Request $request)
    {
        $khuVucs = KhuVucKho::where('trang_thai', true)->get();

        $query = TonKhoKhuVuc::with(['thuoc', 'khuVuc', 'phieuNhap'])->where('so_luong', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('thuoc', function($q) use ($search) {
                $q->where('ten_thuoc', 'like', '%' . $search . '%');
            })->orWhere('so_lo', 'like', '%' . $search . '%');
        }

        if ($request->has('khu_vuc') && $request->khu_vuc != '') {
            $query->where('ma_khu_vuc', $request->khu_vuc);
        }

        $transfers = $query->paginate(20);

        return view('admin.inventory.transfers.index', compact('transfers', 'khuVucs'));
    }

    /**
     * Xử lý luân chuyển kho
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'id_ton_kho_khu_vuc' => 'required|exists:ton_kho_khu_vuc,id',
            'den_khu_vuc' => 'required|exists:khu_vuc_kho,ma_khu_vuc',
            'so_luong_chuyen' => 'required|integer|min:1',
            'ly_do' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $sourceRecord = TonKhoKhuVuc::lockForUpdate()->find($request->id_ton_kho_khu_vuc);

            if (!$sourceRecord || $sourceRecord->so_luong < $request->so_luong_chuyen) {
                throw new \Exception('Số lượng trong kho không đủ để chuyển.');
            }

            if ($sourceRecord->ma_khu_vuc == $request->den_khu_vuc) {
                throw new \Exception('Kho đích không được trùng với kho nguồn.');
            }

            // 1. Trừ số lượng ở kho nguồn
            $sourceRecord->so_luong -= $request->so_luong_chuyen;
            if ($sourceRecord->so_luong <= 0) {
                $sourceRecord->delete();
            } else {
                $sourceRecord->save();
            }

            // 2. Cộng số lượng vào kho đích
            $targetRecord = TonKhoKhuVuc::firstOrNew([
                'ma_thuoc' => $sourceRecord->ma_thuoc,
                'ma_phieu_nhap' => $sourceRecord->ma_phieu_nhap,
                'so_lo' => $sourceRecord->so_lo,
                'ma_khu_vuc' => $request->den_khu_vuc
            ]);
            $targetRecord->so_luong += $request->so_luong_chuyen;
            $targetRecord->save();

            // 3. Ghi log dịch chuyển
            LichSuDichChuyenKho::create([
                'ma_phieu_chuyen' => 'CHCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                'ma_thuoc' => $sourceRecord->ma_thuoc,
                'ma_phieu_nhap' => $sourceRecord->ma_phieu_nhap,
                'so_lo' => $sourceRecord->so_lo,
                'tu_khu_vuc' => $sourceRecord->ma_khu_vuc,
                'den_khu_vuc' => $request->den_khu_vuc,
                'so_luong_chuyen' => $request->so_luong_chuyen,
                'nguoi_thuc_hien' => 'USR001', // TODO: Lấy User hiện tại
                'ngay_chuyen' => Carbon::now(),
                'ly_do_chuyen' => $request->ly_do ?? 'Nhân viên thực hiện luân chuyển',
            ]);

            // 4. Trigger auto cập nhật trạng thái lô
            $tonKho = TonKho::where('ma_thuoc', $sourceRecord->ma_thuoc)
                ->where('ma_phieu_nhap', $sourceRecord->ma_phieu_nhap)
                ->where('so_lo', $sourceRecord->so_lo)
                ->first();

            if ($tonKho) {
                // Nếu chuyển vào Kho Thành phẩm (Sẵn sàng) => Tự động Update trạng thái lên "đang bán"
                if ($request->den_khu_vuc == 'KV03_THANH_PHAM') {
                    if ($tonKho->trang_thai_lo != 'dang_ban') {
                        $tonKho->trang_thai_lo = 'dang_ban';
                        $tonKho->save();
                    }
                }
                
                // Nếu chuyển toàn bộ phần còn lại vào Kho Loại bỏ => ngưng bán
                // Check xem tổng tồn kho ở các kho (ngoại trừ Loại Bỏ) còn hay không
                if ($request->den_khu_vuc == 'KV05_LOAI_BO') {
                    $hangReusables = TonKhoKhuVuc::where('ma_thuoc', $sourceRecord->ma_thuoc)
                        ->where('so_lo', $sourceRecord->so_lo)
                        ->where('ma_khu_vuc', '!=', 'KV05_LOAI_BO')
                        ->sum('so_luong');
                    
                    if ($hangReusables == 0) {
                        $tonKho->trang_thai_lo = 'ngung_ban';
                        $tonKho->save();
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Đã chuyển kho thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi luân chuyển kho: ' . $e->getMessage()]);
        }
    }

    private function buildHistoryQuery(Request $request)
    {
        $query = LichSuDichChuyenKho::with(['thuoc', 'tuKhuVucKho', 'denKhuVucKho', 'nguoiThucHien']);

        if ($request->has('ma_phieu_nhap') && $request->ma_phieu_nhap != '') {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->ma_phieu_nhap . '%');
        }

        if ($request->has('ngay_chuyen') && $request->ngay_chuyen != '') {
            $query->whereDate('ngay_chuyen', $request->ngay_chuyen);
        }

        return $query;
    }

    public function history(Request $request)
    {
        $query = $this->buildHistoryQuery($request);
        $histories = $query->orderBy('ngay_chuyen', 'desc')->paginate(20);

        return view('admin.inventory.transfers.history', compact('histories'));
    }

    public function exportHistory(Request $request)
    {
        $query = $this->buildHistoryQuery($request);
        $histories = $query->orderBy('ngay_chuyen', 'desc')->get();

        $fileName = 'Lich_Su_Dich_Chuyen_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.transfers.export', compact('histories')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
