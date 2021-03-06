<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use File;
use Session;
use Illuminate\Support\Facades\Cookie;

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
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function Index(){
        /*=================================
                    hotsale
        ===================================*/
        // sắp xếp khuyến mãi giảm dần
        $lst_product = $this->sortProductByPromotion($this->getAllProductByCapacity(), 'desc');

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

            if(count($SANPHAM) == 0){
                continue;
            }

            // lấy mẫu sp theo dung lượng
            $lst_temp = $this->getProductByCapacity($SANPHAM);

            foreach($lst_temp as $key){
                $lst_featured[$i] = $key;
                $i++;
            }
        }

        $data = [
            // slideshow
            'lst_slide' => SLIDESHOW::all(),
            'qty_slide' => count(SLIDESHOW::all()),

            // banner
            'lst_banner' => BANNER::all(),

            // list hotsale
            'lst_promotion' => $lst_promotion,

            // list featured
            'lst_featured' => $lst_featured,
        ];


        return view($this->user."index")->with($data);
    }

    public function DienThoai(){

        $lst_product = $this->getAllProductByCapacity();

        // các loại ram hiện có
        $lst_ram = $this->getRamAllProduct();

        // các loại dung lượng hiện có
        $lst_capacity = $this->getCapacityAllProduct();

        // số lượng sản phẩm
        $qty = count($lst_product);
        
        $data = [
            'lst_product' => $lst_product,
            'qty' => $qty,
            'fs_title' => $qty . ' điện thoại', // fs: filter - sort
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
            'tensp_url' => $name,
            'hinhanh' => $SANPHAM->hinhanh,
            'mausac' => $SANPHAM->mausac,
            'ram' => $SANPHAM->ram,
            'dungluong' => $SANPHAM->dungluong,
            'gia' => $SANPHAM->gia,
            'giakhuyenmai' => '',
            'cauhinh' => $this->getSpecifications($id),
            'baohanh' => MAUSP::where('id', $id_msp)->first()->baohanh,
            'khuyenmai' => $id_km != null ? $this->getPromotionById($id_km) : [],
            'id_youtube' => $model->id_youtube,
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
        if(session('user')){
            foreach($phoneByModel as $key){
                if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                    $lst_variation['color'][$i] = [
                        'id' => $key['id'],
                        'hinhanh' => $key['hinhanh'],
                        'mausac' => $key['mausac'],
                        'giakhuyenmai' => $phone['giakhuyenmai'],
                    ];
    
                    $lst_variation['image'][$i] = [
                        'hinhanh' => $key['hinhanh'],
                    ];

                    // đã thêm màu sắc vào danh sách yêu thích
                    if(SP_YEUTHICH::where('id_tk', session('user')->id)->where('id_sp', $key['id'])->first()){
                        $lst_variation['color'][$i]['yeuthich'] = 'true';
                    } else {
                        $lst_variation['color'][$i]['yeuthich'] = 'false';
                    }
    
                    $i++;
                }
            }
        } else {
            foreach($phoneByModel as $key){
                if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                    $lst_variation['color'][$i] = [
                        'id' => $key['id'],
                        'hinhanh' => $key['hinhanh'],
                        'mausac' => $key['mausac'],
                        'giakhuyenmai' => $phone['giakhuyenmai'],
                        'yeuthich' => 0,
                    ];
    
                    $lst_variation['image'][$i] = [
                        'hinhanh' => $key['hinhanh'],
                    ];
    
                    $i++;
                }
            }
        }

        /*==============================================================================================
                                                 Detail Evaluate
        ================================================================================================*/

        // danh sách id_sp cùng mẫu cùng dung lượng
        $lst_id_sp = [];

        // các điện thoại cùng dung lượng
        foreach($phoneByModel as $key){
            if($key['dungluong'] == $capacity){
                array_push($lst_id_sp, $key['id']);
            }
        }

        // đánh giá theo dung lượng
        session('user') ? $lst_evaluate = $this->getEvaluateByCapacity($lst_id_sp, session('user')->id) : $lst_evaluate = $this->getEvaluateByCapacity($lst_id_sp);
        //$this->print($lst_evaluate); return false;
        /*==============================================================================================
                                                    supplier
        ================================================================================================*/

        $supplier = $this->getSupplierByModelId($id_msp);

        /*==============================================================================================
                                                     branch
        ================================================================================================*/

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
                                                Have not Evaluated
        ==============================================================================================*/

        // sản phẩm chưa đánh giá
        $haveNotEvaluated = [];

        // đã có mua hàng hay chưa
        $bought = false;

        // đã đăng nhập
        if(session('user')){
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            // đơn hàng của người dùng
            foreach(DONHANG::where('id_tk', session('user')->id)->get() as $order){
                // chi tiết đơn hàng
                foreach(DONHANG::find($order['id'])->ctdh as $detail){
                    $product = SANPHAM::find($detail->pivot->id_sp);
                    // sản phẩm cùng id_msp và dung lượng
                    if($product->id_msp == $id_msp && $product->dungluong == $capacity){
                        //thời gian đơn hàng
                        $timeOrder = strtotime(str_replace('/', '-', $order['thoigian']));
                        // đánh giá
                        $evaluate = DANHGIASP::where('id_tk', session('user')->id)->where('id_sp', $product->id)->get();
                        // không có id_sp trong bảng đánh giá
                        if(count($evaluate) == 0){
                            array_push($haveNotEvaluated, $this->getProductById($product->id));
                        }
                        // đã có id_sp trong bảng đánh giá. kiểm tra ngày mua với ngày đánh giá
                        else {
                            // thời gian đánh giá của id_sp mới nhất
                            $timeEvaluate = strtotime(str_replace('/', '-', $evaluate[count($evaluate) - 1]['thoigian']));
                            // nếu thời gian mua mới > thời gian đánh giá => chưa đánh giá
                            if($timeOrder > $timeEvaluate){
                                array_push($haveNotEvaluated, $this->getProductById($product->id));
                            }
                        }
    
                        // đã mua hàng
                        $bought = true;
                    }
                }
            }   
        }

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
            'haveNotEvaluated' => $haveNotEvaluated,
            'bought' => $bought,
        ];

        return view($this->user."chi-tiet-dien-thoai")->with($data);
    }

    public function LienHe()
    {
        return view($this->user.'lien-he');
    }

    public function SoSanh($str){
        $lst_urlName = explode('vs', $str);

        $current_url = $lst_urlName[0];
        $compare_url = $lst_urlName[1];

        $third_url = '';
        $thirdProduct = [];

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

        // có sản phẩm thứ 3
        if(count($lst_urlName) == 3){
            $third_url = $lst_urlName[2];   
            $thirdName = $this->getPhoneNameByString($third_url);

            // điện thoại theo tên
            if($thirdName['ram'] == ''){
                $thirdId = SANPHAM::where('tensp', $thirdName['tensp'])
                                ->where('dungluong', $thirdName['dungluong'])
                                ->inRandomOrder()->first()->id;
            } else {
                $thirdId = SANPHAM::where('tensp', $thirdName['tensp'])
                                ->where('dungluong', $thirdName['dungluong'])
                                ->where('ram', $thirdName['ram'])
                                ->inRandomOrder()->first()->id;
            }

            $thirdProduct = $this->getProductInformation($thirdId);
        }

        $data = [
            'currentProduct' => $currentProduct,
            'compareProduct' => $compareProduct,
            'thirdProduct' => $thirdProduct,
        ];

        return view($this->user.'so-sanh')->with($data);
    }

    public function TraCuu()
    {
        return view($this->user.'tra-cuu');
    }

    public function AjaxChangeLocation(Request $request)
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

    /*==========================================================================================================
                                                    ajax                                                            
    ============================================================================================================*/

    public function AjaxForgetLoginStatusSession(Request $request)
    {
        if($request->ajax()){
            $request->session()->forget('login_status');
        }

        return back();
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
            $data = $request->dataFilterSort;

            $filter = null;
            
            if(array_key_exists('filter', $data)){
                $filter = $request->dataFilterSort['filter'];
            }
            
            $sort = $request->dataFilterSort['sort'];

            // nếu gỡ bỏ hết bộ lọc thì trả về tất cả sản phẩm
            if(!$filter){
                // sắp xếp
                if($sort == ''){
                    return $this->getAllProductByCapacity();
                } elseif($sort == 'high-to-low'){
                    return $this->sortPrice($this->getAllProductByCapacity(), 'desc');
                } elseif($sort == 'low-to-high'){
                    return $this->sortPrice($this->getAllProductByCapacity());
                } else {
                    return $this->sortProductByPromotion($this->getAllProductByCapacity(), 'desc');
                }
            }

            $time_filter = [];
            $idxTime = 0;

            $lst_product = [];
            $i = 0;

            // lọc theo tiêu chí đầu tiên
            // lấy mảng dữ liệu được lọc, sử dụng tiếp cho các lần lọc tiếp theo
            if(array_keys($filter)[0] == 'brand'){
                foreach($filter['brand'] as $supplier){
                    $lst_temp = $this->getAllProductBySupplierId(NHACUNGCAP::where('tenncc', 'like', $supplier.'%')->first()->id);
                    foreach($lst_temp as $key){
                        $lst_product[$i] = $key;
                        $i++;
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } elseif(array_keys($filter)[0] == 'price'){
                $lst_temp = $this->getAllProductByCapacity();

                foreach($filter['price'] as $price){
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
            } elseif(array_keys($filter)[0] == 'os'){
                foreach($filter['os'] as $os){
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
            } elseif(array_keys($filter)[0] == 'ram'){
                $lst_temp = $this->getAllProductByCapacity();
                foreach($filter['ram'] as $os){
                    foreach($lst_temp as $product){
                        if(strcmp(explode(' ', $product['ram'])[0].explode(' ', $product['ram'])[1], $os) == 0){
                            $lst_product[$i] = $product;
                            $i++;
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            } elseif(array_keys($filter)[0] == 'capacity') {
                $lst_temp = $this->getAllProductByCapacity();
                foreach($filter['capacity'] as $capacity){
                    foreach($lst_temp as $product){
                        if(strcmp(explode(' ', $product['dungluong'])[0].explode(' ', $product['dungluong'])[1], $capacity) == 0){
                            $lst_product[$i] = $product;
                            $i++;
                        }
                    }
                }
                $time_filter[$idxTime] = $lst_product;
                $idxTime++;
            }

            // nếu chỉ có 1 tiêu chí lọc thì trả về kết quả
            if(count($filter) == 1){
                // kiểm tra sắp xếp
                if($sort == ''){
                    return $time_filter[0];
                } else {
                    if($sort == 'high-to-low'){
                        return $this->sortPrice($time_filter[0], 'desc');
                    } elseif($sort == 'low-to-high'){
                         return $this->sortPrice($time_filter[0]);
                    } else {
                        return $this->sortProductByPromotion($time_filter[0], 'desc');
                    }
                }
            }

            unset($lst_product);

            // lọc tiếp tục các tiêu chí khác
            for($i = 1; $i < count($filter); $i++){
                $lst_product = [];
                $j = 0;

                if(array_keys($filter)[$i] == 'brand'){
                    foreach($filter[array_keys($filter)[$i]] as $supplier){
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
                } elseif(array_keys($filter)[$i] == 'price'){
                    foreach($filter[array_keys($filter)[$i]] as $price){
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
                } elseif(array_keys($filter)[$i] == 'os'){
                    foreach($filter[array_keys($filter)[$i]] as $os){
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
                } elseif(array_keys($filter)[$i] == 'ram'){
                    foreach($filter[array_keys($filter)[$i]] as $ram){
                        foreach($time_filter[$i - 1] as $product){
                            if(strcmp(explode(' ', $product['ram'])[0].explode(' ', $product['ram'])[1], $ram) == 0){
                                $lst_product[$j] = $product;
                                $j++;
                            }
                        }
                    }
                    $time_filter[$idxTime] = $lst_product;
                    $idxTime++;
                } elseif(array_keys($filter)[$i] == 'capacity'){
                    foreach($filter[array_keys($filter)[$i]] as $capacity){
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
            // kiểm tra sắp xếp
            if($sort == ''){
                return $time_filter[count($time_filter) - 1];
            } else {
                if($sort == 'high-to-low'){
                    return $this->sortPrice($time_filter[count($time_filter) - 1], 'desc');
                } elseif($sort == 'low-to-high'){
                     return $this->sortPrice($time_filter[count($time_filter) - 1]);
                } else {
                    return $this->sortProductByPromotion($time_filter[count($time_filter) - 1], 'desc');
                }
            }
        }

        return false;
    }

    // chọn màu sắc
    public function AjaxChooseColor(Request $request)
    {
        if($request->ajax()){
            // chưa đăng nhập
            if(!session('user')){
                return false;
            }
            $product = $this->getProductById($request->id_sp);

            $lst_color = [
                'mausac' => [],
                'tensp' => $product['tensp'],
                'giakhuyenmai' => $product['giakhuyenmai'],
                'gia' => $product['gia'],
                'url_phone' => 'images/phone/',
            ];

            $i = 0;

            foreach(MAUSP::find(SANPHAM::where('id', $request->id_sp)->first()->id_msp)->sanpham as $key){
                if($key['dungluong'] == $product['dungluong'] && $key['ram'] == $product['ram']){
                    $lst_color['mausac'][$i] = [
                        'id' => $key['id'],
                        'mausac' => $key['mausac'],
                        'hinhanh' => $key['hinhanh'],
                    ];

                    $i++;
                }
            }

            return $lst_color;
        }

        return false;
    }

    public function AjaxCheckQtyInStock(Request $request)
    {
        if($request->ajax()){
            if(!$this->checkStatus($request->id_sp)){
                return 'false';
            }
            $warehouse = [];
            $i = 0;

            foreach(SANPHAM::find($request->id_sp)->kho as $key){
                if($key->pivot->slton != 0){
                    $warehouse[$i] = $key;
                    $i++;
                }
            }

            return $warehouse;
        }
    }

    public function AjaxGetQtyInStock(Request $request)
    {
        if($request->ajax()){
            // ngừng kinh doanh
            if(SANPHAM::find($request->id_sp)->trangthai == 0){
                return 'false';
            }

            $qtyInStock = 0;
            foreach(SANPHAM::find($request->id_sp)->kho as $key){
                $qtyInStock += $key->pivot->slton;
            }

            return $qtyInStock;
        }
    }

    public function AjaxCheckQtyInStockBranch(Request $request)
    {
        if($request->ajax()){
            // giỏ hàng
            $cart = $this->getCart(session('user')->id);

            $lst_product = [];
            $i = 0;

            // kiểm tra còn hàng hay không
            foreach($cart['cart'] as $key){
                $qtyInStock = KHO::where('id_cn', $request->id)->where('id_sp', $key['sanpham']['id'])->first()->slton;
                // hết hàng
                if($qtyInStock == 0) {
                    $lst_product[$i] = [
                        'id' => $key['id'],
                        'tensp' => $key['sanpham']['tensp'],
                        'mausac' => $key['sanpham']['mausac'],
                        'ram' => $key['sanpham']['ram'],
                        'hinhanh' => $key['sanpham']['hinhanh'],
                        'trangthai' => 'Tạm hết hàng',
                    ];

                    $i++;
                }
                // còn hàng
                elseif($qtyInStock >= $key['sl']){
                    $lst_product[$i] = [
                        'id' => $key['id'],
                        'tensp' => $key['sanpham']['tensp'],
                        'mausac' => $key['sanpham']['mausac'],
                        'ram' => $key['sanpham']['ram'],
                        'hinhanh' => $key['sanpham']['hinhanh'],
                        'trangthai' => 'Còn hàng',
                    ];

                    $i++;
                }
                // quá số lượng
                else{
                    $lst_product[$i] = [
                        'id' => $key['id'],
                        'tensp' => $key['sanpham']['tensp'],
                        'mausac' => $key['sanpham']['mausac'],
                        'ram' => $key['sanpham']['ram'],
                        'hinhanh' => $key['sanpham']['hinhanh'],
                        'slton' => $qtyInStock,
                        'trangthai' => 'Không đủ',
                    ];

                    $i++;
                }
            }

            return $lst_product;
        }
    }

    public function AjaxCheckImei(Request $request)
    {
        if($request->ajax()){
            $warranty = BAOHANH::where('imei', $request->imei)->first();

            // imei không hợp lệ hoặc chưa kích hoạt
            if(!$warranty){
                return ['status' => 'invalid imei'];
            }
            // imei hợp lệ
            else {
                // sản phẩm
                $product = $this->getProductById(IMEI::find($warranty->id_imei)->id_sp);

                // bảo hành
                $product['baohanh'] = MAUSP::find($product['id_msp'])->baohanh;

                $product['ngaymua'] = $warranty->ngaymua;

                $product['ngayketthuc'] = $warranty->ngayketthuc;

                // trạng thái bảo hành
                $product['trangthaibaohanh'] = strtotime(str_replace('/', '-', $product['ngayketthuc'])) >= time() ? 'true' : 'false';

                return [
                    'status' => 'success',
                    'product' => $product,
                ];
            }
        }
    }

    /*==========================================================================================================
                                                function                                                            
    ============================================================================================================*/

    public function print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function checkStatus($id_sp)
    {
        return SANPHAM::find($id_sp)->trangthai == 0 ? false : true;
    }

    // lấy sao đánh giá và số lượt đánh giá theo mẫu và dung lượng
    public function getStarRatingByCapacity($lst_id)
    {
        // lấy đánh giá của mẫu sp theo dung lượng
        $starRating = [
            'total-rating' => 0,
            'rating' => [
                '5' => 0,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0,
            ],
        ];

        $lst_tempEvaluate = [];

        // mảng đánh giá trong khoảng của id_sp theo thứ tự trong db
        $lst_temp = DANHGIASP::where('id_sp', '>=', $lst_id[0])->where('id_sp', '<=', $lst_id[count($lst_id) - 1])->get();
        $i = 0;

        foreach($lst_temp as $key){
            $imageEvaluate = $this->getEvaluateDetail($key['id']);

            $lst_tempEvaluate[$i] = [
                'id' => $key['id'],
                'taikhoan' => $this->getAccountById($key['id_tk']),
                'noidung' => $key['noidung'],
                'thoigian' => $key['thoigian'],
                'danhgia' => $key['danhgia'],
            ];
            $i++;
        }

        $idx = 0;

        // gộp và lấy đánh giá
        /*mô tả: vòng for chạy từ dòng đầu tiên, nếu các dòng tiếp theo thuộc về 1 đánh giá
                thì gôm chúng lại, lấy màu sắc của chúng cộng vào dòng đầu tiên. ta được 1 đánh giá hoàn chỉnh*/
        for($i = 0; $i < count($lst_tempEvaluate); $i++){
            // dòng đầu tiên
            $first = $lst_tempEvaluate[$i];
            // mảng các dòng giống nhau
            $temp = [];
            // số dòng giống nhau
            $step = 0;
            // for so sánh dòng đầu tiên với các dòng còn lại
            for($j = $i + 1; $j < count($lst_tempEvaluate); $j++){
                // giống nhau
                if($first['taikhoan']['id'] == $lst_tempEvaluate[$j]['taikhoan']['id'] && $first['noidung'] == $lst_tempEvaluate[$j]['noidung'] && $first['thoigian'] == $lst_tempEvaluate[$j]['thoigian']){
                    // thêm vào mảng các dòng giống nhau                   
                    array_push($temp, $lst_tempEvaluate[$j]);
                    // tăng số dòng giống nhau
                    $step++;
                }
                // nếu khác nhau thì thoát vòng lặp
                else {
                    break;
                }
            }

            if(!empty($temp)){
                // đi đến vị trí của dòng mới
                $i += $step;
            }

            // sao đánh giá
            if($lst_tempEvaluate[$i]['danhgia'] == 1){
                $starRating['rating']['1']++;
            } elseif($lst_tempEvaluate[$i]['danhgia'] == 2){
                $starRating['rating']['2']++;
            } elseif($lst_tempEvaluate[$i]['danhgia'] == 3){
                $starRating['rating']['3']++;
            } elseif($lst_tempEvaluate[$i]['danhgia'] == 4){
                $starRating['rating']['4']++;
            } else {
                $starRating['rating']['5']++;
            }

            $idx++;

            $starRating['total-rating']++;
        }

        if($starRating['total-rating'] == 0){
            $starRating['total-star'] = 0;    
        } else {
            $_5s = 5 * $starRating['rating']['5'];
            $_4s = 4 * $starRating['rating']['4'];
            $_3s = 3 * $starRating['rating']['3'];
            $_2s = 2 * $starRating['rating']['2'];
            $_1s = 1 * $starRating['rating']['1'];
    
            $starRating['total-star'] = ($_5s + $_4s + $_3s + $_2s + $_1s) / $starRating['total-rating'];
        }
        
        return $starRating;
    }

    // lấy danh sách mẫu sp theo dung lượng
    public function getAllProductByCapacity()
    {
        $lst_model = MAUSP::all();
        $i = 0;

        foreach($lst_model as $model){
            // danh sách sản phẩm theo id_msp
            $SANPHAM = MAUSP::find($model->id)->sanpham;

            if(count($SANPHAM) == 0){
                continue;
            }

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

        $promotion = 0;

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
                    'cauhinh' => $key['cauhinh'],
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

                    $starRating = $this->getStarRatingByCapacity($this->getListIdByCapacity($key[$rand]['id']));

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
                        'danhgia' => [
                            'qty' => $starRating['total-rating'],
                            'star' => $starRating['total-star'],
                        ],
                        'cauhinh' => $key[$rand]['cauhinh'],
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            } else {
                for($i = 0; $i < count($lst_temp); $i++){
                    $key = $lst_temp[array_keys($lst_temp)[$i]];
    
                    $rand = mt_rand(0, count($key) -1);
                    $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];

                    $starRating = $this->getStarRatingByCapacity($this->getListIdByCapacity($key[$rand]['id']));
    
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
                        'danhgia' => [
                            'qty' => $starRating['total-rating'],
                            'star' => $starRating['total-star'],
                        ],
                        'cauhinh' => $key[$rand]['cauhinh'],
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            }
        } else {
            for($i = 0; $i < count($lst_temp); $i++){
                $key = $lst_temp[array_keys($lst_temp)[$i]];

                $rand = mt_rand(0, count($key) - 1);
                $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];

                $starRating = $this->getStarRatingByCapacity($this->getListIdByCapacity($key[$rand]['id']));

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
                    'danhgia' => [
                        'qty' => $starRating['total-rating'],
                        'star' => $starRating['total-star'],
                    ],
                    'cauhinh' => $key[$rand]['cauhinh'],
                    'trangthai' => $key[$rand]['trangthai'],
                ];
            }
        }

        return $lst_product;
    }

    // lấy danh sách id_sp cùng mẫu cùng dung lượng
    public function getListIdByCapacity($id_sp)
    {
        $product = SANPHAM::find($id_sp);
        $capacity = $product->dungluong;
        $ram = $product->ram;
        $id_msp = $product->id_msp;

        $lst_id = [];

        foreach(SANPHAM::where('id_msp', $id_msp)->where('dungluong', $capacity)->where('ram', $ram)->get() as $key){
            array_push($lst_id, $key['id']);
        }

        return $lst_id;
    }

    // lấy danh sách id_sp cùng mẫu
    public function getListIdByModel($id_msp)
    {
        $lst_result = [];
        $temp = SANPHAM::select('id')->distinct()->where('id_msp', $id_msp)->get();
        foreach($temp as $key){
            array_push($lst_result, $key->id);
        }

        return $lst_result;
    }

    // lấy danh sách id_sp cùng ncc
    public function getListIdBySupplier($id_ncc)
    {
        $lst_result = [];
        foreach(MAUSP::where('id_ncc', $id_ncc)->get() as $model){
            foreach(SANPHAM::select('id')->distinct()->where('id_msp', $model->id)->get() as $product){
                array_push($lst_result, $product->id);
            }
        }

        return $lst_result;
    }

    // lấy sp theo id_sp
    public function getProductById($id_sp)
    {
        return $this->getProductByCapacity(SANPHAM::where('id', $id_sp)->get())[0];
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
                    'giakhuyenmai' => $product['sanpham']['giakhuyenmai'],
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
        // không có khuyến mãi
        if(!SANPHAM::find($id_sp)->khuyenmai){
            return false;
        }

        $warranty = strtotime(str_replace('/', '-', SANPHAM::find($id_sp)->khuyenmai->ngayketthuc));
        $today = time();

        return $warranty >= $today ? true : false;
    }

    // sắp xếp danh sách sp theo khuyến mãi
    public function sortProductByPromotion($lst, $type = 'asc')
    {
        if($type == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i+1; $j < count($lst); $j++){
                    if($lst[$i]['khuyenmai'] > $lst[$j]['khuyenmai']){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i+1; $j < count($lst); $j++){
                    if($lst[$i]['khuyenmai'] < $lst[$j]['khuyenmai']){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        }
        

        return $lst;
    }

    // sắp xếp theo giá giảm dần
    public function sortPrice($lst_product, $type = 'asc')
    {
        if($type == 'asc'){
            for($i = 0; $i < count($lst_product) - 1; $i++){
                for($j = $i+1; $j < count($lst_product); $j++){
                    if($lst_product[$i]['gia'] > $lst_product[$j]['gia']){
                        $temp = $lst_product[$i];
                        $lst_product[$i] = $lst_product[$j];
                        $lst_product[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst_product) - 1; $i++){
                for($j = $i+1; $j < count($lst_product); $j++){
                    if($lst_product[$i]['gia'] < $lst_product[$j]['gia']){
                        $temp = $lst_product[$i];
                        $lst_product[$i] = $lst_product[$j];
                        $lst_product[$j] = $temp;
                    }
                }
            }
        }
        

        return $lst_product;
    }

    // lấy các loại ram hiện có
    public function getRamAllProduct()
    {
        $lst_ram = [];
        foreach(SANPHAM::select('ram')->distinct()->get() as $ram){
            array_push($lst_ram, $ram->ram);
        }

        return $lst_ram;
    }

    // lấy các loại dung lượng hiện có
    public function getCapacityAllProduct()
    {
        $lst_capacity = [];

        foreach(SANPHAM::select('dungluong')->distinct()->get() as $dungluong){
            array_push($lst_capacity, $dungluong->dungluong);
        }

        return $lst_capacity;
    }

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
            'trangthaikhuyenmai' => true,
            'trangthai' => $temp->trangthai,
        ];

        // hết hạn
        if(strtotime(str_replace('/', '-', $temp->ngayketthuc)) < time()){
            $promotion['trangthaikhuyenmai'] = false;
        }

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
            'anhdaidien' => $temp->htdn == 'nomal' ? 'images/user/'.$temp->anhdaidien : $temp->anhdaidien,
            //'loaitk' => $temp->loaitk,
            //'htdn' => $temp->htdn,
            //'trangthai' => $temp->trangthai,
        ];

        return $account;
    }

    // lấy chi nhánh có hàng theo id_sp
    public function getBranchByProductId($id_sp)
    {
        $lst_branch = [];
        $i = 0;

        foreach(SANPHAM::find($id_sp)->kho as $key){
            if($key->pivot->slton != 0){
                $lst_branch[$i] = CHINHANH::where('id', $key->pivot->id_cn)->first();
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

        $lst_id_msp = [];
        foreach($models as $model){
            array_push($lst_id_msp, $model['id']);
        }
        
        // random mẫu sản phẩm không trùng nhau
        // $lst_model = $this->getUniqueRandomNumber($models[0]['id'], $models[count($models) - 1]['id'], $qty);
        $lst_model = [];

        for($i = 0; $i < $qty; $i++){
            array_push($lst_model, $lst_id_msp[array_rand($lst_id_msp)]);
        }

        // random sản phẩm theo id_msp
        for($i = 0; $i < count($lst_model); $i++){
            $phones = SANPHAM::where('id_msp', $lst_model[$i])->get();

            if(count($phones) == 0){
                continue;
            }

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
            if(count($phoneByModel) == 0){
                continue;
            }
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
    public function getEvaluateByCapacity($lst_id_sp, $id_tk = null)
    {
        // lấy đánh giá của mẫu sp theo dung lượng
        $lst_evaluate = [
            'evaluate' => [],
            'total-rating' => 0,
            'rating' => [
                '5' => 0,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0,
            ],
            'total-star' => 0,
        ];

        $lst_tempEvaluate = [];

        // mảng đánh giá trong khoảng của id_sp theo thứ tự trong db
        $lst_temp = DANHGIASP::where('id_sp', '>=', $lst_id_sp[0])->where('id_sp', '<=', $lst_id_sp[count($lst_id_sp) - 1])->get();
        $i = 0;

        foreach($lst_temp as $key){
            $imageEvaluate = $this->getEvaluateDetail($key['id']);

            $lst_tempEvaluate[$i] = [
                'id' => $key['id'],
                'taikhoan' => $this->getAccountById($key['id_tk']),
                'sanpham' => [
                    'mausac' => $this->getProductById($key['id_sp'])['mausac'],
                ],
                'noidung' => $key['noidung'],
                'hinhanh' => $imageEvaluate,
                'thoigian' => $key['thoigian'],
                'soluotthich' => $key['soluotthich'],
                'danhgia' => $key['danhgia'],
                'trangthai' => $key['trangthai'],
            ];
            $i++;
        }

        $idx = 0;

        // gộp và lấy đánh giá
        /*mô tả: vòng for chạy từ dòng đầu tiên, nếu các dòng tiếp theo thuộc về 1 đánh giá
                thì gôm chúng lại, lấy màu sắc của chúng cộng vào dòng đầu tiên. ta được 1 đánh giá hoàn chỉnh*/
        if($id_tk == null){
            for($i = 0; $i < count($lst_tempEvaluate); $i++){
                // dòng đầu tiên
                $first = $lst_tempEvaluate[$i];
                // mảng các dòng giống nhau
                $temp = [];
                // số dòng giống nhau
                $step = 0;
                // for so sánh dòng đầu tiên với các dòng còn lại
                for($j = $i + 1; $j < count($lst_tempEvaluate); $j++){
                    // giống nhau
                    if($first['taikhoan']['id'] == $lst_tempEvaluate[$j]['taikhoan']['id'] && $first['noidung'] == $lst_tempEvaluate[$j]['noidung'] && $first['thoigian'] == $lst_tempEvaluate[$j]['thoigian']){
                        // thêm vào mảng các dòng giống nhau                   
                        array_push($temp, $lst_tempEvaluate[$j]);
                        // tăng số dòng giống nhau
                        $step++;
                    }
                    // nếu khác nhau thì thoát vòng lặp
                    else {
                        break;
                    }
                }
    
                // nếu có dòng giống nhau thì cộng màu vào dòng đầu tiên
                if(!empty($temp)){
                    // đẩy dòng đầu tiên vào mảng chính
                    $lst_evaluate['evaluate'][$idx] = $first;
                    // cộng màu
                    foreach($temp as $key){
                        $lst_evaluate['evaluate'][$idx]['sanpham']['mausac'] .= ', '. $key['sanpham']['mausac'];
                    }

                    // đi đến vị trí của dòng mới
                    $i += $step;
                }
                // không có dòng nào giống nhau: đánh giá khác
                else {
                    $lst_evaluate['evaluate'][$idx] = $first;
                }

                $lst_evaluate['evaluate'][$idx]['liked'] = false;

                // sao đánh giá
                if($lst_tempEvaluate[$i]['danhgia'] == 1){
                    $lst_evaluate['rating']['1']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 2){
                    $lst_evaluate['rating']['2']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 3){
                    $lst_evaluate['rating']['3']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 4){
                    $lst_evaluate['rating']['4']++;
                } else {
                    $lst_evaluate['rating']['5']++;
                }

                // danh sách phản hồi
                $lst_evaluate['evaluate'][$idx]['phanhoi'] = $this->getReply($lst_evaluate['evaluate'][$idx]['id']);

                $idx++;
    
                $lst_evaluate['total-rating']++;
            }
        } else {
            for($i = 0; $i < count($lst_tempEvaluate); $i++){
                // dòng đầu tiên
                $first = $lst_tempEvaluate[$i];
                // mảng các dòng giống nhau
                $temp = [];
                // số dòng giống nhau
                $step = 0;
                // for so sánh dòng đầu tiên với các dòng còn lại
                for($j = $i + 1; $j < count($lst_tempEvaluate); $j++){
                    // giống nhau
                    if($first['taikhoan']['id'] == $lst_tempEvaluate[$j]['taikhoan']['id'] && $first['noidung'] == $lst_tempEvaluate[$j]['noidung'] && $first['thoigian'] == $lst_tempEvaluate[$j]['thoigian']){
                        // thêm vào mảng các dòng giống nhau                   
                        array_push($temp, $lst_tempEvaluate[$j]);
                        // tăng số dòng giống nhau
                        $step++;
                    }
                    // nếu khác nhau thì thoát vòng lặp
                    else {
                        break;
                    }
                }
    
                // nếu có dòng giống nhau thì cộng màu vào dòng đầu tiên
                if(!empty($temp)){
                    // đẩy dòng đầu tiên vào mảng chính
                    $lst_evaluate['evaluate'][$idx] = $first;
                    // cộng màu
                    foreach($temp as $key){
                        $lst_evaluate['evaluate'][$idx]['sanpham']['mausac'] .= ', '. $key['sanpham']['mausac'];
                    }
                    // đi đến vị trí của dòng mới
                    $i += $step;
                }
                // không có dòng nào giống nhau: đánh giá khác
                else {
                    $lst_evaluate['evaluate'][$idx] = $first;
                }

                //kiểm tra tài khoản đang đăng nhập có thích bình luận hay không
                LUOTTHICH::where('id_tk', $id_tk)->where('id_dg', $lst_evaluate['evaluate'][$idx]['id'])->first() ? 
                $lst_evaluate['evaluate'][$idx]['liked'] = true : $lst_evaluate['evaluate'][$idx]['liked'] = false;

                // sao đánh giá
                if($lst_tempEvaluate[$i]['danhgia'] == 1){
                    $lst_evaluate['rating']['1']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 2){
                    $lst_evaluate['rating']['2']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 3){
                    $lst_evaluate['rating']['3']++;
                } elseif($lst_tempEvaluate[$i]['danhgia'] == 4){
                    $lst_evaluate['rating']['4']++;
                } else {
                    $lst_evaluate['rating']['5']++;
                }

                // danh sách phản hồi
                $lst_evaluate['evaluate'][$idx]['phanhoi'] = $this->getReply($lst_evaluate['evaluate'][$idx]['id']);

                $idx++;
    
                $lst_evaluate['total-rating']++;
            }
        }

        if($lst_evaluate['total-rating'] == 0){
            $lst_evaluate['total-star'] = 0;    
        } else {
            $_5s = 5 * $lst_evaluate['rating']['5'];
            $_4s = 4 * $lst_evaluate['rating']['4'];
            $_3s = 3 * $lst_evaluate['rating']['3'];
            $_2s = 2 * $lst_evaluate['rating']['2'];
            $_1s = 1 * $lst_evaluate['rating']['1'];
    
            $lst_evaluate['total-star'] = ($_5s + $_4s + $_3s + $_2s + $_1s) / $lst_evaluate['total-rating'];
        }

        // sắp xếp đánh giá mới nhất ở trên đầu
        $lst_evaluate['evaluate'] = array_reverse($lst_evaluate['evaluate'], false);
        
        return $lst_evaluate;
    }

    // lấy hình ảnh đánh giá theo id_dg
    public function getEvaluateDetail($id_dg)
    {
        $lst_imageEvaluate = [];

        $evaluateDetail = DANHGIASP::find($id_dg)->ctdg;

        foreach(DANHGIASP::find($id_dg)->ctdg as $key){
            $data = [
                'id' => $key['id'],
                'id_dg' => $key['id_dg'],
                'hinhanh' => $key['hinhanh'],
            ];

            array_push($lst_imageEvaluate, $data);
        }

        return $lst_imageEvaluate;
    }

    // lấy các đánh giá đã thích theo id_tk
    public function getEvaluateLiked($id_tk)
    {
        return TAIKHOAN::find($id_tk)->luotthich;
    }

    // lấy phản hồi đánh giá theo id_dg
    public function getReply($id_dg)
    {
        $lst_reply = [];
        foreach(DANHGIASP::find($id_dg)->phanhoi as $key){
            $data = [
                'id' => $key['id'],
                'taikhoan' => $this->getAccountById($key['id_tk']),
                'id_dg' => $id_dg,
                'noidung' => $key['noidung'],
                'thoigian' => $key['thoigian'],
                'trangthai' => $key['trangthai'],
            ];

            array_push($lst_reply, $data);
        }

        return $lst_reply;
    }

    // lấy địa chỉ mặc định
    public function getAddressDefault($id_tk)
    {
        return TAIKHOAN_DIACHI::where('id_tk', $id_tk)->where('macdinh', 1)->first() == null ? null : TAIKHOAN_DIACHI::where('id_tk', $id_tk)->where('macdinh', 1)->first();
    }

    // hàm loại bỏ ký tự có dấu
    public function unaccent($str) {

        $array = [
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
            'A' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
            'Â' => 'A', 'Ấ' => 'A', 'Ầ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
            'Ă' => 'A', 'Ắ' => 'A', 'Ằ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
            'Đ' => 'D',
            'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
            'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
            'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
            'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
            'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y'
        ];

        $str = str_replace(array_keys($array), array_values($array), $str);
        return $str;
    }

    // cập nhật session user
    public function userSessionUpdate()
    {
        $user = TAIKHOAN::where('id', session('user')->id)->first();
        Session::forget('user');
        session(['user' => $user]);
    }

    // lấy giỏ hàng của tài khoản
    public function getCart($id_tk)
    {
        $cart = [
            'cart' => [],
            'qty' => 0,
            'total' => 0,
        ];

        $i = 0;

        if(count(TAIKHOAN::find($id_tk)->giohang) == 0){
            return $cart;
        }

        foreach(TAIKHOAN::find($id_tk)->giohang as $key){
            $cart['cart'][$i] = [
                'id' => $key->pivot->id,
                'sanpham' => $this->getProductById($key->pivot->id_sp),
                'sl' => $key->pivot->sl,
                'thanhtien' => intval($this->getProductById($key->pivot->id_sp)['giakhuyenmai'] * $key->pivot->sl),
            ];

            // số lượng tồn ở mỗi chi nhánh
            $cart['cart'][$i]['slton'] = SANPHAM::find($key->pivot->id_sp)->kho;

            $cart['qty']++;
            $cart['total'] += $cart['cart'][$i]['thanhtien'];

            $i++;
        }

        return $cart;
    }

    // lấy đơn hàng theo id
    public function getOrderById($id_dh)
    {
        $data = [
            'order' => [],
            'detail' => [],
        ];

        // đơn hàng
        $data['order'] = DONHANG::find($id_dh);

        // địa chỉ giao hàng
        $data['order']->hinhthuc == 'Giao hàng tận nơi' ? $data['order']->diachigiaohang = TAIKHOAN_DIACHI::find($data['order']->id_tk_dc) : null;

        // chi nhánh
        $data['order']->hinhthuc == 'Nhận tại cửa hàng' ? $data['order']->chinhanh = CHINHANH::find($data['order']->id_cn) : null;

        // voucher
        $data['order']->voucher ? VOUCHER::find($data['order']->voucher) : null;
        
        // chi tiết đơn hàng
        $data['detail'] = $this->getOrderDetail($id_dh);

        return $data;
    }

    // lấy chi tiết đơn hàng theo id đơn hàng
    public function getOrderDetail($id_dh)
    {
        $lst_detail = [];
        $i = 0;
        foreach(DONHANG::find($id_dh)->ctdh as $key){
            $lst_detail[$i] = [
                'id_dh' => $key->pivot->id_dh,
                'sanpham' => $this->getProductById($key->pivot->id_sp),
                'gia' => $key->pivot->gia,
                'sl' => $key->pivot->sl,
                'giamgia' => $key->pivot->giamgia,
                'thanhtien' => $key->pivot->thanhtien,
            ];

            $i++;
        }

        return $lst_detail;
    }

}
