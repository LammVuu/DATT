<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\UserComposer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\TAIKHOAN;

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
        View::composer('*', function($view){
            if(Auth::check()){
                $user = Auth::user();
                if(!session('user')){
                    session(['user' => $user]);
                }
            } else {
                $socialAccount = TAIKHOAN::where('login_status', 1)->first();

                if($socialAccount && $socialAccount->htdn == 'facebook'){
                    if(!session('login_status')){
                        $token = $socialAccount->user_social_token;
                        $app_id = '186623080040346';
                        $secrect_id = '778d952e526d81b40ec3fef4da5cf0c0';
        
                        // kiểm tra token còn hợp lệ không
                        $validToken = Http::get("https://graph.facebook.com/debug_token?input_token={$token}&access_token={$app_id}|{$secrect_id}")->json();
        
                        // không hợp lệ
                        if(array_key_first($validToken) == 'error'){
                            Session::flush();
                            Session::flash('login_status', false);
                            $socialAccount->login_status = 0;
                            $socialAccount->save();
                        } 
                        // hợp lệ
                        else {
                            Auth::login($socialAccount);
                            session(['user' => $socialAccount]);
                        }
                    }
                }
            }
        });

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
            'user.content.thanh-toan',
            'user.content.dia-chi-giao-hang'
        ], UserComposer::class);
    }
}
