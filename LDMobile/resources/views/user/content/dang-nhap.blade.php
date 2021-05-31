<!DOCTYPE html>
<html lang="en">
    @include("user.header.head")
<body>
    <section class='login-signup-sec'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6 mx-auto box-shadow login-signup-box'>
                    <h3 class='pb-30 font-weight-600'>Đăng nhập</h3>
    
                    <form action="#" method="POST">
                        @csrf
                        <!-- email -->
                        <div class='input-group mb-3'>
                            <span class='input-group-text'><i class="fas fa-phone"></i></span>
                            <input type='text' class='form-control' placeholder='Số điện thoại' maxlength="10">
                        </div>
    
                        <!-- mật khẩu -->
                        <div class='input-group mb-3'>
                            <span class='input-group-text'><i class="fas fa-key"></i></span>
                            <input type='password' class='form-control' placeholder='Mật khẩu'>
                        </div>
    
                        <!-- lưu đăng nhập & quên mật khẩu -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" id='remember-me'>
                                <label for="remember-me" class='form-check-label'>Lưu đăng nhập</label>
                            </div>
                            <a class="forget" href="#">Quên mật khẩu?</a>
                        </div>
    
                        <!-- button đăng nhặp -->
                        <a href="#" class='main-btn p-10 w-100 mt-20'>Đăng nhập</a>
    
                        <!-- đăng nhập khác -->
                        <div class='mt-20'>
                            <div class='d-flex flex-column align-items-center'>
                                <div>Chưa có tài khoản? <a href={{route('user/dang-ky')}}>Đăng ký</a></div>
                                <div class="login-with w-100"></div>
    
                                <a href="#" class='btn-login-signup-with box-shadow'>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <img src="images/icon/facebook-icon.png" alt='fb-icon' class="mr-20">
                                            </div>
                                            <div class="col-sm-8 d-flex align-items-center">Facebook</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class='btn-login-signup-with box-shadow'>
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
