<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\user\IndexController;

use App\Models\MAUSP;
use App\Models\NHACUNGCAP;
use App\Models\SANPHAM;

class MauSanPhamController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        $lst_model = MAUSP::limit(10)->get();

        foreach($lst_model as $idx => $key){
            $lst_model[$idx]['nhacungcap'] = NHACUNGCAP::find($key->id_ncc);
        }

        $data = [
            'lst_supplier' => NHACUNGCAP::all(),
            'lst_model' => $lst_model,
        ];

        return view($this->admin."mau-san-pham")->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            // kiểm tra trùng tên
            if(MAUSP::where('tenmau', 'like',  '%'.$request->tenmau.'%')->first()){
                return 'invalid name';
            }
            
            $data = [
                'tenmau' => $request->tenmau,
                'id_youtube' => $request->id_youtube,
                'id_ncc' => $request->id_ncc,
                'baohanh' => $request->baohanh,
                'diachibaohanh' => $request->diachibaohanh,
                'trangthai' => 1,
            ];

            $create = MAUSP::create($data);

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center w-5">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center w-15">
                            <div class="pt-10 pb-10">'.$data['tenmau'].'</div>
                        </td>
                        <td class="vertical-center w-15">
                            <div class="pt-10 pb-10">'.NHACUNGCAP::find($data['id_ncc'])->tenncc.'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'.$data['baohanh'].'</div>
                        </td>
                        <td class="vertical-center w-30">
                            <div class="pt-10 pb-10">'.$data['diachibaohanh'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'; $html .= $data['trangthai'] == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh'.'</div>
                        </td>';
                        $html .= '<td class="vertical-center w-15">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>
                            </div>
                        </td>
                    </tr>';
            
            return [
                'id' => $create->id,
                'html' => $html,
            ];
        }
    }

    public function update(Request $request)
    {
        if($request->ajax()){
            $data = [
                'tenmau' => $request->tenmau,
                'id_youtube' => $request->id_youtube,
                'id_ncc' => $request->id_ncc,
                'baohanh' => $request->baohanh,
                'diachibaohanh' => $request->diachibaohanh,
                'trangthai' => $request->trangthai,
            ];

            MAUSP::where('id', $request->id)->update($data);
            SANPHAM::where('id_msp', $request->id)->update(['trangthai' => $data['trangthai']]);

            $html = '<tr data-id="'.$request->id.'">
                        <td class="vertical-center w-5">
                            <div class="pt-10 pb-10">'.$request->id.'</div>
                        </td>
                        <td class="vertical-center w-15">
                            <div class="pt-10 pb-10">'.$data['tenmau'].'</div>
                        </td>
                        <td class="vertical-center w-15">
                            <div class="pt-10 pb-10">'.NHACUNGCAP::find($data['id_ncc'])->tenncc.'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'.$data['baohanh'].'</div>
                        </td>
                        <td class="vertical-center w-30">
                            <div class="pt-10 pb-10">'.$data['diachibaohanh'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'.($data['trangthai'] == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                        </td>
                        <td class="vertical-center w-15">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$request->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$request->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                ($data['trangthai'] != 0 ? '
                                <div data-id="'.$request->id.'" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>' : '
                                <div data-id="'.$request->id.'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>').'
                            </div>
                        </td>
                    </tr>';
            
            return $html;
        }
    }

    public function destroy($id)
    {
        MAUSP::where('id', $id)->update(['trangthai' => 0]);
        SANPHAM::where('id_msp', $id)->update(['trangthai' => 0]);
    }

    public function AjaxRestore(Request $request)
    {
        if($request->ajax()){
            MAUSP::find($request->id)->update(['trangthai' => 1]);
            SANPHAM::where('id_msp', $request->id)->update(['trangthai' => 1]);
        }
    }

    public function AjaxGetMausp(Request $request)
    {
        if($request->ajax()){
            return MAUSP::find($request->id);
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(MAUSP::limit(10)->get() as $key){
                    $supplierName = NHACUNGCAP::find($key->id_ncc)->tenncc;
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-5">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$supplierName.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                </td>
                                <td class="vertical-center w-30">
                                    <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-15">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai != 0 ? '
                                            <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>').'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            }

            foreach(MAUSP::all() as $key){
                $supplierName = NHACUNGCAP::find($key->id_ncc)->tenncc;
                $data = strtolower($this->IndexController->unaccent($key->id.$key->tenmau.$supplierName.$key->baohanh.$key->diachibaohanh.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh')));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-5">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$supplierName.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                </td>
                                <td class="vertical-center w-30">
                                    <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-15">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai != 0 ? '
                                            <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>').'
                                    </div>
                                </td>
                            </tr>';
                }
            }

            return $html;
        }
    }

    public function AjaxFilter(Request $request)
    {
        if($request->ajax()){
            $arrFilter = $request->arrFilter;
            $lst_search = [];
            $lst_temp = [];
            $lst_result = [];
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            // có danh sách tìm kiếm
            if($keyword){
                $lst_search = $this->search($keyword);
            }

            // không có lọc
            if(empty($arrFilter)){
                // không có tìm kiếm
                if(empty($lst_search)){
                    foreach(MAUSP::limit(10)->get() as $key){
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center w-5">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center w-15">
                                        <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                    </td>
                                    <td class="vertical-center w-15">
                                        <div class="pt-10 pb-10">'.NHACUNGCAP::find($key->id_ncc)->tenncc.'</div>
                                    </td>
                                    <td class="vertical-center w-10">
                                        <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                    </td>
                                    <td class="vertical-center w-30">
                                        <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                    </td>
                                    <td class="vertical-center w-10">
                                        <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-15">
                                        <div class="d-flex justify-content-start">
                                            <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                            <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                            ($key->trangthai != 0 ? '
                                                <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '' ).'
                                        </div>
                                    </td>
                                </tr>';   
                    }
                } else {
                    foreach($lst_search as $key){
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center w-5">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center w-15">
                                        <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                    </td>
                                    <td class="vertical-center w-15">
                                        <div class="pt-10 pb-10">'.NHACUNGCAP::find($key->id_ncc)->tenncc.'</div>
                                    </td>
                                    <td class="vertical-center w-10">
                                        <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                    </td>
                                    <td class="vertical-center w-30">
                                        <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                    </td>
                                    <td class="vertical-center w-10">
                                        <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-15">
                                        <div class="d-flex justify-content-start">
                                            <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                            <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                            ($key->trangthai != 0 ? '
                                                <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '' ).'
                                        </div>
                                    </td>
                                </tr>';   
                    }
                }

                return $html;
            }

            // tiêu chí lọc đầu tiên trên danh sách tìm kiếm
            if(!empty($lst_search)){
                if(array_key_first($arrFilter) == 'supplier'){
                    foreach($arrFilter['supplier'] as $supplier){
                        foreach($lst_search as $key){
                            if($key->id_ncc == $supplier){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } else {
                    foreach($arrFilter['status'] as $status){
                        foreach($lst_search as $key){
                            if($key->trangthai == $status){
                                array_push($lst_temp, $key);
                            }
                        }
                    }   
                }
            } else {
                if(array_key_first($arrFilter) == 'supplier'){
                    foreach($arrFilter['supplier'] as $key){
                        $lst_model = MAUSP::where('id_ncc', $key)->get();
                        foreach($lst_model as $model){
                            array_push($lst_temp, $model);
                        }
                    }
                } else {
                    foreach($arrFilter['status'] as $key){
                        $lst_model = MAUSP::where('trangthai', $key)->get();
                        foreach($lst_model as $model){
                            array_push($lst_temp, $model);
                        }
                    }   
                }
            }

            // chỉ có 1 tiêu chí
            if(count($arrFilter) == 1){
                foreach($lst_temp as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-5">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.NHACUNGCAP::find($key->id_ncc)->tenncc.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                </td>
                                <td class="vertical-center w-30">
                                    <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-15">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai != 0 ? '
                                            <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '' ).'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            }

            // tiếp tục lọc các tiêu chí còn lại
            // tiêu chí tiếp theo là nhà cung cấp
            if(array_keys($arrFilter)[1] == 'supplier'){
                foreach($arrFilter['supplier'] as $supplier){
                    foreach($lst_temp as $temp){
                        if($temp->id_ncc == $supplier){
                            array_push($lst_result, $temp);
                        }
                    }
                }
            } 
            // tiêu chí tiếp theo là trạng thái
            else {
                foreach($arrFilter['status'] as $status){
                    foreach($lst_temp as $temp){
                        if($temp->trangthai == $status){
                            array_push($lst_result, $temp);
                        }
                    }
                }
            }

            // render danh sách kết quả
            foreach($lst_result as $key){
                $html .= '<tr data-id="'.$key->id.'">
                            <td class="vertical-center w-5">
                                <div class="pt-10 pb-10">'.$key->id.'</div>
                            </td>
                            <td class="vertical-center w-15">
                                <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                            </td>
                            <td class="vertical-center w-15">
                                <div class="pt-10 pb-10">'.NHACUNGCAP::find($key->id_ncc)->tenncc.'</div>
                            </td>
                            <td class="vertical-center w-10">
                                <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                            </td>
                            <td class="vertical-center w-30">
                                <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                            </td>
                            <td class="vertical-center w-10">
                                <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                            </td>
                            {{-- nút --}}
                            <td class="vertical-center w-15">
                                <div class="d-flex justify-content-start">
                                    <div data-id="'.$key->id.'" class="info-mausp-btn info-btn"><i class="fas fa-info"></i></div>
                                    <div data-id="'.$key->id.'" class="edit-mausp-modal-show edit-btn"><i class="fas fa-pen"></i></div>'.
                                    ($key->trangthai != 0 ? '
                                        <div data-id="'.$key->id.'" class="delete-mausp-btn delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '' ).'
                                </div>
                            </td>
                        </tr>';   
            }

            return $html;
        }
    }
    
    public function search($keyword)
    {
        $lst_result = [];

        foreach(MAUSP::all() as $key){
            $supplierName = NHACUNGCAP::find($key->id_ncc)->tenncc;
            $data = strtolower($this->IndexController->unaccent($key->id.$key->tenmau.$supplierName.$key->baohanh.$key->diachibaohanh.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh')));
            if(str_contains($data, $keyword)){
                array_push($lst_result, $key);
            }
        }

        return $lst_result;
    }
}
