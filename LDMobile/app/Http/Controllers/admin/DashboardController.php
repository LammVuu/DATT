<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
    }

    public function Index()
    {
        return view($this->admin.'index');
    }
}
