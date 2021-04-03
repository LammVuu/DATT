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
    public function Detail(){
        return view($this->user."detail_product");
    }
    public function Checkout(){
        return view($this->user."checkout");
    }
    public function Shop(){
        return view($this->user."shop");
    }
}
