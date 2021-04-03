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
                            <li class="breadcrumb-item active" aria-current="page">Cart Page</li>
                        </ol>
                    </div>
                    <div class="breadcrumb-right">
                        <h5 class="heading-5 font-weight-500">Cart Page</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="checkout-wrapper pt-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="checkout-style-1 ">
                    <div class="checkout-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product">Product</th>
                                    <th class="quantity">Quantity</th>
                                    <th class="price">Price</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="product-cart d-flex">
                                            <div class="product-thumb">
                                                <img src="images/product-cart/product-1.png" alt="Product">
                                            </div>
                                            <div class="product-content media-body">
                                                <h5 class="title"><a href="product-details-page.html">Hollow Port</a>
                                                </h5>
                                                <span>UG 0123</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-quantity d-inline-flex">
                                            <button type="button" id="sub" class="sub"><i
                                                    class="mdi mdi-minus"></i></button>
                                            <input type="text" value="0">
                                            <button type="button" id="add" class="add"><i
                                                    class="mdi mdi-plus"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="price">$36.00</p>
                                    </td>
                                    <td>
                                        <ul class="action">
                                            <li><a class="favorite" href="javascript:void(0)"><i
                                                        class="mdi mdi-heart-outline"></i></a>
                                            </li>
                                            <li><a class="delete" href="javascript:void(0)"><i
                                                        class="mdi mdi-delete"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="product-cart d-flex">
                                            <div class="product-thumb">
                                                <img src="images/product-cart/product-2.png" alt="Product">
                                            </div>
                                            <div class="product-content media-body">
                                                <h5 class="title"><a href="product-details-page.html">Hollow Port</a>
                                                </h5>
                                                <span>UG 0123</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-quantity d-inline-flex">
                                            <button type="button" id="sub" class="sub"><i
                                                    class="mdi mdi-minus"></i></button>
                                            <input type="text" value="0">
                                            <button type="button" id="add" class="add"><i
                                                    class="mdi mdi-plus"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="price">$36.00</p>
                                    </td>
                                    <td>
                                        <ul class="action">
                                            <li><a class="favorite" href="javascript:void(0)"><i
                                                        class="mdi mdi-heart-outline"></i></a>
                                            </li>
                                            <li><a class="delete" href="javascript:void(0)"><i
                                                        class="mdi mdi-delete"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="product-cart d-flex">
                                            <div class="product-thumb">
                                                <img src="images/product-cart/product-3.png" alt="Product">
                                            </div>
                                            <div class="product-content media-body">
                                                <h5 class="title"><a href="product-details-page.html">Hollow Port</a>
                                                </h5>
                                                <span>UG 0123</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-quantity d-inline-flex">
                                            <button type="button" id="sub" class="sub"><i
                                                    class="mdi mdi-minus"></i></button>
                                            <input type="text" value="0">
                                            <button type="button" id="add" class="add"><i
                                                    class="mdi mdi-plus"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="price">$36.00</p>
                                    </td>
                                    <td>
                                        <ul class="action">
                                            <li><a class="favorite" href="javascript:void(0)"><i
                                                        class="mdi mdi-heart-outline"></i></a>
                                            </li>
                                            <li><a class="delete" href="javascript:void(0)"><i
                                                        class="mdi mdi-delete"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="product-cart d-flex">
                                            <div class="product-thumb">
                                                <img src="images/product-cart/product-4.png" alt="Product">
                                            </div>
                                            <div class="product-content media-body">
                                                <h5 class="title"><a href="product-details-page.html">Hollow Port</a>
                                                </h5>
                                                <span>UG 0123</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-quantity d-inline-flex">
                                            <button type="button" id="sub" class="sub"><i
                                                    class="mdi mdi-minus"></i></button>
                                            <input type="text" value="0">
                                            <button type="button" id="add" class="add"><i
                                                    class="mdi mdi-plus"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="price">$36.00</p>
                                    </td>
                                    <td>
                                        <ul class="action">
                                            <li><a class="favorite" href="javascript:void(0)"><i
                                                        class="mdi mdi-heart-outline"></i></a>
                                            </li>
                                            <li><a class="delete" href="javascript:void(0)"><i
                                                        class="mdi mdi-delete"></i></a></li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="checkout-coupon-total checkout-coupon-total-2 d-flex flex-wrap">
                        <div class="checkout-coupon">
                            <span>Apply Coupon to get discount!</span>
                            <form action="#" class="has-validation-callback">
                                <div class="single-form form-default d-flex">
                                    <div class="form-input form">
                                        <input type="text" placeholder="Coupon Code">
                                    </div>
                                    <button class="main-btn primary-btn">Apply</button>
                                </div>
                            </form>
                        </div>
                        <div class="checkout-total">
                            <div class="single-total">
                                <p class="value">Subtotal Price:</p>
                                <p class="price">$144.00</p>
                            </div>
                            <div class="single-total">
                                <p class="value">Shipping Cost (+):</p>
                                <p class="price">$10.50</p>
                            </div>
                            <div class="single-total">
                                <p class="value">Discount (-):</p>
                                <p class="price">$10.50</p>
                            </div>
                            <div class="single-total total-payable">
                                <p class="value">Total Payable:</p>
                                <p class="price">$165.00</p>
                            </div>
                        </div>
                    </div>
                    <div class="checkout-btn d-sm-flex justify-content-between">
                        <div class="single-btn">
                            <a href="javascript:void(0)" class="main-btn primary-btn-border">continue shopping</a>
                        </div>
                        <div class="single-btn">
                            <a href="checkout-page.html" class="main-btn primary-btn">Pay now</a>
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
                <div class="slick-track" style="opacity: 1; width: 1016px; transform: translate3d(-508px, 0px, 0px);">
                    <div class="col-lg-3 slick-slide" tabindex="-1" style="width: 254px;" data-slick-index="0"
                        aria-hidden="true">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/uideck-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide" tabindex="-1" style="width: 254px;" data-slick-index="1"
                        aria-hidden="true">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/graygrids-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide slick-current slick-active" tabindex="0" style="width: 254px;"
                        data-slick-index="2" aria-hidden="false">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/lineicons-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide slick-active" tabindex="0" style="width: 254px;"
                        data-slick-index="3" aria-hidden="false">
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