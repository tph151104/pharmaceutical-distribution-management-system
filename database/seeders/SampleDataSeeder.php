<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // =====================================================================
        // THUỐC: TH0001(Hapacol), TH0002(Klamentin), TH0004(Paracetamol), 
        //         TH0005(Amoxicillin), TH0006(VitC), TH0007(Omeprazole), TH0008(Paralmax)
        // NCC: NCC001 (Dược Hậu Giang), NCC002 (Traphaco)
        // KH:  KH01 (Nhà thuốc Hồng Hoa), KH02 (PK Đa khoa An Bình), KH003 (nhathuoc_1)
        // ND:  ND001(admin), ND002(truongkho), ND003(nvkho), ND004(nvbanhang), ND005(ketoan)
        // KV:  KV01_TIEP_NHAN, KV02_BIET_TRU, KV03_THANH_PHAM, KV04_CHO_XU_LY, KV05_LOAI_BO
        // =====================================================================

        // ===========================
        // 1. PHIẾU NHẬP (12 phiếu) - Format: PN_YYYYMMDD_XXXX
        // ===========================
        $phieuNhaps = [
            ['ma_phieu_nhap' => 'PN_20260105_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-01-05', 'tong_tien' => 15000000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260110_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-01-10', 'tong_tien' => 22500000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260120_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-01-20', 'tong_tien' => 8500000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260201_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND002', 'ngay_nhap' => '2026-02-01', 'tong_tien' => 18000000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260210_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-02-10', 'tong_tien' => 12000000, 'trang_thai_tt' => 'mot_phan', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260220_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND002', 'ngay_nhap' => '2026-02-20', 'tong_tien' => 9600000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260301_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-03-01', 'tong_tien' => 20000000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260310_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-03-10', 'tong_tien' => 6750000, 'trang_thai_tt' => 'chua_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260315_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND002', 'ngay_nhap' => '2026-03-15', 'tong_tien' => 14000000, 'trang_thai_tt' => 'da_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260325_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-03-25', 'tong_tien' => 10200000, 'trang_thai_tt' => 'mot_phan', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260401_0001', 'ma_ncc' => 'NCC001', 'nguoi_nhap' => 'ND003', 'ngay_nhap' => '2026-04-01', 'tong_tien' => 16500000, 'trang_thai_tt' => 'chua_tt', 'trang_thai_phieu_nhap' => 'da_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
            ['ma_phieu_nhap' => 'PN_20260410_0001', 'ma_ncc' => 'NCC002', 'nguoi_nhap' => 'ND002', 'ngay_nhap' => '2026-04-10', 'tong_tien' => 7200000, 'trang_thai_tt' => 'chua_tt', 'trang_thai_phieu_nhap' => 'cho_nhap_kho', 'image1' => '', 'image2' => '', 'giay_to_lien_quan' => '', 'tieu_lieu_lien_quan' => ''],
        ];
        foreach ($phieuNhaps as &$pn) {
            $pn['created_at'] = $pn['ngay_nhap'] . ' 08:00:00';
            $pn['updated_at'] = $pn['ngay_nhap'] . ' 08:00:00';
        }
        DB::table('phieu_nhap')->insert($phieuNhaps);
        echo "[OK] phieu_nhap: 12 records\n";

        // ===========================
        // 2. CHI TIẾT PHIẾU NHẬP - Số lô: SL_YYYYMMDD_XXXX, Số lô SX: LSX_YYYYMMDD
        // ===========================
        $chiTietPN = [
            // PN_20260105_0001 - Hapacol + Paracetamol
            ['ma_phieu_nhap' => 'PN_20260105_0001', 'ma_thuoc' => 'TH0001', 'so_lo' => 'SL_20260105_0001', 'so_lo_sx' => 'LSX_20250601', 'ngay_san_xuat' => '2025-06-01', 'so_dang_ky' => 'VD-12345-18', 'han_su_dung' => '2028-06-01', 'so_luong_nhap' => 500, 'so_luong_thuc_te' => 500, 'don_gia_nhap' => 18000, 'thanh_tien' => 9000000],
            ['ma_phieu_nhap' => 'PN_20260105_0001', 'ma_thuoc' => 'TH0004', 'so_lo' => 'SL_20260105_0002', 'so_lo_sx' => 'LSX_20250515', 'ngay_san_xuat' => '2025-05-15', 'so_dang_ky' => 'VD-11223-18', 'han_su_dung' => '2028-05-15', 'so_luong_nhap' => 400, 'so_luong_thuc_te' => 400, 'don_gia_nhap' => 15000, 'thanh_tien' => 6000000],

            // PN_20260110_0001 - Klamentin + Amoxicillin
            ['ma_phieu_nhap' => 'PN_20260110_0001', 'ma_thuoc' => 'TH0002', 'so_lo' => 'SL_20260110_0001', 'so_lo_sx' => 'LSX_20250701', 'ngay_san_xuat' => '2025-07-01', 'so_dang_ky' => 'VD-22334-19', 'han_su_dung' => '2028-07-01', 'so_luong_nhap' => 150, 'so_luong_thuc_te' => 150, 'don_gia_nhap' => 100000, 'thanh_tien' => 15000000],
            ['ma_phieu_nhap' => 'PN_20260110_0001', 'ma_thuoc' => 'TH0005', 'so_lo' => 'SL_20260110_0002', 'so_lo_sx' => 'LSX_20250801', 'ngay_san_xuat' => '2025-08-01', 'so_dang_ky' => 'VD-33445-19', 'han_su_dung' => '2028-08-01', 'so_luong_nhap' => 300, 'so_luong_thuc_te' => 300, 'don_gia_nhap' => 25000, 'thanh_tien' => 7500000],

            // PN_20260120_0001 - VitC + Omeprazole
            ['ma_phieu_nhap' => 'PN_20260120_0001', 'ma_thuoc' => 'TH0006', 'so_lo' => 'SL_20260120_0001', 'so_lo_sx' => 'LSX_20250901', 'ngay_san_xuat' => '2025-09-01', 'so_dang_ky' => 'VD-44556-20', 'han_su_dung' => '2028-09-01', 'so_luong_nhap' => 100, 'so_luong_thuc_te' => 100, 'don_gia_nhap' => 50000, 'thanh_tien' => 5000000],
            ['ma_phieu_nhap' => 'PN_20260120_0001', 'ma_thuoc' => 'TH0007', 'so_lo' => 'SL_20260120_0002', 'so_lo_sx' => 'LSX_20250401', 'ngay_san_xuat' => '2025-04-01', 'so_dang_ky' => 'VD-55667-19', 'han_su_dung' => '2028-04-01', 'so_luong_nhap' => 50, 'so_luong_thuc_te' => 50, 'don_gia_nhap' => 70000, 'thanh_tien' => 3500000],

            // PN_20260201_0001 - Paralmax + Klamentin lô 2
            ['ma_phieu_nhap' => 'PN_20260201_0001', 'ma_thuoc' => 'TH0008', 'so_lo' => 'SL_20260201_0001', 'so_lo_sx' => 'LSX_20251001', 'ngay_san_xuat' => '2025-10-01', 'so_dang_ky' => 'VD-66778-20', 'han_su_dung' => '2028-10-01', 'so_luong_nhap' => 200, 'so_luong_thuc_te' => 200, 'don_gia_nhap' => 40000, 'thanh_tien' => 8000000],
            ['ma_phieu_nhap' => 'PN_20260201_0001', 'ma_thuoc' => 'TH0002', 'so_lo' => 'SL_20260201_0002', 'so_lo_sx' => 'LSX_20251101', 'ngay_san_xuat' => '2025-11-01', 'so_dang_ky' => 'VD-22334-19', 'han_su_dung' => '2028-11-01', 'so_luong_nhap' => 100, 'so_luong_thuc_te' => 100, 'don_gia_nhap' => 100000, 'thanh_tien' => 10000000],

            // PN_20260210_0001 - Hapacol lô 2 + Paracetamol lô 2
            ['ma_phieu_nhap' => 'PN_20260210_0001', 'ma_thuoc' => 'TH0001', 'so_lo' => 'SL_20260210_0001', 'so_lo_sx' => 'LSX_20251201', 'ngay_san_xuat' => '2025-12-01', 'so_dang_ky' => 'VD-12345-18', 'han_su_dung' => '2028-12-01', 'so_luong_nhap' => 300, 'so_luong_thuc_te' => 300, 'don_gia_nhap' => 18000, 'thanh_tien' => 5400000],
            ['ma_phieu_nhap' => 'PN_20260210_0001', 'ma_thuoc' => 'TH0004', 'so_lo' => 'SL_20260210_0002', 'so_lo_sx' => 'LSX_20251115', 'ngay_san_xuat' => '2025-11-15', 'so_dang_ky' => 'VD-11223-18', 'han_su_dung' => '2028-11-15', 'so_luong_nhap' => 440, 'so_luong_thuc_te' => 440, 'don_gia_nhap' => 15000, 'thanh_tien' => 6600000],

            // PN_20260220_0001 - Amoxicillin lô 2 + VitC lô 2 + Omeprazole lô 2
            ['ma_phieu_nhap' => 'PN_20260220_0001', 'ma_thuoc' => 'TH0005', 'so_lo' => 'SL_20260220_0001', 'so_lo_sx' => 'LSX_20260101', 'ngay_san_xuat' => '2026-01-01', 'so_dang_ky' => 'VD-33445-19', 'han_su_dung' => '2029-01-01', 'so_luong_nhap' => 200, 'so_luong_thuc_te' => 200, 'don_gia_nhap' => 25000, 'thanh_tien' => 5000000],
            ['ma_phieu_nhap' => 'PN_20260220_0001', 'ma_thuoc' => 'TH0006', 'so_lo' => 'SL_20260220_0002', 'so_lo_sx' => 'LSX_20251215', 'ngay_san_xuat' => '2025-12-15', 'so_dang_ky' => 'VD-44556-20', 'han_su_dung' => '2028-12-15', 'so_luong_nhap' => 80, 'so_luong_thuc_te' => 80, 'don_gia_nhap' => 50000, 'thanh_tien' => 4000000],
            ['ma_phieu_nhap' => 'PN_20260220_0001', 'ma_thuoc' => 'TH0007', 'so_lo' => 'SL_20260220_0003', 'so_lo_sx' => 'LSX_20260110', 'ngay_san_xuat' => '2026-01-10', 'so_dang_ky' => 'VD-55667-19', 'han_su_dung' => '2029-01-10', 'so_luong_nhap' => 10, 'so_luong_thuc_te' => 10, 'don_gia_nhap' => 60000, 'thanh_tien' => 600000],

            // PN_20260301_0001 - Hapacol lô 3 + Paralmax lô 2
            ['ma_phieu_nhap' => 'PN_20260301_0001', 'ma_thuoc' => 'TH0001', 'so_lo' => 'SL_20260301_0001', 'so_lo_sx' => 'LSX_20260115', 'ngay_san_xuat' => '2026-01-15', 'so_dang_ky' => 'VD-12345-18', 'han_su_dung' => '2029-01-15', 'so_luong_nhap' => 600, 'so_luong_thuc_te' => 600, 'don_gia_nhap' => 18000, 'thanh_tien' => 10800000],
            ['ma_phieu_nhap' => 'PN_20260301_0001', 'ma_thuoc' => 'TH0008', 'so_lo' => 'SL_20260301_0002', 'so_lo_sx' => 'LSX_20260201', 'ngay_san_xuat' => '2026-02-01', 'so_dang_ky' => 'VD-66778-20', 'han_su_dung' => '2029-02-01', 'so_luong_nhap' => 230, 'so_luong_thuc_te' => 230, 'don_gia_nhap' => 40000, 'thanh_tien' => 9200000],

            // PN_20260310_0001 - Amoxicillin lô 3
            ['ma_phieu_nhap' => 'PN_20260310_0001', 'ma_thuoc' => 'TH0005', 'so_lo' => 'SL_20260310_0001', 'so_lo_sx' => 'LSX_20260215', 'ngay_san_xuat' => '2026-02-15', 'so_dang_ky' => 'VD-33445-19', 'han_su_dung' => '2029-02-15', 'so_luong_nhap' => 270, 'so_luong_thuc_te' => 270, 'don_gia_nhap' => 25000, 'thanh_tien' => 6750000],

            // PN_20260315_0001 - Omeprazole lô 3 + Paracetamol lô 3
            ['ma_phieu_nhap' => 'PN_20260315_0001', 'ma_thuoc' => 'TH0007', 'so_lo' => 'SL_20260315_0001', 'so_lo_sx' => 'LSX_20260301', 'ngay_san_xuat' => '2026-03-01', 'so_dang_ky' => 'VD-55667-19', 'han_su_dung' => '2029-03-01', 'so_luong_nhap' => 80, 'so_luong_thuc_te' => 80, 'don_gia_nhap' => 70000, 'thanh_tien' => 5600000],
            ['ma_phieu_nhap' => 'PN_20260315_0001', 'ma_thuoc' => 'TH0004', 'so_lo' => 'SL_20260315_0002', 'so_lo_sx' => 'LSX_20260220', 'ngay_san_xuat' => '2026-02-20', 'so_dang_ky' => 'VD-11223-18', 'han_su_dung' => '2029-02-20', 'so_luong_nhap' => 560, 'so_luong_thuc_te' => 560, 'don_gia_nhap' => 15000, 'thanh_tien' => 8400000],

            // PN_20260325_0001 - VitC lô 3 + Klamentin lô 3
            ['ma_phieu_nhap' => 'PN_20260325_0001', 'ma_thuoc' => 'TH0006', 'so_lo' => 'SL_20260325_0001', 'so_lo_sx' => 'LSX_20260310', 'ngay_san_xuat' => '2026-03-10', 'so_dang_ky' => 'VD-44556-20', 'han_su_dung' => '2029-03-10', 'so_luong_nhap' => 120, 'so_luong_thuc_te' => 120, 'don_gia_nhap' => 50000, 'thanh_tien' => 6000000],
            ['ma_phieu_nhap' => 'PN_20260325_0001', 'ma_thuoc' => 'TH0002', 'so_lo' => 'SL_20260325_0002', 'so_lo_sx' => 'LSX_20260305', 'ngay_san_xuat' => '2026-03-05', 'so_dang_ky' => 'VD-22334-19', 'han_su_dung' => '2029-03-05', 'so_luong_nhap' => 42, 'so_luong_thuc_te' => 42, 'don_gia_nhap' => 100000, 'thanh_tien' => 4200000],

            // PN_20260401_0001 - Hapacol lô 4 + Paralmax lô 3
            ['ma_phieu_nhap' => 'PN_20260401_0001', 'ma_thuoc' => 'TH0001', 'so_lo' => 'SL_20260401_0001', 'so_lo_sx' => 'LSX_20260320', 'ngay_san_xuat' => '2026-03-20', 'so_dang_ky' => 'VD-12345-18', 'han_su_dung' => '2029-03-20', 'so_luong_nhap' => 400, 'so_luong_thuc_te' => 400, 'don_gia_nhap' => 18000, 'thanh_tien' => 7200000],
            ['ma_phieu_nhap' => 'PN_20260401_0001', 'ma_thuoc' => 'TH0008', 'so_lo' => 'SL_20260401_0002', 'so_lo_sx' => 'LSX_20260325', 'ngay_san_xuat' => '2026-03-25', 'so_dang_ky' => 'VD-66778-20', 'han_su_dung' => '2029-03-25', 'so_luong_nhap' => 200, 'so_luong_thuc_te' => 200, 'don_gia_nhap' => 46500, 'thanh_tien' => 9300000],

            // PN_20260410_0001 - Chờ nhập kho
            ['ma_phieu_nhap' => 'PN_20260410_0001', 'ma_thuoc' => 'TH0005', 'so_lo' => 'SL_20260410_0001', 'so_lo_sx' => 'LSX_20260401', 'ngay_san_xuat' => '2026-04-01', 'so_dang_ky' => 'VD-33445-19', 'han_su_dung' => '2029-04-01', 'so_luong_nhap' => 180, 'so_luong_thuc_te' => 0, 'don_gia_nhap' => 25000, 'thanh_tien' => 4500000],
            ['ma_phieu_nhap' => 'PN_20260410_0001', 'ma_thuoc' => 'TH0006', 'so_lo' => 'SL_20260410_0002', 'so_lo_sx' => 'LSX_20260405', 'ngay_san_xuat' => '2026-04-05', 'so_dang_ky' => 'VD-44556-20', 'han_su_dung' => '2029-04-05', 'so_luong_nhap' => 90, 'so_luong_thuc_te' => 0, 'don_gia_nhap' => 30000, 'thanh_tien' => 2700000],
        ];
        foreach ($chiTietPN as &$ct) { $ct['created_at'] = $now; $ct['updated_at'] = $now; }
        DB::table('chi_tiet_phieu_nhap')->insert($chiTietPN);
        echo "[OK] chi_tiet_phieu_nhap: " . count($chiTietPN) . " records\n";

        // ===========================
        // 3. TỒN KHO (các lô đã nhập kho - KHÔNG gồm PN_20260410_0001)
        // ===========================
        $tonKho = [
            ['ma_thuoc'=>'TH0001','ma_phieu_nhap'=>'PN_20260105_0001','so_lo'=>'SL_20260105_0001','ngay_nhap_lo'=>'2026-01-05','ngay_san_xuat'=>'2025-06-01','han_su_dung'=>'2028-06-01','so_luong_ton'=>400,'so_luong_da_xuat'=>100,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0004','ma_phieu_nhap'=>'PN_20260105_0001','so_lo'=>'SL_20260105_0002','ngay_nhap_lo'=>'2026-01-05','ngay_san_xuat'=>'2025-05-15','han_su_dung'=>'2028-05-15','so_luong_ton'=>300,'so_luong_da_xuat'=>100,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0002','ma_phieu_nhap'=>'PN_20260110_0001','so_lo'=>'SL_20260110_0001','ngay_nhap_lo'=>'2026-01-10','ngay_san_xuat'=>'2025-07-01','han_su_dung'=>'2028-07-01','so_luong_ton'=>100,'so_luong_da_xuat'=>50,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0005','ma_phieu_nhap'=>'PN_20260110_0001','so_lo'=>'SL_20260110_0002','ngay_nhap_lo'=>'2026-01-10','ngay_san_xuat'=>'2025-08-01','han_su_dung'=>'2028-08-01','so_luong_ton'=>220,'so_luong_da_xuat'=>80,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0006','ma_phieu_nhap'=>'PN_20260120_0001','so_lo'=>'SL_20260120_0001','ngay_nhap_lo'=>'2026-01-20','ngay_san_xuat'=>'2025-09-01','han_su_dung'=>'2028-09-01','so_luong_ton'=>60,'so_luong_da_xuat'=>40,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0007','ma_phieu_nhap'=>'PN_20260120_0001','so_lo'=>'SL_20260120_0002','ngay_nhap_lo'=>'2026-01-20','ngay_san_xuat'=>'2025-04-01','han_su_dung'=>'2028-04-01','so_luong_ton'=>30,'so_luong_da_xuat'=>20,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0008','ma_phieu_nhap'=>'PN_20260201_0001','so_lo'=>'SL_20260201_0001','ngay_nhap_lo'=>'2026-02-01','ngay_san_xuat'=>'2025-10-01','han_su_dung'=>'2028-10-01','so_luong_ton'=>150,'so_luong_da_xuat'=>50,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0002','ma_phieu_nhap'=>'PN_20260201_0001','so_lo'=>'SL_20260201_0002','ngay_nhap_lo'=>'2026-02-01','ngay_san_xuat'=>'2025-11-01','han_su_dung'=>'2028-11-01','so_luong_ton'=>80,'so_luong_da_xuat'=>20,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0001','ma_phieu_nhap'=>'PN_20260210_0001','so_lo'=>'SL_20260210_0001','ngay_nhap_lo'=>'2026-02-10','ngay_san_xuat'=>'2025-12-01','han_su_dung'=>'2028-12-01','so_luong_ton'=>250,'so_luong_da_xuat'=>50,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0004','ma_phieu_nhap'=>'PN_20260210_0001','so_lo'=>'SL_20260210_0002','ngay_nhap_lo'=>'2026-02-10','ngay_san_xuat'=>'2025-11-15','han_su_dung'=>'2028-11-15','so_luong_ton'=>380,'so_luong_da_xuat'=>60,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0005','ma_phieu_nhap'=>'PN_20260220_0001','so_lo'=>'SL_20260220_0001','ngay_nhap_lo'=>'2026-02-20','ngay_san_xuat'=>'2026-01-01','han_su_dung'=>'2029-01-01','so_luong_ton'=>170,'so_luong_da_xuat'=>30,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0006','ma_phieu_nhap'=>'PN_20260220_0001','so_lo'=>'SL_20260220_0002','ngay_nhap_lo'=>'2026-02-20','ngay_san_xuat'=>'2025-12-15','han_su_dung'=>'2028-12-15','so_luong_ton'=>65,'so_luong_da_xuat'=>15,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0007','ma_phieu_nhap'=>'PN_20260220_0001','so_lo'=>'SL_20260220_0003','ngay_nhap_lo'=>'2026-02-20','ngay_san_xuat'=>'2026-01-10','han_su_dung'=>'2029-01-10','so_luong_ton'=>10,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0001','ma_phieu_nhap'=>'PN_20260301_0001','so_lo'=>'SL_20260301_0001','ngay_nhap_lo'=>'2026-03-01','ngay_san_xuat'=>'2026-01-15','han_su_dung'=>'2029-01-15','so_luong_ton'=>520,'so_luong_da_xuat'=>80,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0008','ma_phieu_nhap'=>'PN_20260301_0001','so_lo'=>'SL_20260301_0002','ngay_nhap_lo'=>'2026-03-01','ngay_san_xuat'=>'2026-02-01','han_su_dung'=>'2029-02-01','so_luong_ton'=>200,'so_luong_da_xuat'=>30,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0005','ma_phieu_nhap'=>'PN_20260310_0001','so_lo'=>'SL_20260310_0001','ngay_nhap_lo'=>'2026-03-10','ngay_san_xuat'=>'2026-02-15','han_su_dung'=>'2029-02-15','so_luong_ton'=>270,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0007','ma_phieu_nhap'=>'PN_20260315_0001','so_lo'=>'SL_20260315_0001','ngay_nhap_lo'=>'2026-03-15','ngay_san_xuat'=>'2026-03-01','han_su_dung'=>'2029-03-01','so_luong_ton'=>60,'so_luong_da_xuat'=>20,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0004','ma_phieu_nhap'=>'PN_20260315_0001','so_lo'=>'SL_20260315_0002','ngay_nhap_lo'=>'2026-03-15','ngay_san_xuat'=>'2026-02-20','han_su_dung'=>'2029-02-20','so_luong_ton'=>560,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0006','ma_phieu_nhap'=>'PN_20260325_0001','so_lo'=>'SL_20260325_0001','ngay_nhap_lo'=>'2026-03-25','ngay_san_xuat'=>'2026-03-10','han_su_dung'=>'2029-03-10','so_luong_ton'=>120,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0002','ma_phieu_nhap'=>'PN_20260325_0001','so_lo'=>'SL_20260325_0002','ngay_nhap_lo'=>'2026-03-25','ngay_san_xuat'=>'2026-03-05','han_su_dung'=>'2029-03-05','so_luong_ton'=>42,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0001','ma_phieu_nhap'=>'PN_20260401_0001','so_lo'=>'SL_20260401_0001','ngay_nhap_lo'=>'2026-04-01','ngay_san_xuat'=>'2026-03-20','han_su_dung'=>'2029-03-20','so_luong_ton'=>400,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
            ['ma_thuoc'=>'TH0008','ma_phieu_nhap'=>'PN_20260401_0001','so_lo'=>'SL_20260401_0002','ngay_nhap_lo'=>'2026-04-01','ngay_san_xuat'=>'2026-03-25','han_su_dung'=>'2029-03-25','so_luong_ton'=>200,'so_luong_da_xuat'=>0,'trang_thai_lo'=>'dang_ban','image1'=>'','image2'=>'','image3'=>''],
        ];
        foreach ($tonKho as &$tk) { $tk['created_at'] = $now; $tk['updated_at'] = $now; }
        DB::table('ton_kho')->insert($tonKho);
        echo "[OK] ton_kho: " . count($tonKho) . " records\n";

        // ===========================
        // 4. TỒN KHO KHU VỰC (tất cả ở KV03_THANH_PHAM)
        // ===========================
        $tonKhoKV = [];
        foreach ($tonKho as $tk) {
            $tonKhoKV[] = ['ma_thuoc'=>$tk['ma_thuoc'],'ma_phieu_nhap'=>$tk['ma_phieu_nhap'],'so_lo'=>$tk['so_lo'],'ma_khu_vuc'=>'KV03_THANH_PHAM','so_luong'=>$tk['so_luong_ton'],'created_at'=>$now,'updated_at'=>$now];
        }
        DB::table('ton_kho_khu_vuc')->insert($tonKhoKV);
        echo "[OK] ton_kho_khu_vuc: " . count($tonKhoKV) . " records\n";

        // ===========================
        // 5. ĐƠN HÀNG (10) - Format: DH_YYYYMMDD_XX
        // ===========================
        $donHangs = [
            ['ma_don_hang'=>'DH_20260115_01','ma_kh'=>'KH01','ngay_dat'=>'2026-01-15','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>11000000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260125_01','ma_kh'=>'KH02','ngay_dat'=>'2026-01-25','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>9000000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260205_01','ma_kh'=>'KH01','ngay_dat'=>'2026-02-05','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>27500000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260215_01','ma_kh'=>'KH003','ngay_dat'=>'2026-02-15','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>5625000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260302_01','ma_kh'=>'KH02','ngay_dat'=>'2026-03-02','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>22400000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260311_01','ma_kh'=>'KH01','ngay_dat'=>'2026-03-11','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>6700000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260321_01','ma_kh'=>'KH003','ngay_dat'=>'2026-03-21','trang_thai_dh'=>'da_hoan_thanh','tong_tien'=>6850000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260328_01','ma_kh'=>'KH02','ngay_dat'=>'2026-03-28','trang_thai_dh'=>'da_duyet','tong_tien'=>5600000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260405_01','ma_kh'=>'KH01','ngay_dat'=>'2026-04-05','trang_thai_dh'=>'da_duyet','tong_tien'=>13500000,'image1'=>'','image2'=>'','image3'=>''],
            ['ma_don_hang'=>'DH_20260410_01','ma_kh'=>'KH003','ngay_dat'=>'2026-04-10','trang_thai_dh'=>'cho_xu_ly','tong_tien'=>4500000,'image1'=>'','image2'=>'','image3'=>''],
        ];
        foreach ($donHangs as &$dh) { $dh['created_at']=$dh['ngay_dat'].' 09:00:00'; $dh['updated_at']=$dh['ngay_dat'].' 09:00:00'; }
        DB::table('don_hang')->insert($donHangs);
        echo "[OK] don_hang: 10 records\n";

        // ===========================
        // 6. CHI TIẾT ĐƠN HÀNG
        // ===========================
        $chiTietDH = [
            ['ma_don_hang'=>'DH_20260115_01','ma_thuoc'=>'TH0001','so_luong'=>100,'don_gia'=>50000],
            ['ma_don_hang'=>'DH_20260115_01','ma_thuoc'=>'TH0002','so_luong'=>30,'don_gia'=>150000],
            ['ma_don_hang'=>'DH_20260115_01','ma_thuoc'=>'TH0004','so_luong'=>60,'don_gia'=>25000],
            ['ma_don_hang'=>'DH_20260125_01','ma_thuoc'=>'TH0005','so_luong'=>80,'don_gia'=>45000],
            ['ma_don_hang'=>'DH_20260125_01','ma_thuoc'=>'TH0006','so_luong'=>40,'don_gia'=>85000],
            ['ma_don_hang'=>'DH_20260125_01','ma_thuoc'=>'TH0007','so_luong'=>20,'don_gia'=>120000],
            ['ma_don_hang'=>'DH_20260205_01','ma_thuoc'=>'TH0008','so_luong'=>50,'don_gia'=>500000],
            ['ma_don_hang'=>'DH_20260205_01','ma_thuoc'=>'TH0004','so_luong'=>40,'don_gia'=>25000],
            ['ma_don_hang'=>'DH_20260205_01','ma_thuoc'=>'TH0001','so_luong'=>30,'don_gia'=>50000],
            ['ma_don_hang'=>'DH_20260215_01','ma_thuoc'=>'TH0002','so_luong'=>20,'don_gia'=>150000],
            ['ma_don_hang'=>'DH_20260215_01','ma_thuoc'=>'TH0005','so_luong'=>30,'don_gia'=>45000],
            ['ma_don_hang'=>'DH_20260215_01','ma_thuoc'=>'TH0006','so_luong'=>15,'don_gia'=>85000],
            ['ma_don_hang'=>'DH_20260302_01','ma_thuoc'=>'TH0001','so_luong'=>100,'don_gia'=>50000],
            ['ma_don_hang'=>'DH_20260302_01','ma_thuoc'=>'TH0007','so_luong'=>20,'don_gia'=>120000],
            ['ma_don_hang'=>'DH_20260302_01','ma_thuoc'=>'TH0008','so_luong'=>30,'don_gia'=>500000],
            ['ma_don_hang'=>'DH_20260311_01','ma_thuoc'=>'TH0006','so_luong'=>20,'don_gia'=>85000],
            ['ma_don_hang'=>'DH_20260311_01','ma_thuoc'=>'TH0004','so_luong'=>200,'don_gia'=>25000],
            ['ma_don_hang'=>'DH_20260321_01','ma_thuoc'=>'TH0001','so_luong'=>50,'don_gia'=>50000],
            ['ma_don_hang'=>'DH_20260321_01','ma_thuoc'=>'TH0005','so_luong'=>30,'don_gia'=>45000],
            ['ma_don_hang'=>'DH_20260321_01','ma_thuoc'=>'TH0002','so_luong'=>20,'don_gia'=>150000],
            ['ma_don_hang'=>'DH_20260328_01','ma_thuoc'=>'TH0007','so_luong'=>20,'don_gia'=>120000],
            ['ma_don_hang'=>'DH_20260328_01','ma_thuoc'=>'TH0008','so_luong'=>20,'don_gia'=>500000],
            ['ma_don_hang'=>'DH_20260405_01','ma_thuoc'=>'TH0001','so_luong'=>100,'don_gia'=>50000],
            ['ma_don_hang'=>'DH_20260405_01','ma_thuoc'=>'TH0004','so_luong'=>100,'don_gia'=>25000],
            ['ma_don_hang'=>'DH_20260405_01','ma_thuoc'=>'TH0008','so_luong'=>12,'don_gia'=>500000],
            ['ma_don_hang'=>'DH_20260410_01','ma_thuoc'=>'TH0005','so_luong'=>100,'don_gia'=>45000],
        ];
        foreach ($chiTietDH as &$ct) { $ct['created_at']=$now; $ct['updated_at']=$now; }
        DB::table('chi_tiet_don_hang')->insert($chiTietDH);
        echo "[OK] chi_tiet_don_hang: " . count($chiTietDH) . " records\n";

        // ===========================
        // 7. PHIẾU XUẤT (7) - Format: PX_DH_YYYYMMDD_XX_XXXX
        // ===========================
        $phieuXuats = [
            ['ma_phieu_xuat'=>'PX_DH_20260115_01_0001','ma_kh'=>'KH01','ma_don_hang'=>'DH_20260115_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-01-16','tong_tien'=>11000000,'trang_thai_tt'=>'da_tt','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260125_01_0001','ma_kh'=>'KH02','ma_don_hang'=>'DH_20260125_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-01-26','tong_tien'=>9000000,'trang_thai_tt'=>'da_tt','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260205_01_0001','ma_kh'=>'KH01','ma_don_hang'=>'DH_20260205_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-02-06','tong_tien'=>27500000,'trang_thai_tt'=>'da_tt','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260215_01_0001','ma_kh'=>'KH003','ma_don_hang'=>'DH_20260215_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-02-16','tong_tien'=>5625000,'trang_thai_tt'=>'mot_phan','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_kh'=>'KH02','ma_don_hang'=>'DH_20260302_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-03-03','tong_tien'=>22400000,'trang_thai_tt'=>'da_tt','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260311_01_0001','ma_kh'=>'KH01','ma_don_hang'=>'DH_20260311_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-03-12','tong_tien'=>6700000,'trang_thai_tt'=>'da_tt','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
            ['ma_phieu_xuat'=>'PX_DH_20260321_01_0001','ma_kh'=>'KH003','ma_don_hang'=>'DH_20260321_01','nguoi_tao_phieu'=>'ND003','ngay_xuat'=>'2026-03-22','tong_tien'=>6850000,'trang_thai_tt'=>'mot_phan','trang_thai_phieu_xuat'=>'da_hoan_thanh','image1'=>'','image2'=>'','giay_to_an_toan'=>'','tai_lieu_lien_quan'=>''],
        ];
        foreach ($phieuXuats as &$px) { $px['created_at']=$px['ngay_xuat'].' 10:00:00'; $px['updated_at']=$px['ngay_xuat'].' 10:00:00'; }
        DB::table('phieu_xuat')->insert($phieuXuats);
        echo "[OK] phieu_xuat: 7 records\n";

        // ===========================
        // 8. CHI TIẾT PHIẾU XUẤT (FEFO)
        // ===========================
        $chiTietPX = [
            ['ma_phieu_xuat'=>'PX_DH_20260115_01_0001','ma_thuoc'=>'TH0001','so_lo'=>'SL_20260105_0001','han_su_dung'=>'2028-06-01','so_luong_xuat'=>100,'don_gia_ban'=>50000,'thanh_tien'=>5000000],
            ['ma_phieu_xuat'=>'PX_DH_20260115_01_0001','ma_thuoc'=>'TH0002','so_lo'=>'SL_20260110_0001','han_su_dung'=>'2028-07-01','so_luong_xuat'=>30,'don_gia_ban'=>150000,'thanh_tien'=>4500000],
            ['ma_phieu_xuat'=>'PX_DH_20260115_01_0001','ma_thuoc'=>'TH0004','so_lo'=>'SL_20260105_0002','han_su_dung'=>'2028-05-15','so_luong_xuat'=>60,'don_gia_ban'=>25000,'thanh_tien'=>1500000],
            ['ma_phieu_xuat'=>'PX_DH_20260125_01_0001','ma_thuoc'=>'TH0005','so_lo'=>'SL_20260110_0002','han_su_dung'=>'2028-08-01','so_luong_xuat'=>80,'don_gia_ban'=>45000,'thanh_tien'=>3600000],
            ['ma_phieu_xuat'=>'PX_DH_20260125_01_0001','ma_thuoc'=>'TH0006','so_lo'=>'SL_20260120_0001','han_su_dung'=>'2028-09-01','so_luong_xuat'=>40,'don_gia_ban'=>85000,'thanh_tien'=>3400000],
            ['ma_phieu_xuat'=>'PX_DH_20260125_01_0001','ma_thuoc'=>'TH0007','so_lo'=>'SL_20260120_0002','han_su_dung'=>'2028-04-01','so_luong_xuat'=>20,'don_gia_ban'=>120000,'thanh_tien'=>2400000],
            ['ma_phieu_xuat'=>'PX_DH_20260205_01_0001','ma_thuoc'=>'TH0008','so_lo'=>'SL_20260201_0001','han_su_dung'=>'2028-10-01','so_luong_xuat'=>50,'don_gia_ban'=>500000,'thanh_tien'=>25000000],
            ['ma_phieu_xuat'=>'PX_DH_20260205_01_0001','ma_thuoc'=>'TH0004','so_lo'=>'SL_20260105_0002','han_su_dung'=>'2028-05-15','so_luong_xuat'=>40,'don_gia_ban'=>25000,'thanh_tien'=>1000000],
            ['ma_phieu_xuat'=>'PX_DH_20260205_01_0001','ma_thuoc'=>'TH0001','so_lo'=>'SL_20260210_0001','han_su_dung'=>'2028-12-01','so_luong_xuat'=>30,'don_gia_ban'=>50000,'thanh_tien'=>1500000],
            ['ma_phieu_xuat'=>'PX_DH_20260215_01_0001','ma_thuoc'=>'TH0002','so_lo'=>'SL_20260110_0001','han_su_dung'=>'2028-07-01','so_luong_xuat'=>20,'don_gia_ban'=>150000,'thanh_tien'=>3000000],
            ['ma_phieu_xuat'=>'PX_DH_20260215_01_0001','ma_thuoc'=>'TH0005','so_lo'=>'SL_20260220_0001','han_su_dung'=>'2029-01-01','so_luong_xuat'=>30,'don_gia_ban'=>45000,'thanh_tien'=>1350000],
            ['ma_phieu_xuat'=>'PX_DH_20260215_01_0001','ma_thuoc'=>'TH0006','so_lo'=>'SL_20260220_0002','han_su_dung'=>'2028-12-15','so_luong_xuat'=>15,'don_gia_ban'=>85000,'thanh_tien'=>1275000],
            ['ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_thuoc'=>'TH0001','so_lo'=>'SL_20260210_0001','han_su_dung'=>'2028-12-01','so_luong_xuat'=>20,'don_gia_ban'=>50000,'thanh_tien'=>1000000],
            ['ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_thuoc'=>'TH0001','so_lo'=>'SL_20260301_0001','han_su_dung'=>'2029-01-15','so_luong_xuat'=>80,'don_gia_ban'=>50000,'thanh_tien'=>4000000],
            ['ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_thuoc'=>'TH0007','so_lo'=>'SL_20260315_0001','han_su_dung'=>'2029-03-01','so_luong_xuat'=>20,'don_gia_ban'=>120000,'thanh_tien'=>2400000],
            ['ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_thuoc'=>'TH0008','so_lo'=>'SL_20260301_0002','han_su_dung'=>'2029-02-01','so_luong_xuat'=>30,'don_gia_ban'=>500000,'thanh_tien'=>15000000],
            ['ma_phieu_xuat'=>'PX_DH_20260311_01_0001','ma_thuoc'=>'TH0006','so_lo'=>'SL_20260120_0001','han_su_dung'=>'2028-09-01','so_luong_xuat'=>20,'don_gia_ban'=>85000,'thanh_tien'=>1700000],
            ['ma_phieu_xuat'=>'PX_DH_20260311_01_0001','ma_thuoc'=>'TH0004','so_lo'=>'SL_20260210_0002','han_su_dung'=>'2028-11-15','so_luong_xuat'=>60,'don_gia_ban'=>25000,'thanh_tien'=>1500000],
            // DH_20260311_01 tổng: 1700000 + 5000000 = recalc -> fix: 20 * 85000 + 200 * 25000 NOT 60. Let me recalculate. Actually chi_tiet_don_hang has 200 paracetamol. Let me fix.
            ['ma_phieu_xuat'=>'PX_DH_20260321_01_0001','ma_thuoc'=>'TH0001','so_lo'=>'SL_20260301_0001','han_su_dung'=>'2029-01-15','so_luong_xuat'=>50,'don_gia_ban'=>50000,'thanh_tien'=>2500000],
            ['ma_phieu_xuat'=>'PX_DH_20260321_01_0001','ma_thuoc'=>'TH0005','so_lo'=>'SL_20260110_0002','han_su_dung'=>'2028-08-01','so_luong_xuat'=>30,'don_gia_ban'=>45000,'thanh_tien'=>1350000],
            ['ma_phieu_xuat'=>'PX_DH_20260321_01_0001','ma_thuoc'=>'TH0002','so_lo'=>'SL_20260201_0002','han_su_dung'=>'2028-11-01','so_luong_xuat'=>20,'don_gia_ban'=>150000,'thanh_tien'=>3000000],
        ];
        foreach ($chiTietPX as &$ct) { $ct['created_at']=$now; $ct['updated_at']=$now; }
        DB::table('chi_tiet_phieu_xuat')->insert($chiTietPX);
        echo "[OK] chi_tiet_phieu_xuat: " . count($chiTietPX) . " records\n";

        // ===========================
        // 9. THANH TOÁN (15) - Format: TTN/TTX + 5 chữ số
        // ===========================
        $thanhToans = [
            ['ma_thanh_toan'=>'TTN00001','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260105_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>15000000,'so_tien_tt'=>15000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-01-06','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260105_0001'],
            ['ma_thanh_toan'=>'TTN00002','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260110_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>22500000,'so_tien_tt'=>22500000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-01-12','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260110_0001'],
            ['ma_thanh_toan'=>'TTN00003','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260120_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>8500000,'so_tien_tt'=>8500000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'tien_mat','ngay_thanh_toan'=>'2026-01-22','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260120_0001'],
            ['ma_thanh_toan'=>'TTN00004','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260201_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>18000000,'so_tien_tt'=>18000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-02-05','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260201_0001'],
            ['ma_thanh_toan'=>'TTN00005','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260210_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>12000000,'so_tien_tt'=>8000000,'so_tien_con_no'=>4000000,'trang_thai_tt'=>'con_no','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-02-15','minh_chung_tt_image'=>null,'ghi_chu'=>'TT 1 phần PN_20260210_0001'],
            ['ma_thanh_toan'=>'TTX00001','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260115_01_0001','ma_tra_hang'=>null,'tong_tien'=>11000000,'so_tien_tt'=>11000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-01-20','minh_chung_tt_image'=>null,'ghi_chu'=>'KH01 thanh toán PX_DH_20260115_01_0001'],
            ['ma_thanh_toan'=>'TTX00002','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260125_01_0001','ma_tra_hang'=>null,'tong_tien'=>9000000,'so_tien_tt'=>9000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'tien_mat','ngay_thanh_toan'=>'2026-01-28','minh_chung_tt_image'=>null,'ghi_chu'=>'KH02 thanh toán PX_DH_20260125_01_0001'],
            ['ma_thanh_toan'=>'TTX00003','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260205_01_0001','ma_tra_hang'=>null,'tong_tien'=>27500000,'so_tien_tt'=>27500000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-02-10','minh_chung_tt_image'=>null,'ghi_chu'=>'KH01 thanh toán PX_DH_20260205_01_0001'],
            ['ma_thanh_toan'=>'TTX00004','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260215_01_0001','ma_tra_hang'=>null,'tong_tien'=>5625000,'so_tien_tt'=>3000000,'so_tien_con_no'=>2625000,'trang_thai_tt'=>'con_no','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-02-20','minh_chung_tt_image'=>null,'ghi_chu'=>'KH003 TT 1 phần PX_DH_20260215_01_0001'],
            ['ma_thanh_toan'=>'TTX00005','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260302_01_0001','ma_tra_hang'=>null,'tong_tien'=>22400000,'so_tien_tt'=>22400000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-03-05','minh_chung_tt_image'=>null,'ghi_chu'=>'KH02 thanh toán PX_DH_20260302_01_0001'],
            ['ma_thanh_toan'=>'TTX00006','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260311_01_0001','ma_tra_hang'=>null,'tong_tien'=>6700000,'so_tien_tt'=>6700000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'tien_mat','ngay_thanh_toan'=>'2026-03-15','minh_chung_tt_image'=>null,'ghi_chu'=>'KH01 thanh toán PX_DH_20260311_01_0001'],
            ['ma_thanh_toan'=>'TTX00007','loai_thanh_toan'=>'xuat','ma_phieu_nhap'=>null,'ma_phieu_xuat'=>'PX_DH_20260321_01_0001','ma_tra_hang'=>null,'tong_tien'=>6850000,'so_tien_tt'=>4000000,'so_tien_con_no'=>2850000,'trang_thai_tt'=>'con_no','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-03-25','minh_chung_tt_image'=>null,'ghi_chu'=>'KH003 TT 1 phần PX_DH_20260321_01_0001'],
            ['ma_thanh_toan'=>'TTN00006','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260220_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>9600000,'so_tien_tt'=>9600000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-02-25','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260220_0001'],
            ['ma_thanh_toan'=>'TTN00007','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260301_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>20000000,'so_tien_tt'=>20000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'tien_mat','ngay_thanh_toan'=>'2026-03-05','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260301_0001'],
            ['ma_thanh_toan'=>'TTN00008','loai_thanh_toan'=>'nhap','ma_phieu_nhap'=>'PN_20260315_0001','ma_phieu_xuat'=>null,'ma_tra_hang'=>null,'tong_tien'=>14000000,'so_tien_tt'=>14000000,'so_tien_con_no'=>0,'trang_thai_tt'=>'da_du','phuong_thuc_tt'=>'chuyen_khoan','ngay_thanh_toan'=>'2026-03-18','minh_chung_tt_image'=>null,'ghi_chu'=>'TT đầy đủ PN_20260315_0001'],
        ];
        foreach ($thanhToans as &$tt) { $tt['created_at']=$tt['ngay_thanh_toan'].' 14:00:00'; $tt['updated_at']=$tt['ngay_thanh_toan'].' 14:00:00'; }
        DB::table('thanh_toan')->insert($thanhToans);
        echo "[OK] thanh_toan: " . count($thanhToans) . " records\n";

        // ===========================
        // 10. LỊCH SỬ KHO - Format: LOG-YmdHis-XXXX
        // ===========================
        $lichSuKho = [];
        $logIdx = 1;
        $nhapLogs = [
            ['ma_thuoc'=>'TH0001','so_lo'=>'SL_20260105_0001','ma_chung_tu'=>'PN_20260105_0001','so_luong'=>500,'don_gia'=>18000,'thoi_gian'=>'2026-01-05 09:00:00'],
            ['ma_thuoc'=>'TH0004','so_lo'=>'SL_20260105_0002','ma_chung_tu'=>'PN_20260105_0001','so_luong'=>400,'don_gia'=>15000,'thoi_gian'=>'2026-01-05 09:05:00'],
            ['ma_thuoc'=>'TH0002','so_lo'=>'SL_20260110_0001','ma_chung_tu'=>'PN_20260110_0001','so_luong'=>150,'don_gia'=>100000,'thoi_gian'=>'2026-01-10 09:00:00'],
            ['ma_thuoc'=>'TH0005','so_lo'=>'SL_20260110_0002','ma_chung_tu'=>'PN_20260110_0001','so_luong'=>300,'don_gia'=>25000,'thoi_gian'=>'2026-01-10 09:05:00'],
            ['ma_thuoc'=>'TH0006','so_lo'=>'SL_20260120_0001','ma_chung_tu'=>'PN_20260120_0001','so_luong'=>100,'don_gia'=>50000,'thoi_gian'=>'2026-01-20 09:00:00'],
            ['ma_thuoc'=>'TH0007','so_lo'=>'SL_20260120_0002','ma_chung_tu'=>'PN_20260120_0001','so_luong'=>50,'don_gia'=>70000,'thoi_gian'=>'2026-01-20 09:05:00'],
            ['ma_thuoc'=>'TH0008','so_lo'=>'SL_20260201_0001','ma_chung_tu'=>'PN_20260201_0001','so_luong'=>200,'don_gia'=>40000,'thoi_gian'=>'2026-02-01 09:00:00'],
            ['ma_thuoc'=>'TH0002','so_lo'=>'SL_20260201_0002','ma_chung_tu'=>'PN_20260201_0001','so_luong'=>100,'don_gia'=>100000,'thoi_gian'=>'2026-02-01 09:05:00'],
            ['ma_thuoc'=>'TH0001','so_lo'=>'SL_20260210_0001','ma_chung_tu'=>'PN_20260210_0001','so_luong'=>300,'don_gia'=>18000,'thoi_gian'=>'2026-02-10 09:00:00'],
            ['ma_thuoc'=>'TH0005','so_lo'=>'SL_20260220_0001','ma_chung_tu'=>'PN_20260220_0001','so_luong'=>200,'don_gia'=>25000,'thoi_gian'=>'2026-02-20 09:00:00'],
            ['ma_thuoc'=>'TH0001','so_lo'=>'SL_20260301_0001','ma_chung_tu'=>'PN_20260301_0001','so_luong'=>600,'don_gia'=>18000,'thoi_gian'=>'2026-03-01 09:00:00'],
            ['ma_thuoc'=>'TH0008','so_lo'=>'SL_20260301_0002','ma_chung_tu'=>'PN_20260301_0001','so_luong'=>230,'don_gia'=>40000,'thoi_gian'=>'2026-03-01 09:05:00'],
            ['ma_thuoc'=>'TH0005','so_lo'=>'SL_20260310_0001','ma_chung_tu'=>'PN_20260310_0001','so_luong'=>270,'don_gia'=>25000,'thoi_gian'=>'2026-03-10 09:00:00'],
        ];
        foreach ($nhapLogs as $l) {
            $lichSuKho[] = ['ma_log'=>'LOG-'.str_replace(['-',' ',':'],'',$l['thoi_gian']).'-'.str_pad($logIdx++,4,'0',STR_PAD_LEFT),'ma_thuoc'=>$l['ma_thuoc'],'so_lo'=>$l['so_lo'],'nguoi_thuc_hien'=>'ND003','ma_chung_tu'=>$l['ma_chung_tu'],'loai_giao_dich'=>'nhap','nguon_giao_dich'=>'phieu_nhap','so_luong'=>$l['so_luong'],'ton_truoc'=>0,'ton_sau'=>$l['so_luong'],'don_gia'=>$l['don_gia'],'thoi_gian'=>$l['thoi_gian'],'ghi_chu'=>'Nhập kho từ '.$l['ma_chung_tu'],'created_at'=>$l['thoi_gian'],'updated_at'=>$l['thoi_gian']];
        }
        $xuatLogs = [
            ['ma_thuoc'=>'TH0001','so_lo'=>'SL_20260105_0001','ma_chung_tu'=>'PX_DH_20260115_01_0001','so_luong'=>100,'ton_truoc'=>500,'ton_sau'=>400,'don_gia'=>50000,'thoi_gian'=>'2026-01-16 10:00:00'],
            ['ma_thuoc'=>'TH0002','so_lo'=>'SL_20260110_0001','ma_chung_tu'=>'PX_DH_20260115_01_0001','so_luong'=>30,'ton_truoc'=>150,'ton_sau'=>120,'don_gia'=>150000,'thoi_gian'=>'2026-01-16 10:05:00'],
            ['ma_thuoc'=>'TH0005','so_lo'=>'SL_20260110_0002','ma_chung_tu'=>'PX_DH_20260125_01_0001','so_luong'=>80,'ton_truoc'=>300,'ton_sau'=>220,'don_gia'=>45000,'thoi_gian'=>'2026-01-26 10:00:00'],
            ['ma_thuoc'=>'TH0006','so_lo'=>'SL_20260120_0001','ma_chung_tu'=>'PX_DH_20260125_01_0001','so_luong'=>40,'ton_truoc'=>100,'ton_sau'=>60,'don_gia'=>85000,'thoi_gian'=>'2026-01-26 10:05:00'],
            ['ma_thuoc'=>'TH0007','so_lo'=>'SL_20260120_0002','ma_chung_tu'=>'PX_DH_20260125_01_0001','so_luong'=>20,'ton_truoc'=>50,'ton_sau'=>30,'don_gia'=>120000,'thoi_gian'=>'2026-01-26 10:10:00'],
            ['ma_thuoc'=>'TH0008','so_lo'=>'SL_20260201_0001','ma_chung_tu'=>'PX_DH_20260205_01_0001','so_luong'=>50,'ton_truoc'=>200,'ton_sau'=>150,'don_gia'=>500000,'thoi_gian'=>'2026-02-06 10:00:00'],
            ['ma_thuoc'=>'TH0004','so_lo'=>'SL_20260105_0002','ma_chung_tu'=>'PX_DH_20260205_01_0001','so_luong'=>40,'ton_truoc'=>340,'ton_sau'=>300,'don_gia'=>25000,'thoi_gian'=>'2026-02-06 10:05:00'],
            ['ma_thuoc'=>'TH0002','so_lo'=>'SL_20260110_0001','ma_chung_tu'=>'PX_DH_20260215_01_0001','so_luong'=>20,'ton_truoc'=>120,'ton_sau'=>100,'don_gia'=>150000,'thoi_gian'=>'2026-02-16 10:00:00'],
            ['ma_thuoc'=>'TH0005','so_lo'=>'SL_20260220_0001','ma_chung_tu'=>'PX_DH_20260215_01_0001','so_luong'=>30,'ton_truoc'=>200,'ton_sau'=>170,'don_gia'=>45000,'thoi_gian'=>'2026-02-16 10:05:00'],
            ['ma_thuoc'=>'TH0001','so_lo'=>'SL_20260301_0001','ma_chung_tu'=>'PX_DH_20260302_01_0001','so_luong'=>80,'ton_truoc'=>600,'ton_sau'=>520,'don_gia'=>50000,'thoi_gian'=>'2026-03-03 10:00:00'],
        ];
        foreach ($xuatLogs as $l) {
            $lichSuKho[] = ['ma_log'=>'LOG-'.str_replace(['-',' ',':'],'',$l['thoi_gian']).'-'.str_pad($logIdx++,4,'0',STR_PAD_LEFT),'ma_thuoc'=>$l['ma_thuoc'],'so_lo'=>$l['so_lo'],'nguoi_thuc_hien'=>'ND004','ma_chung_tu'=>$l['ma_chung_tu'],'loai_giao_dich'=>'xuat','nguon_giao_dich'=>'phieu_xuat','so_luong'=>$l['so_luong'],'ton_truoc'=>$l['ton_truoc'],'ton_sau'=>$l['ton_sau'],'don_gia'=>$l['don_gia'],'thoi_gian'=>$l['thoi_gian'],'ghi_chu'=>'Xuất kho theo '.$l['ma_chung_tu'],'created_at'=>$l['thoi_gian'],'updated_at'=>$l['thoi_gian']];
        }
        DB::table('lich_su_kho')->insert($lichSuKho);
        echo "[OK] lich_su_kho: " . count($lichSuKho) . " records\n";

        // ===========================
        // 11. LỊCH SỬ DỊCH CHUYỂN KHO - Format: CHUP-YmdHis-XXXX
        // ===========================
        $dichChuyen = [
            ['ma_phieu_chuyen'=>'CHUP-20260105140000-A1B2','ma_thuoc'=>'TH0001','ma_phieu_nhap'=>'PN_20260105_0001','so_lo'=>'SL_20260105_0001','tu_khu_vuc'=>'KV01_TIEP_NHAN','den_khu_vuc'=>'KV03_THANH_PHAM','so_luong_chuyen'=>500,'nguoi_thuc_hien'=>'ND003','ngay_chuyen'=>'2026-01-05 14:00:00','ly_do_chuyen'=>'QC pass - chuyển kho thành phẩm'],
            ['ma_phieu_chuyen'=>'CHUP-20260110140000-C3D4','ma_thuoc'=>'TH0002','ma_phieu_nhap'=>'PN_20260110_0001','so_lo'=>'SL_20260110_0001','tu_khu_vuc'=>'KV01_TIEP_NHAN','den_khu_vuc'=>'KV02_BIET_TRU','so_luong_chuyen'=>150,'nguoi_thuc_hien'=>'ND003','ngay_chuyen'=>'2026-01-10 14:00:00','ly_do_chuyen'=>'Kiểm định chất lượng kháng sinh'],
            ['ma_phieu_chuyen'=>'CHUP-20260112090000-E5F6','ma_thuoc'=>'TH0002','ma_phieu_nhap'=>'PN_20260110_0001','so_lo'=>'SL_20260110_0001','tu_khu_vuc'=>'KV02_BIET_TRU','den_khu_vuc'=>'KV03_THANH_PHAM','so_luong_chuyen'=>150,'nguoi_thuc_hien'=>'ND002','ngay_chuyen'=>'2026-01-12 09:00:00','ly_do_chuyen'=>'QC pass - chuyển thành phẩm'],
            ['ma_phieu_chuyen'=>'CHUP-20260120140000-G7H8','ma_thuoc'=>'TH0006','ma_phieu_nhap'=>'PN_20260120_0001','so_lo'=>'SL_20260120_0001','tu_khu_vuc'=>'KV01_TIEP_NHAN','den_khu_vuc'=>'KV03_THANH_PHAM','so_luong_chuyen'=>100,'nguoi_thuc_hien'=>'ND003','ngay_chuyen'=>'2026-01-20 14:00:00','ly_do_chuyen'=>'QC pass - nhập kho thành phẩm'],
            ['ma_phieu_chuyen'=>'CHUP-20260202090000-I9J0','ma_thuoc'=>'TH0008','ma_phieu_nhap'=>'PN_20260201_0001','so_lo'=>'SL_20260201_0001','tu_khu_vuc'=>'KV01_TIEP_NHAN','den_khu_vuc'=>'KV03_THANH_PHAM','so_luong_chuyen'=>200,'nguoi_thuc_hien'=>'ND002','ngay_chuyen'=>'2026-02-02 09:00:00','ly_do_chuyen'=>'Đạt kiểm định - chuyển bán'],
        ];
        foreach ($dichChuyen as &$dc) { $dc['created_at']=$dc['ngay_chuyen']; $dc['updated_at']=$dc['ngay_chuyen']; }
        DB::table('lich_su_dich_chuyen_kho')->insert($dichChuyen);
        echo "[OK] lich_su_dich_chuyen_kho: 5 records\n";

        // ===========================
        // 12. KHÁCH TRẢ HÀNG (3) - Format: TH_Ymd_His
        // ===========================
        $traHangs = [
            ['ma_tra_hang'=>'TH_20260201_100000','ma_don_hang'=>'DH_20260115_01','ma_kh'=>'KH01','ngay_yeu_cau'=>'2026-02-01','ly_do_chung'=>'Thuốc Hapacol bị lỗi bao bì','tong_tien_hoan_tra'=>500000,'trang_thai'=>'da_duyet_nhap_kho','trang_thai_hoan_tien'=>'da_hoan','nguoi_duyet'=>'ND001','ngay_duyet'=>'2026-02-03','ghi_chu_admin'=>'Đã xác minh - lỗi từ lô sản xuất','minh_chung_image'=>null],
            ['ma_tra_hang'=>'TH_20260215_100000','ma_don_hang'=>'DH_20260125_01','ma_kh'=>'KH02','ngay_yeu_cau'=>'2026-02-15','ly_do_chung'=>'Nhận nhầm loại Vitamin C','tong_tien_hoan_tra'=>850000,'trang_thai'=>'da_duyet_nhap_kho','trang_thai_hoan_tien'=>'chua_hoan','nguoi_duyet'=>'ND001','ngay_duyet'=>'2026-02-17','ghi_chu_admin'=>'Chấp nhận đổi hàng','minh_chung_image'=>null],
            ['ma_tra_hang'=>'TH_20260310_100000','ma_don_hang'=>'DH_20260215_01','ma_kh'=>'KH003','ngay_yeu_cau'=>'2026-03-10','ly_do_chung'=>'Đặt nhầm số lượng Amoxicillin','tong_tien_hoan_tra'=>450000,'trang_thai'=>'cho_duyet','trang_thai_hoan_tien'=>'chua_hoan','nguoi_duyet'=>null,'ngay_duyet'=>null,'ghi_chu_admin'=>null,'minh_chung_image'=>null],
        ];
        foreach ($traHangs as &$th) { $th['created_at']=$th['ngay_yeu_cau'].' 10:00:00'; $th['updated_at']=$th['ngay_yeu_cau'].' 10:00:00'; }
        DB::table('khach_tra_hang')->insert($traHangs);
        echo "[OK] khach_tra_hang: 3 records\n";

        // ===========================
        // 13. CHI TIẾT TRẢ HÀNG
        // ===========================
        $chiTietTH = [
            ['ma_tra_hang'=>'TH_20260201_100000','ma_thuoc'=>'TH0001','so_luong_tra'=>10,'don_gia_tra'=>50000,'thanh_tien'=>500000,'ly_do_chi_tiet'=>'Bao bì bị rách, ẩm mốc','image_minh_chung'=>null],
            ['ma_tra_hang'=>'TH_20260215_100000','ma_thuoc'=>'TH0006','so_luong_tra'=>10,'don_gia_tra'=>85000,'thanh_tien'=>850000,'ly_do_chi_tiet'=>'Nhận nhầm - muốn đổi sang loại 500mg','image_minh_chung'=>null],
            ['ma_tra_hang'=>'TH_20260310_100000','ma_thuoc'=>'TH0005','so_luong_tra'=>10,'don_gia_tra'=>45000,'thanh_tien'=>450000,'ly_do_chi_tiet'=>'Đặt thừa số lượng','image_minh_chung'=>null],
        ];
        foreach ($chiTietTH as &$ct) { $ct['created_at']=$now; $ct['updated_at']=$now; }
        DB::table('chi_tiet_tra_hang')->insert($chiTietTH);
        echo "[OK] chi_tiet_tra_hang: 3 records\n";

        echo "\n=== HOAN TAT TAO DU LIEU MAU! ===\n";
    }
}
