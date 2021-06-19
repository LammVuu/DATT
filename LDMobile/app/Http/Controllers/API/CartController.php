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
use App\Models\KHUYENMAI;
use App\Models\DETAILCART;
use App\Classes\Helper;
use session;
use Carbon\Carbon;
class CartController extends Controller
{
    //
    public function getTotalProductInCart($id){
        $cart = GIOHANG::where("id_tk", $id)->get();
        $total = count($cart);
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' =>$total
    	]);
    }
    public function getMyCart($id){
        $listProduct = GIOHANG::where("id_tk", $id)->get();
        $totalMoney = 0;
        foreach($listProduct as $product){
            $pro = SANPHAM::find($product->id_sp);
            $product->name = $pro->tensp;
            $product->avatar = Helper::$URL."phone/".$pro->hinhanh;
            $product->color = $pro->mausac;
            $product->storage = $pro->dungluong;
            $product->discount = KHUYENMAI::find($pro->id_km)->chietkhau;
            $product->price = $pro->gia-($pro->gia*$product->discount);
        
        }
        return response()->json([
            'status' => true,
            'message' => '',
    		'data' =>$listProduct
    	]);
    }
    public function addToCart(Request $req, $id){
        $i = 0;
        $product = SANPHAM::find($id);
        $cart = GIOHANG::where("id_tk", $req->id_user)->where("id_sp", $product->id)->get();
        if(count($cart)==0){
                $cart = new GIOHANG;
                $cart->id_tk = $req->id_user;
                $cart->id_sp = $product->id;
                $cart->sl=1;
                // $GioHang->TongGia=0;
                // $GioHang->TongTien=0;
                // $GioHang->TrangThai=1;
                // $GioHang->NgayLap = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i');
                $cart->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Thêm vào giỏ hàng thành công',
                    'data' =>null
                ]);
        }else{ 
            if($cart[0]->sl==2){
                return response()->json([
                    'status' => false,
                    'message' => 'Số lượng được mua đã giới hạn',
                    'data' =>null
                ]);
            }
            $updateQtyProductInCart = GIOHANG::find($cart[0]->id);
            $updateQtyProductInCart->sl++;
            $updateQtyProductInCart->update();
            return response()->json([
                'status' => true,
                'message' => 'Thêm vào giỏ hàng thành công',
                'data' =>null
            ]);
        }      
         return response()->json([
                'status' => false,
                'message' => 'Thất bại',
                'data' =>null
        ]);   
    }
    public function updateCart($id, Request $request){
        $cart = GIOHANG::find($id);
        if($request->plusOrMin =="plus"){
            $cart->sl++;
        }else $cart->sl--;
     
        if($cart->update());{
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật thành công',
                'data' =>null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>null
        ]);
        
    }
    public function deleteProductInCart($id){
        $cart = GIOHANG::find($id);
        if($cart->delete()){
            return response()->json([
                'status' => true,
                'message' => 'Xóa sản phẩm thành công',
                'data' =>null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>null
        ]);
        
       
    }
}
