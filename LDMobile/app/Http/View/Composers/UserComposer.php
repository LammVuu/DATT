<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\user\IndexController;

use App\Models\TAIKHOAN;
use App\Models\SANPHAM;
use App\Models\VOUCHER;
use App\Models\DONHANG;
use App\Models\CHINHANH;
use App\Models\TAIKHOAN_DIACHI;
use App\Models\THONGBAO;
use App\Models\DONHANG_DIACHI;

class UserComposer
{
    public function __construct()
    {
        $this->IndexController = new IndexController;
    }

    public function compose(View $view)
    {
        if(session('user')){
            $id_tk = session('user')->id;
            
            $data = [
                'lst_noti' => $this->getNotification($id_tk),
                'lst_order' => $this->getListOrder($id_tk),
                'lst_address' => $this->getAddress($id_tk),
                'lst_favorite' => $this->getFavorite($id_tk),
                'lst_voucher' => $this->getVoucher($id_tk),
                'cart' => $this->IndexController->getCart($id_tk),
            ];
    
            $view->with('data', $data);
        }
    }

    // lấy thông báo của tài khoản
    public function getNotification($id_tk)
    {
        $lst_noti = [
            'noti' => [],
            'not-seen' => 0,
        ];

        if(count(TAIKHOAN::find($id_tk)->thongbao) == 0){
            return $lst_noti;
        }

        $lst_noti['noti'] = THONGBAO::where('id_tk', $id_tk)->orderBy('id', 'desc')->limit(10)->get();

        foreach(TAIKHOAN::find($id_tk)->thongbao as $key){
            if($key['trangthaithongbao'] == 0){
                $lst_noti['not-seen']++;
            }
        }

        return $lst_noti;
    }

    // lấy đơn hàng của tài khoản
    public function getListOrder($id_tk)
    {
        $data = [
            'order' => [],
            'detail' => [],
            'processing' => 0,
        ];

        if(count(TAIKHOAN::find($id_tk)->donhang) == 0){
            return $data;
        }

        $i = 0;

        // lấy đơn hàng của người dùng
        foreach(DONHANG::where('id_tk', $id_tk)->orderBy('id', 'desc')->get() as $key){
            // đơn hàng
            $data['order'][$i] = $key;
            
            // chi tiết đơn hàng
            $data['detail'][$key['id']] = $this->IndexController->getOrderDetail($key['id']);
            $i++;

            // đơn hàng đang xử lý
            if($key['trangthaidonhang'] != 'Thành công' && $key['trangthaidonhang'] != 'Đã hủy'){
                $data['processing']++;
            }
        }

        return $data;
    }

    // lấy địa chỉ của tài khoản
    public function getAddress($id_tk)
    {
        return count(TAIKHOAN::find($id_tk)->taikhoan_diachi) == 0 ? [] : TAIKHOAN::find($id_tk)->taikhoan_diachi;
    }

    // lấy sp yêu thích của tài khoản
    public function getFavorite($id_tk)
    {
        if(count(TAIKHOAN::find($id_tk)->sp_yeuthich) == 0){
            return [];
        }
        
        $lst_product = [];

        $i = 0;

        foreach(TAIKHOAN::find($id_tk)->sp_yeuthich as $key){
            $lst_product[$i]['id'] = $key->pivot->id;
            $lst_product[$i]['sanpham'] = $this->IndexController->getProductById($key->pivot->id_sp);
            $i++;
        }

        return $lst_product;
    }

    // lấy voucher của tài khoản
    public function getVoucher($id_tk)
    {
        if(count(TAIKHOAN::find($id_tk)->taikhoan_voucher) == 0){
            return [];
        }
        
        $lst_voucher = [];
        $i = 0;

        foreach(TAIKHOAN::find($id_tk)->taikhoan_voucher as $key){
            $lst_voucher[$i] = VOUCHER::find($key->pivot->id_vc);
            $lst_voucher[$i]->sl_voucher = $key->pivot->sl;
            $i++;
        }

        return $lst_voucher;
    }
}