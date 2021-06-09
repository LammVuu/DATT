<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;

use App\Http\Controllers\user\IndexController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\UserController;

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
    Route::get("/",[IndexController::class, 'Index'])->name("user/index");

    Route::get("thanhtoan", [IndexController::class, 'ThanhToan'])->name("user/thanh-toan");

    Route::get("dienthoai", [IndexController::class, 'DienThoai'])->name("user/dien-thoai");

    Route::get('dienthoai/{id}', [IndexController::class, 'ChiTiet'])->name('user/chi-tiet');
    
    Route::get("dangnhap", [UserController::class, 'DangNhap'])->name("user/dang-nhap");
    
    Route::get("dangky", [UserController::class, 'DangKy'])->name("user/dang-ky");
    
    Route::get("taikhoan", [UserController::class, 'TaiKhoan'])->name("user/tai-khoan");
    
    Route::get("taikhoan/thongbao", [UserController::class, 'ThongBao'])->name("user/tai-khoan-thong-bao");
    
    Route::get("taikhoan/donhang", [UserController::class, 'DonHang'])->name("user/tai-khoan-don-hang");

    Route::get("taikhoan/diachi", [userController::class, 'DiaChi'])->name("user/tai-khoan-dia-chi");
    
    Route::get("taikhoan/chitietdonhang", [UserController::class, 'ChiTietDonHang'])->name("user/tai-khoan-chi-tiet-don-hang");
    
    Route::get("taikhoan/yeuthich", [UserController::class, 'YeuThich'])->name("user/tai-khoan-yeu-thich");
    
    Route::get("taikhoan/voucher", [UserController::class, 'Voucher'])->name("user/tai-khoan-voucher");
    
    Route::get("giohang", [CartController::class, 'GioHang'])->name("user/gio-hang");

    route::get("sosanh", [IndexController::class, 'SoSanh'])->name('user/so-sanh');

    route::get("ketquathanhtoan", [IndexController::class, "ketQuaThanhToan"]);

    route::get("thanhcong", [IndexController::class, 'ThanhCong']);

    route::get('tracuu', [IndexController::class, 'TraCuu'])->name('user/tra-cuu');

    route::get('lienhe', [IndexController::class, 'LienHe'])->name('user/lien-he');

    route::get('vechungtoi', [IndexController::class, 'VeChungToi'])->name('user/ve-chung-toi');

    route::post('test', 'IndexController@test');

    route::post('test2', 'IndexController@test2');

    route::post('test3', 'IndexController@test3')->name('user/test3');

    route::post('test4', 'IndexController@test4');

    route::get('test5', 'IndexController@test5');
    
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

/*
GET	    /product	        		index	product.index
GET	    /product/create	    		create	product.create
POST	/product					store	product.store
GET		/product/{product}			show	product.show
GET		/product/{product}/edit		edit	product.edit
PUT/PATCH	/product/{product}		update	product.update
DELETE	/ product/{product}			destroy	product.destroy
*/

