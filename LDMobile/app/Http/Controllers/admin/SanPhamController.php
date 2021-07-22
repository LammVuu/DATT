<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\SANPHAM;
use App\Models\MAUSP;
use App\Models\KHUYENMAI;

class SanPhamController extends Controller
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
        // danh sách sản phẩm theo dung lượng
        $lst_product = SANPHAM::limit(10)->get();

        foreach($lst_product as $i => $key){
            // khuyến mãi
            $lst_product[$i]->khuyenmai = KHUYENMAI::find($key->id_km)->chietkhau;

            // trạng thái mausp
            $lst_product[$i]->trangthaimausp = MAUSP::find($key->id_msp)->trangthai;
        }

        // danh sách file cấu hình
        $lst_specifications = scandir('json');
        foreach($lst_specifications as $i => $key){
            if($key == '.' || $key == '..'){
                unset($lst_specifications[$i]);
            }
        }

        // danh sách ram hiện có
        $lst_ram = SANPHAM::select('ram')->distinct()->get();

        // danh sách dung lượng hiện có
        $lst_capacity = SANPHAM::select('dungluong')->distinct()->get();

        $data = [
            'lst_product' => $lst_product,
            'lst_model' => MAUSP::select('id', 'tenmau')->get(),
            'lst_promotion' => KHUYENMAI::select('id', 'chietkhau')->get(),
            'lst_specifications' => $lst_specifications,
            'lst_ram' => $lst_ram,
            'lst_capacity' => $lst_capacity,
        ];

        return view($this->admin."san-pham")->with($data);
    }
    
    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'tensp' => $request->tensp,
                'id_msp' => $request->id_msp,
                'hinhanh' => strtolower(str_replace(' ', '_', $request->tensp).'_'.$this->IndexController->unaccent($request->mausac).'.jpg'),
                'mausac' => $request->mausac,
                'ram' => $request->ram,
                'dungluong' => $request->dungluong,
                'gia' => $request->gia,
                'id_km' => $request->id_km,
                'cauhinh' => '',
                'trangthai' => 1,
            ];

            if($request->cauhinhName != 'create'){
                $data['cauhinh'] = $request->cauhinhName;
            }
            // tạo mới file thông số
            else {
                $capacity = strtolower(str_replace(' ', '', $request->dungluong));
                $data['cauhinh'] = strtolower(str_replace(' ', '_', $request->tensp).'_'.$capacity) . '.json';
            }

            // sản phẩm đã tồn tại
            $exists = SANPHAM::where('tensp', $data['tensp'])
                            ->where('id_msp', $data['id_msp'])
                            ->where('mausac', $data['mausac'])
                            ->where('ram', $data['ram'])
                            ->where('dungluong', $data['dungluong'])
                            ->where('gia', $data['gia'])
                            ->where('id_km', $data['id_km'])
                            ->where('cauhinh', $data['cauhinh'])
                            ->where('trangthai', $data['trangthai'])
                            ->first();
            if($exists){
                return 'exists';
            }

            if($request->cauhinhName == 'create'){
                $url = 'json/' . $data['cauhinh'];
                $json = json_encode($request->cauhinh);
                file_put_contents($url, $json);
            }

            // lưu hình ảnh
            $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
            $image = base64_decode($base64);

            $imageName = $data['hinhanh'];
            $urlImage = 'images/phone/' . $imageName;
            file_put_contents($urlImage, $image);

            $create = SANPHAM::create($data);

            // trạng thái mẫu sp
            $modelStatus = MAUSP::find($create->id_msp)->trangthai;

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tensp'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['mausac'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ram'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['dungluong'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.number_format($data['gia'], 0, '', '.').'<sup>đ</sup></div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.(KHUYENMAI::find($data['id_km'])->chietkhau*100).'%'.'</div>
                        </td>
                        <td class="vertical-center">
                            <div data-id="'.$create->id.'" class="trangthai pt-10 pb-10">'.($data['trangthai'] == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" data-name="'.$data['tensp'].' '.$data['dungluong'].' - '.$data['ram'].' Ram - '.$data['mausac'].'" class="delete-btn">
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
                'tensp' => $request->tensp,
                'id_msp' => $request->id_msp,
                'mausac' => $request->mausac,
                'ram' => $request->ram,
                'dungluong' => $request->dungluong,
                'gia' => $request->gia,
                'id_km' => $request->id_km,
                'cauhinh' => $request->cauhinhName,
                'trangthai' => $request->trangthai,
            ];

            $oldData = SANPHAM::find($id);

            // cập nhật file thông số
            $url = 'json/' . $data['cauhinh'];
            $json = json_encode($request->cauhinh);
            file_put_contents($url, $json);

            // cập nhật hình
            // có chỉnh sửa hình ảnh
            if($request->hinhanh){
                // xóa hình cũ
                unlink('images/phone/' . $oldData->hinhanh);

                $base64 = str_replace('data:image/jpeg;base64,', '', $request->hinhanh);
                $image = base64_decode($base64);

                $imageName = strtolower(str_replace(' ', '_', $request->tensp.' '.$this->IndexController->unaccent($request->mausac)).'.jpg');
                $data['hinhanh'] = $imageName;
                $urlImage = 'images/phone/' . $imageName;
                file_put_contents($urlImage, $image);
            }

            SANPHAM::where('id', $id)->update($data);

            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['tensp'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['mausac'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ram'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['dungluong'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.number_format($data['gia'], 0, '', '.').'<sup>đ</sup></div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.(KHUYENMAI::find($data['id_km'])->chietkhau*100).'%'.'</div>
                        </td>
                        <td class="vertical-center">
                            <div data-id="'.$id.'" class="trangthai pt-10 pb-10">'.($data['trangthai'] == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                ($data['trangthai'] == 1 ? '
                                <div data-id="'.$id.'" data-name="'.$data['tensp'].' '.$data['dungluong'].' - '.$data['ram'].' Ram - '.$data['mausac'].'" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </div>' : '
                                <div data-id="'.$id.'" data-name="'.$data['tensp'].' '.$data['dungluong'].' - '.$data['ram'].' Ram - '.$data['mausac'].'" class="undelete-btn">
                                    <i class="fas fa-trash-undo"></i>
                                </div>
                                ').'
                            </div>
                        </td>
                    </tr>';

            return $html;
        }
    }

    public function destroy($id)
    {
        SANPHAM::where('id', $id)->update(['trangthai' => 0]);
    }

    public function AjaxGetSanPham(Request $request)
    {
        if($request->ajax()){
            $product = SANPHAM::find($request->id);
            $product->hinhanh .= '?'.time();
            $specifications = $this->IndexController->getSpecifications($request->id);

            // các màu sắc sản phẩm
            $lst_color = [];
            foreach(SANPHAM::where('id_msp', $product['id_msp'])->where('dungluong', $product['dungluong'])->where('ram', $product['ram'])->get() as $i => $key){
                $data = [
                    'id' => $key['id'],
                    'hinhanh' => $key['hinhanh'].'?'.time().$i,
                ];

                array_push($lst_color, $data);
            }

            return [
                'product' => $product,
                'lst_color' => $lst_color,
                'specifications' => $specifications,
            ];
        }
    }

    public function AjaxGetPromotionIDByModelID(Request $request)
    {
        if($request->ajax()){
            return SANPHAM::where('id_msp', $request->id)->first();
        }
    }

    public function AjaxRestore(Request $request)
    {
        if($request->ajax()){
            if(MAUSP::find(SANPHAM::find($request->id)->id_msp)->trangthai == 0){
                return 'false';
            }
            SANPHAM::where('id', $request->id)->update(['trangthai' => 1]);
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(SANPHAM::limit(10)->get() as $key){
                    $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                    
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ram.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$promotion.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>
                                        ').'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            }

            foreach(SANPHAM::all() as $key){
                $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                $data = strtolower($this->IndexController->unaccent($key->id.$key->tensp.$key->mausac.$key->ram.$key->dungluong.$key->gia.$promotion.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh')));
                
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ram.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$promotion.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>
                                        ').'
                                    </div>
                                </td>
                            </tr>';
                }
            }
            return $html;
        }
    }

    public function AjaxGetModelStatusFalse(Request $request)
    {
        if($request->ajax()){
            $lst_result = [];
            foreach(MAUSP::all() as $key){
                if($key->trangthai == 0){
                    array_push($lst_result, $key->id);
                }
            }

            return $lst_result;
        }
    }

    public function AjaxFilterSort(Request $request)
    {
        if($request->ajax()){
            $arrFilterSort = $request->arrFilterSort;
            $lst_temp = [];
            $lst_result = [];
            $html = '';

            // danh sách sản phẩm tìm kiếm
            $lst_productSearch = [];
            if($request->search != ''){
                $keyword = $this->IndexController->unaccent($request->search);
                $lst_productSearch = $this->search($keyword);
            }

            // không có lọc, có sắp xếp
            if(!key_exists('filter', $arrFilterSort)){
                $sort = $arrFilterSort['sort'];

                // ko có tìm kiếm
                if(empty($lst_productSearch)){
                    if($sort == 'id-asc' || $sort == ''){
                        foreach(SANPHAM::get() as $key){
                            $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                            
                            $html .= '<tr data-id="'.$key->id.'">
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->id.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->ram.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$promotion.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                        </td>
                                        {{-- nút --}}
                                        <td class="vertical-center w-10">
                                            <div class="d-flex justify-content-start">
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                                ($key->trangthai == 1 ? '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                    <i class="fas fa-trash-undo"></i>
                                                </div>
                                                ').'
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    } else if($sort == 'id-desc'){
                        foreach(SANPHAM::orderBy('id', 'desc')->get() as $key){
                            $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                            
                            $html .= '<tr data-id="'.$key->id.'">
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->id.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->ram.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$promotion.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                        </td>
                                        {{-- nút --}}
                                        <td class="vertical-center w-10">
                                            <div class="d-flex justify-content-start">
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                                ($key->trangthai == 1 ? '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                    <i class="fas fa-trash-undo"></i>
                                                </div>
                                                ').'
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    } else if($sort == 'price-asc'){
                        foreach(SANPHAM::orderBy('gia')->get() as $key){
                            $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                            
                            $html .= '<tr data-id="'.$key->id.'">
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->id.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->ram.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$promotion.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                        </td>
                                        {{-- nút --}}
                                        <td class="vertical-center w-10">
                                            <div class="d-flex justify-content-start">
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                                ($key->trangthai == 1 ? '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                    <i class="fas fa-trash-undo"></i>
                                                </div>
                                                ').'
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    } else if($sort == 'price-desc'){
                        foreach(SANPHAM::orderBy('gia', 'desc')->get() as $key){
                            $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                            
                            $html .= '<tr data-id="'.$key->id.'">
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->id.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->ram.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                        </td>
                                        <td class="vertical-center">
                                            <div class="pt-10 pb-10">'.$promotion.'</div>
                                        </td>
                                        <td class="vertical-center">
                                            <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                        </td>
                                        {{-- nút --}}
                                        <td class="vertical-center w-10">
                                            <div class="d-flex justify-content-start">
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                                <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                                ($key->trangthai == 1 ? '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </div>' : '
                                                <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                    <i class="fas fa-trash-undo"></i>
                                                </div>
                                                ').'
                                            </div>
                                        </td>
                                    </tr>';
                        }
                    } else {
                        foreach(KHUYENMAI::orderBy('chietkhau', 'desc')->select('id')->get() as $promotion){
                            foreach(SANPHAM::where('id_km', $promotion->id)->get() as $key){
                                $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                                
                                $html .= '<tr data-id="'.$key->id.'">
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->id.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->ram.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$promotion.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                            </td>
                                            {{-- nút --}}
                                            <td class="vertical-center w-10">
                                                <div class="d-flex justify-content-start">
                                                    <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                                    <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                                    ($key->trangthai == 1 ? '
                                                    <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </div>' : '
                                                    <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                        <i class="fas fa-trash-undo"></i>
                                                    </div>
                                                    ').'
                                                </div>
                                            </td>
                                        </tr>';
                            }
                        }
                    }
                } else {
                    if($sort == 'id-asc' || $sort == ''){
                        $lst_productSearch = $this->sortID($lst_productSearch);
                    } else if($sort == 'id-desc'){
                        $lst_productSearch = $this->sortID($lst_productSearch, 'desc');
                    } else if($sort == 'price-asc'){
                        $lst_productSearch = $this->sortPrice($lst_productSearch);
                    } else if($sort == 'price-desc'){
                        $lst_productSearch = $this->sortPrice($lst_productSearch, 'desc');
                    } else {
                        $lst_productSearch = $this->sortDiscount($lst_productSearch, 'desc');
                    }

                    foreach($lst_productSearch as $key){
                        $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                        
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->ram.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$promotion.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-10">
                                        <div class="d-flex justify-content-start">
                                            <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                            <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                            ($key->trangthai == 1 ? '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                <i class="fas fa-trash-undo"></i>
                                            </div>
                                            ').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                }
                return $html;
            }

            $arrFilter = $arrFilterSort['filter'];

            // lọc trên danh sách đã tìm kiếm
            if(!empty($lst_productSearch)){
                if(array_key_first($arrFilter) == 'ram'){
                    foreach($arrFilter['ram'] as $ram){
                        foreach($lst_productSearch as $key){
                            if($key->ram == $ram){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'capacity'){
                    foreach($arrFilter['capacity'] as $capacity){
                        foreach($lst_productSearch as $key){
                            if($key->dungluong == $capacity){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'status'){
                    foreach($arrFilter['status'] as $status){
                        foreach($lst_productSearch as $key){
                            if($key->trangthai == $status){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                }
            } else{
                if(array_key_first($arrFilter) == 'ram'){
                    foreach($arrFilter['ram'] as $ram){
                        foreach(SANPHAM::where('ram', $ram)->get() as $key){
                            array_push($lst_temp, $key);
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'capacity'){
                    foreach($arrFilter['capacity'] as $capacity){
                        foreach(SANPHAM::where('dungluong', $capacity)->get() as $key){
                            array_push($lst_temp, $key);
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'status'){
                    foreach($arrFilter['status'] as $status){
                        foreach(SANPHAM::where('trangthai', $status)->get() as $key){
                            array_push($lst_temp, $key);
                        }
                    }
                }
            }

            if(count($arrFilter) == 1){
                // không có sắp xếp
                if(!$arrFilterSort['sort']){
                    foreach($lst_temp as $key){
                        $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                        
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->ram.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$promotion.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-10">
                                        <div class="d-flex justify-content-start">
                                            <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                            <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                            ($key->trangthai == 1 ? '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                <i class="fas fa-trash-undo"></i>
                                            </div>
                                            ').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                } else {
                    $sort = $arrFilterSort['sort'];
                    if($sort == 'id-asc'){
                        $lst_temp = $this->sortID($lst_temp);
                    } elseif($sort == 'id-desc'){
                        $lst_temp = $this->sortID($lst_temp, 'desc');
                    } elseif($sort == 'price-asc'){
                        $lst_temp = $this->sortPrice($lst_temp);
                    } elseif($sort == 'price-desc'){
                        $lst_temp = $this->sortPrice($lst_temp, 'desc');
                    } else {
                        $lst_temp = $this->sortDiscount($lst_temp, 'desc');
                    }

                    foreach($lst_temp as $key){
                        $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                        
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->ram.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$promotion.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-10">
                                        <div class="d-flex justify-content-start">
                                            <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                            <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                            ($key->trangthai == 1 ? '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                                <i class="fas fa-trash-undo"></i>
                                            </div>
                                            ').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                }
                return $html;
            }

            array_push($lst_result, $lst_temp);

            for($i = 1; $i < count($arrFilter); $i++){
                $lst_product = [];

                if(array_keys($arrFilter)[$i] == 'ram'){
                    foreach($arrFilter['ram'] as $ram){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->ram == $ram){
                                array_push($lst_product, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_product);
                } elseif(array_keys($arrFilter)[$i] == 'capacity'){
                    foreach($arrFilter['capacity'] as $capacity){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->dungluong == $capacity){
                                array_push($lst_product, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_product);
                } else {
                    foreach($arrFilter['status'] as $status){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->trangthai == $status){
                                array_push($lst_product, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_product);
                }
            }

            $lst_result = $lst_result[count($lst_result) - 1];

            // không có sắp xếp
            if(!$arrFilterSort['sort']){
                foreach($lst_result as $key){
                    $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                    
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ram.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$promotion.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>
                                        ').'
                                    </div>
                                </td>
                            </tr>';
                }
            } else {
                $sort = $arrFilterSort['sort'];
                if($sort == 'id-asc'){
                    $lst_result = $this->sortID($lst_result);
                } elseif($sort == 'id-desc'){
                    $lst_result = $this->sortID($lst_result, 'desc');
                } elseif($sort == 'price-asc'){
                    $lst_result = $this->sortPrice($lst_result);
                } elseif($sort == 'price-desc'){
                    $lst_result = $this->sortPrice($lst_result, 'desc');
                } else {
                    $lst_result = $this->sortDiscount($lst_result, 'desc');
                }

                foreach($lst_result as $key){
                    $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
                    
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tensp.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->mausac.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ram.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->dungluong.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.number_format($key->gia, 0, '', '.').'<sup>đ</sup></div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$promotion.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.
                                        ($key->trangthai == 1 ? '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>
                                        ').'
                                    </div>
                                </td>
                            </tr>';
                }
            }

            return $html;
        }
    }

    // tìm kiếm
    public function search($keyword)
    {
        $lst_product = [];

        foreach(SANPHAM::all() as $key){
            $promotion = (KHUYENMAI::find($key->id_km)->chietkhau * 100) . '%';
            $data = strtolower($this->IndexController->unaccent($key->id.$key->tensp.$key->mausac.$key->ram.$key->dungluong.$key->gia.$promotion.($key->trangthai == 1 ? 'Kinh doanh' : 'Ngừng kinh doanh')));
            
            if(str_contains($data, $keyword)){
                array_push($lst_product, $key);
            }
        }

        return $lst_product;
    }

    // sắp xếp id
    public function sortID($lst, $sort = 'asc')
    {
        if($sort == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->id >= $lst[$j]->id){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->id <= $lst[$j]->id){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        }
        return $lst;
    }

    // sắp xếp giá
    public function sortPrice($lst, $sort = 'asc')
    {
        if($sort == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->gia >= $lst[$j]->gia){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->gia <= $lst[$j]->gia){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        }
        return $lst;
    }

    // sắp xếp khuyếnn mãi
    public function sortDiscount($lst, $sort = 'asc')
    {
        foreach($lst as $i => $key){
            $lst[$i]->khuyenmai = KHUYENMAI::find($key->id_km)->chietkhau;
        }

        if($sort == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->khuyenmai >= $lst[$j]->khuyenmai){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->khuyenmai <= $lst[$j]->khuyenmai){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        }
        return $lst;
    }
}
