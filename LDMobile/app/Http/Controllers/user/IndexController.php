<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use File;

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

        $lst_temp = $this->getAllProductByCapacity();

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
            $lst_temp = $this->getProductByCapacity($SANPHAM);

            foreach($lst_temp as $key){
                $lst_featured[$i] = $key;
                $i++;
            }
        }

        $data = [
            // slideshow
            'lst_slide' => $lst_slide,
            'qty_slide' => count($lst_slide),

            // banner
            'lst_banner' => $lst_banner,

            // list hotsale
            'lst_promotion' => $lst_promotion,

            // list featured
            'lst_featured' => $lst_featured,
        ];


        return view($this->user."index")->with($data);
    }

    // tìm kiếm điện thoại
    public function AjaxSearchPhone(Request $request)
    {
        if($request->ajax()){
            $allProduct = $this->getAllProductByCapacity();

            $val = $this->unaccent($request->str);
            $lst_product = [
                'phone' => [],
                'url_phone' => 'images/phone/',
            ];
            $i = 0;

            foreach($allProduct as $product){
                if(str_contains(strtolower($product['tensp']), $val)){
                    $lst_product['phone'][$i] = $product;
                    $i++;
                }
            }

            return $lst_product;
        }

        return false;
    }

    // lọc sản phẩm
    public function AjaxFilterProduct(Request $request)
    {
        if($request->ajax()){
            $dataFilter = $request->dataFilter;

            // nếu gỡ bỏ hết bộ lọc thì trả về tất cả sản phẩm
            if(empty($dataFilter)){
                return $this->getAllProductByCapacity();
            }

            $time_filter = [];
            $idxTime = 0;

            $lst_product = [];
            $i = 0;

            // lọc theo tiêu chí đầu tiên
            // lấy mảng dữ liệu được lọc, sử dụng tiếp cho các lần lọc tiếp theo
            if(array_keys($dataFilter)[0] == 'brand'){
                foreach($dataFilter[array_keys($dataFilter)[0]] as $supplier){
                    $lst_temp = $this->getAllProductBySupplierId(NHACUNGCAP::where('tenncc', 'like', $supplier.'%')->first()->id);
                    foreach($lst_temp as $key){
                        $lst_product[$i] = $key;
                        $i++;
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } elseif(array_keys($dataFilter)[0] == 'price'){
                $lst_temp = $this->getAllProductByCapacity();

                foreach($dataFilter[array_keys($dataFilter)[0]] as $price){
                    if($price == '2'){
                        foreach($lst_temp as $product){
                            if($product['gia'] < 2000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } elseif($price == '3-4'){
                        foreach($lst_temp as $product){
                            if($product['gia'] >= 3000000 && $product['gia'] <= 4000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } elseif($price == '4-7'){
                        foreach($lst_temp as $product){
                            if($product['gia'] >= 4000000 && $product['gia'] <= 7000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } elseif($price == '7-13'){
                        foreach($lst_temp as $product){
                            if($product['gia'] >= 7000000 && $product <= 13000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } elseif($price == '13-20'){
                        foreach($lst_temp as $product){
                            if($product['gia'] >= 13000000 && $product['gia'] <= 20000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } elseif($price == '20'){
                        foreach($lst_temp as $product){
                            if($product['gia'] >= 20000000){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } elseif(array_keys($dataFilter)[0] == 'os'){
                foreach($dataFilter[array_keys($dataFilter)[0]] as $os){
                    if($os == 'Android'){
                        foreach(NHACUNGCAP::where('id', '!=' , NHACUNGCAP::where('tenncc', 'like', 'Apple%')->first()->id)->get() as $supplier){
                            foreach($this->getAllProductBySupplierId($supplier['id']) as $product){
                                $lst_product[$i] = $product;
                                $i++;
                            }
                        }
                    } else {
                        foreach($this->getAllProductBySupplierId(NHACUNGCAP::where('tenncc', 'like', 'Apple%')->first()->id) as $product){
                            $lst_product[$i] = $product;
                            $i++;
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } elseif(array_keys($dataFilter)[0] == 'ram'){
                $lst_temp = $this->getAllProductByCapacity();
                foreach($dataFilter[array_keys($dataFilter)[0]] as $os){
                    foreach($lst_temp as $product){
                        if(strcmp(explode(' ', $product['ram'])[0].explode(' ', $product['ram'])[1], $os) == 0){
                            $lst_product[$i] = $product;
                            $i++;
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } else{
                $lst_temp = $this->getAllProductByCapacity();
                foreach($dataFilter[array_keys($dataFilter)[0]] as $os){
                    foreach($lst_temp as $product){
                        if(strcmp(explode(' ', $product['dungluong'])[0].explode(' ', $product['dungluong'])[1], $os) == 0){
                            $lst_product[$i] = $product;
                            $i++;
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            }

            // nếu chỉ có 1 tiêu chí lọc thì trả về kết quả
            if(count($dataFilter) == 1){
                return $time_filter[0];
            }

            unset($lst_product);

            // lọc tiếp tục các tiêu chí khác
            for($i = 1; $i < count($dataFilter); $i++){
                $lst_product = [];
                $j = 0;

                if(array_keys($dataFilter)[$i] == 'brand'){
                    foreach($dataFilter[array_keys($dataFilter)[$i]] as $supplier){
                        foreach(NHACUNGCAP::find(NHACUNGCAP::where('tenncc', 'like', $supplier.'%')->first()->id)->mausp as $model){
                            foreach($time_filter[$i - 1] as $product){
                                if($product['id_msp'] == $model['id']){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        }
                    }
                    $time_filter[$idxTime] = $lst_product;
                    $idxTime++;
                } elseif(array_keys($dataFilter)[$i] == 'price'){
                    foreach($dataFilter[array_keys($dataFilter)[$i]] as $price){
                        if($price == '2'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) < 2000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        } elseif($price == '3-4'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) >= 3000000 && intval($product['gia']) <= 4000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        } elseif($price == '4-7'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) >= 4000000 && intval($product['gia']) <= 7000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        } elseif($price == '7-13'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) >= 7000000 && intval($product['gia']) <= 13000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        } elseif($price == '13-20'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) >= 13000000 && intval($product['gia']) <= 20000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        } elseif($price == '20'){
                            foreach($time_filter[$i - 1] as $product){
                                if(intval($product['gia']) >= 20000000){
                                    $lst_product[$j] = $product;
                                    $j++;
                                }
                            }
                        }
                    }
                    $time_filter[$idxTime] = $lst_product;
                    $idxTime++;
                } elseif(array_keys($dataFilter)[$i] == 'os'){
                    foreach($dataFilter[array_keys($dataFilter)[$i]] as $os){
                        if($os == 'Android'){
                            foreach(NHACUNGCAP::where('id', '!=', NHACUNGCAP::where('tenncc', 'like', 'Apple%')->first()->id)->get() as $supplier){
                                foreach(NHACUNGCAP::find($supplier['id'])->mausp as $model){
                                    foreach($time_filter[$i - 1] as $product){
                                        if($product['id_msp'] == $model['id']){
                                            $lst_product[$j] = $product;
                                            $j++;
                                        }
                                    }
                                }
                            }
                            $time_filter[$idxTime] = $lst_product;
                            $idxTime++;
                        } else {
                            foreach(NHACUNGCAP::find(NHACUNGCAP::where('tenncc', 'like', 'Apple%')->first()->id)->mausp as $model){
                                foreach($time_filter[$i - 1] as $product){
                                    if($product['id_msp'] == $model['id']){
                                        $lst_product[$j] = $product;
                                        $j++;
                                    }
                                }
                            }
                            $time_filter[$idxTime] = $lst_product;
                            $idxTime++;
                        }
                    }
                } elseif(array_keys($dataFilter)[$i] == 'ram'){
                    foreach($dataFilter[array_keys($dataFilter)[$i]] as $ram){
                        foreach($time_filter[$i - 1] as $product){
                            if(strcmp(explode(' ', $product['ram'])[0].explode(' ', $product['ram'])[1], $ram) == 0){
                                $lst_product[$j] = $product;
                                $j++;
                            }
                        }
                    }
                    $time_filter[$idxTime] = $lst_product;
                    $idxTime++;
                } elseif(array_keys($dataFilter)[$i] == 'capacity'){
                    foreach($dataFilter[array_keys($dataFilter)[$i]] as $capacity){
                        foreach($time_filter[$i - 1] as $product){
                            if(strcmp(explode(' ', $product['dungluong'])[0].explode(' ', $product['dungluong'])[1], $capacity) == 0){
                                $lst_product[$j] = $product;
                                $j++;
                            }
                        }
                    }
                    $time_filter[$idxTime] = $lst_product;
                    $idxTime++;
                }
            }

            // trả về danh sách kết quả cuối cùng
            return $time_filter[count($time_filter) - 1];
        }

        return false;
    }

    public function DienThoai(){

        $lst_product = $this->getAllProductByCapacity();

        // các loại ram hiện có
        $lst_ram = $this->getRamAllProduct($this->getAllProductByCapacity());

        // các loại dung lượng hiện có
        $lst_capacity = $this->getCapacityAllProduct($this->getAllProductByCapacity());

        // số lượng sản phẩm
        $qty = count($lst_product);
        
        $data = [
            'lst_product' => $lst_product,
            'qty' => $qty,
            'fs_title' => $qty . ' điện thoại',
            'lst_ram' => $lst_ram,
            'lst_capacity' => $lst_capacity,
        ];

        return view($this->user."dien-thoai")->with($data);
    }

    public function TimKiemDienThoai($name = null)
    {
        if($name == null){
            return redirect()->route('user/index');
        }

        $lst_product = [];
        $i = 0;

        // danh sách sản phẩm theo tên được tìm kiếm
        foreach($this->getAllProductByCapacity() as $key){
            if(str_contains(strtolower($key['tensp']), $name)){
                $lst_product[$i] = $key;
                $i++;
            }
        }
        
        $data = [
            'name' => $name,
            'lst_product' => $lst_product,
        ];

        return view($this->user.'tim-kiem')->with($data);
    }

    public function DienThoaiTheoHang($brand)
    {
        $model = MAUSP::where('id_ncc', NHACUNGCAP::where('tenncc', 'like', $brand.'%')->first()->id)->get();

        $lst_product = [];
        $i = 0;

        foreach($model as $key){
            $lst_temp = $this->getProductByCapacity(SANPHAM::where('id_msp', $key['id'])->get());

            foreach($lst_temp as $phone){
                $lst_product[$i] = $phone;
                $i++;
            }
        }

        // các loại ram hiện có
        $lst_ram = $this->getRamAllProduct($lst_product);

        // các loại dung lượng hiện có
        $lst_capacity = $this->getCapacityAllProduct($lst_product);
        
        $data = [
            'lst_product' => $lst_product,
            'qty' => count($lst_product),
            'fs_title' => count($lst_product) . ' điện thoại ' . $brand,
            'brand' => $brand,
            'lst_ram' => $lst_ram,
            'lst_capacity' => $lst_capacity,
        ];


        return view($this->user.'dien-thoai')->with($data);
    }

    public function ChiTiet($name){
        $phoneName = $this->getPhoneNameByString($name);

        if(!$phoneName){
            return redirect()->route('user/dien-thoai');
        }

        // điện thoại theo tên
        if($phoneName['ram'] == ''){
            $SANPHAM = SANPHAM::where('tensp', $phoneName['tensp'])
                            ->where('dungluong', $phoneName['dungluong'])
                            ->inRandomOrder()->first();
        } else {
            $SANPHAM = SANPHAM::where('tensp', $phoneName['tensp'])
                            ->where('dungluong', $phoneName['dungluong'])
                            ->where('ram', $phoneName['ram'])
                            ->inRandomOrder()->first();
        }

        // mã sp
        $id = $SANPHAM->id;

        // mã mẫu sp
        $id_msp = $SANPHAM->id_msp;

        // dung lượng
        $capacity = $SANPHAM->dungluong;

        // mã khuyến mãi
        $id_km = $SANPHAM->id_km;

        // ram
        $ram = $SANPHAM->ram;

        // mẫu sp
        $model = MAUSP::where('id', $id_msp)->first();

        // các điện thoại cùng mẫu
        $phoneByModel = MAUSP::find($id_msp)->sanpham;

        // các điện thoại cùng mẫu theo dung lượng
        $phoneByCapacity = $this->getProductByCapacity($phoneByModel);

        /*==============================================================================================
                                                       Phone
        ================================================================================================*/

        // điện thoại theo id
        $phone = [
            'id' => $id,
            'tensp' => '',
            'hinhanh' => $SANPHAM->hinhanh,
            'mausac' => $SANPHAM->mausac,
            'ram' => $SANPHAM->ram,
            'dungluong' => $SANPHAM->dungluong,
            'gia' => $SANPHAM->gia,
            'giakhuyenmai' => '',
            'cauhinh' => $this->getSpecifications($id),
            'baohanh' => MAUSP::where('id', $id_msp)->first()->baohanh,
            'khuyenmai' => $this->getPromotionById($id_km),
            'id_youtube' => $model->id_youtube,
            'danhgia' => [],
            'trangthai' => $SANPHAM->trangthai,
        ];

        // lấy tên, đánh giá, khuyến mãi của mẫu sp
        foreach($phoneByCapacity as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                $phone['tensp'] = $key['tensp'];
                $phone['giakhuyenmai'] = $key['giakhuyenmai'];
                $phone['danhgia'] = $key['danhgia'];
            }
        }

        /*==============================================================================================
                                                  product variation
        ================================================================================================*/

        $lst_variation = [
            'capacity' => [],
            'color' => [],
            'image' => [],
        ];

        $i = 0;
        // lấy dung lượng biến thể
        foreach($phoneByCapacity as $key){
            $lst_variation['capacity'][$i] = [
                'tensp_url' => $key['tensp_url'],
                'ram' => $key['ram'],
                'dungluong' => $key['dungluong'],
                'giakhuyenmai' => $key['giakhuyenmai'],
            ];
             
            $i++;
        }

        $i = 0;
        // lấy màu sắc, hình ảnh biến thể
        foreach($phoneByModel as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                $lst_variation['color'][$i] = [
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'giakhuyenmai' => $phone['giakhuyenmai'],
                ];

                $lst_variation['image'][$i] = [
                    'hinhanh' => $key['hinhanh'],
                ];

                $i++;
            }
        }

        /*==============================================================================================
                                                 Detail Evaluate
        ================================================================================================*/

        $samePhones = [];
        $i = 0;

        // các điện thoại cùng dung lượng
        foreach($phoneByModel as $key){
            if($key['dungluong'] == $capacity){
                $samePhones[$i] = $key;
                $i++;
            }
        }

        $lst_evaluate = $this->getEvaluateByCapacity($samePhones);

        /*==============================================================================================
                                                    supplier
        ================================================================================================*/

        $supplier = $this->getSupplierByModelId($id_msp);

        /*==============================================================================================
                                                     branch
        ================================================================================================*/
        
        $lst_branch = $this->getBranchByProductId($id);

        $lst_area = TINHTHANH::all();

        /*==============================================================================================
                                                   slideshow model
        ==============================================================================================*/

        $slide_model = MAUSP::find($id_msp)->slideshow_ctmsp;

        /*==============================================================================================
                                                   same brand
        ==============================================================================================*/

        $lst_proSameBrand = $this->getRandomProductBySupplierId($model->id_ncc);

        /*==============================================================================================
                                                similar product
        ==============================================================================================*/
        
        $lst_similarPro = $this->getProductByPriceRange($id);

        /*==============================================================================================
                                                    data
        ================================================================================================*/

        $data = [
            'phone' => $phone,
            'lst_evaluate' => $lst_evaluate,
            'lst_variation' => $lst_variation,
            'supplier' => $supplier,
            'slide_model' => $slide_model,
            'lst_proSameBrand' => $lst_proSameBrand,
            'lst_similarPro' => $lst_similarPro,
            'lst_area' => $lst_area,
            'lst_branch' => $lst_branch,
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

    public function SoSanh($str){
        $current_url = explode('vs', $str)[0];
        $compare_url = explode('vs', $str)[1];

        $currentName = $this->getPhoneNameByString($current_url);
        $compareName = $this->getPhoneNameByString($compare_url);

        // điện thoại theo tên
        if($currentName['ram'] == ''){
            $currentId = SANPHAM::where('tensp', $currentName['tensp'])
                            ->where('dungluong', $currentName['dungluong'])
                            ->inRandomOrder()->first()->id;
        } else {
            $currentId = SANPHAM::where('tensp', $currentName['tensp'])
                            ->where('dungluong', $currentName['dungluong'])
                            ->where('ram', $currentName['ram'])
                            ->inRandomOrder()->first()->id;
        }

        if($compareName['ram'] == ''){
            $compareId = SANPHAM::where('tensp', $compareName['tensp'])
                            ->where('dungluong', $compareName['dungluong'])
                            ->inRandomOrder()->first()->id;
        } else {
            $compareId = SANPHAM::where('tensp', $compareName['tensp'])
                            ->where('dungluong', $compareName['dungluong'])
                            ->where('ram', $compareName['ram'])
                            ->inRandomOrder()->first()->id;
        }

        $currentProduct = $this->getProductInformation($currentId);
        $compareProduct = $this->getProductInformation($compareId);

        $data = [
            'currentProduct' => $currentProduct,
            'compareProduct' => $compareProduct,
        ];

        return view($this->user.'so-sanh')->with($data);
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

    public function test5()
    {
        for($i = 1; $i <= 9; $i++){
            // if($i == 2 || $i == 5 || $i == 10 || $i == 12){
            //     echo 'samsung_zfold2_5g_slide_' . $i . '.gif' . '<br>';
            //     continue;
            // }
            echo 'xiaomi_redmi_note_9_pro_slide_' . $i . '.jpg' . '<br>';
        }
        return false;
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
    public function getAllProductByCapacity()
    {
        $lst_model = MAUSP::all();
        $i = 0;

        foreach($lst_model as $model){
            // danh sách sản phẩm theo id_msp
            $SANPHAM = MAUSP::find($model->id)->sanpham;

            // lấy mẫu sp theo dung lượng
            $lst_temp = $this->getProductByCapacity($SANPHAM);

            foreach($lst_temp as $key){
                $lst_product[$i] = $key;
                $i++;
            }
        }

        return $lst_product;
    }

    // lấy mẫu sp theo dung lượng
    public function getProductByCapacity($lst)
    {
        // dung lượng: 64 GB / 128 GB
        $capacity = $lst[0]['dungluong'];
        $ram = $lst[0]['ram'];

        $promotin = 0;

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
                    'id_msp' => $key['id_msp'],
                    'tensp' => $key['tensp'],
                    'tensp_url' => str_replace(' ', '-', $key['tensp']),
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'giakhuyenmai' => $key['gia'] - ($key['gia'] * $promotion),
                    'danhgia' => $this->starRatingProduct($key['id']),
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
                    'id_msp' => $key['id_msp'],
                    'tensp' => $key['tensp'],
                    'tensp_url' => str_replace(' ', '-', $key['tensp']),
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'giakhuyenmai' => $key['gia'] - ($key['gia'] * $promotion),
                    'danhgia' => $this->starRatingProduct($key['id']),
                    'trangthai' => $key['trangthai'],
                ];

                $i++;
            }
        }

        $lst_product = [];

        // Kiểm tra cùng dung lượng nhưng khác ram
        if(count($lst_temp) > 1){
            $key_1 = explode('_', array_keys($lst_temp)[0])[0];
            $key_2 = explode('_', array_keys($lst_temp)[1])[0];

            // nếu có
            if(strcmp($key_1, $key_2) == 0){
                for($i = 0; $i < count($lst_temp); $i++){
                    $key = $lst_temp[array_keys($lst_temp)[$i]];
                    
                    $rand = mt_rand(0, count($key) - 1);
                    $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['ram'].' '.$key[$rand]['dungluong'];

                    $lst_product[$i] = [
                        'id' => $key[$rand]['id'],
                        'id_msp' => $key[$rand]['id_msp'],
                        'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['ram'].' '.$key[$rand]['dungluong'],
                        'tensp_url' => str_replace(' ', '-', $tensp_url),
                        'hinhanh' => $key[$rand]['hinhanh'],
                        'mausac' => $key[$rand]['mausac'],
                        'ram' => $key[$rand]['ram'],
                        'dungluong' => $key[$rand]['dungluong'],
                        'gia' => $key[$rand]['gia'],
                        'khuyenmai' => $key[$rand]['khuyenmai'],
                        'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                        'danhgia' => $this->starRatingByCapacity($key),
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            } else {
                for($i = 0; $i < count($lst_temp); $i++){
                    $key = $lst_temp[array_keys($lst_temp)[$i]];
    
                    $rand = mt_rand(0, count($key) - 1);
                    $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];
    
                    $lst_product[$i] = [
                        'id' => $key[$rand]['id'],
                        'id_msp' => $key[$rand]['id_msp'],
                        'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['dungluong'],
                        'tensp_url' => str_replace(' ', '-', $tensp_url),
                        'hinhanh' => $key[$rand]['hinhanh'],
                        'mausac' => $key[$rand]['mausac'],
                        'ram' => $key[$rand]['ram'],
                        'dungluong' => $key[$rand]['dungluong'],
                        'gia' => $key[$rand]['gia'],
                        'khuyenmai' => $key[$rand]['khuyenmai'],
                        'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                        'danhgia' => $this->starRatingByCapacity($key),
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            }
        } else {
            for($i = 0; $i < count($lst_temp); $i++){
                $key = $lst_temp[array_keys($lst_temp)[$i]];

                $rand = mt_rand(0, count($key) - 1);
                $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];

                $lst_product[$i] = [
                    'id' => $key[$rand]['id'],
                    'id_msp' => $key[$rand]['id_msp'],
                    'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['dungluong'],
                    'tensp_url' => str_replace(' ', '-', $tensp_url),
                    'hinhanh' => $key[$rand]['hinhanh'],
                    'mausac' => $key[$rand]['mausac'],
                    'ram' => $key[$rand]['ram'],
                    'dungluong' => $key[$rand]['dungluong'],
                    'gia' => $key[$rand]['gia'],
                    'khuyenmai' => $key[$rand]['khuyenmai'],
                    'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                    'danhgia' => $this->starRatingByCapacity($key),
                    'trangthai' => $key[$rand]['trangthai'],
                ];
            }
        }

        return $lst_product;
    }

    // lấy sp theo id_sp
    public function getProductById($id_sp)
    {
        return $temp = $this->getProductByCapacity(SANPHAM::where('id', $id_sp)->get());
    }

    // lấy thông tin sản phẩm
    public function getProductInformation($id_sp)
    {
        $product = [];

        $product['sanpham'] = $this->getProductById($id_sp);

        $phoneByModel = MAUSP::find(SANPHAM::find($id_sp)->mausp->id)->sanpham;
        $phoneByCapacity = $this->getProductByCapacity($phoneByModel);

        // lấy dung lượng biến thể
        $i = 0;
        foreach($phoneByCapacity as $key){
            $lst_variation['capacity'][$i] = [
                'id_sp' => $key['id'],
                'ram' => $key['ram'],
                'dungluong' => $key['dungluong'],
                'giakhuyenmai' => $key['giakhuyenmai'],
            ];
             
            $i++;
        }

        // lấy màu sắc, hình ảnh biến thể
        $i = 0;
        foreach($phoneByModel as $key){
            if($key['dungluong'] == $phoneByModel[0]['dungluong'] && $key['ram'] == $phoneByModel[0]['ram']){
                $lst_variation['color'][$i] = [
                    'id_sp' => $key['id'],
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'giakhuyenmai' => $product['sanpham'][0]['giakhuyenmai'],
                ];
                $i++;
            }
        }

        $product['variation'] = $lst_variation;
        $product['cauhinh'] = $this->getSpecifications($id_sp);

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
                                                        Phone                                                            
    ============================================================================================================*/

    // lấy các loại ram hiện có
    public function getRamAllProduct($allProduct)
    {
        $lst_ram = [];
        $i = 0;

        foreach($allProduct as $key){
            if(!in_array($key['ram'], $lst_ram)){
                $lst_ram[$i] = $key['ram'];
                $i++;
            }
        }

        return $lst_ram;
    }

    // lấy các loại dung lượng hiện có
    public function getCapacityAllProduct($allProduct)
    {
        $lst_capacity = [];
        $i = 0;

        foreach($allProduct as $key){
            if(!in_array($key['dungluong'], $lst_capacity)){
                $lst_capacity[$i] = $key['dungluong'];
                $i++;
            }
        }

        return $lst_capacity;
    }

    /*==========================================================================================================
                                                detail function                                                            
    ============================================================================================================*/
    // lấy tên điện thoại từ chuỗi
    public function getPhoneNameByString($str)
    {
        $lst = explode('-', $str);

        if(count($lst) == 1){
            return false;
        }

        $count = 0;
        foreach($lst as $key){
            if($key == 'GB'){
                $count++;
            }
        }

        $lst_name = [
            'tensp' => '',
            'ram' => '',
            'dungluong' => '',
        ];

        if($count == 1){
            $lst_name['dungluong'] = $lst[count($lst) - 2].' '.$lst[count($lst) - 1];
            for($i = 0; $i < 2; $i++){
                unset($lst[count($lst) - 1]);
            }
        } else {
            $lst_name['ram'] = $lst[count($lst) - 4].' '.$lst[count($lst) - 3];
            $lst_name['dungluong'] = $lst[count($lst) - 2].' '.$lst[count($lst) - 1];
            for($i = 0; $i < 4; $i++){
                unset($lst[count($lst) - 1]);
            }
        }

        $name = '';
        foreach($lst as $key){
            $name .= $key.' ';
        }

        $lst_name['tensp'] = $name;

        return $lst_name;
    }

    // lấy nhà cung cấp theo id_msp
    public function getSupplierByModelId($id_msp)
    {
        $temp = MAUSP::find($id_msp)->nhacungcap;

        $supplier = [];

        foreach($temp as $key){
            $supplier = [
                'id' => $temp['id'],
                'tenncc' => $temp['tenncc'],
                'brand' => explode(' ', $temp['tenncc'])[0],
                'anhdaidien' => $temp['anhdaidien'],
                'diachi' => $temp['diachi'],
                'sdt' => $temp['diachi'],
                'email' => $temp['email'],
                'trangthai' => $temp['trangthai'],
            ];
        }

        return $supplier;
    }

    // lấy thông tin khuyến mãi của sản phẩm
    public function getPromotionById($id)
    {
        $temp = KHUYENMAI::where('id', $id)->first();

        $promotion = [
            'id' => $temp->id,
            'tenkm' => $temp->tenkm,
            'noidung' => $temp->noidung,
            'chietkhau' => $temp->chietkhau,
            'ngaybatdau' => $temp->ngaybatdau,
            'ngayketthuc' => $temp->ngayketthuc,
            'trangthai' => $temp->trangthai,
        ];

        return $promotion;
    }

    // đọc file json thông số kỹ thuật
    public function getSpecifications($id_sp)
    {
        $fileName = SANPHAM::where('id', $id_sp)->first()->cauhinh;

        return json_decode(File::get(public_path('\json\\' . $fileName)), true);
    }

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

    // lấy chi nhánh có hàng theo id_sp
    public function getBranchByProductId($id_sp)
    {
        $temp = SANPHAM::find($id_sp)->kho;

        $lst_branch = [];
        $i = 0;

        foreach($temp as $key){
            if($key->pivot->slton != 0){
                $branch = CHINHANH::where('id', $key->pivot->id_cn)->first();
                $lst_branch[$i] = [
                    'id' => $branch->id,
                    'diachi' => $branch->diachi,
                    'id_tt' => $branch->id_tt,
                    'trangthai' => $branch->trangthai,
                ];
                $i++;
            }
        }

        return $lst_branch;
    }

    // lấy ngẫu nhiên điện thoại cùng nhà cung cấp
    public function getRandomProductBySupplierId($id_ncc, $qty = 5)
    {
        $models = NHACUNGCAP::find($id_ncc)->mausp;
        $lst_product = [];
        
        // random mẫu sản phẩm không trùng nhau
        $lst_model = $this->getUniqueRandomNumber($models[0]['id'], $models[count($models) - 1]['id'], $qty);

        // random sản phẩm theo id_msp
        for($i = 0; $i < count($lst_model); $i++){
            $phones = SANPHAM::where('id_msp', $lst_model[$i])->get();

            $phonesByCapacity = $this->getProductByCapacity($phones);
            $lst_product[$i] = $phonesByCapacity[mt_rand(0 , count($phonesByCapacity) - 1)];
        }

        return $lst_product;
    }

    // lấy tất cả điện thoại cùng nhà cung cấp
    public function getAllProductBySupplierId($id_ncc)
    {
        $lst_product = [];
        $i = 0;

        foreach(NHACUNGCAP::find($id_ncc)->mausp as $model){
            $lst_temp = $this->getProductByCapacity(MAUSP::find($model['id'])->sanpham);
            foreach($lst_temp as $key){
                $lst_product[$i] = $key;
                $i++;
            }
        }

        return $lst_product;
    }

    // lấy ngẫu nhiên điện thoại tương tự trong tầm giá
    public function getProductByPriceRange($id_sp, $qty = 5)
    {
        $id_msp = SANPHAM::find($id_sp)->mausp->id;

        $phone = SANPHAM::where('id', $id_sp)->first();

        // danh sách mẫu sp theo dung lượng không trùng với mẫu đang xem
        $lst_modelByCap = [];
        $i = 0;
        foreach(MAUSP::all() as $model){
            if($model->id == $id_msp){
                continue;
            }

            $phoneByModel = SANPHAM::where('id_msp', $model->id)->get();
            $phoneByCapacity = $this->getProductByCapacity($phoneByModel);

            foreach($phoneByCapacity as $key){
                $lst_modelByCap[$i] = $key;
                $i++;
            }
        }

        // danh sách sản phẩm trong tầm giá:  1tr < giá sp < 1tr
        $lst_product = [];
        $i = 0;
        $higher = $phone->gia + 1000000;
        $lower = $phone->gia - 1000000;
        
        foreach($lst_modelByCap as $phone){
            if($phone['gia'] >= $lower && $phone['gia'] <= $higher){
                $lst_product[$i] = $phone;
                $i++;
            }
        }

        return $lst_product;
    }

    // lấy mảng số ngẫu nhiên không trùng khớp
    public function getUniqueRandomNumber($min, $max, $qty)
    {
        $lst_rand = [];
        
        for($i = 0; $i < $qty; $i++){
            $rand = mt_rand($min, $max);

            if(count($lst_rand) != 0){
                while(1){
                    // nếu bị trùng thì random lại
                    if(in_array($rand, $lst_rand)){
                        $rand = mt_rand($min, $max);
                    } else {
                        $lst_rand[$i] = $rand;
                        break;
                    }
                }
            } else {
                $lst_rand[$i] = $rand;
            }
        }

        return $lst_rand;
    }

    // lấy đánh giá của mẫu sp cùng dung lượng
    public function getEvaluateByCapacity($samePhones)
    {
        $lst_evaluate = [];
        
        // lấy đánh giá của mẫu sp theo dung lượng
        $lst_evaluate = [
            'evaluate' => [],
            'image' => [],
            'total-rating' => '',
            'rating' => [
                '5' => '',
                '4' => '',
                '3' => '',
                '2' => '',
                '1' => '',
            ],
        ];
        $i = 0;

        $_1s = 0; $_2s = 0; $_3s = 0; $_4s = 0; $_5s = 0;
        
        foreach($samePhones as $key){
            $DANHGIASP = SANPHAM::find($key['id'])->danhgiasp;

            if(count($DANHGIASP) == 0){
                continue;
            }

            foreach($DANHGIASP as $evaluate){
                $imageEvaluate = $this->getEvaluateDetail($evaluate->pivot->id_dg);
                $product = $this->getProductById($evaluate->pivot->id_sp)[0];

                $lst_evaluate['evaluate'][$i] = [
                    'id' => $evaluate->pivot->id,
                    'taikhoan' => $this->getAccountById($evaluate->pivot->id_tk),
                    'sanpham' => [
                        'id' => $product['id'],
                        'tensp' => $product['tensp'],
                        'mausac' => $product['mausac'],
                        'dungluong' => $product['dungluong'],
                    ],
                    'noidung' => $evaluate->pivot->noidung,
                    'hinhanh' => $imageEvaluate,
                    'thoigian' => $evaluate->pivot->thoigian,
                    'soluotthich' => $evaluate->pivot->soluotthich,
                    'danhgia' => $evaluate->pivot->danhgia,
                    'trangthai' => $evaluate->pivot->trangthai,
                ];

                if($evaluate->pivot->danhgia == 1){
                    $_1s++;
                } elseif($evaluate->pivot->danhgia == 2){
                    $_2s++;
                } elseif($evaluate->pivot->danhgia == 3){
                    $_3s++;
                } elseif($evaluate->pivot->danhgia == 4){
                    $_4s++;
                } else {
                    $_5s++;
                }
                $i++;
            }
        }

        // chi tiết đánh giá
        $lst_evaluate['total-rating'] = $i;
        $lst_evaluate['rating']['1'] = $_1s;
        $lst_evaluate['rating']['2'] = $_2s;
        $lst_evaluate['rating']['3'] = $_3s;
        $lst_evaluate['rating']['4'] = $_4s;
        $lst_evaluate['rating']['5'] = $_5s;

        if(!isset($lst_evaluate)){
            $lst_evaluate = [];
        }

        return $lst_evaluate;
    }

    // lấy hình ảnh đánh giá theo id_dg
    public function getEvaluateDetail($id_dg)
    {
        $temp = CTDG::where('id_dg', $id_dg)->get();

        if(!isset($temp)){
            return [];
        }

        $lst_imageEvaluate = [];
        $i = 0;
        foreach($temp as $key){
            $lst_imageEvaluate[$i] = [
                'hinhanh' => $key['hinhanh'],
            ];

            $i++;
        }

        return $lst_imageEvaluate;
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
