<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KhachHangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KhachHang::query();

        // Search text
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_kh', 'like', "%{$search}%")
                  ->orWhere('ten_kh', 'like', "%{$search}%")
                  ->orWhere('ten_dang_nhap', 'like', "%{$search}%")
                  ->orWhere('dien_thoai', 'like', "%{$search}%");
            });
        }

        // Filter Type
        if ($request->has('loai_kh') && $request->loai_kh != '') {
            $query->where('loai_kh', $request->loai_kh);
        }

        // Filter Status
        if ($request->has('trang_thai_tk') && $request->trang_thai_tk != '') {
            $query->where('trang_thai_tk', $request->trang_thai_tk);
        }

        $khachHangs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.inventory.customers.index', compact('khachHangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);

        $request->validate([
            'ten_kh' => 'required|string|max:255',
            'loai_kh' => 'required|in:nha_thuoc,dai_ly,phong_kham,benh_vien',
            'dia_chi' => 'required|string|max:255',
            'ma_so_thue' => 'required|string|max:50',
            'giay_phep_hd_image' => 'nullable|image|max:2048',
            'hinh_dai_dien' => 'nullable|image|max:2048',
            'dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:191',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            $data = $request->except(['mat_khau', 'ten_dang_nhap', 'giay_phep_hd_image', 'hinh_dai_dien', 'trang_thai_tk']);

            // Update Password if provided
            if ($request->filled('mat_khau')) {
                $request->validate(['mat_khau' => 'string|min:6']);
                $data['mat_khau'] = Hash::make($request->mat_khau);
            }

            // Handle File Uploads
            if ($request->hasFile('giay_phep_hd_image')) {
                $file = $request->file('giay_phep_hd_image');
                $filename = time() . '_giayphep_' . $khachHang->ma_kh . '.' . $file->extension();
                $file->move(public_path('uploads/customers'), $filename);
                $data['giay_phep_hd_image'] = 'uploads/customers/' . $filename;
            }

            if ($request->hasFile('hinh_dai_dien')) {
                $file2 = $request->file('hinh_dai_dien');
                $filename2 = time() . '_avatar_' . $khachHang->ma_kh . '.' . $file2->extension();
                $file2->move(public_path('uploads/customers'), $filename2);
                $data['hinh_dai_dien'] = 'uploads/customers/' . $filename2;
            }

            $khachHang->update($data);
            return redirect()->route('customers.index')->with('success', 'Đã cập nhật thông tin khách hàng thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Lỗi khi cập nhật khách hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật trạng thái tài khoản (Duyệt / Khóa)
     */
    public function updateStatus(Request $request, $id)
    {
        $khachHang = KhachHang::findOrFail($id);
        
        $request->validate([
            'trang_thai_tk' => 'required|in:cho_duyet,hoat_dong,vo_hieu_hoa'
        ]);

        $khachHang->trang_thai_tk = $request->trang_thai_tk;
        $khachHang->save();

        return back()->with('success', 'Đã thay đổi trạng thái tài khoản thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $khachHang = KhachHang::findOrFail($id);
        
        try {
            $khachHang->delete();
            return redirect()->route('customers.index')->with('success', 'Đã xoá khách hàng thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi khi xoá khách hàng: ' . $e->getMessage()]);
        }
    }

    /**
     * [Wholesale] Xem trang thông tin cá nhân của khách hàng
     */
    public function profile()
    {
        $customer = auth('customer')->user();
        return view('wholesale.profile', compact('customer'));
    }

    /**
     * [Wholesale] Cập nhật thông tin cá nhân của khách hàng
     */
    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'ten_kh'             => 'required|string|max:255',
            'dia_chi'            => 'required|string|max:255',
            'dien_thoai'         => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:191',
            'ma_so_thue'         => 'nullable|string|max:50',
            'loai_kh'            => 'required|in:nha_thuoc,dai_ly,phong_kham,benh_vien',
            'ghi_chu'            => 'nullable|string',
            'giay_phep_hd_image' => 'nullable|image|max:2048',
            'hinh_dai_dien'      => 'nullable|image|max:2048',
            'mat_khau_moi'       => 'nullable|string|min:6|confirmed',
            'mat_khau_moi_confirmation' => 'nullable|string',
        ]);

        $data = $request->only([
            'ten_kh', 'dia_chi', 'dien_thoai', 'email',
            'ma_so_thue', 'loai_kh', 'ghi_chu'
        ]);

        // Đổi mật khẩu
        if ($request->filled('mat_khau_cu') && $request->filled('mat_khau_moi')) {
            if (!Hash::check($request->mat_khau_cu, $customer->mat_khau)) {
                return back()->withErrors(['mat_khau_cu' => 'Mật khẩu cũ không đúng.'])->withInput();
            }
            $data['mat_khau'] = Hash::make($request->mat_khau_moi);
        }

        // Upload giấy phép hoạt động
        if ($request->hasFile('giay_phep_hd_image')) {
            $file = $request->file('giay_phep_hd_image');
            $filename = time() . '_giayphep_' . $customer->ma_kh . '.' . $file->extension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['giay_phep_hd_image'] = 'uploads/customers/' . $filename;
        }

        // Upload ảnh đại diện
        if ($request->hasFile('hinh_dai_dien')) {
            $file2 = $request->file('hinh_dai_dien');
            $filename2 = time() . '_avatar_' . $customer->ma_kh . '.' . $file2->extension();
            $file2->move(public_path('uploads/customers'), $filename2);
            $data['hinh_dai_dien'] = 'uploads/customers/' . $filename2;
        }

        $customer->update($data);

        return back()->with('success', 'Đã cập nhật thông tin thành công!');
    }
}
