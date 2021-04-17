
<div class="head-bg pt-10 pb-10">
    <div class="container-fluid">
        <div class='d-flex justify-content-between align-items-center'>
            {{-- Logo --}}
            <a href="{{route('user/index')}}" class='head-brand'>
                <img src="images/logo/LDMobile-logo.png" alt="Logo">
            </a>

            <div class='head-offcanvas'>
                <a href="#" id='show-offcanvas' aria-expanded="false"><i class="fas fa-bars white fz-30"></i></a>
            </div>
            
            
            {{-- sdt --}}
            <div class='head-item'>
                <a href="#" class="d-flex flex-column white">
                    <span><i class="fas fa-phone mr-5"></i>077 9792000</span>
                    <span><i class="fas fa-phone mr-5"></i>038 4151501</span>
                </a>
            </div>

            {{-- điện thoại --}}
            <div class='head-item head-drop pt-15 pb-15'>
                <a href="{{route('user/san-pham')}}" class='white'>Điện thoại<i class="fas fa-caret-down ml-10"></i></a>
                <div class='head-drop-content p-50 box-shadow'>
                    <div class='row'>
                        <div class='col-md-12'>
                            <div class='row'>
                                {{-- Apple --}}
                                <div class='col-md-2'>
                                    <div class='d-flex flex-column'>
                                        <a href="#" class='black font-weight-600 fz-20'>Apple</a>
                                        <div class='pt-10'>
                                            <a href="#" class='black'>iPhone 12 Series</a>
                                            <a href="#" class='black'>iPhone 11 Series</a>
                                            <a href="#" class='black'>iPhone SE 2020</a>
                                            <a href="#" class='black'>iPhone XR | XS</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Samsung --}}
                                <div class='col-md-2'>
                                    <div class='d-flex flex-column'>
                                        <a href="#" class='black font-weight-600 fz-20'>Samsung</a>
                                        <div class='pt-10'>
                                            <a href="#" class='black'>Galaxy Note</a>
                                            <a href="#" class='black'>Galaxy S</a>
                                            <a href="#" class='black'>Galaxy A</a>
                                            <a href="#" class='black'>Galaxy Z</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- xiaomi --}}
                                <div class='col-md-2'>
                                    <div class='d-flex flex-column'>
                                        <a href="#" class='black font-weight-600 fz-20'>Xiaomi</a>
                                        <div class='pt-10'>
                                            <a href="#" class='black'>Redmi</a>
                                            <a href="#" class='black'>Mi</a>
                                            <a href="#" class='black'>POCO</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- oppo --}}
                                <div class='col-md-2'>
                                    <div class='d-flex flex-column'>
                                        <a href="#" class='black font-weight-600 fz-20'>Oppo</a>
                                        <div class='pt-10'>
                                            <a href="#" class='black'>Oppo A</a>
                                            <a href="#" class='black'>Oppo Reno</a>
                                            <a href="#" class='black'>Oppo FindX</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- vivo --}}
                                <div class='col-md-2'>
                                    <div class='d-flex flex-column'>
                                        <a href="#" class='black font-weight-600 fz-20'>Vivo</a>
                                        <div class='pt-10'>
                                            <a href="#" class='black'>Vivo V</a>
                                            <a href="#" class='black'>Vivo Y</a>
                                        </div>
                                    </div>
                                </div>

                                {{-- logo --}}
                                <div class='col-md-2'>
                                    <img src="images/logo/LDMobile-logo.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- về chúng tôi --}}
            <div class='head-item'>
                <a href="#" class="white">Về chúng tôi</a>
            </div>

            {{-- liên hệ --}}
            <div class='head-item'>
                <a href="#" class="white">Liên hệ</a>
            </div>

            {{-- tìm kiếm --}}
            <div class='head-item'>
                <div class="head-input-grp">
                    <input type="text" class='head-search-input' placeholder="Tìm kiếm">
                    <span class='input-icon-right'><i class="fal fa-search"></i></span>
                </div>
            </div>

            {{-- giỏ hàng --}}
            <div class='head-item'>
                <a href="#" class='head-cart white'>
                    <i class="fas fa-shopping-cart" style='font-size: 32px'></i>
                    <span class='head-qty-cart'>10</span>
                </a>
                
            </div>

            {{-- tài khoản --}}
            <div class='head-item pl-40 pr-20'>
                <div class='d-flex'>
                    <img src="images/profile-photo.png" alt="avatar" class="head-brand mr-10">
                    <div class='d-flex flex-column justify-content-end'>
                        <i class='white fz-14'>Xin Chào!</i>
                        <div class='head-account'>Vũ hoàng lâm<i class="fas fa-caret-down ml-5"></i>
                            <div class='head-account-dropdown p-20'>
                                <div class='pt-10 pb-10'>
                                    <a href="#" class='black'><i class="fas fa-user mr-10"></i>Xem Tài khoản</a>
                                </div>
                                <div class='pt-10 pb-10'>
                                    <a href="#" class='d-flex align-items-center black'>
                                        <span><i class="fas fa-bell mr-10"></i>Thông báo</span>
                                        <div class='head-number fz-12'>50</div>
                                    </a>
                                </div>
                                <div class='pt-10 pb-10'>
                                    <a href="#" class='d-flex align-items-center black'>
                                        <span><i class="fas fa-truck mr-10"></i>Đơn hàng của tôi</span>
                                        <div><div class='head-number fz-12'>50</div></div>
                                    </a>
                                </div>
                                <div class='pt-10 pb-10'>
                                    <a href="#" class='price-color'><i class="fal fa-power-off mr-10"></i>Đăng xuất</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            {{-- <div class='head-item'>
                <a href="#" class='white'><i class="fas fa-user mr-10"></i>Đăng nhập</a>
            </div>

            
            <div class='head-item'>
                <a href="#" class='head-btn-signup'>Đăng ký</a>
            </div> --}}
        </div>
    </div>
