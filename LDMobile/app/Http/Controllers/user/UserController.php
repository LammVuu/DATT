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
        return view($this->user."tai-khoan");
    }
    
}
