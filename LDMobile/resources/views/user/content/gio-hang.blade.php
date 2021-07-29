@extends("user.layout")
@section("content")

@section("breadcrumb")
    <a href="{{route('user/gio-hang')}}" class="bc-item active">Giỏ hàng</a>
@stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            @if ($data['cart']['qty'] != 0)
                <div class="fz-26 fw-600 mb-20">Giỏ hàng</div>
                <div class="col-lg-9 col-md-12">
                    <div class="header-cart">
                        <div class="w-40">Sản phẩm ({{$data['cart']['qty']}})</div>
                        <div class="w-25">Giá</div>
                        <div class="w-15">Số lượng</div>
                        <div class="w-15">Thành tiền</div>
                        <div class="w-5"><span class="relative remove-all-cart"><i class="fal fa-trash-alt"></i></span></div>
                    </div>

                    {{-- danh sách sản phẩm --}}
                    <div id="lst-cart-item" class="box-shadow mb-50">
                        @foreach ($data['cart']['cart'] as $key)
                        <div data-id="{{$key['sanpham']['id']}}" class="d-flex align-items-center p-10 border">
                            {{-- sản phẩm --}}
                            <div class="w-40 d-flex">
                                <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" class="w-30">
                                <div>
                                    <a href="{{route('user/chi-tiet', ['name' => $key['sanpham']['tensp_url']])}}" class="black fw-600 mb-10">{{$key['sanpham']['tensp']}}</a>
                                    <div class="fz-14">Dung lượng: {{$key['sanpham']['dungluong']}}</div>
                                    <div class="fz-14">Màu sắc: {{$key['sanpham']['mausac']}}</div>
                                </div>
                            </div>
                            {{-- giá --}}
                            <div class="w-25 d-flex align-items-center">
                                <div class="ml-10">
                                    <div class="fw-600">{{ number_format($key['sanpham']['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></div>
                                    {{-- hết hạn khuyến mãi --}}
                                    @if ($key['sanpham']['khuyenmai'] != 0)
                                    <div class="fz-14 text-strike gray-1">{{ number_format($key['sanpham']['gia'], 0, '', '.') }}<sup>đ</sup></div>    
                                    @endif
                                </div>
                            </div>
                            {{-- số lượng --}}
                            <div class="w-15 d-flex">
                                <div class='cart-qty-input'>
                                    <button type='button' data-id="cart_{{$key['id']}}" class='update-qty minus'><i class="fas fa-minus"></i></button>
                                    <b id="qty_{{$key['id']}}">{{$key['sl']}}</b>
                                    <button type='button' data-id="cart_{{$key['id']}}" class='update-qty plus'><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            {{-- thành tiền --}}
                            <div class="w-15">
                                <div id="provisional_{{$key['id']}}" class="red fw-600">{{ number_format($key['thanhtien'], 0, '', '.') }}<sup>đ</sup></div>
                            </div>
                            {{-- xóa --}}
                            <div class="w-5">
                                <div type="button" data-id="{{$key['id']}}" data-type="item" class="remove-cart-item fz-18"><i class="fal fa-trash-alt"></i></div>
                            </div>
                        </div>    
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    {{-- mã khuyến mãi --}}
                    <div class="p-20 box-shadow mb-20">
                        <div class="fw-600 mb-20">Mã khuyến mãi</div>
                        
                        {{-- đã áp dụng mã khuyên mãi --}}
                        @if (session('voucher'))
                            <?php $voucher = session('voucher') ?>
                            <div class="d-flex justify-content-center align-items-center">
                                {{-- mã --}}
                                <div class='account-voucher'>
                                    {{-- số phần trăm giảm --}}
                                    <div class='voucher-left-small w-20 p-30'>
                                        <div class='voucher-left-small-content fz-18'>-{{$voucher->chietkhau*100}}%</div>
                                    </div>
                                    {{-- nội dung --}}
                                    <div class='voucher-right-small w-80 d-flex align-items-center justify-content-between p-10'>
                                        {{-- icon xem chi tiết --}}
                                        <b class="fz-14">{{$voucher->code}}</b>
                                        <div class="relative promotion-info-icon">
                                            <i class="fal fa-info-circle main-color-text fz-20"></i>
                                            <div class='voucher-content box-shadow p-20 '>
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
                        {{-- chưa áp dụng mã giảm giá --}}
                        @else
                            <span class="pointer-cs main-color-text" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                                <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
                            </span>
                        @endif
                    </div>

                    {{-- tính tiền --}}
                    <div class="box-shadow mb-20">
                        <div class="p-20 border">
                            {{-- tạm tính --}}
                            <div class="d-flex justify-content-between">
                                <div class="gray-1">Tạm tính</div>
                                <div id="provisional" data-provisional="{{$data['cart']['total']}}"
                                    class="black">{{ number_format($data['cart']['total'], 0, '', '.') }}<sup>đ</sup></div>
                            </div>
                            {{-- mã giảm giá --}}
                            @if (session('voucher'))
                                <div class="d-flex justify-content-between mt-20">
                                    <div class="gray-1">Mã giảm giá</div>
                                    <div id="voucher" data-voucher="{{session('voucher')->chietkhau}}" class="main-color-text">-{{session('voucher')->chietkhau*100}}%</div>
                                </div>
                            @endif
                        </div>
                        
                        {{-- tổng tiền --}}
                        <div class="p-20 border">
                            <div class="d-flex justify-content-between">
                                <div class="gray-1">Tổng tiền</div>
                                <span data-voucher="{{session('voucher') ? "1" : "0"}}" id="total" class="red fz-20 fw-600"></span>
                            </div>
                        </div>
                    </div>

                    {{-- thanh toán --}}
                    <a href="{{route('user/thanh-toan')}}" class="checkout-btn p-10">Tiến hành thanh toán</a>
                </div>
            @else
            <div class="col-lg-12 box-shadow">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="pt-100 pb-100 text-center">
                            <div class="fz-20 mb-40">Không có sản phẩm nào trong giỏ hàng cả.</div>
                            <a href="{{route('user/dien-thoai')}}" class="main-btn p-10">Tiếp tục mua hàng</a>
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

{{-- modal xóa --}}
@include("user.content.modal.xoa-modal")
@stop