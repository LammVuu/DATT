<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Laravel\Socialite\Facades\Socialite;
use Exception;

use App\Models\TAIKHOAN;
use App\Models\TAIKHOAN_DIACHI;

class UserController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    /*============================================================================================================
                                                        Auth
    ==============================================================================================================*/
    public function DangNhap(){
        if(Auth::check()){
            return back()->with('alert_message', 'Bạn đã đăng nhập');
        }
        return view($this->user."dang-nhap");
    }

    public function DangKy(){
        if(Auth::check()){
            return back()->with('alert_message', 'Bạn đã đăng nhập');
        }
        return view($this->user."dang-ky");
    }

    public function SignUpPost(Request $request)
    {
        $exist = TAIKHOAN::where('sdt', $request->su_tel)->first();

        if($exist){
            return redirect('/dangky')->with('error_message', 'Số điện thoại '.$request->su_tel.' đã được đăng ký');
        }

        $data = [
            'sdt' => $request->su_tel,
            'password' => Hash::make($request->su_pw),
            'hoten' => $request->su_fullname,
            'anhdaidien' => 'avatar-default.png',
            'loaitk' => 0,
            'htdn' => 'nomal',
            'trangthai' => 1,
        ];


        $newUser = TAIKHOAN::create($data);

        return redirect('dangnhap')->with('success_message', 'Đăng ký tài khoản thành công!');
    }

    public function LoginPost(Request $request)
    {
        $data = [
            'sdt' => $request->login_tel,
            'password' => $request->login_pw,
            'trangthai' => 1,
        ];

        // lưu đăng nhập hay không
        $request->remember == 'on' ? $remember = true : $remember = false;

        if(Auth::attempt($data, $remember)){
            $request->session()->regenerate();

            $user = TAIKHOAN::where('sdt', $data['sdt'])->first();
            $request->session()->put('user', $user);

            return redirect('')->with('alert_message', 'Đăng nhập thành công');
        }

        return back()->with('error_message', 'số điện thoại hoặc mật khẩu không chính xác');
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
            $user = Socialite::driver('facebook')->user();

            if($user->email == ''){
                return redirect('dangky')->with('error_message', 'Cần quyền truy cập email của bạn để tiếp tục');
            }

            $exist = TAIKHOAN::where('email', $user->email)->first();

            // đã tồn tại
            if($exist){
                if($exist->htdn == 'facebook'){ // đã đăng nhập bằng facebook
                    // còn hạn đăng nhập
                    if($user->token == $exist->user_token){
                        Auth::login($exist, true);
                        session(['user' => $exist]);
                        return redirect('/')->with('alert_message', 'Đăng nhập thành công');
                    }

                    // cập nhật token user, ảnh đại diện
                    $exist->update([
                                'user_token' => $user->token,
                                'anhdaidien' => $user->avatar_original . "&access_token={$user->token}"
                                ]);

                    Auth::login($exist, true);
                    session(['user' => $exist]);
                    return redirect('/')->with('alert_message', 'Đăng nhập thành công');
                }
                
                return redirect('dangnhap')->with('error_message', 'Email này đã được sử dụng');
            }
                
            // tạo tài khoản mới
            TAIKHOAN::create([
                'email' => $user->email,
                'hoten' => $user->name,
                'anhdaidien' => $user->avatar_original . "&access_token={$user->token}",
                'loaitk' => 0,
                'htdn' => 'facebook',
                'user_token' => $user->token,
                'trangthai' => 1,
            ]);

            $newUser = TAIKHOAN::where('email', $user->email)->first();

            Auth::login($newUser, true);
            session(['user' => $newUser]);

            return redirect('/')->with('alert_message', 'Đăng nhập thành công');
        } catch(Exception $e){
            return back()->with('error_message', 'Đã có lỗi xảy ra. Vui lòng thử lại');
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
                    Auth::login($exist, true);
                    session(['user' => $exist]);
                    return redirect('/')->with('alert_message', 'Đăng nhập thành công');
                }

                return redirect('dangnhap')->with('error_message', 'Email này đã được sử dụng');
            }

            TAIKHOAN::create([
                'email' => $user->email,
                'hoten' => $user->name,
                'anhdaidien' => $user->avatar,
                'loaitk' => 0,
                'htdn' => 'google',
                'user_token' => $user->token,
                'trangthai',
            ]);

            $newUser = TAIKHOAN::where('email', $user->email)->first();

            Auth::login($newUser, true);
            session(['user' => $newUser]);
    
            return redirect('/')->with('success_message', 'Đăng nhập thành công');
        } catch (Exception $e) {
            return back()->with('error_message', 'Đã có lỗi xảy ra. Vui lòng thử lại');
        }
    }

    public function LogOut()
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('alert_message', 'Đã đăng xuất');
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
            'addressDefault' => $this->getAddressDefault(session('user')->id),
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

    public function ChiTietDonHang(){
        $array = [
            'page' => 'sec-chi-tiet-don-hang',
        ];

        return view ($this->user."tai-khoan")->with($array);
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

    /*============================================================================================================
                                                        Function
    ==============================================================================================================*/

    public function print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    // lấy địa chỉ mặc định
    public function getAddressDefault($id_tk)
    {
        return TAIKHOAN_DIACHI::where('id_tk', $id_tk)->where('macdinh', 1)->first() == null ? null : TAIKHOAN_DIACHI::where('id_tk', $id_tk)->where('macdinh', 1)->first();
    }
    
}
