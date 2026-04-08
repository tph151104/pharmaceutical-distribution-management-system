<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng (Admin)
     */
    public function index(Request $request)
    {
        $query = DonHang::with('khachHang');

        if ($status = $request->get('status')) {
            $query->where('trang_thai_dh', $status);
        }

        $donHangs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.inventory.orders.index', compact('donHangs'));
    }

    /**
     * Chi tiết đơn hàng (Admin)
     */
    public function show($id)
    {
        $donHang = DonHang::with(['khachHang', 'chiTiet.thuoc'])->findOrFail($id);
        return view('admin.inventory.orders.show', compact('donHang'));
    }

    /**
     * Duyệt đơn hàng
     */
    public function approve($id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->trang_thai_dh !== 'cho_xu_ly') {
            return back()->withErrors(['error' => 'Chỉ có thể duyệt đơn hàng đang Chờ xử lý.']);
        }

        DB::beginTransaction();
        try {
            // Đổi trạng thái đơn hàng sang đã duyệt (Kế toán duyệt)
            // Lát thủ kho sẽ tạo Phiếu xuất từ đơn hàng này.
            $donHang->update(['trang_thai_dh' => 'da_duyet']);

            DB::commit();
            return back()->with('success', 'Đã duyệt đơn hàng ' . $donHang->ma_don_hang . ' thành công. Đơn hàng đang chờ thủ kho xuất hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi duyệt đơn: ' . $e->getMessage()]);
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $donHang = DonHang::findOrFail($id);

        if (in_array($donHang->trang_thai_dh, ['dang_van_chuyen', 'da_hoan_thanh', 'da_huy'])) {
            $statusName = $donHang->tenTrangThai;
            return back()->withErrors(['error' => "Không thể hủy đơn hàng đang ở trạng thái {$statusName}."]);
        }

        $donHang->update(['trang_thai_dh' => 'da_huy']);

        return back()->with('success', 'Đã hủy đơn hàng ' . $donHang->ma_don_hang);
    }

    /**
     * Xuất danh sách đơn hàng ra Excel (CSV)
     */
    public function export(Request $request)
    {
        $query = DonHang::with(['khachHang', 'chiTiet']);

        if ($status = $request->get('status')) {
            $query->where('trang_thai_dh', $status);
        }

        $donHangs = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Danh_Sach_Don_Hang_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.orders.export', compact('donHangs')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * In đơn hàng
     */
    public function print($id)
    {
        $donHang = DonHang::with(['khachHang', 'chiTiet.thuoc'])->findOrFail($id);
        
        // Chỉ cho phép in nếu đơn hàng đã hoàn thành (tùy nghiệp vụ, ở đây mở rộng một chút hoặc bắt buộc 'da_hoan_thanh')
        // if ($donHang->trang_thai_dh !== 'da_hoan_thanh') {
        //     return back()->withErrors(['error' => 'Chỉ có thể in đơn hàng đã hoàn thành.']);
        // }

        return view('admin.inventory.orders.print', compact('donHang'));
    }
}
