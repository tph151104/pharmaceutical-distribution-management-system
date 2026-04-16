<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FeatureToggle;

class CheckFeatureMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $featureKey
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $featureKey)
    {
        // Admin (role = 1) always bypasses the maintenance block
        if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->hasRole(1)) {
            return $next($request);
        }

        // Check if feature is under maintenance
        $feature = FeatureToggle::find($featureKey);
        
        if ($feature && !$feature->trang_thai) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tính năng [' . $feature->ten_chuc_nang . '] đang được bảo trì.'
                ], 503);
            }

            return redirect()->back()->with('error', 'Chức năng [' . $feature->ten_chuc_nang . '] đang được bảo trì theo lịch.');
        }

        return $next($request);
    }
}
