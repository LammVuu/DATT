<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\BAOHANH;
use App\Models\SANPHAM;
use App\Models\MAUSP;
use App\Models\IMEI;

class BaoHanhController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function index()
    {
        $lst_warranty = BAOHANH::limit(10)->get();
        foreach($lst_warranty as $i => $key){
            $lst_warranty[$i]->sanpham = SANPHAM::find(IMEI::find($key->id_imei)->id_sp);
        }
        $data = [
            'lst_warranty' => $lst_warranty,
        ];

        return view($this->admin."bao-hanh")->with($data);
    }

    public function bindElement($id)
    {
        $data = BAOHANH::find($id);
        $html = '<tr data-id="'.$id.'">
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$id.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$data->imei.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$data->ngaymua.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$data->ngayketthuc.'</div>
                    </td>
                    {{-- nút --}}
                    <td class="vertical-center w-5">
                        <div class="d-flex justify-content-start">
                            <div data-id="'.$id.'" class="info-btn"><i class="fas fa-info"></i></div>
                        </div>
                    </td>
                </tr>';
        return $html;
    }

    public function AjaxGetBaoHanh(Request $request)
    {
        if($request->ajax()){
            $warranty = BAOHANH::find($request->id);
            $id_sp = IMEI::find($warranty->id_imei)->id_sp;

            $warranty->sanpham = SANPHAM::find($id_sp);
            $warranty->baohanh = MAUSP::find(SANPHAM::find($id_sp)->id_msp)->baohanh;
            // có bảo hành
            if($warranty->baohanh){
                $warranty->trangthai = $this->warrantyStatus($warranty->ngayketthuc);
            }
            // không có bảo hành
            else {
                $warranty->trangthai = 'no';
            }

            return $warranty;
        }
    }

    // kiểm tra còn bảo hành không
    public function warrantyStatus($dateEnd){
        $status = 0;

        // ngày kết thúc
        $end = strtotime(str_replace('/', '-', $dateEnd));
        // ngày hiện tại
        $current = strtotime(date('d-m-Y'));
        // kiểm tra còn bảo hành không
        return $end >= $current ? 1 : 0;
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(BAOHANH::limit(10)->get() as $key){
                    $html .= $this->bindElement($key->id);
                }
                return $html;
            }

            foreach(BAOHANH::all() as $key){
                $data = strtolower($this->IndexController->unaccent($key->id.$key->imei.$key->ngaymua.$key->ngayketthuc));
                if(str_contains($data, $keyword)){
                    $html .= $this->bindElement($key->id);
                }
            }
            return $html;
        }
    }
}
