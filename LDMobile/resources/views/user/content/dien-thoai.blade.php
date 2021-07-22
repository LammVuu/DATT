@extends("user.layout")
@section("content")

@section("breadcrumb")
    <a href="{{route('user/dien-thoai')}}" class="bc-item active">Điện thoại</a>
@stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class="pt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {{-- thanh bộ lọc & sắp xếp --}}
                @include("user.content.dienthoai.bo-loc-sap-xep")

                {{-- danh sách sản phẩm --}}
                <div id="lst_product" class="row">
                    @foreach ($lst_product as $key)
                        <div class='col-lg-3 col-md-4 col-sm-6'>
                            <div id="product_{{$key['id']}}" class='shop-product-card box-shadow'>
                                {{-- khuyến mãi tag --}}
                                @if($key['khuyenmai'] != 0)
                                    <div class='shop-promotion-tag'>
                                        <span class='shop-promotion-text'>{{ '-'.($key['khuyenmai']*100).'%'}}</span>
                                    </div>
                                @endif

                                {{-- thêm giỏ hàng --}}
                                <div class='shop-overlay-product'></div>
                                <div type="button" data-id="{{$key['id']}}" class='shop-cart-link'><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</div>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='shop-detail-link'>Xem chi tiết</a>
                                {{-- thông tin sản phẩm --}}
                                <div>
                                    <div class='pt-20 pb-20'>
                                        <img src="{{ $url_phone.$key['hinhanh'] }}" class='shop-product-img-card'>
                                    </div>
                                    <div class='pb-20 text-center d-flex flex-column'>
                                        {{-- tên sản phẩm --}}
                                        <b class='mb-10'>{{ $key['tensp'] }}</b>
                                        {{-- giá --}}
                                        <div>
                                            <span class='fw-600 price-color'>{{ number_format($key['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>
                                            @if ($key['khuyenmai'] != 0)
                                                <span class='ml-5 text-strike'>{{ number_format($key['gia'], 0, '', '.') }}<sup>đ</sup></span>    
                                            @endif
                                        </div>
                                        {{-- sao đánh giá --}}
                                        <div class='flex-row pt-5'>
                                            @if ($key['danhgia']['qty'] != 0)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if($key['danhgia']['star'] >= $i)
                                                    <i class="fas fa-star checked"></i>
                                                    @else
                                                    <i class="fas fa-star uncheck"></i>
                                                    @endif
                                                @endfor
                                                <span class='fz-14 ml-10'><?php echo $key['danhgia']['qty'] . ' đánh giá '?></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- modal chọn màu sản phẩm --}}
<div class="modal fade" id="choose-color-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
                <div class="p-20">
                    {{-- tên sản phẩm --}}
                    <div id="choose-color-phone-name" class="fz-20 fw-600"></div>

                    {{-- giá --}}
                    <div class="d-flex align-items-center">
                        <div id="choose-color-promotion-price" class="price-color"></div>
                        <div id="choose-color-price" class="ml-20 gray-1 text-strike"></div>
                    </div><hr>

                    {{-- chọn màu --}}
                    <div class="fw-600">Chọn màu</div>
                    <div class="mb-30">
                        <div id="phone-color" class="d-flex flex-wrap p-5"></div>
                    </div>

                    {{-- số lượng --}}
                    <div id="qty-div">
                        <div class="d-flex align-items-center mb-30">
                            <div class="fw-600 mr-20">Chọn số lượng</div>
                            <div class='cart-qty-input relative'>
                                {{-- tooltip thông báo --}}
                                <div class="tooltip-qty"></div>
                                {{-- số lượng tối đa có thể mua --}}
                                <input type="hidden" id="max-qty">
                                
                                <button type='button' data-id="color" class='update-qty minus'><i class="fas fa-minus"></i></button>
                                <b id="qty">1</b>
                                <button type='button' data-id="color" class='update-qty plus'><i class="fas fa-plus"></i></button>
                                
                            </div>
                        </div>
                    </div>

                    {{-- nút thêm giỏ hàng --}}
                    <div id="btn-add-cart" class="main-btn p-10 w-100">Thêm vào giỏ hàng</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- info modal --}}
<div class="modal fade" id="info-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-60">
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
                <div class="text-center">
                    <i class="fas fa-info-circle fz-100 main-color-text"></i>
                    <div id="info-modal-content" class="mt-40 mb-40 fz-20"></div>
                    <div type="button" id="info-modal-main-btn" class="main-btn p-10 w-100">Đóng</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast"></div>

@include('user.content.section.sec-logo')
@stop