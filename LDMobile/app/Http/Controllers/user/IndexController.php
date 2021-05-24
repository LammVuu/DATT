<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;

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
        return view($this->user."san-pham");
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
            "app_id" => 2553,
            "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
            "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = '{}'; // Merchant's data
        $items = '[]'; // Merchant's data
        $transID = rand(0,1000000); //Random trans id
        $order = [
            "app_id" => $config["app_id"],
            "app_user" => "Hoàng Lâm",
            "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_time" => round(microtime(true) * 1000), // miliseconds
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

        //return $result;

        // foreach ($result as $key => $value) {
        //     echo "$key: $value<br>";
        // }
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
