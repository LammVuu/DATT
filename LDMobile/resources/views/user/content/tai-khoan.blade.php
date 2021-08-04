@extends("user.layout")
<?php $user = session('user') ?>

@section("content")

@section("breadcrumb")
    <a href="{{route('user/tai-khoan')}}" class="bc-item">Tài khoản</a>
    <div class="bc-divider"><i class="fas fa-chevron-right"></i></div>
    {{-- tài khoản --}}
    @if ($page == 'sec-tai-khoan')
        @section("title"){{$user->hoten}} | LDMobile @stop
        <a href="{{route('user/tai-khoan')}}" class="bc-item active">Thông tin tài khoản</a>
    {{-- thông báo --}}
    @elseif ($page == 'sec-thong-bao')
        @section("title")| LDMobile @stop
        <a href="{{route('user/tai-khoan-thong-bao')}}" class="bc-item active">Thông báo</a>
    {{-- đơn hàng --}}
    @elseif ($page == 'sec-don-hang')
        @section("title")Đơn hàng | LDMobile @stop
        <a href="{{route('user/tai-khoan-don-hang')}}" class="bc-item active">Quản lý đơn hàng</a>
    {{-- chi tiết đơn hàng --}}
    @elseif($page == 'sec-chi-tiet-don-hang')
        @section("title")Đơn hàng | LDMobile @stop
        <a href="{{route('user/tai-khoan-don-hang')}}" class="bc-item active">Quản lý đơn hàng</a>
    {{-- địa chỉ --}}
    @elseif ($page == 'sec-dia-chi')
        @section("title")Sổ địa chỉ | LDMobile @stop
        <a href="{{route('user/tai-khoan-dia-chi')}}" class="bc-item active">Sổ địa chỉ</a>
    {{-- yêu thích --}}
    @elseif ($page == 'sec-yeu-thich')
        @section("title")Yêu thích | LDMobile @stop
        <a href="{{route('user/tai-khoan-yeu-thich')}}" class="bc-item active">Sản phẩm yêu thích</a>
    {{-- mã giảm giá --}}
    @elseif ($page == 'sec-voucher')
        @section("title")Mã giảm giá | LDMobile @stop
        <a href="{{route('user/tai-khoan-voucher')}}" class="bc-item active">Mã giảm giá</a>
    @endif
@stop

@include("user.content.section.sec-thanh-dieu-huong")

<section class='profile-wrapper pt-50 pb-100'>
    <div class='container'>
        @include("user.content.taikhoan." . $page)
    </div>
</section>

@include('user.content.section.sec-logo')

@stop