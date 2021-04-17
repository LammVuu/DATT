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
    Route::get("chitiet","IndexController@ChiTiet")->name("user/chi-tiet");
    Route::get("thanhtoan","IndexController@ThanhToan")->name("user/thanh-toan");
    Route::get("sanpham","IndexController@SanPham")->name("user/san-pham");
    Route::get("dangnhap","UserController@DangNhap")->name("user/dang-nhap");
    Route::get("dangky","UserController@DangKy")->name("user/dang-ky");
    Route::get("taikhoan","UserController@TaiKhoan")->name("user/tai-khoan");
    Route::get("giohang","CartController@GioHang")->name("user/gio-hang");
});
Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function() {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('hinhanh', HinhAnhController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('slideshow', SlideshowController::class);
    Route::resource('mausanpham', MauSanPhamController::class);
    Route::resource('sanpham', SanPhamController::class);
    Route::resource('nhacungcap', NhaCungCapController::class);
    Route::resource('danhgia', DanhGiaController::class);
    Route::resource('khuyenmai', KhuyenMaiController::class);
    Route::resource('donhang', DonHangController::class);
    Route::resource('baohanh', BaoHanhController::class);
    Route::resource('taikhoan', TaiKhoanController::class);
});
