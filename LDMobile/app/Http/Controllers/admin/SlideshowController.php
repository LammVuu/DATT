<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\SLIDESHOW;

class SlideshowController extends Controller
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
        if(!is_dir('images/slideshow')){
            // tạo thư mục lưu hình
            mkdir('images/slideshow', 0777, true);
        }
    }
    public function index()
    {
        $data = [
            'slideshow' => SLIDESHOW::all()
        ];

        return view($this->admin."slideshow")->with($data);
    }

    public function bindElement($id)
    {
        $data = SLIDESHOW::find($id);
        $html = '<tr data-id="'.$id.'">
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$id.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">'.$data->link.'</div>
                    </td>
                    <td class="vertical-center">
                        <div class="pt-10 pb-10">
                            <img src="images/slideshow/'.$data->hinhanh.'" alt="" width="300px">
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
                $imageName = 'slide'.(count(SLIDESHOW::all()) + 1).'.png';
            } else {
                $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
                $imageName = 'slide'.(count(SLIDESHOW::all()) + 1).'.jpg';
            }

            // lưu hình
            $this->IndexController->saveImage('images/slideshow/'.$imageName, $base64);

            $data = [
                'link' => $request->link,
                'hinhanh' => $imageName,
            ];

            $create = SLIDESHOW::create($data);

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
            $oldData = SLIDESHOW::find($id);

            $imageName = $oldData->hinhanh;

            // nếu có thay đổi hình ảnh
            if($request->hinhanh){
                // xóa hình cũ
                unlink('images/slideshow/' . $oldData->hinhanh);

                // định dạng hình
                $imageFormat = $this->IndexController->getImageFormat($request->hinhanh);
                if($imageFormat == 'png'){
                    $base64 = str_replace('data:image/png;base64,', '', $request->hinhanh);
                    $imageName = 'slide'.(count(SLIDESHOW::all()) + 1).'.png';
                } else {
                    $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
                    $imageName = 'slide'.(count(SLIDESHOW::all()) + 1).'.jpg';
                }

                // lưu hình
                $this->IndexController->saveImage('images/slideshow/'.$imageName, $base64);
            }
            
            $data = [
                'link' => $request->link,
                'hinhanh' => $imageName,
            ];

            SLIDESHOW::where('id', $id)->update($data);

            $html = $this->bindElement($id);

            return $html;
        }
    }

    public function destroy($id)
    {
        $slideshow = SLIDESHOW::find($id);
        // xóa hình
        unlink('images/slideshow/' . $slideshow->hinhanh);
        SLIDESHOW::destroy($id);
    }

    public function AjaxGetslideshow(Request $request)
    {
        if($request->ajax()){
            $slide = SLIDESHOW::find($request->id);
            $slide->hinhanh .= '?'.time();
            return $slide;
        }
    }
}
