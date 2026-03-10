<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nhom_thuoc', function (Blueprint $table) {
            $table->string('ma_nhom', 50)->primary();
            $table->string('ten_nhom', 255);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nhom_thuoc');
    }
};
