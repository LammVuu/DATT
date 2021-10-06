<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\KHO;
use App\Models\CHINHANH;
use App\Models\SANPHAM;
use App\Models\MAUSP;
use App\Models\IMEI;

class KhoController extends Controller
{
    private const LDMobile = 7668;
    private const APPLE = 8609;
    private const SAMSUNG = 5286;
    private const XIAOMI = 6094;
    private const OPPO = 8112;
    private const VIVO = 2218;

    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        // danh sách kho theo chi nhánh
        $lst_warehouse = [];
        foreach(KHO::limit(10)->get() as $key){
            $temp = [
                'id' => $key['id'],
                'id_cn' => $key['id_cn'],
                'chinhanh' => CHINHANH::find($key['id_cn'])->diachi,
                'id_sp' => $key['id_sp'],
                'sanpham' => SANPHAM::find($key['id_sp']),
                'slton' => $key['slton'],
                'trangthai' => $key['trangthai'],
            ];

            array_push($lst_warehouse, $temp);
        }

        $data = [
            'lst_warehouse' => $lst_warehouse,
            'lst_branch' => CHINHANH::all(),
            'lst_product' => SANPHAM::all(),
        ];

        return view($this->admin.'kho')->with($data);
    }

    public function createIMEI($id_ncc)
    {
        $rand = strval(mt_rand(1, 9999999));
        $lenght = strlen($rand);
        if($lenght != 7){
            $missing = 7 - $lenght;
            for($i = 0; $i < $missing; $i++){
                $rand = '0'.$rand;
            }
        }

        if($id_ncc == 1){
            return self::LDMobile.self::APPLE.$rand;
        } elseif($id_ncc == 2){
            return self::LDMobile.self::OPPO.$rand;
        } elseif($id_ncc == 3){
            return self::LDMobile.self::SAMSUNG.$rand;
        } elseif($id_ncc == 4){
            return self::LDMobile.self::VIVO.$rand;
        } else {
            return self::LDMobile.self::XIAOMI.$rand;
        }
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'id_cn' => $request->id_cn,
                'id_sp' => $request->id_sp,
                'slton' => $request->slton,
            ];

            // thêm vào kho
            $create = KHO::create($data);

            // tạo imei
            $id_ncc = MAUSP::find(SANPHAM::find($data['id_sp'])->id_msp)->id_ncc;
            for($i = 0; $i < $data['slton']; $i++){
                $imei = $this->createIMEI($id_ncc);
                while(1){
                    // trùng imei thì render lại
                    if(IMEI::where('imei', $imei)->first()){
                        $imei = $this->createIMEI($id_ncc);
                    } else {
                        break;
                    }
                }

                IMEI::create([
                    'id_sp' => $data['id_sp'],
                    'imei' => $imei,
                    'trangthai' => 0,
                ]);
            }

            $product = SANPHAM::find($data['id_sp']);
            $branchAddress = CHINHANH::find($data['id_cn'])->diachi;

            $create->product = $product;
            $create->branchAddress = $branchAddress;

            return [
                'id' => $create->id,
                'data' => [$create]
            ];
        }
    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $data = [
                'id_cn' => $request->id_cn,
                'id_sp' => $request->id_sp,
                'slton' => $request->slton,
            ];

            $oldData = KHO::find($id);

            // số lượng tồn cũ
            $qtyInStock = $oldData->slton;
            // nếu số lượng tồn mới > số lượng tồn cũ => thêm mới imei
            if($data['slton'] > $qtyInStock){
                // số lượng imei cần thêm
                $ImeiQty = $data['slton'] - $qtyInStock;
                $id_ncc = MAUSP::find(SANPHAM::find($data['id_sp'])->id_msp)->id_ncc;
                for($i = 0; $i < $ImeiQty; $i++){
                    $imei = $this->createIMEI($id_ncc);
                    while(1){
                        // trùng imei thì render lại
                        if(IMEI::where('imei', $imei)->first()){
                            $imei = $this->createIMEI($id_ncc);
                        } else {
                            break;
                        }
                    }

                    IMEI::create([
                        'id_sp' => $data['id_sp'],
                        'imei' => $imei,
                        'trangthai' => 0,
                    ]);
                }
            }

            KHO::where('id', $id)->update($data);

            $newRow = KHO::find($id);

            $product = SANPHAM::find($data['id_sp']);
            $branchAddress = CHINHANH::find($data['id_cn'])->diachi;

            $newRow->product = $product;
            $newRow->branchAddress = $branchAddress;

            return [$newRow];
        }
    }

    public function destroy($id)
    {
        KHO::destroy($id);
    }

    public function AjaxGetKho(Request $request)
    {
        if($request->ajax()){
            $warehouse = KHO::find($request->id);

            $warehouse->chinhanh = CHINHANH::find($warehouse->id_cn);
            $warehouse->selected_product = SANPHAM::where('id', $warehouse->id_sp)->first();

            $lst_product = [];
            foreach(KHO::where('id_cn', $warehouse->id_cn)->get() as $key){
                array_push($lst_product, SANPHAM::where('id', $key['id_sp'])->first());
            }
            $warehouse->lst_product = $lst_product;

            return $warehouse;
        }
    }

    public function AjaxGetProductIsNotInStock(Request $request)
    {
        if($request->ajax()){
            // lấy sản phẩm không có trong kho tại chi nhánh
            $result = [];
            $lst_id = [];
            $qtyBranch = count(CHINHANH::all());

            // danh sách id sản phẩm không có trong kho tại chi nhánh
            foreach(SANPHAM::all() as $key){
                $exists = KHO::where('id_sp', $key->id)->get();
                if(count($exists) == 0 || count($exists) < $qtyBranch){
                    array_push($lst_id, $key->id);
                }
            }

            // không có
            if(!empty($lst_id)){
                // kiểm tra kho tại chi nhánh đang chọn có sản phẩm không
                foreach($lst_id as $id){
                    if(!KHO::where('id_sp', $id)->where('id_cn', $request->id_cn)->first()){
                        array_push($result, SANPHAM::find($id));
                    }
                }

                return $result;
            }
            // đã có sản phẩm trong kho
            else {
                return 'false';
            }
        }
    }

    public function AjaxGetProductById(Request $request)
    {
        if($request->ajax()){
            $warehouse = KHO::where('id_cn', $request->id_cn)->where('id_sp', $request->id_sp)->first();

            $product = SANPHAM::find($request->id_sp);

            return [
                'warehouse' => $warehouse,
                'product' => [$product]
            ];
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $lst_result = [];

            if($keyword == ''){
                $warehouses = KHO::limit(10)->get();
                foreach($warehouses as $warehouse){
                    $product = SANPHAM::find($warehouse->id_sp);
                    $branchAddress = CHINHANH::find($warehouse->id_cn)->diachi;

                    $warehouse->product = $product;
                    $warehouse->branchAddress = $branchAddress;
                }

                return $warehouses;
            }

            foreach(KHO::all() as $key){
                $address = $this->IndexController->unaccent(CHINHANH::find($key->id_cn)->diachi);
                $product = SANPHAM::find($key->id_sp);
    
                $string = strtolower($this->IndexController->unaccent($key->id.$address.$product->tensp.$product->mausac.$product->ram.$product->dungluong.$key->slton. ' Chiếc'));
                if(strpos($string, $keyword)){
                    $branchAddress = CHINHANH::find($key->id_cn)->diachi;

                    $key->product = $product;
                    $key->branchAddress = $branchAddress;
                    array_push($lst_result, $key);
                }
            }

            return $lst_result;
        }
    }

    public function AjaxFilter(Request $request)
    {
        if($request->ajax()){
            $arrFilter = $request->arrFilter;
            $lst_result = [];
            $lst_search = [];
            $html = '';

            // có danh sách tìm kiếm
            if($request->keyword){
                $keyword = $this->IndexController->unaccent($request->keyword);
                $lst_search = $this->search($keyword);
            }

            // gỡ bỏ lọc
            if(!isset($arrFilter)){
                // có danh sách tìm kiếm
                if(!empty($lst_search)){
                    foreach($lst_search as $key){
                        $branchAddress = CHINHANH::find($key->id_cn)->diachi;
                        $product = SANPHAM::find($key->id_sp);

                        $key->product = $product;
                        $key->branchAddress = $branchAddress;
                    }

                    return $lst_search;
                }
                else {
                    $warehouses = KHO::limit(10)->get();
                    foreach($warehouses as $key){
                        $branchAddress = CHINHANH::find($key->id_cn)->diachi;
                        $product = SANPHAM::find($key->id_sp);

                        $key->product = $product;
                        $key->branchAddress = $branchAddress;
                    }

                    return $warehouses;
                }
            }

            // lọc trên danh sách tìm kiếm
            if(!empty($lst_search)){
                foreach($arrFilter as $filter){
                    foreach($lst_search as $key){
                        if($key->id_cn == $filter){
                            array_push($lst_result, $key);
                        }
                    }
                }
            }
            // lọc trên db
            else {
                foreach($arrFilter as $filter){
                    foreach(KHO::all() as $key){
                        if($key->id_cn == $filter){
                            array_push($lst_result, $key);
                        }
                    }
                }
            }

            foreach($lst_result as $key){
                $branchAddress = CHINHANH::find($key->id_cn)->diachi;
                $product = SANPHAM::find($key->id_sp);

                $key->product = $product;
                $key->branchAddress = $branchAddress;
            }

            return $lst_result;
        }
    }

    public function search($keyword)
    {
        $lst_result = [];
        foreach(KHO::all() as $key){
            $address = CHINHANH::find($key->id_cn)->diachi;
            $product = SANPHAM::find($key->id_sp);

            $data = strtolower($this->IndexController->unaccent($key->id.$address.$product->tensp.$product->mausac.$product->ram.$product->dungluong.$key->slton.' Chiếc'));
            if(str_contains($data, $keyword)){
                array_push($lst_result, $key);
            }
        }

        return $lst_result;
    }
}
