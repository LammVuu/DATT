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

    /*============================================================================================================
                                                        Ajax
    ==============================================================================================================*/

    public function AjaxGetHinhAnh(Request $request)
    {
        if($request->ajax()){
            return 'success';
        }
    }

    public function AjaxDeleteObject(Request $request)
    {
        if($request->ajax()){
            // xóa hình ảnh
            if($request->object == 'hinhanh'){
                // xử lý xóa
                return 'success';
            }
        }
    }
}
