<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_toggles', function (Blueprint $table) {
            $table->string('ma_chuc_nang', 50)->primary();
            $table->string('ten_chuc_nang');
            $table->text('mo_ta')->nullable();
            $table->boolean('trang_thai')->default(true); // true = hoat_dong, false = bao_tri
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_toggles');
    }
};
