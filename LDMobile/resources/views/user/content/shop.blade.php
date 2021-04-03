@extends("user/layout")
@section("content")
<section class="breadcrumbs-wrapper pt-50 pb-50 bg-primary-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumbs-style breadcrumbs-style-1 d-md-flex justify-content-between align-items-center">
                    <div class="breadcrumb-left">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Category</li>
                        </ol>
                    </div>
                    <div class="breadcrumb-right">
                        <h5 class="heading-5 font-weight-500">Category</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="category-wrapper pt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="filter-style-1 mt-0">
                    <div class="filter-title">
                        <h4 class="title">Filter</h4>
                    </div>
                    <div class="filter-btn">
                        <a class="main-btn primary-btn" href="javascript:void(0)">Reset</a>
                    </div>
                </div>
                <div class="filter-style-2">
                    <div class="filter-title">
                        <a class="title" data-toggle="collapse" href="#pricingOne" role="button" aria-expanded="false">
                            Pricing Range
                        </a>
                    </div>
                    <div class="collapse show" id="pricingOne">
                        <div class="price-range">
                            <div class="price-amount">
                                <div class="amount-input">
                                    <label>Minimum Price</label>
                                    <input type="text" id="minAmount">
                                </div>
                                <div class="amount-input">
                                    <label>Maximum Price</label>
                                    <input type="text" id="maxAmount">
                                </div>
                            </div>
                            <div id="slider-range"
                                class="slider-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"
                                    style="left: 0%; width: 100%;"></div><span tabindex="0"
                                    class="ui-slider-handle ui-corner-all ui-state-default"
                                    style="left: 0%;"></span><span tabindex="0"
                                    class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-style-3">
                    <div class="filter-title">
                        <a class="title" data-toggle="collapse" href="#type" role="button" aria-expanded="false">
                            Type
                        </a>
                    </div>
                    <div class="collapse show" id="type">
                        <div class="filter-type">
                            <ul>
                                <li>
                                    <div class="type-check">
                                        <input type="checkbox" id="type-1">
                                        <label for="type-1"><span></span> Standalone</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="type-check">
                                        <input type="checkbox" id="type-2">
                                        <label for="type-2"><span></span> Mobile</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="type-check">
                                        <input type="checkbox" id="type-3">
                                        <label for="type-3"><span></span> Tethered</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="filter-style-4">
                    <div class="filter-title">
                        <a class="title" data-toggle="collapse" href="#size" role="button" aria-expanded="false">
                            Select Size
                        </a>
                    </div>
                    <div class="collapse show" id="size">
                        <div class="filter-size">
                            <ul>
                                <li>XS</li>
                                <li>S</li>
                                <li class="active">M</li>
                                <li>LG</li>
                                <li>XL</li>
                                <li>XXL</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="filter-style-7">
                    <div class="filter-title">
                        <a class="title" data-toggle="collapse" href="#color" role="button" aria-expanded="false">
                            Select Size
                        </a>
                    </div>
                    <div class="collapse show" id="color">
                        <div class="filter-color">
                            <ul>
                                <li>
                                    <div class="color-check">
                                        <p><span style="background-color: #00C2FE;"></span> <strong>Blue</strong></p>
                                        <input type="checkbox" id="color-1">
                                        <label for="color-1"><span></span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="color-check">
                                        <p><span style="background-color: #E14C7B;"></span> <strong>Red</strong></p>
                                        <input type="checkbox" id="color-2">
                                        <label for="color-2"><span></span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="color-check">
                                        <p><span style="background-color: #7CB637;"></span> <strong>Green</strong></p>
                                        <input type="checkbox" id="color-3">
                                        <label for="color-3"><span></span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="color-check">
                                        <p><span style="background-color: #161359;"></span> <strong>Dark</strong></p>
                                        <input type="checkbox" id="color-4">
                                        <label for="color-4"><span></span></label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div
                            class="breadcrumbs-style breadcrumbs-style-1 d-md-flex justify-content-between align-items-center">
                            <div class="breadcrumb-left">
                                <p>Showing 01-09 of 17 Results</p>
                            </div>
                            <div class="breadcrumb-right">
                                <ul class="breadcrumb-list-grid nav nav-tabs border-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                                            aria-selected="true">
                                            <i class="mdi mdi-view-list"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                            aria-controls="profile" aria-selected="false">
                                            <i class="mdi mdi-view-grid"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-style-7 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 480px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 240px;">
                                                                <img src="images/product-4/product-1.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 240px;">
                                                                <img src="images/product-4/product-2.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <ul class="product-meta">
                                                    <li>
                                                        <a class="add-wishlist" href="javascript:void(0)">
                                                            <i class="mdi mdi-heart-outline"></i>
                                                            Add to Favorite
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <span><i class="mdi mdi-star"></i> 4.5/5</span>
                                                    </li>
                                                </ul>
                                                <h4 class="title"><a href="product-details-page.html">Metro 38 Date</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <span class="price">$ 399</span>
                                                <a href="javascript:void(0)" class="main-btn primary-btn">
                                                    <img src="images/icon-svg/cart-4.svg" alt="">
                                                    Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="product-style-7 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 480px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 240px;">
                                                                <img src="images/product-4/product-3.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 240px;">
                                                                <img src="images/product-4/product-4.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <ul class="product-meta">
                                                    <li>
                                                        <a class="add-wishlist" href="javascript:void(0)">
                                                            <i class="mdi mdi-heart-outline"></i>
                                                            Add to Favorite
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <span><i class="mdi mdi-star"></i> 4.5/5</span>
                                                    </li>
                                                </ul>
                                                <h4 class="title"><a href="product-details-page.html">Man's Shoe</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <span class="price">$ 399</span>
                                                <a href="javascript:void(0)" class="main-btn primary-btn">
                                                    <img src="images/icon-svg/cart-4.svg" alt="">
                                                    Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="product-style-7 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 480px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 240px;">
                                                                <img src="images/product-4/product-5.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 240px;">
                                                                <img src="images/product-4/product-6.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <ul class="product-meta">
                                                    <li>
                                                        <a class="add-wishlist" href="javascript:void(0)">
                                                            <i class="mdi mdi-heart-outline"></i>
                                                            Add to Favorite
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <span><i class="mdi mdi-star"></i> 4.5/5</span>
                                                    </li>
                                                </ul>
                                                <h4 class="title"><a href="product-details-page.html">T Shirt 23</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <span class="price">$ 399</span>
                                                <a href="javascript:void(0)" class="main-btn primary-btn">
                                                    <img src="images/icon-svg/cart-4.svg" alt="">
                                                    Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="product-style-1 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 484px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 242px;">
                                                                <img src="images/product-1/product-1.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 242px;">
                                                                <img src="images/product-1/product-2.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                                <a class="add-wishlist" href="javascript:void(0)">
                                                    <i class="mdi mdi-heart-outline"></i>
                                                </a>
                                            </div>
                                            <div class="product-content text-center">
                                                <h4 class="title"><a href="product-details-page.html">Metro 38 Date</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <a href="javascript:void(0)" class="main-btn secondary-1-btn">
                                                    <img src="images/icon-svg/cart-7.svg" alt="">
                                                    $ 399
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="product-style-1 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 484px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 242px;">
                                                                <img src="images/product-1/product-5.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 242px;">
                                                                <img src="images/product-1/product-6.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                                <a class="add-wishlist" href="javascript:void(0)">
                                                    <i class="mdi mdi-heart-outline"></i>
                                                </a>
                                            </div>
                                            <div class="product-content text-center">
                                                <h4 class="title"><a href="product-details-page.html">Lady Shoe</a></h4>
                                                <p>Reference 1102</p>
                                                <a href="javascript:void(0)" class="main-btn secondary-1-btn">
                                                    <img src="images/icon-svg/cart-7.svg" alt="">
                                                    $ 399
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="product-style-1 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 484px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 242px;">
                                                                <img src="images/product-1/product-3.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 242px;">
                                                                <img src="images/product-1/product-4.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                                <a class="add-wishlist" href="javascript:void(0)">
                                                    <i class="mdi mdi-heart-outline"></i>
                                                </a>
                                            </div>
                                            <div class="product-content text-center">
                                                <h4 class="title"><a href="product-details-page.html">Casio 380 Date</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <a href="javascript:void(0)" class="main-btn secondary-1-btn">
                                                    <img src="images/icon-svg/cart-7.svg" alt="">
                                                    $ 399
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="product-style-1 mt-30">
                                            <div class="product-image">
                                                <div class="product-active slick-initialized slick-slider"><span
                                                        class="prev slick-arrow slick-disabled" aria-disabled="true"
                                                        style=""><i class="mdi mdi-chevron-left"></i></span>
                                                    <div class="slick-list draggable">
                                                        <div class="slick-track"
                                                            style="opacity: 1; width: 484px; transform: translate3d(0px, 0px, 0px);">
                                                            <div class="product-item active slick-slide slick-current slick-active"
                                                                data-slick-index="0" aria-hidden="false" tabindex="0"
                                                                style="width: 242px;">
                                                                <img src="images/product-1/product-7.jpg"
                                                                    alt="product">
                                                            </div>
                                                            <div class="product-item slick-slide" data-slick-index="1"
                                                                aria-hidden="true" tabindex="-1" style="width: 242px;">
                                                                <img src="images/product-1/product-8.jpg"
                                                                    alt="product">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="next slick-arrow" style="" aria-disabled="false"><i
                                                            class="mdi mdi-chevron-right"></i></span>
                                                </div>
                                                <a class="add-wishlist" href="javascript:void(0)">
                                                    <i class="mdi mdi-heart-outline"></i>
                                                </a>
                                            </div>
                                            <div class="product-content text-center">
                                                <h4 class="title"><a href="product-details-page.html">Man's Shoe</a>
                                                </h4>
                                                <p>Reference 1102</p>
                                                <a href="javascript:void(0)" class="main-btn secondary-1-btn">
                                                    <img src="images/icon-svg/cart-7.svg" alt="">
                                                    $ 399
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pagination-wrapper pt-70">
                            <ul class="d-flex justify-content-center">
                                <li>
                                    <a href="javascript:void(0)"><i class="lni lni-chevron-left"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="active">1</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">2</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">3</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">4</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><i class="lni lni-chevron-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="clients-logo-section pt-70 pb-70">
    <div class="container">
        <div class="row client-logo-active slick-initialized slick-slider">




            <div class="slick-list draggable">
                <div class="slick-track" style="opacity: 1; width: 1016px; transform: translate3d(0px, 0px, 0px);">
                    <div class="col-lg-3 slick-slide slick-current slick-active" tabindex="0" style="width: 254px;"
                        data-slick-index="0" aria-hidden="false">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/uideck-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide slick-active" tabindex="0" style="width: 254px;"
                        data-slick-index="1" aria-hidden="false">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/graygrids-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide" tabindex="-1" style="width: 254px;" data-slick-index="2"
                        aria-hidden="true">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/lineicons-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide" tabindex="-1" style="width: 254px;" data-slick-index="3"
                        aria-hidden="true">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/pagebulb-logo.svg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="subscribe-section pt-70 pb-70 bg-primary-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="heading text-center">
                    <h1 class="heading-1 font-weight-700 text-white mb-10">Subscribe Newsletter</h1>
                    <p class="gray-3">Be the first to know when new products drop and get behind-the-scenes content
                        straight.</p>
                </div>
                <div class="subscribe-form">
                    <form action="#" class="has-validation-callback">
                        <div class="single-form form-default">
                            <label class="text-white-50">Enter your email address</label>
                            <div class="form-input">
                                <input type="text" placeholder="user@email.com">
                                <i class="mdi mdi-account"></i>
                                <button class="main-btn primary-btn"><span class="mdi mdi-send"></span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop