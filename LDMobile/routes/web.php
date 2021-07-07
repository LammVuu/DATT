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
Route::group(["prefix" => "", "namespace" => "user"], function() {

    /*=======================================================================================================
                                                        Page
    =========================================================================================================*/
    
    Route::get("dangnhap", [UserController::class, "DangNhap"])->name("user/dang-nhap");
    
    Route::get("dangky", [UserController::class, "DangKy"])->name("user/dang-ky");

    Route::get("auth/facebook/redirect", [UserController::class, "FacebookRedirect"])->name("user/facebook-redirect");

    Route::get("auth/facebook/callback", [UserController::class, "FacebookCallback"]);

    Route::get("auth/google/redirect", [UserController::class, "GoogleRedirect"])->name("user/google-redirect");

    Route::get("auth/google/callback", [UserController::class, "GoogleCallback"]);

    Route::post("signup", [UserController::class, "SignUp"])->name("user/signup");

    Route::post("login", [UserController::class, "Login"])->name("user/login");

    Route::get("logout", [UserController::class, "LogOut"])->name("user/logout");

    Route::get("/",[IndexController::class, "Index"])->name("user/index");

    Route::get("dienthoai", [IndexController::class, "DienThoai"])->name("user/dien-thoai");

    Route::get("timkiem/{name?}", [IndexController::class, "TimKiemDienThoai"])->name("user/tim-kiem");

    Route::get("dienthoai-{brand}", [IndexController::class, "DienThoaiTheoHang"])->name("user/dien-thoai-theo-hang");

    Route::get("dienthoai/{name}", [IndexController::class, "ChiTiet"])->name("user/chi-tiet");

    route::get("sosanh/{str}", [IndexController::class, "SoSanh"])->name("user/so-sanh");

    route::get("thanhcong", [CartController::class, "ThanhCong"])->name("user/thanhcong");

    route::get("tracuu", [IndexController::class, "TraCuu"])->name("user/tra-cuu");

    route::get("lienhe", [IndexController::class, "LienHe"])->name("user/lien-he");

    /*=======================================================================================================
                                                        Ajax
    =========================================================================================================*/

    Route::get("ajax-forget-login-status-session", [IndexController::class, "AjaxForgetLoginStatusSession"]);

    Route::post("ajax-search-phone", [IndexController::class, "AjaxSearchPhone"]);

    Route::post("ajax-filter-product", [IndexController::class, "AjaxFilterProduct"]);

    Route::post("ajax-choose-color", [IndexController::class, "AjaxChooseColor"]);

    Route::post("ajax-get-qty-in-stock", [IndexController::class, "AjaxGetQtyInStock"]);

    Route::post("ajax-add-delete-favorite", [UserController::class, "AjaxAddDeleteFavorite"]);

    Route::post("ajax-check-qty-in-stock", [IndexController::class, "AjaxCheckQtyInStock"]);

    Route::post("ajax-add-cart", [CartController::class, "AjaxAddCart"]);

    Route::post("ajax-buy-now", [CartController::class, "AjaxBuyNow"]);

    Route::get("ajax-remove-all-cart", [CartController::class, "AjaxRemoveAllCart"]);

    Route::post("ajax-remove-cart-item", [CartController::class, "AjaxRemoveCartItem"]);

    Route::post("ajax-update-cart", [CartController::class, "AjaxUpdateCart"]);

    Route::post("ajax-like-comment", [UserController::class, "AjaxLikeComment"]);

    route::post("ajax-change-location", [IndexController::class, "AjaxChangeLocation"]);

    Route::post("ajax-check-imei", [IndexController::class, "AjaxCheckImei"]);

    route::post("zalopay/callback", [IndexController::class, "ZaloPayCallback"]);

    route::get("test5", "IndexController@test5");

    Route::group(["middleware" => "CheckLogin"], function(){
        /*=======================================================================================================
                                                        Page
        =========================================================================================================*/

        Route::get("giohang", [CartController::class, "GioHang"])->name("user/gio-hang");
        
        Route::get("thanhtoan", [CartController::class, "ThanhToan"])->name("user/thanh-toan");

        Route::get("diachigiaohang", [UserController::class, "DiaChiGiaoHang"]);

        Route::get("taikhoan", [UserController::class, "TaiKhoan"])->name("user/tai-khoan");

        Route::get("taikhoan/thongbao", [UserController::class, "ThongBao"])->name("user/tai-khoan-thong-bao");
            
        Route::get("taikhoan/donhang", [UserController::class, "DonHang"])->name("user/tai-khoan-don-hang");
    
        Route::get("taikhoan/diachi", [userController::class, "DiaChi"])->name("user/tai-khoan-dia-chi");
        
        Route::get("taikhoan/donhang/{id}", [UserController::class, "ChiTietDonHang"])->name("user/tai-khoan-chi-tiet-don-hang");
        
        Route::get("taikhoan/yeuthich", [UserController::class, "YeuThich"])->name("user/tai-khoan-yeu-thich");
        
        Route::get("taikhoan/voucher", [UserController::class, "Voucher"])->name("user/tai-khoan-voucher");

        /*=======================================================================================================
                                                        Form submit
        =========================================================================================================*/

        Route::post("change-address-delivery", [UserController::class, "ChangeAddressDelivery"])->name("user/change-address-delivery");
        
        Route::post("create-update-address", [UserController::class, "CreateUpdateAddress"])->name("user/create-update-address");

        Route::get("set-default-address/{id}", [UserController::class, "SetDefaultAddress"])->name("user/set-default-address");

        Route::post("delete-object", [UserController::class, "DeleteObject"])->name("user/delete-object");

        Route::post("ajax-change-avatar", [UserController::class, "AjaxChangeAvatar"])->name("user/ajax-change-avatar");

        route::post("checkout", [CartController::class, "Checkout"])->name("user/checkout");

        /*=======================================================================================================
                                                        Ajax
        =========================================================================================================*/

        Route::post("ajax-change-fullname", [UserController::class, "AjaxChangeFullname"]);

        Route::post("ajax-change-password", [UserController::class, "AjaxChangePassword"]);

        Route::post("ajax-check-noti", [UserController::class, "AjaxCheckNoti"]);

        Route::post("ajax-delete-noti", [UserController::class, "AjaxDeleteNoti"]);

        Route::get("ajax-check-all-noti", [UserController::class, "AjaxCheckAllNoti"]);

        Route::get("ajax-delete-all-noti", [UserController::class, "AjaxDeleteAllNoti"]);

        Route::post("ajax-delete-favorite", [UserController::class, "AjaxDeleteFavorite"]);

        Route::get("ajax-delete-all-favorite", [UserController::Class, "AjaxDeleteAllFavorite"]);

        Route::post("ajax-check-qty-in-stock-branch", [IndexController::class, "AjaxCheckQtyInStockBranch"]);

        Route::get("use-voucher/{id}", [UserController::Class, "UserVoucher"]);

        Route::post("ajax-check-voucher-conditions", [UserController::class, "CheckVoucherConditions"]);

        Route::post("ajax-choose-phone-to-evaluate", [UserController::class, "AjaxChoosePhoneToEvaluate"]);

        route::get("ketquathanhtoan", [CartController::class, "ketQuaThanhToan"]);

        Route::post("ajax-create-evaluate", [UserController::class, "AjaxCreateEvaluate"]);

        Route::post("ajax-edit-evaluate", [UserController::class, "AjaxEditEvaluate"]);

        Route::post("ajax-reply", [UserController::class, "AjaxReply"]);
    });
    
});

Route::group(["prefix" => "admin", "namespace" => "admin", "middleware" => "CheckLogin"], function() {
    Route::get("/", [DashboardController::class, "index"])->name("admin/dashboard");
    Route::resource("hinhanh", HinhAnhController::class);
    Route::resource("banner", BannerController::class);
    Route::resource("slideshow", SlideshowController::class);
    Route::resource("mausanpham", MauSanPhamController::class);
    Route::resource("sanpham", SanPhamController::class);
    Route::resource("nhacungcap", NhaCungCapController::class);
    Route::resource("danhgia", DanhGiaController::class);
    Route::resource("khuyenmai", KhuyenMaiController::class);
    Route::resource("donhang", DonHangController::class);
    Route::resource("baohanh", BaoHanhController::class);
    Route::resource("taikhoan", TaiKhoanController::class);

    /*=======================================================================================================
                                                        Ajax
    =========================================================================================================*/
    
    Route::post("ajax-delete-object", [DashboardController::class, "AjaxDeleteObject"]);

    Route::post("ajax-get-hinhanh", [DashboardController::class, "AjaxGetHinhAnh"]);
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

