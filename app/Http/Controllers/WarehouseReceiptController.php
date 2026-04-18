<?php

namespace App\Http\Controllers;

use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;
use App\Models\LichSuDichChuyenKho;
use App\Models\NhaCungCap;
use App\Models\NhomThuoc;
use App\Models\Thuoc;
use App\Services\InventoryLogService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseReceiptController extends Controller
{
    /**
     * Danh sách phiếu nhập
     */
    public function index(Request $request)
    {
        $query = PhieuNhap::with(['nhaCungCap', 'chiTiet'])->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai_phieu_nhap', $request->trang_thai);
        }       
        if ($request->has('search_ncc') && $request->search_ncc != '') {
            $query->whereHas('nhaCungCap', function($q) use ($request) {
                $q->where('ten_ncc', 'like', '%' . $request->search_ncc . '%');
            });
        }
        
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('ngay_nhap', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('ngay_nhap', '<=', $request->to_date);
        }

        $phieuNhaps = $query->paginate(15);
        return view('admin.inventory.imports.index', compact('phieuNhaps'));
    }

    /**
     * Xuất Excel Phiếu Nhập
     */
    public function export(Request $request)
    {
        $query = PhieuNhap::with(['nhaCungCap', 'chiTiet'])->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('ma_phieu_nhap', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai_phieu_nhap', $request->trang_thai);
        }       
        if ($request->has('search_ncc') && $request->search_ncc != '') {
            $query->whereHas('nhaCungCap', function($q) use ($request) {
                $q->where('ten_ncc', 'like', '%' . $request->search_ncc . '%');
            });
        }
        
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('ngay_nhap', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('ngay_nhap', '<=', $request->to_date);
        }

        $phieuNhaps = $query->get();
        $fileName = 'Danh_Sach_Phieu_Nhap_' . date('Y_m_d_H_i') . '.xls';

        return response(view('admin.inventory.imports.export', compact('phieuNhaps')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Mở form Lập phiếu nhập
     */
    public function create()
    {
        $nhaCungCaps = NhaCungCap::all();
        $thuocs = Thuoc::all();
        $nhom_thuocs = NhomThuoc::all();//để tìm kiếm
        return view('admin.inventory.imports.create', compact('nhaCungCaps', 'thuocs','nhom_thuocs') );
    }

    /**
     * AJAX: Xử lý tìm kiếm thuốc nâng cao
     */
    public function advancedSearch(Request $request)
    {
        // Lấy thông tin thuốc kèm theo Nhóm và ĐVT để hiển thị ra bảng
        $query = Thuoc::with(['nhomThuoc', 'donViTinh']);

        // Lọc theo từ khóa (Mã hoặc Tên)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', "%{$keyword}%")
                  ->orWhere('ma_thuoc', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo Nhóm thuốc
        if ($request->has('nhom_thuoc') && $request->nhom_thuoc != '') {
            $query->where('ma_nhom', $request->nhom_thuoc);
        }

        // Giới hạn 50 kết quả để giao diện không bị đơ nếu database quá lớn
        $thuocs = $query->limit(50)->get();

        return response()->json($thuocs);
    }
    /**
     * Xử lý lưu Phiếu nhập (Quản lý lập phiếu)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_ncc' => 'required|exists:nha_cung_cap,ma_ncc',
            'ngay_nhap' => 'required|date',
            'chi_tiet' => 'required|array',
            'chi_tiet.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'chi_tiet.*.ngay_san_xuat' => 'required|date|before_or_equal:today',
            'chi_tiet.*.so_dang_ky' => 'nullable|string',
            'chi_tiet.*.so_luong_nhap' => 'required|integer|min:1',
            'chi_tiet.*.don_gia_nhap' => 'required|numeric|min:0',
            'chi_tiet.*.han_su_dung' => 'required|date|after:today',
        ], [
            'chi_tiet.*.han_su_dung.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại.',
            'chi_tiet.*.ngay_san_xuat.before_or_equal' => 'Ngày sản xuất không được lớn hơn ngày hiện tại.',
        ]);

        // Tự động sinh mã phiếu nhập PN_YYYYMMDD_0001
        $prefix = 'PN_' . date('Ymd') . '_';
        $latest = PhieuNhap::where('ma_phieu_nhap', 'LIKE', $prefix . '%')
            ->orderBy('ma_phieu_nhap', 'desc')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->ma_phieu_nhap, -4);
            $newNum = $num + 1;
        } else {
            $newNum = 1;
        }

        $maPN = $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            // 1. Tạo phiếu nhập
            $tongTien = 0;
            foreach ($request->chi_tiet as $item) {
                $tongTien += $item['so_luong_nhap'] * $item['don_gia_nhap'];
            }

            $phieuNhap = PhieuNhap::create([
                'ma_phieu_nhap' => $maPN,
                'ma_ncc' => $request->ma_ncc,
                'nguoi_nhap' => auth()->id(), 
                'ngay_nhap' => $request->ngay_nhap,
                'tong_tien' => $tongTien,
                'trang_thai_tt' => 'chua_tt',
                'trang_thai_phieu_nhap' => 'doi_hang_ve', 
                'image1' => '',
                'giay_to_lien_quan' => '',
                'tieu_lieu_lien_quan' => '',
            ]);

            // Chuẩn bị sinh số lô tự động
            $currentDate = now()->format('Ymd');
            $slPrefix = 'SL_' . $currentDate . '_';
            $loIndex = 1;
            
            $maxSL = ChiTietPhieuNhap::where('so_lo', 'LIKE', $slPrefix . '%')
                                     ->orderBy('so_lo', 'desc')
                                     ->first();
            if ($maxSL) {
                $num = (int) substr($maxSL->so_lo, -4);
                $loIndex = $num + 1;
            }

            // 2. Tạo chi tiết & Tồn kho
            foreach ($request->chi_tiet as $item) {
                $so_lo = $slPrefix . str_pad($loIndex, 4, '0', STR_PAD_LEFT);
                $so_lo_sx = 'LSX_' . Carbon::parse($item['ngay_san_xuat'])->format('Ymd');
                $loIndex++;

                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_lo' => $so_lo,
                    'so_lo_sx' => $so_lo_sx, 
                    'ngay_san_xuat' => $item['ngay_san_xuat'],
                    'so_dang_ky' => $item['so_dang_ky'] ?? null,
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_nhap' => $item['so_luong_nhap'],
                    'so_luong_thuc_te' => 0, // Mặc định 0 khi mới lập
                    'don_gia_nhap' => $item['don_gia_nhap'],
                    'thanh_tien' => $item['so_luong_nhap'] * $item['don_gia_nhap'],
                ]);

                // Tạo dòng tồn kho ở trạng thái chờ duyệt (đang nhập về)
                TonKho::create([
                    'ma_thuoc' => $item['ma_thuoc'],
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'so_lo' => $so_lo,
                    'ngay_san_xuat' => $item['ngay_san_xuat'],
                    'ngay_nhap_lo' => $request->ngay_nhap, // Ngày nhập trên phiếu
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_ton' => 0, // Hiện tại chưa có hàng vật lý
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet', 
                    'image1' => '',
                ]);
            }

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Lập phiếu nhập thành công. Chờ hàng về kho để kiểm tra.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi lập phiếu: ' . $e->getMessage()]);
        }
    }

    /**
     * Xác nhận hàng đã về kho (chuyển trạng thái từ doi_hang_ve sang cho_nhap_kho)
     */
    public function markArrived($id)
    {
        $phieuNhap = PhieuNhap::findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Chỉ có thể xác nhận hàng đã về kho đối với phiếu đang đợi hàng về.']);
        }

        $phieuNhap->trang_thai_phieu_nhap = 'cho_nhap_kho';
        $phieuNhap->save();

        return redirect()->route('imports.index')->with('success', 'Đã xác nhận hàng về kho! Vui lòng thực hiện kiểm hàng.');
    }

    /**
     * Mở form Sửa phiếu nhập (Chỉ khi trạng thái là doi_hang_ve)
     */
    public function edit($id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return redirect()->route('imports.index')->withErrors(['error' => 'Không thể sửa phiếu nhập đã hàng về hoặc đã hoàn tất.']);
        }

        if (str_starts_with($phieuNhap->ma_phieu_nhap, 'PN_TRA_')) {
            return redirect()->route('imports.index')->withErrors(['error' => 'Phiếu nhập trả hàng không được phép sửa đổi thủ công để đảm bảo tính toàn vẹn dữ liệu.']);
        }

        if ($phieuNhap->chiTiet->sum('so_luong_thuc_te') > 0) {
            return redirect()->route('imports.index')->withErrors(['error' => 'Không thể sửa do đã có một phần hàng hóa được nhập về.']);
        }

        $nhaCungCaps = NhaCungCap::all();
        $thuocs = Thuoc::all();
        $nhom_thuocs = NhomThuoc::all();//để tìm kiếm
        return view('admin.inventory.imports.edit', compact('phieuNhap', 'nhaCungCaps', 'thuocs','nhom_thuocs'));
    }

    /**
     * Xử lý cập nhật phiếu nhập
     */
    public function update(Request $request, $id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);
        
        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Không thể sửa phiếu này.']);
        }

        if (str_starts_with($phieuNhap->ma_phieu_nhap, 'PN_TRA_')) {
            return back()->withErrors(['error' => 'Phiếu nhập trả hàng không được phép sửa đổi thủ công.']);
        }

        if ($phieuNhap->chiTiet->sum('so_luong_thuc_te') > 0) {
            return back()->withErrors(['error' => 'Không thể sửa do đã có một phần hàng hóa được nhập về.']);
        }

        $request->validate([
            'ma_ncc' => 'required|exists:nha_cung_cap,ma_ncc',
            'ngay_nhap' => 'required|date',
            'chi_tiet' => 'required|array',
            'chi_tiet.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'chi_tiet.*.ngay_san_xuat' => 'required|date|before_or_equal:today',
            'chi_tiet.*.so_dang_ky' => 'nullable|string',
            'chi_tiet.*.so_luong_nhap' => 'required|integer|min:1',
            'chi_tiet.*.don_gia_nhap' => 'required|numeric|min:0',
            'chi_tiet.*.han_su_dung' => 'required|date|after:today',
        ], [
            'chi_tiet.*.han_su_dung.after' => 'Hạn sử dụng phải lớn hơn ngày hiện tại.',
            'chi_tiet.*.ngay_san_xuat.before_or_equal' => 'Ngày sản xuất không được lớn hơn ngày hiện tại.',
        ]);

        DB::beginTransaction();
        try {
            // Tính lại tổng tiền
            $tongTien = 0;
            foreach ($request->chi_tiet as $item) {
                $tongTien += $item['so_luong_nhap'] * $item['don_gia_nhap'];
            }

            // Cập nhật thông tin phiếu
            $phieuNhap->update([
                'ma_ncc' => $request->ma_ncc,
                'ngay_nhap' => $request->ngay_nhap,
                'tong_tien' => $tongTien,
            ]);

            // Xóa các chi tiết cũ và tồn kho cũ
            TonKhoKhuVuc::where('ma_phieu_nhap', $id)->delete();
            ChiTietPhieuNhap::where('ma_phieu_nhap', $id)->delete();
            TonKho::where('ma_phieu_nhap', $id)->delete();

            // Chuẩn bị sinh lại số lô tự động nếu có
            $currentDate = now()->format('Ymd');
            $slPrefix = 'SL_' . $currentDate . '_';
            $loIndex = 1;
            
            $maxSL = ChiTietPhieuNhap::where('so_lo', 'LIKE', $slPrefix . '%')
                                     ->orderBy('so_lo', 'desc')
                                     ->first();
            if ($maxSL) {
                $num = (int) substr($maxSL->so_lo, -4);
                $loIndex = $num + 1;
            }

            // Tạo lại chi tiết & Tồn kho nháp
            foreach ($request->chi_tiet as $item) {
                $so_lo = $slPrefix . str_pad($loIndex, 4, '0', STR_PAD_LEFT);
                $so_lo_sx = 'LSX_' . Carbon::parse($item['ngay_san_xuat'])->format('Ymd');
                $loIndex++;

                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'ma_thuoc' => $item['ma_thuoc'],
                    'so_lo' => $so_lo,
                    'so_lo_sx' => $so_lo_sx,
                    'ngay_san_xuat' => $item['ngay_san_xuat'],
                    'so_dang_ky' => $item['so_dang_ky'] ?? null,
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_nhap' => $item['so_luong_nhap'],
                    'so_luong_thuc_te' => 0,
                    'don_gia_nhap' => $item['don_gia_nhap'],
                    'thanh_tien' => $item['so_luong_nhap'] * $item['don_gia_nhap'],
                ]);

                TonKho::create([
                    'ma_thuoc' => $item['ma_thuoc'],
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'so_lo' => $so_lo,
                    'ngay_san_xuat' => $item['ngay_san_xuat'],
                    'ngay_nhap_lo' => $request->ngay_nhap,
                    'han_su_dung' => $item['han_su_dung'],
                    'so_luong_ton' => 0,
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet', 
                    'image1' => '',
                ]);
            }

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Cập nhật phiếu nhập thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi cập nhật phiếu: ' . $e->getMessage()]);
        }
    }

    /**
     * Mở form Kiểm hàng (Show details)
     */
    public function show($id)
    {
        $phieuNhap = PhieuNhap::with(['nhaCungCap', 'chiTiet.thuoc', 'nguoiLap'])->findOrFail($id);
        
        // Lấy danh sách tồn kho nháp liên quan để show ảnh (nếu có)
        $tonKhos = TonKho::where('ma_phieu_nhap', $id)->get()->keyBy(function($item) {
            return $item->ma_thuoc . '_' . $item->so_lo;
        });

        return view('admin.inventory.imports.inspect', compact('phieuNhap', 'tonKhos'));
    }

    /**
     * Lưu tạm công tác kiểm hàng (Nhân viên kho nhập số thực tế và tải ảnh)
     */
    /**
     * Xác nhận hoàn tất nhập kho
     */
    public function confirm(Request $request, $id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                // Validate if quantity is larger than expected
                $expectedQuantity = ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                          ->where('ma_thuoc', $item['ma_thuoc'])
                                          ->where('so_lo', $item['original_so_lo'])
                                          ->value('so_luong_nhap');
                                          
                if ($item['so_luong_thuc_te'] > $expectedQuantity) {
                    throw new \Exception("Số lượng nhập thực tế của lô {$item['original_so_lo']} ({$item['so_luong_thuc_te']}) không được vượt quá số lượng chứng từ ({$expectedQuantity}).");
                }

                // Truy vấn bản ghi cũ
                $chiTiet = ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                           ->where('ma_thuoc', $item['ma_thuoc'])
                                           ->where('so_lo', $item['original_so_lo'])
                                           ->first();
                $tonKho = TonKho::where('ma_phieu_nhap', $id)
                                ->where('ma_thuoc', $item['ma_thuoc'])
                                ->where('so_lo', $item['original_so_lo'])
                                ->first();

                if (!$tonKho && $chiTiet) {
                    $tonKho = TonKho::create([
                        'ma_thuoc' => $chiTiet->ma_thuoc,
                        'ma_phieu_nhap' => $chiTiet->ma_phieu_nhap,
                        'so_lo' => $chiTiet->so_lo,
                        'ngay_san_xuat' => $chiTiet->ngay_san_xuat,
                        'ngay_nhap_lo' => Carbon::now(),
                        'han_su_dung' => $chiTiet->han_su_dung,
                        'so_luong_ton' => 0,
                        'so_luong_da_xuat' => 0,
                        'trang_thai_lo' => 'cho_duyet',
                        'image1' => '',
                    ]);
                }

                if ($chiTiet && $tonKho) {
                    $newSoLo = $item['so_lo'];
                    $oldSoLo = $item['original_so_lo'];
                    
                    // Nếu đổi số lô, phải dọn sạch dữ liệu cũ của lô cũ
                    if ($newSoLo != $oldSoLo) {
                        $chiTietData = $chiTiet->toArray();
                        $tonKhoData = $tonKho->toArray();
                        
                        TonKhoKhuVuc::where('ma_phieu_nhap', $id)
                                    ->where('ma_thuoc', $item['ma_thuoc'])
                                    ->where('so_lo', $oldSoLo)
                                    ->delete();
                        $chiTiet->delete();
                        $tonKho->delete();

                        $chiTietData['so_lo'] = $newSoLo;
                        $chiTietData['so_luong_thuc_te'] = $item['so_luong_thuc_te'];
                        $chiTietData['han_su_dung'] = $item['han_su_dung'];
                        $chiTiet = ChiTietPhieuNhap::create($chiTietData);

                        $tonKhoData['so_lo'] = $newSoLo;
                        $tonKhoData['han_su_dung'] = $item['han_su_dung'];
                        // Khi tạo mới do đổi lô, reset số lượng thực nhập về 0 để tính toán delta bên dưới
                        $tonKhoData['so_luong_ton'] = 0;
                        $tonKhoData['so_luong_da_xuat'] = 0;
                        $tonKho = TonKho::create($tonKhoData);
                    } else {
                        // Cập nhật HSD
                        $chiTiet->update(['han_su_dung' => $item['han_su_dung']]);
                        $tonKho->update(['han_su_dung' => $item['han_su_dung']]);
                    }

                    // --- LOGIC TÍNH TOÁN NHẬP KHO (Không dồn số lượng) ---
                    // tongDaVaoKho: Số lượng thực tế đã ghi nhận vào TonKho trước đó
                    $tongDaVaoKho = $tonKho->so_luong_ton + $tonKho->so_luong_da_xuat;
                    $soLuongMoiKhaiBao = (int)$item['so_luong_thuc_te'];
                    
                    // hangMoiVe: Phần chênh lệch tăng thêm so với lần xác nhận trước
                    $hangMoiVe = $soLuongMoiKhaiBao - $tongDaVaoKho;

                    if ($hangMoiVe > 0) {
                        $tonTruoc = $tonKho->so_luong_ton;
                        $tonKho->so_luong_ton += $hangMoiVe;
                        $tonKho->trang_thai_lo = 'cho_duyet';
                        $tonKho->ngay_nhap_lo = Carbon::now();
                        
                        // Upload ảnh chi tiết lô hàng
                        $lotImageKey = 'image_lot_' . $item['ma_thuoc'] . '_' . $oldSoLo;
                        if ($request->hasFile($lotImageKey)) {
                            $lotImage = $request->file($lotImageKey);
                            $lotImageName = time() . '_lot_' . $item['ma_thuoc'] . '_' . $newSoLo . '.' . $lotImage->extension();
                            $lotImage->move(public_path('uploads/batches'), $lotImageName);
                            
                            $imagePath = 'uploads/batches/' . $lotImageName;
                            $tonKho->image1 = $imagePath;
                            $chiTiet->image = $imagePath;
                        }
                        $tonKho->save();

                        // Cập nhật số lượng thực tế
                        $chiTiet->so_luong_thuc_te = $soLuongMoiKhaiBao;
                        $chiTiet->save();

                        // Cập nhật TonKhoKhuVuc
                        $targetArea = str_starts_with($id, 'PN_TRA_') ? 'KV04_CHO_XU_LY' : 'KV01_TIEP_NHAN';
                        $khuVuc = TonKhoKhuVuc::firstOrNew([
                            'ma_thuoc' => $item['ma_thuoc'],
                            'ma_phieu_nhap' => $id,
                            'so_lo' => $newSoLo,
                            'ma_khu_vuc' => $targetArea
                        ]);
                        $khuVuc->so_luong = ($khuVuc->so_luong ?? 0) + $hangMoiVe;
                        $khuVuc->save();

                        // Ghi log dịch chuyển
                        LichSuDichChuyenKho::create([
                            'ma_phieu_chuyen' => 'CHUP-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                            'ma_thuoc' => $item['ma_thuoc'],
                            'ma_phieu_nhap' => $id,
                            'so_lo' => $newSoLo,
                            'tu_khu_vuc' => null,
                            'den_khu_vuc' => $targetArea,
                            'so_luong_chuyen' => $hangMoiVe,
                            'nguoi_thuc_hien' => auth()->id() ?? 'USR001',
                            'ngay_chuyen' => Carbon::now(),
                            'ly_do_chuyen' => "Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: {$hangMoiVe})",
                        ]);

                        InventoryLogService::logMovement(
                            $item['ma_thuoc'], $newSoLo, auth()->id() ?? 'USR001', $id,
                            'nhap', 'phieu_nhap', $hangMoiVe, $tonTruoc, $tonKho->so_luong_ton,
                            $chiTiet->don_gia_nhap, 'Xác nhận hàng về kho (tổng khai báo: ' . $soLuongMoiKhaiBao . ')'
                        );
                    }
                }
            }

            // Xử lý upload ảnh tổng lô hàng cho Phiếu nhập
            if ($request->hasFile('phieu_nhap_image')) {
                $pimage = $request->file('phieu_nhap_image');
                $pimageName = time() . '_phieunhap.' . $pimage->extension();
                $pimage->move(public_path('uploads/batches'), $pimageName);
                $phieuNhap->image1 = 'uploads/batches/' . $pimageName;
                $phieuNhap->save();
            }

            // Xử lý upload tài liệu phiếu nhập (giữ nguyên logic gốc)
            if ($request->hasFile('giay_to_lien_quan')) {
                $file1 = $request->file('giay_to_lien_quan');
                $name1 = time() . '_giayto.' . $file1->extension();
                $file1->move(public_path('uploads/batches'), $name1);
                $phieuNhap->giay_to_lien_quan = 'uploads/batches/' . $name1;
            }
            if ($request->hasFile('tieu_lieu_lien_quan')) {
                $file2 = $request->file('tieu_lieu_lien_quan');
                $name2 = time() . '_tieulieu.' . $file2->extension();
                $file2->move(public_path('uploads/batches'), $name2);
                $phieuNhap->tieu_lieu_lien_quan = 'uploads/batches/' . $name2;
            }

            // Kiểm tra trạng thái cuối cùng của phiếu
            $isMissing = ChiTietPhieuNhap::where('ma_phieu_nhap', $id)
                                         ->whereRaw('so_luong_thuc_te < so_luong_nhap')
                                         ->exists();

            if ($isMissing) {
                $phieuNhap->trang_thai_phieu_nhap = 'doi_hang_ve';
                $msg = 'Đã xác nhận lô hàng. Số lượng thực tế chưa đủ, phiếu được chuyển về "Đợi hàng về".';
            } else {
                $phieuNhap->trang_thai_phieu_nhap = 'da_nhap_kho';
                $msg = 'Đã hoàn tất quy trình nhập kho toàn bộ chứng từ!';
            }
            $phieuNhap->save();

            DB::commit();
            return redirect()->route('imports.index')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Lỗi khi nhập kho: ' . $e->getMessage()]);
        }
    }

    /**
     * Xoá phiếu nhập (Chỉ khi đang ở trạng thái 'doi_hang_ve')
     */
    public function destroy($id)
    {
        $phieuNhap = PhieuNhap::with('chiTiet')->findOrFail($id);

        if ($phieuNhap->trang_thai_phieu_nhap != 'doi_hang_ve') {
            return back()->withErrors(['error' => 'Chỉ có thể xoá phiếu nhập ở trạng thái đợi hàng về.']);
        }

        if ($phieuNhap->chiTiet->sum('so_luong_thuc_te') > 0) {
            return back()->withErrors(['error' => 'Không thể xoá do đã có một phần hàng hóa được nhập về.']);
        }

        DB::beginTransaction();
        try {
            // Xoá tồn kho khu vực nháp (do db ko cascade)
            TonKhoKhuVuc::where('ma_phieu_nhap', $id)->delete();
            // Xoá tồn kho nháp
            TonKho::where('ma_phieu_nhap', $id)->delete();
            // Xoá chi tiết
            ChiTietPhieuNhap::where('ma_phieu_nhap', $id)->delete();
            // Xoá phiếu
            $phieuNhap->delete();

            DB::commit();
            return redirect()->route('imports.index')->with('success', 'Đã xoá phiếu nhập ' . $id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi khi xoá phiếu: ' . $e->getMessage()]);
        }
    }
}
