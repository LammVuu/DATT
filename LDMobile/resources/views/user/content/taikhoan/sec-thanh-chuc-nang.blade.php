<div class='d-flex flex-column'>
    <a href="{{route('user/tai-khoan')}}" class='account-sidebar-tag @yield('acc-info-active')'>
        <i class="fas fa-user mr-20"></i>Thông tin tài khoản
    </a>
    <a href="{{route('user/tai-khoan-thong-bao')}}" class='account-sidebar-tag @yield('acc-noti-active')'>
        <i class="fas fa-bell mr-20"></i>Thông báo
        @if (count($data['lst_noti']['noti']) != 0)
            <span id="not-seen-qty" class='head-number ml-20 fz-12'>{{$data['lst_noti']['not-seen']}}</span>
        @endif
    </a>
    <a href="{{route('user/tai-khoan-don-hang')}}" class='account-sidebar-tag @yield('acc-order-active')'>
        <i class="fas fa-box mr-20"></i>Quản lý đơn hàng
        @if ($data['lst_order']['processing'] != 0)
            <span class='head-number ml-20 fz-12'>{{$data['lst_order']['processing']}}</span>
        @endif
    </a>
    <a href="{{route('user/tai-khoan-dia-chi')}}" class='account-sidebar-tag @yield('acc-address-active')'>
        <i class="fas fa-map-marker-alt mr-20"></i>Sổ địa chỉ
    </a>
    <a href="{{route('user/tai-khoan-yeu-thich')}}" class='account-sidebar-tag @yield('acc-favorite-active')'>
        <i class="fas fa-heart mr-20"></i>Sản phẩm yêu thích
    </a>
    <a href="{{route('user/tai-khoan-voucher')}}" class='account-sidebar-tag @yield('acc-voucher-active')'>
        <i class="fas fa-ticket-alt mr-20"></i>Mã giảm giá
    </a>
    <hr>
    <a href="{{route('user/logout')}}" class='account-sidebar-tag price-color'>
        <i class="far fa-power-off mr-20"></i>Đăng xuất
    </a>
</div>