</div>


{{-- giỏ hàng box --}}
<div class='head-cart-box box-shadow'>
    <table class='table'>
        <thead>
            <th colspan="4">
                <div class='d-flex justify-content-between pl-10 pr-10'>
                    Giỏ hàng
                    <button type='button' class='head-btn-close-cart'><i class="far fa-times"></i></button>
                </div>
            </th>
        </thead>
        <tbody>
            <?php for($i = 0; $i < 2; $i++) : ?>
            <tr>
                <td class='center-td d-flex flex-row'>
                    <img class='cart-img-pro' src="images/iphone/iphone_11_black.jpg">
                    <div class='cart-pro-info'>
                        <a href="#" style='color:black'><b>iPhone 11 PRO MAX</b></a>
                        <i>Màu sắc: Đen</i>
                        <i>Dung lượng: 128GB</i>
                    </div>
                </td>
                <td class='center-td'>
                    <div>
                        <div class='cart-qty-input d-flex justify-content-between'>
                            <button type='button' data-id='<?php echo $i ?>' class='minus'><i class="fas fa-minus"></i></button>
                            <b id=<?php echo 'qty_' . $i ?>>1</b>
                            <button type='button' data-id='<?php echo $i ?>' class='plus'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </td>
                <td class='center-td'><b>25.000.000 <sup>đ</sup></b></td>
                <td class='center-td'><a href="#" style='font-size: 20px; color: red'><i class="fas fa-trash"></i></a></td>
            </tr>
            <?php endfor ?>
        </tbody>
    </table>
    <div class='d-flex flex-column pt-20 pb-20 pl-20 pr-20'>
        <div class='cart-total d-flex flex-row align-items-center justify-content-between'>
            <h4>TỔNG TIỀN</h4>
            <h4>50.000.000 <sup>đ</sup></h4>
        </div>
        <div class='d-flex flex-row'>
            <a href={{route('user/gio-hang')}} class='btn-view-cart'>Xem giỏ hàng</a>
            <a href="#" class='btn-checkout'>Thanh Toán</a>
        </div>
    </div>  
</div>

