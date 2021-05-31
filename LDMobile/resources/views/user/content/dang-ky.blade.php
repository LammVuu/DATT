<!DOCTYPE html>
<html lang="en">
    @include("user.header.head")
<body>
    <div class="loader"><div class="loader-bg"></div><div class="spinner-border" role="status"></div></div>
    <section class='login-signup-sec'>
        <div class='container'>
            <div class='row'>
                {{-- Full signup --}}
                <!-- <div class='col-md-10 mx-auto box-shadow login-signup-box'>
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class='mb-30 text-center'>Đăng Ký</h3>
    
                            <form action="#" method="POSt">
                                @csrf
                                <div class="font-weight-600 mb-10">Thông tin cá nhân</div>
            
                                {{-- họ và tên --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-user"></i></span>
                                    <input type='text' class='form-control' placeholder='Họ và tên'>
                                </div>
            
                                {{-- sdt --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-phone"></i></span>
                                    <input type='tel' pattern="[0-9][0-9][0-9]" class='form-control' placeholder='Số điện thoại'>
                                </div>
            
                                {{-- địa chỉ --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-map-marker-alt"></i></span>
                                    <input type='text' class='form-control' placeholder='Địa chỉ'>
                                </div>
            
                                <div class="font-weight-600 mb-10">Thông tin tài khoản</div>
            
                                {{-- email --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-envelope"></i></span>
                                    <input type='email' class='form-control' placeholder='Email'>
                                </div>
            
                                {{-- mật khẩu --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-key"></i></span>
                                    <input type='password' class='form-control' placeholder='Mật khẩu'>
                                </div>
            
                                {{-- nhập lại mật khẩu --}}
                                <div class='input-group mb-3'>
                                    <span class='input-group-text'><i class="fas fa-key"></i></span>
                                    <input type='password' class='form-control' placeholder='Nhập lại mật khẩu'>
                                </div>
            
                                {{-- nút đăng ký --}}
                                <a href="#" class='main-btn p-10 w-100'>Đăng ký</a>
                                <div class="mt-20 text-center">
                                    <div>Đã có tài khoản? <a href={{route('user/dang-nhap')}}>Đăng nhập</a></div>
                                </div>

                                {{-- về trang chủ --}}
                                <div class="mt-30">
                                    <a href={{route('user/index')}} class="d-flex align-items-center"><i class="far fa-chevron-left mr-10 fz-14"></i>Về trang chủ</a>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            {{-- hình ảnh --}}
                            <div class="signup-image">
                                <img src="images/signup-des-bg.jpg" alt="">
                                <div class="text-center main-color-text p-10">
                                    <div class="fz-18 font-weight-600">Mua hàng tại LDMoible</div>
                                    <div>Cập nhật sản phẩm mới nhất mức giá ưu đãi</div>
                                </div>
                            </div>

                            {{-- đang nhập khác --}}
                            <div class='d-flex flex-column align-items-center mt-20'>
                                <div class="login-with w-90"></div>
    
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
                    </div>
                </div> -->

                {{-- quick signup --}}
                <div class="col-md-5 col-sm-10 mx-auto box-shadow login-signup-box">
                    <form action="#" method="POST">
                        @csrf
                        {{-- enter phone number --}}
                        <div id='enter-phone-number'>
                            {{-- title --}}
                            <div class="mb-30">
                                <h2 class="font-weight-600">Đăng ký</h2>
                                <div>Vui lòng nhập số điện thoại</div>    
                            </div>
                            {{-- SDT --}}
                            <div>
                                <input type='tel' id='su-tel' maxlength="10" class="sign-up fz-26" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" placeholder='Số điện thoại' required>
                            </div>
                            {{-- nút tiếp tục --}}
                            <button type='button' id='signup-step-1' class='main-btn mt-30 p-10 w-100'>Tiếp tục</button>
                            <div class="d-flex justify-content-center mt-10">Đã có tài khoản? <a href="{{route('user/dang-nhap')}}" class="ml-10">Đăng nhập</a></div>
        
                            {{-- đăng nhập khác --}}
                            <div class='mt-50'>
                                <div class='d-flex flex-column align-items-center'>
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
                        </div>

                        {{-- enter Verification --}}
                        <div id="enter-verify-code" class="none-dp">
                            {{-- nút quay lại --}}
                            <div class="mb-30">
                                <span type="button" id="back-to-enter-tel"><i class="far fa-chevron-left fz-26"></i></span>
                            </div>

                            <div class="mb-30">
                                <h3 class="font-weight-600">Nhập mã xác thực</h3>
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
                                <h3 class="font-weight-600">Tạo mật khẩu</h3>
                                <div>Mật khẩu này sử dụng cho lần đăng nhập tiếp theo</div>
                            </div>

                            {{-- mật khẩu --}}
                            <div class="mb-3">
                                <input type='password' id="su-pw" class='form-control' placeholder='Mật khẩu'>
                            </div>

                            {{-- nhập lại mật khẩu --}}
                            <div class="mb-3">
                                <input type='password' id="su-re-pw" class='form-control' placeholder='Nhập lại mật khẩu'>
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