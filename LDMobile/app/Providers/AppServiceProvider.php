<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NHACUNGCAP;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
        // check facebook login
        


        // supplier
        $lst_brand = [];
        $i = 0;

        foreach(NHACUNGCAP::all() as $key){
            $lst_brand[$i]['brand'] = explode(' ', $key->tenncc)[0];
            $lst_brand[$i]['image'] = $key->anhdaidien;
            $i++;
        }

        // url
        View::share('lst_brand', $lst_brand);
        View::share('url_phone', 'images/phone/');
        View::share('url_logo', 'images/logo/');
        View::share('url_slide', 'images/slideshow/');
        View::share('url_banner', 'images/banner/');
        View::share('url_json', 'json/');
        View::share('url_model_slide', 'images/phone/slideshow/');
        View::share('url_user', 'images/user/');
    }
}
