<!DOCTYPE html>
<html lang="en">
    @include("user.header.head")
<body>
    <div class="loader"><div class="loader-bg"></div><div class="loader-img"><img src="images/logo/LDMobile-logo.png" alt=""></div><div class="spinner-border" role="status"></div></div>
    <section class='login-signup-sec'>
        <div class='container'>
            <div class='row'>
                <div class="col-md-5 col-sm-10 mx-auto box-shadow login-signup-box">
                    {{-- session message --}}
                    @if(session('error_message'))
                        <div class="error-message mb-20">{{ session('error_message') }}</div>
                    @endif
                    <form id="signup-form" action="{{route('user/signup')}}" method="POST">
                        @csrf
                        {{-- enter phone number --}}
                        <div id='enter-information'>
                            {{-- title --}}
                            <div class="mb-30">
                                <h2 class="fw-600">Đăng ký</h2>
                            </div>

                            {{-- họ tên --}}
                            <div class="mb-3">
                                <input type="text" id='su_fullname' name="su_fullname" placeholder="Họ và tên" required>    
                            </div> 

                            {{-- SDT --}}
                            <div class="mb-3">
                                <input type='text' id='su_tel' name="su_tel" maxlength="10" placeholder='Số điện thoại' required>
                            </div>

                            <div class="mb-3">
                                <div id="recaptcha-container"></div>
                            </div>

                            {{-- nút tiếp tục --}}
                            <button type='button' id='signup-step-1' class='main-btn p-10 w-100'>Tiếp tục</button>
                            <div class="d-flex justify-content-center mt-10">Đã có tài khoản? <a href="{{route('user/dang-nhap')}}" class="ml-10">Đăng nhập</a></div>
        
                            {{-- đăng nhập khác --}}
                            <div class='mt-50'>
                                <div class='d-flex flex-column align-items-center'>
                                    <div class="login-with w-100"></div>
        
                                    <a href="{{route('user/facebook-redirect')}}" class='btn-login-signup-with box-shadow'>
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
                        </div>

                        {{-- enter Verification --}}
                        <div id="enter-verify-code" class="none-dp">
                            {{-- nút quay lại --}}
                            <div class="mb-30">
                                <span type="button" id="back-to-enter-tel"><i class="far fa-chevron-left fz-26"></i></span>
                            </div>

                            <div class="mb-30">
                                <h3 class="fw-600">Nhập mã xác thực</h3>
                                <div>Mã xác thực đã được gửi đến số điện thoại <b id="tel-confirm"></b></div>
                            </div>
                            
                            <div class="mb-30">
                                <input type="text" id="verify-code-inp" maxlength="6" placeholder="Ví dụ: 123456" class="text-center">
                            </div>

                            <div type="button" id="signup-step-2" class="main-btn p-10">Tiếp tục</div>
                        </div>

                        {{-- enter password --}}
                        <div id='enter-password' class="none-dp">
                            <div class="mb-30">
                                <h3 class="fw-600">Tạo mật khẩu</h3>
                            </div>
                            {{-- mật khẩu --}}
                            <div class="mb-3">
                                <label for="su_pw" class="fw-600 form-label">Mật khẩu</label>
                                <input type='password' id="su_pw" name="su_pw">
                            </div>

                            {{-- nhập lại mật khẩu --}}
                            <div class="mb-3">
                                <label for="su_re_pw" class="fw-600 form-label">Nhập lại mật khẩu</label>
                                <input type='password' id="su_re_pw" name="su_re_pw">
                            </div>

                            <div type='button' id='signup-step-3' class="main-btn p-10">Đăng ký</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include("user.footer.footer-link")
</body>
</html>