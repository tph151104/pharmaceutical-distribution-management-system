<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDung;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'ho_ten_nd' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'sdt' => 'nullable|string|max:20',
        ]);

        $user = Auth::guard('admin')->user();
        
        $user->ho_ten_nd = $request->ho_ten_nd;
        $user->email = $request->email;
        $user->sdt = $request->sdt;
        
        // Save using Eloquent to update specific fields without side effects on password etc.
        $user->save();

        return redirect()->route('account.profile')->with('success', 'Đã cập nhật thông tin cá nhân thành công.');
    }

    public function password()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.account.password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'
        ]);

        $user = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $user->mat_khau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->mat_khau = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.password')->with('success', 'Đã thay đổi mật khẩu thành công.');
    }
}
