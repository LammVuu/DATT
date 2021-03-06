
<div class="head-bg">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            {{-- Logo --}}
            <div class="w-7">
                <a href="{{route('user/index')}}" class='head-brand'>
                    <img src="images/logo/LDMobile-logo.png" alt="Logo">
                </a>
            </div>

            {{-- link --}}
            <div class="w-93">
                <div class="head-items">
                    <div class="d-flex align-items-center justify-content-lg-between">
                        {{-- điện thoại --}}
                        <a href="{{route('user/dien-thoai')}}" class='head-item pt-15 pb-15 white'>Điện thoại</a>
                        
                        {{-- tìm kiếm & giỏ hàng --}}
                        <div class="head-item">
                            {{-- tìm kiếm --}}
                            <div class='relative'>
                                <div class="head-input-grp">
                                    <input type="text" class='head-search-input' placeholder="Tìm kiếm">
                                    <span class='input-icon-right'><i class="fal fa-search"></i></span>
                                </div>

                                {{-- danh sách kết quả --}}
                                <div class="head-search-result border"></div>
                            </div>
            
                            {{-- giỏ hàng --}}
                            <div class="relative">
                                <a href="{{route('user/gio-hang')}}" class='head-cart ml-20'>
                                    <i class="fas fa-shopping-cart fz-32"></i>
                                    @if (session('user') && $data['cart']['qty'] != 0)
                                        <span class='head-qty-cart'>{{ $data['cart']['qty'] }}</span>
                                    @else
                                        <span class='head-qty-cart none-dp'>0</span>
                                    @endif
                                </a>
                                <div id="add-cart-success"></div>
                            </div>
                            
                        </div>
            
                        {{-- tài khoản --}}
                        @if (session('user'))
                            <div class='head-item mr-20'>
                                <div class='d-flex'>
                                    <?php $user = session('user'); ?>
                                    <img id="avt-header" src="{{ $user->htdn == 'nomal' ? $url_user.$user->anhdaidien : $user->anhdaidien }}" alt="avatar" width="60px" class="circle-img mr-10">
                                    
                                    <div class='d-flex flex-column justify-content-end'>
                                        <i class='white fz-14'>Xin Chào!</i>
                                        <div class='white head-account'>{{ session('user')->hoten }}<i class="fas fa-caret-down ml-5"></i>
                                            <div class='head-account-dropdown box-shadow'>
                                                <a href="{{route('user/tai-khoan')}}" class='head-account-option'><i class="fas fa-user mr-10"></i>Xem Tài khoản</a>
                                                <a href="{{route('user/tai-khoan-thong-bao')}}" class='head-account-option'>
                                                    <span><i class="fas fa-bell mr-10"></i>Thông báo</span>
                                                    @if (count($data['lst_noti']['noti']) != 0)
                                                        <div id="not-seen-qty-header" class='head-number fz-12'>{{$data['lst_noti']['not-seen']}}</div>
                                                    @else
                                                        <div class='head-number fz-12 none-dp'>0</div>
                                                    @endif
                                                </a>
                                                <a href="{{route('user/tai-khoan-don-hang')}}" class='head-account-option'>
                                                    <span><i class="fas fa-box mr-10"></i>Đơn hàng của tôi</span>
                                                    @if ($data['lst_order']['processing'] != 0)
                                                        <span class='head-number ml-20 fz-12'>{{$data['lst_order']['processing']}}</span>
                                                    @endif
                                                </a>
                                                <a href="{{route('user/logout')}}" class='head-account-option'><i class="far fa-power-off mr-10"></i>Đăng xuất</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- đăng nhập/đăng ký --}}
                            <div class="head-item">
                                <a href="{{route('user/dang-nhap')}}" class='head-item white'><i class="fas fa-user mr-10"></i>Đăng nhập</a>
                                <span class="ml-10 mr-10 fz-26 white">|</span>
                                <a href="{{route('user/dang-ky')}}" class='head-btn-signup'>Đăng ký</a>
                            </div>
                        @endif
                    </div>
                </div>     

                <div class='head-offcanvas'>
                    <div id='show-offcanvas' aria-expanded="false"><i class="fas fa-bars white fz-30"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='backdrop'></div>
