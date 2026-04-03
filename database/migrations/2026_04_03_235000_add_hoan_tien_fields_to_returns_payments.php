<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 1. Thêm cột trang_thai_hoan_tien vào khach_tra_hang
     * 2. Thêm cột ma_tra_hang vào thanh_toan
     * 3. Mở rộng enum loai_thanh_toan thêm 'tra_hang'
     */
    public function up(): void
    {
        // 1. Thêm trạng thái hoàn tiền vào bảng khach_tra_hang
        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->enum('trang_thai_hoan_tien', ['chua_hoan', 'mot_phan', 'da_hoan'])
                  ->default('chua_hoan')
                  ->after('trang_thai');
        });

        // 2. Thêm cột liên kết đơn trả hàng vào bảng thanh_toan
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->string('ma_tra_hang', 50)->nullable()->after('ma_phieu_xuat');
            $table->foreign('ma_tra_hang')
                  ->references('ma_tra_hang')
                  ->on('khach_tra_hang')
                  ->onDelete('restrict');
        });

        // 3. Mở rộng enum loai_thanh_toan để thêm 'tra_hang'
        // MySQL: cần dùng raw ALTER TABLE để thay đổi ENUM
        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN loai_thanh_toan ENUM('nhap', 'xuat', 'tra_hang') NOT NULL");

        // 4. Cập nhật constraint: cho phép ma_tra_hang thay thế phiếu nhập/xuất
        // Xóa constraint cũ và tạo lại
        try {
            DB::statement('ALTER TABLE thanh_toan DROP CONSTRAINT chk_phieu_thanh_toan');
        } catch (\Exception $e) {
            // Bỏ qua nếu constraint không tồn tại (MariaDB có thể dùng tên khác)
        }
        DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_phieu_thanh_toan CHECK (ma_phieu_nhap IS NOT NULL OR ma_phieu_xuat IS NOT NULL OR ma_tra_hang IS NOT NULL)');
    }

    public function down(): void
    {
        // Rollback constraint
        try {
            DB::statement('ALTER TABLE thanh_toan DROP CONSTRAINT chk_phieu_thanh_toan');
            DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_phieu_thanh_toan CHECK (ma_phieu_nhap IS NOT NULL OR ma_phieu_xuat IS NOT NULL)');
        } catch (\Exception $e) {}

        // Rollback enum
        DB::statement("ALTER TABLE thanh_toan MODIFY COLUMN loai_thanh_toan ENUM('nhap', 'xuat') NOT NULL");

        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->dropForeign(['ma_tra_hang']);
            $table->dropColumn('ma_tra_hang');
        });

        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->dropColumn('trang_thai_hoan_tien');
        });
    }
};
