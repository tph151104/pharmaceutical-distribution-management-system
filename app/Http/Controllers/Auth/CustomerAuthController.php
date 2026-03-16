<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerAuthController extends Controller
{
    /**
     * Hiển thị trang đăng ký Khách hàng
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý submit Đăng ký Khách hàng
     */
    public function register(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string|max:191|unique:khach_hang',
            'mat_khau' => 'required|string|min:6|confirmed',
            'ten_kh' => 'required|string|max:255',
            'loai_kh' => 'required|in:nha_thuoc,dai_ly,phong_kham,benh_vien',
            'dien_thoai' => 'required|string|max:20',
            'email' => 'required|email|max:191',
            'dia_chi' => 'required|string|max:255',
            'ma_so_thue' => 'required|string|max:50',
            'giay_phep_hd_image' => 'required|image|max:2048', // Bắt buộc file GPKD
            'hinh_dai_dien' => 'nullable|image|max:2048',
        ], [
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại trong hệ thống.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'giay_phep_hd_image.required' => 'Vui lòng cung cấp hình ảnh Giấy phép hoạt động/GPKD.'
        ]);

        DB::beginTransaction();
        try {
            // Tự động sinh mã KH
            $lastKh = KhachHang::orderBy('ma_kh', 'desc')->first();
            $nextId = 1;
            if ($lastKh && preg_match('/^KH(\d+)$/', $lastKh->ma_kh, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maKh = 'KH' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            $data = $request->except(['mat_khau', 'mat_khau_confirmation', 'giay_phep_hd_image', 'hinh_dai_dien', 'terms']);
            $data['ma_kh'] = $maKh;
            $data['mat_khau'] = Hash::make($request->mat_khau);
            $data['trang_thai_tk'] = 'cho_duyet'; // Mặc định phải chờ admin duyệt

            // Xử lý upload Giấy phép KD 
            if ($request->hasFile('giay_phep_hd_image')) {
                $file = $request->file('giay_phep_hd_image');
                $filename = time() . '_giayphep_' . $maKh . '.' . $file->extension();
                $file->move(public_path('uploads/customers'), $filename);
                $data['giay_phep_hd_image'] = 'uploads/customers/' . $filename;
            }

            // Xử lý upload Ảnh đại diện/Logo
            if ($request->hasFile('hinh_dai_dien')) {
                $file2 = $request->file('hinh_dai_dien');
                $filename2 = time() . '_avatar_' . $maKh . '.' . $file2->extension();
                $file2->move(public_path('uploads/customers'), $filename2);
                $data['hinh_dai_dien'] = 'uploads/customers/' . $filename2;
            }

            KhachHang::create($data);

            DB::commit();
            return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công! Tài khoản của bạn đang chờ quản trị viên xét duyệt. Vui lòng quay lại đăng nhập sau.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi đăng ký: ' . $e->getMessage()]);
        }
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý Đăng nhập Khách hàng
     */
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ]);

        $credentials = [
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'password' => $request->mat_khau, // Auth facade ánh xạ sang getAuthPassword()
        ];
        
        $remember = $request->filled('remember');

        // Thử đăng nhập bằng Guard 'customer'
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $customer = Auth::guard('customer')->user();

            // Kiểm tra trạng thái tài khoản
            if ($customer->trang_thai_tk === 'cho_duyet') {
                Auth::guard('customer')->logout();
                return back()->withInput()->withErrors(['error' => 'Tài khoản của bạn đang chờ xét duyệt.']);
            }

            if ($customer->trang_thai_tk === 'vo_hieu_hoa') {
                Auth::guard('customer')->logout();
                return back()->withInput()->withErrors(['error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin.']);
            }

            $request->session()->regenerate();

            // Đăng nhập hợp lệ, chuyển hướng sang giao diện khách hàng sỉ
            return redirect()->route('wholesale.catalog');
        }

        return back()->withInput()->withErrors([
            'error' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ]);
    }

    /**
     * Đăng xuất Khách hàng
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
