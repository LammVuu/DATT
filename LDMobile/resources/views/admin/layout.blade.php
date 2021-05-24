<!DOCTYPE html>
<html lang="en">

@include('admin/header/head')

<body>
    @include("admin/header/header")
    @include("admin.content.section.sec-sidebar")
    
    <!-- content -->
    <div class="content">
        <!-- title of page -->
        <div class="content-header d-flex justify-content-between">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fz-22 font-weight-600">@yield('content-title')</div>
            </div>
        </div>

        <div class="m-30 white-bg p-20">
            @yield("content")
        </div>
    </div>
    

    @include("admin/footer/foot")
</body>

</html>
