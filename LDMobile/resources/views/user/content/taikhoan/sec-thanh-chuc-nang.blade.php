<div class='d-flex flex-column'>
    <a href="{{route('user/tai-khoan')}}" class='account-sidebar-tag @yield('acc-info-active')'>
        <i class="fas fa-user mr-20"></i>Thông tin tài khoản
    </a>
    <a href="{{route('user/tai-khoan-thong-bao')}}" class='account-sidebar-tag @yield('acc-noti-active')'>
        <i class="fas fa-bell mr-20"></i>Thông báo <span class='head-number ml-20 fz-12'>10</span>
    </a>
    <a href="{{route('user/tai-khoan-don-hang')}}" class='account-sidebar-tag @yield('acc-order-active')'>
        <i class="fas fa-box mr-20"></i>Quản lý đơn hàng
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
    <a href="#" class='account-sidebar-tag price-color'>
        <i class="far fa-power-off mr-20"></i>Đăng xuất
    </a>
</div>