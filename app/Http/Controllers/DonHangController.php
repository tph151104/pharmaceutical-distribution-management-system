<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;

class DonHangController extends Controller
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

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Đổi trạng thái đơn hàng sang đã duyệt (Kế toán duyệt)
            // Lát thủ kho sẽ tạo Phiếu xuất từ đơn hàng này.
            $donHang->update(['trang_thai_dh' => 'da_duyet']);

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Đã duyệt đơn hàng ' . $donHang->ma_don_hang . ' thành công. Đơn hàng đang chờ thủ kho xuất hàng.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi duyệt đơn: ' . $e->getMessage()]);
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $donHang = DonHang::findOrFail($id);

        if (in_array($donHang->trang_thai_dh, ['da_hoan_thanh', 'da_huy'])) {
            return back()->withErrors(['error' => 'Không thể hủy đơn hàng đã hoàn thành hoặc đã hủy.']);
        }

        $donHang->update(['trang_thai_dh' => 'da_huy']);

        return back()->with('success', 'Đã hủy đơn hàng ' . $donHang->ma_don_hang);
    }

    /**
     * Xuất danh sách đơn hàng ra Excel (CSV)
     */
    public function export(Request $request)
    {
        $query = DonHang::with('khachHang');

        if ($status = $request->get('status')) {
            $query->where('trang_thai_dh', $status);
        }

        $donHangs = $query->orderBy('created_at', 'desc')->get();

        $filename = "don_hang_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Mã đơn hàng', 'Khách hàng', 'Số điện thoại', 'Ngày đặt', 'Tổng tiền', 'Trạng thái'];

        $callback = function() use($donHangs, $columns) {
            $file = fopen('php://output', 'w');
            
            // Thêm BOM để Excel đọc đúng tiếng Việt UTF-8
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, $columns);

            foreach ($donHangs as $dh) {
                fputcsv($file, [
                    $dh->ma_don_hang,
                    $dh->khachHang->ten_kh ?? '',
                    $dh->khachHang->dien_thoai ?? '',
                    $dh->ngay_dat ? $dh->ngay_dat->format('d/m/Y H:i') : '',
                    $dh->tong_tien,
                    $dh->tenTrangThai
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
