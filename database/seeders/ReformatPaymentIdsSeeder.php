<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThanhToan;
use Illuminate\Support\Facades\DB;

class ReformatPaymentIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cập nhật lại toàn bộ mã giao dịch (ma_thanh_toan) về định dạng mới đồng bộ.
     */
    public function run()
    {
        // Tắt check khóa ngoại để update PK
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        echo "Bắt đầu định dạng lại mã giao dịch...\n";

        // 1. Phải trả (Nhập kho) - TTNxxxxx
        $this->reformatByCriteria(['loai_thanh_toan' => 'nhap'], 'TTN');

        // 2. Phải thu (Xuất kho) - TTXxxxxx
        $this->reformatByCriteria(['loai_thanh_toan' => 'xuat'], 'TTX');

        // 3. Hoàn trả cho NCC - TTHTNCCxxxxx
        $this->reformatByReturnCriteria('ma_phieu_tra_ncc', 'TTHTNCC');

        // 4. Hoàn trả cho Khách hàng - TTHTKxxxxx
        $this->reformatByReturnCriteria('ma_tra_hang', 'TTHTK');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo "Hoàn thành định dạng lại mã giao dịch.\n";
    }

    private function reformatByCriteria($criteria, $prefix)
    {
        $records = ThanhToan::where($criteria)
            ->whereNull('ma_phieu_tra_ncc')
            ->whereNull('ma_tra_hang')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Bước 1: Chuyển hết sang mã tạm để tránh trùng PK khi đổi chéo
        foreach ($records as $record) {
            DB::table('thanh_toan')->where('ma_thanh_toan', $record->ma_thanh_toan)->update(['ma_thanh_toan' => 'TEMP_' . $record->ma_thanh_toan]);
        }

        // Bước 2: Đổi về mã chuẩn
        $count = 0;
        foreach ($records as $index => $record) {
            $newId = $prefix . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            DB::table('thanh_toan')->where('ma_thanh_toan', 'TEMP_' . $record->ma_thanh_toan)->update(['ma_thanh_toan' => $newId]);
            $count++;
        }
        echo " - Đã cập nhật $count bản ghi loại $prefix.\n";
    }

    private function reformatByReturnCriteria($foreignKey, $prefix)
    {
        $records = ThanhToan::whereNotNull($foreignKey)
            ->orderBy('created_at', 'asc')
            ->get();

        // Bước 1: Chuyển hết sang mã tạm
        foreach ($records as $record) {
            DB::table('thanh_toan')->where('ma_thanh_toan', $record->ma_thanh_toan)->update(['ma_thanh_toan' => 'TEMP_' . $record->ma_thanh_toan]);
        }

        // Bước 2: Đổi về mã chuẩn
        $count = 0;
        foreach ($records as $index => $record) {
            $newId = $prefix . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            DB::table('thanh_toan')->where('ma_thanh_toan', 'TEMP_' . $record->ma_thanh_toan)->update(['ma_thanh_toan' => $newId]);
            $count++;
        }
        echo " - Đã cập nhật $count bản ghi loại $prefix.\n";
    }
}
