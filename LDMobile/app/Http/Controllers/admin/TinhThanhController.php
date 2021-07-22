<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\TINHTHANH;

class TinhThanhController extends Controller
{
    public function __construct()
    {
        $this->admin = 'admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        $data = [
            'lst_province' => TINHTHANH::limit(10)->get(),
        ];

        return view($this->admin.'tinh-thanh')->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'tentt' => $request->tentt,
            ];

            // đã có tỉnh thành
            if(TINHTHANH::where('tentt', 'like', $data['tentt'])->first()){
                return 'exists';
            }

            $create = TINHTHANH::create($data);

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tentt'].'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" data-name="'.$data['tentt'].'" class="delete-btn"><i class="fas fa-trash"></i></div>
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
                'tentt' => $request->tentt,
            ];

            // đã có tỉnh thành
            if(TINHTHANH::where('tentt', 'like', $data['tentt'])->first()){
                return 'exists';
            }

            TINHTHANH::where('id', $id)->update($data);

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tentt'].'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$id.'" data-name="'.$data['tentt'].'" class="delete-btn"><i class="fas fa-trash"></i></div>
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
        TINHTHANH::destroy($id);
    }

    public function AjaxGetTinhThanh(Request $request)
    {
        if($request->ajax()){
            return TINHTHANH::find($request->id);
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(TINHTHANH::all() as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tentt.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" data-name="'.$key->tentt.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            }

            foreach(TINHTHANH::all() as $key){
                $data = strtolower($this->IndexController->unaccent($key->id.$key->tentt));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tentt.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" data-name="'.$key->tentt.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                                    </div>
                                </td>
                            </tr>';
                }
            }

            return $html;
        }
    }
}
