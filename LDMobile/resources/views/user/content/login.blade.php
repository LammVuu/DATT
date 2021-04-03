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
    <li class="breadcrumb-item active" aria-current="page">Sign in</li>
    </ol>
    </div>
    <div class="breadcrumb-right">
    <h5 class="heading-5 font-weight-500">Sign in</h5>
    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
    <section class="login-registration-wrapper pt-50">
        <div class="container">
        <div class="row">
        <div class="col-lg-6 mx-auto">
        <div class="login-registration-style-2 mt-50">
        <h1 class="heading-4 font-weight-500 title">Login</h1>
        <div class="login-registration-form pt-10">
        <form action="#" class="has-validation-callback">
        <div class="single-form form-default form-border">
        <label>Email Address</label>
        <div class="form-input">
        <input type="email" placeholder="user@email.com">
        <i class="mdi mdi-email"></i>
        </div>
        </div>
        <div class="single-form form-default form-border">
        <label>Your Password</label>
        <div class="form-input">
        <input id="password-7" type="password" placeholder="Password">
        <i class="mdi mdi-lock"></i>
        <span toggle="#password-7" class="mdi mdi-eye-outline toggle-password"></span>
        </div>
        </div>
        <div class="login-checkbox-forget d-sm-flex justify-content-between align-items-center">
        <div class="single-checkbox checkbox-style-3">
        <input type="checkbox" id="login-7">
        <label for="login-7"><span></span> </label>
        <p>Remember Me</p>
        </div>
        <a class="forget" href="#">Forget Password?</a>
        </div>
        <div class="single-form">
        <button class="main-btn primary-btn">Sign in</button>
        </div>
        </form>
        </div>
        <div class="text-center">
        <p class="login">Don’t have an account? <a href="signup-page.html">Sign up</a></p>
        <p class="account mt-25">Or</p>
        <ul>
        <li>
        <a href="javascript:void(0)" class="facebook-login-registration">
        <i class="lni lni-facebook-original"></i>
        </a>
        </li>
        <li>
        <a href="javascript:void(0)" class="google-login-registration">
        <img src="images/google-logo.svg" alt="">
        </a>
        </li>
        </ul>
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
                <div class="slick-track" style="opacity: 1; width: 1016px; transform: translate3d(-254px, 0px, 0px);">
                    <div class="col-lg-3 slick-slide" tabindex="-1" style="width: 254px;" data-slick-index="0"
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
                    <div class="col-lg-3 slick-slide slick-active" tabindex="0" style="width: 254px;"
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