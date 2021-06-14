<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

use App\Models\TAIKHOAN;

class UserController extends Controller
{
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    //
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
            'loaitk' => 0,
            'htdn' => 'nomal',
            'trangthai' => 1,
        ];


        $newUser = TAIKHOAN::create($data);

        return redirect('/dangnhap')->with('success_message', 'Đăng ký tài khoản thành công!');
    }

    public function LoginPost(Request $request)
    {
        $data = [
            'sdt' => $request->login_tel,
            'password' => $request->login_pw,
        ];

        // lưu đăng nhập hay không
        $request->remember == 'on' ? $remember = true : $remember = false;

        if(Auth::attempt($data, $remember)){
            $request->session()->regenerate();

            $user = TAIKHOAN::where('sdt', $data['sdt'])->first();
            $request->session()->put('user', $user);

            return redirect('/')->with('alert_message', 'Đăng nhập thành công');
        }

        return redirect('/dangnhap')->with('error_message', 'số điện thoại hoặc mật khẩu không chính xác');
    }

    public function LogOut()
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('alert_message', 'Đã đăng xuất');
    }

    public function TaiKhoan(){
        $json_file = file_get_contents('TinhThanh.json');
        $tinhThanh = json_decode($json_file, true);

        $json_file = file_get_contents('QuanHuyen.json');
        $quanHuyen = json_decode($json_file, true);

        $tinhThanhID_0 = $tinhThanh[0]['ID'];

        $lstQuanHuyen = $quanHuyen[$tinhThanhID_0];

        $array = [
            'page' => 'sec-tai-khoan',
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

    public function print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    
}
