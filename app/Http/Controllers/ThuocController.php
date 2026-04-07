<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;//thêm vào để import excel
use App\Imports\ThuocImport;

use App\Models\Thuoc;
use App\Models\NhomThuoc;
use App\Models\DonViTinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThuocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Thuoc::with(['nhomThuoc', 'donViTinh']);

        // Xử lý tìm kiếm cơ bản (nếu có)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('ma_thuoc', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ten_thuoc', 'like', '%' . $searchTerm . '%');
        }

        if ($request->has('nhom_thuoc') && $request->nhom_thuoc != '') {
            $query->where('ma_nhom', $request->nhom_thuoc);
        }

        $thuocs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $nhom_thuocs = NhomThuoc::withCount('cacThuoc')->get();
        $don_vi_tinhs = DonViTinh::withCount('cacThuoc')->get();

        return view('admin.inventory.products.index', compact('thuocs', 'nhom_thuocs', 'don_vi_tinhs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_thuoc' => 'required|string|max:255',
            'ma_nhom' => 'required|string|exists:nhom_thuoc,ma_nhom',
            'ma_dvt' => 'required|string|exists:don_vi_tinh,ma_dvt',
            'nguon_goc' => 'nullable|string|max:255',
            'thanh_phan' => 'nullable|string',
            'ham_luong' => 'nullable|string|max:100',
            'cong_dung' => 'nullable|string',
            'cach_dung' => 'nullable|string',
            'bao_quan' => 'nullable|string',
            'chong_chi_dinh' => 'nullable|string',
            'dang_bao_che' => 'nullable|string|max:100',
            'gia_ban_de_xuat' => 'nullable|numeric|min:0',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tự động sinh mã sản phẩm TH01, TH02...
        $latest = Thuoc::where('ma_thuoc', 'LIKE', 'TH%')
            //sắp xếp giảm dần để đưa mã có số thứ tự lớn nhất lên đầu
            ->orderByRaw('CAST(SUBSTRING(ma_thuoc, 3) AS UNSIGNED) DESC')//bỏ 2 ký tự đầu
            ->first();

        if ($latest) {
            $num = (int) substr($latest->ma_thuoc, 2);
            $newNum = $num + 1;
        } else {
            $newNum = 1;
        }
                                        //hàm "lấp đầy" chuỗi.
        $validated['ma_thuoc'] = 'TH' . str_pad($newNum, 4, '0', STR_PAD_LEFT);//Bù vào bên trái

        // Upload images
        $imagePaths = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('image'.$i)) {
                $image = $request->file('image'.$i);
                $imageName = time() . '_' . $i . '.' . $image->extension();
                $image->move(public_path('uploads/thuoc'), $imageName);
                $validated['image'.$i] = 'uploads/thuoc/' . $imageName;
            } else {
                $validated['image'.$i] = '';
            }
        }

        Thuoc::create($validated);

        return redirect()->route('products.index')->with('success', 'Đã thêm sản phẩm thành công!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $thuoc = Thuoc::findOrFail($id);

        $validated = $request->validate([
            'ten_thuoc' => 'required|string|max:255',
            'ma_nhom' => 'required|string|exists:nhom_thuoc,ma_nhom',
            'ma_dvt' => 'required|string|exists:don_vi_tinh,ma_dvt',
            'nguon_goc' => 'nullable|string|max:255',
            'thanh_phan' => 'nullable|string',
            'ham_luong' => 'nullable|string|max:100',
            'cong_dung' => 'nullable|string',
            'cach_dung' => 'nullable|string',
            'bao_quan' => 'nullable|string',
            'chong_chi_dinh' => 'nullable|string',
            'dang_bao_che' => 'nullable|string|max:100',
            'gia_ban_de_xuat' => 'nullable|numeric|min:0',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload images
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('image'.$i)) {
                $image = $request->file('image'.$i);
                $imageName = time() . '_' . $i . '.' . $image->extension();
                $image->move(public_path('uploads/thuoc'), $imageName);
                $validated['image'.$i] = 'uploads/thuoc/' . $imageName;
                
                // Delete old image if exists
                if ($thuoc->{'image'.$i} && file_exists(public_path($thuoc->{'image'.$i}))) {
                    @unlink(public_path($thuoc->{'image'.$i}));
                }
            }
        }

        $thuoc->update($validated);

        return redirect()->route('products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $thuoc = Thuoc::findOrFail($id);
        
        // Kiểm tra xem thuốc này đã có trong tồn kho hay không
        $hasInventory = \App\Models\TonKho::where('ma_thuoc', $id)->exists();
        if ($hasInventory) {
            return redirect()->route('products.index')->with('error', "Không thể xoá thuốc " . $thuoc->ten_thuoc . " vì sản phẩm này đã có dữ liệu trong tồn kho.");
        }

        // Delete images
        for ($i = 1; $i <= 3; $i++) {
            if ($thuoc->{'image'.$i} && file_exists(public_path($thuoc->{'image'.$i}))) {
                @unlink(public_path($thuoc->{'image'.$i}));
            }
        }

        $thuoc->delete();

        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công!');
    }

    /**
     * Xử lý file Excel Import
     */
    public function import(Request $request)
    {
        // 1. Kiểm tra xem người dùng đã chọn file chưa và định dạng có đúng không
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120', // Tối đa 5MB
        ], [
            'file_excel.required' => 'Vui lòng chọn file Excel.',
            'file_excel.mimes' => 'Định dạng file không hợp lệ. Chỉ chấp nhận .xlsx, .xls, .csv',
        ]);

        try {
            // 2. Thực hiện Import bằng file class ThuocImport vừa tạo
            Excel::import(new ThuocImport, $request->file('file_excel'));

            // 3. Trả về thông báo thành công
            return redirect()->route('products.index')->with('success', 'Đã import dữ liệu thuốc từ Excel thành công!');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Lỗi do dữ liệu Excel không khớp (nếu bạn có dùng validate trong file Import)
            return redirect()->route('products.index')->with('error', 'Lỗi dữ liệu trong file Excel. Vui lòng kiểm tra lại cấu trúc file.');
        } catch (\Exception $e) {
            // Lỗi hệ thống khác (ví dụ sai tên cột, thiếu cột...)
            return redirect()->route('products.index')->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}
