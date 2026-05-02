<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\KhachHang;
use App\Models\Thuoc;
use App\Models\NhomThuoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng (Admin)
     */
    public function index(Request $request)
    {
        $query = DonHang::with('khachHang');

        if ($status = $request->get('status')) {
            $query->where('trang_thai_dh', $status);
        }

        $donHangs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.inventory.orders.index', compact('donHangs'));
    }

    /**
     * Giao diện tạo đơn hàng (Sales Staff / Admin)
     */
    public function create()
    {
        $khachHangs = KhachHang::where('trang_thai_tk', 'hoat_dong')->get();
        // Lấy danh sách thuốc có thể bán
        $thuocs = Thuoc::with(['donViTinh'])
            ->whereHas('tonKho', function ($q) {
                $q->where('trang_thai_lo', 'dang_ban')
                  ->where('han_su_dung', '>=', now()->toDateString())
                  ->whereExists(function ($sub) {
                      $sub->select(DB::raw(1))
                          ->from('ton_kho_khu_vuc')
                          ->whereColumn('ton_kho_khu_vuc.ma_thuoc', 'ton_kho.ma_thuoc')
                          ->whereColumn('ton_kho_khu_vuc.ma_phieu_nhap', 'ton_kho.ma_phieu_nhap')
                          ->whereColumn('ton_kho_khu_vuc.so_lo', 'ton_kho.so_lo')
                          ->where('ton_kho_khu_vuc.ma_khu_vuc', 'KV03_THANH_PHAM')
                          ->where('ton_kho_khu_vuc.so_luong', '>', 0);
                  });
            })
            ->get();
            
        // Gắn số lượng có thể bán vào mỗi thuốc (chỉ để dự phòng nếu vẫn dùng code cũ)
        foreach ($thuocs as $thuoc) {
            $thuoc->ton_kho_hien_tai = $thuoc->tong_ton_kho;
        }

        $nhom_thuocs = NhomThuoc::all();

        return view('admin.inventory.orders.create', compact('khachHangs', 'thuocs', 'nhom_thuocs'));
    }

    /**
     * AJAX: Xử lý tìm kiếm thuốc nâng cao (cho form tạo đơn hàng)
     */
    public function advancedSearch(Request $request)
    {
        $query = Thuoc::with(['nhomThuoc', 'donViTinh'])
            ->whereHas('tonKho', function ($q) {
                $q->where('trang_thai_lo', 'dang_ban')
                  ->where('han_su_dung', '>=', now()->toDateString())
                  ->whereExists(function ($sub) {
                      $sub->select(DB::raw(1))
                          ->from('ton_kho_khu_vuc')
                          ->whereColumn('ton_kho_khu_vuc.ma_thuoc', 'ton_kho.ma_thuoc')
                          ->whereColumn('ton_kho_khu_vuc.ma_phieu_nhap', 'ton_kho.ma_phieu_nhap')
                          ->whereColumn('ton_kho_khu_vuc.so_lo', 'ton_kho.so_lo')
                          ->where('ton_kho_khu_vuc.ma_khu_vuc', 'KV03_THANH_PHAM')
                          ->where('ton_kho_khu_vuc.so_luong', '>', 0);
                  });
            });

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', "%{$keyword}%")
                  ->orWhere('ma_thuoc', 'like', "%{$keyword}%");
            });
        }

        if ($request->has('nhom_thuoc') && $request->nhom_thuoc != '') {
            $query->where('ma_nhom', $request->nhom_thuoc);
        }

        $thuocs = $query->limit(50)->get();

        foreach ($thuocs as $thuoc) {
            $thuoc->ton_kho_hien_tai = $thuoc->tong_ton_kho;
        }

        return response()->json($thuocs);
    }

    /**
     * Lưu đơn hàng (Sales Staff / Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_kh' => 'required|exists:khach_hang,ma_kh',
            'items' => 'required|array|min:1',
            'items.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'items.*.so_luong' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $tongTien = 0;
            $chiTietData = [];

            foreach ($request->items as $item) {
                $thuoc = Thuoc::find($item['ma_thuoc']);
                if (!$thuoc || $item['so_luong'] > $thuoc->tong_ton_kho) {
                    return back()->withErrors(['error' => 'Sản phẩm "' . ($thuoc->ten_thuoc ?? $item['ma_thuoc']) . '" hiện không đủ hàng trong kho (Thành phẩm).']);
                }
                
                $donGia = $thuoc->gia_ban_de_xuat ?? 0;
                $tongTien += $donGia * $item['so_luong'];

                $chiTietData[] = [
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_luong' => $item['so_luong'],
                    'don_gia' => $donGia,
                ];
            }

            // Sinh mã đơn hàng: DH_YYYYMMDD_XX
            $today = Carbon::now()->format('Ymd');
            $lastDH = DonHang::where('ma_don_hang', 'like', "DH_{$today}_%")
                ->orderBy('ma_don_hang', 'desc')->first();
            $nextNum = 1;
            if ($lastDH && preg_match('/DH_' . $today . '_(\d+)/', $lastDH->ma_don_hang, $m)) {
                $nextNum = intval($m[1]) + 1;
            }
            $maDH = "DH_{$today}_" . str_pad($nextNum, 2, '0', STR_PAD_LEFT);

            $donHang = DonHang::create([
                'ma_don_hang' => $maDH,
                'ma_kh' => $request->ma_kh,
                'ngay_dat' => Carbon::now(),
                'trang_thai_dh' => 'cho_xu_ly',
                'tong_tien' => $tongTien,
            ]);

            foreach ($chiTietData as $ct) {
                ChiTietDonHang::create(array_merge($ct, ['ma_don_hang' => $maDH]));
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Đã tạo đơn hàng thành công! Mã đơn: ' . $maDH);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * Chi tiết đơn hàng (Admin)
     */
    public function show($id)
    {
        $donHang = DonHang::with(['khachHang', 'chiTiet.thuoc', 'nguoiDuyet'])->findOrFail($id);
        return view('admin.inventory.orders.show', compact('donHang'));
    }

    /**
     * Duyệt đơn hàng
     */
    public function approve($id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->trang_thai_dh !== 'cho_xu_ly') {
            return back()->withErrors(['error' => 'Chỉ có thể duyệt đơn hàng đang Chờ xử lý.']);
        }

        DB::beginTransaction();
        try {
            // Đổi trạng thái đơn hàng sang đã duyệt (Kế toán duyệt)
            // Lát thủ kho sẽ tạo Phiếu xuất từ đơn hàng này.
            $donHang->update([
                'trang_thai_dh' => 'da_duyet',
                'nguoi_duyet' => auth()->id(),
            ]);

            DB::commit();
            return back()->with('success', 'Đã duyệt đơn hàng ' . $donHang->ma_don_hang . ' thành công. Đơn hàng đang chờ thủ kho xuất hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi duyệt đơn: ' . $e->getMessage()]);
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);

        if (in_array($donHang->trang_thai_dh, ['dang_van_chuyen', 'da_hoan_thanh', 'da_huy'])) {
            $statusName = $donHang->tenTrangThai;
            return back()->withErrors(['error' => "Không thể hủy đơn hàng đang ở trạng thái {$statusName}."]);
        }

        $donHang->update([
            'trang_thai_dh' => 'da_huy',
            'nguoi_huy' => auth()->id(),
            'ly_do_huy' => $request->ly_do_huy ?? 'Không có lý do',
        ]);

        return back()->with('success', 'Đã hủy đơn hàng ' . $donHang->ma_don_hang);
    }

    /**
     * Xuất danh sách đơn hàng ra Excel (CSV)
     */
    public function export(Request $request)
    {
        $query = DonHang::with(['khachHang', 'chiTiet']);

        if ($status = $request->get('status')) {
            $query->where('trang_thai_dh', $status);
        }

        $donHangs = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Danh_Sach_Don_Hang_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.orders.export', compact('donHangs')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * In đơn hàng
     */
    public function print($id)
    {
        $donHang = DonHang::with(['khachHang', 'chiTiet.thuoc'])->findOrFail($id);
        
        // Chỉ cho phép in nếu đơn hàng đã hoàn thành (tùy nghiệp vụ, ở đây mở rộng một chút hoặc bắt buộc 'da_hoan_thanh')
        // if ($donHang->trang_thai_dh !== 'da_hoan_thanh') {
        //     return back()->withErrors(['error' => 'Chỉ có thể in đơn hàng đã hoàn thành.']);
        // }

        return view('admin.inventory.orders.print', compact('donHang'));
    }
}
