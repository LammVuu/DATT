<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    //
    public function DangNhap(){
        return view($this->user."dang-nhap");
    }

    public function DangKy(){
        return view($this->user."dang-ky");
    }

    public function TaiKhoan(){
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $array = [
            'page' => 'sec-tai-khoan',
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
        ];
        return view($this->user."tai-khoan")->with($array);
    }

    public function ThongBao()
    {
        $array = [
            'page' => 'sec-thong-bao',
        ];
        return view($this->user."tai-khoan")->with($array);
    }

    public function DonHang()
    {
        $array = [
            'page' => 'sec-don-hang',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function ChiTietDonHang(){
        $array = [
            'page' => 'sec-chi-tiet-don-hang',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function YeuThich()
    {
        $array = [
            'page' => 'sec-yeu-thich',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function Voucher()
    {
        $array = [
            'page' => 'sec-voucher',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }
    
}
