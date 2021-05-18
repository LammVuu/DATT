<div class='row'>
    <div class='col-md-3'>
        @section("acc-order-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <div class='d-flex justify-content-between align-items-center box-shadow p-10 fz-22'>
            <div>
                <span>Chi tiết đơn hàng</span>
                <span class='gray-1 ml-10 mr-10'>#123</span>    
            </div>
            <div class='account-deliver-success'>Giao hàng thành công</div>
            {{-- <div class='account-deliver-fail'>Đã hủy</div> --}}
        </div>   
        <div class="d-flex justify-content-end mt-5">
            <span>Ngày mua: <b>12:00 19/04/2021</b></span>
        </div> 

        <div class='mt-50'>
            <div class='row'>
                {{-- địa chỉ người nhận --}}
                <div class='col-md-4 col-sm-12'>
                    <span class='pb-10'>Địa chỉ người nhận</span>
                    <div id='DCNN-div'>
                        <div class='box-shadow p-10'>
                            <div class="d-flex flex-column fz-14">
                                <b class='text-uppercase pb-5'>Vũ Hoàng Lâm</b>
                                <span class='pb-5'>
                                    Địa chỉ: 403/10, Đường Lê Văn Sỹ, Phường 2, Quận Tân Bình, Hồ Chí Minh
                                </span>
                                <span>
                                    SĐT: 0779792000
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- hình thức giao hàng --}}
                <div class='col-md-4 col-sm-12'>
                    <span class='pb-10'>Hình thức giao hàng</span>
                    <div id='HTGH-div'>
                        <div class='box-shadow p-10' style='height: 100%'>
                            <span>Giao hàng tận nơi</span>
                        </div>
                    </div>
                </div>
                {{-- hình thức thanh toán --}}
                <div class='col-md-4 col-sm-12'>
                    <div id='HTTT-div'>
                        <span class='pb-10'>Hình thức thanh toán</span>
                        <div class='box-shadow p-10' style='height: 100%'>
                            <span>Thanh toán khi nhận hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- danh sách sản phẩm --}}
        <div class='mt-50 box-shadow'>
            <table class='table'>
                <thead>
                    <tr>
                        <th><div class='pt-10 pb-10'>Sản phẩm</div></th>
                        <th><div class='pt-10 pb-10'>Giá</div></th>
                        <th><div class='pt-10 pb-10'>Số lượng</div></th>
                        <th><div class='pt-10 pb-10'>Giảm giá</div></th>
                        <th><div class='pt-10 pb-10'>Tạm tính</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($j = 0; $j < 3; $j++) : ?>
                    <tr>
                        <td class='account-td-40'>
                            <div class='d-flex flex-row'>
                                <img src="images/phone/iphone/iphone_12_red.jpg" alt="" width="100px">
                                <div class='d-flex flex-column fz-14'>
                                    <a href='#' class="black font-weight-600">iPhone 12 PRO MAX</a>
                                    <span>Dung Lượng: 128GB</span>
                                    <span>Màu: Đỏ</span>
                                </div>
                            </div>
                        </td>
                        <td class="center-td">
                            <span>25.000.000 VND</span>
                        </td>
                        <td class="center-td">
                            <span>2</span>
                        </td>
                        <td class="center-td">
                            <span>-3.000.000 VND</span>
                        </td>
                        <td class="center-td">
                            <span>22.000.000 VND</span>
                        </td>
                    </tr>
                    <?php endfor ?>
                    {{-- mã giảm giá --}}
                    <tr>
                        <td colspan="5">
                            <div class='d-flex align-items-center p-10'>
                                <i class="fas fa-ticket-alt mr-10"></i>
                                Mã giảm giá:
                                <span class='ml-20 font-weight-600 main-color-text'>ABCDEF</span>
                                <span class="ml-10 mr-10">|</span>
                                <span class="main-color-text">Giảm 10% cho đơn hàng từ 5.000.000 VND</span>
                            </div>
                        </td>
                    </tr>
                    {{-- tính tiền --}}
                    <tr>
                        <td colspan="account-td-40">
                            <div class='p-10'>
                                <div class='d-flex justify-content-between pb-5'>
                                    <span>Tạm tính:</span>
                                    <span>22.000.000 VND</span>
                                </div>
                                <div class='d-flex justify-content-between pb-5'>
                                    <span>Giảm giá:</span>
                                    <span>-2.000.000 VND</span>
                                </div>
                                <div class='d-flex justify-content-between pb-5'>
                                    <span>Mã giảm giá:</span>
                                    <span class="main-color-text">-2.000.000 VND</span>
                                </div>
                                <div class='d-flex justify-content-between font-weight-600 pt-10'>
                                    <span>TỔNG TIỀN:</span>
                                    <span class="price-color">22.000.000 VND</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- quay về --}}
                    <tr>
                        <td colspan="5">
                            <div class='p-10'>
                                <a href="#"><i class="fas fa-chevron-left mr-10"></i>Quay về</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>