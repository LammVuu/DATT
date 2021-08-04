@extends("user.layout")
@section("title")Thanh toán | LDMobile @stop
@section("content")
@section("breadcrumb")
    <a href="{{route('user/thanh-toan')}}" class="bc-item active">Thanh toán</a>
@stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            {{-- chưa có giỏ hàng --}}
            @if ($cartRequired == 'true')
                <div class="col-lg-12 box-shadow">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="pt-50 pb-50 text-center">
                                <div class="mb-20 fz-20">Bạn chưa có sản phẩm để thanh toán.</div>
                                <a href="{{route('user/dien-thoai')}}" class="main-btn p-10">Tiếp tục mua sắm</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class='col-lg-8 col-md-12'>
                    <h3>Thông tin mua hàng</h3>
                    <hr>
                    <div class='pb-20'>
                        {{-- cách thức nhận hàng --}}
                        <div class='row'>
                            <div class='col-md-10 col-sm-12 mb-20'>
                                <label for="CachThucNhanHang" class='form-label fw-600'>Chọn cách thức nhận hàng</label>
                                <div id='CachThucNhanHang' class='d-flex'>
                                    <div class='mr-20'>
                                        <input type="radio" name='receive-method' id="TaiNha" value='Giao hàng tận nơi' checked>
                                        <label for="TaiNha">Giao hàng tận nơi</label>
                                    </div>
                                    <div>
                                        <input type="radio" name='receive-method' id='TaiCuaHang' value='Nhận tại cửa hàng'>
                                        <label for="TaiCuaHang">Nhận tại cửa hàng</label>
                                    </div>
                                </div>

                                {{-- giao hàng tận nơi --}}
                                <div data-flag="{{$defaultAdr != null ? "1" : "0"}}" class='atHome p-20 mt-10'>
                                    <div id="delivery-address">
                                        @if ($defaultAdr != null)
                                            <div id="address-{{$defaultAdr->id}}" data-default="true" class="col-md-12 white-bg p-20 border">
                                                {{-- họ tên --}}
                                                <div class="d-flex justify-content-between pb-10">
                                                    <div class="d-flex">
                                                        <b id="adr-fullname-{{$defaultAdr->id}}" class="text-uppercase">{{ $defaultAdr->hoten}}</b>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div id='btn-change-address-delivery' class="pointer-cs main-color-text">Thay đổi</div>
                                                    </div>
                                                </div>
                                    
                                                {{-- địa chỉ --}}
                                                <div class="d-flex mb-5">
                                                    <div class="gray-1">Địa chỉ:</div>
                                                    <div class="ml-5 black">{{ $defaultAdr->diachi.', '.$defaultAdr->phuongxa.', '.$defaultAdr->quanhuyen.', '.$defaultAdr->tinhthanh }}</div>
                                                </div>
                                    
                                                {{-- SĐT --}}
                                                <div class="d-flex">
                                                    <div class="gray-1">Điện thoại:</div>
                                                    <div id="adr-tel-{{$defaultAdr->id}}" class="ml-5 black">{{ $defaultAdr->sdt}}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center white-bg p-20 border">
                                                <div class="d-flex justify-content-center">
                                                    Bạn chưa có địa chỉ giao hàng.
                                                    <div id="new-address-show" data-default="true" type="button" class="main-color-text ml-5">Thêm địa chỉ</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- nhận tại cửa hàng --}}
                                <div class='atStore p-20 mt-10'>
                                    <div class='row'>
                                        <div class='col-lg-6'>
                                            <div class="select">
                                                <div id='area-selected' class="select-selected">
                                                    <div id='area-name'>Chọn khu vực</div>
                                                    <i class="far fa-chevron-down fz-14"></i>
                                                </div>

                                                <div id='area-box' class="select-box">
                                                    {{-- option --}}
                                                    <div class="select-option">
                                                        @foreach ($lstKhuVuc as $lst)
                                                            <div class="option-area select-single-option" data-area='{{ $lst['id'] }}'>{{ $lst['tentt'] }}</div>
                                                        @endforeach 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-branch">
                                                @foreach ($lstChiNhanh as $lst)
                                                    <div class="single-branch" data-area='{{ $lst['id_tt'] }}'>
                                                        <input type="radio" name='branch' id='{{ 'branch-'. $lst['id'] }}' value='{{ $lst['id'] }}'>
        
                                                        <label for="{{ 'branch-' . $lst['id'] }}" class="branch-text">
                                                            <div class="d-flex flex-column">
                                                                <div class="d-flex">
                                                                    <i class="fas fa-store mr-10"></i>
                                                                    {{ $lst['diachi'] }}
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-6 white-bg relative p-0 none-dp">
                                            <div class="info-qty-in-stock">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Phương thức thanh toán</h3>
                    <hr>
                    <div class='pb-20'>
                        {{-- phương thức thanh toán --}}
                        <div class='mb-3 d-flex align-items-center'>
                            <input type="radio" name='payment-method' id="cash" checked value="cash">
                            <label for="cash">Thanh toán khi nhận hàng</label>
                        </div>
                        <div class='mb-3'>
                            <input type="radio" name='payment-method' id="zalopay" value='zalopay'>
                            <label for="zalopay">Thanh toán online</label>
                            <div class="ml-20 mt-5">
                                <img src="images/logo/zalopay-logo.png" alt="" class="ml-10 w-10">
                                <span class="fz-14 ml-10 mr-10">Cổng thanh toán ZaloPay</span>
                                <img src="images/icon/atm-card-icon.png" alt="" class='checkout-payment-icon'>
                                <img src="images/icon/visa-card-icon.png" alt="" class='checkout-payment-icon'>
                                <img src="images/icon/master-card-icon.png" alt="" class='checkout-payment-icon'>
                                <img src="images/icon/jcb-card-icon.png" alt="" class='checkout-payment-icon'>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class='col-md-8 mx-auto pt-20 pb-20'>
                        <form id='checkout-form' action={{route('user/checkout')}} method="POST">
                            @csrf
                            <input type="hidden" name="paymentMethod" id="paymentMethod">
                            <input type="hidden" name="receciveMethod" id="receciveMethod">
                            <input type="hidden" name="id_tk_dc" id="id_tk_dc" value="{{$defaultAdr != null ? $defaultAdr->id : null}}">
                            <input type="hidden" name="id_cn" id="id_cn">
                            <input type="hidden" name="cartTotal" id="cartTotal">
                            <input type="hidden" name="id_vc" id="id_vc" value="{{session('voucher') ? session('voucher')->id : null}}">

                            <div id='btn-confirm-checkout' type="button" class="checkout-btn w-100 p-10">ĐẶT HÀNG</div>
                            <div class="text-center pt-5">(Vui lòng kiểm tra lại đơn hàng trước khi đặt mua)</div>
                        </form>
                    </div>
                </div>

                {{-- giỏ hàng --}}
                <div class='col-lg-4 col-md-10'>
                    <h3>Giỏ hàng</h3>
                    <hr>
                    <div class='pt-20 pb-20'>
                        <div class='checkout-cart-box box-shadow'>
                            <div class='d-flex justify-content-end white-bg p-10'>
                                <div href="#collapseExample" class='checkout-btn-collapse-cart' data-bs-toggle="collapse"
                                    href="#collapseExample" role="button" aria-expanded="true"
                                    aria-controls="collapseExample"><i class="fas fa-chevron-up"></i></div>
                            </div>
                            <div class="collapse show" id="collapseExample">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class='p-0'></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="{{route('user/gio-hang')}}"><i class="fas fa-chevron-left mr-5"></i>Chỉnh sửa giỏ hàng</a>
                                            </td>
                                        </tr>
                                        {{-- sản phẩm trong giỏ hàng --}}
                                        @foreach ($data['cart']['cart'] as $key)
                                            <tr>
                                                <td>
                                                    <div class='d-flex flex-row align-items-center justify-content-between'>
                                                        <div class='d-flex'>
                                                            <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" width="90px">
                                                            <div class='d-flex flex-column ml-5 fz-14'>
                                                                <b>{{$key['sanpham']['tensp']}}</b>
                                                                <span>Dung lượng: {{$key['sanpham']['dungluong']}}</span>
                                                                <span>Màu sắc: {{$key['sanpham']['mausac']}}</span>
                                                                <b>Số lượng: {{$key['sl']}}</b>
                                                            </div>
                                                        </div>
                                                        <b class='d-flex align-items-center justify-content-end red'>{{number_format($key['thanhtien'], 0, '', '.')}}<sup>đ</sup></b>
                                                    </div>
                                                </td>
                                            </tr>    
                                        @endforeach
                                        {{-- mã giảm giá --}}
                                        @if (session('voucher'))
                                            <?php $voucher = session('voucher') ?>
                                            <tr>
                                                <td class="p-0 d-flex">
                                                    <div class='w-30 p-10 bg-gray-4 d-flex justify-content-center align-items-center'>
                                                        <b>Mã giảm giá</b>
                                                    </div>
                                                    <div class="w-70 p-10 d-flex justify-content-center">
                                                        {{-- mã --}}
                                                        <div class="w-97">
                                                            <div class='account-voucher'>
                                                                {{-- số phần trăm giảm --}}
                                                                <div class='voucher-left-small w-20 p-30'>
                                                                    <div class='voucher-left-small-content fz-18'>-{{$voucher->chietkhau*100}}%</div>
                                                                </div>
                                                                {{-- nội dung --}}
                                                                <div class='voucher-right-small w-80 d-flex align-items-center justify-content-between p-10'>
                                                                    {{-- icon xem chi tiết --}}
                                                                    <b class="fz-14">{{$voucher->code}}</b>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="relative promotion-info-icon mr-10">
                                                                            <i class="fal fa-info-circle main-color-text fz-20"></i>
                                                                            <div class='voucher-content box-shadow p-20'>
                                                                                <table class='table'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class='w-40'>Mã</td>
                                                                                            <td><b>{{$voucher->code}}</b></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="w-40">Nội dung</td>
                                                                                            <td>{{$voucher->noidung}}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="2" class='w-40'>
                                                                                                <div class='d-flex flex-column'>
                                                                                                    <span>Điều kiện</span>
                                                                                                    @if ($voucher->dieukien != 0)
                                                                                                    <ul class='mt-10'>
                                                                                                        <li>Áp dụng cho đơn hàng từ {{number_format($voucher->dieukien, 0, '', '.')}}<sup>đ</sup></li>
                                                                                                    </ul>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class='w-40'>Hạn sử dụng</td>
                                                                                            <td>{{$voucher->ngayketthuc}}</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div data-id="{{$voucher->id}}" class="use-voucher-btn main-btn p-5">Bỏ chọn</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <span class="pointer-cs main-color-text p-10" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                                                        <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- tính tiền --}}
                                        <tr>
                                            <td>
                                                <div class='p-10 d-flex flex-column'>
                                                    <div class='d-flex justify-content-between'>
                                                        <span id="provisional" data-provisional="{{$data['cart']['total']}}">Tạm tính ({{$data['cart']['qty']}} sản phẩm):</span>
                                                        <b>{{number_format($data['cart']['total'], 0, '', '.')}}<sup>đ</sup></b>
                                                    </div>
                                                    @if (session('voucher'))
                                                        <div class='d-flex justify-content-between mt-10'>
                                                            <span>Mã giảm giá:</span>
                                                            <b id="voucher" data-voucher="{{session('voucher')->chietkhau}}" class='main-color-text'>-{{session('voucher')->chietkhau*100}}%</b>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- tổng tiền --}}
                                        <tr>
                                            <td>
                                                <div class='d-flex align-items-center justify-content-between p-10'>
                                                    <b>Tổng tiền:</b>
                                                    <b data-voucher="{{session('voucher') ? "1" : "0"}}" id="total" class='red fz-22'></b>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- modal chọn khuyến mãi --}}
@include("user.content.modal.voucher-modal")

{{-- modal thông báo --}}
@include("user.content.modal.thongbao-modal")

{{-- modal thêm/sửa địa chỉ --}}
@include("user.content.modal.dia-chi-modal")

{{-- modal xóa --}}
@include("user.content.modal.xoa-modal")
@stop
