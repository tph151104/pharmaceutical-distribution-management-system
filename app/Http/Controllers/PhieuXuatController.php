<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhieuXuat;
use App\Models\ChiTietPhieuXuat;
use App\Models\TonKho;
use App\Models\DonHang;
use App\Models\ThanhToan;
use Illuminate\Support\Facades\DB;

class PhieuXuatController extends Controller
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

        $phieuXuats = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.inventory.sales.index', compact('phieuXuats'));
    }

    /**
     * Màn hình tạo phiếu xuất (Chọn đơn hàng)
     */
    public function create(Request $request)
    {
        // Lấy danh sách đơn hàng đã duyệt để thủ kho chọn
        $donHangs = DonHang::where('trang_thai_dh', 'da_duyet')->orderBy('created_at', 'desc')->get();
        // Nếu truyền sẵn order_id từ nút "Tạo phiếu xuất"
        $selectedOrderId = $request->get('order_id');
        
        return view('admin.inventory.sales.create', compact('donHangs', 'selectedOrderId'));
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

        DB::beginTransaction();
        try {
            // Sinh mã phiếu xuất tự động: PX_MaDonHang_Time
            $maPX = 'PX_' . $donHang->ma_don_hang . '_' . time();
            
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
     * Chi tiết phiếu xuất (Màn hình FEFO)
     */
    public function show($id)
    {
        $phieuXuat = PhieuXuat::with(['khachHang', 'donHang.chiTiet.thuoc'])->findOrFail($id);

        // Gợi ý FEFO
        $fefoAllocation = [];
        
        if ($phieuXuat->trang_thai_phieu_xuat === 'dang_chuan_bi') {
            foreach ($phieuXuat->donHang->chiTiet as $ctdh) {
                // Tìm các lô thuốc còn tồn, đang bán, còn hạn theo FEFO (ASC date)
                $lots = TonKho::where('ma_thuoc', $ctdh->ma_thuoc)
                    ->where('trang_thai_lo', 'dang_ban')
                    ->where('han_su_dung', '>=', now()->toDateString())
                    ->where('so_luong_ton', '>', 0)
                    ->orderBy('han_su_dung', 'asc')
                    ->get();
                
                $needed = $ctdh->so_luong;
                $allocatedLots = [];

                foreach ($lots as $lot) {
                    if ($needed <= 0) break;

                    $take = min($needed, $lot->so_luong_ton);
                    $allocatedLots[] = [
                        'so_lo' => $lot->so_lo,
                        'han_su_dung' => $lot->han_su_dung,
                        'so_luong_ton' => $lot->so_luong_ton,
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
            $uploads = [];
            foreach (['image1', 'image2', 'giay_to_an_toan', 'tai_lieu_lien_quan'] as $field) {
                if ($request->hasFile($field)) {
                    $uploads[$field] = $request->file($field)->store('exports', 'public');
                    $phieuXuat->$field = $uploads[$field];
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
                        ->first();

                    if (!$tonKho || $tonKho->so_luong_ton < $soLuongXuat) {
                        throw new \Exception("Lô {$soLo} của thuốc {$maThuoc} không đủ số lượng tồn ({$soLuongXuat}).");
                    }

                    // Trừ tồn
                    $tonKho->so_luong_ton -= $soLuongXuat;
                    $tonKho->so_luong_da_xuat += $soLuongXuat;
                    $tonKho->save();

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
        
        if ($phieuXuat->trang_thai_phieu_xuat !== 'da_van_chuyen') {
            return back()->withErrors(['error' => 'Chỉ phiếu "Đang vận chuyển" mới có thể xác nhận hoàn thành.']);
        }

        DB::beginTransaction();
        try {
            $phieuXuat->trang_thai_phieu_xuat = 'da_hoan_thanh';
            $phieuXuat->save();

            if ($phieuXuat->ma_don_hang) {
                $donHang = DonHang::find($phieuXuat->ma_don_hang);
                if ($donHang) {
                    $donHang->trang_thai_dh = 'da_hoan_thanh';
                    $donHang->save();
                }
            }

            DB::commit();
            return back()->with('success', 'Đã xác nhận Giao hàng hoàn thành.');
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
}
