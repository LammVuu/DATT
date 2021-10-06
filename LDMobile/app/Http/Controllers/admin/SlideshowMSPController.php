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

        // chưa có thư mục lưu hình
        if(!is_dir('images/phone/slideshow')){
            // tạo thư mục lưu hình
            mkdir('images/phone/slideshow', 0777, true);
        }
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

            foreach($request->lst_base64_slideshow as $i => $key){
                // định dạng hình
                $imageFormat = $this->IndexController->getImageFormat($key);
                if($imageFormat == 'png'){
                    $base64 = str_replace('data:image/png;base64,', '', $key);
                    $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' '.time().$i.'.png'));
                } else {
                    $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                    $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' '.time().$i.'.jpg'));
                }
                // lưu hình
                $this->IndexController->saveImage('images/phone/slideshow/'.$imageName, $base64);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                SLIDESHOW_CTMSP::create($data);
            }

            $models = MAUSP::all();
            foreach($models as $val) {
                $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $val->id)->get());
                $val->slideQty = $slideQty;
            }

            return [
                'id' => $model->id,
                'data' => $models
            ];
        }
    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $oldData = SLIDESHOW_CTMSP::where('id_msp', $id)->get();
            $model = MAUSP::find($id);

            // xóa hình cũ
            if($request->lst_delete){
                foreach($request->lst_delete as $name){
                    unlink('images/phone/slideshow/' . $name);
                    SLIDESHOW_CTMSP::where('id_msp', $id)->where('hinhanh', $name)->delete();
                }
            }

            // cập nhật hình mới
            if($request->lst_base64_slideshow){
                foreach($request->lst_base64_slideshow as $i => $key){
                    // định dạng hình
                    $imageFormat = $this->IndexController->getImageFormat($key);
                    if($imageFormat == 'png'){
                        $base64 = str_replace('data:image/png;base64,', '', $key);
                        $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' '.time().$i.'.png'));
                    } else {
                        $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                        $imageName = strtolower(str_replace(' ', '_', $model->tenmau.' '.time().$i.'.jpg'));
                    }
                    // lưu hình
                    $this->IndexController->saveImage('images/phone/slideshow/'.$imageName, $base64);
    
                    $data = [
                        'id_msp' => $model->id,
                        'hinhanh' => $imageName,
                    ];
    
                    SLIDESHOW_CTMSP::create($data);
                }
            }

            $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $id)->get());
            $model->slideQty = $slideQty;

            return [$model];
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
            $lst_result = [];

            if($keyword == ''){
                $models = MAUSP::limit(10)->get();
                foreach($models as $key){
                    $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());
                    $key->slideQty = $slideQty;
                }

                return $models;
            }

            foreach(MAUSP::all() as $key){
                $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());
                $data = strtolower($this->IndexController->unaccent($key->tenmau.$slideQty.' Hình'));
                if(str_contains($data, $keyword)){
                    $slideQty = count(SLIDESHOW_CTMSP::where('id_msp', $key->id)->get());
                    $key->slideQty = $slideQty;
                    array_push($lst_result, $key);
                }
            }

            return $lst_result;
        }
    }
}
