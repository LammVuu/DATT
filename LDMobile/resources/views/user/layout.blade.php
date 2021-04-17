<!doctype html>
<html lang="en">

@include("user.header.head")

<body>

    {{-- <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    @include('user.header.header')
   
    @yield("content")

    <a href="#" class='btn-scroll-top'><i class="fas fa-chevron-up"></i></a>
  
    @include("user.footer.footer")
    @include("user.footer.footer-link")
</body>
</html>
