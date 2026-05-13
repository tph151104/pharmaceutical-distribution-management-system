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
     * Ma trận chuyển kho hợp lệ theo quy trình GSP
     * Key: Kho nguồn → Value: Các kho đích được phép
     * KV01 (Tiếp nhận) → KV02 (Biệt trữ), KV04 (Chờ xử lý)
     * KV02 (Biệt trữ) → KV03 (Thành phẩm), KV04 (Chờ xử lý)
     * KV03 (Thành phẩm) → KV04 (Chờ xử lý)
     * KV04 (Chờ xử lý) → KV03 (Thành phẩm - bắt buộc lý do), KV05 (Loại bỏ)
     * KV05 (Loại bỏ) → Không cho chuyển (Dead end)
     */
    const ALLOWED_TRANSFERS = [
        'KV01_TIEP_NHAN'  => ['KV02_BIET_TRU', 'KV04_CHO_XU_LY'],
        'KV02_BIET_TRU'   => ['KV03_THANH_PHAM', 'KV04_CHO_XU_LY'],
        'KV03_THANH_PHAM'  => ['KV04_CHO_XU_LY'],
        'KV04_CHO_XU_LY'   => ['KV03_THANH_PHAM', 'KV05_LOAI_BO'],
        'KV05_LOAI_BO'     => [], // Dead end - không cho chuyển đi đâu
    ];

    /**
     * Hiển thị danh sách tồn kho theo khu vực lưu trữ
     */
    public function index(Request $request)
    {
        $khuVucs = KhuVucKho::where('trang_thai', true)->get();

        $query = TonKhoKhuVuc::with(['thuoc', 'khuVuc', 'phieuNhap'])->where('so_luong', '>', 0);

        // 1. Tìm kiếm tổng quát (Tên thuốc / Số lô / Mã phiếu nhập)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('thuoc', function($inner) use ($search) {
                    $inner->where('ten_thuoc', 'like', '%' . $search . '%');
                })
                ->orWhere('so_lo', 'like', '%' . $search . '%')
                ->orWhere('ma_phieu_nhap', 'like', '%' . $search . '%');
            });
        }

        // 2. Lọc theo mã phiếu nhập riêng biệt
        if ($request->filled('ma_phieu_nhap')) {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->ma_phieu_nhap . '%');
        }

        // 3. Lọc theo khu vực kho
        if ($request->filled('khu_vuc')) {
            $query->where('ma_khu_vuc', $request->khu_vuc);
        }

        // 4. Lọc theo khoảng ngày nhập (Từ ngày - Đến ngày)
        if ($request->filled('from_date')) {
            $query->whereHas('phieuNhap', function($q) use ($request) {
                $q->whereDate('ngay_nhap', '>=', $request->from_date);
            });
        }
        if ($request->filled('to_date')) {
            $query->whereHas('phieuNhap', function($q) use ($request) {
                $q->whereDate('ngay_nhap', '<=', $request->to_date);
            });
        }

        // 5. Sắp xếp: Ưu tiên theo khu vực KV01 -> KV05
        $transfers = $query->orderBy('ma_khu_vuc', 'asc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);

        // Truyền ma trận chuyển kho sang view để JS lọc dropdown
        $allowedTransfers = self::ALLOWED_TRANSFERS;

        return view('admin.inventory.transfers.index', compact('transfers', 'khuVucs', 'allowedTransfers'));
    }

    /**
     * Xử lý luân chuyển kho (có kiểm tra quy tắc GSP)
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

            // ══════ KIỂM TRA QUY TẮC LUÂN CHUYỂN GSP ══════
            $allowedDestinations = self::ALLOWED_TRANSFERS[$sourceRecord->ma_khu_vuc] ?? [];

            if (empty($allowedDestinations)) {
                throw new \Exception('Khu vực ' . $sourceRecord->ma_khu_vuc . ' là kho loại bỏ (KV05), không được phép chuyển hàng đi.');
            }

            if (!in_array($request->den_khu_vuc, $allowedDestinations)) {
                $tenNguon = KhuVucKho::find($sourceRecord->ma_khu_vuc)->ten_khu_vuc ?? $sourceRecord->ma_khu_vuc;
                $tenDich = KhuVucKho::find($request->den_khu_vuc)->ten_khu_vuc ?? $request->den_khu_vuc;
                throw new \Exception("Không được phép chuyển từ \"{$tenNguon}\" sang \"{$tenDich}\". Vui lòng chuyển theo đúng trình tự GSP.");
            }

            // KV04 → KV03: Bắt buộc nhập lý do
            if ($sourceRecord->ma_khu_vuc == 'KV04_CHO_XU_LY' && $request->den_khu_vuc == 'KV03_THANH_PHAM') {
                if (empty($request->ly_do) || trim($request->ly_do) === '') {
                    throw new \Exception('Khi chuyển hàng từ Kho Chờ xử lý trở lại Kho Thành phẩm, bắt buộc phải nhập lý do.');
                }
            }
            // ══════════════════════════════════════════════

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
                'nguoi_thuc_hien' => auth()->id(),
                'ngay_chuyen' => Carbon::now(),
                'ly_do_chuyen' => $request->ly_do ?? 'Kiểm tra định kỳ',
            ]);

            // 4. Trigger auto cập nhật trạng thái lô + tổng tồn kho
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
                
                // Nếu chuyển vào Kho Loại bỏ (KV05) => Trừ tổng tồn kho + check ngưng bán
                if ($request->den_khu_vuc == 'KV05_LOAI_BO') {
                    // Trừ tổng tồn kho (hàng vào KV05 = không còn giá trị sử dụng)
                    $tonKho->so_luong_ton -= $request->so_luong_chuyen;
                    if ($tonKho->so_luong_ton < 0) $tonKho->so_luong_ton = 0;

                    // Check xem tổng tồn kho ở các kho (ngoại trừ Loại Bỏ) còn hay không
                    $hangReusables = TonKhoKhuVuc::where('ma_thuoc', $sourceRecord->ma_thuoc)
                        ->where('so_lo', $sourceRecord->so_lo)
                        ->where('ma_khu_vuc', '!=', 'KV05_LOAI_BO')
                        ->sum('so_luong');
                    
                    if ($hangReusables == 0) {
                        $tonKho->trang_thai_lo = 'ngung_ban';
                    }

                    $tonKho->save();
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
