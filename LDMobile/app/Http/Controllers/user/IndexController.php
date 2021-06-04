<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;

use App\Models;

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
        $SANPHAM = Models\SANPHAM::class;
        $MAUSP = Models\MAUSP::class;

        $lst_model = $MAUSP::all();
        // $lst_product = [];
        // $i = 0;

        // echo '<pre>';
        // foreach($lst_model as $model){
        //     $id_msp =  $model['id'];
        //     $sanpham = $SANPHAM::where('id_msp', $id_msp)->first();

        //     $temp = [
        //         'id' => $sanpham->id,
        //         'tensp' => $sanpham->tensp,
        //         'hinhanh' => $sanpham->hinhanh,
        //         'mausac' => $sanpham->mausac,
        //         'ram' => $sanpham->ram,
        //         'dungluong' => $sanpham->dungluong,
        //         'gia' => $sanpham->gia,
        //         'id_km' => $sanpham->id_km,
        //     ];

        //     $lst_product[$i] = $temp;
            
        //     $i++;
        // }
        // print_r($lst_product);
        // echo '</pre>';
        // return false;

        $url_img = 'images/phone/';

        $data = [
            'lst_product' => $SANPHAM::all(),
            'url_img' => $url_img,
        ];

        return view($this->user."san-pham")->with($data);
    }

    public function ChiTiet(){
        $lstChiNhanh = [
            [
                'ID' => '0',
                'DiaChi' => '403/10, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '1',
                'DiaChi' => '403/9, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '2',
                'DiaChi' => '403/8, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '3',
                'DiaChi' => '403/7, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '1'
            ],
            [
                'ID' => '4',
                'DiaChi' => '403/6, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '1'
            ]
        ];

        $lstKhuVuc = [
            [
                'ID' => '0',
                'TenTT' => 'Hà Nội',
            ],
            [
                'ID' => '1',
                'TenTT' => 'Hồ Chí Minh',
            ],
        ];

        $evaluate = [
            'total-rating' => '100',
            'rating' => [
                '5' => '90',
                '4' => '5',
                '3' => '5',
                '2' => '0',
                '1' => '0',
            ],
        ];

        $data = [
            'evaluate' => $evaluate,
            'lstKhuVuc' => $lstKhuVuc,
            'lstChiNhanh' => $lstChiNhanh
        ];

        return view($this->user."chi-tiet-san-pham")->with($data);
    }

    public function ThanhToan(){
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $lstChiNhanh = [
            [
                'ID' => '0',
                'DiaChi' => '403/10, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '1',
                'DiaChi' => '403/9, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '2',
                'DiaChi' => '403/8, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '0'
            ],
            [
                'ID' => '3',
                'DiaChi' => '403/7, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '1'
            ],
            [
                'ID' => '4',
                'DiaChi' => '403/6, Lê Văn Sỹ, Phường 02, Quận Tân Bình',
                'ID_TT' => '1'
            ]
        ];

        $lstKhuVuc = [
            [
                'ID' => '0',
                'TenTT' => 'Hà Nội',
            ],
            [
                'ID' => '1',
                'TenTT' => 'Hồ Chí Minh',
            ],
        ];

        $data = [
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
            'lstKhuVuc' => $lstKhuVuc,
            'lstChiNhanh' => $lstChiNhanh
        ];
        
        return view($this->user."thanh-toan")->with($data);
    }

    public function LienHe()
    {
        return view($this->user.'lien-he');
    }

    public function VeChungToi()
    {
        return view($this->user.'ve-chung-toi');
    }

    public function createDataAPI()
    {
        //========================== tỉnh thành ===================

        // $response = Http::get('https://api.mysupership.vn/v1/partner/areas/province')->json();
        // $TinhThanh = $response['results'];
        // $count = count($TinhThanh);

        // $data = [];
        
        // for($i = 0; $i < $count; $i++){ 
        //     $data[$i]['ID'] = $TinhThanh[$i]['code'];
        //     $data[$i]['Name'] = $TinhThanh[$i]['name'];
        // }

        // sort($data);

        // $fileName = 'TinhThanh.json';
        // $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        // file_put_contents($fileName, $json);
        
        
        // echo '<pre>';
        // print_r('ok');
        // echo '</pre>';

        //============================== Quận huyện =============================
        
        // $data = [];
        // $id;
        // for($i = 1; $i <= 96; $i++){
        //     if($i < 10){
        //         $id = '0' . $i;
        //         $url = 'https://api.mysupership.vn/v1/partner/areas/district?province=' . $id;
        //     } else {
        //         $id = $i;
        //         $url = 'https://api.mysupership.vn/v1/partner/areas/district?province=' . $id;
        //     }
            
            
        //     $response = Http::get($url)->json();
        //     $response = $response['results'];

        //     $count = count($response);

        //     if($count == 0) continue;

        //     $temp = [];

        //     for($j = 0; $j < $count; $j++){ 
        //         $temp[$j]['ID'] = $response[$j]['code'];
        //         $temp[$j]['Name'] = $response[$j]['name'];
        //         $temp[$j]['province'] = $response[$j]['province'];
        //     }

        //     sort($temp);

        //     $data[$id] = $temp;
        // }
        

        // $fileName = 'QuanHuyen.json';
        // $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        // file_put_contents($fileName, $json);

        // echo '<pre>';
        // print_r('ok');
        // echo '</pre>';
        
        //============================== phường xã =============================

        // $data = [];
        // $id;
        // for($i = 900; $i <= 973; $i++){
        //     if($i < 10){
        //         $id = '00' . $i;
        //         $url = 'https://api.mysupership.vn/v1/partner/areas/commune?district=' . $id;
        //     } elseif($i < 100) {
        //         $id = '0' . $i;
        //         $url = 'https://api.mysupership.vn/v1/partner/areas/commune?district=' . $id;
        //     } else {
        //         $id = $i;
        //         $url = 'https://api.mysupership.vn/v1/partner/areas/commune?district=' . $id;
        //     }
            
            
        //     $response = Http::get($url)->json();
        //     $response = $response['results'];

        //     $count = count($response);

        //     if($count == 0) continue;

        //     $temp = [];

        //     for($j = 0; $j < $count; $j++){ 
        //         $temp[$j]['ID'] = $response[$j]['code'];
        //         $temp[$j]['Name'] = $response[$j]['name'];
        //         $temp[$j]['District'] = $response[$j]['district'];
        //         $temp[$j]['Province'] = $response[$j]['province'];
        //     }

        //     sort($temp);

        //     $data[$id] = $temp;
        // }
        

        // $fileName = 'PhuongXa7.json';
        // $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        // file_put_contents($fileName, $json);

        // echo '<pre>';
        // print_r('ok');
        // echo '</pre>';
        
            

        // return false;
    }

    public function SoSanh(){
        return view($this->user.'so-sanh');
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

    public function ThanhCong()
    {
        return view($this->user.'thanh-cong');
    }

    public function TraCuu()
    {
        return view($this->user.'tra-cuu');
    }

    public function test(Request $request){
        if($request->ajax()){    
            $data = $request->image;
            $image_arr_1 = explode(';', $data);
            $image_arr_2 = explode(',', $image_arr_1[1]);

            $data = base64_decode($image_arr_2[1]);

            $image_name = 'images/avt' . time() . '.jpg';

            file_put_contents($image_name, $data);
            
            $data = base64_decode($image_name, $data);

            return $image_name;
        }
    }

    public function test2(Request $request)
    {   
        if($request->ajax()){
            $type = $request->type;
            $id = $request->id;

            if($type == 'TinhThanh'){
                $file = file_get_contents('QuanHuyen.json');
                $quanHuyen = json_decode($file, true)[$id];

                $name = array_column($quanHuyen, 'Name');
                array_multisort($name, SORT_ASC, $quanHuyen);

                return $quanHuyen;
            } else {
                $file = file_get_contents('PhuongXa.json');
                $phuongXa = json_decode($file, true)[$id];

                $name = array_column($phuongXa, 'Name');
                array_multisort($name, SORT_ASC, $phuongXa);

                return $phuongXa;
            }
        }
    }

    public function test3(Request $request)
    {
        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = '{}'; // Merchant's data
        $items = '[]'; // Merchant's data
        $transID = rand(0,1000000); //Random trans id
        $order = [
            "app_id" => $config["app_id"],
            "app_user" => "Hoàng Lâm",
            "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            // "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_time" => 1000000, // miliseconds
            "amount" => 100000,
            "item" => $items,
            "embed_data" => $embeddata,
            
            "description" => "Lazada - Payment for the order #$transID",
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

    public function test4(Request $request)
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

    // hàm loại bỏ ký tự có dấu
    public function unaccent($str) {

        $transliteration = [
            'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'đ' => 'd',
            'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        ];

        $str = str_replace( array_keys( $transliteration ),
                            array_values( $transliteration ),
                            $str);
        return $str;
    }
}
