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
use App\Models\CTDH;
use App\Models\TAIKHOAN;
use App\Models\KHUYENMAI;
use App\Models\THONGBAO;
use App\Models\TAIKHOAN_VOUCHER;
use App\Models\CHINHANH;
use App\Models\VOUCHER;
use App\Models\TINHTHANH;
use App\Models\TAIKHOAN_DIACHI;
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
            $product->priceRoot =  $pro->gia;
        
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
    public function getMyVoucher($id){
        $listResult = array();
        $myVoucher = TAIKHOAN_VOUCHER::where('id_tk', $id)->get();
        foreach($myVoucher as $voucher){
            $infoVoucher = VOUCHER::find($voucher->id_vc);
            array_push($listResult, $infoVoucher);
        }
        $currentDate = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
      
        foreach($listResult as $voucher){
            $date1 = Carbon :: createFromFormat ('d/m/Y', $currentDate);
            $date2 = Carbon :: createFromFormat ('d/m/Y', $voucher->ngayketthuc);
           if($date2->gte($date1)){
            $voucher->active = true;
           }else $voucher->active = false;
           $voucher->chietkhau = $voucher->chietkhau*100;
        }
        return response()->json([
            'status' => true,
            'message' => 'Xóa sản phẩm thành công',
            'data' =>$listResult
        ]);
    }
    public function deleteMyVoucher($id){
        
        $myVoucher = TAIKHOAN_VOUCHER::where('id_vc', $id)->get();
        $voucher = TAIKHOAN_VOUCHER::find($myVoucher[0]->id);
        if($voucher->delete()){
            return response()->json([
                'status' => true,
                'message' => 'Xóa voucher thành công',
                'data' =>null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>null
        ]);
    }
    public function createOrder($id, Request $request){
        $order = new DONHANG();
        $order->id_tk = $id;
        $order->pttt = request('pttt');
        $order->id_vc = request('id_vc');
        $order->hinhthuc = request('hinhthuc');
        $order->trangthaidonhang = "Đã tiếp nhận";
        $order->diachigiaohang= request('diachigiaohang');
        $order->thoigian = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
        $order->tongtien = request('tongtien');
        $order->trangthai = 1;
        if($order->save()){
            foreach(request('listProduct') as $product){
                $detailOrder = new CTDH();
                $detailOrder->id_dh = $order->id;
                $detailOrder->id_sp = $product['id_sp'];
                $detailOrder->gia = $product['priceRoot'];
                $detailOrder->sl = $product['sl'];
                $detailOrder->giamgia = $product['discount'];
                $detailOrder->thanhtien =  $product['price']*$product['sl'];
                $detailOrder->save();
                $cart = GIOHANG::find($product['id']);
                $cart->delete();
            }
            $notification = new THONGBAO();
            $notification->id_tk = $id;
            $notification->tieude = "Đơn đã tiếp nhận";
            $notification->noidung = "Đã tiếp nhận đơn hàng số #".$order->id;
            $notification->thoigian = Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y');
            $notification->trangthaithongbao = 0;
            $notification->save();
            return response()->json([
                'status' => true,
                'message' => 'Đặt hàng thành công',
                'data' => null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>null
        ]);
    }
    public function getProvinceStore(){
        $listProvince = TINHTHANH::all();
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $listProvince
        ]);
    }
    public function getAddressStore($id){
        $branch = CHINHANH::where('id_tt', $id)->get();
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $branch
        ]);
    }
    public function getProvince(){
        $url ="TinhThanh.json" ;
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        return response()->json([
            'status' => true,
            'message' => '',
            'data' =>  $data
        ]);
        
    }
    public function getDistrict(Request $request){
        $url ="QuanHuyen.json" ;
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        return response()->json([
            'status' => true,
            'message' => '',
            'data' =>  $data[strval($request->id)]
        ]);
        
    }
    public function getWard(Request $request){
        $url ="PhuongXa.json" ;
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        return response()->json([
            'status' => true,
            'message' => '',
            'data' =>  $data[strval($request->id)]
        ]);
    }
    public function getMyAddress($id){
        $listAddress = TAIKHOAN_DIACHI::where('id_tk', $id)->get();
        return response()->json([
            'status' => true,
            'message' => '',
            'data' =>  $listAddress
        ]);
    }
    public function getID(Request $request){
        $IDCity = "";
        $IDDistrict ="";
        $url ="TinhThanh.json" ;
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        $data = array_filter($data);
        $data = collect($data)->where("Name", $request->city)->all();
        $firstKey = array_key_first($data);
        $IDCity = $data[$firstKey]['ID'];
        $url ="QuanHuyen.json" ;
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        $data = array_filter($data[$IDCity]);
        $data = collect($data)->where("Name",$request->district)->all();
        $firstKey = array_key_first($data);
        $IDDistrict = $data[$firstKey]['ID'];
  
        return response()->json([
                 $IDCity,
                $IDDistrict,
                
        ]);
    }
    public function createMyAddress(Request $request){
        $newAddress = new TAIKHOAN_DIACHI();
        $newAddress->id_tk = request('id_tk');
        $newAddress->diachi = request('diachi');
        $newAddress->phuongxa = request('phuongxa');
        $newAddress->hoten = request('hoten');
        $newAddress->quanhuyen = request('quanhuyen');
        $newAddress->tinhthanh = request('tinhthanh');
        $newAddress->sdt = request('sdt');
        $newAddress->macdinh = request('macdinh');
        $newAddress->trangthai = 1;
        if(request('macdinh')==1){
            $oldDefault = TAIKHOAN_DIACHI::where('macdinh', 1)->get();
            $oldAddress = TAIKHOAN_DIACHI::find($oldDefault[0]->id);
            $oldAddress->macdinh = 0;
            $oldAddress->update();
        }
        if($newAddress->save()){
            return response()->json([
                'status' => true,
                'message' => 'Thêm địa chỉ thành công',
                'data' =>  null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>  null
        ]);
        
    }
    public function updateMyAddress($id, Request $request){
        $updateAddress = TAIKHOAN_DIACHI::find($id);
        $updateAddress->id_tk = request('id_tk');
        $updateAddress->hoten = request('hoten');
        $updateAddress->diachi = request('diachi');
        $updateAddress->phuongxa = request('phuongxa');
        $updateAddress->quanhuyen = request('quanhuyen');
        $updateAddress->tinhthanh = request('tinhthanh');
        $updateAddress->sdt = request('sdt');
        $updateAddress->macdinh = request('macdinh');
        if($updateAddress->update()){
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật thành công',
                'data' =>  null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>  null
        ]);
    }
    public function deleteNotification($id){
        $deleteAddress = TAIKHOAN_DIACHI::find($id);
        if($deleteAddress->delete()){
            return response()->json([
                'status' => true,
                'messages' => "Xóat hành công",
                'data' => null
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Có lỗi xảy ra',
            'data' =>  null
        ]);
       
    }
}
