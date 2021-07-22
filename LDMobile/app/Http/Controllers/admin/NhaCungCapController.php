<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\NHACUNGCAP;
use App\Models\MAUSP;
use App\Models\SANPHAM;

class NhaCungCapController extends Controller
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
        // danh sách nhà cung cấp
        $lst_supplier = NHACUNGCAP::limit(10)->get();

        $data = [
            'lst_supplier' => $lst_supplier,
        ];

        return view($this->admin."nha-cung-cap")->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'tenncc' => $request->tenncc,
                'anhdaidien' => strtolower(str_replace(' ','-', $this->IndexController->unaccent($request->tenncc))).'-logo.jpg',
                'diachi' => $request->diachi,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'trangthai' => $request->trangthai,
            ];

            // đã tồn tại
            $exists = NHACUNGCAP::where('tenncc', $data['tenncc'])
                                ->where('anhdaidien', $data['anhdaidien'])
                                ->where('diachi', $data['diachi'])
                                ->where('sdt', $data['sdt'])
                                ->where('email', $data['email'])
                                ->where('trangthai', $data['trangthai'])
                                ->first();
            if($exists){
                return 'exists';
            }

            // lưu hình
            $base64 = str_replace('data:image/jpeg;base64,', '', $request->anhdaidien);
            $image = base64_decode($base64);

            $imageName = $data['anhdaidien'];
            $urlImage = 'images/logo/' . $imageName;
            file_put_contents($urlImage, $image);

            $create = NHACUNGCAP::create($data);

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tenncc'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">
                                <img src="images/logo/'.$data['anhdaidien'].'" alt="">
                            </div>
                        </td>
                        <td class="vertical-center w-25">
                            <div class="pt-10 pb-10">'.$data['diachi'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sdt'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'.$data['email'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div data-id="'.$create->id.'" class="trangthai pt-10 pb-10">Hoạt động</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-5">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" data-name="'.$data['tenncc'].'" class="delete-btn">
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

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            $data = [
                'tenncc' => $request->tenncc,
                'diachi' => $request->diachi,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'trangthai' => $request->trangthai,
            ];

            $oldData = NHACUNGCAP::find($id);

            // nếu có chỉnh sửa hình
            if($request->anhdaidien){
                $data['anhdaidien'] = strtolower(str_replace(' ','-', $this->IndexController->unaccent($request->tenncc))).'-logo.jpg';

                // xóa hình cũ
                unlink('images/logo/' . $oldData->anhdaidien);

                // lưu hình
                $base64 = str_replace('data:image/jpeg;base64,', '', $request->anhdaidien);
                $image = base64_decode($base64);

                $imageName = $data['anhdaidien'];
                $urlImage = 'images/logo/' . $imageName;
                file_put_contents($urlImage, $image);
            } else {
                $data['anhdaidien'] = $oldData->anhdaidien;
            }

            NHACUNGCAP::where('id', $id)->update($data);

            // cập nhật trạng thái mẫu sản phẩm
            $model = MAUSP::where('id_ncc', $id)->get();
            MAUSP::where('id_ncc', $id)->update(['trangthai' => $data['trangthai']]);

            // cập nhật sản phẩm
            foreach($model as $key){
                SANPHAM::where('id_msp', $key['id'])->update(['trangthai' => $data['trangthai']]);
            }

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tenncc'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">
                                <img src="images/logo/'.$data['anhdaidien'].'?'.time().'" alt="">
                            </div>
                        </td>
                        <td class="vertical-center w-25">
                            <div class="pt-10 pb-10">'.$data['diachi'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sdt'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div class="pt-10 pb-10">'.$data['email'].'</div>
                        </td>
                        <td class="vertical-center w-10">
                            <div data-id="'.$id.'" class="trangthai pt-10 pb-10">'.($data['trangthai'] == 1 ? 'Hoạt động' : 'Ngừng kinh doanh').'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-5">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                ($data['trangthai'] == 1 ? '
                                <div data-id="'.$id.'" data-name="'.$data['tenncc'].'" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>' : '
                                <div data-id="'.$id.'" data-name="'.$data['tenncc'].'" class="undelete-btn">
                                    <i class="fas fa-trash-undo"></i>
                                </div>') .'
                            </div>
                        </td>
                    </tr>';

            return $html;
        }
    }

    public function destroy($id)
    {
        // xóa nhà cung cấp
        NHACUNGCAP::where('id', $id)->update(['trangthai' => 0]);

        // xóa mẫu sản phẩm
        $model = MAUSP::where('id_ncc', $id)->get();
        MAUSP::where('id_ncc', $id)->update(['trangthai' => 0]);

        // xóa sản phẩm
        foreach($model as $key){
            SANPHAM::where('id_msp', $key['id'])->update(['trangthai' => 0]);
        }
    }

    public function AjaxRetore(Request $request)
    {
        if($request->ajax()){
            // khôi phục ncc
            NHACUNGCAP::where('id', $request->id)->update(['trangthai' => 1]);

            // khôi phục mẫu sp
            $model = MAUSP::where('id_ncc', $request->id)->get();
            MAUSP::where('id_ncc', $request->id)->update(['trangthai' => 1]);

            // khôi phục sản phẩm
            foreach($model as $key){
                SANPHAM::where('id_msp', $key['id'])->update(['trangthai' => 1]);
            }
        }
    }

    public function AjaxGetNCC(Request $request)
    {
        if($request->ajax()){
            return NHACUNGCAP::find($request->id);
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(NHACUNGCAP::all() as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tenncc.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">
                                        <img src="images/logo/'.$key->anhdaidien.'?'.time().'" alt="">
                                    </div>
                                </td>
                                <td class="vertical-center w-25">
                                    <div class="pt-10 pb-10">'.$key->diachi.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sdt.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->email.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-5">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tenncc.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tenncc.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>') .'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            }

            foreach(NHACUNGCAP::all() as $key){
                $data = strtolower($this->IndexController->unaccent($key->id.$key->tenncc.$key->diachi.$key->sdt.$key->email.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh')));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tenncc.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">
                                        <img src="images/logo/'.$key->anhdaidien.'?'.time().'" alt="">
                                    </div>
                                </td>
                                <td class="vertical-center w-25">
                                    <div class="pt-10 pb-10">'.$key->diachi.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sdt.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->email.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-5">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tenncc.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tenncc.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>') .'
                                    </div>
                                </td>
                            </tr>';
                }
            }

            return $html;
        }
    }
}
