@extends("user.layout")

@section("content")

@section("breadcrumb")
    <a href="{{route('user/tai-khoan')}}" class="bc-item">Tài khoản</a>
    <div class="bc-divider"><i class="fas fa-chevron-right"></i></div>
    @if ($page == 'sec-tai-khoan')
        <a href="{{route('user/tai-khoan')}}" class="bc-item active">Thông tin tài khoản</a>
    @elseif ($page == 'sec-thong-bao')
        <a href="{{route('user/tai-khoan-thong-bao')}}" class="bc-item active">Thông báo</a>
    @elseif ($page == 'sec-don-hang')
        <a href="{{route('user/tai-khoan-don-hang')}}" class="bc-item active">Quản lý đơn hàng</a>
    @elseif($page == 'sec-chi-tiet-don-hang')
        <a href="{{route('user/tai-khoan-don-hang')}}" class="bc-item active">Quản lý đơn hàng</a>
    @elseif ($page == 'sec-dia-chi')
        <a href="{{route('user/tai-khoan-dia-chi')}}" class="bc-item active">Sổ địa chỉ</a>
    @elseif ($page == 'sec-yeu-thich')
        <a href="{{route('user/tai-khoan-yeu-thich')}}" class="bc-item active">Sản phẩm yêu thích</a>
    @elseif ($page == 'sec-voucher')
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