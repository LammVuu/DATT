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

        // chưa có thư mục lưu hình
        if(!is_dir('images/banner')){
            // tạo thư mục lưu hình
            mkdir('images/banner', 0777, true);
        }
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
                $this->IndexController->saveImage('images/phone/'.$imageName, $base64);

                $data = [
                    'id_msp' => $model->id,
                    'hinhanh' => $imageName,
                ];

                HINHANH::create($data);
            }

            $models = MAUSP::all();
            foreach($models as $model){
                $imageQty = count(HINHANH::where('id_msp', $model->id)->get());
                $model->imageQty = $imageQty;
            }   

            return [
                'id' => $model->id,
                'data' => $models,
            ];
        }
    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $model = MAUSP::find($id);

            // xóa hình cũ
            if($request->lst_delete){
                foreach($request->lst_delete as $name){
                    unlink('images/phone/' . $name);
                    HINHANH::where('id_msp', $id)->where('hinhanh', $name)->delete();
                }
            }

            // cập nhật hình mới
            if($request->lst_base64){
                foreach($request->lst_base64 as $i => $key){
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
                    $this->IndexController->saveImage('images/phone/'.$imageName, $base64);
    
                    $data = [
                        'id_msp' => $model->id,
                        'hinhanh' => $imageName,
                    ];
    
                    HINHANH::create($data);
                }
            }

            $imageQty = count(HINHANH::where('id_msp', $id)->get());
            $model->imageQty = $imageQty;

            return [$model];
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
            $lst_result = [];

            if($keyword == ''){
                $models = MAUSP::limit(10)->get();
                foreach($models as $model){
                    $imageQty = count(HINHANH::where('id_msp', $model->id)->get());
                    $model->imageQty = $imageQty;
                }
                return $models;
            }

            foreach(MAUSP::all() as $key){
                $imageQty = count(HINHANH::where('id_msp', $key->id)->get());
                $string = strtolower($this->IndexController->unaccent($key->tenmau.$imageQty.' Hình'));
                if(str_contains($string, $keyword)){
                    $imageQty = count(HINHANH::where('id_msp', $key->id)->get());
                    $key->imageQty = $imageQty;
                    array_push($lst_result, $key);
                }
            }
            return $lst_result;
        }
    }
}
