<!doctype html>
<html lang="en">

@include("user.header.head")

<body>
    {{-- session --}}
    @if (session('alert_message'))
        <div id="alert-message" data-message="{{session('alert_message')}}"></div>
    @endif
    
    <div class="loader"><div class="loader-bg"></div><div class="loader-img"><img src="images/logo/LDMobile-logo.png" alt=""></div><div class="spinner-border" role="status"></div></div>

    @include('user.header.header')
    
    @yield("content")

    <div id='btn-scroll-top'><i class="fas fa-chevron-up"></i></div>
  
    @include("user.footer.footer")
    @include("user.footer.footer-link")
</body>
</html>
