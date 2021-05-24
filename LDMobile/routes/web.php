<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;

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
    
    Route::get("taikhoan/thongbao","UserController@ThongBao")->name("user/tai-khoan-thong-bao");
    
    Route::get("taikhoan/donhang","UserController@DonHang")->name("user/tai-khoan-don-hang");

    Route::get("taikhoan/diachi","UserController@DiaChi")->name("user/tai-khoan-dia-chi");
    
    Route::get("taikhoan/chitietdonhang","UserController@ChiTietDonHang")->name("user/tai-khoan-chi-tiet-don-hang");
    
    Route::get("taikhoan/yeuthich","UserController@YeuThich")->name("user/tai-khoan-yeu-thich");
    
    Route::get("taikhoan/voucher","UserController@Voucher")->name("user/tai-khoan-voucher");
    
    Route::get("giohang","CartController@GioHang")->name("user/gio-hang");

    route::get("sosanh", "IndexController@SoSanh")->name('user/so-sanh');

    route::get("thanhcong", "IndexController@ThanhCong");

    route::get('tracuu', 'IndexController@TraCuu')->name('user/tra-cuu');

    route::get('lienhe', 'IndexController@LienHe')->name('user/lien-he');

    route::get('vechungtoi', 'IndexController@VeChungToi')->name('user/ve-chung-toi');

    route::post('test', 'IndexController@test');

    route::post('test2', 'IndexController@test2');

    route::post('test3', 'IndexController@test3')->name('user/test3');
    
});

Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('admin/dashboard');

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
