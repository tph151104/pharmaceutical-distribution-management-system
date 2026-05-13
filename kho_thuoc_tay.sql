-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 06, 2026 at 04:39 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kho_thuoc_tay`
--
CREATE DATABASE IF NOT EXISTS `kho_thuoc_tay` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kho_thuoc_tay`;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
  `ma_don_hang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong` int NOT NULL,
  `don_gia` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_don_hang`,`ma_thuoc`),
  KEY `chi_tiet_don_hang_ma_thuoc_foreign` (`ma_thuoc`)
) ;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_don_hang`, `ma_thuoc`, `so_luong`, `don_gia`, `created_at`, `updated_at`) VALUES
('DH_20260115_01', 'TH0001', 100, 50000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260115_01', 'TH0002', 30, 150000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260115_01', 'TH0004', 60, 25000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260125_01', 'TH0005', 80, 45000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260125_01', 'TH0006', 40, 85000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260125_01', 'TH0007', 20, 120000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260205_01', 'TH0008', 50, 500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260205_01', 'TH0004', 40, 25000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260205_01', 'TH0001', 30, 50000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260215_01', 'TH0002', 20, 150000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260215_01', 'TH0005', 30, 45000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260215_01', 'TH0006', 15, 85000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260302_01', 'TH0001', 100, 50000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260302_01', 'TH0007', 20, 120000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260302_01', 'TH0008', 30, 500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260311_01', 'TH0006', 20, 85000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260311_01', 'TH0004', 200, 25000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260321_01', 'TH0001', 50, 50000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260321_01', 'TH0005', 30, 45000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260321_01', 'TH0002', 20, 150000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260328_01', 'TH0007', 20, 120000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260328_01', 'TH0008', 20, 500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260405_01', 'TH0001', 100, 50000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260405_01', 'TH0004', 100, 25000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260405_01', 'TH0008', 12, 500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260410_01', 'TH0005', 100, 45000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('DH_20260418_01', 'TH0006', 5, 85000.00, '2026-04-18 00:48:05', '2026-04-18 00:48:05'),
('DH_20260502_01', 'TH0001', 2, 50000.00, '2026-05-02 03:24:34', '2026-05-02 03:24:34'),
('DH_20260502_01', 'TH0002', 1, 150000.00, '2026-05-02 03:24:34', '2026-05-02 03:24:34'),
('DH_20260505_01', 'TH0001', 110, 50000.00, '2026-05-05 01:02:00', '2026-05-05 01:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_nhap`
--

DROP TABLE IF EXISTS `chi_tiet_phieu_nhap`;
CREATE TABLE IF NOT EXISTS `chi_tiet_phieu_nhap` (
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo_sx` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_san_xuat` date NOT NULL,
  `so_dang_ky` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `han_su_dung` date NOT NULL,
  `so_luong_nhap` int NOT NULL,
  `so_luong_thuc_te` int NOT NULL DEFAULT '0',
  `don_gia_nhap` decimal(15,2) NOT NULL,
  `thanh_tien` decimal(15,2) NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_nhap`,`ma_thuoc`,`so_lo`),
  KEY `chi_tiet_phieu_nhap_ma_thuoc_foreign` (`ma_thuoc`)
) ;

--
-- Dumping data for table `chi_tiet_phieu_nhap`
--

INSERT INTO `chi_tiet_phieu_nhap` (`ma_phieu_nhap`, `ma_thuoc`, `so_lo`, `so_lo_sx`, `ngay_san_xuat`, `so_dang_ky`, `han_su_dung`, `so_luong_nhap`, `so_luong_thuc_te`, `don_gia_nhap`, `thanh_tien`, `image`, `created_at`, `updated_at`) VALUES
('PN_20260105_0001', 'TH0001', 'SL_20260105_0001', 'LSX_20250601', '2025-06-01', 'VD-12345-18', '2028-06-01', 500, 500, 18000.00, 9000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260105_0001', 'TH0004', 'SL_20260105_0002', 'LSX_20250515', '2025-05-15', 'VD-11223-18', '2028-05-15', 400, 400, 15000.00, 6000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260110_0001', 'TH0002', 'SL_20260110_0001', 'LSX_20250701', '2025-07-01', 'VD-22334-19', '2028-07-01', 150, 150, 100000.00, 15000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260110_0001', 'TH0005', 'SL_20260110_0002', 'LSX_20250801', '2025-08-01', 'VD-33445-19', '2028-08-01', 300, 300, 25000.00, 7500000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260120_0001', 'TH0006', 'SL_20260120_0001', 'LSX_20250901', '2025-09-01', 'VD-44556-20', '2028-09-01', 100, 100, 50000.00, 5000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260120_0001', 'TH0007', 'SL_20260120_0002', 'LSX_20250401', '2025-04-01', 'VD-55667-19', '2028-04-01', 50, 50, 70000.00, 3500000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260201_0001', 'TH0008', 'SL_20260201_0001', 'LSX_20251001', '2025-10-01', 'VD-66778-20', '2028-10-01', 200, 200, 40000.00, 8000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260201_0001', 'TH0002', 'SL_20260201_0002', 'LSX_20251101', '2025-11-01', 'VD-22334-19', '2028-11-01', 100, 100, 100000.00, 10000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260210_0001', 'TH0001', 'SL_20260210_0001', 'LSX_20251201', '2025-12-01', 'VD-12345-18', '2028-12-01', 300, 300, 18000.00, 5400000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260210_0001', 'TH0004', 'SL_20260210_0002', 'LSX_20251115', '2025-11-15', 'VD-11223-18', '2028-11-15', 440, 440, 15000.00, 6600000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260220_0001', 'TH0005', 'SL_20260220_0001', 'LSX_20260101', '2026-01-01', 'VD-33445-19', '2029-01-01', 200, 200, 25000.00, 5000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260220_0001', 'TH0006', 'SL_20260220_0002', 'LSX_20251215', '2025-12-15', 'VD-44556-20', '2028-12-15', 80, 80, 50000.00, 4000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260220_0001', 'TH0007', 'SL_20260220_0003', 'LSX_20260110', '2026-01-10', 'VD-55667-19', '2029-01-10', 10, 10, 60000.00, 600000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260301_0001', 'TH0001', 'SL_20260301_0001', 'LSX_20260115', '2026-01-15', 'VD-12345-18', '2029-01-15', 600, 600, 18000.00, 10800000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260301_0001', 'TH0008', 'SL_20260301_0002', 'LSX_20260201', '2026-02-01', 'VD-66778-20', '2029-02-01', 230, 230, 40000.00, 9200000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260310_0001', 'TH0005', 'SL_20260310_0001', 'LSX_20260215', '2026-02-15', 'VD-33445-19', '2029-02-15', 270, 270, 25000.00, 6750000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260315_0001', 'TH0007', 'SL_20260315_0001', 'LSX_20260301', '2026-03-01', 'VD-55667-19', '2029-03-01', 80, 80, 70000.00, 5600000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260315_0001', 'TH0004', 'SL_20260315_0002', 'LSX_20260220', '2026-02-20', 'VD-11223-18', '2029-02-20', 560, 560, 15000.00, 8400000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260325_0001', 'TH0006', 'SL_20260325_0001', 'LSX_20260310', '2026-03-10', 'VD-44556-20', '2029-03-10', 120, 120, 50000.00, 6000000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260325_0001', 'TH0002', 'SL_20260325_0002', 'LSX_20260305', '2026-03-05', 'VD-22334-19', '2029-03-05', 42, 42, 100000.00, 4200000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260401_0001', 'TH0001', 'SL_20260401_0001', 'LSX_20260320', '2026-03-20', 'VD-12345-18', '2029-03-20', 400, 400, 18000.00, 7200000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260401_0001', 'TH0008', 'SL_20260401_0002', 'LSX_20260325', '2026-03-25', 'VD-66778-20', '2029-03-25', 200, 200, 46500.00, 9300000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PN_20260410_0001', 'TH0005', 'SL_20260410_0001', 'LSX_20260401', '2026-04-01', 'VD-33445-19', '2029-04-01', 180, 180, 25000.00, 4500000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 09:23:20'),
('PN_20260410_0001', 'TH0006', 'SL_20260410_0002', 'LSX_20260405', '2026-04-05', 'VD-44556-20', '2029-04-05', 90, 90, 30000.00, 2700000.00, NULL, '2026-04-17 08:23:17', '2026-04-17 09:23:20'),
('PN_20260417_0001', 'TH0006', 'SL_20260417_0001', 'LSX_20241230', '2024-12-30', 'SĐK_159', '2026-05-30', 10, 10, 800000.00, 8000000.00, NULL, '2026-04-17 09:47:15', '2026-04-17 09:57:13'),
('PN_20260418_0001', 'TH0001', 'SL_20260418_0001', 'LSX_20241230', '2024-12-30', 'SDK_123', '2026-12-15', 10, 10, 500000.00, 5000000.00, NULL, '2026-04-18 00:58:52', '2026-04-18 10:28:03'),
('PN_20260418_0002', 'TH0001', 'SL_20260418_0002', 'LSX_20251215', '2025-12-15', 'SĐK_258', '2026-12-12', 10, 10, 1000000.00, 10000000.00, 'uploads/batches/1776533980_lot_TH0001_SL_20260418_0002.jpg', '2026-04-18 10:38:59', '2026-04-30 02:53:57'),
('PN_20260430_0001', 'TH0007', 'SL_20260430_0001', 'LSX_20251215', '2025-12-15', 'SĐK_258', '2026-06-30', 10, 4, 500000.00, 5000000.00, 'uploads/batches/1777542781_lot_TH0007_SL_20260430_0001.webp', '2026-04-30 02:51:41', '2026-04-30 02:53:01'),
('PN_TRA_20260505_0001', 'TH0005', 'SL_20260220_0001', 'LSX_20260101', '2026-01-01', 'VD-33445-19', '2029-01-01', 10, 10, 45000.00, 450000.00, NULL, '2026-05-05 06:34:48', '2026-05-05 06:35:13'),
('PN_20260505_0001', 'TH0001', 'SL_20260505_0001', 'LSX_20240101', '2024-01-01', 'SDK123', '2028-01-01', 100, 0, 5000.00, 500000.00, NULL, '2026-05-05 00:53:29', '2026-05-05 00:53:29'),
('PN_TRA_20260502_0003', 'TH0006', 'SL_20260120_0001', 'LSX_20250901', '2025-09-01', 'VD-44556-20', '2028-09-01', 3, 3, 85000.00, 255000.00, NULL, '2026-05-02 02:00:44', '2026-05-02 02:02:25'),
('PN_20260505_0002', 'TH0009', 'SL_20260505_0002', 'LSX_20251211', '2025-12-11', 'SĐK_258', '2026-05-28', 8, 8, 1000000.00, 50000000.00, 'uploads/batches/1777989124_lot_TH0009_SL_20260505_0002.jpg', '2026-05-05 06:49:59', '2026-05-05 06:52:26');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_tra_ncc`
--

DROP TABLE IF EXISTS `chi_tiet_phieu_tra_ncc`;
CREATE TABLE IF NOT EXISTS `chi_tiet_phieu_tra_ncc` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ma_phieu_tra_ncc` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_phieu_nhap` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong_tra` int NOT NULL,
  `don_gia` decimal(15,2) NOT NULL,
  `thanh_tien` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chi_tiet_phieu_tra_ncc_ma_phieu_tra_ncc_foreign` (`ma_phieu_tra_ncc`),
  KEY `chi_tiet_phieu_tra_ncc_ma_thuoc_foreign` (`ma_thuoc`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_tiet_phieu_tra_ncc`
--

INSERT INTO `chi_tiet_phieu_tra_ncc` (`id`, `ma_phieu_tra_ncc`, `ma_thuoc`, `ma_phieu_nhap`, `so_lo`, `so_luong_tra`, `don_gia`, `thanh_tien`, `created_at`, `updated_at`) VALUES
(2, 'PTNCC_20260505_0003', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 3, 800000.00, 2400000.00, '2026-05-05 06:11:43', '2026-05-05 06:11:43'),
(3, 'PTNCC_20260505_0002', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 2, 1000000.00, 2000000.00, '2026-05-05 06:31:15', '2026-05-05 06:31:15'),
(4, 'PTNCC_20260505_0001', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 1, 1000000.00, 1000000.00, '2026-05-05 06:56:56', '2026-05-05 06:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_xuat`
--

DROP TABLE IF EXISTS `chi_tiet_phieu_xuat`;
CREATE TABLE IF NOT EXISTS `chi_tiet_phieu_xuat` (
  `ma_phieu_xuat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `han_su_dung` date NOT NULL,
  `so_luong_xuat` int NOT NULL,
  `don_gia_ban` decimal(15,2) NOT NULL,
  `thanh_tien` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_xuat`,`ma_thuoc`,`so_lo`),
  KEY `chi_tiet_phieu_xuat_ma_thuoc_foreign` (`ma_thuoc`)
) ;

--
-- Dumping data for table `chi_tiet_phieu_xuat`
--

INSERT INTO `chi_tiet_phieu_xuat` (`ma_phieu_xuat`, `ma_thuoc`, `so_lo`, `han_su_dung`, `so_luong_xuat`, `don_gia_ban`, `thanh_tien`, `created_at`, `updated_at`) VALUES
('PX_DH_20260115_01_0001', 'TH0001', 'SL_20260105_0001', '2028-06-01', 100, 50000.00, 5000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260115_01_0001', 'TH0002', 'SL_20260110_0001', '2028-07-01', 30, 150000.00, 4500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260115_01_0001', 'TH0004', 'SL_20260105_0002', '2028-05-15', 60, 25000.00, 1500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260125_01_0001', 'TH0005', 'SL_20260110_0002', '2028-08-01', 80, 45000.00, 3600000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260125_01_0001', 'TH0006', 'SL_20260120_0001', '2028-09-01', 40, 85000.00, 3400000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260125_01_0001', 'TH0007', 'SL_20260120_0002', '2028-04-01', 20, 120000.00, 2400000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260205_01_0001', 'TH0008', 'SL_20260201_0001', '2028-10-01', 50, 500000.00, 25000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260205_01_0001', 'TH0004', 'SL_20260105_0002', '2028-05-15', 40, 25000.00, 1000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260205_01_0001', 'TH0001', 'SL_20260210_0001', '2028-12-01', 30, 50000.00, 1500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260215_01_0001', 'TH0002', 'SL_20260110_0001', '2028-07-01', 20, 150000.00, 3000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260215_01_0001', 'TH0005', 'SL_20260220_0001', '2029-01-01', 30, 45000.00, 1350000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260215_01_0001', 'TH0006', 'SL_20260220_0002', '2028-12-15', 15, 85000.00, 1275000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260302_01_0001', 'TH0001', 'SL_20260210_0001', '2028-12-01', 20, 50000.00, 1000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260302_01_0001', 'TH0001', 'SL_20260301_0001', '2029-01-15', 80, 50000.00, 4000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260302_01_0001', 'TH0007', 'SL_20260315_0001', '2029-03-01', 20, 120000.00, 2400000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260302_01_0001', 'TH0008', 'SL_20260301_0002', '2029-02-01', 30, 500000.00, 15000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260311_01_0001', 'TH0006', 'SL_20260120_0001', '2028-09-01', 20, 85000.00, 1700000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260311_01_0001', 'TH0004', 'SL_20260210_0002', '2028-11-15', 60, 25000.00, 1500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260321_01_0001', 'TH0001', 'SL_20260301_0001', '2029-01-15', 50, 50000.00, 2500000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260321_01_0001', 'TH0005', 'SL_20260110_0002', '2028-08-01', 30, 45000.00, 1350000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260321_01_0001', 'TH0002', 'SL_20260201_0002', '2028-11-01', 20, 150000.00, 3000000.00, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('PX_DH_20260418_01_0001', 'TH0006', 'SL_20260120_0001', '2028-09-01', 5, 85000.00, 425000.00, '2026-04-18 01:03:16', '2026-04-18 01:03:16'),
('PX_DH_20260505_01_0001', 'TH0001', 'SL_20260105_0001', '2028-06-01', 110, 50000.00, 5500000.00, '2026-05-05 01:31:53', '2026-05-05 01:31:53');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_tra_hang`
--

DROP TABLE IF EXISTS `chi_tiet_tra_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_tra_hang` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ma_tra_hang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong_tra` int NOT NULL,
  `don_gia_tra` decimal(15,2) NOT NULL DEFAULT '0.00',
  `thanh_tien` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ly_do_chi_tiet` text COLLATE utf8mb4_unicode_ci,
  `image_minh_chung` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chi_tiet_tra_hang_ma_tra_hang_foreign` (`ma_tra_hang`),
  KEY `chi_tiet_tra_hang_ma_thuoc_foreign` (`ma_thuoc`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_tiet_tra_hang`
--

INSERT INTO `chi_tiet_tra_hang` (`id`, `ma_tra_hang`, `ma_thuoc`, `so_luong_tra`, `don_gia_tra`, `thanh_tien`, `ly_do_chi_tiet`, `image_minh_chung`, `created_at`, `updated_at`) VALUES
(3, 'TH_20260310_100000', 'TH0005', 10, 45000.00, 450000.00, 'Đặt thừa số lượng', NULL, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(4, 'TH_20260418_080953', 'TH0006', 3, 85000.00, 255000.00, 'hàng lỗi', NULL, '2026-04-18 01:09:53', '2026-04-18 01:09:53'),
(5, 'TH_20260505_085135', 'TH0001', 10, 50000.00, 500000.00, 'lỗi bao bì', NULL, '2026-05-05 01:51:35', '2026-05-05 01:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE IF NOT EXISTS `don_hang` (
  `ma_don_hang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_kh` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_dat` date NOT NULL,
  `trang_thai_dh` enum('cho_xu_ly','da_duyet','dang_xuat_kho','da_hoan_thanh','da_huy','dang_van_chuyen') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_xu_ly',
  `nguoi_duyet` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nguoi_huy` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ly_do_huy` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tong_tien` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_don_hang`),
  KEY `don_hang_ma_kh_foreign` (`ma_kh`),
  KEY `don_hang_nguoi_duyet_foreign` (`nguoi_duyet`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_kh`, `ngay_dat`, `trang_thai_dh`, `nguoi_duyet`, `nguoi_huy`, `ly_do_huy`, `tong_tien`, `created_at`, `updated_at`) VALUES
('DH_20260115_01', 'KH01', '2026-01-15', 'da_hoan_thanh', 'ND004', NULL, NULL, 11000000.00, '2026-01-15 02:00:00', '2026-04-18 22:31:40'),
('DH_20260125_01', 'KH02', '2026-01-25', 'da_hoan_thanh', 'ND001', NULL, NULL, 9000000.00, '2026-01-25 02:00:00', '2026-04-18 22:31:40'),
('DH_20260205_01', 'KH01', '2026-02-05', 'da_hoan_thanh', 'ND004', NULL, NULL, 27500000.00, '2026-02-05 02:00:00', '2026-04-18 22:31:40'),
('DH_20260215_01', 'KH003', '2026-02-15', 'da_hoan_thanh', 'ND001', NULL, NULL, 5625000.00, '2026-02-15 02:00:00', '2026-04-18 22:31:40'),
('DH_20260302_01', 'KH02', '2026-03-02', 'da_hoan_thanh', 'ND001', NULL, NULL, 22400000.00, '2026-03-02 02:00:00', '2026-04-18 22:31:40'),
('DH_20260311_01', 'KH01', '2026-03-11', 'da_hoan_thanh', 'ND004', NULL, NULL, 6700000.00, '2026-03-11 02:00:00', '2026-04-18 22:31:40'),
('DH_20260321_01', 'KH003', '2026-03-21', 'da_hoan_thanh', 'ND004', NULL, NULL, 6850000.00, '2026-03-21 02:00:00', '2026-04-18 22:31:40'),
('DH_20260328_01', 'KH02', '2026-03-28', 'da_duyet', 'ND001', NULL, NULL, 5600000.00, '2026-03-28 02:00:00', '2026-04-18 22:31:40'),
('DH_20260405_01', 'KH01', '2026-04-05', 'da_duyet', 'ND004', NULL, NULL, 13500000.00, '2026-04-05 02:00:00', '2026-04-18 22:31:40'),
('DH_20260410_01', 'KH003', '2026-04-10', 'cho_xu_ly', NULL, NULL, NULL, 4500000.00, '2026-04-10 02:00:00', '2026-04-10 02:00:00'),
('DH_20260418_01', 'KH003', '2026-04-18', 'da_hoan_thanh', 'ND001', NULL, NULL, 425000.00, '2026-04-18 00:48:05', '2026-04-18 22:31:40'),
('DH_20260502_01', 'KH003', '2026-05-02', 'da_huy', NULL, 'ND001', NULL, 250000.00, '2026-05-02 03:24:34', '2026-05-02 03:24:56'),
('DH_20260505_01', 'KH01', '2026-05-05', 'da_hoan_thanh', 'ND001', NULL, NULL, 5500000.00, '2026-05-05 01:02:00', '2026-05-05 01:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `don_vi_tinh`
--

DROP TABLE IF EXISTS `don_vi_tinh`;
CREATE TABLE IF NOT EXISTS `don_vi_tinh` (
  `ma_dvt` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_dvt` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ghi_chu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ma_dvt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `don_vi_tinh`
--

INSERT INTO `don_vi_tinh` (`ma_dvt`, `ten_dvt`, `created_at`, `updated_at`, `ghi_chu`) VALUES
('DVT01', 'Hộp', '2026-03-12 15:33:48', '2026-03-27 00:22:24', NULL),
('DVT02', 'Vỉ', '2026-03-12 15:33:48', '2026-03-12 15:33:48', ''),
('DVT03', 'Lọ', '2026-03-12 15:33:48', '2026-03-12 15:33:48', ''),
('DVT04', 'Gói', '2026-03-27 00:32:14', '2026-03-27 00:32:14', NULL),
('DVT05', 'bịt', '2026-04-08 02:40:19', '2026-04-08 09:04:02', NULL),
('DVT06', 'Test', '2026-04-14 07:52:29', '2026-05-05 06:47:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feature_toggles`
--

DROP TABLE IF EXISTS `feature_toggles`;
CREATE TABLE IF NOT EXISTS `feature_toggles` (
  `ma_chuc_nang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_chuc_nang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_chuc_nang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feature_toggles`
--

INSERT INTO `feature_toggles` (`ma_chuc_nang`, `ten_chuc_nang`, `mo_ta`, `trang_thai`, `created_at`, `updated_at`) VALUES
('imports', 'Quản lý Nhập kho', 'Sử dụng để bật hoặc tắt tính năng Quản lý Nhập kho', 1, '2026-04-14 07:45:37', '2026-05-06 08:23:25'),
('sales', 'Quản lý Xuất kho', 'Sử dụng để bật hoặc tắt tính năng Quản lý Xuất kho', 1, '2026-04-14 07:45:37', '2026-04-14 07:49:23'),
('transfers', 'Điều chuyển kho', 'Sử dụng để bật hoặc tắt tính năng Điều chuyển kho', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('batches', 'Tồn kho & Lô hàng', 'Sử dụng để bật hoặc tắt tính năng Tồn kho & Lô hàng', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('orders', 'Đơn đặt hàng', 'Sử dụng để bật hoặc tắt tính năng Đơn đặt hàng', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('returns', 'Khách trả hàng', 'Sử dụng để bật hoặc tắt tính năng Khách trả hàng', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('products', 'Danh mục Thuốc', 'Sử dụng để bật hoặc tắt tính năng Danh mục Thuốc', 1, '2026-04-14 07:45:37', '2026-04-14 08:33:45'),
('suppliers', 'Nhà cung cấp', 'Sử dụng để bật hoặc tắt tính năng Nhà cung cấp', 1, '2026-04-14 07:45:37', '2026-04-14 08:33:45'),
('customers', 'Khách hàng', 'Sử dụng để bật hoặc tắt tính năng Khách hàng', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('payments', 'Thanh toán & Công nợ', 'Sử dụng để bật hoặc tắt tính năng Thanh toán & Công nợ', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('reports', 'Báo cáo thống kê', 'Sử dụng để bật hoặc tắt tính năng Báo cáo thống kê', 1, '2026-04-14 07:45:37', '2026-04-14 07:45:37'),
('supplier_returns', 'Trả hàng nhà cung cấp', 'Sử dụng để bật hoặc tắt tính năng Trả hàng nhà cung cấp', 1, '2026-05-06 07:29:54', '2026-05-06 07:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `khach_hang`
--

DROP TABLE IF EXISTS `khach_hang`;
CREATE TABLE IF NOT EXISTS `khach_hang` (
  `ma_kh` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_dang_nhap` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_kh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_kh` enum('nha_thuoc','dai_ly','phong_kham','benh_vien') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_so_thue` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `giay_phep_hd_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ảnh giấy phép hoạt động',
  `hinh_dai_dien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình đại diện',
  `trang_thai_tk` enum('cho_duyet','hoat_dong','vo_hieu_hoa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_duyet',
  `dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_kh`),
  UNIQUE KEY `khach_hang_ten_dang_nhap_unique` (`ten_dang_nhap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khach_hang`
--

INSERT INTO `khach_hang` (`ma_kh`, `ten_dang_nhap`, `mat_khau`, `ten_kh`, `loai_kh`, `dia_chi`, `ma_so_thue`, `giay_phep_hd_image`, `hinh_dai_dien`, `trang_thai_tk`, `dien_thoai`, `email`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('KH01', 'nhathuoc_honghoa', '$2y$10$5Aw3SXPdEd7yNM/SzPlkb.kfnghzpJvF6Ahfjfh4jKYliRNOFWopS', 'Nhà thuốc Hồng Hoa', 'nha_thuoc', '12 Nguyễn Trãi, Q.5, TP.HCM', '0311223344', 'giay_phep_honghoa.pdf', 'avatar_honghoa.jpg', 'hoat_dong', '0918111222', 'honghoa@gmail.com', 'Khách VIP', '2026-03-12 15:33:48', '2026-03-30 08:18:29'),
('KH02', 'phongkham_anbinh', '$2y$10$5Aw3SXPdEd7yNM/SzPlkb.kfnghzpJvF6Ahfjfh4jKYliRNOFWopS', 'Phòng khám Đa khoa An Bình', 'phong_kham', '45 Lê Lợi, Q.1, TP.HCM', '0322334455', 'giay_phep_anbinh.jpg', 'default_avatar.png', 'hoat_dong', '0988333444', 'anbinh@phongkham.vn', 'Hồ sơ đang chờ duyệt', '2026-03-12 15:33:48', '2026-04-17 08:03:50'),
('KH003', 'nhathuoc_1', '$2y$10$5Aw3SXPdEd7yNM/SzPlkb.kfnghzpJvF6Ahfjfh4jKYliRNOFWopS', 'nhathuoc_1', 'nha_thuoc', 'agg', '12334564', 'uploads/customers/1773390593_giayphep_KH003.png', 'uploads/customers/1773390593_avatar_KH003.png', 'hoat_dong', '034543543', 'fghfhfghfg@gmail.com', NULL, '2026-03-13 01:29:53', '2026-04-09 08:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `khach_tra_hang`
--

DROP TABLE IF EXISTS `khach_tra_hang`;
CREATE TABLE IF NOT EXISTS `khach_tra_hang` (
  `ma_tra_hang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_don_hang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_kh` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_yeu_cau` date NOT NULL,
  `ly_do_chung` text COLLATE utf8mb4_unicode_ci,
  `tong_tien_hoan_tra` decimal(15,2) NOT NULL DEFAULT '0.00',
  `trang_thai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_duyet',
  `trang_thai_hoan_tien` enum('chua_hoan','mot_phan','da_hoan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chua_hoan',
  `nguoi_duyet` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nguoi_tao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_duyet` date DEFAULT NULL,
  `ghi_chu_admin` text COLLATE utf8mb4_unicode_ci,
  `minh_chung_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_tra_hang`),
  KEY `khach_tra_hang_ma_don_hang_foreign` (`ma_don_hang`),
  KEY `khach_tra_hang_ma_kh_foreign` (`ma_kh`),
  KEY `khach_tra_hang_nguoi_tao_foreign` (`nguoi_tao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khach_tra_hang`
--

INSERT INTO `khach_tra_hang` (`ma_tra_hang`, `ma_don_hang`, `ma_kh`, `ngay_yeu_cau`, `ly_do_chung`, `tong_tien_hoan_tra`, `trang_thai`, `trang_thai_hoan_tien`, `nguoi_duyet`, `nguoi_tao`, `ngay_duyet`, `ghi_chu_admin`, `minh_chung_image`, `created_at`, `updated_at`) VALUES
('TH_20260310_100000', 'DH_20260215_01', 'KH003', '2026-03-10', 'Đặt nhầm số lượng Amoxicillin', 450000.00, 'da_duyet_nhap_kho', 'mot_phan', 'ND001', 'ND001', '2026-05-05', 'sẽ trả', NULL, '2026-03-10 03:00:00', '2026-05-05 06:36:48'),
('TH_20260418_080953', 'DH_20260418_01', 'KH003', '2026-04-18', 'kkk', 255000.00, 'da_duyet_nhap_kho', 'da_hoan', 'ND001', 'ND004', '2026-05-02', 'Chuyển khoản hoàn trả khách.', 'uploads/returns/1776499793_minhchung.jpg', '2026-04-18 01:09:53', '2026-05-02 03:31:50'),
('TH_20260505_085135', 'DH_20260505_01', 'KH01', '2026-05-05', 'hàng bong tróc', 500000.00, 'cho_duyet', 'chua_hoan', NULL, 'ND001', NULL, NULL, 'uploads/returns/1777971095_minhchung.jpg', '2026-05-05 01:51:35', '2026-05-05 01:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `khu_vuc_kho`
--

DROP TABLE IF EXISTS `khu_vuc_kho`;
CREATE TABLE IF NOT EXISTS `khu_vuc_kho` (
  `ma_khu_vuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_khu_vuc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_khu_vuc` enum('tiep_nhan','biet_tru','san_sang','tra_ve','loai_bo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_khu_vuc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khu_vuc_kho`
--

INSERT INTO `khu_vuc_kho` (`ma_khu_vuc`, `ten_khu_vuc`, `loai_khu_vuc`, `mo_ta`, `trang_thai`, `created_at`, `updated_at`) VALUES
('KV01_TIEP_NHAN', 'Kho Tiếp nhận (Receiving Area)', 'tiep_nhan', 'Nơi kiểm tra khi hàng mới về trước khi chuyển sang biệt trữ', 1, '2026-04-01 08:42:34', '2026-04-01 08:42:34'),
('KV02_BIET_TRU', 'Kho Biệt trữ (Quarantine Area)', 'biet_tru', 'Nơi lưu trữ chờ kết quả kiểm soát chất lượng (QC)', 1, '2026-04-01 08:42:34', '2026-04-01 08:42:34'),
('KV03_THANH_PHAM', 'Kho Thành phẩm (Ready for Sale)', 'san_sang', 'Khu vực chứa hàng đã sẵn sàng để phân phối xuất bán', 1, '2026-04-01 08:42:34', '2026-04-01 08:42:34'),
('KV04_CHO_XU_LY', 'Kho Chờ xử lý / Trả về (Return Area)', 'tra_ve', 'Lưu trữ hàng thu hồi, hàng lỗi chờ quyết định xử lý', 1, '2026-04-01 08:42:34', '2026-04-01 08:42:34'),
('KV05_LOAI_BO', 'Kho Loại bỏ (Disposal Area)', 'loai_bo', 'Chứa hàng không còn giá trị chờ xử lý tiêu hủy', 1, '2026-04-01 08:42:34', '2026-04-01 08:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_dich_chuyen_kho`
--

DROP TABLE IF EXISTS `lich_su_dich_chuyen_kho`;
CREATE TABLE IF NOT EXISTS `lich_su_dich_chuyen_kho` (
  `ma_phieu_chuyen` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tu_khu_vuc` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `den_khu_vuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong_chuyen` int NOT NULL,
  `nguoi_thuc_hien` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_chuyen` datetime NOT NULL,
  `ly_do_chuyen` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_chuyen`),
  KEY `lich_su_dich_chuyen_kho_ma_thuoc_ma_phieu_nhap_so_lo_foreign` (`ma_thuoc`,`ma_phieu_nhap`,`so_lo`),
  KEY `lich_su_dich_chuyen_kho_tu_khu_vuc_foreign` (`tu_khu_vuc`),
  KEY `lich_su_dich_chuyen_kho_den_khu_vuc_foreign` (`den_khu_vuc`),
  KEY `lich_su_dich_chuyen_kho_nguoi_thuc_hien_foreign` (`nguoi_thuc_hien`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_dich_chuyen_kho`
--

INSERT INTO `lich_su_dich_chuyen_kho` (`ma_phieu_chuyen`, `ma_thuoc`, `ma_phieu_nhap`, `so_lo`, `tu_khu_vuc`, `den_khu_vuc`, `so_luong_chuyen`, `nguoi_thuc_hien`, `ngay_chuyen`, `ly_do_chuyen`, `created_at`, `updated_at`) VALUES
('CHUP-20260105140000-A1B2', 'TH0001', 'PN_20260105_0001', 'SL_20260105_0001', 'KV01_TIEP_NHAN', 'KV03_THANH_PHAM', 500, 'ND003', '2026-01-05 14:00:00', 'QC pass - chuyển kho thành phẩm', '2026-01-05 07:00:00', '2026-01-05 07:00:00'),
('CHUP-20260110140000-C3D4', 'TH0002', 'PN_20260110_0001', 'SL_20260110_0001', 'KV01_TIEP_NHAN', 'KV02_BIET_TRU', 150, 'ND003', '2026-01-10 14:00:00', 'Kiểm định chất lượng kháng sinh', '2026-01-10 07:00:00', '2026-01-10 07:00:00'),
('CHUP-20260112090000-E5F6', 'TH0002', 'PN_20260110_0001', 'SL_20260110_0001', 'KV02_BIET_TRU', 'KV03_THANH_PHAM', 150, 'ND002', '2026-01-12 09:00:00', 'QC pass - chuyển thành phẩm', '2026-01-12 02:00:00', '2026-01-12 02:00:00'),
('CHUP-20260120140000-G7H8', 'TH0006', 'PN_20260120_0001', 'SL_20260120_0001', 'KV01_TIEP_NHAN', 'KV03_THANH_PHAM', 100, 'ND003', '2026-01-20 14:00:00', 'QC pass - nhập kho thành phẩm', '2026-01-20 07:00:00', '2026-01-20 07:00:00'),
('CHUP-20260202090000-I9J0', 'TH0008', 'PN_20260201_0001', 'SL_20260201_0001', 'KV01_TIEP_NHAN', 'KV03_THANH_PHAM', 200, 'ND002', '2026-02-02 09:00:00', 'Đạt kiểm định - chuyển bán', '2026-02-02 02:00:00', '2026-02-02 02:00:00'),
('CHUP-20260417160914-ZDTK', 'TH0005', 'PN_20260410_0001', 'SL_20260410_0001', NULL, 'KV01_TIEP_NHAN', 50, 'ND001', '2026-04-17 16:09:14', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:09:14', '2026-04-17 09:55:35'),
('CHUP-20260417160914-GPBX', 'TH0006', 'PN_20260410_0001', 'SL_20260410_0002', NULL, 'KV01_TIEP_NHAN', 20, 'ND001', '2026-04-17 16:09:14', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:09:14', '2026-04-17 09:55:35'),
('CHUP-20260417161058-RKDQ', 'TH0005', 'PN_20260410_0001', 'SL_20260410_0001', NULL, 'KV01_TIEP_NHAN', 50, 'ND001', '2026-04-17 16:10:58', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:10:58', '2026-04-17 09:55:35'),
('CHUP-20260417161058-QWUH', 'TH0006', 'PN_20260410_0001', 'SL_20260410_0002', NULL, 'KV01_TIEP_NHAN', 20, 'ND001', '2026-04-17 16:10:58', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:10:58', '2026-04-17 09:55:35'),
('CHUP-20260417162239-7PFZ', 'TH0005', 'PN_20260410_0001', 'SL_20260410_0001', NULL, 'KV01_TIEP_NHAN', 130, 'ND001', '2026-04-17 16:22:39', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:22:39', '2026-04-17 09:55:35'),
('CHUP-20260417162239-SW82', 'TH0006', 'PN_20260410_0001', 'SL_20260410_0002', NULL, 'KV01_TIEP_NHAN', 60, 'ND001', '2026-04-17 16:22:39', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:22:39', '2026-04-17 09:55:35'),
('CHUP-20260417162320-IAU7', 'TH0006', 'PN_20260410_0001', 'SL_20260410_0002', NULL, 'KV01_TIEP_NHAN', 10, 'ND001', '2026-04-17 16:23:20', 'Tự động nhập vào kho Tiếp Nhận sau khi xác nhận kiểm đếm', '2026-04-17 09:23:20', '2026-04-17 09:55:35'),
('CHUP-20260417164741-NBCR', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', NULL, 'KV01_TIEP_NHAN', 3, 'ND001', '2026-04-17 16:47:41', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 3)', '2026-04-17 09:47:41', '2026-04-17 09:47:41'),
('CHUP-20260417164957-XLGU', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', NULL, 'KV01_TIEP_NHAN', 2, 'ND001', '2026-04-17 16:49:57', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 2)', '2026-04-17 09:49:57', '2026-04-17 09:49:57'),
('CHUP-20260417165713-YYQZ', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', NULL, 'KV01_TIEP_NHAN', 5, 'ND001', '2026-04-17 16:57:13', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 5)', '2026-04-17 09:57:13', '2026-04-17 09:57:13'),
('CHCK-20260418080715-FKZZ', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV01_TIEP_NHAN', 'KV03_THANH_PHAM', 5, 'ND003', '2026-04-18 08:07:15', 'Nhân viên thực hiện luân chuyển', '2026-04-18 01:07:15', '2026-04-18 01:07:15'),
('CHUP-20260418172751-JTPX', 'TH0001', 'PN_20260418_0001', 'SL_20260418_0001', NULL, 'KV01_TIEP_NHAN', 5, 'ND001', '2026-04-18 17:27:51', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 5)', '2026-04-18 10:27:51', '2026-04-18 10:27:51'),
('CHUP-20260418172803-R386', 'TH0001', 'PN_20260418_0001', 'SL_20260418_0001', NULL, 'KV01_TIEP_NHAN', 5, 'ND001', '2026-04-18 17:28:03', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 5)', '2026-04-18 10:28:03', '2026-04-18 10:28:03'),
('CHUP-20260418173940-PDPI', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', NULL, 'KV01_TIEP_NHAN', 5, 'ND001', '2026-04-18 17:39:40', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 5)', '2026-04-18 10:39:40', '2026-04-18 10:39:40'),
('CHUP-20260430095301-DSQ8', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', NULL, 'KV01_TIEP_NHAN', 4, 'ND001', '2026-04-30 09:53:01', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 4)', '2026-04-30 02:53:01', '2026-04-30 02:53:01'),
('CHUP-20260430095357-N4VJ', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', NULL, 'KV01_TIEP_NHAN', 5, 'ND001', '2026-04-30 09:53:57', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 5)', '2026-04-30 02:53:57', '2026-04-30 02:53:57'),
('CHUP-20260502090225-0QVL', 'TH0006', 'PN_TRA_20260502_0003', 'SL_20260120_0001', NULL, 'KV04_CHO_XU_LY', 3, 'ND001', '2026-05-02 09:02:25', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 3)', '2026-05-02 02:02:25', '2026-05-02 02:02:25'),
('CHCK-20260505081122-6MY2', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV01_TIEP_NHAN', 'KV03_THANH_PHAM', 2, 'ND001', '2026-05-05 08:11:22', 'Test luan chuyen tu tiep nhan sang thanh pham', '2026-05-05 01:11:22', '2026-05-05 01:11:22'),
('CHCK-20260505081345-CALE', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV03_THANH_PHAM', 'KV02_BIET_TRU', 2, 'ND001', '2026-05-05 08:13:45', 'Final test: KV03 to KV04', '2026-05-05 01:13:45', '2026-05-05 01:13:45'),
('CHCK-20260505090021-NH2X', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV04_CHO_XU_LY', 'KV05_LOAI_BO', 1, 'ND001', '2026-05-05 09:00:21', 'ko thể sử dụng', '2026-05-05 02:00:21', '2026-05-05 02:00:21'),
('CHCK-20260505125237-KZGA', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV01_TIEP_NHAN', 'KV04_CHO_XU_LY', 2, 'ND001', '2026-05-05 12:52:37', 'kiểm tra', '2026-05-05 05:52:37', '2026-05-05 05:52:37'),
('CHCK-20260505125942-KQFO', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV04_CHO_XU_LY', 'XUAT_TRA_NCC', 2, 'ND002', '2026-05-05 12:59:42', 'Trả hàng NCC - Phiếu PTNCC-20260505125454-BQW0', '2026-05-05 05:59:42', '2026-05-05 05:59:42'),
('CHCK-20260505130738-9TE7', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV02_BIET_TRU', 'KV04_CHO_XU_LY', 2, 'ND002', '2026-05-05 13:07:38', 'test', '2026-05-05 06:07:38', '2026-05-05 06:07:38'),
('CHCK-20260505130756-D7GA', 'TH0007', 'PN_20260430_0001', 'SL_20260430_0001', 'KV04_CHO_XU_LY', 'XUAT_TRA_NCC', 2, 'ND002', '2026-05-05 13:07:56', 'Trả hàng NCC - Phiếu PTNCC-20260505125454-BQW0', '2026-05-05 06:07:56', '2026-05-05 06:07:56'),
('CHCK-20260505131131-DY7E', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV01_TIEP_NHAN', 'KV04_CHO_XU_LY', 3, 'ND002', '2026-05-05 13:11:31', 'test', '2026-05-05 06:11:31', '2026-05-05 06:11:31'),
('CHCK-20260505131236-CAJ9', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV04_CHO_XU_LY', 'XUAT_TRA_NCC', 3, 'ND002', '2026-05-05 13:12:36', 'Trả hàng NCC - Phiếu PTNCC-20260505131143-CKUN', '2026-05-05 06:12:36', '2026-05-05 06:12:36'),
('CHCK-20260505131406-NYCZ', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV01_TIEP_NHAN', 'KV04_CHO_XU_LY', 2, 'ND002', '2026-05-05 13:14:06', 'tesst kho 5', '2026-05-05 06:14:06', '2026-05-05 06:14:06'),
('CHCK-20260505131421-XDLK', 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV04_CHO_XU_LY', 'KV05_LOAI_BO', 2, 'ND002', '2026-05-05 13:14:21', 'tesst 05', '2026-05-05 06:14:21', '2026-05-05 06:14:21'),
('CHCK-20260505132912-17ZE', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV01_TIEP_NHAN', 'KV04_CHO_XU_LY', 10, 'ND001', '2026-05-05 13:29:12', 'tesr 3', '2026-05-05 06:29:12', '2026-05-05 06:29:12'),
('CHCK-20260505132933-5SS8', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV04_CHO_XU_LY', 'KV05_LOAI_BO', 5, 'ND001', '2026-05-05 13:29:33', 'test 3', '2026-05-05 06:29:33', '2026-05-05 06:29:33'),
('CHCK-20260505133049-TMVD', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV04_CHO_XU_LY', 'KV03_THANH_PHAM', 3, 'ND001', '2026-05-05 13:30:49', 'đặt yêu cầu', '2026-05-05 06:30:49', '2026-05-05 06:30:49'),
('CHCK-20260505133119-LJZ1', 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV04_CHO_XU_LY', 'XUAT_TRA_NCC', 2, 'ND001', '2026-05-05 13:31:19', 'Trả hàng NCC - Phiếu PTNCC-20260505133115-FQCN', '2026-05-05 06:31:19', '2026-05-05 06:31:19'),
('CHUP-20260505133513-R4XA', 'TH0005', 'PN_TRA_20260505_0001', 'SL_20260220_0001', NULL, 'KV04_CHO_XU_LY', 10, 'ND001', '2026-05-05 13:35:13', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 10)', '2026-05-05 06:35:13', '2026-05-05 06:35:13'),
('CHUP-20260505135204-VFR9', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', NULL, 'KV01_TIEP_NHAN', 8, 'ND001', '2026-05-05 13:52:04', 'Nhập kho tự động sau khi xác nhận kiểm đếm (Lượng tăng thêm: 8)', '2026-05-05 06:52:04', '2026-05-05 06:52:04'),
('CHCK-20260505135515-94AN', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV01_TIEP_NHAN', 'KV02_BIET_TRU', 8, 'ND001', '2026-05-05 13:55:15', 'Nhân viên thực hiện luân chuyển', '2026-05-05 06:55:15', '2026-05-05 06:55:15'),
('CHCK-20260505135534-3G2X', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV02_BIET_TRU', 'KV03_THANH_PHAM', 4, 'ND001', '2026-05-05 13:55:34', 'Nhân viên thực hiện luân chuyển', '2026-05-05 06:55:34', '2026-05-05 06:55:34'),
('CHCK-20260505135549-PVU9', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV02_BIET_TRU', 'KV04_CHO_XU_LY', 4, 'ND001', '2026-05-05 13:55:49', 'test', '2026-05-05 06:55:49', '2026-05-05 06:55:49'),
('CHCK-20260505135613-HSSJ', 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV04_CHO_XU_LY', 'KV05_LOAI_BO', 2, 'ND001', '2026-05-05 13:56:13', 'test', '2026-05-05 06:56:13', '2026-05-05 06:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_kho`
--

DROP TABLE IF EXISTS `lich_su_kho`;
CREATE TABLE IF NOT EXISTS `lich_su_kho` (
  `ma_log` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguoi_thuc_hien` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_chung_tu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã phiếu nhập, phiếu xuất hoặc đơn hàng liên quan',
  `loai_giao_dich` enum('nhap','xuat','dieu_chinh','tra_hang') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nguon_giao_dich` enum('phieu_nhap','phieu_xuat','don_hang','kiem_kho','tra_hang') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_luong` int NOT NULL,
  `ton_truoc` int NOT NULL,
  `ton_sau` int NOT NULL,
  `don_gia` decimal(15,2) NOT NULL,
  `thoi_gian` datetime NOT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_log`),
  KEY `lich_su_kho_nguoi_thuc_hien_foreign` (`nguoi_thuc_hien`),
  KEY `lich_su_kho_ma_thuoc_so_lo_index` (`ma_thuoc`,`so_lo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_kho`
--

INSERT INTO `lich_su_kho` (`ma_log`, `ma_thuoc`, `so_lo`, `nguoi_thuc_hien`, `ma_chung_tu`, `loai_giao_dich`, `nguon_giao_dich`, `so_luong`, `ton_truoc`, `ton_sau`, `don_gia`, `thoi_gian`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('LOG-20260105090000-0001', 'TH0001', 'SL_20260105_0001', 'ND003', 'PN_20260105_0001', 'nhap', 'phieu_nhap', 500, 0, 500, 18000.00, '2026-01-05 09:00:00', 'Nhập kho từ PN_20260105_0001', '2026-01-05 02:00:00', '2026-01-05 02:00:00'),
('LOG-20260105090500-0002', 'TH0004', 'SL_20260105_0002', 'ND003', 'PN_20260105_0001', 'nhap', 'phieu_nhap', 400, 0, 400, 15000.00, '2026-01-05 09:05:00', 'Nhập kho từ PN_20260105_0001', '2026-01-05 02:05:00', '2026-01-05 02:05:00'),
('LOG-20260110090000-0003', 'TH0002', 'SL_20260110_0001', 'ND003', 'PN_20260110_0001', 'nhap', 'phieu_nhap', 150, 0, 150, 100000.00, '2026-01-10 09:00:00', 'Nhập kho từ PN_20260110_0001', '2026-01-10 02:00:00', '2026-01-10 02:00:00'),
('LOG-20260110090500-0004', 'TH0005', 'SL_20260110_0002', 'ND003', 'PN_20260110_0001', 'nhap', 'phieu_nhap', 300, 0, 300, 25000.00, '2026-01-10 09:05:00', 'Nhập kho từ PN_20260110_0001', '2026-01-10 02:05:00', '2026-01-10 02:05:00'),
('LOG-20260120090000-0005', 'TH0006', 'SL_20260120_0001', 'ND003', 'PN_20260120_0001', 'nhap', 'phieu_nhap', 100, 0, 100, 50000.00, '2026-01-20 09:00:00', 'Nhập kho từ PN_20260120_0001', '2026-01-20 02:00:00', '2026-01-20 02:00:00'),
('LOG-20260120090500-0006', 'TH0007', 'SL_20260120_0002', 'ND003', 'PN_20260120_0001', 'nhap', 'phieu_nhap', 50, 0, 50, 70000.00, '2026-01-20 09:05:00', 'Nhập kho từ PN_20260120_0001', '2026-01-20 02:05:00', '2026-01-20 02:05:00'),
('LOG-20260201090000-0007', 'TH0008', 'SL_20260201_0001', 'ND003', 'PN_20260201_0001', 'nhap', 'phieu_nhap', 200, 0, 200, 40000.00, '2026-02-01 09:00:00', 'Nhập kho từ PN_20260201_0001', '2026-02-01 02:00:00', '2026-02-01 02:00:00'),
('LOG-20260201090500-0008', 'TH0002', 'SL_20260201_0002', 'ND003', 'PN_20260201_0001', 'nhap', 'phieu_nhap', 100, 0, 100, 100000.00, '2026-02-01 09:05:00', 'Nhập kho từ PN_20260201_0001', '2026-02-01 02:05:00', '2026-02-01 02:05:00'),
('LOG-20260210090000-0009', 'TH0001', 'SL_20260210_0001', 'ND003', 'PN_20260210_0001', 'nhap', 'phieu_nhap', 300, 0, 300, 18000.00, '2026-02-10 09:00:00', 'Nhập kho từ PN_20260210_0001', '2026-02-10 02:00:00', '2026-02-10 02:00:00'),
('LOG-20260220090000-0010', 'TH0005', 'SL_20260220_0001', 'ND003', 'PN_20260220_0001', 'nhap', 'phieu_nhap', 200, 0, 200, 25000.00, '2026-02-20 09:00:00', 'Nhập kho từ PN_20260220_0001', '2026-02-20 02:00:00', '2026-02-20 02:00:00'),
('LOG-20260301090000-0011', 'TH0001', 'SL_20260301_0001', 'ND003', 'PN_20260301_0001', 'nhap', 'phieu_nhap', 600, 0, 600, 18000.00, '2026-03-01 09:00:00', 'Nhập kho từ PN_20260301_0001', '2026-03-01 02:00:00', '2026-03-01 02:00:00'),
('LOG-20260301090500-0012', 'TH0008', 'SL_20260301_0002', 'ND003', 'PN_20260301_0001', 'nhap', 'phieu_nhap', 230, 0, 230, 40000.00, '2026-03-01 09:05:00', 'Nhập kho từ PN_20260301_0001', '2026-03-01 02:05:00', '2026-03-01 02:05:00'),
('LOG-20260310090000-0013', 'TH0005', 'SL_20260310_0001', 'ND003', 'PN_20260310_0001', 'nhap', 'phieu_nhap', 270, 0, 270, 25000.00, '2026-03-10 09:00:00', 'Nhập kho từ PN_20260310_0001', '2026-03-10 02:00:00', '2026-03-10 02:00:00'),
('LOG-20260116100000-0014', 'TH0001', 'SL_20260105_0001', 'ND004', 'PX_DH_20260115_01_0001', 'xuat', 'phieu_xuat', 100, 500, 400, 50000.00, '2026-01-16 10:00:00', 'Xuất kho theo PX_DH_20260115_01_0001', '2026-01-16 03:00:00', '2026-01-16 03:00:00'),
('LOG-20260116100500-0015', 'TH0002', 'SL_20260110_0001', 'ND004', 'PX_DH_20260115_01_0001', 'xuat', 'phieu_xuat', 30, 150, 120, 150000.00, '2026-01-16 10:05:00', 'Xuất kho theo PX_DH_20260115_01_0001', '2026-01-16 03:05:00', '2026-01-16 03:05:00'),
('LOG-20260126100000-0016', 'TH0005', 'SL_20260110_0002', 'ND004', 'PX_DH_20260125_01_0001', 'xuat', 'phieu_xuat', 80, 300, 220, 45000.00, '2026-01-26 10:00:00', 'Xuất kho theo PX_DH_20260125_01_0001', '2026-01-26 03:00:00', '2026-01-26 03:00:00'),
('LOG-20260126100500-0017', 'TH0006', 'SL_20260120_0001', 'ND004', 'PX_DH_20260125_01_0001', 'xuat', 'phieu_xuat', 40, 100, 60, 85000.00, '2026-01-26 10:05:00', 'Xuất kho theo PX_DH_20260125_01_0001', '2026-01-26 03:05:00', '2026-01-26 03:05:00'),
('LOG-20260126101000-0018', 'TH0007', 'SL_20260120_0002', 'ND004', 'PX_DH_20260125_01_0001', 'xuat', 'phieu_xuat', 20, 50, 30, 120000.00, '2026-01-26 10:10:00', 'Xuất kho theo PX_DH_20260125_01_0001', '2026-01-26 03:10:00', '2026-01-26 03:10:00'),
('LOG-20260206100000-0019', 'TH0008', 'SL_20260201_0001', 'ND004', 'PX_DH_20260205_01_0001', 'xuat', 'phieu_xuat', 50, 200, 150, 500000.00, '2026-02-06 10:00:00', 'Xuất kho theo PX_DH_20260205_01_0001', '2026-02-06 03:00:00', '2026-02-06 03:00:00'),
('LOG-20260206100500-0020', 'TH0004', 'SL_20260105_0002', 'ND004', 'PX_DH_20260205_01_0001', 'xuat', 'phieu_xuat', 40, 340, 300, 25000.00, '2026-02-06 10:05:00', 'Xuất kho theo PX_DH_20260205_01_0001', '2026-02-06 03:05:00', '2026-02-06 03:05:00'),
('LOG-20260216100000-0021', 'TH0002', 'SL_20260110_0001', 'ND004', 'PX_DH_20260215_01_0001', 'xuat', 'phieu_xuat', 20, 120, 100, 150000.00, '2026-02-16 10:00:00', 'Xuất kho theo PX_DH_20260215_01_0001', '2026-02-16 03:00:00', '2026-02-16 03:00:00'),
('LOG-20260216100500-0022', 'TH0005', 'SL_20260220_0001', 'ND004', 'PX_DH_20260215_01_0001', 'xuat', 'phieu_xuat', 30, 200, 170, 45000.00, '2026-02-16 10:05:00', 'Xuất kho theo PX_DH_20260215_01_0001', '2026-02-16 03:05:00', '2026-02-16 03:05:00'),
('LOG-20260303100000-0023', 'TH0001', 'SL_20260301_0001', 'ND004', 'PX_DH_20260302_01_0001', 'xuat', 'phieu_xuat', 80, 600, 520, 50000.00, '2026-03-03 10:00:00', 'Xuất kho theo PX_DH_20260302_01_0001', '2026-03-03 03:00:00', '2026-03-03 03:00:00'),
('LOG-20260417160914-VNFS', 'TH0005', 'SL_20260410_0001', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 50, 0, 50, 25000.00, '2026-04-17 16:09:14', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:09:14', '2026-04-17 09:09:14'),
('LOG-20260417160914-UXD5', 'TH0006', 'SL_20260410_0002', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 20, 0, 20, 30000.00, '2026-04-17 16:09:14', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:09:14', '2026-04-17 09:09:14'),
('LOG-20260417161058-DCIX', 'TH0005', 'SL_20260410_0001', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 50, 0, 50, 25000.00, '2026-04-17 16:10:58', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:10:58', '2026-04-17 09:10:58'),
('LOG-20260417161058-DWJM', 'TH0006', 'SL_20260410_0002', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 20, 0, 20, 30000.00, '2026-04-17 16:10:58', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:10:58', '2026-04-17 09:10:58'),
('LOG-20260417162239-XDVO', 'TH0005', 'SL_20260410_0001', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 130, 50, 180, 25000.00, '2026-04-17 16:22:39', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:22:39', '2026-04-17 09:22:39'),
('LOG-20260417162239-CHXN', 'TH0006', 'SL_20260410_0002', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 60, 20, 80, 30000.00, '2026-04-17 16:22:39', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:22:39', '2026-04-17 09:22:39'),
('LOG-20260417162320-1JKC', 'TH0006', 'SL_20260410_0002', 'USR001', 'PN_20260410_0001', 'nhap', 'phieu_nhap', 10, 80, 90, 30000.00, '2026-04-17 16:23:20', 'Xác nhận hàng về kho từ phiếu nhập', '2026-04-17 09:23:20', '2026-04-17 09:23:20'),
('LOG-20260417164741-AQXV', 'TH0006', 'SL_20260417_0001', 'ND001', 'PN_20260417_0001', 'nhap', 'phieu_nhap', 3, 0, 3, 800000.00, '2026-04-17 16:47:41', 'Xác nhận hàng về kho (tổng khai báo: 3)', '2026-04-17 09:47:41', '2026-04-17 09:47:41'),
('LOG-20260417164957-DF4L', 'TH0006', 'SL_20260417_0001', 'ND001', 'PN_20260417_0001', 'nhap', 'phieu_nhap', 2, 3, 5, 800000.00, '2026-04-17 16:49:57', 'Xác nhận hàng về kho (tổng khai báo: 5)', '2026-04-17 09:49:57', '2026-04-17 09:49:57'),
('LOG-20260417165713-AGSP', 'TH0006', 'SL_20260417_0001', 'ND001', 'PN_20260417_0001', 'nhap', 'phieu_nhap', 5, 5, 10, 800000.00, '2026-04-17 16:57:13', 'Xác nhận hàng về kho (tổng khai báo: 10)', '2026-04-17 09:57:13', '2026-04-17 09:57:13'),
('LOG-20260418080316-LLQB', 'TH0006', 'SL_20260120_0001', 'ND003', 'PX_DH_20260418_01_0001', 'xuat', 'phieu_xuat', 5, 60, 55, 85000.00, '2026-04-18 08:03:16', 'Xuất kho (Bán sỉ)', '2026-04-18 01:03:16', '2026-04-18 01:03:16'),
('LOG-20260418172751-M9PQ', 'TH0001', 'SL_20260418_0001', 'ND001', 'PN_20260418_0001', 'nhap', 'phieu_nhap', 5, 0, 5, 500000.00, '2026-04-18 17:27:51', 'Xác nhận hàng về kho (tổng khai báo: 5)', '2026-04-18 10:27:51', '2026-04-18 10:27:51'),
('LOG-20260418172803-NWM2', 'TH0001', 'SL_20260418_0001', 'ND001', 'PN_20260418_0001', 'nhap', 'phieu_nhap', 5, 5, 10, 500000.00, '2026-04-18 17:28:03', 'Xác nhận hàng về kho (tổng khai báo: 10)', '2026-04-18 10:28:03', '2026-04-18 10:28:03'),
('LOG-20260418173940-5QYX', 'TH0001', 'SL_20260418_0002', 'ND001', 'PN_20260418_0002', 'nhap', 'phieu_nhap', 5, 0, 5, 1000000.00, '2026-04-18 17:39:40', 'Xác nhận hàng về kho (tổng khai báo: 5)', '2026-04-18 10:39:40', '2026-04-18 10:39:40'),
('LOG-20260430095301-ZOSV', 'TH0007', 'SL_20260430_0001', 'ND001', 'PN_20260430_0001', 'nhap', 'phieu_nhap', 4, 0, 4, 500000.00, '2026-04-30 09:53:01', 'Xác nhận hàng về kho (tổng khai báo: 4)', '2026-04-30 02:53:01', '2026-04-30 02:53:01'),
('LOG-20260430095357-A94T', 'TH0001', 'SL_20260418_0002', 'ND001', 'PN_20260418_0002', 'nhap', 'phieu_nhap', 5, 5, 10, 1000000.00, '2026-04-30 09:53:57', 'Xác nhận hàng về kho (tổng khai báo: 10)', '2026-04-30 02:53:57', '2026-04-30 02:53:57'),
('LOG-20260502090225-LCBR', 'TH0006', 'SL_20260120_0001', 'ND001', 'PN_TRA_20260502_0003', 'nhap', 'phieu_nhap', 3, 0, 3, 85000.00, '2026-05-02 09:02:25', 'Xác nhận hàng về kho (tổng khai báo: 3)', '2026-05-02 02:02:25', '2026-05-02 02:02:25'),
('LOG-20260502094404-LLGI', 'TH0006', 'SL_20260417_0001', 'ND001', 'PN_20260417_0001', 'dieu_chinh', 'kiem_kho', 1, 10, 10, 0.00, '2026-05-02 09:44:04', '[Ngưng bán] check lại HSD | Đã chuyển 1 vào KV04', '2026-05-02 02:44:04', '2026-05-02 02:44:04'),
('LOG-20260505083002-NGZD', 'TH0001', 'SL_20260105_0001', 'ND001', 'PX_DH_20260505_01_0001', 'xuat', 'phieu_xuat', 110, 400, 290, 50000.00, '2026-05-05 08:30:02', 'Xuất kho (Bán sỉ)', '2026-05-05 01:30:02', '2026-05-05 01:30:02'),
('LOG-20260505083010-CNCG', 'TH0001', 'SL_20260105_0001', 'ND001', 'PX_DH_20260505_01_0001', 'dieu_chinh', 'phieu_xuat', 110, 290, 400, 50000.00, '2026-05-05 08:30:10', '[Hoàn tác xuất kho] Đưa phiếu về trạng thái Đang chuẩn bị', '2026-05-05 01:30:10', '2026-05-05 01:30:10'),
('LOG-20260505083153-CPZK', 'TH0001', 'SL_20260105_0001', 'ND001', 'PX_DH_20260505_01_0001', 'xuat', 'phieu_xuat', 110, 400, 290, 50000.00, '2026-05-05 08:31:53', 'Xuất kho (Bán sỉ)', '2026-05-05 01:31:53', '2026-05-05 01:31:53'),
('LOG-20260505094825-THQI', 'TH0006', 'SL_20260120_0001', 'ND001', 'TN260505094825B9G', 'xuat', 'phieu_nhap', 1, 2, 1, 85000.00, '2026-05-05 09:48:25', 'Trả hàng lại cho nhà cung cấp', '2026-05-05 02:48:25', '2026-05-05 02:48:25'),
('LOG-20260505133513-2B7M', 'TH0005', 'SL_20260220_0001', 'ND001', 'PN_TRA_20260505_0001', 'nhap', 'phieu_nhap', 10, 0, 10, 45000.00, '2026-05-05 13:35:13', 'Xác nhận hàng về kho (tổng khai báo: 10)', '2026-05-05 06:35:13', '2026-05-05 06:35:13'),
('LOG-20260505135204-WNCZ', 'TH0009', 'SL_20260505_0002', 'ND001', 'PN_20260505_0002', 'nhap', 'phieu_nhap', 8, 0, 8, 1000000.00, '2026-05-05 13:52:04', 'Xác nhận hàng về kho (tổng khai báo: 8)', '2026-05-05 06:52:04', '2026-05-05 06:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(33, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(34, '2024_01_01_000001_create_nhom_thuoc_table', 1),
(35, '2024_01_01_000002_create_don_vi_tinh_table', 1),
(36, '2024_01_01_000003_create_nguoi_dung_table', 1),
(37, '2024_01_01_000004_create_nha_cung_cap_table', 1),
(38, '2024_01_01_000005_create_khach_hang_table', 1),
(39, '2024_01_01_000006_create_thuoc_table', 1),
(40, '2024_01_01_000007_create_phieu_nhap_table', 1),
(41, '2024_01_01_000008_create_chi_tiet_phieu_nhap_table', 1),
(42, '2024_01_01_000009_create_ton_kho_table', 1),
(43, '2024_01_01_000010_create_don_hang_table', 1),
(44, '2024_01_01_000011_create_chi_tiet_don_hang_table', 1),
(45, '2024_01_01_000012_create_phieu_xuat_table', 1),
(46, '2024_01_01_000013_create_chi_tiet_phieu_xuat_table', 1),
(47, '2024_01_01_000014_create_thanh_toan_table', 1),
(48, '2024_01_01_000015_create_lich_su_kho_table', 1),
(49, '2024_01_01_000015_add_columns_to_thanh_toan_table', 2),
(50, '2024_01_01_000016_add_image_col_to_thanh_toan_table', 3),
(51, '2024_04_01_000001_create_khu_vuc_kho_table', 4),
(52, '2024_04_01_000002_create_ton_kho_khu_vuc_table', 4),
(53, '2024_04_01_000003_create_lich_su_dich_chuyen_kho_table', 4),
(54, '2024_04_02_000001_add_nsx_sdk_to_tables', 5),
(55, '2024_04_03_000001_create_khach_tra_hang_tables', 6),
(56, '2026_04_03_224300_add_tra_hang_to_lich_su_kho_enums', 7),
(57, '2026_04_03_235000_add_hoan_tien_fields_to_returns_payments', 7),
(58, '2026_04_07_000000_add_minh_chung_image_to_khach_tra_hang_table', 8),
(59, '2026_04_08_000001_expand_nguoi_dung_roles', 9),
(60, '2026_04_14_141222_create_feature_toggles_table', 10),
(61, '2026_04_18_171342_modify_images_ton_kho_and_chi_tiet', 11),
(62, '2026_04_18_173633_remove_image2_from_phieu_nhap_table', 12),
(63, '2026_04_19_050000_add_nguoi_duyet_and_nguoi_tao_columns', 13),
(64, '2026_05_02_084913_drop_image_columns_from_don_hang_table', 14),
(65, '2026_05_02_102711_add_nguoi_huy_to_don_hang_table', 15),
(69, '2026_05_05_091230_create_phieu_tra_ncc_tables', 16),
(70, '2026_05_05_091243_create_phieu_tieu_huy_tables', 16),
(71, '2026_05_05_124240_add_ma_phieu_tra_ncc_to_thanh_toan_table', 16),
(72, '2026_05_05_130838_update_thanh_toan_constraint_for_supplier_returns', 17);

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `ma_nguoi_dung` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_dang_nhap` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL COMMENT '1: Admin, 2: Nhân viên kho, 3: Nhân viên bán hàng, 4: Kế toán, 5: Trưởng kho',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trang_thai` enum('cho_phep_hd','vo_hieu_hoa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_phep_hd',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ho_ten_nd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ma_nguoi_dung`),
  UNIQUE KEY `nguoi_dung_ten_dang_nhap_unique` (`ten_dang_nhap`),
  UNIQUE KEY `nguoi_dung_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `ten_dang_nhap`, `mat_khau`, `role`, `email`, `sdt`, `trang_thai`, `remember_token`, `created_at`, `updated_at`, `ho_ten_nd`) VALUES
('ND001', 'admin', '$2y$10$W8JcnvQObK6H4c6FDxrNq.DkvynNldpKYw/eG8BP5jR36PEnd4vlG', 1, 'admin@pharma.vn', '0901000001', 'cho_phep_hd', 'RBj9Qx9qP4gX7ye3cPFGVJRw3KkiQKn0LTfpRNSF81kj0YqzYjhHclmKSDDd', '2026-03-12 15:33:48', '2026-04-08 02:13:16', 'Quản trị viên'),
('ND002', 'truongkho', '$2y$10$eBHslj3DxrwhVIlIKGnihe0tBCcOUIQae9/hH8ZhDJyhCdp.9SVbW', 5, 'truongkho@pharma.vn', '0901000005', 'cho_phep_hd', NULL, '2026-04-08 02:13:16', '2026-04-08 02:13:16', 'Nguyễn Văn Trưởng'),
('ND003', 'nvkho', '$2y$10$UE6snvqS4wK1.1xfCBs3jOVQ0qzZe9weU4TZkd40.8MElvAPh2lNy', 2, 'nvkho@pharma.vn', '0901000002', 'cho_phep_hd', NULL, '2026-04-08 02:13:16', '2026-04-08 02:13:16', 'Trần Thị Kho'),
('ND004', 'nvbanhang', '$2y$10$bSo7dsi2MLq1JD5X8zk4VOGWcovIBXK6lSIOfAv.KOL15ZEYbYNCG', 3, 'nvbanhang@pharma.vn', '0901000003', 'cho_phep_hd', NULL, '2026-04-08 02:13:16', '2026-04-11 01:35:14', 'Lê Văn Bán'),
('ND005', 'ketoan', '$2y$10$Lr/cbCDYXEC1jy6.TsZwZ.zO/PXMWp4rYhimtujkDDACifczltDHu', 4, 'ketoan@pharma.vn', '0901000004', 'cho_phep_hd', NULL, '2026-04-08 02:13:16', '2026-04-08 02:13:16', 'Phạm Thị Toán');

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

DROP TABLE IF EXISTS `nha_cung_cap`;
CREATE TABLE IF NOT EXISTS `nha_cung_cap` (
  `ma_ncc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_ncc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_so_thue` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_ncc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`ma_ncc`, `ten_ncc`, `dia_chi`, `dien_thoai`, `email`, `ma_so_thue`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('NCC001', 'Công ty CP Dược Hậu Giang', '288 Bis Nguyễn Văn Cừ, Cần Thơ', '02923891433', 'dhg@dhgpharma.com.vn', '18001568', 'Nhà cung cấp chính', '2026-03-12 15:33:48', '2026-03-12 15:33:48'),
('NCC002', 'Công ty CP Traphaco', '75 Yên Ninh, Ba Đình, Hà Nội', '02436814472', 'info@traphaco.com.vn', '0100108656', 'Chuyên đông dược', '2026-03-12 15:33:48', '2026-03-12 15:33:48'),
('NCC003', '123132', '35', '043543654675465', 'fghfhfghfg@gmail.com', '1312546', 'a', '2026-03-13 01:00:54', '2026-03-13 01:00:54'),
('NCC004', 'TEST1', 'An Giang', '0123456789', 'tph151104@gmail.com', '151104', NULL, '2026-04-04 01:40:46', '2026-04-04 01:40:46'),
('NCC005', 'TEST2', 'Phú An', '0987654123', 'Test2@gmail.com', '098765', NULL, '2026-04-08 08:25:15', '2026-04-08 08:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `nhom_thuoc`
--

DROP TABLE IF EXISTS `nhom_thuoc`;
CREATE TABLE IF NOT EXISTS `nhom_thuoc` (
  `ma_nhom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_nhom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_nhom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhom_thuoc`
--

INSERT INTO `nhom_thuoc` (`ma_nhom`, `ten_nhom`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('NT01', 'Kháng sinh', 'Thuốc tiêu diệt hoặc kìm hãm vi khuẩn', '2026-03-12 15:33:48', '2026-03-27 00:22:38'),
('NT02', 'Giảm đau - Hạ sốt', 'Thuốc điều trị triệu chứng đau và sốt', '2026-03-12 15:33:48', '2026-03-12 15:33:48'),
('NT03', 'Vitamin & Khoáng chất', 'Bổ sung dưỡng chất cho cơ thể', '2026-03-12 15:33:48', '2026-03-12 15:33:48'),
('NT04', 'Thực phẩm bổ sung', NULL, '2026-03-27 00:27:47', '2026-03-27 00:27:47'),
('NT05', 'Nhóm Test', NULL, '2026-05-05 06:47:32', '2026-05-05 06:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieu_nhap`
--

DROP TABLE IF EXISTS `phieu_nhap`;
CREATE TABLE IF NOT EXISTS `phieu_nhap` (
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_ncc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguoi_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_nhap` date NOT NULL,
  `tong_tien` decimal(15,2) NOT NULL,
  `trang_thai_tt` enum('chua_tt','mot_phan','da_tt') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chua_tt',
  `trang_thai_phieu_nhap` enum('cho_nhap_kho','da_nhap_kho','da_huy','doi_hang_ve') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_nhap_kho',
  `image1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hình ảnh phiếu nhập 1',
  `giay_to_lien_quan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'giấy tờ liên quan',
  `tieu_lieu_lien_quan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tài liệu liên quan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_nhap`),
  KEY `phieu_nhap_ma_ncc_foreign` (`ma_ncc`),
  KEY `phieu_nhap_nguoi_nhap_foreign` (`nguoi_nhap`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieu_nhap`
--

INSERT INTO `phieu_nhap` (`ma_phieu_nhap`, `ma_ncc`, `nguoi_nhap`, `ngay_nhap`, `tong_tien`, `trang_thai_tt`, `trang_thai_phieu_nhap`, `image1`, `giay_to_lien_quan`, `tieu_lieu_lien_quan`, `created_at`, `updated_at`) VALUES
('PN_20260105_0001', 'NCC001', 'ND003', '2026-01-05', 15000000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
('PN_20260110_0001', 'NCC002', 'ND003', '2026-01-10', 22500000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
('PN_20260120_0001', 'NCC001', 'ND003', '2026-01-20', 8500000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
('PN_20260201_0001', 'NCC002', 'ND002', '2026-02-01', 18000000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-02-01 01:00:00', '2026-02-01 01:00:00'),
('PN_20260210_0001', 'NCC001', 'ND003', '2026-02-10', 12000000.00, 'mot_phan', 'da_nhap_kho', '', '', '', '2026-02-10 01:00:00', '2026-02-10 01:00:00'),
('PN_20260220_0001', 'NCC002', 'ND002', '2026-02-20', 9600000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-02-20 01:00:00', '2026-02-20 01:00:00'),
('PN_20260301_0001', 'NCC001', 'ND003', '2026-03-01', 20000000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-03-01 01:00:00', '2026-03-01 01:00:00'),
('PN_20260310_0001', 'NCC002', 'ND003', '2026-03-10', 6750000.00, 'chua_tt', 'da_nhap_kho', '', '', '', '2026-03-10 01:00:00', '2026-03-10 01:00:00'),
('PN_20260315_0001', 'NCC001', 'ND002', '2026-03-15', 14000000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-03-15 01:00:00', '2026-03-15 01:00:00'),
('PN_20260325_0001', 'NCC002', 'ND003', '2026-03-25', 10200000.00, 'da_tt', 'da_nhap_kho', '', '', '', '2026-03-25 01:00:00', '2026-05-05 06:42:47'),
('PN_20260401_0001', 'NCC001', 'ND003', '2026-04-01', 16500000.00, 'chua_tt', 'da_nhap_kho', '', '', '', '2026-04-01 01:00:00', '2026-04-01 01:00:00'),
('PN_20260410_0001', 'NCC002', 'ND002', '2026-04-10', 7200000.00, 'chua_tt', 'da_nhap_kho', '', 'uploads/batches/1776441562_giayto.png', 'uploads/batches/1776441563_tieulieu.pdf', '2026-04-10 01:00:00', '2026-04-17 09:23:20'),
('PN_20260417_0001', 'NCC004', 'ND001', '2026-04-17', 8000000.00, 'chua_tt', 'da_nhap_kho', '', 'uploads/batches/1776445033_giayto.png', 'uploads/batches/1776445033_tieulieu.pdf', '2026-04-17 09:47:15', '2026-04-17 09:57:13'),
('PN_20260418_0001', 'NCC004', 'ND003', '2026-04-18', 5000000.00, 'chua_tt', 'da_nhap_kho', 'uploads/batches/1776533271_phieunhap.jpg', 'uploads/batches/1776533271_giayto.png', 'uploads/batches/1776533271_tieulieu.pdf', '2026-04-18 00:58:52', '2026-04-18 10:28:03'),
('PN_20260418_0002', 'NCC004', 'ND001', '2026-04-19', 10000000.00, 'chua_tt', 'da_nhap_kho', 'uploads/batches/1776533980_phieunhap.jpg', 'uploads/batches/1776533980_giayto.png', 'uploads/batches/1776533980_tieulieu.pdf', '2026-04-18 10:38:59', '2026-04-30 02:53:57'),
('PN_20260430_0001', 'NCC001', 'ND001', '2026-04-30', 5000000.00, 'chua_tt', 'cho_nhap_kho', 'uploads/batches/1777542781_phieunhap.jpg', 'uploads/batches/1777542781_giayto.png', 'uploads/batches/1777542781_tieulieu.pdf', '2026-04-30 02:51:41', '2026-04-30 02:53:04'),
('PN_20260505_0002', 'NCC005', 'ND001', '2026-05-05', 8000000.00, 'chua_tt', 'da_nhap_kho', 'uploads/batches/1777989124_phieunhap.jpg', 'uploads/batches/1777989124_giayto.png', 'uploads/batches/1777989124_tieulieu.pdf', '2026-05-05 06:49:59', '2026-05-05 06:52:26'),
('PN_20260505_0001', 'NCC001', 'ND001', '2026-05-05', 500000.00, 'chua_tt', 'doi_hang_ve', '', '', '', '2026-05-05 00:53:29', '2026-05-05 00:53:29'),
('PN_TRA_20260505_0001', 'NCC002', 'ND001', '2026-05-05', 450000.00, 'chua_tt', 'da_nhap_kho', '', '', '[MA_TRA:TH_20260310_100000] Khách hàng trả hàng đơn DH_20260215_01', '2026-05-05 06:34:48', '2026-05-05 06:35:13'),
('PN_TRA_20260502_0003', 'NCC001', 'ND001', '2026-05-02', 255000.00, 'chua_tt', 'da_nhap_kho', '', '', '[MA_TRA:TH_20260418_080953] Khách hàng trả hàng đơn DH_20260418_01', '2026-05-02 02:00:44', '2026-05-02 02:02:25');

-- --------------------------------------------------------

--
-- Table structure for table `phieu_tra_ncc`
--

DROP TABLE IF EXISTS `phieu_tra_ncc`;
CREATE TABLE IF NOT EXISTS `phieu_tra_ncc` (
  `ma_phieu_tra_ncc` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_ncc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguoi_tao` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_tao` date NOT NULL,
  `tong_tien` decimal(15,2) NOT NULL DEFAULT '0.00',
  `trang_thai` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_duyet',
  `ly_do_tra` text COLLATE utf8mb4_unicode_ci,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_tra_ncc`),
  KEY `phieu_tra_ncc_ma_ncc_foreign` (`ma_ncc`),
  KEY `phieu_tra_ncc_nguoi_tao_foreign` (`nguoi_tao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieu_tra_ncc`
--

INSERT INTO `phieu_tra_ncc` (`ma_phieu_tra_ncc`, `ma_ncc`, `nguoi_tao`, `ngay_tao`, `tong_tien`, `trang_thai`, `ly_do_tra`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('PTNCC_20260505_0001', 'NCC005', 'ND001', '2026-05-05', 1000000.00, 'da_huy', 'ko đặt chuẩn', '\nLý do hủy: ncc từ chối', '2026-05-05 06:56:56', '2026-05-05 06:57:11'),
('PTNCC_20260505_0002', 'NCC004', 'ND002', '2026-05-05', 2400000.00, 'da_hoan_thanh', 'tesst', NULL, '2026-05-05 06:11:43', '2026-05-05 06:12:55'),
('PTNCC_20260505_0003', 'NCC004', 'ND001', '2026-05-05', 2000000.00, 'da_hoan_thanh', 'ko đủ chất lượng', NULL, '2026-05-05 06:31:15', '2026-05-05 06:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `phieu_xuat`
--

DROP TABLE IF EXISTS `phieu_xuat`;
CREATE TABLE IF NOT EXISTS `phieu_xuat` (
  `ma_phieu_xuat` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_kh` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_don_hang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nguoi_tao_phieu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_xuat` date NOT NULL,
  `tong_tien` decimal(15,2) NOT NULL,
  `trang_thai_tt` enum('chua_tt','mot_phan','da_tt') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chua_tt',
  `trang_thai_phieu_xuat` enum('dang_chuan_bi','da_xuat_kho','da_van_chuyen','da_huy','da_hoan_thanh') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dang_chuan_bi',
  `image1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hình ảnh phiếu xuất 1',
  `image2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hình ảnh phiếu xuất 2',
  `giay_to_an_toan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'giấy tờ an toàn về thuốc',
  `tai_lieu_lien_quan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tài liệu liên quan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_phieu_xuat`),
  KEY `phieu_xuat_ma_kh_foreign` (`ma_kh`),
  KEY `phieu_xuat_ma_don_hang_foreign` (`ma_don_hang`),
  KEY `phieu_xuat_nguoi_tao_phieu_foreign` (`nguoi_tao_phieu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieu_xuat`
--

INSERT INTO `phieu_xuat` (`ma_phieu_xuat`, `ma_kh`, `ma_don_hang`, `nguoi_tao_phieu`, `ngay_xuat`, `tong_tien`, `trang_thai_tt`, `trang_thai_phieu_xuat`, `image1`, `image2`, `giay_to_an_toan`, `tai_lieu_lien_quan`, `created_at`, `updated_at`) VALUES
('PX_DH_20260115_01_0001', 'KH01', 'DH_20260115_01', 'ND003', '2026-01-16', 11000000.00, 'da_tt', 'da_hoan_thanh', '', '', '', '', '2026-01-16 03:00:00', '2026-01-16 03:00:00'),
('PX_DH_20260125_01_0001', 'KH02', 'DH_20260125_01', 'ND003', '2026-01-26', 9000000.00, 'da_tt', 'da_hoan_thanh', '', '', '', '', '2026-01-26 03:00:00', '2026-01-26 03:00:00'),
('PX_DH_20260205_01_0001', 'KH01', 'DH_20260205_01', 'ND003', '2026-02-06', 27500000.00, 'da_tt', 'da_hoan_thanh', '', '', '', '', '2026-02-06 03:00:00', '2026-02-06 03:00:00'),
('PX_DH_20260215_01_0001', 'KH003', 'DH_20260215_01', 'ND003', '2026-02-16', 5625000.00, 'mot_phan', 'da_hoan_thanh', '', '', '', '', '2026-02-16 03:00:00', '2026-02-16 03:00:00'),
('PX_DH_20260302_01_0001', 'KH02', 'DH_20260302_01', 'ND003', '2026-03-03', 22400000.00, 'da_tt', 'da_hoan_thanh', '', '', '', '', '2026-03-03 03:00:00', '2026-03-03 03:00:00'),
('PX_DH_20260311_01_0001', 'KH01', 'DH_20260311_01', 'ND003', '2026-03-12', 6700000.00, 'da_tt', 'da_hoan_thanh', '', '', '', '', '2026-03-12 03:00:00', '2026-03-12 03:00:00'),
('PX_DH_20260321_01_0001', 'KH003', 'DH_20260321_01', 'ND003', '2026-03-22', 6850000.00, 'mot_phan', 'da_hoan_thanh', '', '', '', '', '2026-03-22 03:00:00', '2026-03-22 03:00:00'),
('PX_DH_20260418_01_0001', 'KH003', 'DH_20260418_01', 'ND003', '2026-04-18', 425000.00, 'mot_phan', 'da_hoan_thanh', '', '', '', '', '2026-04-18 01:02:50', '2026-05-05 06:42:59'),
('PX_DH_20260505_01_0001', 'KH01', 'DH_20260505_01', 'ND001', '2026-05-05', 5500000.00, 'chua_tt', 'da_hoan_thanh', 'uploads/exports/1777969913_image1.jpg', 'uploads/exports/1777969913_image2.jpg', 'uploads/exports/1777969913_giay_to_an_toan.png', 'uploads/exports/1777969913_tai_lieu_lien_quan.pdf', '2026-05-05 01:02:47', '2026-05-05 01:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

DROP TABLE IF EXISTS `thanh_toan`;
CREATE TABLE IF NOT EXISTS `thanh_toan` (
  `ma_thanh_toan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_thanh_toan` enum('nhap','xuat','tra_hang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_phieu_xuat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_tra_hang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_phieu_tra_ncc` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tong_tien` decimal(15,2) NOT NULL DEFAULT '0.00',
  `so_tien_tt` decimal(15,2) NOT NULL,
  `so_tien_con_no` decimal(15,2) NOT NULL DEFAULT '0.00',
  `trang_thai_tt` enum('da_du','con_no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'con_no',
  `phuong_thuc_tt` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_thanh_toan` date NOT NULL,
  `minh_chung_tt_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_thanh_toan`),
  KEY `thanh_toan_ma_phieu_nhap_foreign` (`ma_phieu_nhap`),
  KEY `thanh_toan_ma_phieu_xuat_foreign` (`ma_phieu_xuat`),
  KEY `thanh_toan_ma_tra_hang_foreign` (`ma_tra_hang`)
) ;

--
-- Dumping data for table `thanh_toan`
--

INSERT INTO `thanh_toan` (`ma_thanh_toan`, `loai_thanh_toan`, `ma_phieu_nhap`, `ma_phieu_xuat`, `ma_tra_hang`, `ma_phieu_tra_ncc`, `tong_tien`, `so_tien_tt`, `so_tien_con_no`, `trang_thai_tt`, `phuong_thuc_tt`, `ngay_thanh_toan`, `minh_chung_tt_image`, `ghi_chu`, `created_at`, `updated_at`) VALUES
('TTN00001', 'nhap', 'PN_20260105_0001', NULL, NULL, NULL, 15000000.00, 15000000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-01-06', NULL, 'TT đầy đủ PN_20260105_0001', '2026-01-06 07:00:00', '2026-01-06 07:00:00'),
('TTN00002', 'nhap', 'PN_20260110_0001', NULL, NULL, NULL, 22500000.00, 22500000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-01-12', NULL, 'TT đầy đủ PN_20260110_0001', '2026-01-12 07:00:00', '2026-01-12 07:00:00'),
('TTN00003', 'nhap', 'PN_20260120_0001', NULL, NULL, NULL, 8500000.00, 8500000.00, 0.00, 'da_du', 'tien_mat', '2026-01-22', NULL, 'TT đầy đủ PN_20260120_0001', '2026-01-22 07:00:00', '2026-01-22 07:00:00'),
('TTN00004', 'nhap', 'PN_20260201_0001', NULL, NULL, NULL, 18000000.00, 18000000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-02-05', NULL, 'TT đầy đủ PN_20260201_0001', '2026-02-05 07:00:00', '2026-02-05 07:00:00'),
('TTN00005', 'nhap', 'PN_20260210_0001', NULL, NULL, NULL, 12000000.00, 8000000.00, 4000000.00, 'con_no', 'chuyen_khoan', '2026-02-15', NULL, 'TT 1 phần PN_20260210_0001', '2026-02-15 07:00:00', '2026-02-15 07:00:00'),
('TTX00001', 'xuat', NULL, 'PX_DH_20260115_01_0001', NULL, NULL, 11000000.00, 11000000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-01-20', NULL, 'KH01 thanh toán PX_DH_20260115_01_0001', '2026-01-20 07:00:00', '2026-01-20 07:00:00'),
('TTX00002', 'xuat', NULL, 'PX_DH_20260125_01_0001', NULL, NULL, 9000000.00, 9000000.00, 0.00, 'da_du', 'tien_mat', '2026-01-28', NULL, 'KH02 thanh toán PX_DH_20260125_01_0001', '2026-01-28 07:00:00', '2026-01-28 07:00:00'),
('TTX00003', 'xuat', NULL, 'PX_DH_20260205_01_0001', NULL, NULL, 27500000.00, 27500000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-02-10', NULL, 'KH01 thanh toán PX_DH_20260205_01_0001', '2026-02-10 07:00:00', '2026-02-10 07:00:00'),
('TTX00004', 'xuat', NULL, 'PX_DH_20260215_01_0001', NULL, NULL, 5625000.00, 3000000.00, 2625000.00, 'con_no', 'chuyen_khoan', '2026-02-20', NULL, 'KH003 TT 1 phần PX_DH_20260215_01_0001', '2026-02-20 07:00:00', '2026-02-20 07:00:00'),
('TTX00005', 'xuat', NULL, 'PX_DH_20260302_01_0001', NULL, NULL, 22400000.00, 22400000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-03-05', NULL, 'KH02 thanh toán PX_DH_20260302_01_0001', '2026-03-05 07:00:00', '2026-03-05 07:00:00'),
('TTX00006', 'xuat', NULL, 'PX_DH_20260311_01_0001', NULL, NULL, 6700000.00, 6700000.00, 0.00, 'da_du', 'tien_mat', '2026-03-15', NULL, 'KH01 thanh toán PX_DH_20260311_01_0001', '2026-03-15 07:00:00', '2026-03-15 07:00:00'),
('TTX00007', 'xuat', NULL, 'PX_DH_20260321_01_0001', NULL, NULL, 6850000.00, 4000000.00, 2850000.00, 'con_no', 'chuyen_khoan', '2026-03-25', NULL, 'KH003 TT 1 phần PX_DH_20260321_01_0001', '2026-03-25 07:00:00', '2026-03-25 07:00:00'),
('TTN00006', 'nhap', 'PN_20260220_0001', NULL, NULL, NULL, 9600000.00, 9600000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-02-25', NULL, 'TT đầy đủ PN_20260220_0001', '2026-02-25 07:00:00', '2026-02-25 07:00:00'),
('TTN00007', 'nhap', 'PN_20260301_0001', NULL, NULL, NULL, 20000000.00, 20000000.00, 0.00, 'da_du', 'tien_mat', '2026-03-05', NULL, 'TT đầy đủ PN_20260301_0001', '2026-03-05 07:00:00', '2026-03-05 07:00:00'),
('TTN00008', 'nhap', 'PN_20260315_0001', NULL, NULL, NULL, 14000000.00, 14000000.00, 0.00, 'da_du', 'chuyen_khoan', '2026-03-18', NULL, 'TT đầy đủ PN_20260315_0001', '2026-03-18 07:00:00', '2026-03-18 07:00:00'),
('TTN00009', 'nhap', 'PN_20260325_0001', NULL, NULL, NULL, 10200000.00, 200000.00, 10000000.00, 'con_no', 'Chuyển khoản', '2026-04-17', NULL, NULL, '2026-04-17 08:44:24', '2026-04-17 08:44:24'),
('TTX00008', 'xuat', NULL, 'PX_DH_20260418_01_0001', NULL, NULL, 425000.00, 0.00, 425000.00, 'con_no', NULL, '2026-04-18', NULL, NULL, '2026-04-18 01:03:16', '2026-04-18 01:03:16'),
('TTHTK00001', 'tra_hang', NULL, NULL, 'TH_20260418_080953', NULL, 255000.00, 255000.00, 0.00, 'da_du', 'Tiền mặt', '2026-05-02', 'payments/A3C1pS9XzpubPv61TSE97AgeNgyxHnDVvhFGukOu.jpg', 'chuyển đủ', '2026-05-02 03:31:50', '2026-05-02 03:31:50'),
('TTX00009', 'xuat', NULL, 'PX_DH_20260505_01_0001', NULL, NULL, 5500000.00, 0.00, 5500000.00, 'con_no', NULL, '2026-05-05', NULL, NULL, '2026-05-05 01:31:53', '2026-05-05 01:31:53'),
('TTHTNCC00001', 'tra_hang', NULL, NULL, NULL, 'PTNCC_20260505_0003', 2400000.00, 0.00, 2400000.00, 'con_no', NULL, '2026-05-05', NULL, 'Tiền NCC cần hoàn do trả hàng (PTNCC_20260505_0003)', '2026-05-05 06:12:36', '2026-05-05 06:12:36'),
('TTHTNCC00002', 'tra_hang', NULL, NULL, NULL, 'PTNCC_20260505_0003', 2000000.00, 0.00, 2000000.00, 'con_no', NULL, '2026-05-05', NULL, 'Tiền NCC cần hoàn do trả hàng (Phiếu PTNCC-20260505133115-FQCN)', '2026-05-05 06:31:19', '2026-05-05 06:31:19'),
('TTHTK00002', 'tra_hang', NULL, NULL, 'TH_20260310_100000', NULL, 450000.00, 50000.00, 400000.00, 'con_no', 'Chuyển khoản', '2026-05-05', NULL, 'Hoàn tiền đơn trả hàng TH_20260310_100000', '2026-05-05 06:36:48', '2026-05-05 06:36:48'),
('TTHTNCC00003', 'tra_hang', NULL, NULL, NULL, 'PTNCC_20260505_0002', 2400000.00, 2000000.00, 400000.00, 'con_no', 'Chuyển khoản', '2026-05-05', NULL, 'Nhận tiền hoàn từ NCC cho phiếu PTNCC-20260505131143-CKUN', '2026-05-05 06:39:28', '2026-05-05 06:39:28'),
('TTN00010', 'nhap', 'PN_20260325_0001', NULL, NULL, NULL, 10200000.00, 10000000.00, 0.00, 'da_du', 'Tiền mặt', '2026-05-05', NULL, NULL, '2026-05-05 06:42:47', '2026-05-05 06:42:47'),
('TTX00010', 'xuat', NULL, 'PX_DH_20260418_01_0001', NULL, NULL, 425000.00, 25000.00, 400000.00, 'con_no', 'Chuyển khoản', '2026-05-05', NULL, NULL, '2026-05-05 06:42:59', '2026-05-05 06:42:59'),
('TTHTNCC00004', 'tra_hang', NULL, NULL, NULL, 'PTNCC_20260505_0003', 2000000.00, 1000000.00, 1000000.00, 'con_no', 'Chuyển khoản', '2026-05-06', NULL, 'Nhận tiền hoàn từ NCC cho phiếu PTNCC_20260505_0003', '2026-05-06 09:19:45', '2026-05-06 09:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `thuoc`
--

DROP TABLE IF EXISTS `thuoc`;
CREATE TABLE IF NOT EXISTS `thuoc` (
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_thuoc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_nhom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_dvt` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguon_goc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thanh_phan` text COLLATE utf8mb4_unicode_ci,
  `ham_luong` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cong_dung` text COLLATE utf8mb4_unicode_ci,
  `cach_dung` text COLLATE utf8mb4_unicode_ci,
  `bao_quan` text COLLATE utf8mb4_unicode_ci,
  `chong_chi_dinh` text COLLATE utf8mb4_unicode_ci,
  `dang_bao_che` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia_ban_de_xuat` decimal(15,2) DEFAULT NULL,
  `image1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh thuốc 1',
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh thuốc 2',
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hình ảnh thuốc 3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_thuoc`),
  KEY `thuoc_ma_nhom_foreign` (`ma_nhom`),
  KEY `thuoc_ma_dvt_foreign` (`ma_dvt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thuoc`
--

INSERT INTO `thuoc` (`ma_thuoc`, `ten_thuoc`, `ma_nhom`, `ma_dvt`, `nguon_goc`, `thanh_phan`, `ham_luong`, `cong_dung`, `cach_dung`, `bao_quan`, `chong_chi_dinh`, `dang_bao_che`, `gia_ban_de_xuat`, `image1`, `image2`, `image3`, `created_at`, `updated_at`) VALUES
('TH0001', 'Hapacol 500mg', 'NT02', 'DVT01', 'Việt Nam', 'Paracetamol', '500mg', 'Giảm đau, hạ sốt', 'Uống sau bữa ăn', 'Nơi khô ráo', 'Mẫn cảm với Paracetamol', 'Viên nén', 50000.00, 'uploads/thuoc/1775925572_1.jpg', 'hapacol_2.jpg', 'hapacol_3.jpg', '2026-03-12 15:33:48', '2026-04-11 09:39:32'),
('TH0002', 'Klamentin 875/125', 'NT01', 'DVT01', 'Việt Nam', 'Amoxicillin, Clavulanic acid', '875mg/125mg', 'Kháng sinh trị nhiễm khuẩn', 'Uống trước bữa ăn', 'Dưới 30 độ C', 'Dị ứng Penicillin', 'Viên nén', 150000.00, 'uploads/thuoc/1775925593_1.jpg', 'klamentin_2.jpg', 'no_image.jpg', '2026-03-12 15:33:48', '2026-04-11 09:39:53'),
('TH0008', 'Paralmax 650mg', 'NT02', 'DVT01', 'Việt Nam', 'Paracetamol  650 mg', '650mg', 'Điều trị các triệu chứng đau từ nhẹ đến vừa, bao gồm: Đau đầu, đau nửa đầu, đau họng, đau răng, đau bụng kinh, làm giảm các triệu chứng đau nhức do cảm cúm. Hạ sốt.', 'Người lớn và trẻ em từ 12 tuổi trở lên: 1 - 1,5 viên/ngày, 3 – 4 lần/ngày.\r\n\r\nKhoảng cách giữa hai lần uống thuốc phải cách nhau ít nhất 4 giờ, không được vượt quá 6 viên/ngày.\r\nTrẻ em dưới 12 tuổi:\r\n\r\nPARALMAX 650 không được khuyến cáo cho trẻ em dưới 12 tuổi.', 'Bảo quản nơi khô, dưới 30°C, tránh ánh sáng.', 'Bệnh nhân quá mẫn với paracetamol hoặc bất cứ thành phần nào của thuốc.\r\nBệnh nhân bị suy gan nặng.', 'viên nén', 500000.00, 'uploads/thuoc/1775641798_1.png', '', '', '2026-04-08 02:49:58', '2026-04-08 02:49:58'),
('TH0004', 'Paracetamol 500mg', 'NT01', 'DVT01', 'Việt Nam', 'Paracetamol', '500mg', 'Giảm đau,  hạ sốt', 'Uống 1 viên/lần', 'Nơi khô ráo,  thoáng mát', 'Mẫn cảm với thành phần của thuốc', 'Viên nén', 25000.00, 'uploads/thuoc/1775925495_1.jpg', NULL, NULL, '2026-04-04 01:15:20', '2026-04-11 09:38:15'),
('TH0005', 'Amoxicillin 250mg', 'NT02', 'DVT02', 'Ấn Độ', 'Amoxicillin trihydrate', '250mg', 'Kháng sinh điều trị nhiễm khuẩn', 'Theo chỉ định của bác sĩ', 'Tránh ánh sáng trực tiếp', 'Người dị ứng Penicillin', 'Viên nang', 45000.00, 'uploads/thuoc/1775925523_1.jpg', NULL, NULL, '2026-04-04 01:15:20', '2026-04-11 09:38:43'),
('TH0006', 'Vitamin C 1000mg', 'NT03', 'DVT03', 'Mỹ', 'Ascorbic Acid', '1000mg', 'Tăng sức đề kháng', 'Hòa tan 1 viên vào nước uống', 'Đậy kín nắp', 'Sỏi thận', 'Viên sủi', 85000.00, 'uploads/thuoc/1775925131_1.jpg', NULL, NULL, '2026-04-04 01:15:20', '2026-04-11 09:32:11'),
('TH0007', 'Omeprazole 20mg', 'NT01', 'DVT02', 'Pháp', 'Omeprazole', '20mg', 'Trị viêm loét dạ dày', 'Uống trước bữa ăn 30 phút', 'Nhiệt độ phòng dưới 30 độ C', 'Phụ nữ có thai', 'Viên nang cứng', 120000.00, 'uploads/thuoc/1775925549_1.jpg', NULL, NULL, '2026-04-04 01:15:20', '2026-04-11 09:39:09'),
('TH0009', 'Test Thuốc', 'NT05', 'DVT06', 'Việt Nam', 'abc', 'cde', 'fgh', 'hjk', 'fghd', 'gds', 'Viên nang cứng', 100000.00, 'uploads/thuoc/1777988939_1.jpg', '', '', '2026-05-05 06:48:59', '2026-05-05 06:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `ton_kho`
--

DROP TABLE IF EXISTS `ton_kho`;
CREATE TABLE IF NOT EXISTS `ton_kho` (
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_san_xuat` date NOT NULL,
  `ngay_nhap_lo` date DEFAULT NULL,
  `han_su_dung` date NOT NULL,
  `so_luong_ton` int NOT NULL DEFAULT '0',
  `so_luong_da_xuat` int NOT NULL DEFAULT '0',
  `trang_thai_lo` enum('cho_duyet','dang_ban','het_han','ngung_ban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_duyet',
  `image1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hình ảnh tồn kho 1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ma_thuoc`,`ma_phieu_nhap`,`so_lo`),
  KEY `ton_kho_ma_phieu_nhap_foreign` (`ma_phieu_nhap`)
) ;

--
-- Dumping data for table `ton_kho`
--

INSERT INTO `ton_kho` (`ma_thuoc`, `ma_phieu_nhap`, `so_lo`, `ngay_san_xuat`, `ngay_nhap_lo`, `han_su_dung`, `so_luong_ton`, `so_luong_da_xuat`, `trang_thai_lo`, `image1`, `created_at`, `updated_at`) VALUES
('TH0001', 'PN_20260105_0001', 'SL_20260105_0001', '2025-06-01', '2026-01-05', '2028-06-01', 290, 210, 'dang_ban', '', '2026-04-17 08:23:17', '2026-05-05 01:31:53'),
('TH0004', 'PN_20260105_0001', 'SL_20260105_0002', '2025-05-15', '2026-01-05', '2028-05-15', 300, 100, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0002', 'PN_20260110_0001', 'SL_20260110_0001', '2025-07-01', '2026-01-10', '2028-07-01', 100, 50, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0005', 'PN_20260110_0001', 'SL_20260110_0002', '2025-08-01', '2026-01-10', '2028-08-01', 220, 80, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0006', 'PN_20260120_0001', 'SL_20260120_0001', '2025-09-01', '2026-01-20', '2028-09-01', 55, 45, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-18 01:03:16'),
('TH0007', 'PN_20260120_0001', 'SL_20260120_0002', '2025-04-01', '2026-01-20', '2028-04-01', 30, 20, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0008', 'PN_20260201_0001', 'SL_20260201_0001', '2025-10-01', '2026-02-01', '2028-10-01', 150, 50, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0002', 'PN_20260201_0001', 'SL_20260201_0002', '2025-11-01', '2026-02-01', '2028-11-01', 80, 20, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0001', 'PN_20260210_0001', 'SL_20260210_0001', '2025-12-01', '2026-02-10', '2028-12-01', 250, 50, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0004', 'PN_20260210_0001', 'SL_20260210_0002', '2025-11-15', '2026-02-10', '2028-11-15', 380, 60, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0005', 'PN_20260220_0001', 'SL_20260220_0001', '2026-01-01', '2026-02-20', '2029-01-01', 170, 30, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0006', 'PN_20260220_0001', 'SL_20260220_0002', '2025-12-15', '2026-02-20', '2028-12-15', 65, 15, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0007', 'PN_20260220_0001', 'SL_20260220_0003', '2026-01-10', '2026-02-20', '2029-01-10', 10, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0001', 'PN_20260301_0001', 'SL_20260301_0001', '2026-01-15', '2026-03-01', '2029-01-15', 520, 80, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0008', 'PN_20260301_0001', 'SL_20260301_0002', '2026-02-01', '2026-03-01', '2029-02-01', 200, 30, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0005', 'PN_20260310_0001', 'SL_20260310_0001', '2026-02-15', '2026-03-10', '2029-02-15', 270, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0007', 'PN_20260315_0001', 'SL_20260315_0001', '2026-03-01', '2026-03-15', '2029-03-01', 60, 20, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0004', 'PN_20260315_0001', 'SL_20260315_0002', '2026-02-20', '2026-03-15', '2029-02-20', 560, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0006', 'PN_20260325_0001', 'SL_20260325_0001', '2026-03-10', '2026-03-25', '2029-03-10', 120, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0002', 'PN_20260325_0001', 'SL_20260325_0002', '2026-03-05', '2026-03-25', '2029-03-05', 42, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0001', 'PN_20260401_0001', 'SL_20260401_0001', '2026-03-20', '2026-04-01', '2029-03-20', 400, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0008', 'PN_20260401_0001', 'SL_20260401_0002', '2026-03-25', '2026-04-01', '2029-03-25', 200, 0, 'dang_ban', '', '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
('TH0005', 'PN_20260410_0001', 'SL_20260410_0001', '2026-04-01', '2026-04-17', '2029-04-01', 180, 0, 'cho_duyet', '', '2026-04-17 09:09:14', '2026-04-17 09:22:39'),
('TH0006', 'PN_20260410_0001', 'SL_20260410_0002', '2026-04-05', '2026-04-17', '2029-04-05', 90, 0, 'cho_duyet', '', '2026-04-17 09:09:14', '2026-04-17 09:23:20'),
('TH0006', 'PN_20260417_0001', 'SL_20260417_0001', '2024-12-30', '2026-04-17', '2026-05-30', 4, 0, 'dang_ban', 'uploads/batches/1776445033_TH0006_1.jpg', '2026-04-17 09:47:15', '2026-05-05 06:14:21'),
('TH0001', 'PN_20260418_0001', 'SL_20260418_0001', '2024-12-30', '2026-04-18', '2026-12-15', 10, 0, 'cho_duyet', 'uploads/batches/1776533271_lot_TH0001_SL_20260418_0001.jpg', '2026-04-18 00:58:52', '2026-04-18 10:28:03'),
('TH0001', 'PN_20260418_0002', 'SL_20260418_0002', '2025-12-15', '2026-04-30', '2026-12-12', 3, 0, 'dang_ban', 'uploads/batches/1776533980_lot_TH0001_SL_20260418_0002.jpg', '2026-04-18 10:38:59', '2026-05-05 06:31:19'),
('TH0007', 'PN_20260430_0001', 'SL_20260430_0001', '2025-12-15', '2026-04-30', '2026-06-30', 0, 0, 'ngung_ban', 'uploads/batches/1777542781_lot_TH0007_SL_20260430_0001.webp', '2026-04-30 02:51:41', '2026-05-05 06:07:56'),
('TH0006', 'PN_TRA_20260502_0003', 'SL_20260120_0001', '2025-09-01', '2026-05-02', '2028-09-01', 1, 0, 'cho_duyet', '', '2026-05-02 02:00:44', '2026-05-05 02:48:25'),
('TH0001', 'PN_20260505_0001', 'SL_20260505_0001', '2024-01-01', '2026-05-05', '2028-01-01', 0, 0, 'cho_duyet', '', '2026-05-05 00:53:29', '2026-05-05 00:53:29'),
('TH0005', 'PN_TRA_20260505_0001', 'SL_20260220_0001', '2026-01-01', '2026-05-05', '2029-01-01', 10, 0, 'cho_duyet', '', '2026-05-05 06:34:48', '2026-05-05 06:35:13'),
('TH0009', 'PN_20260505_0002', 'SL_20260505_0002', '2025-12-11', '2026-05-05', '2026-05-28', 6, 0, 'dang_ban', 'uploads/batches/1777989124_lot_TH0009_SL_20260505_0002.jpg', '2026-05-05 06:49:59', '2026-05-05 06:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `ton_kho_khu_vuc`
--

DROP TABLE IF EXISTS `ton_kho_khu_vuc`;
CREATE TABLE IF NOT EXISTS `ton_kho_khu_vuc` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ma_thuoc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_phieu_nhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_lo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_khu_vuc` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ton_kho_khu_vuc_ma_thuoc_ma_phieu_nhap_so_lo_foreign` (`ma_thuoc`,`ma_phieu_nhap`,`so_lo`),
  KEY `ton_kho_khu_vuc_ma_khu_vuc_foreign` (`ma_khu_vuc`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ton_kho_khu_vuc`
--

INSERT INTO `ton_kho_khu_vuc` (`id`, `ma_thuoc`, `ma_phieu_nhap`, `so_lo`, `ma_khu_vuc`, `so_luong`, `created_at`, `updated_at`) VALUES
(1, 'TH0001', 'PN_20260105_0001', 'SL_20260105_0001', 'KV03_THANH_PHAM', 290, '2026-04-17 08:23:17', '2026-05-05 01:31:53'),
(2, 'TH0004', 'PN_20260105_0001', 'SL_20260105_0002', 'KV03_THANH_PHAM', 300, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(3, 'TH0002', 'PN_20260110_0001', 'SL_20260110_0001', 'KV03_THANH_PHAM', 100, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(4, 'TH0005', 'PN_20260110_0001', 'SL_20260110_0002', 'KV03_THANH_PHAM', 220, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(5, 'TH0006', 'PN_20260120_0001', 'SL_20260120_0001', 'KV03_THANH_PHAM', 55, '2026-04-17 08:23:17', '2026-04-18 01:03:16'),
(6, 'TH0007', 'PN_20260120_0001', 'SL_20260120_0002', 'KV03_THANH_PHAM', 30, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(7, 'TH0008', 'PN_20260201_0001', 'SL_20260201_0001', 'KV03_THANH_PHAM', 150, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(8, 'TH0002', 'PN_20260201_0001', 'SL_20260201_0002', 'KV03_THANH_PHAM', 80, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(9, 'TH0001', 'PN_20260210_0001', 'SL_20260210_0001', 'KV03_THANH_PHAM', 250, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(10, 'TH0004', 'PN_20260210_0001', 'SL_20260210_0002', 'KV03_THANH_PHAM', 380, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(11, 'TH0005', 'PN_20260220_0001', 'SL_20260220_0001', 'KV03_THANH_PHAM', 170, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(12, 'TH0006', 'PN_20260220_0001', 'SL_20260220_0002', 'KV03_THANH_PHAM', 65, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(13, 'TH0007', 'PN_20260220_0001', 'SL_20260220_0003', 'KV03_THANH_PHAM', 10, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(14, 'TH0001', 'PN_20260301_0001', 'SL_20260301_0001', 'KV03_THANH_PHAM', 520, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(15, 'TH0008', 'PN_20260301_0001', 'SL_20260301_0002', 'KV03_THANH_PHAM', 200, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(16, 'TH0005', 'PN_20260310_0001', 'SL_20260310_0001', 'KV03_THANH_PHAM', 270, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(17, 'TH0007', 'PN_20260315_0001', 'SL_20260315_0001', 'KV03_THANH_PHAM', 60, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(18, 'TH0004', 'PN_20260315_0001', 'SL_20260315_0002', 'KV03_THANH_PHAM', 560, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(19, 'TH0006', 'PN_20260325_0001', 'SL_20260325_0001', 'KV03_THANH_PHAM', 120, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(20, 'TH0002', 'PN_20260325_0001', 'SL_20260325_0002', 'KV03_THANH_PHAM', 42, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(21, 'TH0001', 'PN_20260401_0001', 'SL_20260401_0001', 'KV03_THANH_PHAM', 400, '2026-04-17 08:23:17', '2026-04-17 08:23:17'),
(24, 'TH0005', 'PN_20260410_0001', 'SL_20260410_0001', 'KV01_TIEP_NHAN', 180, '2026-04-17 09:09:14', '2026-04-17 09:36:16'),
(23, 'TH0008', 'PN_20260401_0001', 'SL_20260401_0002', 'KV03_THANH_PHAM', 200, '2026-04-17 08:55:41', '2026-04-17 08:55:41'),
(25, 'TH0006', 'PN_20260410_0001', 'SL_20260410_0002', 'KV01_TIEP_NHAN', 90, '2026-04-17 09:09:14', '2026-04-17 09:36:16'),
(42, 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV03_THANH_PHAM', 3, '2026-05-05 06:30:49', '2026-05-05 06:30:49'),
(27, 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV03_THANH_PHAM', 4, '2026-04-18 01:07:15', '2026-05-02 02:44:04'),
(28, 'TH0001', 'PN_20260418_0001', 'SL_20260418_0001', 'KV01_TIEP_NHAN', 10, '2026-04-18 10:27:51', '2026-04-18 10:28:03'),
(43, 'TH0005', 'PN_TRA_20260505_0001', 'SL_20260220_0001', 'KV04_CHO_XU_LY', 10, '2026-05-05 06:35:13', '2026-05-05 06:35:13'),
(41, 'TH0001', 'PN_20260418_0002', 'SL_20260418_0002', 'KV05_LOAI_BO', 5, '2026-05-05 06:29:33', '2026-05-05 06:29:33'),
(31, 'TH0006', 'PN_TRA_20260502_0003', 'SL_20260120_0001', 'KV04_CHO_XU_LY', 0, '2026-05-02 02:02:25', '2026-05-05 02:55:25'),
(35, 'TH0006', 'PN_20260417_0001', 'SL_20260417_0001', 'KV05_LOAI_BO', 3, '2026-05-05 02:00:21', '2026-05-05 06:14:21'),
(47, 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV04_CHO_XU_LY', 2, '2026-05-05 06:55:49', '2026-05-05 06:56:13'),
(46, 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV03_THANH_PHAM', 4, '2026-05-05 06:55:34', '2026-05-05 06:55:34'),
(48, 'TH0009', 'PN_20260505_0002', 'SL_20260505_0002', 'KV05_LOAI_BO', 2, '2026-05-05 06:56:13', '2026-05-05 06:56:13');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
