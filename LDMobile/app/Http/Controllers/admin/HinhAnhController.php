<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\MAUSP;
use App\Models\HINHANH;

class HinhAnhController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }
    public function index()
    {
        // danh sách hình ảnh theo mẫu
        $lst_image = [];
        foreach(MAUSP::limit(10)->get() as $model){
            $temp = [
                'id_msp' => $model->id,
                'tenmau' => $model->tenmau,
                'hinhanh' => [],
            ];

            $image = HINHANH::where('id_msp', $model->id)->get();
            foreach($image as $key){
                array_push($temp['hinhanh'], $key['hinhanh']);
            }

            array_push($lst_image, $temp);
        }

        $data = [
            'lst_image' => $lst_image,
        ];

        return view($this->admin."hinh-anh")->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $model = MAUSP::find($request->id_msp);

            foreach($request->lst_base64 as $i => $key){
                // thêm hình
                $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                $image = base64_decode($base64);

                $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' image '.($i + 1).'.jpg'));
                $urlImage = 'images/phone/' . $imageName;
                file_put_contents($urlImage, $image);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                HINHANH::create($data);
            }

            $html = '';
            foreach(MAUSP::all() as $key){
                $imageQty = count(HINHANH::where('id_msp', $key->id)->get());
                $html .= '<tr data-id="'.$key->id.'">
                            <td class="vertical-center w-50">
                                <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                            </td>
                            <td class="vertical-center">
                                <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$imageQty.' Hình</div>
                            </td>
                            {{-- nút --}}
                            <td class="vertical-center w-10">
                                <div class="d-flex justify-content-start">
                                    <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                    <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                    <div data-id="'.$key->id.'" data-name="'.$model->tenmau.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                                </div>
                            </td>
                        </tr>';
            }

            return [
                'id' => $model->id,
                'html' => $html,
            ];
        }
    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $oldData = HINHANH::where('id_msp', $id)->get();
            $model = MAUSP::find($id);

            // xóa hình cũ, xóa db
            foreach($oldData as $key){
                unlink('images/phone/' . $key['hinhanh']);
            }
            HINHANH::where('id_msp', $id)->delete();

            // cập nhật hình mới
            foreach($request->lst_base64 as $i => $key){
                // thêm hình
                $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                $image = base64_decode($base64);

                $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' image '.($i + 1).'.jpg'));
                $urlImage = 'images/phone/' . $imageName;
                file_put_contents($urlImage, $image);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                HINHANH::create($data);
            }

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center w-50">
                            <div class="pt-10 pb-10">'.$model->tenmau.'</div>
                        </td>
                        <td class="vertical-center">
                            <div data-id="'.$id.'" class="qty-image pt-10 pb-10">'.count($request->lst_base64).' Hình</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$id.'" data-name="'.$model->tenmau.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                            </div>
                        </td>
                    </tr>';
            return $html;
        }
    }

    public function destroy($id)
    {
        // xóa hình
        foreach(HINHANH::where('id_msp', $id)->get() as $key){
            unlink('images/phone/' . $key['hinhanh']);
        }
        HINHANH::where('id_msp', $id)->delete();
    }

    public function AjaxGetHinhAnh(Request $request)
    {
        if($request->ajax()){
            return [
                'lst_image' => HINHANH::where('id_msp', $request->id)->get(),
                'lst_model' => MAUSP::all(),
            ];
        }
    }

    public function AjaxGetModelHaveNotImage(Request $request)
    {
        if($request->ajax()){
            $lst_result = [];

            // danh sách mẫu sp chưa có hình ảnh
            foreach(MAUSP::all() as $model){
                $exists = MAUSP::find($model->id)->hinhanh;
                if(count($exists) == 0){
                    array_push($lst_result, $model);
                }
            }

            return $lst_result;
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(MAUSP::limit(10)->get() as $key){
                    $imageQty = count(HINHANH::where('id_msp', $key->id)->get());
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-50">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$imageQty.' Hình</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($imageQty != 0 ? '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tenmau.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '').'
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            }

            foreach(MAUSP::all() as $key){
                $imageQty = count(HINHANH::where('id_msp', $key->id)->get());
                $data = strtolower($this->IndexController->unaccent($key->tenmau.$imageQty.' Hình'));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-50">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$imageQty.' Hình</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($imageQty != 0 ? '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tenmau.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '').'
                                    </div>
                                </td>
                            </tr>';
                }
            }
            return $html;
        }
    }
}
