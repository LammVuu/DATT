@extends("user.layout")
@section("content")

@section("direct")SẢN PHẨM @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class="pt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {{-- thanh bộ lọc & sắp xếp --}}
                @include('user.content.sanpham.bo-loc-sap-xep')

                {{-- danh sách sản phẩm --}}
                <div class="row">
                    <?php for($i = 0; $i < 10; $i++) : ?>
                    <div class='col-lg-3 col-md-4 col-sm-6'>
                        <div class='shop-product-card box-shadow'>
                            {{-- khuyến mãi tag --}}
                            <div class='shop-promotion-tag'><span class='shop-promotion-text'>-15%</span></div>

                            {{-- thêm giỏ hàng --}}
                            <div class='shop-overlay-product'></div>
                            <a href="#" class='shop-cart-link'><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</a>
                            <a href={{route('user/chi-tiet')}} class='shop-detail-link'>Xem chi tiết</a>
                            {{-- thông tin sản phẩm --}}
                            <div>
                                <div class='pt-20 pb-20'>
                                    <img src="images/phone/iphone_11_black.jpg" class='shop-product-img-card'>
                                </div>
                                <div class='pb-20 text-center d-flex flex-column'>
                                    <b class='mb-10'>iPhone 11 PRO MAX</b>
                                    <div>
                                        <span class='font-weight-600 price-color'>36.000.000<sup>đ</sup></span>
                                        <span class='ml-5 text-strike'>39.000.000<sup>đ</sup></span>
                                    </div>
                                    <div>
                                        <div class='flex-row'>
                                            <i class="fas fa-star checked"></i>
                                            <i class="fas fa-star checked"></i>
                                            <i class="fas fa-star checked"></i>
                                            <i class="fas fa-star checked"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <span class='fz-12 ml-10'>21 đánh giá</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor ?>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.content.section.sec-logo')
@stop