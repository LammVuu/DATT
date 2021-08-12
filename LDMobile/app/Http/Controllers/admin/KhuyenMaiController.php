<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\user\IndexController;

use App\Models\KHUYENMAI;

class KhuyenMaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->admin='admin/content/';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        $lst_promotion = KHUYENMAI::limit(10)->get();

        foreach($lst_promotion as $i => $key){
            $dateEnd = strtotime(str_replace('/', '-', $key->ngayketthuc));
            $lst_promotion[$i]->trangthai = $dateEnd >= strtotime(date('d-m-Y')) ? 1 : 0;
        }

        $data = [
            'lst_promotion' => $lst_promotion,
        ];

        return view($this->admin."khuyen-mai")->with($data);
    }

    public function bindElement($id)
    {
        $data = KHUYENMAI::find($id);
        // trạng thái
        $status = strtotime(str_replace('/', '-', $data->ngayketthuc)) >= strtotime(date('d-m-Y')) ? 'Hoạt động' : 'Hết hạn';

        $html = '<tr data-id="'.$id.'">
                    <td class="vertical-center w-5">
                        <div class="pt-10 pb-10">'.$id.'</div>
                    </td>
                    <td class="vertical-center w-15">
                        <div class="pt-10 pb-10">'.$data->tenkm.'</div>
                    </td>
                    <td class="vertical-center w-24">
                        <div class="pt-10 pb-10">'.$data->noidung.'</div>
                    </td>
                    <td class="vertical-center w-10">
                        <div class="pt-10 pb-10">'.($data->chietkhau*100).'%</div>
                    </td>
                    <td class="vertical-center w-10">
                        <div class="pt-10 pb-10">'.$data->ngaybatdau.'</div>
                    </td>
                    <td class="vertival-center w-11">
                        <div class="pt-10 pb-10">'.$data->ngayketthuc.'</div>
                    </td>
                    <td class="vertical-center w-10">
                        <div data-id="'.$id.'" class="trangthai pt-10 pb-10">'.$status.'</div>
                    </td>
                    {{-- nút --}}
                    <td class="vertical-center w-15">
                        <div class="d-flex justify-content-start">
                            <div data-id="'.$id.'" class="info-khuyenmai-btn info-btn"><i class="fas fa-info"></i></div>
                            <div data-id="'.$id.'" class="edit-khuyenmai-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                            <div data-id="'.$id.'" data-object="khuyenmai" class="delete-khuyenmai-btn delete-btn">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>';
        return $html;
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'tenkm' => $request->tenkm,
                'noidung' => $request->noidung,
                'chietkhau' => $request->chietkhau,
                'ngaybatdau' => date('d/m/Y', strtotime($request->ngaybatdau)),
                'ngayketthuc' => date('d/m/Y', strtotime($request->ngayketthuc)),
            ];

            // kiểm tra đã tồn tại
            $exists = KHUYENMAI::where('tenkm', $data['tenkm'])
                                ->where('noidung', $data['noidung'])
                                ->where('chietkhau', $data['chietkhau'])
                                ->where('ngaybatdau', $data['ngaybatdau'])
                                ->where('ngayketthuc', $data['ngayketthuc'])
                                ->first();

            if($exists){
                return 'already exist';
            }

            $create = KHUYENMAI::create($data);
            
            $html = $this->bindElement($create->id);

            return [
                'id' => $create->id, 
                'html' => $html,
            ];
        }
    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $data = [
                'tenkm' => $request->tenkm,
                'noidung' => $request->noidung,
                'chietkhau' => $request->chietkhau,
                'ngaybatdau' => date('d/m/Y', strtotime($request->ngaybatdau)),
                'ngayketthuc' => date('d/m/Y', strtotime($request->ngayketthuc)),
            ];

            // kiểm tra đã tồn tại
            $exists = KHUYENMAI::where('tenkm', $data['tenkm'])
                                ->where('noidung', $data['noidung'])
                                ->where('chietkhau', $data['chietkhau'])
                                ->where('ngaybatdau', $data['ngaybatdau'])
                                ->where('ngayketthuc', $data['ngayketthuc'])
                                ->first();

            if($exists){
                return 'already exist';
            }

            KHUYENMAI::where('id', $id)->update($data);

            $html = $this->bindElement($id);

            return [
                'id' => $id,
                'html' => $html,
            ];
        }
    }

    public function destroy($id)
    {
        KHUYENMAI::destroy($id);
    }

    public function AjaxGetKhuyenMai(Request $request)
    {
        if($request->ajax()){
            $result =  KHUYENMAI::find($request->id);
            // format YYY-MM-dd
            $temp = explode('/', $result->ngaybatdau);
            $start = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
            $result->ngaybatdau = $start;

            $temp = explode('/', $result->ngayketthuc);
            $end = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
            $result->ngayketthuc = $end;

            return $result;
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(KHUYENMAI::all() as $key){
                    $html .= $this->bindElement($key->id);
                }
                return $html;
            }

            foreach(KHUYENMAI::all() as $key){
                // trạng thái
                $status = strtotime(str_replace('/', '-', $key->ngayketthuc)) >= strtotime(date('d-m-Y')) ? 'Hoạt động' : 'Hết hạn';
                $data = strtolower($this->IndexController->unaccent($key->id.$key->tenkm.$key->noidung.($key->chietkhau*100).'%'.$key->ngaybatdau.$key->ngayketthuc.$status));
                if(str_contains($data, $keyword)){
                    $html .= $this->bindElement($key->id);
                }
            }

            return $html;
        }
    }
}
