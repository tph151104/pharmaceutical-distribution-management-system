<?php

namespace App\Http\Controllers;

use App\Models\NhomThuoc;
use App\Models\Thuoc;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Lưu nhóm mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_nhom' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        $latest = NhomThuoc::where('ma_nhom', 'LIKE', 'NT%')
            ->orderByRaw('CAST(SUBSTRING(ma_nhom, 3) AS UNSIGNED) DESC')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->ma_nhom, 2);
            $newNum = $num + 1;
        } else {
            $newNum = 1;
        }

        $validated['ma_nhom'] = 'NT' . str_pad($newNum, 2, '0', STR_PAD_LEFT);

        NhomThuoc::create($validated);

        return redirect()->route('products.index')->with('success', "Đã thêm nhóm thuốc mới thành công (Mã: {$validated['ma_nhom']})!");
    }

    /**
     * Cập nhật nhóm
     */
    public function update(Request $request, $id)
    {
        $category = NhomThuoc::findOrFail($id);

        $validated = $request->validate([
            'ten_nhom' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('products.index')->with('success', 'Đã cập nhật thông tin nhóm thuốc!');
    }

    /**
     * Xoá nhóm (có kiểm tra ràng buộc)
     */
    public function destroy($id)
    {
        $category = NhomThuoc::findOrFail($id);

        // Kiểm tra xem có thuốc nào thuộc nhóm này không
        $productCount = Thuoc::where('ma_nhom', $id)->count();

        if ($productCount > 0) {
            return redirect()->route('products.index')->with('error', "Không thể xoá nhóm «{$category->ten_nhom}» vì đang có {$productCount} sản phẩm thuộc nhóm này. Vui lòng thay đổi nhóm cho các sản phẩm đó trước khi xoá.");
        }

        $category->delete();

        return redirect()->route('products.index')->with('success', 'Đã xoá nhóm thuốc thành công!');
    }
}
