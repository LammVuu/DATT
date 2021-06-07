<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;

use App\Models\BANNER;
use App\Models\BAOHANH;
use App\Models\CHINHANH;
use App\Models\CTDG;
use App\Models\CTDH;
use App\Models\DANHGIASP;
use App\Models\DONHANG;
use App\Models\GIOHANG;
use App\Models\HINHANH;
use App\Models\IMEI;
use App\Models\KHO;
use App\Models\KHUYENMAI;
use App\Models\LUOTTHICH;
use App\Models\MAUSP;
use App\Models\NHACUNGCAP;
use App\Models\PHANHOI;
use App\Models\SANPHAM;
use App\Models\SLIDESHOW_CTMSP;
use App\Models\SLIDESHOW;
use App\Models\SP_YEUTHICH;
use App\Models\TAIKHOAN_DIACHI;
use App\Models\TAIKHOAN_VOUCHER;
use App\Models\TAIKHOAN;
use App\Models\THONGBAO;
use App\Models\TINHTHANH;
use App\Models\VOUCHER;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    public function Index(){
        /*=================================
                    Slideshow
        ===================================*/
        $i = 0;

        foreach(SLIDESHOW::all() as $key){
            $lst_slide[$i] = [
                'id' => $key->id,
                'link' => $key->link,
                'hinhanh' => $key->hinhanh,
            ];

            $i++;
        }

        /*=================================
                    Banner
        ===================================*/
        $qty_banner = count(BANNER::all());
        $i = 0;

        foreach(BANNER::all() as $key){
            $lst_banner[$i] = [
                'id' => $key->id,
                'link' => $key->link,
                'hinhanh' => $key->hinhanh,
            ];

            $i++;
        }


        /*=================================
                    hotsale
        ===================================*/

        $lst_temp = $this->getAllProductsByCapacity();

        // sắp xếp khuyến mãi giảm dần
        $lst_product = $this->sortProductsByPromotionASC($lst_temp);

        // lấy 10 sản phẩm có khuyến mãi cao nhất
        for($i = 0; $i < 10; $i++){
            $lst_promotion[$i] = $lst_product[$i];
        }

        /*=================================
                    featured
        ===================================*/

        $lst_model = MAUSP::orderBy('id', 'desc')->take(5)->get();
        $i = 0;

        foreach($lst_model as $model){
            // danh sách sản phẩm theo id_msp
            $SANPHAM = MAUSP::find($model->id)->sanpham;

            // lấy mẫu sp theo dung lượng
            $lst_temp = $this->getProductsByCapacity($SANPHAM);

            foreach($lst_temp as $key){
                $lst_featured[$i] = [
                    // thông tin sản phẩm
                    'id' => $key['id'],
                    'tensp' => $key['tensp'],
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
    
                    // khuyến mãi
                    'khuyenmai' => $key['khuyenmai'],
    
                    // đánh giá
                    'danhgia' => $key['danhgia'],
                ];
                $i++;
            }
        }

        $data = [
            // slideshow
            'lst_slide' => $lst_slide,
            'url_slide' => 'images/slideshow/',
            'qty_slide' => count($lst_slide),

            // banner
            'lst_banner' => $lst_banner,
            'url_banner' => 'images/banner/',
            'qty_banner' => count($lst_banner),

            // list hotsale
            'lst_promotion' => $lst_promotion,
            'url_phone' => 'images/phone/',

            // list featured
            'lst_featured' => $lst_featured,
        ];


        return view($this->user."index")->with($data);
    }

    public function DienThoai(){

        $lst_product = $this->getAllProductsByCapacity();

        // số lượng sản phẩm
        $qty = count($lst_product);
        
        // echo '<pre>';
        // print_r($lst_product);
        // echo '</pre>';
        // return false;
        
        $data = [
            'lst_product' => $lst_product,
            'qty' => $qty,
            'url_phone' => 'images/phone/',
        ];

        return view($this->user."dien-thoai")->with($data);
    }

    public function ChiTiet($id){
        /**==============================================================================================
         *                                              Phone
         * ==============================================================================================*/

        // điện thoại theo id;
        $SANPHAM = SANPHAM::where('id', $id)->first();

        // nếu id không hợp lệ
        if(!isset($SANPHAM)){
            return redirect()->route('user/dien-thoai');
        }

        // mã mẫu sp
        $id_msp = $SANPHAM->id_msp;

        // dung lượng
        $capacity = $SANPHAM->dungluong;

        // ram
        $ram = $SANPHAM->ram;

        // các điện thoại cùng mẫu
        $phones = MAUSP::find($id_msp)->sanpham;

        // các điện thoại cùng mẫu theo dung lượng
        $phonesByCapacity = $this->getProductsByCapacity($phones);

        // điện thoại theo id
        $phone = [
            'id' => $SANPHAM->id,
            'tensp' => $SANPHAM->tensp.' '.$SANPHAM->dungluong,
            'id_msp' => $SANPHAM->id_msp,
            'hinhanh' => $SANPHAM->hinhanh,
            'mausac' => $SANPHAM->mausac,
            'ram' => $SANPHAM->ram,
            'dungluong' => $SANPHAM->dungluong,
            'gia' => $SANPHAM->gia,
            'cauhinh' => $SANPHAM->cauhinh,
            'trangthai' => $SANPHAM->trangthai,
        ];

        // lấy đánh giá, khuyến mãi của mẫu sp
        foreach($phonesByCapacity as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                $phone['khuyenmai'] = $key['khuyenmai'];
                $phone['danhgia'] = $key['danhgia'];
            }
        }

        /**==============================================================================================
        *                                          product variation
        * ==============================================================================================*/

        $lst_variation = [
            'capacity' => [],
            'color' => [],
        ];

        $i = 0;
        // lấy dung lượng biến thể
        foreach($phonesByCapacity as $key){
            if(count($lst_variation['capacity']) != 0){
                if($key['dungluong'] == $lst_variation['capacity'][0]['dungluong']){
                    continue;
                }
            }
            $lst_variation['capacity'][$i] = [
                'id_sp' => $key['id'],
                'dungluong' => $key['dungluong'],
                'gia' => $key['gia'],
            ];

            $i++;
        }

        $i = 0;
        // lấy màu sắc biến thể
        foreach($phones as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                $lst_variation['color'][$i] = [
                    'id_sp' => $key['id'],
                    'mausac' => $key['mausac'],
                    'gia' => $key['gia'],
                ];
                $i++;
            }
        }

        // hình ảnh mẫu sp
        $modelImages = MAUSP::find($id_msp)->hinhanh;

        /**==============================================================================================
        *                                          Detail Evaluate
        * ==============================================================================================*/

        $samePhones = [];
        $i = 0;

        // các điện thoại cùng dung lượng
        foreach($phones as $key){
            if($key['dungluong'] == $capacity){
                $samePhones[$i] = $key;
                $i++;
            }
        }

        // lấy đánh giá của mẫu sp theo dung lượng
        $lst_evaluate;
        $i = 0;
        
        foreach($samePhones as $key){
            $DANHGIASP = SANPHAM::find($key['id'])->danhgiasp;

            if(count($DANHGIASP) == 0){
                continue;
            }

            foreach($DANHGIASP as $evaluate){
                $lst_evaluate[$i] = [
                    'id' => $evaluate->pivot->id,
                    'taikhoan' => $this->getAccountById($evaluate->pivot->id_tk),
                    'sanpham' => $this->getProductById($evaluate->pivot->id_sp),
                    'noidung' => $evaluate->pivot->noidung,
                    'thoigian' => $evaluate->pivot->thoigian,
                    'soluotthich' => $evaluate->pivot->soluotthich,
                    'danhgia' => $evaluate->pivot->danhgia,
                    'trangthai' => $evaluate->pivot->trangthai,
                ];
                $i++;
            }
        }

        if(!isset($lst_evaluate)){
            $lst_evaluate = [];
        }


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
            'phone' => $phone,
            'samePhones' => $samePhones,
            'url_phone' => 'images/phone/',
            'url_json' => 'json/',
            'modelImages' => $modelImages,
            'lst_evaluate' => $lst_evaluate,
            'lst_variation' => $lst_variation,

            'evaluate' => $evaluate,
            'lstKhuVuc' => $lstKhuVuc,
            'lstChiNhanh' => $lstChiNhanh,
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

    /*==========================================================================================================
                                                index function                                                            
    ============================================================================================================*/

    public function print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    // lấy sao đánh giá, số lượt đánh giá của mẫu sản phẩm theo dung lượng
    public function starRatingByCapacity($lst_product)
    {
        $lst_evaluate = [];
        $i = 0;

        // tính sao đánh giá từng sản phẩm
        foreach($lst_product as $key){
            $evaluate = $key['danhgia'];
            if($evaluate['qty'] == 0){
                continue;
            }

            $lst_evaluate[$i] = $evaluate;
            $i++;
        }

        // chưa có đánh giá
        if(count($lst_evaluate) == 0){
            return [
                'qty' => 0,
                'star' => 0,
            ];
        }

        // tính sao đánh giá mẫu sản phẩm theo dung lượng
        // công thức: trung bình tổng các sao đánh giá sản phẩm
        $total = 0;

        // tổng số lượng đánh giá của mẫu sp theo dung lượng
        $qtyEvaluate = 0;

        // số lượng sp có đánh giá
        $qty = 0;

        foreach($lst_evaluate as $key){
            $qtyEvaluate += $key['qty'];
            $total += $key['star'];
            $qty++;
        }

        // tổng đánh giá sao
        $totalEvaluate = round(($total / $qty), 1);

        return [
            'qty' => $qtyEvaluate,
            'star' => $totalEvaluate,
        ];
    }

    // lấy sao đánh giá, số lượt đánh giá của sản phẩm theo id_sp
    public function starRatingProduct($id_sp)
    {
        // danh sách đánh giá theo id_sp
        $lst_evaluate = SANPHAM::find($id_sp)->danhgiasp;

        // số lượng đánh giá
        $qty = count($lst_evaluate);

        if($qty == 0){
            return [
                'qty' => 0,
                'star' => 0
            ];
        }

        $_1s = 0; $_2s = 0; $_3s = 0; $_4s = 0; $_5s = 0;

        foreach($lst_evaluate as $key){
            if($key->pivot->danhgia == 1){
                $_1s++;
            } elseif($key->pivot->danhgia == 2){
                $_2s++;
            } elseif($key->pivot->danhgia == 3){
                $_3s++;
            } elseif($key->pivot->danhgia == 4){
                $_4s++;
            } else {
                $_5s++;
            }
        }

        $star = round(((($_1s * 1) + ($_2s * 2) + ($_3s * 3) + ($_4s * 4) + ($_5s * 5)) / $qty),1);

        return [
            'qty' => $qty,
            'star' => $star,
        ];
    }

    // lấy danh sách mẫu sp theo dung lượng
    public function getAllProductsByCapacity()
    {
        $lst_model = MAUSP::all();
        $i = 0;

        foreach($lst_model as $model){
            // danh sách sản phẩm theo id_msp
            $SANPHAM = MAUSP::find($model->id)->sanpham;

            // lấy mẫu sp theo dung lượng
            $lst_temp = $this->getProductsByCapacity($SANPHAM);

            foreach($lst_temp as $key){
                $lst_product[$i] = [
                    // thông tin sản phẩm
                    'id' => $key['id'],
                    'tensp' => $key['tensp'],
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
    
                    // khuyến mãi
                    'khuyenmai' => $key['khuyenmai'],
    
                    // đánh giá
                    'danhgia' => $key['danhgia'],
                ];
                $i++;
            }
        }

        return $lst_product;
    }

    // lấy mẫu sp theo dung lượng
    public function getProductsByCapacity($lst)
    {
        // dung lượng: 64 GB / 128 GB
        $capacity = $lst[0]['dungluong'];
        $ram = $lst[0]['ram'];

        $lst_temp = [];
        $i = 0;

        // lọc mảng sp theo dung lượng
        foreach($lst as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                if($this->promotionCheck($key['id'])){
                    $promotion = SANPHAM::find($key['id'])->khuyenmai->chietkhau;
                }

                $lst_temp[$capacity.'_'.$ram][$i] = [
                    'id' => $key['id'],
                    'tensp' => $key['tensp'] . ' ' . $key['dungluong'],
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'danhgia' => $this->starRatingProduct($key['id']),
                    'cauhinh' => $key['cauhinh'],
                    'trangthai' => $key['trangthai'],
                ];

                $i++;
            } else {
                $capacity = $key['dungluong'];
                $ram = $key['ram'];

                $i = 0;

                if($this->promotionCheck($key['id'])){
                    $promotion = SANPHAM::find($key['id'])->khuyenmai->chietkhau;
                }

                $lst_temp[$capacity.'_'.$ram][$i] = [
                    'id' => $key['id'],
                    'tensp' => $key['tensp'] . ' ' . $key['dungluong'],
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'danhgia' => $this->starRatingProduct($key['id']),
                    'cauhinh' => $key['cauhinh'],
                    'trangthai' => $key['trangthai'],
                ];

                $i++;
            }
        }

        //return $lst_temp[array_keys($lst_temp)[0]];

        $i = 0;
        
        for($loop = 0; $loop < count($lst_temp); $loop++){
            $array = $lst_temp[array_keys($lst_temp)[$loop]];
            
            $rand = mt_rand(0, count($array) - 1);

            $lst_product[$i] = [
                'id' => $array[$rand]['id'],
                'tensp' => $array[$rand]['tensp'],
                'hinhanh' => $array[$rand]['hinhanh'],
                'mausac' => $array[$rand]['mausac'],
                'ram' => $array[$rand]['ram'],
                'dungluong' => $array[$rand]['dungluong'],
                'gia' => $array[$rand]['gia'],
                'khuyenmai' => $array[$rand]['khuyenmai'],
                'danhgia' => $this->starRatingByCapacity($array),
                'cauhinh' => $array[$rand]['cauhinh'],
                'trangthai' => $array[$rand]['trangthai'],
            ];

            $i++;
        }

        return $lst_product;
    }

    // lấy sp theo id_sp
    public function getProductById($id_sp)
    {
        $temp = SANPHAM::where('id', $id_sp)->first();

        $product = [
            'id' => $id_sp,
            'tensp' => $temp->tensp,
            'id_msp' => $temp->id_msp,
            'hinhanh' => $temp->hinhanh,
            'mausac' => $temp->mausac,
            'ram' => $temp->ram,
            'dungluong' => $temp->dungluong,
            'gia' => $temp->gia,
            'id_km' => $temp->id_km,
            'cauhinh' => $temp->cauhinh,
            'trangthai' => $temp->trangthai,
        ];

        return $product;
    }

    // kiểm tra còn hạn khuyến mãi không
    public function promotionCheck($id_sp)
    {
        $warranty = SANPHAM::find($id_sp)->khuyenmai->ngayketthuc;
        $today = date('d/m/Y');

        return $warranty >= $today ? true : false;
    }

    // sắp xếp danh sách sp theo khuyến mãi giảm dần
    public function sortProductsByPromotionASC($lst)
    {
        for($i = 0; $i < count($lst) - 1; $i++){
            for($j = $i+1; $j < count($lst); $j++){
                if($lst[$i]['khuyenmai'] < $lst[$j]['khuyenmai']){
                    $temp = $lst[$i];
                    $lst[$i] = $lst[$j];
                    $lst[$j] = $temp;
                }
            }
        }

        return $lst;
    }

    /*==========================================================================================================
                                                detail function                                                            
    ============================================================================================================*/

    // lấy tài khoản theo id_tk
    public function getAccountById($id_tk)
    {
        $temp = TAIKHOAN::where('id', $id_tk)->first();
        
        $account = [
            'id' => $temp->id,
            //'sdt' => $temp->sdt,
            //'password' => $temp->password,
            //'email' => $temp->email,
            'hoten' => $temp->hoten,
            'anhdaidien' => $temp->anhdaidien,
            'anhbia' => $temp->anhbia,
            //'loaitk' => $temp->loaitk,
            //'htdn' => $temp->htdn,
            //'trangthai' => $temp->trangthai,
        ];

        return $account;
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
