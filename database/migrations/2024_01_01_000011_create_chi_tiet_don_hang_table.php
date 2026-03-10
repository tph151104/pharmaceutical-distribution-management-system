<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_don_hang', function (Blueprint $table) {
            $table->string('ma_don_hang', 50);
            $table->string('ma_thuoc', 50);
            $table->integer('so_luong');
            $table->decimal('don_gia', 15, 2);
            $table->timestamps();

            $table->primary(['ma_don_hang', 'ma_thuoc']);
            $table->foreign('ma_don_hang')->references('ma_don_hang')->on('don_hang')->onDelete('cascade');
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('restrict');
        });

        DB::statement('ALTER TABLE chi_tiet_don_hang ADD CONSTRAINT chk_sl_don_hang CHECK (so_luong > 0)');
        DB::statement('ALTER TABLE chi_tiet_don_hang ADD CONSTRAINT chk_don_gia_dh CHECK (don_gia >= 0)');
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_don_hang');
    }
};
