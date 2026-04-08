<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Kiểm tra role của người dùng nội bộ (guard admin).
     * Sử dụng: ->middleware('role:1,2,5') → chỉ Admin, NV Kho, Trưởng Kho
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  Danh sách role IDs được phép
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('admin.auth.login');
        }

        // Flatten: nếu truyền '1,2,5' thì tách ra thành [1, 2, 5]
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $allowedRoles[] = (int) trim($r);
            }
        }

        if (!in_array((int) $user->role, $allowedRoles)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        return $next($request);
    }
}
