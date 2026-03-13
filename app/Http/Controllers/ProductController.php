<?php

namespace App\Http\Controllers;

use App\Models\Thuoc;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị danh mục sản phẩm thuốc
     */
    public function index(Request $request)
    {
        $query = Thuoc::query();

        // 1. Tìm kiếm theo tên thuốc
        if ($request->filled('search')) {
            $query->where('ten_thuoc', 'like', '%' . $request->search . '%');
        }

        // 2. Tính tổng số lượng tồn kho khả dụng (chỉ lấy lô đang bán, còn hạn, sl > 0)
        // Sử dụng scopeDangBan đã định nghĩa trong App\Models\TonKho
        $query->withSum(['tonKho as tong_ton_kho' => function ($q) {
            $q->dangBan();
        }], 'so_luong_ton');

        // 3. Sắp xếp: Ưu tiên hiển thị thuốc còn hàng trước
        $query->orderByDesc('tong_ton_kho');

        // 4. Phân trang
        $products = $query->paginate(12)->withQueryString();

        return view('client.products.index', [
            'products' => $products
        ]);
    }
}