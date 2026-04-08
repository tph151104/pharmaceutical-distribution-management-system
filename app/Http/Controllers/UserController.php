<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Danh sách người dùng nội bộ
     */
    public function index(Request $request)
    {
        $query = NguoiDung::query();

        // Lọc theo role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten_nd', 'like', "%{$search}%")
                  ->orWhere('ten_dang_nhap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tạo người dùng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten_nd'     => 'required|string|max:255',
            'ten_dang_nhap' => 'required|string|max:191|unique:nguoi_dung',
            'mat_khau'      => 'required|string|min:6',
            'email'         => 'required|email|max:191|unique:nguoi_dung',
            'sdt'           => 'required|string|max:20',
            'role'          => 'required|integer|in:1,2,3,4,5',
        ], [
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.unique'         => 'Email đã được sử dụng.',
            'mat_khau.min'         => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        DB::beginTransaction();
        try {
            // Tự động sinh mã người dùng
            $last = NguoiDung::orderBy('ma_nguoi_dung', 'desc')->first();
            $nextId = 1;
            if ($last && preg_match('/^ND(\d+)$/', $last->ma_nguoi_dung, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maNguoiDung = 'ND' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            NguoiDung::create([
                'ma_nguoi_dung' => $maNguoiDung,
                'ho_ten_nd'     => $request->ho_ten_nd,
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau'      => Hash::make($request->mat_khau),
                'email'         => $request->email,
                'sdt'           => $request->sdt,
                'role'          => $request->role,
                'trang_thai'    => 'cho_phep_hd',
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, $id)
    {
        $user = NguoiDung::where('ma_nguoi_dung', $id)->firstOrFail();

        $request->validate([
            'ho_ten_nd'     => 'required|string|max:255',
            'email'         => 'required|email|max:191|unique:nguoi_dung,email,' . $id . ',ma_nguoi_dung',
            'sdt'           => 'required|string|max:20',
            'role'          => 'required|integer|in:1,2,3,4,5',
        ]);

        $data = $request->only(['ho_ten_nd', 'email', 'sdt', 'role']);

        // Chỉ cập nhật mật khẩu nếu có nhập
        if ($request->filled('mat_khau')) {
            $request->validate(['mat_khau' => 'string|min:6']);
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa người dùng
     */
    public function destroy($id)
    {
        $user = NguoiDung::where('ma_nguoi_dung', $id)->firstOrFail();

        // Không cho xóa chính mình
        if (auth()->guard('admin')->user()->ma_nguoi_dung === $id) {
            return back()->withErrors(['error' => 'Không thể xóa tài khoản đang đăng nhập.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng.');
    }

    /**
     * Bật/tắt trạng thái hoạt động
     */
    public function toggleStatus($id)
    {
        $user = NguoiDung::where('ma_nguoi_dung', $id)->firstOrFail();

        // Không cho vô hiệu hóa chính mình
        if (auth()->guard('admin')->user()->ma_nguoi_dung === $id) {
            return back()->withErrors(['error' => 'Không thể vô hiệu hóa tài khoản đang đăng nhập.']);
        }

        $user->trang_thai = $user->trang_thai === 'cho_phep_hd' ? 'vo_hieu_hoa' : 'cho_phep_hd';
        $user->save();

        $statusText = $user->trang_thai === 'cho_phep_hd' ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('admin.users.index')->with('success', "Đã {$statusText} tài khoản {$user->ho_ten_nd}.");
    }
}
