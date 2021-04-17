@extends("user.layout")
@section("content")

@section("direct")SẢN PHẨM @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class="category-wrapper pt-50">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-lg-12">
                    {{-- thanh bộ lọc & sắp xếp --}}
                    @include('user.content.sanpham.bo-loc-sap-xep')

                    {{-- thanh kết quả tìm kiếm --}}
                    <div class="breadcrumbs-style breadcrumbs-style-1 d-md-flex justify-content-between align-items-center">
                        <div class="breadcrumb-left">
                            <p>Showing 01-09 of 17 Results</p>
                        </div>

                        {{-- tab lưới/ danh sách --}}
                        <div class="breadcrumb-right">
                            <ul class="breadcrumb-list-grid nav nav-tabs border-0" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a id="home-tab" data-bs-toggle="tab" data-bs-target="#home" href="#home" role="tab" aria-controls="home"
                                        aria-selected="true">
                                        <i class="mdi mdi-view-list"></i>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">
                                        <i class="mdi mdi-view-grid"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
  
                    </div>

                    <div class="tab-content" id="myTabContent">
                        {{-- xem dạng danh sách --}}
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <?php for($i = 0; $i < 5; $i++) : ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class='mt-30'>
                                        <div class='shop-product-card box-shadow'>
                                            {{-- khuyến mãi tag --}}
                                            <div class='shop-promotion-tag'><span class="shop-promotion-text">-15%</span></div>
                                            <div class='row'>  
                                                {{-- hình --}}
                                                <div class='col-md-2 pt-20 pr-5 pt-20 pl-5'>
                                                    <img src="images/iphone/iphone_11_black.jpg" class='shop-product-img-card' style='width: 90%'>
                                                </div>
                                                {{-- thông tin sản phẩm --}}
                                                <div class='col-md-10 pt-20 pb-20'>
                                                    <div class='d-flex flex-column'>
                                                        <h4><b>iPhone 11 PRO MAX</b></h4>
                                                        <div class='d-flex align-items-center mt-20'>
                                                            <b class='price-color'>36.000.000 VND</b>
                                                            <div class='ml-10 text-strike'></div>
                                                        </div>
                                                        <div class='flex-row'>
                                                            <i class="fas fa-star checked"></i>
                                                            <i class="fas fa-star checked"></i>
                                                            <i class="fas fa-star checked"></i>
                                                            <i class="fas fa-star checked"></i>
                                                            <i class="fas fa-star uncheck"></i>&nbsp;
                                                            <i class='vote-text'>21 đánh giá</i>
                                                        </div>

                                                        <div class='d-flex flex-column mt-20'>
                                                            <a href={{route('user/chi-tiet')}} class='shop-detail-link-2 box-shadow'>Xem chi tiết</a>
                                                            <a href="#" class='shop-cart-link-2'><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor ?>
                        </div>

                        {{-- xem dạng lưới --}}
                        <div class="tab-pane fade show active" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">
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
                                                <img src="images/iphone/iphone_11_black.jpg" class='shop-product-img-card'>
                                            </div>
                                            <div class='pb-20 text-center d-flex flex-column'>
                                                <b class='mb-10'>iPhone 11 PRO MAX</b>
                                                <span><b class='price-color'>36.000.000 VND</b>&nbsp; <strike>39.000.000 VND </strike></span>
                                                <span>
                                                    <div class='flex-row'>
                                                        <i class="fas fa-star checked"></i>
                                                        <i class="fas fa-star checked"></i>
                                                        <i class="fas fa-star checked"></i>
                                                        <i class="fas fa-star checked"></i>
                                                        <i class="fas fa-star uncheck"></i>&nbsp;
                                                        <i class='shop-vote-text'>21 đánh giá</i>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endfor ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.content.section.sec-logo')
@include('user.content.section.sec-dang-ky')
@stop