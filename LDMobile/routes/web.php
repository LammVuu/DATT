<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => '', 'namespace' => 'user'], function() {
    Route::get("/","IndexController@Index")->name("user/index");
    Route::get("detail","IndexController@Detail")->name("user/detail");
    Route::get("checkout","IndexController@Checkout")->name("user/checkout");
    Route::get("shop","IndexController@Shop")->name("user/shop");
    Route::get("login","UserController@Login")->name("user/login");
    Route::get("signup","UserController@Signup")->name("user/signup");
    Route::get("account","UserController@Account")->name("user/account");
    Route::get("cart","CartController@MyCart")->name("user/MyCart");
});
// Route::get('/', function () {
//     return view('user/content/index');
   
// });
// Route::get('/detail', function () {
//     return view('user/content/detail_product');
   
// });
