<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\Voucher;
use App\Models\TAIKHOAN_VOUCHER;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->admin = 'admin/content/';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->IndexController = new IndexController;
    }

    public function index()
    {
        $lst_voucher = VOUCHER::limit(10)->get();
        foreach($lst_voucher as $i => $key){
            // trạng thái
            $dateEnd = strtotime(str_replace('/', '-', $key->ngayketthuc));
            $currentDate = strtotime(date('d-m-Y'));
            $lst_voucher[$i]->trangthai = $dateEnd >= $currentDate ? 1 : 0;
        }

        $data = [
            'lst_voucher' => $lst_voucher,
        ];

        return view($this->admin.'voucher')->with($data);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [
                'code' => $request->code,
                'noidung' => $request->noidung,
                'chietkhau' => $request->chietkhau,
                'dieukien' => $request->dieukien ? $request->dieukien : 0,
                'ngaybatdau' => date('d/m/Y', strtotime($request->ngaybatdau)),
                'ngayketthuc' => date('d/m/Y', strtotime($request->ngayketthuc)),
                'sl' => $request->sl,
            ];

            if(VOUCHER::where('code', $data['code'])->first()){
                return 'exists';
            }

            $create = VOUCHER::create($data);

            // trạng thái
            $dateEnd = strtotime(str_replace('/', '-', $data['ngayketthuc']));
            $currentDate = strtotime(date('d-m-Y'));
            $status = $dateEnd >= $currentDate ? 'Hoạt động' : 'Hết hạn';

            $html = '<tr data-id="'.$create->id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$create->id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['code'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['chietkhau']*100 .'%</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ngaybatdau'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ngayketthuc'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sl'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$status.'</div>
                        </td>
                        {{-- nút --}}
                        <td class="vertical-center w-10">
                            <div class="d-flex justify-content-start">
                                <div data-id="'.$create->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                <div data-id="'.$create->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                <div data-id="'.$create->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>
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
                'code' => $request->code,
                'noidung' => $request->noidung,
                'chietkhau' => $request->chietkhau,
                'dieukien' => $request->dieukien ? $request->dieukien : 0,
                'ngaybatdau' => date('d/m/Y', strtotime($request->ngaybatdau)),
                'ngayketthuc' => date('d/m/Y', strtotime($request->ngayketthuc)),
                'sl' => $request->sl,
            ];
    
            $oldData = VOUCHER::find($id);
    
            // voucher đã tồn tại
            if($oldData->code != $data['code']){
                if(VOUCHER::where('code', $data['code'])->first()){
                    return 'exists';
                }
            }
    
            VOUCHER::where('id', $id)->update($data);

            // trạng thái
            $dateEnd = strtotime(str_replace('/', '-', $data['ngayketthuc']));
            $currentDate = strtotime(date('d-m-Y'));
            $status = $dateEnd >= $currentDate ? 'Hoạt động' : 'Hết hạn';
    
            $html = '<tr data-id="'.$id.'">
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$id.'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['code'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['chietkhau']*100 .'%</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ngaybatdau'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['ngayketthuc'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$data['sl'].'</div>
                        </td>
                        <td class="vertical-center">
                            <div class="pt-10 pb-10">'.$status.'</div>
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
    }

    public function destroy($id)
    {
        // xóa voucher của người dùng
        TAIKHOAN_VOUCHER::where('id_vc', $id)->delete();
        // xóa voucher
        VOUCHER::destroy($id);
    }

    public function AjaxGetVoucher(Request $request)
    {
        if($request->ajax()){
            $voucher = VOUCHER::find($request->id);
            $voucher->ngaybatdau = date('Y-m-d', strtotime(str_replace('/', '-', $voucher->ngaybatdau)));
            $voucher->ngayketthuc = date('Y-m-d', strtotime(str_replace('/', '-', $voucher->ngayketthuc)));

            return $voucher;
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(VOUCHER::limit(10)->get() as $key){
                    $dateEnd = strtotime(str_replace('/', '-', $key->ngayketthuc));
                    $currentDate = strtotime(date('d-m-Y'));
                    $status = $dateEnd >= $currentDate ? 'Hoạt động' : 'Hết hạn';

                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->code.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->chietkhau*100 .'%</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngaybatdau.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngayketthuc.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sl.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$status.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            }

            foreach(VOUCHER::all() as $key){
                $dateEnd = strtotime(str_replace('/', '-', $key->ngayketthuc));
                $currentDate = strtotime(date('d-m-Y'));
                $status = $dateEnd >= $currentDate ? 'Hoạt động' : 'Hết hạn';

                $data = strtolower($this->IndexController->unaccent($key->id.$key->code.$key->chietkhau*100 .'%'.$key->ngaybatdau.$key->ngayketthuc.$key->sl.$status));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->code.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->chietkhau*100 .'%</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngaybatdau.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngayketthuc.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sl.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$status.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>
                                    </div>
                                </td>
                            </tr>';
                }
            }
            return $html;
        }
    }
}
