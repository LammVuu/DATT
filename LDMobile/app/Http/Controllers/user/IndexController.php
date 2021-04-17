<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    public function Index(){
        return view($this->user."index");
    }

    public function SanPham(){
        return view($this->user."san-pham");
    }

    public function ChiTiet(){
        return view($this->user."chi-tiet-san-pham");
    }

    public function ThanhToan(){
        return view($this->user."thanh-toan");
    }
}