<div class="head-offcanvas-box">
    {{-- nút đóng  --}}
    <div class='d-flex justify-content-end p-10'><div id='btn-close-offcanvas'><i class="fas fa-times fz-30 gray-1"></i></div></div>

    <div class='row'>
        <div class='col-md-10 mx-auto p-20'>
            <div class='d-flex flex-column justify-content-center'>
                <img src="images/logo/LDMobile-logo.png" class='head-offcanvas-img'><hr>

                 @if (session('user'))
                    <div class='d-flex justify-content-center align-items-center'>
                        <div class='head-offcanvas-avatar mr-20'>
                            <img src="{{$user->htdn == 'nomal' ? $url_user.$user->anhdaidien : $user->anhdaidien}}" alt="avatar" class="circle-img">
                        </div>
                        <div class='d-flex align-items-center'>
                            <div class='head-offcanvas-account'>
                                <b>Vũ Hoàng Lâm</b><i class="fas fa-caret-down ml-10"></i>
                                <div class='head-offcanvas-account-option'>
                                    <div class='d-flex flex-column fz-14'>
                                        <a href="#" class='options black'><i class="fas fa-user mr-10"></i>Xem tài khoản</a>
                                        <a href="#" class='d-flex align-items-center options black'>
                                            <span><i class="fas fa-box mr-10"></i>Đơn hàng của tôi</span>
                                            <div><div class='head-number fz-12'>50</div></div>
                                        </a>
                                        <a href="#" class="d-flex align-items-center options black">
                                            <span><i class="fas fa-bell mr-10"></i>Thông báo</span>
                                            <div class='head-number fz-12'>5</div>
                                        </a>
                                        <hr class='m-0'>
                                        <a href="#" class='options price-color'><i class="fal fa-power-off mr-10"></i>Đăng xuất</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class='d-flex justify-content-center align-items-center'>
                        <i class="fas fa-user mr-10"></i>
                        <a href="{{route('user/dang-nhap')}}" class='black fw-600'>Đăng nhập</a>
                        <b class='ml-10 mr-10'>|</b>
                        <a href="{{route('user/dang-ky')}}" class='main-btn p-5'>Đăng ký</a>
                    </div>
                @endif
                <hr>
                
                {{-- tìm kiếm --}}
                <div class='d-flex justify-content-center relative'>
                    <div class="head-input-grp pb-20 w-70">
                        <input type="text" class='head-search-input border' placeholder="Tìm kiếm">
                        <span class='input-icon-right'><i class="fal fa-search"></i></span>
                    </div>
                    {{-- danh sách kết quả --}}
                    <div class="head-search-result border">
                        @for ($i = 0; $i < 15; $i++)
                        <a href="#" class="head-single-result black fz-14">
                            <div class="d-flex">
                                <div class="w-25 p-10">
                                    <img src="images/phone/iphone_12_red.jpg" alt="">
                                </div>
                                <div class="d-flex flex-column w-75 p-10">
                                    <b>iPhone 12 PRO MAX 128GB</b>
                                    <div class="d-flex align-items-center mt-5">
                                        <span class="price-color fw-600">25.000.000<sup>đ</sup></span>
                                        <span class="text-strike ml-10">29.000.000<sup>đ</sup></span>
                                        <span class="price-color ml-10">-10%</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endfor
                    </div>
                </div>
                
            
                {{-- điện thoại --}}
                <div class='head-drop-2 head-offcanvas-item text-center pb-20'>
                    <a href="{{route('user/dien-thoai')}}">Điện thoại</a>
                </div>

                {{-- giỏ hàng --}}
                <div class='head-offcanvas-item pb-20'>
                    <div class='d-flex align-items-center justify-content-center'>
                        <a href="{{route('user/gio-hang')}}">
                            <div class='d-flex align-items-center'>Giỏ hàng
                                @if (session('user') && $data['cart']['qty'] != 0)
                                    <span class='head-number'>{{ $data['cart']['qty'] }}</span>
                                @else
                                    <span class='head-number none-dp'>0</span>
                                @endif
                            </div>
                        </a>
                    </div>
                </div><hr>

                {{-- gọi tư vấn --}}
                <div class='d-flex justify-content-start'>
                    <div class='d-flex flex-column pt-20'>
                        <b>Gọi tư vấn:</b>
                        <div class='d-flex flex-column mt-10 ml-30'>
                            <span><i class="fas fa-phone mr-5"></i>077 9792000</span>
                            <span><i class="fas fa-phone mr-5"></i>038 4151501</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    