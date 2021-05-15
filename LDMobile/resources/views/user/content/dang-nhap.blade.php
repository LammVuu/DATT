@extends("user.layout")
@section("content")

<section class='login-registration-wrapper pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-6 offset-3 box-shadow login-box'>
                <h3 class='pb-30'>Đăng nhập</h3>

                <form action="#">
                    <!-- email -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-envelope"></i></span>
                        <input type='email' class='form-control' placeholder='abc@gmail.com'>
                    </div>

                    <!-- mật khẩu -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-key"></i></span>
                        <input type='password' class='form-control' placeholder='Password'>
                    </div>

                    <!-- lưu đăng nhập & quên mật khẩu -->
                    <div class="login-checkbox-forget d-sm-flex justify-content-between align-items-center">
                        <div class='form-check'>
                            <input type="checkbox" class='form-check-input' name='remember'>
                            <label for="remember" class='form-check-label'>Lưu đăng nhập</label>
                        </div>
                        <a class="forget" href="#">Quên mật khẩu?</a>
                    </div>

                    <!-- button đăng nhặp -->
                    <div class="single-form">
                        <a href="#" class='login-btn'>Đăng nhập</a>
                    </div>

                    <!-- đăng nhập khác -->
                    <div class='mt-50'>
                        <div class='d-flex flex-column align-items-center'>
                            <p class="login ml-20">Chưa có tài khoản? <a href={{route('user/dang-ky')}}>Đăng ký</a></p>
                            <p class="account mt-25">Đăng nhập với</p><br>

                            <a href="#" class='login-signup-with box-shadow d-flex'>
                                <div class='pl-50 pr-20'>
                                    <img src="images/icon/facebook-icon.png" alt='fb-icon' class='icon-btn-login-signup'>
                                </div>
                                <span>Đăng nhập với Facebook</span>
                            </a>
                            <a href="#" class='login-signup-with box-shadow d-flex'>
                                <div class='pl-50 pr-20'>
                                    <img src="images/icon/google-icon.png" alt='gg-icon' class='icon-btn-login-signup'>
                                </div>
                                <span>Đăng nhập với Google</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@stop