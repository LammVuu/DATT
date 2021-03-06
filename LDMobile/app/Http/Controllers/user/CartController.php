<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\TAIKHOAN;
use App\Models\GIOHANG;
use App\Models\SANPHAM;
use App\Models\KHO;
use App\Models\TINHTHANH;
use App\Models\CHINHANH;
use App\Models\DONHANG;
use App\Models\CTDH;
use App\Models\TAIKHOAN_VOUCHER;
use App\Models\VOUCHER;
use App\Models\TAIKHOAN_DIACHI;


class CartController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
        $this->IndexController = new IndexController;
    }

    /*======================================================================================================
                                                    Page
    ========================================================================================================*/
    public function GioHang(){
        return view($this->user."gio-hang");
    }

    public function ThanhToan(){
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $data = [
            'defaultAdr' => $this->IndexController->getAddressDefault(session('user')->id),
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
            'lstKhuVuc' => TINHTHANH::all(),
            'lstChiNhanh' => CHINHANH::all(),
            'cartRequired' => count(TAIKHOAN::find(session('user')->id)->giohang) == 0 ? true : false,
        ];
        
        return view($this->user."thanh-toan")->with($data);
    }

    public function ThanhCong(Request $request)
    {
        // đã thanh toán
        if($request->id){
            $data = [
                'order' => DONHANG::find($request->id),
            ];

            return view($this->user.'thanh-cong')->with($data);
        }

        return back();
    }

    /*======================================================================================================
                                                Function route
    ========================================================================================================*/

    public function Checkout(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $order = [
            'thoigian' => date('d/m/Y H:i:s'),
            'id_tk' => session('user')->id,
            'id_tk_dc' => $request->receciveMethod == 'Giao hàng tận nơi' ? $request->id_tk_dc : null,
            'id_cn' => $request->receciveMethod == 'Nhận tại cửa hàng' ? $request->id_cn : null,
            'pttt' => $request->paymentMethod == 'cash' ? 'Thanh toán khi nhận hàng' : 'Thanh toán ZaloPay',
            'id_vc' => $request->id_vc,
            'hinhthuc' => $request->receciveMethod,
            'tongtien' => $request->cartTotal,
            'trangthaidonhang' => 'Đã tiếp nhận',
            'trangthai' => 1,
        ];

        // thanh toán khi nhận hàng
        if($request->paymentMethod == 'cash'){
            // tạo đơn hàng
            $create = DONHANG::create($order);

            //chi tiết đơn hàng & trừ số lượng kho
            $cart = $this->IndexController->getCart(session('user')->id);

            foreach($cart['cart'] as $key){
                $detail = [
                    'id_dh' => $create->id,
                    'id_sp' => $key['sanpham']['id'],
                    'gia' => $key['sanpham']['gia'],
                    'sl' => $key['sl'],
                    'giamgia' => $key['sanpham']['khuyenmai'],
                    'thanhtien' => $key['thanhtien'],
                ];

                CTDH::create($detail);

                // trừ số lượng kho
                if($order['hinhthuc'] == 'Giao hàng tận nơi'){
                    // lấy sản phẩm ở chi nhánh có tỉnh thành giống với tỉnh thành của người đặt hàng
                    $userCity = TAIKHOAN_DIACHI::find($order['id_tk_dc'])->tinhthanh;

                    // Kho
                    $warehouse = KHO::where('id_cn', CHINHANH::where('id_tt', TINHTHANH::where('tentt', $userCity)->first()->id)->first()->id)
                        ->where('id_sp', $detail['id_sp'])->first();

                    // slton
                    $slton = intval($warehouse->slton);

                    // cập nhật số lượng
                    // nếu kho tại chi nhánh không đủ thì lấy tiếp từ kho ở chi nhánh khác
                    if($warehouse->slton < $detail['sl']){
                        $missingQty = $detail['sl'] - $warehouse->slton;

                        $warehouse->slton = 0;
                        $warehouse->save();

                        $anotherBranch = KHO::where('id_cn', '!=', CHINHANH::where('id_tt', TINHTHANH::where('tentt', $userCity)->first()->id)->first()->id)
                        ->where('id_sp', $detail['id_sp'])->first();

                        $anotherBranch->slton -= $missingQty;
                        $anotherBranch->save();
                    }
                    // ngược lại trừ số lượng kho tại chi nhánh bình thường
                    else {
                        $warehouse->slton -= $detail['sl'];
                        $warehouse->save();
                    }
                } else{
                    // Kho
                    $warehouse = KHO::where('id_cn', $order['id_cn'])->where('id_sp', $detail['id_sp'])->first();

                    // slton
                    $slton = intval($warehouse->slton);

                    // cập nhật số lượng
                    $warehouse->slton = $slton - $detail['sl'];
                    $warehouse->save();
                }
            }

            // xóa giỏ hàng
            GIOHANG::where('id_tk', session('user')->id)->delete();

            // xóa voucher đã áp dụng
            if($request->id_vc){
                TAIKHOAN_VOUCHER::where('id_tk', session('user')->id)->where('id_vc', $request->id_vc)->delete();
            }

            // xóa voucher trong session
            Session::forget('voucher');

            return redirect()->route('user/thanhcong', ['id' => $create->id]);
        }
        // thanh toán zalo pay
        else {
            $config = [
                "app_id" => 2553,
                "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
                "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
                "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
            ];
    
            $embeddata = '{}'; // Merchant's data
            $items = '[]'; // Merchant's data
            $transID =  rand(0,1000000);
            $order = [
                "app_id" => $config["app_id"],
                "app_user" => session('user')->id,
                "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
                "app_time" => round(microtime(true) * 1000), // miliseconds
                "amount" => $order['tongtien'],
                "item" => $items,
                "embed_data" => $embeddata,
                "description" => "LDMobile - Thanh toán đơn hàng",
                "bank_code" => ""
            ];
    
            // appid|app_trans_id|appuser|amount|apptime|embeddata|item
            $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
                . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
            $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);
    
            $context = stream_context_create([
                "http" => [
                    "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "POST",
                    "content" => http_build_query($order)
                ]
            ]);
    
            $resp = file_get_contents($config["endpoint"], false, $context);
            $result = json_decode($resp, true);
    
            return redirect($result['order_url']);
    
            // foreach ($result as $key => $value) {
            //     echo "$key: $value<br>";
            // }
            
            // return false;
        }
    }

    public function ZaloPayCallback(Request $request)
    {
        $result = [];

        try {
            $key2 = "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf";
            $postdata = file_get_contents('php://input');
            $postdatajson = json_decode($postdata, true);
            $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

            $requestmac = $postdatajson["mac"];

            // kiểm tra callback hợp lệ (đến từ ZaloPay server)
            if (strcmp($mac, $requestmac) != 0) {
                // callback không hợp lệ
                $result["return_code"] = -1;
                $result["return_message"] = "mac not equal";
            } else {
                // thanh toán thành công
                // merchant cập nhật trạng thái cho đơn hàng
                $datajson = json_decode($postdatajson["data"], true);
                // echo "update order's status = success where app_trans_id = ". $dataJson["app_trans_id"];

                $result["return_code"] = 1;
                $result["return_message"] = "success";
            }
        } catch (Exception $e) {
        $result["return_code"] = 0; // ZaloPay server sẽ callback lại (tối đa 3 lần)
        $result["return_message"] = $e->getMessage();
        }

        // thông báo kết quả cho ZaloPay server
        echo json_encode($result);
    }

    public function KetQuaThanhToan()
    {
        $key2 = "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf";
        $data = $_GET;
        $checksumData = $data["appid"] ."|". $data["apptransid"] ."|". $data["pmcid"] ."|". $data["bankcode"] ."|". $data["amount"] ."|". $data["discountamount"] ."|". $data["status"];
        $mac = hash_hmac("sha256", $checksumData, $key2);

        if (strcmp($mac, $data["checksum"]) != 0) {
        http_response_code(400);
        echo "Bad Request";
        } else {
            // kiểm tra xem đã nhận được callback hay chưa, nếu chưa thì tiến hành gọi API truy vấn trạng thái thanh toán của đơn hàng để lấy kết quả cuối cùng
            $config = [
                "app_id" => 2554,
                "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
                "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
                "endpoint" => "https://sb-openapi.zalopay.vn/v2/query"
            ];
            
            $app_trans_id = $data["apptransid"];  // Input your app_trans_id
            $data_2 = $config["app_id"]."|".$app_trans_id."|".$config["key1"]; // app_id|app_trans_id|key1
            $params = [
                "app_id" => $config["app_id"],
                "app_trans_id" => $app_trans_id,
                "mac" => hash_hmac("sha256", $data_2, $config["key1"])
            ];
            
            $context = stream_context_create([
                "http" => [
                    "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "POST",
                    "content" => http_build_query($params)
                ]
            ]);
            
            $resp = file_get_contents($config["endpoint"], false, $context);
            $result = json_decode($resp, true);

            if($result['return_code'] == 1){
                http_response_code(200);
                return redirect('/thanhcong');
            } else {
                foreach ($result as $key => $value) {
                    echo "$key: $value<br>";
                }
                return false;
            }
        }
    }

    public function AjaxAddCart(Request $request)
    {
        if($request->ajax()){
            // chưa đăng nhập
            if(!Auth::check()){
                return [
                    'status' => false,
                ];
            }

            $data = [
                'id_tk' => session('user')->id,
                'id_sp' => $request->id_sp,
                'sl' => $request->sl,
            ];

            // nếu tài khoản chưa có sản phẩm nào trong giỏ hàng
            if(count(TAIKHOAN::find(session('user')->id)->giohang) == 0){
                GIOHANG::create($data);
                return [
                    'status' => 'success',
                ];
            } 
            // kiểm tra cập nhật số lượng hoặc tạo mới
            else {
                foreach(TAIKHOAN::find(session('user')->id)->giohang as $cart){
                    // cập nhật số lượng sản phẩm trong giỏ hàng
                    if($cart->pivot->id_sp == $request->id_sp){
                        $sl = Intval(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()->sl);
                        $sl += $request->sl;

                        // số lượng tồn kho hiện tại
                        $qtyInStock = 0;
                        foreach(KHO::where('id_sp', $request->id_sp)->get() as $key){
                            $qtyInStock += $key['slton'];
                        }

                        // so sánh số lượng tồn với số lượng trong giỏ hàng
                        // mua quá số lượng
                        if($sl > $qtyInStock){
                            return [
                                'status' => 'invalid qty',
                            ];
                        }
                        GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->update(['sl' => $sl]);

                        return [
                            'status' => 'update success',
                        ];
                    }
                }

                // thêm sản phẩm mới vào giỏ hàng
                GIOHANG::create($data);
                return [
                    'status' => 'success',
                ];
            }
        }

        return false;
    }

    public function AjaxBuyNow(Request $request)
    {
        if($request->ajax()){
            // chưa đăng nhập
            if(!session('user')){
                return [
                    'status' => 'login required'
                ];
            }
            // đã có sản phẩm trong giỏ hàng thì sang trang giỏ hàng
            elseif(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()){
                return [
                    'status' => 'redirect cart'
                ];
            }
            // sản phẩm hết hàng
            $qtyInStock = KHO::where('id_sp', $request->id_sp)->count('slton');
            if($qtyInStock == 0){
                return [
                    'status' => 'out of stock'
                ];
            }
            // thêm sản phẩm vào giỏ hàng
            else {
                GIOHANG::create([
                    'id_tk' => session('user')->id,
                    'id_sp' => $request->id_sp,
                    'sl' => 1,
                ]);

                return [
                    'status' => 'redirect cart'
                ];
            }
        }
    }

    public function AjaxRemoveAllCart(Request $request)
    {
        if($request->ajax()){
            GIOHANG::where('id_tk', session('user')->id)->delete();
        }

        return back();
    }

    public function AjaxRemoveCartItem(Request $request)
    {
        if($request->ajax()){
            GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->delete();
        }

        return false;
    }

    public function AjaxUpdateCart(Request $request)
    {
        if($request->ajax()){
            $data = [
                'newQty' => '',
                'newPrice' => '',
                'provisional' => 0,
            ];

            $cart = GIOHANG::where('id', $request->id)->first();
            $qty = intval($cart->sl);

            // tăng số lượng
            if($request->type == 'plus'){
                GIOHANG::where('id', $request->id)->update(['sl' => ++$qty]);
            } 
            // giảm số lượng
            else {
                GIOHANG::where('id', $request->id)->update(['sl' => --$qty]);
            }

            // trả dữ liệu về view
            $data['newQty'] = $qty;
            $data['newPrice'] = intval($this->IndexController->getProductById($cart->id_sp)['giakhuyenmai'] * $qty);

            // tạm tính
            foreach(TAIKHOAN::find(session('user')->id)->giohang as $key){
                $data['provisional'] += intval($key->pivot->sl * $this->IndexController->getProductById($key->pivot->id_sp)['giakhuyenmai']);
            }

            return $data;
        }

        return false;
    }
}
