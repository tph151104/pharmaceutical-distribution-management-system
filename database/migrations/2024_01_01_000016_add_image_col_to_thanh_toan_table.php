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
        Schema::table('thanh_toan', function (Blueprint $table) {
            if (!Schema::hasColumn('thanh_toan', 'giay_phep_tt_image')) {
                $table->string('giay_phep_tt_image', 255)->nullable()->after('ngay_thanh_toan');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->dropColumn('giay_phep_tt_image');
        });
    }
};
