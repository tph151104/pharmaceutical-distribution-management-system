<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhachTraHang;
use App\Models\ChiTietTraHang;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;
use App\Models\ThanhToan;
use App\Models\PhieuXuat;
use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryLogService;

class KhachTraHangController extends Controller
{
    /**
     * Danh sách Yêu cầu trả hàng
     */
    public function index()
    {
        $traHangs = KhachTraHang::with(['khachHang', 'donHang'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.inventory.returns.index', compact('traHangs'));
    }

    /**
     * Chi tiết Yêu cầu
     */
    public function show($id)
    {
        $traHang = KhachTraHang::with(['khachHang', 'donHang', 'chiTiet.thuoc'])->findOrFail($id);
        return view('admin.inventory.returns.show', compact('traHang'));
    }

    /**
     * Từ chối Yêu cầu
     */
    public function reject(Request $request, $id)
    {
        $traHang = KhachTraHang::findOrFail($id);
        if ($traHang->trang_thai !== 'cho_duyet') return back()->withErrors(['error' => 'Trạng thái không hợp lệ.']);

        $traHang->trang_thai = 'tu_choi';
        $traHang->ghi_chu_admin = $request->ly_do;
        $traHang->nguoi_duyet = auth()->id();
        $traHang->ngay_duyet = now()->toDateString();
        $traHang->save();

        return back()->with('success', 'Đã từ chối yêu cầu trả hàng.');
    }

    /**
     * Phê duyệt & Nhập kho (KV04) & Hoàn tiền
     */
    // public function approve(Request $request, $id)
    // {
    //     $traHang = KhachTraHang::with('chiTiet')->findOrFail($id);
    //     if ($traHang->trang_thai !== 'cho_duyet') return back()->withErrors(['error' => 'Yêu cầu này đã được xử lý.']);

    //     DB::beginTransaction();
    //     try {
    //         // Đảm bảo có một Nhà cung cấp (Nhập trả) để không bị lỗi ma_ncc null
    //         $nccTraHang = \App\Models\NhaCungCap::firstOrCreate(
    //             ['ma_ncc' => 'NCC_TRAHANG'],
    //             [
    //                 'ten_ncc' => 'Khách Hàng Trả Lại',
    //                 'so_dien_thoai' => '0000000000',
    //                 'dia_chi' => 'Hệ thống tự động',
    //                 'trang_thai' => 1
    //             ]
    //         );

    //         // 1. Tạo Phiếu Nhập đặc biệt mang tính chất Trả hàng
    //         $maPhieuNhap = 'PN_TRA_' . time();
    //         $phieuNhap = PhieuNhap::create([
    //             'ma_phieu_nhap' => $maPhieuNhap,
    //             'ma_ncc' => $nccTraHang->ma_ncc,
    //             'nguoi_nhap' => auth()->id() ?? 'NV001',
    //             'ngay_nhap' => now(),
    //             'tong_tien' => $traHang->tong_tien_hoan_tra,
    //             'trang_thai_tt' => 'da_tt', // Hoàn trả vào công nợ/tiền mặt luôn
    //             'trang_thai_phieu_nhap' => 'da_nhap_kho',
    //             'image1' => '', // Thêm các trường require
    //             'image2' => '',
    //             'giay_to_lien_quan' => '',
    //             'tieu_lieu_lien_quan' => 'Khách hàng trả hàng đơn ' . $traHang->ma_don_hang
    //         ]);

    //         // 2. Chuyển hàng vào KV04_CHO_XU_LY
    //         $soLoTraSuffix = date('Ymd_His');
    //         foreach ($traHang->chiTiet as $ct) {
    //             $soLoTra = 'LO_TRA_' . $soLoTraSuffix . '_' . $ct->ma_thuoc;

    //             // Tạo Chi tiết Phiếu nhập mới cho hàng trả lại
    //             ChiTietPhieuNhap::create([
    //                 'ma_phieu_nhap' => $maPhieuNhap,
    //                 'ma_thuoc' => $ct->ma_thuoc,
    //                 'so_lo' => $soLoTra,
    //                 'so_lo_sx' => 'LSX_TRA_' . $ct->ma_thuoc, // Số lô sản xuất (bắt buộc)
    //                 'ngay_san_xuat' => now()->toDateString(),
    //                 'so_dang_ky' => 'DK_TRA',
    //                 'han_su_dung' => now()->addYear()->toDateString(),
    //                 'so_luong_nhap' => $ct->so_luong_tra,
    //                 'so_luong_thuc_te' => $ct->so_luong_tra,
    //                 'don_gia_nhap' => $ct->don_gia_tra,
    //                 'thanh_tien' => $ct->thanh_tien,
    //             ]);

    //             // Tạo Tồn Kho (Lô hàng trả)
    //             TonKho::create([
    //                 'ma_thuoc' => $ct->ma_thuoc,
    //                 'so_lo' => $soLoTra,
    //                 'ma_phieu_nhap' => $maPhieuNhap,
    //                 'ngay_san_xuat' => now()->toDateString(),
    //                 'ngay_nhap_lo' => now()->toDateString(),
    //                 'han_su_dung' => now()->addYear()->toDateString(),
    //                 'so_luong_ton' => $ct->so_luong_tra,
    //                 'so_luong_da_xuat' => 0,
    //                 'trang_thai_lo' => 'cho_duyet',
    //                 'image1' => '',
    //                 'image2' => '',
    //                 'image3' => '',
    //             ]);

    //             // Nhập 100% thẳng vào KV04_CHO_XU_LY 
    //             TonKhoKhuVuc::create([
    //                 'ma_thuoc' => $ct->ma_thuoc,
    //                 'so_lo' => $soLoTra,
    //                 'ma_phieu_nhap' => $maPhieuNhap,
    //                 'ma_khu_vuc' => 'KV04_CHO_XU_LY',
    //                 'so_luong' => $ct->so_luong_tra
    //             ]);

    //             InventoryLogService::logMovement(
    //                 $ct->ma_thuoc,
    //                 $soLoTra,
    //                 auth()->id() ?? 'NV001',
    //                 $maPhieuNhap,
    //                 'nhap',
    //                 'phieu_nhap',
    //                 $ct->so_luong_tra,
    //                 0,
    //                 $ct->so_luong_tra,
    //                 0,
    //                 '[HÀNG TRẢ VỀ] Đã tự động cách ly vào KV04_CHO_XU_LY để chờ đánh giá lại.'
    //             );
    //         }

    //         // 3. Hoàn tiền cho Khách: Ghi nhận 1 bản Thanh Toán có tiền Âm (Hoàn lại)
    //         $phieuXuatCu = PhieuXuat::where('ma_don_hang', $traHang->ma_don_hang)->first();
    //         if ($phieuXuatCu) {
    //             // Tạo thanh toán hoàn tiền ghi chú chuyển khoản hoàn tiền đơn hàng
    //             ThanhToan::create([
    //                 'ma_phieu_xuat' => $phieuXuatCu->ma_phieu_xuat,
    //                 'loai_thanh_toan' => 'xuat',
    //                 'so_tien_tt' => -abs($traHang->tong_tien_hoan_tra), // Trừ lượng đã thanh toán đi
    //                 'phuong_thuc' => 'chuyen_khoan',
    //                 'ngay_thanh_toan' => now(),
    //                 'hinh_anh' => null,
    //                 'ghi_chu' => 'Chuyển khoản hoàn tiền trả hàng cho đơn: ' . $traHang->ma_don_hang . '. ' . ($request->ghi_chu ?? '')
    //             ]);
    //         }

    //         // 4. Đổi trạng thái Yêu cầu Trả hàng
    //         $traHang->trang_thai = 'da_duyet_nhap_kho';
    //         $traHang->ghi_chu_admin = $request->ghi_chu;
    //         $traHang->nguoi_duyet = auth()->id();
    //         $traHang->ngay_duyet = now()->toDateString();
    //         $traHang->save();

    //         DB::commit();
    //         return back()->with('success', 'Đã duyệt yêu cầu, hàng được cách ly vào KV04 chờ xử lý, và tiền đã được hoàn.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => 'Lỗi xử lý: ' . $e->getMessage()]);
    //     }
    // }
        /**
     * Phê duyệt & Nhập kho (KV04) & Hoàn tiền
     */
    public function approve(Request $request, $id)
    {
        $traHang = KhachTraHang::with('chiTiet')->findOrFail($id);
        if ($traHang->trang_thai !== 'cho_duyet') return back()->withErrors(['error' => 'Yêu cầu này đã được xử lý.']);

        DB::beginTransaction();
        try {
            // Đảm bảo có một Nhà cung cấp (Nhập trả) để không bị lỗi ma_ncc null
            $nccTraHang = \App\Models\NhaCungCap::firstOrCreate(
                ['ma_ncc' => 'NCC_TRAHANG'],
                [
                    'ten_ncc' => 'Khách Hàng Trả Lại',
                    'so_dien_thoai' => '0000000000',
                    'dia_chi' => 'Hệ thống tự động',
                    'trang_thai' => 1
                ]
            );

            // 1. Tạo Phiếu Nhập đặc biệt mang tính chất Trả hàng
            $maPhieuNhap = 'PN_TRA_' . time();
            $phieuNhap = PhieuNhap::create([
                'ma_phieu_nhap' => $maPhieuNhap,
                'ma_ncc' => $nccTraHang->ma_ncc,
                'nguoi_nhap' => auth()->id() ?? 'NV001',
                'ngay_nhap' => now(),
                'tong_tien' => $traHang->tong_tien_hoan_tra,
                'trang_thai_tt' => 'da_tt', 
                'trang_thai_phieu_nhap' => 'da_nhap_kho',
                'image1' => '', 
                'image2' => '',
                'giay_to_lien_quan' => '',
                'tieu_lieu_lien_quan' => 'Khách hàng trả hàng đơn ' . $traHang->ma_don_hang
            ]);

            // 2. Chuyển hàng vào KV04_CHO_XU_LY
            $soLoTraSuffix = date('Ymd_His');
            foreach ($traHang->chiTiet as $ct) {
                // Dùng chung 1 biến số lô để tránh lệch dữ liệu
                $soLoTra = 'LO_TRA_' . $soLoTraSuffix . '_' . $ct->ma_thuoc;

                ChiTietPhieuNhap::create([
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'so_lo_sx' => 'LSX_TRA_' . $ct->ma_thuoc,
                    'ngay_san_xuat' => now()->toDateString(),
                    'so_dang_ky' => 'DK_TRA',
                    'han_su_dung' => now()->addYear()->toDateString(),
                    'so_luong_nhap' => $ct->so_luong_tra,
                    'so_luong_thuc_te' => $ct->so_luong_tra,
                    'don_gia_nhap' => $ct->don_gia_tra,
                    'thanh_tien' => $ct->thanh_tien,
                ]);

                TonKho::create([
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ngay_san_xuat' => now()->toDateString(),
                    'ngay_nhap_lo' => now()->toDateString(),
                    'han_su_dung' => now()->addYear()->toDateString(),
                    'so_luong_ton' => $ct->so_luong_tra,
                    'so_luong_da_xuat' => 0,
                    'trang_thai_lo' => 'cho_duyet',
                    'image1' => '',
                    'image2' => '',
                    'image3' => '',
                ]);

                TonKhoKhuVuc::create([
                    'ma_thuoc' => $ct->ma_thuoc,
                    'so_lo' => $soLoTra,
                    'ma_phieu_nhap' => $maPhieuNhap,
                    'ma_khu_vuc' => 'KV04_CHO_XU_LY',
                    'so_luong' => $ct->so_luong_tra
                ]);

                InventoryLogService::logMovement(
                    $ct->ma_thuoc,
                    $soLoTra,
                    auth()->id() ?? 'NV001',
                    $maPhieuNhap,
                    'nhap', // Sử dụng giá trị enum hợp lệ
                    'phieu_nhap', // Sử dụng giá trị enum hợp lệ
                    $ct->so_luong_tra,
                    0,
                    $ct->so_luong_tra,
                    0,
                    '[HÀNG TRẢ VỀ] Đã tự động cách ly vào KV04_CHO_XU_LY để chờ đánh giá lại.'
                );
            }

            // 3. Hoàn tiền cho Khách: Tạo bản ghi Thanh toán cho Phiếu Nhập trả này
            ThanhToan::create([
                'ma_thanh_toan' => 'TT_TRA_' . time() . '_' . rand(100, 999),
                'ma_phieu_nhap' => $maPhieuNhap,
                'loai_thanh_toan' => 'nhap',
                'so_tien_tt' => abs($traHang->tong_tien_hoan_tra), // Số dương để thỏa mãn SQL CHECK constraint
                'phuong_thuc_tt' => 'chuyen_khoan',
                'ngay_thanh_toan' => now(),
                'ghi_chu' => 'Hoàn tiền trả hàng cho đơn: ' . $traHang->ma_don_hang . '. ' . ($request->ghi_chu ?? '')
            ]);

            // 4. Đổi trạng thái Yêu cầu Trả hàng
            $traHang->trang_thai = 'da_duyet_nhap_kho';
            $traHang->ghi_chu_admin = $request->ghi_chu;
            $traHang->nguoi_duyet = auth()->id();
            $traHang->ngay_duyet = now()->toDateString();
            $traHang->save();

            DB::commit();
            return back()->with('success', 'Đã duyệt yêu cầu thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi xử lý: ' . $e->getMessage()]);
        }
    }
}
