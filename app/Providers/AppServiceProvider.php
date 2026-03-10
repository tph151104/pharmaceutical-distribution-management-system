<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// THÊM DÒNG NÀY VÀO ĐÂY:
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191); //fix Lỗi này rất phổ biến trong Laravel khi bạn sử dụng phiên bản MySQL cũ (thấp hơn 5.7.7) hoặc MariaDB (thấp hơn 10.2.2).
    }
}
