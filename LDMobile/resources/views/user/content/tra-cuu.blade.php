@extends("user.layout")

@section("content")

@section("direct")TRA CỨU @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class="pt-100 pb-100">
    <div class="container">
        {{-- check imei --}}
        <div id='check-imei' class="col-md-4 col-sm-6 mx-auto">
            <h3 class="text-center mb-10">Nhập số IMEI điện thoại</h3>
            <input type="text" id="imei-inp" class="text-center">
            <span class="required-text">Số IMEI không được bỏ trống</span>
            <span id='imei-error' class="required-text">Số IMEI không hợp lệ, vui lòng kiểm tra lại</span>
            <div class="d-flex justify-content-center mt-10">
                <div id='btn-check-imei' class="pointer-cs main-btn p-10 w-40">Tra cứu</div>
            </div>            
        </div>

        {{-- imei hợp lệ --}}
        <div id='valid-imei' class="none-dp">
            <div class="col-md-3 mx-auto">
                {{-- hình điện thoại --}}
                <img src="images/phone/iphone_12_red.jpg" alt="">
                <div class="text-center">
                    {{-- thông tin nhanh điện thoại --}}
                    <div class="fz-26 font-weight-600 pt-20 pb-20">iPhone 12 PRO MAX</div>
                    <div class="pb-10">
                        <b>Màu sắc: Đỏ</b>
                        <b class="ml-20">Dung lượng: 512GB</b>
                    </div>
                    <div class="font-weight-600 pb-10">IMEI: 123456789</div>
                    {{-- kiểm tra imei khác --}}
                    <div id='btn-check-imei-2' class="main-color-text pointer-cs">Kiểm tra số IMEI khác<i class="far fa-chevron-right ml-10"></i></div> 
                </div>
            </div>

            {{-- thông tin bảo hành --}}
            <div class="pt-70">
                <div class="col-md-8 mx-auto">
                    <div class="fz-26 font-weight-600">
                        <i class="fas fa-shield-check mr-10"></i>Bảo hành
                    </div>
                    <div class="d-flex fz-20 mt-10">
                        <div>Trạng thái bảo hành:</div>
                        <b class="success-color-2 ml-10">Trong bảo hành</b>
                    </div>
                    <div class="mt-10">Còn lại: <b class="success-color-2">12 tháng</b></div>
                    <div class="d-flex mt-10">
                        <div>Bắt đầu: <b>01/01/2021</b></div>
                        <div class="ml-10">Kết thúc: <b>09/07/2021</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop