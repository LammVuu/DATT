<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\SLIDESHOW_CTMSP;
use App\Models\MAUSP;

class SlideshowMSPController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }

    public function index()
    {
        // danh sách slide theo mẫu sp
        $lst_slide = [];
        foreach(MAUSP::limit(10)->get() as $model){
            $temp = [
                'id_msp' => $model->id,
                'tenmau' => MAUSP::find($model->id)->tenmau,
                'slide' => [],
            ];

            $slide = MAUSP::find($model->id)->slideshow_ctmsp;

            foreach($slide as $key){
                $data = [
                    'id' => $key['id'],
                    'id_msp' => $key['id_msp'],
                    'hinhanh' => $key['hinhanh'],
                ];
                array_push($temp['slide'], $data);
            }

            array_push($lst_slide, $temp);
        }

        $data = [
            'lst_slide' => $lst_slide,
            'lst_model' => MAUSP::all(),
        ];

        return view($this->admin.'slideshow-msp')->with($data);
    }

    
    public function store(Request $request)
    {
        if($request->ajax()){
            $model = MAUSP::find($request->id_msp);

            foreach($request->image_slideshow as $i => $key){
                // thêm hình
                $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                $image = base64_decode($base64);

                $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' slide '.($i + 1).'.jpg'));
                $urlImage = 'images/phone/slideshow/' . $imageName;
                file_put_contents($urlImage, $image);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                SLIDESHOW_CTMSP::create($data);
            }

            $html = '';
            foreach(MAUSP::all() as $key){
                $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());
                $html .= '<tr data-id="'.$key->id.'">
                            <td class="vertical-center w-50">
                                <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                            </td>
                            <td class="vertical-center">
                                <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$slideQty.' hình </div>
                            </td>
                            {{-- nút --}}
                            <td class="vertical-center w-10">
                                <div class="d-flex justify-content-start">
                                    <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                    <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                    ($slideQty != 0 ? '
                                    <div data-id="'.$key->id.'" data-name="'.$key->tenmau.'" class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </div>' : '').'
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
            $oldData = SLIDESHOW_CTMSP::where('id_msp', $id)->get();
            $model = MAUSP::find($id);

            // xóa hình cũ, xóa db
            foreach($oldData as $key){
                unlink('images/phone/slideshow/' . $key['hinhanh']);
            }
            SLIDESHOW_CTMSP::where('id_msp', $id)->delete();

            // cập nhật hình mới
            foreach($request->image_slideshow as $i => $key){
                // thêm hình
                $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                $image = base64_decode($base64);

                $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' slide '.($i + 1).'.jpg'));
                $urlImage = 'images/phone/slideshow/' . $imageName;
                file_put_contents($urlImage, $image);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                SLIDESHOW_CTMSP::create($data);
            }

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center w-50">
                            <div class="pt-10 pb-10">'.$model->tenmau.'</div>
                        </td>
                        <td class="vertical-center">
                        <div data-id="'.$id.'" class="qty-image pt-10 pb-10">'.count($request->image_slideshow).' hình </div>
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
        // xóa hình, xóa db
        foreach(SLIDESHOW_CTMSP::where('id_msp', $id)->get() as $key){
            unlink('images/phone/slideshow/' . $key['hinhanh']);
        }

        SLIDESHOW_CTMSP::where('id_msp', $id)->delete();
    }

    public function AjaxGetSlideshowMSP(Request $request)
    {
        if($request->ajax()){
            return [
                'lst_slide' => MAUSP::find($request->id)->slideshow_ctmsp,
                'lst_model' => MAUSP::all(),
            ];
        }
    }

    public function AjaxGetModelHaveNotSlideshow(Request $request)
    {
        if($request->ajax()){
            // danh sách mẫu sp chưa có hình
            $lst_haveNotImage = [];
            foreach(MAUSP::all() as $model){
                $exists = MAUSP::find($model->id)->slideshow_ctmsp;
                if(count($exists) == 0){
                    array_push($lst_haveNotImage, $model);
                }
            }

            return $lst_haveNotImage;
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(MAUSP::limit(10)->get() as $key){
                    $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());

                    $html .= '<tr data-id="'.$key->id.'">
                        <td class="vertical-center w-50">
                            <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                        </td>
                        <td class="vertical-center">
                        <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$slideQty.' hình </div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                ($slideQty != 0 ? '
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
                $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());
                $data = strtolower($this->IndexController->unaccent($key->tenmau.$slideQty.' Hình'));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                        <td class="vertical-center w-50">
                            <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                        </td>
                        <td class="vertical-center">
                        <div data-id="'.$key->id.'" class="qty-image pt-10 pb-10">'.$slideQty.' hình </div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                ($slideQty != 0 ? '
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
