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
        Schema::table('don_hang', function (Blueprint $table) {
            $table->string('nguoi_huy', 50)->nullable()->after('nguoi_duyet');
            $table->string('ly_do_huy', 500)->nullable()->after('nguoi_huy');
        });
    }

    public function down()
    {
        Schema::table('don_hang', function (Blueprint $table) {
            $table->dropColumn(['nguoi_huy', 'ly_do_huy']);
        });
    }
};
