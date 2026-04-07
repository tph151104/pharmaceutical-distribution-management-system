<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->string('minh_chung_image', 255)->nullable()->after('ghi_chu_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->dropColumn('minh_chung_image');
        });
    }
};
