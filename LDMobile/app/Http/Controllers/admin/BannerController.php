<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\BANNER;

class BannerController extends Controller
{
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
        $data = [
            'banner' => BANNER::all(),
        ];
        return view($this->admin."banner")->with($data);
    }

    public function bindElement($id)
    {
        $data = BANNER::find($id);
        $html = '<tr data-id="'.$id.'">
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$id.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$data->link.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">
                            <img src="images/banner/'.$data->hinhanh.'" alt="" width="300px">
                        </div>
                    </td>
                    {{-- nút --}}
                    <td class="vertical-center w-10">
                        <div class="d-flex justify-content-start">
                            <div data-id="'.$id.'" class="info-btn"><i class="fas fa-info"></i></div>
                            <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                            <div data-id="'.$id.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                        </div>
                    </td>
                </tr>';
        return $html;
}

    public function store(Request $request)
    {
        if($request->ajax()){
            // định dạng hình
            $imageFormat = $this->IndexController->getImageFormat($request->hinhanh);
            if($imageFormat == 'png'){
                $base64 = str_replace('data:image/png;base64,', '', $request->hinhanh);
                $imageName = 'banner'.(count(BANNER::all()) + 1).'.png';
            } else {
                $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
                $imageName = 'banner'.(count(BANNER::all()) + 1).'.jpg';
            }
            
            // lưu Hình
            $this->IndexController->saveImage('images/banner/'.$imageName, $base64);

            $data = [
                'link' => $request->link,
                'hinhanh' => $imageName,
            ];

            $create = BANNER::create($data);

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
            $oldData = BANNER::find($id);

            $imageName = $oldData->hinhanh;

            // nếu có thay đổi hình ảnh
            if($request->hinhanh){
                // xóa hình cũ
                unlink('images/banner/' . $oldData->hinhanh);

                // định dạng hình
                $imageFormat = $this->IndexController->getImageFormat($request->hinhanh);
                if($imageFormat == 'png'){
                    $base64 = str_replace('data:image/png;base64,', '', $request->hinhanh);
                    $imageName = 'banner'.(count(BANNER::all()) + 1).'.png';
                } else {
                    $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
                    $imageName = 'banner'.(count(BANNER::all()) + 1).'.jpg';
                }
                
                // lưu Hình
                $this->IndexController->saveImage('images/banner/'.$imageName, $base64);
            }

            $data = [
                'link' => $request->link,
                'hinhanh' => $imageName,
            ];
            
            BANNER::where('id', $id)->update($data);

            $html = $this->bindElement($id);

            return $html;
        }
    }

    public function destroy($id)
    {
        $banner = BANNER::find($id);
        // xóa hình
        unlink('images/banner/' . $banner->hinhanh);
        BANNER::destroy($id);
    }

    public function AjaxGetBanner(Request $request)
    {
        if($request->ajax()){
            return BANNER::find($request->id);
        }
    }
}
