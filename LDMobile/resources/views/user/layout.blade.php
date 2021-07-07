<!doctype html>
<html lang="en">

@include("user.header.head")

<body>
    {{-- session --}}
    @if (session('toast_message'))
        <div id="toast-message" data-message="{{session('toast_message')}}"></div>
    @endif

    {{-- hết hạn phiên đăng nhập fb, gg --}}
    @if (session('login_status'))
        <div class="modal fade" id="invalid-login-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-60">
                        <div class="text-center">
                            <i class="fal fa-info-circle fz-100 main-color-text"></i>
                            <div class="fz-20 mt-20">Phiên đăng nhập đã hết hạn.</div>
                        </div>
                        <div class="mt-30 d-flex justify-content-between">
                            <div class="close-invalid-login-modal cancel-btn p-10 w-48" data-bs-dismiss="modal">Đóng</div>
                            <a href="{{route('user/dang-nhap')}}" id="delete-btn" class="close-invalid-login-modal checkout-btn p-10 w-48">Đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="loader"><div class="loader-bg"></div><div class="loader-img"><img src="images/logo/LDMobile-logo.png" alt=""></div><div class="spinner-border" role="status"></div></div>

    @include('user.header.header')
    
    @yield("content")

    <div id='btn-scroll-top'><i class="fas fa-chevron-up"></i></div>
  
    @include("user.footer.footer")
    @include("user.footer.footer-link")
</body>
</html>
