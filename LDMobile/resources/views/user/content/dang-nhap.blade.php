<!DOCTYPE html>
<html lang="en">
    @include("user.header.head")
<body>
    <div class="loader"><div class="loader-bg"></div><div class="loader-img"><img src="images/logo/LDMobile-logo.png" alt=""></div><div class="spinner-border" role="status"></div></div>
    <section class='login-signup-sec'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-5 mx-auto box-shadow login-signup-box'>
                    @if(session('success_message'))
                        <div class="success-message mb-20">{{ session('success_message') }}</div>
                    @elseif (session('error_message'))
                        <div class="error-message mb-20">{{ session('error_message') }}</div>
                    @endif
                    <h3 class='pb-30 font-weight-600'>Đăng nhập</h3>
    
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
                                <input type="checkbox" id='remember' name="remember">
                                <label for="remember" class='form-check-label'>Lưu đăng nhập</label>
                            </div>
                            <a class="forget" href="#">Quên mật khẩu?</a>
                        </div>
    
                        <!-- button đăng nhặp -->
                        <div id="btn-login" class='main-btn p-10 w-100 mt-20'>Đăng nhập</div>
    
                        <!-- đăng nhập khác -->
                        <div class='mt-20'>
                            <div class='d-flex flex-column align-items-center'>
                                <div>Chưa có tài khoản? <a href={{route('user/dang-ky')}}>Đăng ký</a></div>
                                <div class="login-with w-100"></div>
                                <a href="{{route('user/facebook-redirect')}}" type="button" class='btn-login-signup-with box-shadow'>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <img src="images/icon/facebook-icon.png" alt='fb-icon' class="mr-20">
                                            </div>
                                            <div class="col-sm-8 d-flex align-items-center">Facebook</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{route('user/google-redirect')}}" class='btn-login-signup-with box-shadow'>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <img src="images/icon/google-icon.png" alt='fb-icon' class="mr-20">
                                            </div>
                                            <div class="col-sm-8 d-flex align-items-center">Google</div>
                                        </div>
                                    </div>
                                </a>
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
