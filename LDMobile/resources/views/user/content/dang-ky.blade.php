@extends("user.layout")
@section("content")

<section class='login-registration-wrapper pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-6 offset-3 box-shadow login-box'>
                <h3 class='mb-30' style='text-align: center'>Đăng Ký</h3>

                <form action="#">
                    <b>Thông tin cá nhân</b><hr>

                    <!-- họ và tên -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-user"></i></span>
                        <input type='text' class='form-control' placeholder='Họ và tên'>
                    </div>

                    <!-- SDT -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-phone"></i></span>
                        <input type='tel' pattern="[0-9][0-9][0-9]" class='form-control' placeholder='Số điện thoại'>
                    </div>

                    <!-- Địa chỉ -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-map-marker-alt"></i></span>
                        <input type='text' class='form-control' placeholder='Địa chỉ'>
                    </div>

                    <b>Thông tin tài khoản</b><hr>

                    <!-- Email -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-envelope"></i></span>
                        <input type='email' class='form-control' placeholder='Email'>
                    </div>

                    <!-- mật khẩu -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-key"></i></span>
                        <input type='password' class='form-control' placeholder='Mật khẩu'>
                    </div>

                    <!-- nhập lại mật khẩu -->
                    <div class='input-group mb-3'>
                        <span class='input-group-text'><i class="fas fa-key"></i></span>
                        <input type='password' class='form-control' placeholder='Nhập lại mật khẩu'>
                    </div>

                    <!-- button đăng ký -->
                    <div class="single-form">
                        <a href="#" class='login-btn'>Đăng ký</a>
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