<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TAIKHOAN;
use App\Models\GIOHANG;
use App\Models\SANPHAM;

class CartController extends Controller
{
    //
    public function __construct()
    {
        $this->viewprefix='user.pages.';
        $this->user='user/content/';
    }
    public function GioHang(){
        return view($this->user."gio-hang");
    }

    public function AjaxAddCart(Request $request)
    {
        if($request->ajax()){
            // chưa đăng nhập
            if(!Auth::check()){
                return [
                    'status' => false,
                ];
            }

            $data = [
                'id_tk' => session('user')->id,
                'id_sp' => $request->id_sp,
                'sl' => $request->sl,
            ];

            // nếu tài khoản chưa có sản phẩm nào trong giỏ hàng
            if(count(TAIKHOAN::find(session('user')->id)->giohang) == 0){
                GIOHANG::create($data);
                return [
                    'status' => 'success',
                ];
            } 
            // kiểm tra cập nhật số lượng hoặc tạo mới
            else {
                foreach(TAIKHOAN::find(session('user')->id)->giohang as $cart){
                    // cập nhật số lượng sản phẩm trong giỏ hàng
                    if($cart->pivot->id_sp == $request->id_sp){
                        $sl = Intval(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()->sl);
                        $sl += $request->sl;
                        GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->update(['sl' => $sl]);

                        return [
                            'status' => 'update success',
                        ];
                    }
                }

                // thêm sản phẩm mới vào giỏ hàng
                GIOHANG::create($data);
                return [
                    'status' => 'success',
                ];
            }
        }

        return false;
    }

    public function AjaxRemoveAllCart(Request $request)
    {
        if($request->ajax()){
            GIOHANG::where('id_tk', session('user')->id)->delete();
        }

        return back();
    }

    public function AjaxRemoveCartItem(Request $request)
    {
        if($request->ajax()){
            GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->delete();
        }

        return false;
    }

    public function AjaxUpdateCart(Request $request)
    {
        if($request->ajax()){
            $data = [
                'newQty' => '',
                'newPrice' => '',
            ];

            // tăng số lượng
            if($request->type == 'plus'){
                $qty = Intval(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()->sl);
                $qty++;
                GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->update(['sl' => $qty]);
            } 
            // giảm số lượng
            else {
                $qty = Intval(GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->first()->sl);
                $qty--;
                GIOHANG::where('id_tk', session('user')->id)->where('id_sp', $request->id_sp)->update(['sl' => $qty]);
            }

            // trả dữ liệu về view
            $data['newQty'] = $qty;
            $data['newPrice'] = Intval($this->getProductById($request->id_sp)['giakhuyenmai'] * $qty);

            return $data;
        }

        return false;
    }

    // lấy sao đánh giá, số lượt đánh giá của mẫu sản phẩm theo dung lượng
    public function starRatingByCapacity($lst_product)
    {
        $lst_evaluate = [];
        $i = 0;

        // tính sao đánh giá từng sản phẩm
        foreach($lst_product as $key){
            $evaluate = $key['danhgia'];
            if($evaluate['qty'] == 0){
                continue;
            }

            $lst_evaluate[$i] = $evaluate;
            $i++;
        }

        // chưa có đánh giá
        if(count($lst_evaluate) == 0){
            return [
                'qty' => 0,
                'star' => 0,
            ];
        }

        // tính sao đánh giá mẫu sản phẩm theo dung lượng
        // công thức: trung bình tổng các sao đánh giá sản phẩm
        $total = 0;

        // tổng số lượng đánh giá của mẫu sp theo dung lượng
        $qtyEvaluate = 0;

        // số lượng sp có đánh giá
        $qty = 0;

        foreach($lst_evaluate as $key){
            $qtyEvaluate += $key['qty'];
            $total += $key['star'];
            $qty++;
        }

        // tổng đánh giá sao
        $totalEvaluate = round(($total / $qty), 1);

        return [
            'qty' => $qtyEvaluate,
            'star' => $totalEvaluate,
        ];
    }

    // lấy sao đánh giá, số lượt đánh giá của sản phẩm theo id_sp
    public function starRatingProduct($id_sp)
    {
        // danh sách đánh giá theo id_sp
        $lst_evaluate = SANPHAM::find($id_sp)->danhgiasp;

        // số lượng đánh giá
        $qty = count($lst_evaluate);

        if($qty == 0){
            return [
                'qty' => 0,
                'star' => 0
            ];
        }

        $_1s = 0; $_2s = 0; $_3s = 0; $_4s = 0; $_5s = 0;

        foreach($lst_evaluate as $key){
            if($key->pivot->danhgia == 1){
                $_1s++;
            } elseif($key->pivot->danhgia == 2){
                $_2s++;
            } elseif($key->pivot->danhgia == 3){
                $_3s++;
            } elseif($key->pivot->danhgia == 4){
                $_4s++;
            } else {
                $_5s++;
            }
        }

        $star = round(((($_1s * 1) + ($_2s * 2) + ($_3s * 3) + ($_4s * 4) + ($_5s * 5)) / $qty),1);

        return [
            'qty' => $qty,
            'star' => $star,
        ];
    }

