<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\CHINHANH;
use App\Models\TINHTHANH;

class ChiNhanhController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        // danh sách chi nhánh
        $lst_branch = CHINHANH::limit(10)->get();
        foreach($lst_branch as $i => $key){
            $lst_branch[$i]->tinhthanh = TINHTHANH::find($key->id_tt)->tentt;
        }


        $data = [
            'lst_branch' => $lst_branch,
            'lst_province' => TINHTHANH::all(),
        ];

        return view($this->admin.'chi-nhanh')->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'diachi' => $request->diachi,
                'sdt' => $request->sdt,
                'id_tt' => $request->id_tt,
                'trangthai' => $request->trangthai,
            ];

            $create = CHINHANH::create($data);

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['diachi'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sdt'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.TINHTHANH::find($request->id_tt)->tentt.'</div>
                        </td>
                        <td class="vertical-center">
                            <div data-id="'.$create->id.'" class="trangthai pt-10 pb-10">Hoạt động</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                            </div>
                        </td>
                    </tr>';

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
                'diachi' => $request->diachi,
                'sdt' => $request->sdt,
                'id_tt' => $request->id_tt,
                'trangthai' => $request->trangthai,
            ];

            CHINHANH::where('id', $id)->update($data);

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['diachi'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sdt'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.TINHTHANH::find($request->id_tt)->tentt.'</div>
                        </td>
                        <td class="vertical-center">
                            <div data-id="'.$id.'" class="trangthai pt-10 pb-10">'.($data['trangthai'] == 1 ? 'Hoạt động' : 'Ngừng hoạt động').'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.(
                                $data['trangthai'] != 0 ?
                                '<div data-id="'.$id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '
                                <div data-id="'.$id.'" class="undelete-btn">
                                    <i class="fas fa-trash-undo"></i>
                                </div>').'
                            </div>
                        </td>
                    </tr>';

            return [
                'id' => $id,
                'html' => $html,
            ];
        }
    }

    public function destroy($id)
    {
        CHINHANH::where('id', $id)->update(['trangthai' => 0]);
    }

    public function AjaxRestore(Request $request)
    {
        if($request->ajax()){
            CHINHANH::where('id', $request->id)->update(['trangthai' => 1]);
        }
    }

    public function AjaxGetChiNhanh(Request $request)
    {
        if($request->ajax()){
            return CHINHANH::find($request->id);
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(CHINHANH::all() as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->diachi.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sdt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.TINHTHANH::find($key->id_tt)->tentt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng hoạt động').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.(
                                        $key->trangthai != 0 ?
                                        '<div data-id="'.$key->id.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>').'
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            }

            foreach(CHINHANH::all() as $key){
                $province = TINHTHANH::find($key->id_tt)->tentt;
                $data = strtolower($this->IndexController->unaccent($key->diachi.$key->sdt.$province.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng hoạt động')));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->diachi.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sdt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.TINHTHANH::find($key->id_tt)->tentt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng hoạt động').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.(
                                        $key->trangthai != 0 ?
                                        '<div data-id="'.$key->id.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>').'
                                    </div>
                                </td>
                            </tr>';
                }
            }
            return $html;
        }
    }
}
