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
                <div class="fz-26 font-weight-600 mb-20">Giỏ hàng</div>
                <div class="col-lg-9 col-md-12">
                    <div class="header-cart">
                        <div class="w-40">Sản phẩm ({{$data['cart']['qty']}})</div>
                        <div class="w-25">Giá</div>
                        <div class="w-15">Số lượng</div>
                        <div class="w-15">Thành tiền</div>
                        <div class="w-5"><span class="relative remove-all-cart"><i class="fal fa-trash-alt"></i></span></div>
                    </div>

                    {{-- danh sách sản phẩm --}}
                    <div class="box-shadow mb-50">
                        @foreach ($data['cart']['cart'] as $key)
                        <div class="d-flex align-items-center p-10 border">
                            {{-- sản phẩm --}}
                            <div class="w-40 d-flex">
                                <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" class="w-30">
                                <div>
                                    <div class="font-weight-600 mb-10">{{$key['sanpham']['tensp']}}</div>
                                    <div class="fz-14">Dung lượng: {{$key['sanpham']['dungluong']}}</div>
                                    <div class="fz-14">Màu sắc: {{$key['sanpham']['mausac']}}</div>
                                </div>
                            </div>
                            {{-- giá --}}
                            <div class="w-25 d-flex align-items-center">
                                <i class="fal fa-info-circle"></i>
                                <div class="ml-10">
                                    <div class="font-weight-600">{{ number_format($key['sanpham']['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></div>
                                    <div class="fz-14 text-strike gray-1">{{ number_format($key['sanpham']['gia'], 0, '', '.') }}<sup>đ</sup></div>
                                </div>
                            </div>
                            {{-- số lượng --}}
                            <div class="w-15 d-flex">
                                <div class='cart-qty-input'>
                                    <button type='button' data-id="cart_{{$key['sanpham']['id']}}" class='update-qty plus'><i class="fas fa-plus"></i></button>
                                    <b id="qty_{{$key['sanpham']['id']}}">{{$key['sl']}}</b>
                                    <button type='button' data-id="cart_{{$key['sanpham']['id']}}" class='update-qty minus'><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            {{-- thành tiền --}}
                            <div class="w-15">
                                <div id="provisional_{{$key['sanpham']['id']}}" class="price-color font-weight-600">{{ number_format($key['thanhtien'], 0, '', '.') }}<sup>đ</sup></div>
                            </div>
                            {{-- xóa --}}
                            <div class="w-5">
                                <div type="button" data-id="{{$key['sanpham']['id']}}" data-type="item" class="remove-cart-item fz-18"><i class="fal fa-trash-alt"></i></div>
                            </div>
                        </div>    
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    {{-- mã khuyến mãi --}}
                    <div class="p-20 box-shadow mb-20">
                        <div class="font-weight-600 mb-20">Mã khuyến mãi</div>
                        <span class="pointer-cs main-color-text" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                            <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
                        </span>
                    </div>

                    {{-- tính tiền --}}
                    <div class="box-shadow mb-20">
                        <div class="p-20 border">
                            <div class="d-flex justify-content-between">
                                <div class="gray-1">Tạm tính</div>
                                <div id="provisional" class="black">{{ number_format($data['cart']['total'], 0, '', '.') }}<sup>đ</sup></div>
                            </div>
                        </div>
                        <div class="p-20 border">
                            <div class="d-flex justify-content-between">
                                <div class="gray-1">Tổng tiền</div>
                                <span id="total" class="price-color fz-20 font-weight-600">{{ number_format($data['cart']['total'], 0, '', '.') }}<sup>đ</sup></span>
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

{{-- mã giảm giá --}}
<!--<tr>
    <td class="p-0 d-flex">
        <div class='w-30 p-20 bg-gray-4 d-flex justify-content-center align-items-center'>
            <b></i>Mã khuyến mãi</b>
        </div>
        <div class="w-70 p-10 d-flex justify-content-center align-items-center">
            {{-- mã --}}
            <div class="w-70">
                <div class='account-voucher'>
                    {{-- số phần trăm giảm --}}
                    <div class='voucher-left-small w-20 p-30'>
                        <div class='voucher-left-small-content fz-18'>-10%</div>
                    </div>
                    {{-- nội dung --}}
                    <div class='voucher-right-small w-80 d-flex align-items-center justify-content-between p-10'>
                        {{-- icon xem chi tiết --}}
                        <div>Giảm 10%...</div>
                        <div class="relative promotion-info-icon">
                            <i class="fal fa-info-circle main-color-text fz-20"></i>
                            <div class='voucher-content box-shadow p-20 '>
                                <table class='table'>
                                    <tbody>
                                        <tr>
                                            <td class='account-td-40'>Mã</td>
                                            <td><b>ABCDEF</b></td>
                                        </tr>
                                        <tr>
                                            <td class='account-td-40'>Hạn sử dụng</td>
                                            <td>31/12/2021</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class='account-td-40'>
                                                <div class='d-flex flex-column'>
                                                    <span>Điều kiện:</span>
                                                    <ul class='mt-10'>
                                                        <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="main-btn p-5">Bỏ chọn</div>
                    </div>
                </div>
            </div>
        
            <span class="pointer-cs main-color-text" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
            </span>
        </div>
    </td>
</tr>-->

{{-- modal chọn khuyến mãi --}}
<div class="modal fade" id="modal-promotion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="fz-20 font-weight-600">Mã khuyến mãi của tôi</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="cart-list-pro pl-50 pr-50 mt-20 mb-20">
                    @if (count($data['lst_voucher']) != 0)
                        @foreach ($data['lst_voucher'] as $key)
                        <div class="pb-30">
                            <div class='account-voucher'>
                                {{-- số phần trăm giảm --}}
                                <div class='voucher-left w-20 p-70'>
                                    <div class='voucher-left-content fz-40'>-10%</div>
                                </div>
                                {{-- nội dung --}}
                                <div class='voucher-right w-80'>
                                    <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                        {{-- icon xem chi tiết --}}
                                        <div class="d-flex justify-content-end">
                                            <div class="relative promotion-info-icon">
                                                <i class="fal fa-info-circle fz-20"></i>
                                                <div class='voucher-content box-shadow p-20 '>
                                                    <table class='table'>
                                                        <tbody>
                                                            <tr>
                                                                <td class='account-td-40'>Mã</td>
                                                                <td><b>ABCDEF</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td class='account-td-40'>Hạn sử dụng</td>
                                                                <td>31/12/2021</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class='account-td-40'>
                                                                    <div class='d-flex flex-column'>
                                                                        <span>Điều kiện:</span>
                                                                        <ul class='mt-10'>
                                                                            <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- nội dung --}}
                                        <div class="flex-fill">
                                            <span>Áp dụng cho đơn hàng từ 5.000.000 VND</span>
                                        </div>
                                        {{-- hạn sử dụng --}}
                                        <div class="d-flex justify-content-between">
                                            <span class="d-flex align-items-end">HSD: 31/12/2021</span>
                                            <div class="main-btn p-5">Áp dụng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center">Bạn chưa có mã khuyến mãi nào.</div>
                    @endif
                    <!--<div class="pb-30">
                        <div class='account-voucher'>
                            {{-- số phần trăm giảm --}}
                            <div class='dis-voucher-left w-20 p-70'>
                                <div class='dis-voucher-left-content fz-40'>-10%</div>
                            </div>
                            {{-- nội dung --}}
                            <div class='dis-voucher-right w-80'>
                                <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                    {{-- icon xem chi tiết --}}
                                    <div class="d-flex justify-content-end">
                                        <div class="relative dis-promotion-info-icon">
                                            <i class="fal fa-info-circle fz-20"></i>
                                            <div class='voucher-content box-shadow p-20 '>
                                                <table class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='account-td-40'>Mã</td>
                                                            <td><b>ABCDEF</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='account-td-40'>Hạn sử dụng</td>
                                                            <td>31/12/2021</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class='account-td-40'>
                                                                <div class='d-flex flex-column'>
                                                                    <span>Điều kiện:</span>
                                                                    <ul class='mt-10'>
                                                                        <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- nội dung --}}
                                    <div class="flex-fill">
                                        <span>Áp dụng cho đơn hàng từ 5.000.000 VND</span>
                                    </div>
                                    {{-- hạn sử dụng --}}
                                    <div class="d-flex justify-content-between">
                                        <span class="d-flex align-items-end">HSD: 31/12/2021</span>
                                        <div class="dis-condition-tag">Chưa thỏa điều kiện</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal xóa sản phẩm trong giỏ hàng --}}
<div class="modal fade" id="remove-cart-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
                <div class="p-50">
                    <div id="remove-cart-title" class="fz-20"></div>
                    <div class="mt-30 d-flex justify-content-between">
                        <div class="cancel-btn p-10 w-48" data-bs-dismiss="modal">Hủy</div>
                        <div class="btn-remove-cart checkout-btn p-10 w-48">Xóa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop