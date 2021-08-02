<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\user\IndexController;
use Illuminate\Http\Request;
use App\Events\sendNotification;

use App\Models\DONHANG;
use App\Models\CTDH;
use App\Models\TAIKHOAN;
use App\Models\TAIKHOAN_DIACHI;
use App\Models\TAIKHOAN_VOUCHER;
use App\Models\CHINHANH;
use App\Models\VOUCHER;
use App\Models\MAUSP;
use App\Models\SANPHAM;
use App\Models\KHUYENMAI;
use App\Models\IMEI;
use App\Models\BAOHANH;
use App\Models\THONGBAO;
use App\Models\KHO;
use App\Models\TINHTHANH;

class DonHangController extends Controller
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
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    public function index()
    {
        $lst_order = DONHANG::orderBy('id', 'desc')->limit(10)->get();
        foreach($lst_order as $i => $key){
            $lst_order[$i]->taikhoan = TAIKHOAN::find($lst_order[$i]->id_tk);
        }

        $data = [
            'lst_order' => $lst_order,
        ];

        return view($this->admin."don-hang")->with($data);
    }

    public function AjaxGetDonHang(Request $request)
    {
        if($request->ajax()){
            // đơn hàng
            $order = DONHANG::find($request->id);

            // chi tiết đơn hàng
            $order->ctdh = DONHANG::find($request->id)->ctdh;
            // tạm tính
            $order->ctdh->tamtinh = 0;
            foreach($order->ctdh as $i => $key){
                $order->ctdh[$i]->sanpham = SANPHAM::find($key->pivot->id_sp);
                $order->ctdh->tamtinh += $key->pivot->thanhtien;
            }

            // tài khoản
            $order->taikhoan = TAIKHOAN::find($order->id_tk);

            // giao hàng tận nơi
            if($order->hinhthuc == 'Giao hàng tận nơi'){
                // địa chỉ tài khoản
                $order->taikhoan_diachi = TAIKHOAN_DIACHI::find($order->id_tk_dc);
            }
            // nhận tại cửa hàng
            else {
                // chi nhánh
                $order->chinhanh = CHINHANH::find($order->id_cn);
            }

            // voucher
            if($order->id_vc){
                $order->voucher = VOUCHER::find($order->id_vc);
            }

            $html = '<div>
                        <div class="row mb-40">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-end mb-5">
                                    <div>Trạng thái đơn hàng:</div>'.(
                                    $order->trangthaidonhang != 'Đã hủy' ?
                                    '<div class="ml-10 fz-20 fw-600 success-color">'.$order->trangthaidonhang.'</div>' :
                                    '<div class="ml-10 fz-20 fw-600 warning-color">'.$order->trangthaidonhang.'</div>').'
                                </div>
                                <div class="d-flex">
                                    <div>Ngày mua:</div>
                                    <div class="ml-10 fw-600">'.$order->thoigian.'</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-end">
                                    <div class="account-badge">
                                        <img src="'.($order->taikhoan->htdn == 'nomal' ? 'images/user/'.$order->taikhoan->anhdaidien : $order->taikhoan->anhdaidien).'" width="40px" class="circle-img">
                                        <div class="ml-10 mr-10 black">'.$order->taikhoan->hoten.'</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-50">
                            <div class="col-lg-6">'.(
                                $order->hinhthuc == 'Giao hàng tận nơi' ? '
                                <div class="mb-5 fw-600">Thông tin giao hàng</div>
                                <div id="receiveMethod">
                                    <div class="d-flex flex-column box-shadow p-20">
                                        <div class="fw-600 text-uppercase mb-5">'.$order->taikhoan_diachi->hoten.'</div>
                                        <div class="d-flex fz-14 mb-5">
                                            <div class="gray-1">Địa chỉ:</div>
                                            <div class="ml-5 black">'.$order->taikhoan_diachi->diachi.', '.$order->taikhoan_diachi->phuongxa.', '.$order->taikhoan_diachi->quanhuyen.', '.$order->taikhoan_diachi->tinhthanh.'</div>
                                        </div>
                                        <div class="d-flex fz-14">
                                            <div class="gray-1">SĐT:</div>
                                            <div class="ml-5 black">'.$order->taikhoan_diachi->sdt.'</div>
                                        </div>
                                    </div>
                                </div>' : '
                                <div class="mb-5 fw-600">Nhận tại cửa hàng</div>
                                <div id="receiveMethod">
                                    <div class="d-flex flex-column box-shadow p-20">
                                        <div class="d-flex fz-14 mb-5">
                                            <div class="gray-1">Địa chỉ:</div>
                                            <div class="ml-5 black">'.$order->chinhanh->diachi.'</div>
                                        </div>
                                        <div class="d-flex fz-14">
                                            <div class="gray-1">SĐT:</div>
                                            <div class="ml-5 black">'.$order->chinhanh->sdt.'</div>
                                        </div>
                                    </div>
                                </div>').'
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-5 fw-600">Phương thức thanh toán</div>
                                <div id="paymentMethod">
                                    <div class="box-shadow p-20 h-100 black">'.$order->pttt.'</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table box-shadow">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Giảm giá</th>
                                            <th>Tạm tính</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    foreach($order->ctdh as $key){
                                        $html .= '
                                        <tr>
                                            <td class="vertical-center">
                                                <div class="d-flex pt-10 pb-10">
                                                    <img src="images/phone/'.$key->sanpham->hinhanh.'" alt="" width="100px">
                                                    <div class="ml-5">
                                                        <div class="fw-600">'.$key->sanpham->tensp.'</div>
                                                        <div>Ram: '.$key->sanpham->ram.'</div>
                                                        <div>Dung lượng: '.$key->sanpham->dungluong.'</div>
                                                        <div>Màu sắc: '.$key->sanpham->mausac.'</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">
                                                    '.number_format($key->sanpham->gia, 0, '', '.').'<sup>đ</sup>
                                                </div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.$key->pivot->sl.'</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">-'.$key->pivot->giamgia*100 .'%</div>
                                            </td>
                                            <td class="vertical-center">
                                                <div class="pt-10 pb-10">'.number_format($key->pivot->thanhtien, 0, '', '.').'<sup>đ</sup></div>
                                            </td>
                                        </tr>';
                                    }
                                    if($order->id_vc){
                                        $html .= '
                                        <tr>
                                            <td colspan="5" class="p-0">
                                                <div class="d-flex">
                                                    <div class="w-20 bg-gray-4 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-ticket-alt mr-10"></i>Mã giảm giá
                                                    </div>
                                                    
                                                    <div class="w-30 p-10">
                                                        <div class="account-voucher">
                                                            <div class="voucher-left-small w-20 p-30">
                                                                <div class="voucher-left-small-content fz-18">-'.$order->voucher->chietkhau*100 .'%</div>
                                                            </div>
                                                            <div class="voucher-right-small w-80 d-flex align-items-center justify-content-between p-10">
                                                                <b>'.$order->voucher->code.'</b>
                                                                <div class="relative promotion-info-icon">
                                                                    <i class="fal fa-info-circle main-color-text fz-20"></i>
                                                                    <div class="voucher-content box-shadow p-20">
                                                                        <table class="table">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="w-40">Mã</td>
                                                                                    <td><b>'.$order->voucher->code.'</b></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="w-40">Nội dung</td>
                                                                                    <td>'.$order->voucher->noidung.'</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2" class="w-40">
                                                                                        <div class="d-flex flex-column">
                                                                                            <span>Điều kiện:</span>'.(
                                                                                            $order->voucher->dieukien != 0 ? '
                                                                                                <ul class="mt-10">
                                                                                                    <li>Áp dụng cho đơn hàng từ '.number_format($order->voucher->dieukien, 0, '', '.').'<sup>đ</sup></li>
                                                                                                </ul>' : '').'
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="w-40">Hạn sử dụng</td>
                                                                                    <td>'.$order->voucher->ngayketthuc.'</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                    $html .= '
                                        <tr>
                                            <td class="vertical-center">
                                                <div class="pt-20 pb-20 pl-10">
                                                    <div class="d-flex justify-content-between mb-10">
                                                        <div>Tạm tính:</div>
                                                        <div>'.number_format($order->ctdh->tamtinh, 0, '', '.').'<sup>đ</sup></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-10">
                                                        <div>Mã giảm giá:</div>
                                                        <div class="main-color-text">'.($order->id_vc ? '-'.$order->voucher->chietkhau*100 .'%' : '0').'</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>Tổng tiền:</div>
                                                        <div class="fz-20 fw-600 red">'.number_format($order->tongtien, 0, '', '.').'<sup>đ</sup></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>';

            return $html;
        }
    }

    public function destroy($id)
    {
        // hủy đơn hàng
        DONHANG::where('id', $id)->update(['trangthaidonhang' => 'Đã hủy']);

        $order = DONHANG::where('id', $id)->first();

        // hoàn lại voucher
        if($order->id_vc){
            TAIKHOAN_VOUCHER::create([
                'id_vc' => $order->id_vc,
                'id_tk' => $order->id_tk,
            ]);
        }

        // hoàn lại số lượng kho
        foreach(CTDH::where('id_dh', $id)->get() as $detail){
            // kho tại chi nhánh
            if($order->id_cn){
                // số lượng sp trong kho hiện tại
                $qtyInStock = KHO::where('id_cn', $order->id_cn)->where('id_sp', $detail->id_sp)->first()->slton;
                // số lượng sản phẩm mua
                $qtyBuy = $detail->sl;
                // trả lại số lượng kho
                $qtyInStock += $qtyBuy;
                // cập nhật kho
                KHO::where('id_cn', $order->id_cn)->where('id_sp', $detail->id_sp)->update(['slton' => $qtyInStock]);
            }
            // kho theo khu vực người đặt
            else {
                // tỉnh thành của người dùng
                $province = TAIKHOAN_DIACHI::find($order->id_tk_dc)->tinhthanh;
                // id_cn theo tỉnh thành
                $id_cn = CHINHANH::where('id_tt', TINHTHANH::where('tentt', $province)->first()->id)->first()->id;
                // số lượng sp trong kho tại chi nhánh
                $qtyInStock = KHO::where('id_cn', $id_cn)->where('id_sp', $detail->id_sp)->first()->slton;
                // số lượng sản phẩm mua
                $qtyBuy = $detail->sl;
                // trả lại số lượng kho
                $qtyInStock += $qtyBuy;
                // cập nhật kho
                KHO::where('id_cn', $id_cn)->where('id_sp', $detail->id_sp)->update(['slton' => $qtyInStock]);
            }
        }
    }

    public function AjaxOrderConfirmation(Request $request)
    {
        if($request->ajax()){
            DONHANG::where('id', $request->id)->update(['trangthaidonhang' => 'Đã xác nhận']);
            $order = DONHANG::find($request->id);
            // gửi thông báo
            $data = [
                'id_tk' => $order->id_tk,
                'tieude' => 'Đơn đã xác nhận',
                'noidung' => "Đã xác nhận đơn hàng <b>#$order->id</b> của bạn.",
                'thoigian' => date('d/m/Y h:i'),
                'trangthaithongbao' => 0,
            ];

            THONGBAO::create($data);

            $notification = [
                'user' => TAIKHOAN::find($order->id_tk),
                'type' => 'order',
                'notification' => '',
            ];

            $notification['notification'] = '<div id="alert-toast" class="alert-toast-2">
                                                <span class="close-toast-btn"><i class="fal fa-times-circle"></i></span>
                                                <div class="d-flex align-items-center">
                                                    <div class="alert-toast-icon white fz-36"><i class="fas fa-truck"></i></div>
                                                    <div class="alert-toast-2-content">
                                                        <div class="mb-10">Đã xác nhận đơn hàng <b>#'.$order->id.'</b> của bạn.</div>
                                                        <div class="d-flex justify-content-end align-items-center mr-5">
                                                            <div class="dot-green mr-5"></div>
                                                            <div class="fst-italic fw-lighter fz-12">Bây giờ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div>';

            event(new sendNotification($notification));
        }
    }

    public function AjaxSuccessfulOrder(Request $request)
    {
        if($request->ajax()){
            // cập nhật đơn hàng thành công
            DONHANG::where('id', $request->id)->update(['trangthaidonhang' => 'Thành công']);

            // đơn hàng
            $order = DONHANG::find($request->id);

            // kích hoạt imei & thêm bảo hành
            foreach($order->ctdh as $key){
                // kích hoạt và thêm theo theo số lượng sản phẩm trong đơn hàng
                for($i = 0; $i < $key->pivot->sl; $i++){
                    $imei = IMEI::where('id_sp', $key->pivot->id_sp)->where('trangthai', 0)->first();

                    // kích hoạt iemi
                    IMEI::where('id', $imei->id)->update(['trangthai' => 1]);

                    // ngày giao hàng thành công
                    $start = date('d/m/Y');

                    // timestamp ngày giao hàng thành công
                    $startTimestamp = strtotime(date('d-m-Y'));

                    // tháng bảo hành
                    $month = explode(' ', MAUSP::find(SANPHAM::find($imei->id_sp)->id_msp)->baohanh)[0];
                    // ngày kết thúc
                    $end = date('d/m/Y', strtotime('+'.$month.' months', $startTimestamp));

                    $data = [
                        'id_imei' => $imei->id,
                        'imei' => $imei->imei,
                        'ngaymua' => $start,
                        'ngayketthuc' => $end,
                    ];

                    BAOHANH::create($data);
                }
            }

            // gửi thông báo thành công & tặng voucher
            $voucher = VOUCHER::where('code', 'GIAM10')->first();

            THONGBAO::create([
                'id_tk' => $order->id_tk,
                'tieude' => 'Giao hàng thành công',
                'noidung' => "Kiện hàng của đơn hàng <b>#$order->id</b> đã giao thành công đến bạn.",
                'thoigian' => date('d/m/Y h:i'),
                'trangthaithongbao' => 0,
            ]);
            THONGBAO::create([
                'id_tk' => $order->id_tk,
                'tieude' => 'Mã giảm giá',
                'noidung' => 'Cảm ơn bạn đã mua hàng tại LDMobile, chúng tôi xin gửi tặng bạn mã giảm giá giảm '.$voucher->chietkhau*100 .'% cho đơn hàng từ '.number_format($voucher->dieukien, 0, '', '.').'<sup>đ</sup>. Áp dụng đến hết ngày '.$voucher->ngayketthuc.'.',
                'thoigian' => date('d/m/Y h:i'),
                'trangthaithongbao' => 0,
            ]);

            // tặng voucher
            $userVoucher = TAIKHOAN_VOUCHER::where('id_vc', $voucher->id)->where('id_tk', $order->id_tk)->first();
            // nếu chưa có voucher
            if(!$userVoucher){
                TAIKHOAN_VOUCHER::create([
                    'id_vc' => $voucher->id,
                    'id_tk' => $order->id_tk,
                    'sl' => 1,
                ]);
            }
            // cập nhật số lượng voucher
            else {
                $qty = $userVoucher->sl;
                $userVoucher->sl = ++$qty;
                $userVoucher->save();
            }
            
            // giảm số lượng voucher
            $qty = $voucher->sl;
            $voucher->sl = --$qty;
            $voucher->save();

            $notification = [
                'user' => TAIKHOAN::find($order->id_tk),
                'type' => 'order',
                'notification' => '',
            ];

            $notification['notification'] = '<div id="alert-toast" class="alert-toast-2">
                                                <span class="close-toast-btn"><i class="fal fa-times-circle"></i></span>
                                                <div class="d-flex align-items-center">
                                                    <div class="alert-toast-icon white fz-36"><i class="fas fa-truck"></i></div>
                                                    <div class="alert-toast-2-content">
                                                        <div class="mb-10" style="max-width: 350px">
                                                            Đơn hàng <b>#'.$order->id.'</b> đã được giao thành công. Cảm ơn bạn đã mua hàng tại LDMobile, chúng tôi xin gửi tặng bạn mã giảm giá... <a href="taikhoan/thongbao">Chi tiết</a>
                                                        </div>
                                                        <div class="d-flex justify-content-end align-items-center mr-5">
                                                            <div class="dot-green mr-5"></div>
                                                            <div class="fst-italic fw-lighter fz-12">Bây giờ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div>';

            event(new sendNotification($notification));
        }
    }

    public function AjaxSearch(Request $request)
    {
        if($request->ajax()){
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            if($keyword == ''){
                foreach(DONHANG::orderBy('id', 'desc')->limit(10)->get() as $key){
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
                                            <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                    </div>
                                </td>
                            </tr>';
                }
                return $html;
            }

            foreach(DONHANG::orderBy('id', 'desc')->get() as $key){
                $fullname = TAIKHOAN::find($key->id_tk)->hoten;
                $data = strtolower($this->IndexController->unaccent($key->id.$key->thoigian.$fullname.$key->pttt.$key->hinhthuc.$key->tongtien.$key->trangthaidonhang));
                if(str_contains($data, $keyword)){
                    $html .= '<tr data-id="'.$key->id.'">
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->id.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->thoigian.'</div>
                                </td>
                                <td class="vertical-center">
                                    <div class="pt-10 pb-10">'.$key->taikhoan->hoten.'</div>
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
            }
            return $html;
        }
    }

    public function AjaxFilterSort(Request $request)
    {
        if($request->ajax()){
            $arrFilterSort = $request->arrFilterSort;
            $lst_temp = [];
            $lst_result = [];
            $lst_search = [];
            $keyword = $this->IndexController->unaccent($request->keyword);
            $html = '';

            // danh sách tìm kiếm
            if($keyword){
                $lst_search = $this->search($keyword);
            }

            // gỡ tất cả bỏ lọc | không có bộ lọc & có sắp xếp
            if(!key_exists('filter', $arrFilterSort)){
                $sort = $arrFilterSort['sort'];

                // Không có tìm kiếm
                if(empty($lst_search)){
                    if($sort == '' || $sort == 'date-desc'){
                        foreach(DONHANG::orderBy('id', 'desc')->limit(10)->get() as $key){
                            array_push($lst_result, $key);
                        }
                    } elseif($sort == 'date-asc'){
                        foreach(DONHANG::orderBy('id')->limit(10)->get() as $key){
                            array_push($lst_result, $key);
                        }
                    } elseif($sort == 'total-asc'){
                        foreach(DONHANG::orderBy('tongtien')->limit(10)->get() as $key){
                            array_push($lst_result, $key);
                        }
                    } elseif($sort == 'total-desc'){
                        foreach(DONHANG::orderBy('tongtien', 'desc')->limit(10)->get() as $key){
                            array_push($lst_result, $key);
                        }
                    }

                    foreach($lst_result as $key){
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
                                                <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                } else {
                    if($sort == '' || $sort == 'date-desc'){
                        $lst_result = $this->sortDate($lst_search, 'desc');
                    } elseif($sort == 'date-asc'){
                        $lst_result = $this->sortDate($lst_search);
                    } elseif($sort == 'total-asc'){
                        $lst_result = $this->sortTotal($lst_search);
                    } elseif($sort == 'total-desc'){
                        $lst_result = $this->sortTotal($lst_search, 'desc');
                    }

                    foreach($lst_result as $key){
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
                                                <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                }

                return $html;
            }

            $arrFilter = $arrFilterSort['filter'];

            // lọc tiêu chí đầu tiên trên danh sách tìm kiếm
            if(!empty($lst_search)){
                if(array_key_first($arrFilter) == 'paymentMethod'){
                    foreach($arrFilter['paymentMethod'] as $paymentMethod){
                        foreach($lst_search as $key){
                            if($key->pttt == $paymentMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'receiveMethod'){
                    foreach($arrFilter['receiveMethod'] as $receiveMethod){
                        foreach($lst_search as $key){
                            if($key->hinhthuc == $receiveMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'status'){
                    foreach($arrFilter['status'] as $status){
                        foreach($lst_search as $key){
                            if($key->trangthaidonhang == $status){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                }
            } else {
                if(array_key_first($arrFilter) == 'paymentMethod'){
                    foreach($arrFilter['paymentMethod'] as $paymentMethod){
                        foreach(DONHANG::orderBy('id', 'desc')->get() as $key){
                            if($key->pttt == $paymentMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'receiveMethod'){
                    foreach($arrFilter['receiveMethod'] as $receiveMethod){
                        foreach(DONHANG::orderBy('id', 'desc')->get() as $key){
                            if($key->hinhthuc == $receiveMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                } elseif(array_key_first($arrFilter) == 'status'){
                    foreach($arrFilter['status'] as $status){
                        foreach(DONHANG::orderBy('id', 'desc')->get() as $key){
                            if($key->trangthaidonhang == $status){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                }
            }

            // chỉ có 1 tiêu chí lọc
            if(count($arrFilter) == 1){
                // Không có sắp xếp
                if(!$arrFilterSort['sort']){
                    foreach($lst_temp as $key){
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
                                                <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                } else {
                    $sort = $arrFilterSort['sort'];
                    if($sort == 'date-desc'){
                        $lst_result = $this->sortDate($lst_temp, 'desc');
                    } elseif($sort == 'date-asc'){
                        $lst_result = $this->sortDate($lst_temp);
                    } elseif($sort == 'total-asc'){
                        $lst_result = $this->sortTotal($lst_temp);
                    } elseif($sort == 'total-desc'){
                        $lst_result = $this->sortTotal($lst_temp, 'desc');
                    }

                    foreach($lst_result as $key){
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
                                                <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                        </div>
                                    </td>
                                </tr>';
                    }
                }

                return $html;
            }

            // tiếp tục lọc các tiêu chí khác
            array_push($lst_result, $lst_temp);

            for($i = 1; $i < count($arrFilter); $i++){
                $lst_temp = [];

                if(array_keys($arrFilter)[$i] == 'paymentMethod'){
                    foreach($arrFilter['paymentMethod'] as $paymentMethod){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->pttt == $paymentMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_temp);
                } elseif(array_keys($arrFilter)[$i] == 'receiveMethod'){
                    foreach($arrFilter['receiveMethod'] as $receiveMethod){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->hinhthuc == $receiveMethod){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_temp);
                } elseif(array_keys($arrFilter)[$i] == 'status'){
                    foreach($arrFilter['status'] as $status){
                        foreach($lst_result[$i - 1] as $key){
                            if($key->trangthaidonhang == $status){
                                array_push($lst_temp, $key);
                            }
                        }
                    }
                    array_push($lst_result, $lst_temp);
                }
            }

            // lấy danh sách kết quả cuối cùng
            $lst_result = $lst_result[count($lst_result) - 1];

            // không có sắp xếp
            if(!$arrFilterSort['sort']){
                foreach($lst_result as $key){
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
                                            <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                    </div>
                                </td>
                            </tr>';
                }
            } else {
                $sort = $arrFilterSort['sort'];
                if($sort == 'date-desc'){
                    $lst_result = $this->sortDate($lst_temp, 'desc');
                } elseif($sort == 'date-asc'){
                    $lst_result = $this->sortDate($lst_temp);
                } elseif($sort == 'total-asc'){
                    $lst_result = $this->sortTotal($lst_temp);
                } elseif($sort == 'total-desc'){
                    $lst_result = $this->sortTotal($lst_temp, 'desc');
                }

                foreach($lst_result as $key){
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
                                            <div data-id="'.$key->id.'" class="delete-btn"><i class="fas fa-trash"></i></div>' : '').'
                                    </div>
                                </td>
                            </tr>';
                }
            }

            return $html;
        }
    }

    public function search($keyword)
    {
        $lst_result = [];
        foreach(DONHANG::orderBy('id', 'desc')->get() as $key){
            $fullname = TAIKHOAN::find($key->id_tk)->hoten;
            $data = strtolower($this->IndexController->unaccent($key->id.$key->thoigian.$fullname.$key->pttt.$key->hinhthuc.$key->tongtien.$key->trangthaidonhang));
            if(str_contains($data, $keyword)){
                array_push($lst_result, $key);
            }
        }
        return $lst_result;
    }

    // sắp xếp ngày
    public function sortDate($lst, $sort = 'asc')
    {
        if($sort == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    $timestamp_i = strtotime(str_replace('/', '-', $lst[$i]->thoigian));
                    $timestamp_j = strtotime(str_replace('/', '-', $lst[$j]->thoigian));
                    if($timestamp_i >= $timestamp_j){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    $timestamp_i = strtotime(str_replace('/', '-', $lst[$i]->thoigian));
                    $timestamp_j = strtotime(str_replace('/', '-', $lst[$j]->thoigian));
                    if($timestamp_i <= $timestamp_j){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        }
        return $lst;
    }

    // sắp xếp tổng tiền
    public function sortTotal($lst, $sort = 'asc')
    {
        if($sort == 'asc'){
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->tongtien >= $lst[$j]->tongtien){
                        $temp = $lst[$i];
                        $lst[$i] = $lst[$j];
                        $lst[$j] = $temp;
                    }
                }
            }
        } else {
            for($i = 0; $i < count($lst) - 1; $i++){
                for($j = $i + 1; $j < count($lst); $j++){
                    if($lst[$i]->tongtien <= $lst[$j]->tongtien){
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
