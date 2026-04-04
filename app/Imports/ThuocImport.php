<?php

namespace App\Imports;

use App\Models\Thuoc;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Nhớ thêm dòng này

class ThuocImport implements ToModel, WithHeadingRow // Thêm implement WithHeadingRow
{
    private $currentNumber;

    public function __construct()
    {
        $latest = Thuoc::where('ma_thuoc', 'LIKE', 'TH%')
            ->orderByRaw('CAST(SUBSTRING(ma_thuoc, 3) AS UNSIGNED) DESC')
            ->first();

        if ($latest) {
            $this->currentNumber = (int) substr($latest->ma_thuoc, 2);
        } else {
            $this->currentNumber = 0;  
        }
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Lấy tên thuốc và hàm lượng (Dùng trim để cắt khoảng trắng thừa)
        $tenThuoc = trim($row['ten_thuoc'] ?? $row['ten_thuoc_'] ?? '');
        $hamLuong = trim($row['ham_luong'] ?? '');

        // Nếu file có dòng trống (không có tên thuốc), bỏ qua
        if (empty($tenThuoc)) {
            return null;
        }

        // 2. KIỂM TRA TRÙNG LẶP
        $thuocDaTonTai = Thuoc::where('ten_thuoc', $tenThuoc)
                              ->where('ham_luong', $hamLuong)
                              ->first();

        if ($thuocDaTonTai) {
            // NẾU ĐÃ CÓ (Trùng cả tên lẫn hàm lượng): Bỏ qua
            return null;
        }

        // 3. NẾU CHƯA CÓ: Tiến hành tăng số đếm và tạo mã TH... mới
        $this->currentNumber++;
        $ma_thuoc = 'TH' . str_pad($this->currentNumber, 4, '0', STR_PAD_LEFT);

        // Lưu vào cơ sở dữ liệu
        return new Thuoc([
            'ma_thuoc'        => $ma_thuoc,
            'ten_thuoc'       => $tenThuoc,
            'ma_nhom'         => trim($row['ma_nhom'] ?? ''),
            'ma_dvt'          => trim($row['ma_dvt'] ?? ''),
            'nguon_goc'       => $row['nguon_goc'] ?? null,
            'thanh_phan'      => $row['thanh_phan'] ?? null,
            'ham_luong'       => $hamLuong,
            'cong_dung'       => $row['cong_dung'] ?? null,
            'cach_dung'       => $row['cach_dung'] ?? null,
            'bao_quan'        => $row['bao_quan'] ?? null,
            'chong_chi_dinh'  => $row['chong_chi_dinh'] ?? null,
            'dang_bao_che'    => $row['dang_bao_che'] ?? null,
            'gia_ban_de_xuat' => $row['gia_ban_de_xuat'] ?? 0,
        ]);
    }
}