@extends("user.layout")
@section("title")Giỏ hàng | LDMobile @stop
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
                <i class="mb-10">* Số lượng mua tối đa là 5</i>
                <div class="col-lg-9 col-12">
                    <div class="header-cart">
                        <div class="w-5">
                            <div data-id="all" class="select-item-cart cus-checkbox cus-checkbox-checked"></div>
                        </div>
                        <div class="w-35">Chọn tất cả ({{$data['cart']['qty']}} sản phẩm)</div>
                        <div class="w-25">Giá</div>
                        <div class="w-15">Số lượng</div>
                        <div class="w-15">Thành tiền</div>
                        <div class="w-5"><span class="relative remove-all-cart"><i class="fal fa-trash-alt"></i></span></div>
                    </div>

                    {{-- danh sách sản phẩm --}}
                    <div id="lst-cart-item" class="box-shadow mb-50">
                        @foreach ($data['cart']['cart'] as $key)
                            <div data-id="{{$key['sanpham']['id']}}" class="d-flex align-items-center p-10 border-bottom">
                                {{-- custom checkbox --}}
                                <div class="w-5">
                                    <div data-id="{{$key['sanpham']['id']}}" class="select-item-cart cus-checkbox cus-checkbox-checked"></div>
                                </div>
                                {{-- sản phẩm --}}
                                <div class="w-35 d-flex">
                                    <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" class="w-30">
                                    <div class="ml-5">
                                        <a href="{{route('user/chi-tiet', ['name' => $key['sanpham']['tensp_url'], 'mausac' => $key['sanpham']['mausac_url']])}}" class="black fw-600 mb-10">{{$key['sanpham']['tensp']}}</a>
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
                                @if($key['hethang'])
                                    <div class="w-30">
                                        <div data-id="{{$key['sanpham']['id']}}" class="out-of-stock">TẠM HẾT HÀNG</div>
                                    </div>
                                @else
                                    {{-- số lượng --}}
                                    <div class="w-15 d-flex">
                                        @if (!$key['hethang'])
                                            <div class='cart-qty-input'>
                                                <button type='button' data-id="{{$key['id']}}" data-component="cart" class='update-qty minus'><i class="fas fa-minus"></i></button>
                                                <b data-id="{{$key['id']}}" class="qty-item">{{$key['sl']}}</b>
                                                <button type='button' data-id="{{$key['id']}}" data-component="cart" class='update-qty plus'><i class="fas fa-plus"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- thành tiền --}}
                                    <div class="w-15">
                                        @if (!$key['hethang'])
                                            <div data-id="{{$key['id']}}" class="provisional_item red fw-600">{{ number_format($key['thanhtien'], 0, '', '.') }}<sup>đ</sup></div>
                                        @endif
                                    </div>
                                @endif
                                {{-- xóa --}}
                                <div class="w-5">
                                    <div type="button" data-id="{{$key['id']}}" data-type="item" class="remove-cart-item fz-18"><i class="fal fa-trash-alt"></i></div>
                                </div>
                            </div>    
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    {{-- mã khuyến mãi --}}
                    <div class="p-20 box-shadow mb-20">
                        <div class="fw-600 mb-20">Mã khuyến mãi</div>
                        
                        <div id="cart-voucher">
                            {{-- đã áp dụng mã khuyên mãi --}}
                            @if (session('voucher'))
                                <?php $voucher = session('voucher') ?>
                                {{-- mã --}}
                                @include("user.content.components.voucher.apply-small-voucher")
                            {{-- chưa áp dụng mã giảm giá --}}
                            @else
                                @include("user.content.components.voucher.choose-voucher-button")
                            @endif
                        </div>
                    </div>

                    {{-- tính tiền --}}
                    <div class="box-shadow mb-20">
                        <div class="p-20 border-bottom">
                            {{-- tạm tính --}}
                            <div id="provisional-text" class="d-flex justify-content-between">
                                <div class="gray-1">Tạm tính</div>
                                <div id="provisional" class="black"></div>
                            </div>
                            {{-- mã giảm giá --}}
                            @if (session('voucher'))
                                <div id="voucher-discount-text" class="d-flex justify-content-between mt-20">
                                    <div class="gray-1">Mã giảm giá</div>
                                    <div id="voucher" class="main-color-text">-{{session('voucher')->chietkhau*100}}%</div>
                                </div>
                            @endif
                        </div>
                        
                        {{-- tổng tiền --}}
                        <div class="p-20">
                            <div class="d-flex justify-content-between">
                                <div class="gray-1">Tổng tiền</div>
                                <span id="total" class="red fz-20 fw-600"></span>
                            </div>
                        </div>
                    </div>

                    {{-- thanh toán --}}
                    <div type="button" id="checkout-page" href="{{route('user/thanh-toan')}}" class="checkout-btn">Tiến hành thanh toán</div>
                </div>
            @else
                <div class="col-lg-12">
                    <div class="box-shadow">
                        <div class="row">
                            <div class="col-lg-4 col-md-8 col-10 mx-auto">
                                <div class="pt-100 pb-100 text-center">
                                    <div class="fz-20 mb-40">Không có sản phẩm nào trong giỏ hàng.</div>
                                    <a href="{{route('user/dien-thoai')}}" class="main-btn">Tiếp tục mua hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- modal hỏi bỏ qua sản phẩm đã hết hàng --}}
<div class="modal fade" id="skip-product-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="btn-close" data-bs-dismiss="modal"></div>
                <div class="p-80">
                    <div class="mb-40 fz-18 text-center">Giỏ hàng của bạn có sản phẩm đã hết hàng. Bạn có muốn bỏ qua sản phẩm đó và thanh toán các sản phẩm còn lại không?</div>
                    <div class="d-flex justify-content-between">
                        <div class="cancel-btn w-49" data-bs-dismiss="modal">Đóng</div>
                        <div id="skip-product-btn" class="main-btn w-49">OK</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal chọn khuyến mãi --}}
@include("user.content.modal.voucher-modal")

{{-- modal xóa --}}
@include("user.content.modal.xoa-modal")
@stop