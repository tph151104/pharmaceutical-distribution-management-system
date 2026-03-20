<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thuoc;
use App\Models\NhomThuoc;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
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

        if (!in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet'])) {
            return back()->withErrors(['error' => 'Chỉ có thể hủy đơn hàng khi chưa xuất kho/vận chuyển.']);
        }

        $donHang->update(['trang_thai_dh' => 'da_huy']);
        return back()->with('success', 'Đã hủy đơn hàng thành công!');
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

        if (!in_array($donHang->trang_thai_dh, ['cho_xu_ly', 'da_duyet'])) {
            return back()->withErrors(['error' => 'Chỉ có thể sửa đơn hàng khi chưa xuất kho/vận chuyển.']);
        }

        DB::beginTransaction();
        try {
            // Hủy đơn hàng hiện tại
            $donHang->update(['trang_thai_dh' => 'da_huy']);

            // Lấy giỏ hàng hiện tại (nếu có, nhưng tốt nhất là ghi đè hoặc cộng dồn, ở đây chọn ghi đè giỏ mới cho chắc chắn)
            $cart = [];
            foreach ($donHang->chiTiet as $ct) {
                // Lấy giá đề xuất hiện tại của thuốc (hoặc dùng giá cũ của đơn, nhưng thường là lấy giá mới nhất)
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
}
