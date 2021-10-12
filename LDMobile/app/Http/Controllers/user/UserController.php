<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\user\IndexController;
use Illuminate\Support\Facades\Cookie;
use App\Events\sendNotification;

use App\Models\TAIKHOAN;
use App\Models\TAIKHOAN_DIACHI;
use App\Models\THONGBAO;
use App\Models\LUOTTHICH;
use App\Models\SP_YEUTHICH;
use App\Models\DANHGIASP;
use App\Models\GIOHANG;
use App\Models\VOUCHER;
use App\Models\DONHANG;
use App\Models\TAIKHOAN_VOUCHER;
use App\Models\CTDG;
use App\Models\PHANHOI;
use App\Models\CTDH;
use App\Models\KHO;
use App\Models\CHINHANH;
use App\Models\TINHTHANH;
use App\Models\SANPHAM;
use App\Models\DONHANG_DIACHI;
use App\Http\Controllers\PushNotificationController;

class UserController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
        $this->IndexController = new IndexController;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    /*============================================================================================================
                                                        Auth
    ==============================================================================================================*/
    public function DangNhap(){
        if(Auth::check() || session('user')){
            return back()->with('toast_message', 'Bạn đã đăng nhập');
        }
        // $this->IndexController->print(Session::all()); return false;
        // url trước đó
        if(!Session::get('prev_url')){
            $prev_url = '/';
            if(Session::get('_previous')){
                $url = Session::get('_previous')['url'];
                $arrUrl = explode('/', $url);
                $page = $arrUrl[count($arrUrl) - 1];
                if($page != 'dangky' && $page != 'khoiphuctaikhoan'){
                    $prev_url = $url;
                }
            }
            Session::put('prev_url', $prev_url);
        }

        return view($this->user."dang-nhap");
    }

    public function DangKy(){
        if(Auth::check()){
            return back()->with('toast_message', 'Bạn đã đăng nhập');
        }
        return view($this->user."dang-ky");
    }

    public function KhoiPhucTaiKhoan()
    {
        // đang đăng nhập
        if(session('user')){
            return back();
        }

        return view($this->user."khoi-phuc-tai-khoan");
    }

    public function SignUp(Request $request)
    {
        $data = [
            'sdt' => $request->su_tel,
            'password' => Hash::make($request->su_pw),
            'hoten' => $request->su_fullname,
            'anhdaidien' => 'avatar-default.png',
            'loaitk' => 0,
            'htdn' => 'nomal',
            'thoigian' => date('d/m/Y'),
            'trangthai' => 1,
        ];

        $newUser = TAIKHOAN::create($data);

        return redirect('dangnhap')->with('success_message', 'Đăng ký tài khoản thành công!');
    }

    public function Login(Request $request)
    {
        $data = [
            'sdt' => $request->login_tel,
            'password' => $request->login_pw,
            'trangthai' => 1,
        ];
        
        if(Auth::attempt($data, $request->remember)){
            Session::regenerate();

            $user = TAIKHOAN::where('sdt', $data['sdt'])->first();
            session(['user' => $user]);

            // quay về url trước đó
            $prev_url = session('prev_url');
            if($prev_url){
                Session::forget('prev_url');
                return redirect($prev_url)->with('toast_message', 'Đăng nhập thành công');
            }

            return redirect('/')->with('toast_message', 'Đăng nhập thành công');
        }

        return back()->with('error_message', 'số điện thoại hoặc mật khẩu không chính xác');
    }

    public function RecoverAccount(Request $request)
    {
        $user = TAIKHOAN::where('sdt', $request->forget_tel)->first();
        $hashPW = Hash::make($request->forget_pw);
        $user->password = $hashPW;
        $user->save();

        return redirect('dangnhap')->with('success_message', 'Khôi phục tài khoản thành công!');
    }

    public function FacebookRedirect()
    {
        return Socialite::driver('facebook')
            ->scopes(['email', 'public_profile'])
            ->redirect();
    }

    public function FacebookCallback()
    {
        try{
            $user = Socialite::driver('facebook')->stateless()->user();

            if($user->email == ''){
                return redirect('dangnhap')->with('error_message', 'Cần quyền truy cập email của bạn để tiếp tục');
            }

            $exists = TAIKHOAN::where('email', $user->email)->first();

            // đã tồn tại
            if($exists){
                // đã đăng nhập bằng facebook
                if($exists->htdn == 'facebook'){
                    $exists->update([
                        'anhdaidien' => $user->avatar_original . "&access_token={$user->token}",
                        'user_social_token' => $user->token,
                        'login_status' => 1
                    ]);
                    
                    Auth::login($exists, true);
                    session(['user' => $exists]);
                    Cookie::queue('acccount_social_id', $exists->id, 60*24*30*12);

                    // quay về url trước đó
                    $prev_url = session('prev_url');
                    if($prev_url){
                        Session::forget('prev_url');
                        return redirect($prev_url)->with('toast_message', 'Đăng nhập thành công');
                    }

                    return redirect('/')->with('toast_message', 'Đăng nhập thành công');
                }

                return redirect('dangnhap')->with('error_message', 'Email này đã được sử dụng');
            } else {
                // tạo tài khoản mới
                $newUser = TAIKHOAN::create([
                    'email' => $user->email,
                    'hoten' => $user->name,
                    'anhdaidien' => $user->avatar_original . "&access_token={$user->token}",
                    'loaitk' => 0,
                    'htdn' => 'facebook',
                    'user_social_token' => $user->token,
                    'login_status' => 1,
                    'thoigian' => date('d/m/Y'),
                    'trangthai' => 1,
                ]);
                
                Auth::login($newUser, true);
                session(['user' => $newUser]);
                Cookie::queue('acccount_social_id', 'facebook_'.$newUser->id, 60*24*30*12);

                // quay về url trước đó
                $prev_url = session('prev_url');
                if($prev_url){
                    Session::forget('prev_url');
                    return redirect($prev_url)->with('toast_message', 'Đăng nhập thành công');
                }

                return redirect('/')->with('toast_message', 'Đăng nhập thành công');
            }
        } catch(Exception $e){
            return redirect('dangnhap')->with('error_message', 'Đã có lỗi xảy ra. Vui lòng thử lại');
            $this->IndexController->print($e->getMessage());
        }
    }

    public function GoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function GoogleCallback()
    {
        try {      
            $user = Socialite::driver('google')->user();
       
            $exists = TAIKHOAN::where('email', $user->email)->first();
       
            if($exists){
                if($exists->htdn == 'google'){
                    $exists->update(['login_status' => 1]);

                    Auth::login($exists, true);
                    session(['user' => $exists]);
                    Cookie::queue('acccount_social_id', $exists->id, 60*24*30*12);

                    // quay về url trước đó
                    $prev_url = session('prev_url');
                    if($prev_url){
                        Session::forget('prev_url');
                        return redirect($prev_url)->with('toast_message', 'Đăng nhập thành công');
                    }

                    return redirect('/')->with('toast_message', 'Đăng nhập thành công');
                }

                return redirect('dangnhap')->with('error_message', 'Email này đã được sử dụng');
            }

            $newUser = TAIKHOAN::create([
                'email' => $user->email,
                'hoten' => $user->name,
                'anhdaidien' => $user->avatar,
                'loaitk' => 0,
                'htdn' => 'google',
                'user_social_token' => $user->token,
                'login_status' => 1,
                'thoigian' => date('d/m/Y'),
                'trangthai' => 1,
            ]);

            Auth::login($newUser, true);
            Session::regenerate();
            session(['user' => $newUser]);
            Cookie::queue('acccount_social_id', $newUser->id, 60*24*30*12);

            // quay về url trước đó
            $prev_url = session('prev_url');
            if($prev_url){
                Session::forget('prev_url');
                return redirect($prev_url)->with('toast_message', 'Đăng nhập thành công');
            }
    
            return redirect('/')->with('toast_message', 'Đăng nhập thành công');
        } catch (Exception $e) {
            return back()->with('error_message', 'Đã có lỗi xảy ra. Vui lòng thử lại');
        }
    }

    public function LogOut(Response $response)
    {
        if(!session('user')){
            return back();
        }

        Auth::logout();
        if(session('user')->htdn != 'nomal'){
            TAIKHOAN::where('id', session('user')->id)->update(['login_status' => 0]);
            Cookie::queue(Cookie::forget('acccount_social_id'));
        }
        
        Session::flush();
        Session::put('visitor', '1');
        Session::flash('toast_message', 'Đã đăng xuất');
        return redirect('/');
    }

    public function AjaxPhoneNumberIsExists(Request $request)
    {
        if($request->ajax()){
            return TAIKHOAN::where('sdt', $request->sdt)->first() ? 'exists' : 'valid';
        }
    }

    /*============================================================================================================
                                                        Page
    ==============================================================================================================*/

    public function TaiKhoan(){
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $array = [
            'page' => 'sec-tai-khoan',
            'addressDefault' => $this->IndexController->getAddressDefault(session('user')->id),
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
        ];
        return view($this->user."tai-khoan")->with($array);
    }

    public function ThongBao()
    {
        $array = [
            'page' => 'sec-thong-bao',
        ];
        return view($this->user."tai-khoan")->with($array);
    }

    public function DonHang()
    {
        $array = [
            'page' => 'sec-don-hang',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function DiaChi()
    {
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];
        
        $array = [
            'page' => 'sec-dia-chi',
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
        ];

        return view($this->user."tai-khoan")->with($array);
    }

    public function ChiTietDonHang($id){
        $data = [
            'order' => $this->IndexController->getOrderById($id),
            'page' => 'sec-chi-tiet-don-hang',
        ];

        return view ($this->user."tai-khoan")->with($data);
    }

    public function YeuThich()
    {
        $array = [
            'page' => 'sec-yeu-thich',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function Voucher()
    {
        $array = [
            'page' => 'sec-voucher',
        ];

        return view ($this->user."tai-khoan")->with($array);
    }

    public function DiaChiGiaoHang(Request $request)
    {
        // bắt buộc request phải từ trang thanh toán
        if(Session::get('_previous')){
            $url = Session::get('_previous')['url'];
            $arrUrl = explode('/', $url);
            $page = $arrUrl[count($arrUrl) - 1];

            // mảng các trang cho phép truy cập | redirect
            $lst_allowPage = [
                'diachigiaohang',
                'thanhtoan',
                'create-update-address'
            ];

            // các trang không nằm trong mảng cho phép
            if(!in_array($page, $lst_allowPage)){
                return back();
            }
        } else {
            return redirect('/');
        }

        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $data = [
            'lstTinhThanh' => $tinhThanh,
            'lstQuanHuyen' => $lstQuanHuyen,
        ];

        return view($this->user."dia-chi-giao-hang")->with($data);
    }

    /*============================================================================================================
                                                    Submit
    ==============================================================================================================*/
    
    public function ChangeAddressDelivery(Request $request)
    {
        TAIKHOAN_DIACHI::where('macdinh', 1)->update(['macdinh' => 0]);
        TAIKHOAN_DIACHI::where('id', $request->address_id)->update(['macdinh' => 1]);

        return redirect('thanhtoan')->with('toast_message', 'Đã thay đổi địa chỉ giao hàng');
    }

    public function CreateUpdateAddress(Request $request)
    {
        $type = $request->address_type;

        if($type == 'create'){
            $data = [
                'id_tk' => session('user')->id,
                'hoten' => $request->adr_fullname_inp,
                'diachi' => $request->address_inp,
                'phuongxa' => $request->PhuongXa_name_inp,
                'quanhuyen' => $request->QuanHuyen_name_inp,
                'tinhthanh' => $request->TinhThanh_name_inp,
                'sdt' => $request->adr_tel_inp,
                'macdinh' => $request->set_default_address == null ? 0 : 1,
            ];

            if($data['macdinh'] == null || $data['macdinh'] == 0){
                if(!TAIKHOAN_DIACHI::where('id_tk', session('user')->id)->where('macdinh' , 1)->first()){
                    $data['macdinh'] = 1;
                }
            } else {
                TAIKHOAN_DIACHI::where('id_tk', session('user')->id)->where('macdinh', 1)->update(['macdinh' => 0]);
            }

            TAIKHOAN_DIACHI::create($data);

            return back()->with('toast_message', 'Tạo địa chỉ thành công');

        } elseif($type == 'edit'){
            $data = [
                'hoten' => $request->adr_fullname_inp,
                'diachi' => $request->address_inp,
                'phuongxa' => $request->PhuongXa_name_inp,
                'quanhuyen' => $request->QuanHuyen_name_inp,
                'tinhthanh' => $request->TinhThanh_name_inp,
                'sdt' => $request->adr_tel_inp,
                'macdinh' => $request->set_default_address == null ? 0 : 1,
            ];

            if($data['macdinh'] == null || $data['macdinh'] == 0){
                if(!TAIKHOAN_DIACHI::where('id_tk', session('user')->id)->where('macdinh' , 1)->first() || (count(TAIKHOAN_DIACHI::where('id_tk', session('user')->id)->where('macdinh' , 1)->get()) == 1 && TAIKHOAN_DIACHI::where('id', $request->tk_dc_id)->first()->macdinh == 1)){
                    $data['macdinh'] = 1;
                }
            }
            else {
                $address = TAIKHOAN_DIACHI::where('id_tk', session('user')->id)->where('macdinh' , 1)->first();
                if($address){
                    $address->macdinh = 0;
                    $address->save();
                }
            }

            TAIKHOAN_DIACHI::where('id', $request->tk_dc_id)->update($data);

            return back()->with('toast_message', 'Chỉnh sửa địa chỉ thành công');
        }
    }

    public function SetDefaultAddress($id)
    {
        TAIKHOAN_DIACHI::where('macdinh', 1)->update(['macdinh' => 0]);
        TAIKHOAN_DIACHI::where('id', $id)->update(['macdinh' => 1]);

        return back()->with('toast_message', 'Đã thay đổi địa chỉ');
    }

    public function ApplyVoucher(Request $request)
    {
        if($request->ajax()){
            $id = $request->id;

            $response = [
                'status' => 'success',
                'voucher' => null
            ];
    
            if(session('user')){
                if(count(TAIKHOAN::find(session('user')->id)->giohang) == 0){
                    return back();
                }
    
                if(session('voucher')){
                    Session::forget('voucher');

                    $response['status'] = 'cancel';
                } else {
                    $voucher = VOUCHER::find($id);

                    session(['voucher' => $voucher]);
                    $response['voucher'] = $voucher;
                }

                return $response;
            }
        }
    }

    public function DeleteObject(Request $request)
    {
        switch($request->object) {
            case 'address':
                TAIKHOAN_DIACHI::destroy($request->id);
                return back()->with('toast_message', 'Xóa địa chỉ thành công');
                break;
            case 'item-cart':
                GIOHANG::destroy($request->id);
                if(count(GIOHANG::all()) === 0) {
                    GIOHANG::truncate();
                }

                // xóa voucher đang áp dụng khi giỏ hàng rỗng
                if(!GIOHANG::where('id_tk', session('user')->id)->count() && session('voucher')){
                    $request->session()->forget('voucher');
                }

                return back()->with('toast_message', 'Đã xóa sản phẩm');
                break;
            case 'all-cart':
                GIOHANG::where('id_tk', session('user')->id)->delete();
                if(count(GIOHANG::all()) === 0) {
                    GIOHANG::truncate();
                }
                // xóa session voucher
                if(session('voucher')){
                    $request->session()->forget('voucher');
                }
                return back()->with('toast_message', 'Đã xóa giỏ hàng');
                break;
            case 'order':
                // cập nhật trạng thái đơn hàng: Đã hủy
                DONHANG::where('id', $request->id)->update(['trangthaidonhang' => 'Đã hủy']);

                $order = DONHANG::find($request->id);
                $id_tk = $order->id_tk;

                // hoàn lại số lượng kho
                $this->refundOfInventory($order->id);

                // khôi phục voucher đã áp dụng
                if($order->id_vc){
                    $id_vc = DONHANG::find($request->id)->id_vc;
                    $this->restoreTheAppliedVoucher($id_vc, $id_tk);
                }

                return back()->with('toast_message', 'Đã hủy đơn hàng');
                break;
            case 'evaluate':
                $id_dg = $request->id;

                $evaluate = DANHGIASP::find($id_dg);

                // xóa hình đánh giá trong thư mục và db
                foreach(CTDG::where('id_dg', $id_dg)->get() as $key){
                    unlink('images/evaluate/' . $key['hinhanh']);
                    CTDG::destroy($key['id']);
                }

                // xóa phản hồi
                PHANHOI::where('id_dg', $id_dg)->delete();

                // xóa lượt thích
                LUOTTHICH::where('id_dg', $id_dg)->delete();
                
                // kiểm tra các dòng thuộc cùng 1 đánh giá
                $lst_id = $this->IndexController->getListIdByCapacity(DANHGIASP::find($id_dg)->id_sp);
                
                $lst_id_dg = [];

                // danh sách đánh giá trong khoảng của id_sp và cùng thuộc 1 đánh giá
                foreach(DANHGIASP::whereBetween('id_sp', [$lst_id[0], $lst_id[count($lst_id) - 1]])->get() as $key){
                    if($evaluate->id_tk == $key['id_tk'] && $evaluate->noidung == $key['noidung'] && $evaluate->thoigian == $key['thoigian']){
                        array_push($lst_id_dg, $key['id']);
                    }
                }

                // xóa các dòng thuộc 1 đánh giá
                if(!empty($lst_id_dg)){
                    foreach($lst_id_dg as $key){
                        DANHGIASP::destroy($key);
                    }
                } else {
                    DANHGIASP::destroy($id_dg);
                }

                return back()->with('toast_message', 'Đã xóa đánh giá');
                break;
        }

        return back();
    }

    // hoàn lại số lượng kho
    public function refundOfInventory($id_dh)
    {
        $order = DONHANG::find($id_dh);

        foreach(CTDH::where('id_dh', $id_dh)->get() as $detail){
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
                $userProvince = DONHANG_DIACHI::find($order->id_dh_dc)->tinhthanh;

                // tỉnh thành thuộc bắc || nam
                $file = file_get_contents('TinhThanh.json');
                $lst_province = json_decode($file, true);
                $province = [];
                foreach($lst_province as $key){
                    if($key['Name'] == $userProvince){
                        $province = $key;
                        break;
                    }
                }

                // chi nhánh tại Hà Nội
                if($province['ID'] < 48){
                    $branch = CHINHANH::where('id_tt', TINHTHANH::where('tentt', 'like', 'Hà Nội')->first()->id)->first();
                }
                // chi nhánh tại Hồ Chí Minh
                else {
                    $branch = CHINHANH::where('id_tt', TINHTHANH::where('tentt', 'like', 'Hồ Chí Minh')->first()->id)->first();
                }

                // số lượng sp trong kho tại chi nhánh
                $qtyInStock = KHO::where('id_cn', $branch->id)->where('id_sp', $detail->id_sp)->first()->slton;
                // số lượng sản phẩm mua
                $qtyBuy = $detail->sl;
                // trả lại số lượng kho
                $qtyInStock += $qtyBuy;
                // cập nhật kho
                KHO::where('id_cn', $branch->id)->where('id_sp', $detail->id_sp)->update(['slton' => $qtyInStock]);
            }
        }
    }

    // khôi phục voucher đã sử dụng
    public function restoreTheAppliedVoucher($id_vc, $id_tk)
    {
        $userVoucher = TAIKHOAN_VOUCHER::where('id_vc', $id_vc)->where('id_tk', $id_tk)->first();
        if(!$userVoucher){
            TAIKHOAN_VOUCHER::create([
                'id_tk' => $id_tk,
                'id_vc' => $id_vc,
                'sl' => 1
            ]);
        } else {
            $qty = $userVoucher->sl;
            $userVoucher->sl = ++$qty;
            $userVoucher->save();
        }
    }

    /*============================================================================================================
                                                    Ajax
    ==============================================================================================================*/

    public function AjaxCheckNoti(Request $request)
    {
        if($request->ajax()){
            THONGBAO::where('id', $request->id)->update(['trangthaithongbao' => 1]);
        }
    }

    public function AjaxDeleteNoti(Request $request)
    {
        if($request->ajax()){
            THONGBAO::destroy($request->id);
        }
    }

    public function AjaxCheckAllNoti(Request $request)
    {
        if($request->ajax()){
            THONGBAO::where('id_tk', session('user')->id)->update(['trangthaithongbao' => 1]);
        }
    }

    public function AjaxDeleteAllNoti(Request $request)
    {
        if($request->ajax()){
            THONGBAO::where('id_tk', session('user')->id)->delete();
        }
    }

    public function AjaxDeleteFavorite(Request $request)
    {
        if($request->ajax()){
            SP_YEUTHICH::destroy($request->id);
        }
    }

    public function AjaxDeleteAllFavorite(Request $request)
    {
        if($request->ajax()){
            SP_YEUTHICH::where('id_tk', session('user')->id)->delete();
        }
    }

    public function AjaxChangeAvatar(Request $request)
    {
        $data = $request->base64data;
        $image_arr_1 = explode(';', $data);
        $image_arr_2 = explode(',', $image_arr_1[1]);

        $data = base64_decode($image_arr_2[1]);

        $imageName = session('user')->id.'.jpg';
        $urlImage = 'images/user/'.$imageName;

        file_put_contents($urlImage, $data);

        TAIKHOAN::where('id', session('user')->id)->update(['anhdaidien' => $imageName]);

        $this->IndexController->userSessionUpdate();

        //return $urlImage;
        return back()->with('toast_message', 'Cập nhật ảnh đại diện thành công');
    }

    public function AjaxChangeFullname(Request $request)
    {
        if($request->ajax()){
            $update = TAIKHOAN::where('id', session('user')->id)->update(['hoten' => $request->hoten]);
            
            $this->IndexController->userSessionUpdate();
        }
    }

    public function AjaxChangePassword(Request $request)
    {
        if($request->ajax()){
            // kiểm tra mật khẩu cũ có chính xác không
            if(Hash::check($request->old_pw, TAIKHOAN::where('id', session('user')->id)->first()->password)){
                $new_pw = Hash::make($request->new_pw);
                TAIKHOAN::where('id', session('user')->id)->update(['password' => $new_pw]);

                $this->IndexController->userSessionUpdate();

                return [
                    'status' => 'success'
                ];
            }

            return [
                'status' => 'invalid password'
            ];
        }
    }

    public function AjaxAddDeleteFavorite(Request $request)
    {
        if($request->ajax()){
            // chưa đăng nhập
            if(!session('user')){
                return [
                    'status' => 'login required'
                ];
            }

            // chưa có: thêm vào danh sách yêu thích
            if(!SP_YEUTHICH::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()){
                SP_YEUTHICH::create([
                    'id_tk' => session('user')->id,
                    'id_sp' => $request->id_sp,
                ]);

                return [
                    'status' => 'add success'
                ];
            }
            // đã có: xóa khỏi danh sách yêu thích
            else {
                SP_YEUTHICH::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->delete();

                return [
                    'status' => 'delete success'
                ];
            }
        }
    }

    public function AjaxLikeComment(Request $request)
    {
        if($request->ajax()){
            // chưa thích bình luận
            if(!LUOTTHICH::where('id_tk', session('user')->id)->where('id_dg', $request->id_dg)->first()){
                LUOTTHICH::create([
                    'id_tk' => session('user')->id,
                    'id_dg' => $request->id_dg,
                ]);

                // cập nhật lượt thích bảng DANHGIASP
                $qty = intval(DANHGIASP::where('id', $request->id_dg)->first()->soluotthich);
                DANHGIASP::where('id', $request->id_dg)->update(['soluotthich' => ++$qty]);

                return [
                    'status' => 'like success'
                ];
            }
            // bỏ thích
            LUOTTHICH::where('id_tk', session('user')->id)->where('id_dg', $request->id_dg)->delete();

            // cập nhật lượt thích bảng DANHGIASP
            $qty = intval(DANHGIASP::where('id', $request->id_dg)->first()->soluotthich);
            DANHGIASP::where('id', $request->id_dg)->update(['soluotthich' => --$qty]);

            return [
                'status' => 'unlike success'
            ];
        }
    }

    public function CheckVoucherConditions(Request $request)
    {
        if($request->ajax()){
            $cartTotal = $request->cartTotal;
            $lst_voucher = [];

            $userVoucher = TAIKHOAN_VOUCHER::where('id_tk', session('user')->id)->get();

            if(!empty($userVoucher)){
                foreach($userVoucher as $key) {
                    $voucher = VOUCHER::find($key->id_vc);
    
                    // ngày hết hạn
                    $end = strtotime(str_replace('/', '-', $voucher->ngayketthuc));
                    // ngày hiện h
                    $current = strtotime(date('d-m-Y'));
    
                    // voucher còn HSD
                    if($end >= $current){
                        if($request->cartTotal >= $voucher->dieukien){
                            $voucher->status = 'satisfied';
                            $voucher->sl = $key->sl;
                        } else {
                            $voucher->status = 'unsatisfied';
                            $voucher->sl = $key->sl;
                        }
    
                        array_push($lst_voucher, $voucher);
                    }
                }
            }

            return $lst_voucher;
        }
    }

    public function AjaxChoosePhoneToEvaluate(Request $request)
    {
        if($request->ajax()){
            $lst_product = [];
            $lst_id = $request->lst_id;

            for($i = 0; $i < count($lst_id); $i++){
                $lst_product[$i] = $this->IndexController->getProductById($lst_id[$i]);
            }
            return $lst_product;
        }
    }

    public function AjaxCreateEvaluate(Request $request)
    {
        if($request->ajax()){
            // danh sách id_sp
            $lst_id = explode(',', $request->lst_id);
            $id_dg = 0;

            // đánh giá cho 1 sản phẩm
            if(count($lst_id) == 1){
                $data = [
                    'id_tk' => session('user')->id,
                    'id_sp' => $lst_id[0],
                    'noidung' => $request->evaluateContent,
                    'thoigian' => date('d/m/Y H:i:s'),
                    'soluotthich' => 0,
                    'danhgia' => $request->evaluateStarRating,
                    'chinhsua' => 0
                ];

                $create = DANHGIASP::create($data);
                $id_dg = $create->id;
            }
            // đánh giá cho nhiều sản phẩm
            else {
                $time = date('d/m/Y H:i:s');
                $id_tk = session('user')->id;

                // lấy id_dg đầu tiên cho trường hợp có ảnh đính kèm
                $data = [
                    'id_tk' => $id_tk,
                    'id_sp' => $lst_id[0],
                    'noidung' => $request->evaluateContent,
                    'thoigian' =>$time,
                    'soluotthich' => 0,
                    'danhgia' => $request->evaluateStarRating,
                    'chinhsua' => 0
                ];

                $create = DANHGIASP::create($data);

                $id_dg = $create->id;

                for($i = 1; $i < count($lst_id); $i++){
                    $data = [
                        'id_tk' => $id_tk,
                        'id_sp' => $lst_id[$i],
                        'noidung' => $request->evaluateContent,
                        'thoigian' =>$time,
                        'soluotthich' => 0,
                        'danhgia' => $request->evaluateStarRating,
                        'chinhsua' => 0
                    ];

                    DANHGIASP::create($data);
                }
            }
            // có ảnh đính kèm
            if($request->evaluateImage){
                // chưa có thư mục lưu hình
                if(!is_dir('images/evaluate')){
                    // tạo thư mục lưu hình
                    mkdir('images/evaluate', 0777, true);
                }
                foreach($request->evaluateImage as $idx => $image){
                    // định dạng hình
                    $imageFormat = $this->IndexController->getImageFormat($image);
                    if($imageFormat == 'png'){
                        $base64 = str_replace('data:image/png;base64,', '', $image);
                        $imageName = time().$idx.'.png';
                    } else {
                        $base64 = str_replace('data:image/jpeg;base64,', '', $image);
                        $imageName = time().$idx.'.jpg';
                    }
                    // lưu hình
                    $this->IndexController->saveImage('images/evaluate/'.$imageName, $base64);

                    CTDG::create([
                        'id_dg' => $id_dg,
                        'hinhanh' => $imageName,
                    ]);
                }
            }
        }
    }

    public function AjaxEditEvaluate(Request $request)
    {
        if($request->ajax()){
            // cập nhật đánh giá
            // kiểm tra gộp đánh giá
            $evaluate = DANHGIASP::find($request->id_dg);

            $lst_id = $this->IndexController->getListIdByCapacity($evaluate->id_sp);
            
            $lst_id_dg = [];

            // danh sách đánh giá trong khoảng của id_sp
            foreach(DANHGIASP::whereBetween('id_sp', [$lst_id[0], $lst_id[count($lst_id) - 1]])->get() as $key){
                if($evaluate->id_tk == $key['id_tk'] && $evaluate->noidung == $key['noidung'] && $evaluate->thoigian == $key['thoigian']){
                    array_push($lst_id_dg, $key['id']);
                }
            }

            // cập nhật các dòng của cùng 1 đánh giá
            if(!empty($lst_id_dg)){
                foreach($lst_id_dg as $key){
                    $data = [
                        'noidung' => $request->evaluateContent,
                        'thoigian' => date('d/m/Y H:i:s'),
                        'danhgia' => $request->evaluateStarRating,
                        'chinhsua' => 1
                    ];

                    DANHGIASP::where('id', $key)->update($data);
                }
            } else {
                $data = [
                    'noidung' => $request->evaluateContent,
                    'thoigian' => date('d/m/Y H:i:s'),
                    'danhgia' => $request->evaluateStarRating,
                    'chinhsua' => 1
                ];

                DANHGIASP::where('id', $request->id_dg)->update($data);
            }

            // xóa hình đánh giá trong thư mục và db
            foreach(CTDG::where('id_dg', $request->id_dg)->get() as $key){
                unlink('images/evaluate/' . $key['hinhanh']);
                CTDG::destroy($key['id']);
            }

            // cập nhật hình mới
            if(!empty($request->evaluateImage)){
                foreach($request->evaluateImage as $idx => $image){
                    // định dạng hình
                    $imageFormat = $this->IndexController->getImageFormat($image);
                    if($imageFormat == 'png'){
                        $base64 = str_replace('data:image/png;base64,', '', $image);
                        $imageName = time().$idx.'.png';
                    } else {
                        $base64 = str_replace('data:image/jpeg;base64,', '', $image);
                        $imageName = time().$idx.'.jpg';
                    }
                    // lưu hình
                    $this->IndexController->saveImage('images/evaluate/'.$imageName, $base64);
    
                    CTDG::create([
                        'id_dg' => $request->id_dg,
                        'hinhanh' => $imageName,
                    ]);
                }
            }
        }
    }

    public function AjaxReply(Request $request)
    {
        if($request->ajax()){
            $create = PHANHOI::create([
                'id_tk' => session('user')->id,
                'id_dg' => $request->id_dg,
                'noidung' => $request->replyContent,
                'thoigian' => date('d/m/Y H:i:s'),
            ]);

            $evaluate = DANHGIASP::find($request->id_dg);
            $user = TAIKHOAN::find($evaluate->id_tk);
            $userReply = TAIKHOAN::find($create->id_tk);
            $product = $this->IndexController->getProductById($evaluate->id_sp);

            // gửi thông báo
            if($userReply->id != $user->id){
                THONGBAO::create([
                    'id_tk' => $user->id,
                    'tieude' => 'Phản hồi',
                    'noidung' => "Bạn có một phản hồi từ <b>$userReply->hoten</b> ở sản phẩm <b>".$product['tensp'] .' - '. $product['mausac']."</b>.",
                    'thoigian' => date('d/m/Y H:i:s')
                ]);

                $notification = [
                    'user' => $user,
                    'type' => 'reply',
                    'data' => [
                        'userReply' => $userReply,
                        'avtURL' => $userReply->htdn == 'nomal' ? 'images/user/'.$userReply->anhdaidien : $userReply->anhdaidien,
                        'link' => route('user/chi-tiet', ['name' => $product['tensp_url'], 'danhgia' => $request->id_dg])
                    ]
                ];
                //PUSH NOTI TO APP 
                if(!empty($user->device_token))
                (new PushNotificationController)->sendPush($user->device_token, "Phản hồi", $userReply->hoten." đã trả lời đánh giá của bạn ");

                event(new sendNotification($notification));
            }
        }
    }

    public function AjaxGetTypeNotification(Request $request)
    {
        if($request->ajax()){
            $type = $request->type;
            $data = [];
            switch($type){
                case 'all':
                    $data = THONGBAO::where('id_tk', session('user')->id)->orderBy('id', 'desc')->limit(10)->get();
                    break;
                case 'not-seen':
                    $data = THONGBAO::where('id_tk', session('user')->id)->orderBy('id', 'desc')->where('trangthaithongbao', 0)->get();
                    break;
                case 'seen':
                    $data = THONGBAO::where('id_tk', session('user')->id)->orderBy('id', 'desc')->where('trangthaithongbao', 1)->get();
                    break;
                case 'order':
                    $data = THONGBAO::where('id_tk', session('user')->id)
                                    ->orderBy('id', 'desc')
                                    ->Where('tieude', 'Đơn đã tiếp nhận')
                                    ->orWhere('tieude', 'Đơn đã xác nhận')
                                    ->orWhere('tieude', 'Giao hàng thành công')
                                    ->get();
                    break;
                case 'voucher':
                    $data = THONGBAO::where('id_tk', session('user')->id)->orderBy('id', 'desc')->where('tieude', 'Mã giảm giá')->get();
                    break;
                case 'reply':
                    $data = THONGBAO::where('id_tk', session('user')->id)->orderBy('id', 'desc')->where('tieude', 'Phản hồi')->get();
                    break;
            }

            return $data;
        }
    }

    public function AjaxDeleteExpiredVoucher(Request $request){
        if($request->ajax()){
            TAIKHOAN_VOUCHER::destroy($request->id);
        }
    }

    public function AjaxRemoveVoucher(Request $request){
        if($request->ajax()){
            Session::forget('voucher');
            return 'Đã hủy mã giảm giá do chưa thỏa điều kiện';
        }
    }

    public function AjaxIsAppliedVoucher(Request $request)
    {
        if($request->ajax()) {
            return session('voucher') ? true : false;
        }
    }

    public function AjaxIsExpiredVoucher(Request $request){
        if($request->ajax()){
            $id = session('voucher')->id;
            $voucher = VOUCHER::find($id);

            // ngày kết thúc
            $end = strtotime(str_replace('/', '-', $voucher->ngayketthuc));
            // ngày hiện tại
            $current = strtotime(date('d-m-Y'));
            // voucher hết HSD
            if($end < $current){
                $request->session()->forget('voucher');
                return true;
            }

            return false;
        }
    }

    public function AjaxCheckSatisfiedVoucher(Request $request) {
        if($request->ajax()) {
            $response = [
                'status' => true
            ];

            $total = 0;
            $id_tk = session('user')->id;

            // lấy tổng tiền giỏ hàng thanh toán
            foreach($request->idList as $id_sp) {
                $qtyInStock = KHO::where('id_sp', $id_sp)->sum('slton');

                if($qtyInStock > 0) {
                    $product = $this->IndexController->getProductById($id_sp);
                    $price = $product['giakhuyenmai'];
                    $qtyInCart = GIOHANG::where('id_tk', $id_tk)->where('id_sp', $id_sp)->first()->sl;
                    $total += $price * $qtyInCart;
                }
            }

            // điều kiện voucher
            $condition = session('voucher')->dieukien;

            return $total >= $condition;
        }
    }
}
