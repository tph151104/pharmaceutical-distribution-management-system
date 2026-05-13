<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhieuXuat;
use App\Models\ChiTietPhieuXuat;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;
use App\Models\DonHang;
use App\Models\ThanhToan;
use App\Models\KhachTraHang;
use App\Models\KhachHang;
use App\Services\InventoryLogService;
use Illuminate\Support\Facades\DB;

class WarehouseReleaseController extends Controller
{
    /**
     * Danh sách phiếu xuất kho
     */
    public function index(Request $request)
    {
        $query = PhieuXuat::with('khachHang');

        if ($status = $request->get('status')) {
            $query->where('trang_thai_phieu_xuat', $status);
        }

        if ($fromDate = $request->get('from_date')) {
            $query->whereDate('ngay_xuat', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $query->whereDate('ngay_xuat', '<=', $toDate);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ma_phieu_xuat', 'like', "%{$search}%")
                  ->orWhereHas('khachHang', function($kh) use ($search) {
                      $kh->where('ten_kh', 'like', "%{$search}%");
                  });
            });
        }

        $phieuXuats = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.inventory.sales.index', compact('phieuXuats'));
    }

    /**
     * Xuất Excel Phiếu Xuất
     */
    public function export(Request $request)
    {
        $query = PhieuXuat::with('khachHang');

        if ($status = $request->get('status')) {
            $query->where('trang_thai_phieu_xuat', $status);
        }

        if ($fromDate = $request->get('from_date')) {
            $query->whereDate('ngay_xuat', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $query->whereDate('ngay_xuat', '<=', $toDate);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ma_phieu_xuat', 'like', "%{$search}%")
                  ->orWhereHas('khachHang', function($kh) use ($search) {
                      $kh->where('ten_kh', 'like', "%{$search}%");
                  });
            });
        }

        $phieuXuats = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Danh_Sach_Phieu_Xuat_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.sales.export', compact('phieuXuats')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Màn hình tạo phiếu xuất (Chọn đơn hàng - Redesign với filter + preview)
     */
    public function create(Request $request)
    {
        // Lấy danh sách khách hàng cho dropdown filter
        $khachHangs = KhachHang::where('trang_thai_tk', 'hoat_dong')->orderBy('ten_kh')->get();

        // Query đơn hàng đã duyệt với filters
        $query = DonHang::with(['khachHang', 'chiTiet.thuoc', 'nguoiDuyet'])
            ->where('trang_thai_dh', 'da_duyet')
            ->orderBy('updated_at', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('ngay_dat', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ngay_dat', '<=', $request->to_date);
        }
        if ($request->filled('ma_kh')) {
            $query->where('ma_kh', $request->ma_kh);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_don_hang', 'like', "%{$search}%")
                  ->orWhereHas('khachHang', function($kh) use ($search) {
                      $kh->where('ten_kh', 'like', "%{$search}%");
                  });
            });
        }

        $donHangs = $query->get();

        // Nếu truyền sẵn order_id từ nút "Tạo phiếu xuất"
        $selectedOrderId = $request->get('order_id');
        
        return view('admin.inventory.sales.create', compact('donHangs', 'selectedOrderId', 'khachHangs'));
    }

    /**
     * AJAX: Lấy chi tiết đơn hàng để preview trước khi tạo phiếu xuất
     */
    public function getOrderDetail($id)
    {
        $donHang = DonHang::with(['chiTiet.thuoc.donViTinh', 'khachHang', 'nguoiDuyet'])->findOrFail($id);
        
        $items = $donHang->chiTiet->map(function ($ct) {
            return [
                'ma_thuoc' => $ct->ma_thuoc,
                'ten_thuoc' => $ct->thuoc->ten_thuoc ?? $ct->ma_thuoc,
                'don_vi_tinh' => $ct->thuoc->donViTinh->ten_dvt ?? '',
                'so_luong' => $ct->so_luong,
                'don_gia' => $ct->don_gia,
                'thanh_tien' => $ct->so_luong * $ct->don_gia,
            ];
        });

        return response()->json([
            'ma_don_hang' => $donHang->ma_don_hang,
            'ten_kh' => $donHang->khachHang->ten_kh ?? 'N/A',
            'ngay_dat' => $donHang->ngay_dat->format('d/m/Y'),
            'nguoi_duyet' => $donHang->nguoiDuyet->ho_ten_nd ?? 'N/A',
            'tong_tien' => $donHang->tong_tien,
            'items' => $items,
        ]);
    }

    /**
     * Lưu Phiếu Xuất mới (Dự thảo)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_don_hang' => 'required|exists:don_hang,ma_don_hang',
        ]);

        $donHang = DonHang::find($request->ma_don_hang);

        if ($donHang->trang_thai_dh !== 'da_duyet') {
            return back()->withErrors(['error' => 'Đơn hàng này chưa được duyệt hoặc đã xuất kho.']);
        }

        // Kiểm tra tài khoản khách hàng có bị khóa không
        $khachHang = KhachHang::find($donHang->ma_kh);
        if ($khachHang && $khachHang->trang_thai_tk === 'vo_hieu_hoa') {
            return back()->withErrors(['error' => 'Không thể xuất hàng cho khách hàng "' . $khachHang->ten_kh . '" vì tài khoản đã bị khóa.']);
        }

        DB::beginTransaction();
        try {
            $prefix = 'PX_' . $donHang->ma_don_hang . '_';

            $lastPX = PhieuXuat::where('ma_phieu_xuat', 'like', $prefix . '%')->orderBy('ma_phieu_xuat', 'desc')->first();
            $nextId = 1;
            if ($lastPX && preg_match('/' . $prefix . '(\d+)/', $lastPX->ma_phieu_xuat, $matches)) {
                $nextId = intval($matches[1]) + 1;
            }
            $maPX = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            
            $phieuXuat = PhieuXuat::create([
                'ma_phieu_xuat' => $maPX,
                'ma_kh' => $donHang->ma_kh,
                'ma_don_hang' => $donHang->ma_don_hang,
                'nguoi_tao_phieu' => auth()->id() ?? 'NV001',
                'ngay_xuat' => now()->toDateString(),
                'tong_tien' => $donHang->tong_tien,
                'trang_thai_tt' => 'chua_tt',
                'trang_thai_phieu_xuat' => 'dang_chuan_bi',
                'image1' => '',
                'image2' => '',
                'giay_to_an_toan' => '',
                'tai_lieu_lien_quan' => ''
            ]);

            // Cập nhật trạng thái đơn hàng sang chờ xuất kho để không bị lấy lại
            $donHang->update(['trang_thai_dh' => 'dang_xuat_kho']);

            DB::commit();
            return redirect()->route('sales.show', $phieuXuat->ma_phieu_xuat)
                ->with('success', 'Đã khởi tạo phiếu xuất kho. Vui lòng phân bổ lô FEFO.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi tạo phiếu xuất: ' . $e->getMessage()]);
        }
    }

    /**
     * Chi tiết phiếu xuất
     */
    public function show($id)
    {
        $phieuXuat = PhieuXuat::with(['khachHang', 'donHang.chiTiet.thuoc'])->findOrFail($id);

        // Gợi ý FEFO
        $fefoAllocation = [];
        
        if ($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi') {
            // duyệt từng thuốc trong đơn hàng
            foreach ($phieuXuat->donHang->chiTiet as $ctdh) { 
                // Chỉ tìm các lô thuốc còn tồn ở khu vực Sẵn Sàng Bán (KV03), đang bán, còn hạn theo FEFO
                $lots = TonKhoKhuVuc::select('ton_kho_khu_vuc.*', 'ton_kho.han_su_dung', 'ton_kho.so_luong_ton as tong_ton')
                    ->join('ton_kho', function ($join) {
                        $join->on('ton_kho_khu_vuc.ma_thuoc', '=', 'ton_kho.ma_thuoc')
                             ->on('ton_kho_khu_vuc.ma_phieu_nhap', '=', 'ton_kho.ma_phieu_nhap')
                             ->on('ton_kho_khu_vuc.so_lo', '=', 'ton_kho.so_lo');
                    })
                    ->where('ton_kho_khu_vuc.ma_thuoc', $ctdh->ma_thuoc)
                    ->where('ton_kho_khu_vuc.ma_khu_vuc', 'KV03_THANH_PHAM')
                    ->where('ton_kho.trang_thai_lo', 'dang_ban')
                    ->where('ton_kho.han_su_dung', '>=', now()->toDateString())
                    ->where('ton_kho_khu_vuc.so_luong', '>', 0)
                    //FEFO: Ưu tiên lô hạn gần lên đầu
                    ->orderBy('ton_kho.han_su_dung', 'asc')
                    ->get();
                
                $needed = $ctdh->so_luong;
                $allocatedLots = [];

                foreach ($lots as $lot) {
                    if ($needed <= 0) break;

                    $take = min($needed, $lot->so_luong); 

                    $allocatedLots[] = [
                        'so_lo' => $lot->so_lo,
                        'han_su_dung' => $lot->han_su_dung,
                        'so_luong_ton' => $lot->so_luong, // Hiển thị tồn của KV03
                        'so_luong_xuat' => $take,
                        'ma_phieu_nhap' => $lot->ma_phieu_nhap
                    ];
                    $needed -= $take;
                }

                $fefoAllocation[$ctdh->ma_thuoc] = [
                    'thuoc' => $ctdh->thuoc,
                    'so_luong_can_xuat' => $ctdh->so_luong,
                    'don_gia' => $ctdh->don_gia,
                    'thieu_hang' => $needed > 0,
                    'so_luong_thieu' => $needed,
                    'allocated' => $allocatedLots
                ];
            }
        } else {
            // Nếu đã xuất, lấy trực tiếp từ chiTiet
            $phieuXuat->load('chiTiet.thuoc');
        }

        return view('admin.inventory.sales.show', compact('phieuXuat', 'fefoAllocation'));
    }

    /**
     * Xác nhận xuất kho
     */
    public function confirm(Request $request, $id)
    {
        $phieuXuat = PhieuXuat::findOrFail($id);

        if ($phieuXuat->trang_thai_phieu_xuat !== 'dang_chuan_bi') {
            return back()->withErrors(['error' => 'Chỉ có thể xác nhận xuất kho cho phiếu đang chuẩn bị.']);
        }

        $request->validate([
            'image1' => 'nullable|image',
            'image2' => 'nullable|image',
            'giay_to_an_toan' => 'nullable|file',
            'tai_lieu_lien_quan' => 'nullable|file',
            'allocations' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Lưu ảnh/tài liệu
            foreach (['image1', 'image2', 'giay_to_an_toan', 'tai_lieu_lien_quan'] as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $name = time() . "_{$field}." . $file->extension();
                    $file->move(public_path('uploads/exports'), $name);
                    $phieuXuat->$field = 'uploads/exports/' . $name;
                }
            }

            // 2. Trừ tồn kho & Sinh chi_tiet_phieu_xuat
            $allocations = $request->input('allocations'); 
            // format: ['ma_thuoc' => ['so_lo' => ['so_luong_xuat', 'don_gia', 'ma_phieu_nhap']]]

            foreach ($allocations as $maThuoc => $lots) {
                foreach ($lots as $soLo => $info) {
                    $soLuongXuat = (int) $info['so_luong_xuat'];
                    if ($soLuongXuat <= 0) continue;

                    $tonKho = TonKho::where('ma_thuoc', $maThuoc)
                        ->where('so_lo', $soLo)
                        ->where('ma_phieu_nhap', $info['ma_phieu_nhap'])
                        ->lockForUpdate() // Khóa dòng để tránh tranh chấp khi nhiều người cùng xuất
                        ->first();

                    $tonKhoKhuVuc = TonKhoKhuVuc::where('ma_thuoc', $maThuoc)
                        ->where('so_lo', $soLo)
                        ->where('ma_phieu_nhap', $info['ma_phieu_nhap'])
                        ->where('ma_khu_vuc', 'KV03_THANH_PHAM')
                        ->lockForUpdate() // Khóa dòng khu vực để đảm bảo đồng bộ
                        ->first();

                    // Check nếu thiếu hàng ở khu vực sẵn sàng bán
                    if (!$tonKho || !$tonKhoKhuVuc || $tonKhoKhuVuc->so_luong < $soLuongXuat) {
                        throw new \Exception("Lô {$soLo} của thuốc {$maThuoc} không đủ hàng tại khu vực Sẵn Sàng Bán (KV03).");
                    }

                    // Trừ tồn KV03
                    $tonKhoKhuVuc->so_luong -= $soLuongXuat;
                    $tonKhoKhuVuc->save();

                    // Trừ tồn Tổng
                    $tonTruoc = $tonKho->so_luong_ton;
                    $tonKho->so_luong_ton -= $soLuongXuat;
                    $tonKho->so_luong_da_xuat += $soLuongXuat;
                    $tonKho->save();

                    // Ghi log xuất kho
                    InventoryLogService::logMovement(
                        $maThuoc,
                        $soLo,
                        $phieuXuat->nguoi_tao_phieu ?? 'NV001',
                        $phieuXuat->ma_phieu_xuat,
                        'xuat',
                        'phieu_xuat',
                        $soLuongXuat,
                        $tonTruoc,
                        $tonKho->so_luong_ton,
                        $info['don_gia'],
                        'Xuất kho (Bán sỉ)'
                    );

                    // Lưu chi tiết chứng từ
                    ChiTietPhieuXuat::create([
                        'ma_phieu_xuat' => $phieuXuat->ma_phieu_xuat,
                        'ma_thuoc' => $maThuoc,
                        'so_lo' => $soLo,
                        'han_su_dung' => $tonKho->han_su_dung,
                        'so_luong_xuat' => $soLuongXuat,
                        'don_gia_ban' => $info['don_gia'],
                        'thanh_tien' => $soLuongXuat * $info['don_gia'],
                    ]);
                }
            }

            // 3. Cập nhật phiếu xuất
            $phieuXuat->trang_thai_phieu_xuat = 'da_xuat_kho';
            $phieuXuat->save();

            // 4. Cập nhật đơn hàng (nếu có)
            // Không đổi trạng thái Đơn Hàng ở bước xuất kho, vẫn giữ 'dang_xuat_kho'
            // Chờ đến khi nhân viên kho bấm chuyển Vận chuyển thì mới đổi trạng thái.

            // 5. Sinh công nợ thanh toán (nếu tổng tiền > 0)
            if ($phieuXuat->tong_tien > 0) {
                // Tạo mã thanh toán
                $maTT = 'TTX' . str_pad(ThanhToan::where('loai_thanh_toan', 'xuat')->count() + 1, 5, '0', STR_PAD_LEFT);
                
                ThanhToan::create([
                    'ma_thanh_toan' => $maTT,
                    'loai_thanh_toan' => 'xuat',
                    'ma_phieu_xuat' => $phieuXuat->ma_phieu_xuat,
                    'tong_tien' => $phieuXuat->tong_tien,
                    'so_tien_tt' => 0,
                    'so_tien_con_no' => $phieuXuat->tong_tien,
                    'trang_thai_tt' => 'con_no',
                    'phuong_thuc_tt' => null,
                    'ngay_thanh_toan' => now(), 
                ]);
            }

            DB::commit();
            return redirect()->route('sales.show', $id)
                ->with('success', 'Đã xuất kho và trừ tồn lô thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi xuất kho: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * In phiếu xuất kho
     */
    public function print($id)
    {
        $phieuXuat = PhieuXuat::with(['khachHang', 'chiTiet.thuoc'])->findOrFail($id);
        
        if ($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi') {
            return back()->withErrors(['error' => 'Phiếu xuất chưa hoàn tất, không thể in!']);
        }

        return view('admin.inventory.sales.print', compact('phieuXuat'));
    }

    /**
     * Chuyển trạng thái sang Đang vận chuyển
     */
    public function markAsShipping($id)
    {
        $phieuXuat = PhieuXuat::findOrFail($id);
        
        if ($phieuXuat->trang_thai_phieu_xuat !== 'da_xuat_kho') {
            return back()->withErrors(['error' => 'Chỉ phiếu "Đã xuất kho" mới có thể chuyển trạng thái vận chuyển.']);
        }

        DB::beginTransaction();
        try {
            $phieuXuat->trang_thai_phieu_xuat = 'da_van_chuyen';
            $phieuXuat->save();

            if ($phieuXuat->ma_don_hang) {
                $donHang = DonHang::find($phieuXuat->ma_don_hang);
                if ($donHang) {
                    $donHang->trang_thai_dh = 'dang_van_chuyen';
                    $donHang->save();
                }
            }

            DB::commit();
            return back()->with('success', 'Đã chuyển trạng thái Đang vận chuyển thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Xác nhận giao hàng hoàn thành
     */
    public function markAsCompleted($id)
    {
        $phieuXuat = PhieuXuat::findOrFail($id);
        
        if (!in_array($phieuXuat->trang_thai_phieu_xuat, ['da_van_chuyen', 'da_xuat_kho'])) {
            return back()->withErrors(['error' => 'Trạng thái không hợp lệ để xác nhận hoàn thành (chỉ thao tác được khi Đang vận chuyển hoặc Đã xuất kho).']);
        }

        DB::beginTransaction();
        try {
            $phieuXuat->trang_thai_phieu_xuat = 'da_hoan_thanh';
            $phieuXuat->save();

            DB::commit();
            return back()->with('success', 'Đã xác nhận Giao hàng hoàn thành.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Hoàn tác xác nhận hoàn thành giao hàng
     */
    public function undoCompleted($id)
    {
        $phieuXuat = PhieuXuat::findOrFail($id);
        
        if ($phieuXuat->trang_thai_phieu_xuat !== 'da_hoan_thanh') {
            return back()->withErrors(['error' => 'Chỉ có thể hoàn tác từ trạng thái Đã hoàn thành.']);
        }

        if ($phieuXuat->ma_don_hang && KhachTraHang::where('ma_don_hang', $phieuXuat->ma_don_hang)->exists()) {
             return back()->withErrors(['error' => 'Không thể hoàn tác vì đơn hàng này đã được khách yêu cầu trả hàng.']);
        }

        DB::beginTransaction();
        try {
            $phieuXuat->trang_thai_phieu_xuat = 'da_xuat_kho';
            $phieuXuat->save();

            if ($phieuXuat->ma_don_hang) {
                $donHang = DonHang::find($phieuXuat->ma_don_hang);
                if ($donHang && $donHang->trang_thai_dh === 'da_hoan_thanh') {
                    $donHang->trang_thai_dh = 'dang_xuat_kho'; 
                    $donHang->save();
                }
            }

            DB::commit();
            return back()->with('success', 'Đã hoàn tác trạng thái hoàn thành giao hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Hủy / Xóa phiếu nháp
     */
    public function destroy($id)
    {
        $phieuXuat = PhieuXuat::findOrFail($id);

        if ($phieuXuat->trang_thai_phieu_xuat !== 'dang_chuan_bi') {
            return back()->withErrors(['error' => 'Chỉ phiếu nháp đang chuẩn bị mới được xóa.']);
        }

        DB::beginTransaction();
        try {
            if ($phieuXuat->ma_don_hang) {
                $donHang = DonHang::find($phieuXuat->ma_don_hang);
                if ($donHang && $donHang->trang_thai_dh === 'dang_xuat_kho') {
                    $donHang->trang_thai_dh = 'da_duyet';
                    $donHang->save();
                }
            }
            $phieuXuat->delete();

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Đã xóa phiếu xuất nháp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Đưa phiếu xuất về trạng thái "Đang chuẩn bị" (hoàn tác xuất kho)
     */
    public function revertToPreparing($id)
    {
        $phieuXuat = PhieuXuat::with('chiTiet')->findOrFail($id);

        if ($phieuXuat->trang_thai_phieu_xuat !== 'da_xuat_kho') {
            return back()->withErrors(['error' => 'Chỉ có thể đưa về "Đang chuẩn bị" khi phiếu đang ở trạng thái "Đã xuất kho".']);
        }

        DB::beginTransaction();
        try {
            // 1. Hoàn trả tồn kho cho từng dòng chi tiết
            foreach ($phieuXuat->chiTiet as $ct) {
                $tonKho = TonKho::where('ma_thuoc', $ct->ma_thuoc)
                    ->where('so_lo', $ct->so_lo)
                    ->first();

                if ($tonKho) {
                    $tonKhoKhuVuc = TonKhoKhuVuc::where('ma_thuoc', $tonKho->ma_thuoc)
                        ->where('so_lo', $tonKho->so_lo)
                        ->where('ma_phieu_nhap', $tonKho->ma_phieu_nhap)
                        ->where('ma_khu_vuc', 'KV03_THANH_PHAM')
                        ->first();

                    // Hoàn trả tồn Kho Tổng
                    $tonTruoc = $tonKho->so_luong_ton;
                    $tonKho->so_luong_ton += $ct->so_luong_xuat;
                    $tonKho->so_luong_da_xuat -= $ct->so_luong_xuat;
                    if ($tonKho->so_luong_da_xuat < 0) $tonKho->so_luong_da_xuat = 0;
                    $tonKho->save();

                    // Hoàn trả tồn Khu Vực KV03
                    if ($tonKhoKhuVuc) {
                        $tonKhoKhuVuc->so_luong += $ct->so_luong_xuat;
                        $tonKhoKhuVuc->save();
                    } else {
                        TonKhoKhuVuc::create([
                            'ma_thuoc' => $tonKho->ma_thuoc,
                            'ma_phieu_nhap' => $tonKho->ma_phieu_nhap,
                            'so_lo' => $tonKho->so_lo,
                            'ma_khu_vuc' => 'KV03_THANH_PHAM',
                            'so_luong' => $ct->so_luong_xuat
                        ]);
                    }

                    // Ghi log hoàn trả
                    InventoryLogService::logMovement(
                        $ct->ma_thuoc,
                        $ct->so_lo,
                        auth()->id() ?? 'NV001',
                        $phieuXuat->ma_phieu_xuat,
                        'dieu_chinh',
                        'phieu_xuat',
                        $ct->so_luong_xuat,
                        $tonTruoc,
                        $tonKho->so_luong_ton,
                        $ct->don_gia_ban,
                        '[Hoàn tác xuất kho] Đưa phiếu về trạng thái Đang chuẩn bị'
                    );
                }
            }

            // 2. Xóa chi tiết phiếu xuất
            ChiTietPhieuXuat::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)->delete();

            // 3. Xóa bản ghi thanh toán liên quan (công nợ)
            ThanhToan::where('ma_phieu_xuat', $phieuXuat->ma_phieu_xuat)->delete();

            // 4. Đưa phiếu xuất về trạng thái đang chuẩn bị
            $phieuXuat->trang_thai_phieu_xuat = 'dang_chuan_bi';
            $phieuXuat->trang_thai_tt = 'chua_tt';
            $phieuXuat->save();

            // 5. Đưa đơn hàng về trạng thái đang xuất kho (nếu có)
            if ($phieuXuat->ma_don_hang) {
                $donHang = DonHang::find($phieuXuat->ma_don_hang);
                if ($donHang) {
                    $donHang->trang_thai_dh = 'dang_xuat_kho';
                    $donHang->save();
                }
            }

            DB::commit();
            return redirect()->route('sales.show', $id)
                ->with('success', 'Đã hoàn tác xuất kho. Phiếu đã trở về trạng thái "Đang chuẩn bị". Tồn kho đã được khôi phục.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi hoàn tác: ' . $e->getMessage()]);
        }
    }
}

