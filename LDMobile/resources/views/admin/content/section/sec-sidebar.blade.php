<!-- Sidebar -->
<div class="sidebar custom-scrollbar">
    <!-- avatar -->
    <div class="sidebar-avt">
        <div class="d-flex flex-column">
            <img src="{{$url_user.$user->anhdaidien}}" alt="" width="80px" class="circle-img">
            <div class="white mt-10">{{$user->hoten}}</div>
        </div>
    </div>

    <!-- link -->
    <div class="sidebar-menu">
        <!-- dashboard -->
        <div class="sidebar-title">Trang chủ</div>
        <a href={{route('admin/dashboard')}} class="sidebar-link @yield('sidebar-dashboard')"><i class="fas fa-home mr-20"></i>Bảng điều khiển</a>
        <div class="ml-20 mr-20"><hr></div>
        <!-- table -->
        <div class="sidebar-title">Bảng</div>

        <a href={{route('hinhanh.index')}} class="sidebar-link @yield('sidebar-image')"><i class="fas fa-image mr-10"></i>Hình ảnh</a>

        <a href={{route('banner.index')}} class="sidebar-link @yield('sidebar-banner')"><i class="fas fa-ad mr-10"></i>Banner</a>

        <a href={{route('slideshow.index')}} class="sidebar-link @yield('sidebar-slideshow')"><i class="fas fa-images mr-10"></i>Slideshow</a>

        <a href={{route('mausanpham.index')}} class="sidebar-link @yield('sidebar-product-samples')"><i class="fas fa-th-list mr-10"></i>Mẫu sản phẩm</a>

        <a href={{route('sanpham.index')}} class="sidebar-link @yield('sidebar-product')"><i class="fas fa-mobile-alt mr-10"></i>Sản phẩm</a>

        <a href={{route('nhacungcap.index')}} class="sidebar-link @yield('sidebar-supplier')"><i class="fas fa-building mr-10"></i>Nhà cung cấp</a>

        <a href={{route('danhgia.index')}} class="sidebar-link @yield('sidebar-evaluate')"><i class="fas fa-star mr-10"></i>Đánh giá</a>

        <a href={{route('khuyenmai.index')}} class="sidebar-link @yield('sidebar-promotion')"><i class="fas fa-gift mr-10"></i>Khuyến mãi</a>

        <a href={{route('donhang.index')}} class="sidebar-link @yield('sidebar-order')"><i class="fas fa-file-alt mr-10"></i>Đơn hàng</a>

        <a href={{route('baohanh.index')}} class="sidebar-link @yield('sidebar-warranty')"><i class="fas fa-shield-alt mr-10"></i>Bảo hành</a>

        <a href={{route('taikhoan.index')}} class="sidebar-link @yield('sidebar-account')"><i class="fas fa-user mr-10"></i>Tài khoản</a>

        <a href={{route('giohang.index')}} class="sidebar-link @yield('sidebar-cart')"><i class="fas fa-shopping-cart mr-10"></i>Giỏ Hàng</a>

        <a href={{route('spyeuthich.index')}} class="sidebar-link @yield('sidebar-wishlist')"><i class="fas fa-heart mr-10"></i>Sản Phẩm Yêu Thích</a>

        <a href={{route('taikhoandiachi.index')}} class="sidebar-link @yield('sidebar-account-address')"><i class="fas fa-map-marker mr-10"></i>Tài Khoản Địa Chỉ</a>

        <a href={{route('thongbao.index')}} class="sidebar-link @yield('sidebar-notification')"><i class="fas fa-bell mr-10"></i>Thông Báo</a>

        <a href={{route('taikhoanvoucher.index')}} class="sidebar-link @yield('sidebar-account-voucher')"><i class="fas fa-ticket-alt mr-10"></i>Tài Khoản Voucher</a>

        <div class="ml-20 mr-20"><hr></div>
    </div>
</div>