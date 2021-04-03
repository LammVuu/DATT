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
    public function Login(){
        return view($this->user."login");
    }
    public function Signup(){
        return view($this->user."signup");
    }
    public function Account(){
        return view($this->user."account");
    }
    
}
