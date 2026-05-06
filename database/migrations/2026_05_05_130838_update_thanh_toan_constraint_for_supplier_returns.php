<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE thanh_toan DROP CONSTRAINT chk_phieu_thanh_toan');
        DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_phieu_thanh_toan CHECK (ma_phieu_nhap IS NOT NULL OR ma_phieu_xuat IS NOT NULL OR ma_tra_hang IS NOT NULL OR ma_phieu_tra_ncc IS NOT NULL)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE thanh_toan DROP CONSTRAINT chk_phieu_thanh_toan');
        DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_phieu_thanh_toan CHECK (ma_phieu_nhap IS NOT NULL OR ma_phieu_xuat IS NOT NULL OR ma_tra_hang IS NOT NULL)');
    }
};
