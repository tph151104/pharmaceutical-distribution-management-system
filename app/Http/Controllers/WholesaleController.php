<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thuoc;
use App\Models\NhomThuoc;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WholesaleController extends Controller
{
    /**
     * Catalog - Danh sách sản phẩm động từ DB
     */
    public function catalog(Request $request)
    {
        $query = Thuoc::with(['nhomThuoc', 'donViTinh']);

        // Tìm kiếm
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ma_thuoc', 'like', "%{$search}%")
                  ->orWhere('ten_thuoc', 'like', "%{$search}%")
                  ->orWhere('thanh_phan', 'like', "%{$search}%");
            });
        }

        // Lọc theo nhóm
        if ($nhom = $request->get('nhom')) {
            $query->where('ma_nhom', $nhom);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'ten_thuoc');
        if ($sort == 'gia_thap') {
            $query->orderBy('gia_ban_de_xuat', 'asc');
        } elseif ($sort == 'gia_cao') {
            $query->orderBy('gia_ban_de_xuat', 'desc');
        } elseif ($sort == 'moi_nhat') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('ten_thuoc', 'asc');
        }

        $thuocs = $query->paginate(12)->appends($request->query());
        $nhomThuocs = NhomThuoc::orderBy('ten_nhom')->get();

        // Tính tồn kho cho từng sản phẩm
        foreach ($thuocs as $thuoc) {
            $thuoc->ton_kho_hien_tai = $thuoc->tongTonKho;
        }

        // Đếm giỏ hàng
        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'so_luong'));

        return view('wholesale.catalog', compact('thuocs', 'nhomThuocs', 'cartCount'));
    }

    /**
     * Thêm vào giỏ hàng (session-based)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'ma_thuoc' => 'required|string|exists:thuoc,ma_thuoc',
            'so_luong' => 'required|integer|min:1',
        ]);

        $thuoc = Thuoc::findOrFail($request->ma_thuoc);
        $cart = session('cart', []);

        if (isset($cart[$thuoc->ma_thuoc])) {
            $cart[$thuoc->ma_thuoc]['so_luong'] += $request->so_luong;
        } else {
            $cart[$thuoc->ma_thuoc] = [
                'ma_thuoc' => $thuoc->ma_thuoc,
                'ten_thuoc' => $thuoc->ten_thuoc,
                'don_gia' => $thuoc->gia_ban_de_xuat ?? 0,
                'so_luong' => $request->so_luong,
                'image1' => $thuoc->image1,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Đã thêm "' . $thuoc->ten_thuoc . '" vào giỏ hàng!');
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function cart()
    {
        $cart = session('cart', []);
        $tongTien = 0;
        foreach ($cart as $item) {
            $tongTien += $item['don_gia'] * $item['so_luong'];
        }

        $customer = auth('customer')->user();

        return view('wholesale.cart', compact('cart', 'tongTien', 'customer'));
    }

    /**
     * Cập nhật số lượng trong giỏ
     */
    public function updateCart(Request $request)
    {
        $cart = session('cart', []);

        if (isset($cart[$request->ma_thuoc])) {
            $cart[$request->ma_thuoc]['so_luong'] = max(1, intval($request->so_luong));
        }

        session(['cart' => $cart]);
        return back();
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     */
    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->ma_thuoc]);
        session(['cart' => $cart]);
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Đặt hàng
     */
    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['error' => 'Giỏ hàng trống, không thể đặt hàng.']);
        }

        $customer = auth('customer')->user();

        DB::beginTransaction();
        try {
            // Tính tổng tiền
            $tongTien = 0;
            foreach ($cart as $item) {
                $tongTien += $item['don_gia'] * $item['so_luong'];
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
                'ma_kh' => $customer->ma_kh,
                'ngay_dat' => Carbon::now(),
                'trang_thai_dh' => 'cho_xu_ly',
                'tong_tien' => $tongTien,
                'image1' => '',
                'image2' => '',
                'image3' => '',
            ]);

            foreach ($cart as $item) {
                ChiTietDonHang::create([
                    'ma_don_hang' => $maDH,
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_luong' => $item['so_luong'],
                    'don_gia' => $item['don_gia'],
                ]);
            }

            // Xóa giỏ hàng
            session()->forget('cart');

            DB::commit();
            return redirect()->route('wholesale.orders.index')
                ->with('success', 'Đã đặt hàng thành công! Mã đơn: ' . $maDH);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi đặt hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * Đơn hàng của tôi
     */
    public function orders()
    {
        $customer = auth('customer')->user();
        $donHangs = DonHang::where('ma_kh', $customer->ma_kh)
            ->orderBy('ngay_dat', 'desc')
            ->paginate(10);

        return view('wholesale.orders', compact('donHangs'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($id)
    {
        $customer = auth('customer')->user();
        $donHang = DonHang::with('chiTiet.thuoc')
            ->where('ma_kh', $customer->ma_kh)
            ->where('ma_don_hang', $id)
            ->firstOrFail();

        return view('wholesale.order_detail', compact('donHang'));
    }

    /**
     * Hủy đơn hàng (Khách hàng)
     */
    public function cancelOrder($id)
    {
        $customer = auth('customer')->user();
        $donHang = DonHang::where('ma_kh', $customer->ma_kh)
            ->where('ma_don_hang', $id)
            ->firstOrFail();

        if (!in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet', 'dang_xuat_kho'])) {
            return back()->withErrors(['error' => 'Chỉ có thể hủy đơn hàng khi chưa vận chuyển hoặc hoàn thành.']);
        }

        DB::beginTransaction();
        try {
            // Xóa phiếu xuất nháp liên quan (nếu có)
            $phieuXuat = \App\Models\PhieuXuat::where('ma_don_hang', $id)
                ->where('trang_thai_phieu_xuat', 'dang_chuan_bi')
                ->first();
            if ($phieuXuat) {
                $phieuXuat->delete();
            }

            $donHang->update(['trang_thai_dh' => 'da_huy']);

            DB::commit();
            return back()->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi hủy đơn hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * Sửa đơn hàng (Khách hàng)
     * Đưa sản phẩm về giỏ hàng, hủy đơn cũ và chuyển hướng đến giỏ.
     */
    public function editOrder($id)
    {
        $customer = auth('customer')->user();
        $donHang = DonHang::with('chiTiet.thuoc')
            ->where('ma_kh', $customer->ma_kh)
            ->where('ma_don_hang', $id)
            ->firstOrFail();

        if (!in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet', 'dang_xuat_kho'])) {
            return back()->withErrors(['error' => 'Chỉ có thể sửa đơn hàng khi chưa vận chuyển hoặc hoàn thành.']);
        }

        DB::beginTransaction();
        try {
            // Xóa phiếu xuất nháp liên quan (nếu có)
            $phieuXuat = \App\Models\PhieuXuat::where('ma_don_hang', $id)
                ->where('trang_thai_phieu_xuat', 'dang_chuan_bi')
                ->first();
            if ($phieuXuat) {
                $phieuXuat->delete();
            }

            // Hủy đơn hàng hiện tại
            $donHang->update(['trang_thai_dh' => 'da_huy']);

            // Lấy giỏ hàng hiện tại
            $cart = [];
            foreach ($donHang->chiTiet as $ct) {
                $thuoc = Thuoc::find($ct->ma_thuoc);
                if ($thuoc) {
                    $cart[$thuoc->ma_thuoc] = [
                        'ma_thuoc' => $thuoc->ma_thuoc,
                        'ten_thuoc' => $thuoc->ten_thuoc,
                        'don_gia' => $thuoc->gia_ban_de_xuat ?? $ct->don_gia,
                        'so_luong' => $ct->so_luong,
                        'image1' => $thuoc->image1,
                    ];
                }
            }
            session(['cart' => $cart]);

            DB::commit();
            return redirect()->route('wholesale.cart')->with('success', 'Bạn có thể chỉnh sửa số lượng và đặt lại đơn hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi sửa đơn hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * Khách hàng xác nhận đã nhận được hàng
     */
    public function completeOrder($id)
    {
        $customer = auth('customer')->user();
        $donHang  = DonHang::where('ma_kh', $customer->ma_kh)
                            ->where('ma_don_hang', $id)
                            ->firstOrFail();

        if ($donHang->trang_thai_dh !== 'dang_van_chuyen') {
            return back()->withErrors(['error' => 'Chỉ có thể xác nhận nhận hàng khi đơn đang vận chuyển.']);
        }

        DB::beginTransaction();
        try {
            $donHang->trang_thai_dh = 'da_hoan_thanh';
            $donHang->save();

            // Cập nhật Phiếu xuất liên quan
            \App\Models\PhieuXuat::where('ma_don_hang', $id)
                ->whereIn('trang_thai_phieu_xuat', ['da_van_chuyen'])
                ->update(['trang_thai_phieu_xuat' => 'da_hoan_thanh']);

            DB::commit();
            return back()->with('success', 'Cảm ơn! Đơn hàng đã được xác nhận hoàn thành.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
    /**
     * Chi tiết sản phẩm
     */
    public function product($id)
    {
        $thuoc = Thuoc::with(['nhomThuoc', 'donViTinh'])->findOrFail($id);
        $thuoc->ton_kho_hien_tai = $thuoc->tong_ton_kho;

        // Sản phẩm tương tự
        $similarProducts = Thuoc::with(['donViTinh'])
            ->where('ma_nhom', $thuoc->ma_nhom)
            ->where('ma_thuoc', '!=', $thuoc->ma_thuoc)
            ->inRandomOrder()
            ->limit(4)
            ->get();
            
        foreach ($similarProducts as $sp) {
            $sp->ton_kho_hien_tai = $sp->tong_ton_kho;
        }

        $cartCount = array_sum(array_column(session('cart', []), 'so_luong'));

        return view('wholesale.product', compact('thuoc', 'similarProducts', 'cartCount'));
    }

    /**
     * Khách hàng thanh toán đơn hàng
     */
    public function payOrder(Request $request, $id)
    {
        $customer = auth('customer')->user();
        $donHang = DonHang::where('ma_kh', $customer->ma_kh)
            ->where('ma_don_hang', $id)
            ->firstOrFail();

        // Tìm phiếu xuất liên quan
        $phieuXuat = PhieuXuat::where('ma_don_hang', $id)->first();
        if (!$phieuXuat) {
            return back()->withErrors(['error' => 'Chưa có phiếu xuất kho liên kết với đơn hàng này.']);
        }

        $request->validate([
            'so_tien_tt' => 'required|numeric|min:1',
            'phuong_thuc_tt' => 'required|string',
            'minh_chung_tt_image' => 'nullable|image|max:2048',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $soTienTT = floatval($request->so_tien_tt);
            $tongDaTra = ThanhToan::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)->sum('so_tien_tt');
            $tongTienCT = floatval($phieuXuat->tong_tien);
            $soTienConNo = $tongTienCT - $tongDaTra;

            if ($soTienTT > $soTienConNo + 0.01) {
                return back()->withErrors(['error' => 'Số tiền thanh toán (' . number_format($soTienTT) . 'đ) không được lớn hơn số tiền còn nợ (' . number_format($soTienConNo) . 'đ).']);
            }

            // Sinh mã thanh toán
            $lastTT = ThanhToan::where('ma_thanh_toan', 'like', 'TTX%')->orderBy('ma_thanh_toan', 'desc')->first();
            $nextId = 1;
            if ($lastTT && preg_match('/TTX(\d+)/', $lastTT->ma_thanh_toan, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maTT = 'TTX' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Xử lý ảnh minh chứng
            $imagePath = null;
            if ($request->hasFile('minh_chung_tt_image')) {
                $imagePath = $request->file('minh_chung_tt_image')->store('payments', 'public');
            }

            $conNoMoi = $soTienConNo - $soTienTT;

            ThanhToan::create([
                'ma_thanh_toan' => $maTT,
                'loai_thanh_toan' => 'xuat',
                'ma_phieu_xuat' => $phieuXuat->ma_phieu_xuat,
                'tong_tien' => $tongTienCT,
                'so_tien_tt' => $soTienTT,
                'so_tien_con_no' => $conNoMoi,
                'trang_thai_tt' => $conNoMoi <= 0.01 ? 'da_du' : 'con_no',
                'phuong_thuc_tt' => $request->phuong_thuc_tt,
                'ngay_thanh_toan' => now(),
                'minh_chung_tt_image' => $imagePath,
                'ghi_chu' => $request->ghi_chu,
            ]);

            // Cập nhật trạng thái phiếu xuất
            $phieuXuat->update([
                'trang_thai_tt' => $conNoMoi <= 0.01 ? 'da_tt' : 'mot_phan'
            ]);

            DB::commit();
            return back()->with('success', 'Thanh toán thành công! Mã giao dịch: ' . $maTT);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi thanh toán: ' . $e->getMessage()]);
        }
    }

    /**
     * Lịch sử thanh toán đơn hàng
     */
    public function paymentHistory($id)
    {
        $customer = auth('customer')->user();
        $donHang = DonHang::where('ma_kh', $customer->ma_kh)
            ->where('ma_don_hang', $id)
            ->firstOrFail();

        $phieuXuat = PhieuXuat::where('ma_don_hang', $id)->first();

        $transactions = collect();
        $tongDaTra = 0;
        $tongTien = $donHang->tong_tien;

        if ($phieuXuat) {
            $transactions = ThanhToan::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)
                ->orderBy('ngay_thanh_toan', 'desc')
                ->get();
            $tongDaTra = $transactions->sum('so_tien_tt');
        }

        $conNo = $tongTien - $tongDaTra;

        return view('wholesale.payment_history', compact('donHang', 'phieuXuat', 'transactions', 'tongTien', 'tongDaTra', 'conNo'));
    }
}