    // lấy mẫu sp theo dung lượng
    public function getProductByCapacity($lst)
    {
        // dung lượng: 64 GB / 128 GB
        $capacity = $lst[0]['dungluong'];
        $ram = $lst[0]['ram'];

        $promotin = 0;

        $lst_temp = [];
        $i = 0;

        // lọc mảng sp theo dung lượng
        foreach($lst as $key){
            if($key['dungluong'] == $capacity && $key['ram'] == $ram){
                if($this->promotionCheck($key['id'])){
                    $promotion = SANPHAM::find($key['id'])->khuyenmai->chietkhau;
                }

                $lst_temp[$capacity.'_'.$ram][$i] = [
                    'id' => $key['id'],
                    'id_msp' => $key['id_msp'],
                    'tensp' => $key['tensp'],
                    'tensp_url' => str_replace(' ', '-', $key['tensp']),
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'giakhuyenmai' => $key['gia'] - ($key['gia'] * $promotion),
                    'danhgia' => $this->starRatingProduct($key['id']),
                    'trangthai' => $key['trangthai'],
                ];

                $i++;
            } else {
                $capacity = $key['dungluong'];
                $ram = $key['ram'];

                $i = 0;

                if($this->promotionCheck($key['id'])){
                    $promotion = SANPHAM::find($key['id'])->khuyenmai->chietkhau;
                }

                $lst_temp[$capacity.'_'.$ram][$i] = [
                    'id' => $key['id'],
                    'id_msp' => $key['id_msp'],
                    'tensp' => $key['tensp'],
                    'tensp_url' => str_replace(' ', '-', $key['tensp']),
                    'hinhanh' => $key['hinhanh'],
                    'mausac' => $key['mausac'],
                    'ram' => $key['ram'],
                    'dungluong' => $key['dungluong'],
                    'gia' => $key['gia'],
                    'khuyenmai' => $promotion,
                    'giakhuyenmai' => $key['gia'] - ($key['gia'] * $promotion),
                    'danhgia' => $this->starRatingProduct($key['id']),
                    'trangthai' => $key['trangthai'],
                ];

                $i++;
            }
        }

        $lst_product = [];

        // Kiểm tra cùng dung lượng nhưng khác ram
        if(count($lst_temp) > 1){
            $key_1 = explode('_', array_keys($lst_temp)[0])[0];
            $key_2 = explode('_', array_keys($lst_temp)[1])[0];

            // nếu có
            if(strcmp($key_1, $key_2) == 0){
                for($i = 0; $i < count($lst_temp); $i++){
                    $key = $lst_temp[array_keys($lst_temp)[$i]];
                    
                    $rand = mt_rand(0, count($key) - 1);
                    $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['ram'].' '.$key[$rand]['dungluong'];

                    $lst_product[$i] = [
                        'id' => $key[$rand]['id'],
                        'id_msp' => $key[$rand]['id_msp'],
                        'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['ram'].' '.$key[$rand]['dungluong'],
                        'tensp_url' => str_replace(' ', '-', $tensp_url),
                        'hinhanh' => $key[$rand]['hinhanh'],
                        'mausac' => $key[$rand]['mausac'],
                        'ram' => $key[$rand]['ram'],
                        'dungluong' => $key[$rand]['dungluong'],
                        'gia' => $key[$rand]['gia'],
                        'khuyenmai' => $key[$rand]['khuyenmai'],
                        'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                        'danhgia' => $this->starRatingByCapacity($key),
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            } else {
                for($i = 0; $i < count($lst_temp); $i++){
                    $key = $lst_temp[array_keys($lst_temp)[$i]];
    
                    $rand = mt_rand(0, count($key) - 1);
                    $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];
    
                    $lst_product[$i] = [
                        'id' => $key[$rand]['id'],
                        'id_msp' => $key[$rand]['id_msp'],
                        'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['dungluong'],
                        'tensp_url' => str_replace(' ', '-', $tensp_url),
                        'hinhanh' => $key[$rand]['hinhanh'],
                        'mausac' => $key[$rand]['mausac'],
                        'ram' => $key[$rand]['ram'],
                        'dungluong' => $key[$rand]['dungluong'],
                        'gia' => $key[$rand]['gia'],
                        'khuyenmai' => $key[$rand]['khuyenmai'],
                        'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                        'danhgia' => $this->starRatingByCapacity($key),
                        'trangthai' => $key[$rand]['trangthai'],
                    ];
                }
            }
        } else {
            for($i = 0; $i < count($lst_temp); $i++){
                $key = $lst_temp[array_keys($lst_temp)[$i]];

                $rand = mt_rand(0, count($key) - 1);
                $tensp_url = $key[$rand]['tensp_url'].' '.$key[$rand]['dungluong'];

                $lst_product[$i] = [
                    'id' => $key[$rand]['id'],
                    'id_msp' => $key[$rand]['id_msp'],
                    'tensp' => $key[$rand]['tensp'].' '.$key[$rand]['dungluong'],
                    'tensp_url' => str_replace(' ', '-', $tensp_url),
                    'hinhanh' => $key[$rand]['hinhanh'],
                    'mausac' => $key[$rand]['mausac'],
                    'ram' => $key[$rand]['ram'],
                    'dungluong' => $key[$rand]['dungluong'],
                    'gia' => $key[$rand]['gia'],
                    'khuyenmai' => $key[$rand]['khuyenmai'],
                    'giakhuyenmai' => $key[$rand]['giakhuyenmai'],
                    'danhgia' => $this->starRatingByCapacity($key),
                    'trangthai' => $key[$rand]['trangthai'],
                ];
            }
        }

        return $lst_product;
    }

    // lấy sp theo id_sp
    public function getProductById($id_sp)
    {
        return $temp = $this->getProductByCapacity(SANPHAM::where('id', $id_sp)->get())[0];
    }

    // kiểm tra còn hạn khuyến mãi không
    public function promotionCheck($id_sp)
    {
        $warranty = SANPHAM::find($id_sp)->khuyenmai->ngayketthuc;
        $today = date('d/m/Y');

        return $warranty >= $today ? true : false;
    }
}
