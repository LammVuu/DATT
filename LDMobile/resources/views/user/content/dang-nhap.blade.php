<!DOCTYPE html>
<html lang="en">
    @section("title")Đăng nhập | LDMobile @stop
    @include("user.header.head")
<body>
    <div class="loader"><div class="loader-bg"></div><div class="loader-img"><img src="images/logo/LDMobile-logo.png" alt=""></div><div class="spinner-border" role="status"></div></div>
    <section class='login-signup-sec'>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-5 col-md-8 col-sm-10 col-10 mx-auto box-shadow login-signup-box'>
                    @if(session('success_message'))
                        <div class="success-message mb-20">{{ session('success_message') }}</div>
                    @elseif (session('error_message'))
                        <div class="error-message mb-20">{{ session('error_message') }}</div>
                    @endif
                    <h3 class='pb-30 fw-600'>Đăng nhập</h3>
    
                    <form id="login-form" action="{{route('user/login')}}" method="POST">
                        @csrf
                        <!-- sdt -->
                        <div class='mb-3'>
                            <input type='text' id="login_tel" name="login_tel" placeholder='Số điện thoại' maxlength="10">
                        </div>
    
                        <!-- mật khẩu -->
                        <div class='mb-3'>
                            <input type='password' id="login_pw" name="login_pw" placeholder='Mật khẩu'>
                        </div>
    
                        <!-- lưu đăng nhập & quên mật khẩu -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" id='remember' name="remember" value="0">
                                <label for="remember" class='form-check-label'>Lưu đăng nhập</label>
                            </div>
                            <a href="{{route('user/khoi-phuc-tai-khoan')}}">Quên mật khẩu?</a>
                        </div>
    
                        <!-- button đăng nhặp -->
                        <div id="btn-login" class='main-btn w-100 mt-20'>Đăng nhập</div>
    
                        <!-- đăng nhập khác -->
                        <div class='mt-20'>
                            <div class='d-flex flex-column align-items-center'>
                                <div>Chưa có tài khoản? <a href={{route('user/dang-ky')}}>Đăng ký</a></div>
                                <div class="login-with w-100"></div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-10 mx-auto">
                                        <a href="{{route('user/facebook-redirect')}}" type="button" class='btn-login-signup-with box-shadow'>
                                            <div class="row">
                                                <div class="col-lg-4 col-4">
                                                    <img src="images/icon/facebook-icon.png" alt='fb-icon' class="mr-20 w-90">
                                                </div>
                                                <div class="col-lg-8 col-8 d-flex align-items-center">Facebook</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-10 mx-auto">
                                        <a href="{{route('user/google-redirect')}}" type="button" class='btn-login-signup-with box-shadow'>
                                            <div class="row">
                                                <div class="col-lg-4 col-4">
                                                    <img src="images/icon/google-icon.png" alt='gg-icon' class="mr-20 w-90">
                                                </div>
                                                <div class="col-lg-8 col-8 d-flex align-items-center">Google</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- về trang chủ --}}
                        <div class="mt-30">
                            <a href={{route('user/index')}} class="d-flex align-items-center"><i class="far fa-chevron-left mr-10 fz-14"></i>Về trang chủ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include("user.footer.footer-link")
</body>
</html>
