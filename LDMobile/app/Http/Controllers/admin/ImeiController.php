<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\IMEI;
use App\Models\SANPHAM;
use App\Models\MAUSP;

class ImeiController extends Controller
{
    public function __construct()
    {
        $this->admin = 'admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        $lst_imei = IMEI::limit(10)->get();
        foreach($lst_imei as $i => $key){
            $product = SANPHAM::find($key->id_sp);
            // sản phẩm
            $lst_imei[$i]->product = $product;
            // id_ncc
            $lst_imei[$i]->id_ncc = MAUSP::find($product->id_msp)->id_ncc;
        }

        $data = [
            'lst_imei' => $lst_imei,
        ];

        return view($this->admin.'imei')->with($data);
    }

    public function bindElement($id)
    {
        $data = IMEI::find($id);
        $product = SANPHAM::find($data->id_sp);
        $html = '<tr data-id="'.$id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="d-flex pt-10 pb-10">
                                        <img src="images/phone/'.$product->hinhanh.'" alt="" width="70px">
                                        <div class="ml-10">
                                            <div class="d-flex align-items-center fw-600">'.
                                                $product->tensp.'
                                                <i class="fas fa-circle fz-5 ml-5 mr-5"></i>'.
                                                $product->mausac.'
                                            </div>
                                            <div>Ram: '.$product->ram.'</div>
                                            <div>Dung lượng: '.$product->dungluong.'</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$data->imei.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$id.'" class="trangthai pt-10 pb-10">'.($data->trangthai == 1 ? 'Đã kích hoạt' : 'Chưa kích hoạt').'</div>
                                </td>
                            </tr>';
        return $html;
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(IMEI::limit(10)->get() as $key){
                    $html .= $this->bindElement($key->id);
                }
                return $html;
            }

            $count = 0;

            foreach(IMEI::all() as $key){
                // lấy 10 bản ghi
                if($count == 10){
                    break;
                }
                $product = SANPHAM::find($key->id_sp);
                $data = strtolower($this->IndexController->unaccent($key->id.$product->tensp.$product->mausac.$product->ram.$product->dungluong.$key->imei.($key->trangthai == 1 ? 'Đã kích hoạt' : 'Chưa kích hoạt')));
                if(str_contains($data, $keyword)){
                    $html .= $this->bindElement($key->id);
                    $count++;
                }
            }
            return $html;
        }
    }
}
