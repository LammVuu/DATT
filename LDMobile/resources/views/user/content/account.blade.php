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
                            <li class="breadcrumb-item active" aria-current="page">Account</li>
                        </ol>
                    </div>
                    <div class="breadcrumb-right">
                        <h5 class="heading-5 font-weight-500">Account</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="profile-wrapper pt-50 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="profile">
                    <div class="profile-header">
                        <div class="profile-cover-photo bg_cover"
                            style="background-image: url(images/profile-cover.png);"></div>
                        <div class="profile-author d-sm-flex flex-row-reverse justify-content-between align-items-end">
                            <div class="profile-photo">
                                <img src="images/profile-photo.png" alt="Profile Photo">
                            </div>
                            <div class="profile-name">
                                <h4 class="name">Musa Ahmed</h4>
                                <p class="email">musa@email.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="profile-body">
                        <div class="profile-title">
                            <h5 class="title">Personal Details</h5>
                            <a class="profile-link" href="javascript:void(0)">edit</a>
                        </div>
                        <div class="profile-details">
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">User Full Name:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>Musa Ahmed</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Email:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>musa@email.com</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Phone:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>+123 456 789 0234</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Address:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>Company Inc., 8901 Marmora Road, Glasgow, D04 89GR.</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Gender:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>Male</p>
                                </div>
                            </div>
                            <div class="single-details-item d-flex flex-wrap">
                                <div class="details-title">
                                    <h6 class="title">Birthday:</h6>
                                </div>
                                <div class="details-content media-body">
                                    <p>04 January 1992</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-footer mt-45">
                        <div class="profile-title">
                            <h5 class="title">Cards</h5>
                            <a class="profile-link" href="javascript:void(0)">Add Cards</a>
                        </div>
                        <div class="profile-card-info">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="single-card-info d-flex">
                                        <div class="card-icon">
                                            <img src="images/visa.png" alt="Card">
                                        </div>
                                        <div class="card-info media-body">
                                            <h5 class="card-name">Musa Ahmed</h5>
                                            <p class="card-number">.... 4534 <span>02/20</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="single-card-info d-flex">
                                        <div class="card-icon">
                                            <img src="images/american-express.png" alt="Card">
                                        </div>
                                        <div class="card-info media-body">
                                            <h5 class="card-name">Musa Ahmed</h5>
                                            <p class="card-number">.... 4534 <span>02/20</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="single-card-info d-flex">
                                        <div class="card-icon">
                                            <img src="images/mastercard.png" alt="Card">
                                        </div>
                                        <div class="card-info media-body">
                                            <h5 class="card-name">Musa Ahmed</h5>
                                            <p class="card-number">.... 4534 <span>02/20</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <div class="slick-track"
                    style="opacity: 1; width: 1016px; transform: translate3d(-254px, 0px, 0px); transition: transform 800ms ease 0s;">
                    <div class="col-lg-3 slick-slide" tabindex="0" style="width: 254px;" data-slick-index="0"
                        aria-hidden="true">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/uideck-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide slick-current slick-active" tabindex="0" style="width: 254px;"
                        data-slick-index="1" aria-hidden="false">
                        <div class="single-logo-wrapper">
                            <img src="images/client-logo/graygrids-logo.svg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-3 slick-slide slick-active" tabindex="-1" style="width: 254px;"
                        data-slick-index="2" aria-hidden="false">
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