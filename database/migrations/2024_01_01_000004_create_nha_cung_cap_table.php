<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nha_cung_cap', function (Blueprint $table) {
            $table->string('ma_ncc', 50)->primary();
            $table->string('ten_ncc', 255);
            $table->string('dia_chi', 255)->nullable();
            $table->string('dien_thoai', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('ma_so_thue', 50)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nha_cung_cap');
    }
};
