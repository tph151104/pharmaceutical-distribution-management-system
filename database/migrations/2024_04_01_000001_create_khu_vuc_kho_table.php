<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('khu_vuc_kho', function (Blueprint $table) {
            $table->string('ma_khu_vuc', 50)->primary();
            $table->string('ten_khu_vuc', 255);
            $table->enum('loai_khu_vuc', ['tiep_nhan', 'biet_tru', 'san_sang', 'tra_ve', 'loai_bo']);
            $table->text('mo_ta')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('khu_vuc_kho');
    }
};