<div class='backdrop'></div>
<div class="head-offcanvas-box">
    {{-- nút đóng  --}}
    <div class='d-flex justify-content-end p-10'><a href="#" id='btn-close-offcanvas'><i class="fas fa-times fz-30 gray-1"></i></a></div>

    <div class='row'>
        <div class='col-md-10 mx-auto p-20'>
            <div class='d-flex flex-column justify-content-center'>
                <img src="images/logo/LDMobile-logo.png" class='head-offcanvas-img'><hr>

                 {{-- đăng nhập/ đăng ký --}}
                <div class='d-flex justify-content-center align-items-center'>
                    <div class='head-offcanvas-avatar mr-20'>
                        <img src="images/profile-photo.png" alt="">
                    </div>
                    <div class='d-flex align-items-center'>
                        <div class='head-offcanvas-account' aria-expanded="false">
                            <b>Vũ Hoàng Lâm</b><i class="fas fa-caret-down ml-10"></i>
                            <div class='head-offcanvas-account-option'>
                                <div class='d-flex flex-column fz-14'>
                                    <a href="#" class='options black'><i class="fas fa-user mr-10"></i>Xem tài khoản</a>
                                    <a href="#" class='d-flex align-items-center options black'>
                                        <span><i class="fas fa-truck mr-10"></i>Đơn hàng của tôi</span>
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
                </div><hr>

                {{-- <div class='d-flex justify-content-center align-items-center'>
                    <i class="fas fa-user mr-10"></i>
                    <a href="#" class='black font-weight-600'>Đăng nhập</a>
                    <b class='ml-10 mr-10'>|</b>
                    <a href="#" class='head-offcanvas-btn-signup'>Đăng ký</a>
                </div><hr> --}}
                
                {{-- tìm kiếm --}}
                <div class='d-flex justify-content-center'>
                    <div class="head-input-grp pb-20" style='width: 95%;'>
                        <input type="text" class='head-search-input' placeholder="Tìm kiếm" style='border: 1px solid #dcdcdc'>
                        <span class='input-icon-right'><i class="fal fa-search"></i></span>
                    </div>
                </div>
                
            
                {{-- điện thoại --}}
                <div class='head-drop-2 head-offcanvas-item text-center pb-20'>
                    <a href="{{route('user/san-pham')}}">Điện thoại<i class="fas fa-caret-right ml-5"></i></a>
                    <div class='head-drop-content-2 box-shadow pt-50 pb-50 pl-50 pr-50 fz-16'>
                            {{-- Apple --}}
                            <div>
                                <div class='d-flex flex-column'>
                                    <a href="#" class='black font-weight-600 fz-20'>Apple</a>
                                    <div class='d-flex flex-column pt-10'>
                                        <a href="#" class='black'>iPhone 12 Series</a>
                                        <a href="#" class='black'>iPhone 11 Series</a>
                                        <a href="#" class='black'>iPhone SE 2020</a>
                                        <a href="#" class='black'>iPhone XR | XS</a>
                                    </div>
                                </div><hr>
                            </div>
            
                            {{-- Samsung --}}
                            <div>
                                <div class='d-flex flex-column'>
                                    <a href="#" class='black font-weight-600 fz-20'>Samsung</a>
                                    <div class='d-flex flex-column pt-10'>
                                        <a href="#" class='black'>Galaxy Note</a>
                                        <a href="#" class='black'>Galaxy S</a>
                                        <a href="#" class='black'>Galaxy A</a>
                                        <a href="#" class='black'>Galaxy Z</a>
                                    </div>
                                </div><hr>
                            </div>
            
                            {{-- xiaomi --}}
                            <div>
                                <div class='d-flex flex-column'>
                                    <a href="#" class='black font-weight-600 fz-20'>Xiaomi</a>
                                    <div class='d-flex flex-column pt-10'>
                                        <a href="#" class='black'>Redmi</a>
                                        <a href="#" class='black'>Mi</a>
                                        <a href="#" class='black'>POCO</a>
                                    </div>
                                </div><hr>
                            </div>
            
                            {{-- oppo --}}
                            <div>
                                <div class='d-flex flex-column'>
                                    <a href="#" class='black font-weight-600 fz-20'>Oppo</a>
                                    <div class='d-flex flex-column pt-10'>
                                        <a href="#" class='black'>Oppo A</a>
                                        <a href="#" class='black'>Oppo Reno</a>
                                        <a href="#" class='black'>Oppo FindX</a>
                                    </div>
                                </div><hr>
                            </div>
            
                            {{-- vivo --}}
                            <div>
                                <div class='d-flex flex-column'>
                                    <a href="#" class='black font-weight-600 fz-20'>Vivo</a>
                                    <div class='d-flex flex-column pt-10'>
                                        <a href="#" class='black'>Vivo V</a>
                                        <a href="#" class='black'>Vivo Y</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- giỏ hàng --}}
                <div class='head-offcanvas-item pb-20'>
                    <div class='d-flex align-items-center justify-content-center'>
                        <a href="{{route('user/gio-hang')}}">
                            <div class='d-flex align-items-center'>Giỏ hàng
                                <div class='head-number'>5</div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- về chúng tôi --}}
                <div class='head-offcanvas-item text-center pb-20'>
                    <a href="#">Về chúng tôi</a>
                </div>

               {{-- liên hệ --}}
               <div class='head-offcanvas-item text-center'>
                    <a href="#">Liên hệ</a>
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
    