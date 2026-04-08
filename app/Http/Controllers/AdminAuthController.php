<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập Admin/Nhân viên
     */
    public function showLoginForm()
    {
        // Nếu đã đăng nhập rồi thì redirect về dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.admin_login');
    }

    /**
     * Xử lý đăng nhập Admin/Nhân viên
     */
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = [
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'password' => $request->mat_khau,
        ];

        $remember = $request->filled('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();

            // Kiểm tra trạng thái tài khoản
            if ($user->trang_thai === 'vo_hieu_hoa') {
                Auth::guard('admin')->logout();
                return back()->withInput()->withErrors([
                    'error' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withInput()->withErrors([
            'error' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ]);
    }

    /**
     * Đăng xuất Admin/Nhân viên
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.auth.login');
    }
}
