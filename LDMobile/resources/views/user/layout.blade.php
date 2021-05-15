<!doctype html>
<html lang="en">

@include("user.header.head")

<body>
    {{-- <div class="loader"><div class="loader-bg"></div><div class="spinner-border" role="status"></div></div> --}}

    @include('user.header.header')
    
    @yield("content")

    <div id='btn-scroll-top'><i class="fas fa-chevron-up"></i></div>
  
    @include("user.footer.footer")
    @include("user.footer.footer-link")
</body>
</html>
