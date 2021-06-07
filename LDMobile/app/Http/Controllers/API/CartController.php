<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SANPHAM;
use App\Models\MAUSP;
// use App\Models\ThongBao;
use App\Models\GIOHANG;
// use App\Models\CHITIETGIOHANG;
use App\Models\DONHANG;
use App\Models\CHITIETDONHANG;
use App\Models\TAIKHOAN;
use App\Models\DETAILCART;
use App\Classes\Helper;
use session;
use Carbon\Carbon;
class CartController extends Controller
{
    //
    public function geTotalProductInCart($id){
        $giohang = GIOHANG::where("id_tk", $id)->get();
        if(count($giohang)>0){
            $chitietgiohang = GIOHANG::where("MaGH", $giohang[0]->id)->get();
            $tongsl = count($chitietgiohang);
        }else{
            $tongsl = 0;
        }
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' =>$tongsl
    	]);
    }
}
