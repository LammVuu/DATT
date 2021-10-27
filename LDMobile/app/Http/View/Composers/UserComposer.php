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
use App\Models\TAIKHOAN_VOUCHER;

class UserComposer
{
    public function __construct()
    {
        $this->IndexController = new IndexController;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
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
            'processing' => [],
            'complete' => [],
            'processingQty' => 0
        ];

        if(DONHANG::where('id_tk', $id_tk)->get()->count() === 0){
            return $data;
        }

        // lấy đơn hàng của người dùng, sắp sếp ngày mua mới nhất
        $allOrderOfUser = DONHANG::where('id_tk', $id_tk)->orderBy('id', 'desc')->get();
        foreach($allOrderOfUser as $userOrder){
            $order = [
                'order' => $userOrder,
                'detail' => $this->IndexController->getOrderDetail($userOrder->id)
            ];

            // đơn hàng đang xử lý
            if($userOrder['trangthaidonhang'] !== 'Thành công' && $userOrder['trangthaidonhang'] !== 'Đã hủy') {
            array_push($data['processing'], $order);
                $data['processingQty']++;
            } else {
                array_push($data['complete'], $order);
            }
        }

        return $data;
    }

    // lấy địa chỉ của tài khoản
    public function getAddress($id_tk)
    {
        $addressList = [
            'status' => false,
            'default' => [],
            'another' => []
        ];

        $allAddress = TAIKHOAN::find($id_tk)->taikhoan_diachi;

        if($allAddress->count()) {
            $addressList['status'] = true;

            foreach($allAddress as $address) {
                if($address->macdinh === 1) {
                    $addressList['default'] = $address;
                } else {
                    array_push($addressList['another'], $address);
                }
            }
        }

        return $addressList;
    }

    // lấy sp yêu thích của tài khoản
    public function getFavorite($id_tk)
    {
        if(count(TAIKHOAN::find($id_tk)->sp_yeuthich) == 0){
            return [];
        }
        
        $lst_product = [];

        foreach(TAIKHOAN::find($id_tk)->sp_yeuthich as $key){
            $id = $key->pivot->id;

            $id_sp_list = $this->IndexController->getListIdSameCapacity($key->pivot->id_sp);
            $product = $this->IndexController->getProductById($key->pivot->id_sp);
            
            array_push($lst_product, [
                'id' => $id,
                'sanpham' => $product
            ]);
        }

        return $lst_product;
    }

    // lấy voucher của tài khoản
    public function getVoucher($id_tk)
    {
        if(count(TAIKHOAN_VOUCHER::where('id_tk', $id_tk)->get()) === 0){
            return [];
        }

        $userVoucher = TAIKHOAN_VOUCHER::where('id_tk', $id_tk)->get();

        foreach($userVoucher as $i => $key){
            $voucher = VOUCHER::find($key->id_vc);
            $userVoucher[$i]->voucher = $voucher;

            // ngày kết thúc
            $end = strtotime(str_replace('/', '-', $voucher->ngayketthuc));
            // ngày hiện tại
            $current = strtotime(date('d-m-Y'));

            if($end < $current){
                $userVoucher[$i]->trangthai = false;
            } else {
                $userVoucher[$i]->trangthai = true;
            }
        }

        return $userVoucher;
    }
}