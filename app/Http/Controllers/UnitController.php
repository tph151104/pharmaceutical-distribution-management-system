<?php

namespace App\Http\Controllers;

use App\Models\DonViTinh;
use App\Models\Thuoc;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Lưu đơn vị mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_dvt' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        //tự động sinh mã 
        $latest = DonViTinh::where('ma_dvt', 'LIKE', 'DVT%')
            ->orderByRaw('CAST(SUBSTRING(ma_dvt, 4) AS UNSIGNED) DESC')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->ma_dvt, 3);
            $newNum = $num + 1;
        } else {
            $newNum = 1;
        }

        $validated['ma_dvt'] = 'DVT' . str_pad($newNum, 2, '0', STR_PAD_LEFT);

        DonViTinh::create($validated);

        return redirect()->route('products.index')->with('success', 'Đã thêm đơn vị tính mới thành công!');
    }

    /**
     * Cập nhật đơn vị
     */
    public function update(Request $request, $id)
    {
        $unit = DonViTinh::findOrFail($id);

        $validated = $request->validate([
            'ten_dvt' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('products.index')->with('success', 'Đã cập nhật thông tin đơn vị tính!');
    }

    /**
     * Xoá đơn vị (có kiểm tra ràng buộc)
     */
    public function destroy($id)
    {
        $unit = DonViTinh::findOrFail($id);

        // Kiểm tra xem có thuốc nào dùng đơn vị này không
        $productCount = Thuoc::where('ma_dvt', $id)->count();

        if ($productCount > 0) {
            return redirect()->route('products.index')->with('error', "Không thể xoá đơn vị «{$unit->ten_dvt}» vì đang có {$productCount} sản phẩm sử dụng. Vui lòng thay đổi đơn vị tính cho các sản phẩm đó trước khi xoá.");
        }

        $unit->delete();

        return redirect()->route('products.index')->with('success', 'Đã xoá đơn vị tính thành công!');
    }
}
