@extends("user.layout")

@section("content")

@section("breadcrumb")
    <a href="{{route('user/tra-cuu')}}" class="bc-item active">Tra cứu</a>
@stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class="pt-80 pb-100">
    <div class="container">
        {{-- check imei --}}
        <div id='check-imei' class="col-md-4 col-sm-6 mx-auto">
            <h3 class="text-center mb-10">Nhập số IMEI điện thoại</h3>
            <div>
                <input type="text" id="imei-inp" class="text-center">
            </div>
            <div class="d-flex justify-content-center mt-10">
                <div id='btn-check-imei' class="pointer-cs main-btn p-10 w-40">Tra cứu</div>
            </div>            
        </div>

        <div id="valid-imei"></div>
    </div>
</section>

@include('user.content.section.sec-logo')

@stop