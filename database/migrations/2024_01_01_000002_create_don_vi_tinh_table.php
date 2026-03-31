<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('don_vi_tinh', function (Blueprint $table) {
            $table->string('ma_dvt', 50)->primary();
            $table->string('ten_dvt', 100);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('don_vi_tinh');
    }
};
