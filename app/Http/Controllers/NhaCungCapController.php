<?php

namespace App\Http\Controllers;

use App\Models\NhaCungCap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NhaCungCapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NhaCungCap::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_ncc', 'like', "%{$search}%")
                  ->orWhere('ten_ncc', 'like', "%{$search}%")
                  ->orWhere('dien_thoai', 'like', "%{$search}%")
                  ->orWhere('ma_so_thue', 'like', "%{$search}%");
            });
        }

        $nhaCungCaps = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('inventory.suppliers.index', compact('nhaCungCaps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_ncc' => 'required|string|max:255',
            'dia_chi' => 'nullable|string|max:255',
            'dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'ma_so_thue' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Tự động sinh mã NCC
            $lastNcc = NhaCungCap::orderBy('ma_ncc', 'desc')->first();
            $nextId = 1;
            if ($lastNcc && preg_match('/^NCC(\d+)$/', $lastNcc->ma_ncc, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maNcc = 'NCC' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            NhaCungCap::create(array_merge($request->all(), ['ma_ncc' => $maNcc]));

            DB::commit();
            return redirect()->route('suppliers.index')->with('success', 'Đã thêm nhà cung cấp thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi thêm nhà cung cấp: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nhaCungCap = NhaCungCap::findOrFail($id);

        $request->validate([
            'ten_ncc' => 'required|string|max:255',
            'dia_chi' => 'nullable|string|max:255',
            'dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'ma_so_thue' => 'nullable|string|max:50',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            $nhaCungCap->update($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Đã cập nhật nhà cung cấp thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Lỗi khi cập nhật nhà cung cấp: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nhaCungCap = NhaCungCap::findOrFail($id);
        
        // Kiểm tra xem NCC đã có phiếu nhập chưa
        if ($nhaCungCap->cacPhieuNhap()->exists()) {
            return back()->withErrors(['error' => 'Không thể xoá vì nhà cung cấp này đã có giao dịch nhập kho.']);
        }

        try {
            $nhaCungCap->delete();
            return redirect()->route('suppliers.index')->with('success', 'Đã xoá nhà cung cấp thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi khi xoá nhà cung cấp: ' . $e->getMessage()]);
        }
    }
}
