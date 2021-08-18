@extends("user.layout")
@section("title")Điện thoại | LDMobile @stop
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
                <div id="lst_product" class="row" data-row="10">
                    {{-- ko có queryString --}}
                    @if (!parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))
                        @foreach ($lst_product as $key)
                            <div class='col-lg-3 col-md-4 col-sm-6 col-6'>
                                <div id="product_{{$key['id']}}" class='shop-product-card'>
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
                                                <span class='fw-600 red'>{{ number_format($key['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- modal chọn màu sản phẩm --}}
@include("user.content.modal.chon-mau-sac-modal");

<div id="toast"></div>

@include('user.content.section.sec-logo')
@stop