<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\UserComposer;
use Illuminate\Support\Facades\View;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'user.header.header',
            'user.content.tai-khoan',
            'user.content.taikhoan.sec-chi-tiet-don-hang',
            'user.content.taikhoan.sec-dia-chi',
            'user.content.taikhoan.sec-don-hang',
            'user.content.taikhoan.sec-tai-khoan',
            'user.content.taikhoan.sec-thanh-chuc-nang',
            'user.content.taikhoan.sec-thong-bao',
            'user.content.taikhoan.voucher',
            'user.content.taikhoan.yeu-thich',
            'user.content.gio-hang',
            'user.content.thanh-toan'
        ], UserComposer::class);
    }
}
