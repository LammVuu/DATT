@extends("user.layout")

@section("content")

@section("direct")TÀI KHOẢN @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='profile-wrapper pt-50 pb-100'>
    <div class='container'>
        @include("user.content.taikhoan." . $page)
    </div>
</section>

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop