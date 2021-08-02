<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\user\IndexController;
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
        if(Auth::check()){
            return back()->with('toast_message', 'Bạn đã đăng nhập');
        }
        
        // url trước đó
        $prev_url = Session::get('prev_url');
        if(!$prev_url){
            $prevUrlSession = Session::get('_previous')['url'];
            if(!$prevUrlSession){
                $prevUrlSession = '/';
            }
            Session::put('prev_url', $prevUrlSession);
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
            TAIKHOAN::where('id', $user->id)->update(['login_status' => 1]);
            session(['user' => $user]);

            // quay về url trước đó
            $prev_url = session('prev_url');
            if($prev_url){
                if($prev_url == 'http://127.0.0.1:8000/dangky'){
                    Session::forget('prev_url');
                    return redirect('/')->with('toast_message', 'Đăng nhập thành công');
                }
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

            $exist = TAIKHOAN::where('email', $user->email)->first();

            // đã tồn tại
            if($exist){
                // đã đăng nhập bằng facebook
                if($exist->htdn == 'facebook'){
                    $exist->update([
                        'anhdaidien' => $user->avatar_original . "&access_token={$user->token}",
                        'user_social_token' => $user->token,
                        'login_status' => 1
                    ]);
                    
                    Auth::login($exist);
                    session(['user' => $exist]);
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
                
                Auth::login($newUser);
                session(['user' => $newUser]);
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
       
            $exist = TAIKHOAN::where('email', $user->email)->first();
       
            if($exist){
                if($exist->htdn == 'google'){
                    $exist->update(['login_status' => 1]);

                    Auth::login($exist, true);
                    session(['user' => $exist]);
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
    
            return redirect('/')->with('toast_message', 'Đăng nhập thành công');
        } catch (Exception $e) {
            return back()->with('error_message', 'Đã có lỗi xảy ra. Vui lòng thử lại');
        }
    }

    public function LogOut()
    {
        if(!session('user')){
            return back();
        }

        Auth::logout();
        TAIKHOAN::where('id', session('user')->id)->update(['login_status' => 0]);
        Session::flush();
        Session::put('visitor', '1');
        return redirect('/')->with('toast_message', 'Đã đăng xuất');
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

    public function DiaChiGiaoHang()
    {
        if(count(TAIKHOAN::find(session('user')->id)->giohang) == 0){
            return back();
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

    public function UseVoucher($id)
    {
        if(session('user')){
            if(count(TAIKHOAN::find(session('user')->id)->giohang) == 0){
                return back();
            }

            if(session('voucher')){
                Session::forget('voucher');
                return back()->with('toast_message', 'Đã hủy áp dụng mã giảm giá');
            }

            session(['voucher' => VOUCHER::find($id)]);
            return back()->with('toast_message', 'Đã áp dụng mã giảm giá');
        }

        return back();
    }

    public function DeleteObject(Request $request)
    {
        if($request->object == 'address'){
            TAIKHOAN_DIACHI::destroy($request->id);
            return back()->with('toast_message', 'Xóa địa chỉ thành công');
        } 
        elseif($request->object == 'item-cart'){
            GIOHANG::destroy($request->id);
            return back()->with('toast_message', 'Đã xóa sản phẩm');
        } 
        elseif($request->object == 'all-cart'){
            GIOHANG::where('id_tk', session('user')->id)->delete();
            // xóa session voucher
            if(session('voucher')){
                $request->session()->forget('voucher');
            }
            return back()->with('toast_message', 'Đã xóa giỏ hàng');
        }
        // Hủy đơn hàng
        elseif($request->object == 'order'){
            // cập nhật trạng thái đơn hàng: Đã hủy
            DONHANG::where('id', $request->id)->update(['trangthaidonhang' => 'Đã hủy']);

            // hoàn lại số lượng kho
            $order = DONHANG::find($request->id);
            foreach(CTDH::where('id_dh', $request->id)->get() as $detail){
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
                    $userProvince = TAIKHOAN_DIACHI::find($order->id_tk_dc)->tinhthanh;

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

            // khôi phục voucher đã áp dụng
            if($order->id_vc){
                $id_vc = DONHANG::find($request->id)->id_vc;
                $userVoucher = TAIKHOAN_VOUCHER::where('id_vc', $id_vc)->where('id_tk', session('user')->id)->first();
                if(!$userVoucher){
                    TAIKHOAN_VOUCHER::create([
                        'id_tk' => session('user')->id,
                        'id_vc' => $id_vc,
                    ]);
                } else {
                    $qty = $userVoucher->sl;
                    $userVoucher->sl = ++$qty;
                    $userVoucher->save();
                }
                
                // cập nhật số lượng voucher
                $voucher = VOUCHER::find($userVoucher->id_vc);
                $qty = $voucher->sl;
                $voucher->sl = ++$qty;
                $voucher->save();
            }

            return back()->with('toast_message', 'Đã hủy đơn hàng');
        }
        // xóa đánh giá
        elseif($request->object == 'evaluate'){
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

        }

        return back();
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

            return $request->hoten;
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
            // chưa đăng nhập
            if(!session('user')){
                return [
                    'status' => 'login required'
                ];
            }

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
            $html = '';
            foreach(TAIKHOAN::find(session('user')->id)->taikhoan_voucher as $key){
                $voucher = VOUCHER::find($key->pivot->id_vc);
                if($request->cartTotal >= $voucher->dieukien){
                    $html .= '
                                <div class="pb-30">
                                    <div class="account-voucher">
                                        <div class="voucher-left w-20 p-70">'.
                                            ($key->pivot->sl != 1 ? '
                                            <div class="voucher-qty">'.$key->pivot->sl.'x</div>' : '').'
                                            <div class="voucher-left-content fz-40">-'.$voucher->chietkhau*100 .'%</div>
                                        </div>
                                        <div class="voucher-right w-80">
                                            <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                                <div class="d-flex justify-content-end">
                                                    <div class="relative promotion-info-icon">
                                                        <i class="fal fa-info-circle fz-20"></i>
                                                        <div class="voucher-content box-shadow p-20 ">
                                                            <table class="table">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="w-40">Mã</td>
                                                                        <td><b>'.$voucher->code.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="w-40">Nội dung</td>
                                                                        <td>'.$voucher->noidung.'</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" class="w-40">
                                                                            <div class="d-flex flex-column">
                                                                                <span>Điều kiện</span>';
                                                                                if($voucher->dieukien != 0){
                                                                    $html .='   <ul class="mt-10">
                                                                                    <li>Áp dụng cho đơn hàng từ '.number_format($key->dieukien, 0, '', '.').'<sup>đ</sup></li>
                                                                                </ul>';
                                                                                }
                                                                $html .='    </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="w-40">Hạn sử dụng</td>
                                                                        <td>'.$voucher->ngayketthuc.'</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-fill">
                                                    <span>'.$voucher->noidung.'</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="d-flex align-items-end">HSD: '.$voucher->ngayketthuc.'</span>
                                                    <div data-id="'.$key->id.'" class="use-voucher-btn main-btn p-10">Áp dụng</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    ';
                } else {
                    $html .= '
                                <div class="pb-30">
                                    <div class="account-voucher">
                                        <div class="dis-voucher-left w-20 p-70">'.
                                            ($key->pivot->sl != 1 ? '
                                                <div class="voucher-qty">'.$key->pivot->sl.'x</div>' : '').'
                                            <div class="dis-voucher-left-content fz-40">-'.$voucher->chietkhau*100 .'%</div>
                                        </div>
                                        <div class="dis-voucher-right w-80">
                                            <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                                <div class="d-flex justify-content-end">
                                                    <div class="relative dis-promotion-info-icon">
                                                        <i class="fal fa-info-circle fz-20"></i>
                                                        <div class="voucher-content box-shadow p-20 ">
                                                            <table class="table">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="w-40">Mã</td>
                                                                        <td><b>'.$voucher->code.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="w-40">Nội dung</td>
                                                                        <td>'.$voucher->noidung.'</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" class="w-40">
                                                                            <div class="d-flex flex-column">
                                                                                <span>Điều kiện</span>';
                                                                                if($voucher->dieukien != 0){
                                                                    $html .='   <ul class="mt-10">
                                                                                    <li>Áp dụng cho đơn hàng từ '.number_format($key->dieukien, 0, '', '.').'<sup>đ</sup></li>
                                                                                </ul>';
                                                                                }
                                                                $html .='    </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="w-40">Hạn sử dụng</td>
                                                                        <td>'.$voucher->ngayketthuc.'</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-fill">
                                                    <span>'.$voucher->noidung.'</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="d-flex align-items-end">HSD: '.$voucher->ngayketthuc.'</span>

                                                    <div class="dis-condition-tag">Chưa thỏa điều kiện</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    ';
                }
            }

            return $html;
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
                ];

                $create = DANHGIASP::create($data);
                $id_dg = $create->id;
            }
            // đánh giá cho nhiều sản phẩm
            else {
                $time = date('d/m/Y H:i:s');
                $data = [
                    'id_tk' => session('user')->id,
                    'id_sp' => $lst_id[0],
                    'noidung' => $request->evaluateContent,
                    'thoigian' =>$time,
                    'soluotthich' => 0,
                    'danhgia' => $request->evaluateStarRating,
                ];

                $create = DANHGIASP::create($data);

                $id_dg = $create->id;

                for($i = 1; $i < count($lst_id); $i++){
                    $data = [
                        'id_tk' => session('user')->id,
                        'id_sp' => $lst_id[$i],
                        'noidung' => $request->evaluateContent,
                        'thoigian' =>$time,
                        'soluotthich' => 0,
                        'danhgia' => $request->evaluateStarRating,
                        'trangthai' => 1,
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
                foreach($request->evaluateImage as $idx => $key){
                    $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                    $data = base64_decode($base64);

                    $imageName = time().$idx.'.jpg';
                    $urlImage = 'images/evaluate/'.$imageName;
                    // thêm hình
                    file_put_contents($urlImage, $data);

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
                    ];

                    DANHGIASP::where('id', $key)->update($data);
                }
            } else {
                $data = [
                    'noidung' => $request->evaluateContent,
                    'thoigian' => date('d/m/Y H:i:s'),
                    'danhgia' => $request->evaluateStarRating,
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
                foreach($request->evaluateImage as $idx => $key){
                    $base64 = str_replace('data:image/jpeg;base64,', '', $key);
                    $data = base64_decode($base64);
    
                    $imageName = time().$idx.'.jpg';
                    $urlImage = 'images/evaluate/'.$imageName;
                    // thêm hình
                    file_put_contents($urlImage, $data);
    
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
                    'noidung' => "Bạn có một phản hồi từ <b>$userReply->hoten</b> ở sản phẩm <b>".$product['tensp'] .' - '. $product['mausac']."</b>."
                ]);

                $notification = [
                    'user' => $user,
                    'type' => 'reply',
                    'notification' => '',
                ];

                $notification['notification'] = '<div id="alert-toast" class="alert-toast-2">
                                                    <span class="close-toast-btn"><i class="fal fa-times-circle"></i></span>
                                                    <div class="d-flex align-items-center">
                                                        <div class="alert-toast-icon">
                                                            <img src="'.(session('user')->htdn == 'nomal' ? 'images/user/'.$userReply->anhdaidien : $userReply->anhdaidien).'" class="circle-img">
                                                        </div>
                                                        <div class="alert-toast-2-content">
                                                            <div class="mb-10"><b>'.$userReply->hoten.'</b> đã trả lời đánh giá của bạn. <a href="'.route('user/chi-tiet', ['name' => $product['tensp_url'], 'danhgia' => $request->id_dg]).'">Chi tiết</a></div>
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

            $html = '';
            foreach($data as $key){
                $html .= '<div id="noti-'.$key->id.'" class="single-noti '.($key->trangthaithongbao == 0 ? 'account-noti-wait' : 'account-noti-checked').' box-shadow mb-20">
                            <div class="d-flex align-items-center justify-content-between p-10 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div>';
                                        if ($key->tieude == 'Đơn đã tiếp nhận'){
                                            $html .= '<i class="fas fa-file-alt fz-28 info-color"></i>';
                                        } elseif ($key->tieude == 'Đơn đã xác nhận'){
                                            $html .= '<i class="fas fa-file-check fz-28 success-color"></i>';
                                        } elseif ($key->tieude == 'Giao hàng thành công'){
                                            $html .='<i class="fas fa-box-check fz-28 success-color"></i>';
                                        } elseif ($key->tieude == 'Mã giảm giá'){
                                            $html .= '<i class="fas fa-badge-percent fz-28 yellow"></i>';
                                        } elseif ($key->tieude == 'Phản hồi'){
                                            $html .= '<i class="fas fa-reply fz-28 purple"></i>';
                                        } $html .='
                                    </div>
                                    <div class="fw-600 fz-18 ml-10">'.$key->tieude.'</div>
                                </div>
                                <div class="d-flex align-items-end">'.
                                    ($key->trangthaithongbao == 0 ? '
                                    <div type="button" class="noti-btn-read main-color-text mr-10" data-id="'.$key->id.'">Đánh dấu đã đọc</div>' : '').'
                                    <div type="button" class="noti-btn-delete red" data-id="'.$key->id.'">xóa</div>
                                </div>
                            </div>
                            <div class="d-flex pt-20 pb-20 pl-10 pr-10">
                                <div id="noti-content-' . $key['id'] .'">
                                    <div>'.$key['noidung'].'</div>
                                    <div class="mt-10 fz-14">'.$key->thoigian.'</div>
                                </div>
                            </div>
                        </div>';
            }

            return $html;
        }
    }
}
