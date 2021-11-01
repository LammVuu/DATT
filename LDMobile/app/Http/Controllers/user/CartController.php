<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Support\Facades\DB;
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
use App\Models\THONGBAO;
use App\Models\DONHANG_DIACHI;
use App\Models\HANGDOI;


class CartController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->IndexController = new IndexController;
        $this->nofiticationContent = 'Oops! Có sản phẩm đã hết hàng hoặc số lượng không đủ, bạn không thể thanh toán đơn hàng này.';
    }

    /*======================================================================================================
                                                    Page
    ========================================================================================================*/
    public function GioHang(Request $request){
        if(!Session::get('_previous')){
            $url = [
                'url' => $request->fullUrl(),
            ];

            session(['_previous' => $url]);
        }

        return view($this->user."gio-hang");
    }

    public function ThanhToan(Request $request){
        // print_r(Session::get('_previous'));die; 
        // bắt buộc request phải từ trang giỏ hàng
        if(Session::get('_previous')){
            $url = Session::get('_previous')['url'];
            $arrUrl = explode('/', $url);
            $page = $arrUrl[count($arrUrl) - 1];

            // mảng các trang cho phép truy cập | redirect
            $lst_allowPage = [
                'thanhtoan',
                'giohang',
                'diachigiaohang',
                'create-update-address',
                'apply-voucher'
            ];

            // các trang không nằm trong mảng cho phép
            if(!in_array($page, $lst_allowPage)){
                return redirect('/giohang');
            }
        } else {
            return redirect('/');
        }

        // // giỏ hàng của người dùng
        // $cart = $this->IndexController->getCart(session('user')->id);

        // // có sản phẩm không thể thanh toán hay không
        // $checkoutFlag = true;

        // foreach($cart['cart'] as $key){
        //     $id_sp = $key['sanpham']['id'];
        //     // sl của sp trong giỏ hàng
        //     $qtyCart = $key['sl'];
        //     // slton của sản phẩm trong kho
        //     $qtyInStock = KHO::where('id_sp', $id_sp)->sum('slton');
        //     // nếu sp hết hàng
        //     if(!$qtyInStock){
        //         // xóa hàng đợi
        //         HANGDOI::where('id_tk', session('user')->id)->delete();
        //         return redirect()->route('user/thongbao')->with('message', $this->nofiticationContent);
        //     }
        //     // nếu sl của sp trong giỏ hàng > slton trong kho => không đủ hàng
        //     elseif($qtyCart > $qtyInStock){
        //         $checkoutFlag = false;
        //         // cập nhật sl của sp trong giỏ hàng = slton hiện tại
        //         GIOHANG::where('id', $key['id'])->update(['sl' => $qtyInStock]);
        //     }
        // }

        // if(!$checkoutFlag){
        //     // xóa hàng đợi
        //     HANGDOI::where('id_tk', session('user')->id)->delete();
            
        //     // quay về trang giỏ hàng và thông báo
        //     $message = 'Số lượng tồn kho của một số sản phẩm không đủ để thanh toán.';
        //     return redirect('/giohang')->with('alert_top', $message);
        // }

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
            'lstArea' => TINHTHANH::all(),
            'lstBranch' => CHINHANH::all(),
        ];
        
        return view($this->user."thanh-toan")->with($data);
    }

    public function ThanhCong(Request $request)
    {
        // đã thanh toán
        if($request->id){
            if(DONHANG::orderBy('id', 'desc')->first()->id == $request->id){
                $data = [
                    'order' => DONHANG::find($request->id),
                ];
                
                return view($this->user.'thanh-cong')->with($data);
            }

            return back();
        }

        return back();
    }

    /*======================================================================================================
                                                Function route
    ========================================================================================================*/

    public function Checkout(Request $request)
    {
        // $requestData = [
        //     'paymentMethod' => $request->paymentMethod,
        //     'receciveMethod' => $request->receivingMethod,
        //     'id_tk_dc' => $request->id_tk_dc,
        //     'id_cn' => $request->id_cn,
        //     'cartTotal' => $request->cartTotal,
        //     'id_vc' => $request->id_vc,
        //     'id_sp_list' => $request->id_sp_list,
        // ];
        // $this->IndexController->print($requestData); die;

        // danh sách id_sp thanh toán
        $idList = explode(',', $request->id_sp_list);
        
        /*kiểm tra slton kho của sản phẩm. nếu 1 trong những sp trong giỏ hàng hết hàng
        thì trả về lỗi. ngược lại tiến hành thanh toán*/
        $checkout = true;
        foreach($idList as $id_sp){
            $qtyInCart = GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $id_sp)->first()->sl;
            // slton kho của sp
            $qtyInStock = KHO::where('id_sp', $id_sp)->sum('slton');

            if($qtyInStock === 0 || $qtyInStock < $qtyInCart){
                $checkout = false;
            }
        }

        // đã có sản phẩm hết hàng hoặc slton không đủ và trả về lỗi
        if(!$checkout){
            // xóa hàng đợi
            HANGDOI::where('id_tk', session('user')->id)->delete();
            return redirect()->route('user/thongbao')->with('message', $this->nofiticationContent);
        }
        // tiến hành thanh toán
        else {
            // lưu địa chỉ cho đơn hàng
            $orderAddress = null;
            if($request->receciveMethod == 'Giao hàng tận nơi'){
                $userAddress = TAIKHOAN_DIACHI::find($request->id_tk_dc);

                // sử dụng lại địa chỉ đơn hàng đã có
                $exists = DONHANG_DIACHI::where('hoten', $userAddress->hoten)
                                        ->where('diachi', $userAddress->diachi)
                                        ->where('phuongxa', $userAddress->phuongxa)
                                        ->where('quanhuyen', $userAddress->quanhuyen)
                                        ->where('tinhthanh', $userAddress->tinhthanh)
                                        ->where('sdt', $userAddress->sdt)->first();

                // nếu có
                if($exists){
                    $orderAddress = $exists;
                } else {
                    $orderAddress = DONHANG_DIACHI::create([
                        'hoten' => $userAddress->hoten,
                        'diachi' => $userAddress->diachi,
                        'phuongxa' => $userAddress->phuongxa,
                        'quanhuyen' => $userAddress->quanhuyen,
                        'tinhthanh' => $userAddress->tinhthanh,
                        'sdt' => $userAddress->sdt,
                    ]);
                }
            }

            $order = [
                'thoigian' => date('d/m/Y H:i:s'),
                'id_tk' => session('user')->id,
                'id_dh_dc' => $orderAddress ? $orderAddress->id : null,
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

                foreach($idList as $id_sp){
                    $product = $this->IndexController->getProductById($id_sp);
                    $qtyInCart = GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $id_sp)->first()->sl;

                    $detail = [
                        'id_dh' => $create->id,
                        'id_sp' => $product['id'],
                        'gia' => $product['gia'],
                        'sl' => $qtyInCart,
                        'giamgia' => $product['khuyenmai'] ? $product['khuyenmai'] : null,
                        'thanhtien' => $product['giakhuyenmai'] * $qtyInCart,
                    ];

                    CTDH::create($detail);

                    // giao hàng tận nơi
                    if($order['hinhthuc'] == 'Giao hàng tận nơi'){
                        // tỉnh thành của người dùng
                        $userCity = DONHANG_DIACHI::find($orderAddress->id)->tinhthanh;

                        // tỉnh thành thuộc bắc || nam
                        $file = file_get_contents('TinhThanh.json');
                        $lst_province = json_decode($file, true);
                        $province = [];
                        foreach($lst_province as $key){
                            if($key['Name'] == $userCity){
                                $province = $key;
                                break;
                            }
                        }

                        // chi nhánh tại Hà Nội
                        if($province['ID'] < 48){
                            $branch = CHINHANH::where('id_tt', TINHTHANH::where('tentt', 'like', 'Hà Nội')->first()->id)->first();
                        }
                        // chi nhánh tại Hồ Chí Minh
                        else {
                            $branch = CHINHANH::where('id_tt', TINHTHANH::where('tentt', 'like', 'Hồ Chí Minh')->first()->id)->first();
                        }

                        // Kho tại chi nhánh
                        $warehouse = KHO::where('id_cn', $branch->id)->where('id_sp', $detail['id_sp'])->first();

                        // slton
                        $slton = intval($warehouse->slton);

                        // cập nhật số lượng
                        // nếu kho tại chi nhánh không đủ thì lấy tiếp từ kho ở chi nhánh khác
                        if($warehouse->slton < $detail['sl']){
                            $missingQty = $detail['sl'] - $warehouse->slton;

                            $warehouse->slton = 0;
                            $warehouse->save();

                            // chi nhánh khác
                            $anotherBranch = KHO::where('id_cn', '!=', $branch->id)->where('id_sp', $detail['id_sp'])->first();

                            $anotherBranch->slton -= $missingQty;
                            $anotherBranch->save();
                        }
                        // ngược lại trừ số lượng kho tại chi nhánh bình thường
                        else {
                            $warehouse->slton -= $detail['sl'];
                            $warehouse->save();
                        }
                    }
                    // nhận tại cửa hàng
                    else{
                        // Kho
                        $warehouse = KHO::where('id_cn', $order['id_cn'])->where('id_sp', $detail['id_sp'])->first();

                        // slton
                        $slton = intval($warehouse->slton);

                        // cập nhật số lượng
                        $warehouse->slton = $slton - $detail['sl'];
                        $warehouse->save();
                    }
                }

                // gửi thông báo
                THONGBAO::create([
                    'id_tk' => session('user')->id,
                    'tieude' => 'Đơn đã tiếp nhận',
                    'noidung' => "Đã tiếp nhận đơn hàng <b>#$create->id</b> của bạn.",
                    'thoigian' => date('d/m/Y H:i'),
                    'trangthaithongbao' => 0,
                ]);

                // xóa sản phẩm đã thanh toán trong giỏ hàng
                foreach($idList as $id_sp) {
                    GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $id_sp)->delete();
                }

                // xóa voucher đã áp dụng
                if($request->id_vc){
                    $userVoucher = TAIKHOAN_VOUCHER::where('id_tk', session('user')->id)->where('id_vc', $request->id_vc)->first();
                    $qty = $userVoucher->sl;
                    if($qty == 1){
                        $userVoucher->delete();
                    } else {
                        $userVoucher->sl = --$qty;
                        $userVoucher->save();
                    }
                }

                // xóa voucher trong session
                Session::forget('voucher');

                // xóa hàng đợi
                HANGDOI::where('id_tk', session('user')->id)->delete();

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
            if(!session('user')){
                return [
                    'status' => 'login required'
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
                    'status' => 'new one',
                ];
            } 
            // kiểm tra cập nhật số lượng hoặc tạo mới
            else {
                foreach(TAIKHOAN::find(session('user')->id)->giohang as $cart){
                    // cập nhật số lượng sản phẩm trong giỏ hàng
                    if($cart->pivot->id_sp == $request->id_sp){
                        // slg sp trong giỏ hàng hiện tại
                        $sl = Intval(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()->sl);
                        // slg cộng thêm
                        $sl += $request->sl;

                        // số lượng tồn kho hiện tại
                        $qtyInStock = KHO::where('id_sp', $request->id_sp)->sum('slton');

                        // so sánh số lượng tồn với số lượng trong giỏ hàng
                        // mua quá số lượng || slg mua tối đa là 5
                        if($sl > $qtyInStock || $sl > 5){
                            return [
                                'status' => 'already have',
                                'qtyInStock' => $qtyInStock
                            ];
                        }

                        // cập nhật số lượng sp trong giỏ hàng
                        GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->update(['sl' => $sl]);
                        return [
                            'status' => 'update'
                        ];
                    }
                }

                // thêm sản phẩm mới vào giỏ hàng
                GIOHANG::create($data);
                return [
                    'status' => 'new one',
                ];
            }
        }
    }

    public function AjaxUpdateCart(Request $request)
    {
        if($request->ajax()){
            $data = [
                'status' => '',
                'newQty' => '',
                'newPrice' => '',
            ];

            $cart = GIOHANG::where('id', $request->id)->first();
            $id_msp = SANPHAM::find($cart->id_sp)->id_msp;
            $qty = intval($cart->sl);

            // tăng số lượng
            if($request->type == 'plus'){
                // kiểm tra số lượng tồn kho
                $qtyInStock = KHO::where('id_sp', $cart->id_sp)->select(DB::raw('sum(slton) as slton'))->first()->slton;

                if($qty < $qtyInStock) {
                    GIOHANG::where('id', $request->id)->update(['sl' => ++$qty]);
                }
                // slton < sl trong giỏ hàng
                else {
                    $data['status'] = 'not enough';
                    $data['qtyInStock'] = $qtyInStock;
                    return $data;
                }
            } 
            // giảm số lượng
            else {
                GIOHANG::where('id', $request->id)->update(['sl' => --$qty]);
            }

            // trả dữ liệu về view
            $data['newQty'] = $qty;
            $data['newPrice'] = intval($this->IndexController->getProductById($cart->id_sp)['giakhuyenmai'] * $qty);

            return $data;
        }
    }

    public function AjaxGetCartByIdProductList(Request $request)
    {
        if($request->ajax()) {
            $idList = $request->idList;
            $id_tk = session('user')->id;
            $data = [
                'productList' => [],
                'voucher' => session('voucher') ? $data['voucher'] = session('voucher') : null,
                'total' => 0,
            ];

            foreach($idList as $id_sp) {
                $product = $this->IndexController->getProductById($id_sp);
                // sl của sp trong giỏ hàng
                $qtyInCart = GIOHANG::where('id_tk', $id_tk)->where('id_sp', $id_sp)->first()->sl;
                // thành tiền của 1 sp
                $totalOfProduct = $product['giakhuyenmai'] * $qtyInCart;
                // tổng Tiền
                $data['total'] += $totalOfProduct;

                $product['sl'] = $qtyInCart;
                $product['thanhtien'] = $totalOfProduct;

                array_push($data['productList'], $product);
            }

            return $data;
        }
    }

    public function AjaxGetProvisionalOrder(Request $request)
    {
        if($request->ajax()) {
            $response = [
                'provisional' => 0,
                'voucher' => session('voucher') ? session('voucher') : null
            ];

            if(empty($request->idList)) {
                return $response;
            }
            
            $id_tk = session('user')->id;

            foreach($request->idList as $id_sp) {
                $product = $this->IndexController->getProductById($id_sp);

                if($product['trangthai']) {
                    $qtyInStock = KHO::where('id_sp', $id_sp)->sum('slton');
    
                    if($qtyInStock > 0) {
                        $price = $product['giakhuyenmai'];
                        $qtyInCart = GIOHANG::where('id_tk', $id_tk)->where('id_sp', $id_sp)->first()->sl;
                        $response['provisional'] += $price * $qtyInCart;
                    }
                }
            }

            return $response;
        }
    }
}
