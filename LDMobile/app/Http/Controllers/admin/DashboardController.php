<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;

use App\Models\MAUSP;
use App\Models\SANPHAM;
use App\Models\NHACUNGCAP;
use App\Models\KHUYENMAI;
use App\Models\SLIDESHOW_CTMSP;
use App\Models\HINHANH;
use App\Models\TINHTHANH;
use App\Models\CHINHANH;
use App\Models\KHO;
use App\Models\VOUCHER;
use App\Models\DONHANG;
use App\Models\TAIKHOAN;
use App\Models\BAOHANH;
use App\Models\IMEI;
use App\Models\CTDH;
use App\Models\LUOTTRUYCAP;
use App\Models\DANHGIASP;
use Carbon\Carbon;
use DB;
use Math;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
        $this->IndexController = new IndexController;
    }

    public function Index()
    {
        //lay ngay hien tai va ngay dau tien cua thang
        $currentMonth =  Carbon::now('Asia/Ho_Chi_Minh')->format('m/Y');
        $currentDate =  Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $dateFirstOfMonth = Carbon::now()->year;
        $dateFirstOfYear = Carbon::now()->year."-01-01";
        if(Carbon::now()->month<10){
            $dateFirstOfMonth .="-0".Carbon::now()->month.'-01';
        }else $dateFirstOfMonth .="-".Carbon::now()->month.'-01';

        //thong ke don hang va doanh thu
        $bills= DONHANG::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->get();
        $totalBillInMonth = count($bills);
        $totalMoneyInMonth = 0;
        foreach($bills as $bill){
            if($bill->trangthaidonhang == 'Thành công'){
                $totalMoneyInMonth += $bill->tongtien;
            }
        }

        //thong ke thanh vien
        $accounts= TAIKHOAN::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->get();
        $totalAccountInMonth = count($accounts);
        $bestSellers = $this->getTopProductBestSellers($currentDate, $dateFirstOfMonth);

        /*==========================================================
                        trạng thái đơn hàng trong tháng
        ============================================================*/
        // tháng/năm hiện tại
        $currentMonthYear = date('m/Y');
        // năm hiện tại
        $currentYear = date('Y');

        $lst_orderStatus = $this->getOrderStatus($currentMonthYear);
        
        /*==========================================================
                        lượt truy cập web trong tháng
        ============================================================*/

        $accessTimesOnWeb = $this->getAccessTimesOnWeb($currentMonthYear);

          /*==========================================================
                        lượt truy cập app trong tháng
        ============================================================*/

        $accessTimesOnApp = $this->getAccessAppInMonth($currentDate, $dateFirstOfMonth);
        
        /*==========================================================
                        lượt truy cập app trong tháng
        ============================================================*/
        $totalReviewInMonth = $this->getTotalReviewInMonth($currentDate, $dateFirstOfMonth);

        /*==========================================================
                            Doanh thu trong năm
        ============================================================*/

        $salesOfYear = $this->getSalesOfYear($currentYear);
        // $this->IndexController->print($salesOfYear); return false;
        $suppplierOfYear = $this->getSupplierOfYear($currentDate, $dateFirstOfYear);
        $data = [
            'totalBillInMonth' => $totalBillInMonth,
            'totalMoneyInMonth' => $totalMoneyInMonth,
            'totalAccountInMonth' => $totalAccountInMonth,
            'currentMonth' => $currentMonth,
            'bestSellers' => $bestSellers,
            'lst_orderStatus' => $lst_orderStatus,
            'accessTimesOnWeb' => $accessTimesOnWeb,
            'accessTimesOnApp' => $accessTimesOnApp,
            'totalReviewInMonth' => $totalReviewInMonth,
            'salesOfYear' => $salesOfYear,
            'suppplierOfYear' => $suppplierOfYear,
        ];

        return view($this->admin.'index')->with($data);
    }

    /*============================================================================================================
                                                        Ajax
    ==============================================================================================================*/

    public function AjaxLoadMore(Request $request)
    {
        if($request->ajax()){
            $url = $request->url;
            $row = $request->row;
            $html = '';

            if($url == 'mausanpham'){
                $data = MAUSP::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->tenmau.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.NHACUNGCAP::find($key->id_ncc)->tenncc.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->baohanh.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->diachibaohanh.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == '1' ? 'Kinh doanh' : 'Ngừng kinh doanh').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.(
                                        $key->trangthai != 0 ? '
                                            <div data-id="'.$key->id.'" class="delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </div>' : '
                                            <div data-id="'.$key->id.'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>' ).'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            } elseif($url == 'khuyenmai'){
                $data = KHUYENMAI::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    // trạng thái
                    $status = strtotime(str_replace('/', '-', $key->ngayketthuc)) >= strtotime(date('d-m-Y')) ? 'Hoạt động' : 'Hết hạn';

                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center w-5">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center w-15">
                                    <div class="pt-10 pb-10">'.$data->tenkm.'</div>
                                </td>
                                <td class="vertical-center w-24">
                                    <div class="pt-10 pb-10">'.$key->noidung.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.($key->chietkhau*100).'%</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div class="pt-10 pb-10">'.$key->ngaybatdau.'</div>
                                </td>
                                <td class="vertival-center w-11">
                                    <div class="pt-10 pb-10">'.$key->ngayketthuc.'</div>
                                </td>
                                <td class="vertical-center w-10">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.$status.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-15">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-khuyenmai-btn info-btn"><i class="fas fa-info"></i></div>
                                        <div data-id="'.$key->id.'" class="edit-khuyenmai-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" data-object="khuyenmai" class="delete-khuyenmai-btn delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            } elseif($url == 'sanpham'){
                $sort = $request->sort;

                if($sort == '' || $sort == 'id-asc'){
                    $data = SANPHAM::offset($row)->limit(10)->get();
                } elseif($sort == 'id-desc'){
                    $data = SANPHAM::orderBy('id', 'desc')->offset($row)->limit(10)->get();
                } elseif($sort == 'price-asc'){
                    $data = SANPHAM::orderBy('gia')->offset($row)->limit(10)->get();
                } elseif($sort == 'price-desc'){
                    $data = SANPHAM::orderBy('gia', 'desc')->offset($row)->limit(10)->get();
                }

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
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
                                    <div class="pt-10 pb-10">'.(KHUYENMAI::find($key->id_km)->chietkhau*100).'%'.'</div>
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
                                        <div data-id="'.$key->id.'" data-name="'.$key->tensp.' '.$key->dungluong.' - '.$key->ram.' Ram - '.$key->mausac.'"    class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>').'
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            } elseif($url == 'nhacungcap'){
                $data = NHACUNGCAP::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$ket->tenncc.'</div>
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
            } elseif($url == 'slideshow-msp'){
                $data = MAUSP::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
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
            } elseif($url == 'hinhanh'){
                $data = MAUSP::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
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
            } elseif($url == 'kho'){
                $data = KHO::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $product = SANPHAM::find($key->id_sp);
                    $branchAddress = CHINHANH::find($key->id_cn)->diachi;

                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$branchAddress.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="d-flex pt-10 pb-10">
                                        <img src="images/phone/'.$product->hinhanh.'" alt="" width="80px">
                                        <div class="ml-5 fz-14">
                                            <div class="d-flex align-items-center fw-600">'.
                                                $product->tensp. '<i class="fas fa-circle ml-5 mr-5 fz-5"></i>'.$product->mausac.'
                                            </div>
                                            <div>Ram: '.$product->ram.'</div>
                                            <div>Dung lượng: '.$product->dungluong.'</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->slton.' Chiếc</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>
                                        <div data-id="'.$key->id.'" data-branch="'.$branchAddress.'" class="delete-btn"><i class="fas fa-trash"></i></div>    
                                    </div>
                                </td>
                            </tr>';
                }

                return $html;
            } elseif($url == 'chinhanh'){
                $data = CHINHANH::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->diachi.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->sdt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.TINHTHANH::find($key->id_tt)->tentt.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Hoạt động' : 'Ngừng hoạt động').'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-10">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="edit-btn"><i class="fas fa-pen"></i></div>'.(
                                        $key->trangthai != 0 ?
                                        '<div data-id="'.$key->id.'" class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </div>' : '
                                        <div data-id="'.$key->id.'" class="undelete-btn">
                                            <i class="fas fa-trash-undo"></i>
                                        </div>').'
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            } elseif($url == 'tinhthanh'){
                $data = TINHTHANH::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
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
            } elseif($url == 'voucher'){
                $data = VOUCHER::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
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
            } elseif($url == 'donhang'){
                $sort = $request->sort;

                // không có sắp xếp
                if($sort == ''){
                    $data = DONHANG::offset($row)->limit(10)->get();

                    if(count($data) == 0){
                        return 'done';
                    }

                    foreach($data as $key){
                        $fullname = TAIKHOAN::find($key->id_tk)->hoten;
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->thoigian.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$fullname.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->pttt.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->hinhthuc.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.number_format($key->tongtien, 0, '', '.').'<sup>đ</sup></div>
                                    </td>
                                    <td class="vertical-center">
                                        <div data-id="'.$key->id.'" class="trangthaidonhang pt-10 pb-10">'.$key->trangthaidonhang.'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-5">
                                        <div class="d-flex justify-content-start">'.
                                        ($key->trangthaidonhang != 'Thành công' && $key->trangthaidonhang != 'Đã hủy' ?
                                                ($key->trangthaidonhang == 'Đã tiếp nhận' ? '
                                                    <div data-id="'.$key->id.'" class="confirm-btn">
                                                        <i class="fas fa-file-check"></i>
                                                    </div>' :'
                                                    <div data-id="'.$key->id.'" class="success-btn">
                                                        <i class="fas fa-box-check"></i>
                                                    </div>' ) : '') .'
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>'.
                                        ($key->trangthaidonhang != 'Đã hủy' ? '
                                            <div data-id="{{$key->id}}" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }

                    return $html;
                } else {
                    if($sort == 'date-desc'){
                        $data = DONHANG::orderBy('id', 'desc')->offset($row)->limit(10)->get();
                    } elseif($sort == 'date-asc'){
                        $data = DONHANG::orderBy('id')->offset($row)->limit(10)->get();
                    } elseif($sort == 'total-asc'){
                        $data = DONHANG::orderBy('tongtien')->offset($row)->limit(10)->get();
                    } elseif($sort == 'total-desc'){
                        $data = DONHANG::orderBy('tongtien', 'desc')->offset($row)->limit(10)->get();
                    }

                    if(count($data) == 0){
                        return 'done';
                    }

                    foreach($data as $key){
                        $fullname = TAIKHOAN::find($key->id_tk)->hoten;
                        $html .= '<tr data-id="'.$key->id.'">
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->id.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->thoigian.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$fullname.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->pttt.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.$key->hinhthuc.'</div>
                                    </td>
                                    <td class="vertical-center">
                                        <div class="pt-10 pb-10">'.number_format($key->tongtien, 0, '', '.').'<sup>đ</sup></div>
                                    </td>
                                    <td class="vertical-center">
                                        <div data-id="'.$key->id.'" class="trangthaidonhang pt-10 pb-10">'.$key->trangthaidonhang.'</div>
                                    </td>
                                    {{-- nút --}}
                                    <td class="vertical-center w-5">
                                        <div class="d-flex justify-content-start">'.
                                        ($key->trangthaidonhang != 'Thành công' && $key->trangthaidonhang != 'Đã hủy' ?
                                                ($key->trangthaidonhang == 'Đã tiếp nhận' ? '
                                                    <div data-id="'.$key->id.'" class="confirm-btn">
                                                        <i class="fas fa-file-check"></i>
                                                    </div>' :'
                                                    <div data-id="'.$key->id.'" class="success-btn">
                                                        <i class="fas fa-box-check"></i>
                                                    </div>' ) : '') .'
                                                <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>'.
                                        ($key->trangthaidonhang != 'Đã hủy' ? '
                                            <div data-id="{{$key->id}}" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }

                    return $html;
                }
            } elseif($url == 'baohanh'){
                $data = BAOHANH::offset($row)->limit(10)->get();

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->imei.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngaymua.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->ngayketthuc.'</div>
                                </td>
                                {{-- nút --}}
                                <td class="vertical-center w-5">
                                    <div class="d-flex justify-content-start">
                                        <div data-id="'.$key->id.'" class="info-btn"><i class="fas fa-info"></i></div>
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            } elseif($url == 'imei'){
                if(!$request->keyword){
                    $data = IMEI::offset($row)->limit(10)->get();
                } else {
                    $data = [];
                    $keyword = $this->IndexController->unaccent($request->keyword);
                    $count = 0;
                    $i = 0;
                    foreach(IMEI::all() as $key){
                        $product = SANPHAM::find($key->id_sp);
                        $str = strtolower($this->IndexController->unaccent($key->id.$product->tensp.$product->mausac.$product->ram.$product->dungluong.$key->imei.($key->trangthai == 1 ? 'Đã kích hoạt' : 'Chưa kích hoạt')));
                        if(str_contains($str, $keyword)){
                            // bỏ qua số dòng đã cuộn
                            if($i != $row){
                                $i++;
                                continue;
                            } else {
                                // lấy tiếp tục 10 bản ghi
                                if($count == 10){
                                    break;
                                }
                                array_push($data, $key);
                                $count++;
                            }
                        }
                    }
                }

                if(count($data) == 0){
                    return 'done';
                }

                foreach($data as $key){
                    $product = SANPHAM::find($key->id_sp);
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="d-flex pt-10 pb-10">
                                        <img src="images/phone/'.$product->hinhanh.'" alt="" width="70px">
                                        <div class="ml-10">
                                            <div class="d-flex align-items-center fw-600">'.
                                                $product->tensp.'
                                                <i class="fas fa-circle fz-5 ml-5 mr-5"></i>'.
                                                $product->mausac.'
                                            </div>
                                            <div>Ram: '.$product->ram.'</div>
                                            <div>Dung lượng: '.$product->dungluong.'</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->imei.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div data-id="'.$key->id.'" class="trangthai pt-10 pb-10">'.($key->trangthai == 1 ? 'Đã kích hoạt' : 'Chưa kích hoạt').'</div>
                                </td>
                            </tr>';
                }

                return $html;
            }
        }
    }

    public function mainStatic(){
       
    }

    public function getTopProductBestSellers($currentDate, $dateFirstOfMonth){
        $listTop5IDs = array();
        $listIDBills = array();
        $bills = DONHANG::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->where('trangthaidonhang','LIKE', '%'.'Thành công'.'%')->get();
        foreach($bills as $bill){
            array_push($listIDBills, $bill->id);
        }
        $listIDs = CTDH::select('id_sp')
                        ->groupBy('id_sp')
                        ->orderByRaw('COUNT(sl) DESC')
                        ->whereIn('id_dh', $listIDBills)
                        ->take(5)
                        ->get();
        $detailBill = CTDH::whereIn('id_dh', $listIDBills)->get();

        foreach($listIDs as $id){
            array_push($listTop5IDs, $id->id_sp);
        }
        $bestSellers = SANPHAM::whereIn('id', $listTop5IDs)->get();
        foreach($bestSellers as $product){
            $product->total = 0;
            foreach($detailBill as $detail){
                if($product->id == $detail->id_sp){
                    $product->total += $detail->sl;
                }  
            }
        }
        $result = $bestSellers->sortByDesc(function($pro) {
            return $pro->total;
        });
        return $result;   
    }

    // lượt truy cập trên web trong tháng
    public function getAccessTimesOnWeb($currentMonthYear)
    {
        $count = 0;
        
        foreach(LUOTTRUYCAP::where('nentang', 'web')->get() as $key){
            // array [ngày, tháng, năm] lượt truy cập
            $accessTimesDate = explode('/', explode(' ', $key->thoigian)[0]);
            // tháng/năm của lượt truy cập
            $accessTimesMonthYear = $accessTimesDate[1] . '/' . $accessTimesDate[2];

            if($accessTimesMonthYear == $currentMonthYear){
                $count++;
            }
        }

        return $count;
    }

    // trạng thái đơn hàng trong tháng
    public function getOrderStatus($currentMonthYear)
    {
        // danh sách trạng thái
        $lst_orderStatus = [
            'total' => 0,
            'received' => 0,
            'confirmed' => 0,
            'success' => 0,
            'cancelled' => 0,
        ];
        foreach(DONHANG::all() as $order){
            // array [ngày, tháng, năm] đơn hàng
            $orderDate = explode('/', explode(' ', $order->thoigian)[0]);
            // tháng/năm của đơn hàng
            $orderMonthYear = $orderDate[1] . '/' . $orderDate[2];

            if($orderMonthYear == $currentMonthYear){
                // thêm vào danh sách trạng thái
                if($order->trangthaidonhang == 'Đã tiếp nhận'){
                    $lst_orderStatus['received']++;
                } elseif($order->trangthaidonhang == 'Đã xác nhận'){
                    $lst_orderStatus['confirmed']++;
                } elseif($order->trangthaidonhang == 'Thành công'){
                    $lst_orderStatus['success']++;
                } else {
                    $lst_orderStatus['cancelled']++;
                }

                $lst_orderStatus['total']++;
            }
        }

        return $lst_orderStatus;
    }
    public function getAccessAppInMonth($currentDate, $dateFirstOfMonth){
        $apps= LUOTTRUYCAP::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->where('nentang', 'app')->get();
        $result = count($apps);
        return $result;
    }
    public function getTotalReviewInMonth($currentDate, $dateFirstOfMonth){
        $reviews= DANHGIASP::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->get();
        $result = count($reviews);
        return $result;
    }

    // thống kê doanh thu trong năm
    public function getSalesOfYear($year)
    {
        $sales = [];
        $str = '';

        foreach(DONHANG::all() as $order){
            // năm của đơn hàng
            $orderYear = explode('/', explode(' ', $order->thoigian)[0])[2];
            // tháng của đơn hàng
            $orderMonth = explode('/', explode(' ', $order->thoigian)[0])[1];

            if($orderYear == $year){
                switch($orderMonth){
                    case '01':
                        if(!key_exists('1', $sales)){
                            $sales['1'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['1'] += $order->tongtien;
                        }
                        break;
                    case '02':
                        if(!key_exists('2', $sales)){
                            $sales['2'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['2'] += $order->tongtien;
                        }
                        break;
                    case '03':
                        if(!key_exists('3', $sales)){
                            $sales['3'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['3'] += $order->tongtien;
                        }
                        break;
                    case '04':
                        if(!key_exists('4', $sales)){
                            $sales['4'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['4'] += $order->tongtien;
                        }
                        break;
                    case '05':
                        if(!key_exists('5', $sales)){
                            $sales['5'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['5'] += $order->tongtien;
                        }
                        break;
                    case '06':
                        if(!key_exists('6', $sales)){
                            $sales['6'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['6'] += $order->tongtien;
                        }
                        break;
                    case '07':
                        if(!key_exists('7', $sales)){
                            $sales['7'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['7'] += $order->tongtien;
                        }
                        break;
                    case '08':
                        if(!key_exists('8', $sales)){
                            $sales['8'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['8'] += $order->tongtien;
                        }
                        break;
                    case '09':
                        if(!key_exists('9', $sales)){
                            $sales['9'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['9'] += $order->tongtien;
                        }
                        break;
                    case '10':
                        if(!key_exists('10', $sales)){
                            $sales['10'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['10'] += $order->tongtien;
                        }
                        break;
                    case '11':
                        if(!key_exists('11', $sales)){
                            $sales['11'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['11'] += $order->tongtien;
                        }
                        break;
                    case '12':
                        if(!key_exists('12', $sales)){
                            $sales['12'] = 0;
                        }
                        if($order->trangthaidonhang == 'Thành công'){
                            $sales['12'] += $order->tongtien;
                        }
                        break;
                }
            }
        }

        // không có dữ liệu
        if(count($sales) == 0 && $year != date('Y')){
            return '';
        }

        // năm hiện tại
        if($year == date('Y')){
            // từ tháng 1 -> hiện tại, thêm doanh thu = 0 vào tháng k có doanh thu
            for($i = 1; $i <= intval(date('m')); $i++){
                if(!key_exists($i, $sales)){
                    $sales[$i] = 0;
                }
                
            }
        }
        // năm cũ: thêm vào trước và sau tháng tìm thấy, doanh thu = 0
        else {
            for($i = 1; $i < 13; $i++){
                if(!key_exists($i, $sales)){
                    $sales[$i] = 0;
                }
            }
        }

        // sắp xếp mảng theo thứ tự tháng tăng dần
        $arr = [];
        ksort($sales);
        foreach($sales as $i => $key){
            $arr[$i] = $key;
        }

        // chuyển sang chuỗi
        foreach($arr as $key){
            $str .= $key .'-';
        }

        // cắt bỏ dấu '-' cuối chuỗi
        $str = substr_replace($str, '', strlen($str) - 1);

        return $str;
    }

    public function AjaxGetSalesOfYear(Request $request)
    {
        if($request->ajax()){
            return $this->getSalesOfYear($request->year);
        }
    }
    public function getSupplierOfYear($currentDate, $dateFirstOfYear){
        $total = 0;
        $listResult = array();
        $listIDBillYears = array();
        $listSupplier= array();
        $bills = DONHANG::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $dateFirstOfYear)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->where('trangthaidonhang','LIKE', '%'.'Thành công'.'%')->get();
        foreach($bills as $bill){
            array_push($listIDBillYears, $bill->id);
        }
        $detailBills = CTDH::whereIn('id_dh', $listIDBillYears)->get();
        foreach($detailBills as $bill){
            $idCate = SANPHAM::select('id_msp')
                            ->where('id', $bill->id_sp)
                            ->get();
            $cateProduct = MAUSP::find($idCate);
            if($bill->sl==2){
                array_push($listSupplier, $cateProduct[0]->id_ncc);
                array_push($listSupplier, $cateProduct[0]->id_ncc);
            }else array_push($listSupplier, $cateProduct[0]->id_ncc);
        }
        $results = array_count_values($listSupplier);
        foreach($results as $key => $id){
            $name = NHACUNGCAP::select('tenncc')
                            ->where('id', $key)
                            ->get();
            $total += $id;               
            $listResult[$name[0]->tenncc] = $id;
        }
     
        foreach($listResult as $key => $value){
            $num = ($value/$total)*100;

            $listResult[$key] = number_format($num, 1);
        }
        return $listResult;
    }
    public function AjaxGetSupplierOfYear(Request $request){
        if($request->ajax()){
            return $this->getSupplierOfYear($request->currentDate, $request->dateFirstOfYear);
        }
    }
}
