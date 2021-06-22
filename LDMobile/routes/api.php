<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    //
});
Route::group(['namespace' => 'API','middleware'=>'auth:api'],function (){
    Route::get('total-product-in-cart/{id}','CartController@getTotalProductInCart');
    Route::get('supplier','SanPhamController@getSupplier');
    Route::get('slideshow','SanPhamController@getSlideshow');
    Route::get('hotsale','SanPhamController@getHotSale');
    Route::get('featured-product','SanPhamController@getFeaturedProduct');
    Route::get('related-product/{id}','SanPhamController@getRelatedProduct');
    Route::get('detail-product/{id}','SanPhamController@getDetailProduct');
    Route::get('compare-product/{id}','SanPhamController@getCompareProduct');
    Route::get('ram-storage','SanPhamController@getRamAndStorage');
    Route::get('all-product','SanPhamController@getAllProduct');
    Route::get('filter-product','SanPhamController@getProductFilter');
    Route::get('banner','SanPhamController@getBanner');
    Route::get('slideshow-product/{id}','SanPhamController@getSlideShowOfProduct');
    Route::get('change-color-storage/{id}','SanPhamController@changeColorOrStorageProduct');
    Route::get('my-cart/{id}','CartController@getMyCart');
    Route::get('total-product-in-cart/{id}','CartController@getTotalProductInCart');
    Route::post('add-to-cart/{id}','CartController@addToCart');
    Route::put('update-cart/{id}','CartController@updateCart');
    Route::delete('delete-product-in-cart/{id}','CartController@deleteProductInCart');
    Route::get('my-voucher/{id}', 'CartController@getMyVoucher');
    Route::delete('delete-voucher/{id}', 'CartController@deleteMyVoucher');
    Route::post('create-order/{id}','CartController@createOrder');
    Route::get('province-store', 'CartController@getProvinceStore');
    Route::get('address-store/{id}', 'CartController@getAddressStore');

    Route::put('my-address/{id}','CartController@updateMyAddress');
    Route::post('my-address','CartController@createMyAddress');
    Route::get('my-address/{id}','CartController@getMyAddress');
    Route::delete('my-address/{id}','CartController@deleteMyAddress');
    Route::get('id-address','CartController@getID');
    Route::get('city','CartController@getProvince');
    Route::get('district','CartController@getDistrict');
    Route::get('ward','CartController@getWard');

    Route::get('total-notification/{id}','UserController@getTotalNotification');
    Route::get('notification/{id}','UserController@getNotification');
    Route::put('notification/{id}','UserController@updateNotification');
    Route::delete('notification/{id}','UserController@deleteNotification');
    Route::delete('log-out','UserController@logout');
    Route::post('change-avatar/{id}', 'UserController@changeAvatar');
    Route::put('change-info/{id}','UserController@changeInfoUser');
    Route::post('check-password','UserController@checkPassword');
});
Route::post('check-number-phone','API\UserController@checkNumberPhone');
Route::post('log-in','API\UserController@login');
Route::post('sign-up','API\UserController@signup');
Route::put('change-password', 'API\UserController@changePassword');