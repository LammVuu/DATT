@extends("user.layout")
@section("content")

@section("direct")THANH TOÁN @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-8'>
                <h3>Thông tin mua hàng</h3>
                <hr>
                <div class='pb-20'>
                    {{-- tên & sdt --}}
                    <div class='row mb-20'>
                        <div class='col-md-6'>
                            <label for="HoTen" class='font-weight-600 mb-5'>Họ và tên (Bắt buộc)</label>
                            <input type="text" id='HoTen' name='checkout-inp' required>
                            <span class="required-text">Vui lòng nhập họ và tên</span>
                        </div>
                        <div class='col-md-6'>
                            <label for="SDT" class='font-weight-600 mb-5'>Số điện thoại đặt hàng (Bắt buộc)</label>
                            <input type="tel" id='SDT' name='checkout-inp'pattern="[0-9]{3}[0-9]{3}[0-9]{4}" required>
                            <span class="required-text">Vui lòng nhập số diện thoại</span>
                        </div>
                    </div>

                    {{-- cách thức nhận hàng --}}
                    <div class='row'>
                        <div class='col-md-10 mb-20'>
                            <label for="CachThucNhanHang" class='form-label font-weight-600'>Chọn cách thức nhận hàng</label>
                            <div id='CachThucNhanHang' class='d-flex'>
                                <div class='mr-20'>
                                    <input type="radio" checked name='receive-method' id="TaiNha" value='atHome'>
                                    <label for="TaiNha">Giao hàng tận nơi</label>
                                </div>
                                <div>
                                    <input type="radio" name='receive-method' id='TaiCuaHang' value='atStore'>
                                    <label for="TaiCuaHang">Nhận tại cửa hàng</label>
                                </div>
                            </div>

                            {{-- giao hàng tận nơi --}}
                            <div class='atHome p-20 mt-10'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <label for="DiaChi" class='form-label font-weight-600'>Chọn địa chỉ giao hàng</label>
                                        <div class='row' id='DiaChi'>
                                            {{-- chọn tỉnh thành --}}
                                            <div class='col-md-6 mb-3'>
                                                <div class="select">
                                                    <div id='TinhThanh-selected' class="select-selected">
                                                        <div id='TinhThanh-name'>
                                                            <?php echo $lstTinhThanh[0]['Name'] ?>
                                                        </div>
                                                        <i class="far fa-chevron-down fz-14"></i>
                                                    </div>
                                                    <div id='TinhThanh-box' class="select-box">
                                                        {{-- tìm kiếm --}}
                                                        <div class="select-search">
                                                            <input id='search-tinh-thanh' type="text" class="select-search-inp" placeholder="Nhập tên Tỉnh / Thành">
                                                            <i class="select-search-icon far fa-search"></i>
                                                        </div>

                                                        {{-- option --}}
                                                        <div id='list-tinh-thanh' class="select-option">
                                                            @foreach($lstTinhThanh as $lst)
                                                            <div id='<?php echo $lst['ID'] ?>' data-type='<?php echo $lst['Name'] . '/TinhThanh' ?>' class="option-tinhthanh select-single-option"><?php echo $lst['Name'] ?></div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- chọn quận huyện --}}
                                            <div class='col-md-6 mb-3'>
                                                <div class="select">
                                                    <div id='QuanHuyen-selected' class="select-selected">
                                                        <div id='QuanHuyen-name'>Chọn Quận / Huyện</div>
                                                        <i class="far fa-chevron-down fz-14"></i>
                                                    </div>
                                                    <span class="required-text">Vui lòng chọn quận / huyện</span>
                                                    
                                                    <div id='QuanHuyen-box' class="select-box">
                                                        {{-- tìm kiếm --}}
                                                        <div class="select-search">
                                                            <input id='search-quan-huyen' type="text" class="select-search-inp" placeholder="Nhập tên Quận / Huyện">
                                                            <i class="select-search-icon far fa-search"></i>
                                                        </div>

                                                        {{-- option --}}
                                                        <div id='list-quan-huyen' class="select-option">
                                                            @foreach($lstQuanHuyen as $lst)
                                                            <div id='<?php echo $lst['ID'] ?>' data-type='<?php echo $lst['Name'] . '/QuanHuyen' ?>' class="option-quanhuyen select-single-option"><?php echo $lst['Name'] ?></div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- chọn phường xã --}}
                                            <div class='col-md-6'>
                                                <div class="select">
                                                    <div id='PhuongXa-selected' class="select-disable">
                                                        <div id="PhuongXa-name">Chọn Phường / Xã</div>
                                                        <i class="far fa-chevron-down fz-14"></i>
                                                    </div>
                                                    <span class="required-text">Vui lòng chọn phường / xã</span>

                                                    <div id='PhuongXa-box' class="select-box">
                                                        {{-- tìm kiếm --}}
                                                        <div class="select-search">
                                                            <input id='search-phuong-xa' type="text" class="select-search-inp" placeholder="Nhập tên Phường / Xã">
                                                            <i class="select-search-icon far fa-search"></i>
                                                        </div>

                                                        {{-- option --}}
                                                        <div id='list-phuong-xa' class="select-option"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- số nhà, tên đường --}}
                                            <div class='col-md-6'>
                                                <input id='address-inp' type="text" name='checkout-inp' placeholder="Số nhà, tên đường" required>
                                                <span class="required-text">Vui lòng nhập địa chỉ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- nhận tại cửa hàng --}}
                            <div class='atStore p-20 mt-10'>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="select">
                                            <div id='area-selected' class="select-selected">
                                                <div id='area-name'>Chọn khu vực</div>
                                                <i class="far fa-chevron-down fz-14"></i>
                                            </div>
                                            <span class="required-text">Vui lòng khu vực</span>

                                            <div id='area-box' class="select-box">
                                                {{-- option --}}
                                                <div class="select-option">
                                                    @foreach ($lstKhuVuc as $lst)
                                                        <div class="option-area select-single-option" data-area='<?php echo $lst['ID'] ?>'><?php echo $lst['TenTT'] ?></div>
                                                    @endforeach 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="list-branch">
                                    @foreach ($lstChiNhanh as $lst)
                                        <div class="single-branch" data-area='<?php echo $lst['ID_TT'] ?>'>
                                            <input type="radio" name='branch' id='<?php echo 'branch-'. $lst['ID'] ?>' value='<?php echo $lst['ID'] ?>'>

                                            <label for="<?php echo 'branch-' . $lst['ID'] ?>" class="branch-text">
                                                <i class="fas fa-store mr-5"></i>
                                                <?php echo $lst['DiaChi'] ?>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="required-text">Vui lòng cửa hàng để nhận hàng</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h3>Phương thức thanh toán</h3>
                <hr>
                <div class='pb-20'>
                    {{-- phương thức thanh toán --}}
                    <div class='mb-3 d-flex align-items-center'>
                        <input type="radio" name='payment-method' id="cash" checked>
                        <label for="cash">Thanh toán khi nhận hàng</label>
                    </div>
                    <div class='mb-3'>
                        <input type="radio" name='payment-method' id="zalopay">
                        <label for="zalopay">Thanh toán online</label>
                        <div class="ml-20 mt-5">
                            <img src="images/logo/zalopay-logo.png" alt="" class="ml-10 w-10">
                            <span class="fz-14 ml-10 mr-10">Cổng thanh toán ZaloPay</span>
                            <img src="images/icon/atm-card-icon.png" alt="" class='checkout-payment-icon'>
                            <img src="images/icon/visa-card-icon.png" alt="" class='checkout-payment-icon'>
                            <img src="images/icon/master-card-icon.png" alt="" class='checkout-payment-icon'>
                            <img src="images/icon/jcb-card-icon.png" alt="" class='checkout-payment-icon'>
                        </div>
                    </div>
                </div>
                <hr>
                <div class='col-md-8 mx-auto pt-20 pb-20'>
                    <div id='btn-confirm-checkout' type="button" class="checkout-btn w-100 p-10">ĐẶT HÀNG</div>
                    <div class="text-center pt-5">(Vui lòng kiểm tra lại đơn hàng trước khi Đặt mua)</div>
                </div>
            </div>

            {{-- giỏ hàng --}}
            <div class='col-md-4'>
                <h3>Giỏ hàng</h3>
                <hr>
                <div class='pt-20 pb-20'>
                    <div class='checkout-cart-box box-shadow'>
                        <div class='d-flex justify-content-end white-bg p-10'>
                            <div href="#collapseExample" class='checkout-btn-collapse-cart' data-bs-toggle="collapse"
                                href="#collapseExample" role="button" aria-expanded="true"
                                aria-controls="collapseExample"><i class="fas fa-chevron-up"></i></div>
                        </div>
                        <div class="collapse show fz-14" id="collapseExample">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class='p-0'></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#"><i class="fas fa-chevron-left mr-5"></i>Chỉnh sửa giỏ hàng</a>
                                        </td>
                                    </tr>
                                    {{-- sản phẩm trong giỏ hàng --}}
                                    <?php for($i = 0; $i < 2; $i++) : ?>
                                    <tr>
                                        <td>
                                            <div
                                                class='d-flex flex-row align-items-center justify-content-between fz-14'>
                                                <div class='d-flex'>
                                                    <img src="images/phone/iphone/iphone_12_red.jpg" alt="" width="80px">
                                                    <div class='d-flex flex-column ml-5 fz-12'>
                                                        <b>iPhone 12 PRO MAX</b>
                                                        <span>Dung lượng: 128GB</span>
                                                        <span>Màu sắc: Đỏ</span>
                                                        <b>Số lượng: 2</b>
                                                    </div>
                                                </div>
                                                <b class='d-flex align-items-center justify-content-end price-color'>26.000.000<sup>đ</sup></b>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endfor ?>
                                    {{-- mã giảm giá --}}
                                    <tr>
                                        <td class="p-0 d-flex">
                                            <div class='w-30 p-10 bg-gray-4 d-flex justify-content-center align-items-center'>
                                                <b></i>Mã khuyến mãi</b>
                                            </div>
                                            <div class="w-70 p-10 d-flex justify-content-center">
                                                {{-- mã --}}
                                                <div class="w-97">
                                                    <div class='account-voucher'>
                                                        {{-- số phần trăm giảm --}}
                                                        <div class='voucher-left-small w-20 p-30'>
                                                            <div class='voucher-left-small-content fz-18'>-10%</div>
                                                        </div>
                                                        {{-- nội dung --}}
                                                        <div class='voucher-right-small w-80 d-flex align-items-center justify-content-between p-10'>
                                                            {{-- icon xem chi tiết --}}
                                                            <div>Giảm 10%...</div>
                                                            <div class="relative promotion-info-icon">
                                                                <i class="fal fa-info-circle main-color-text fz-20"></i>
                                                                <div class='voucher-content box-shadow p-20 '>
                                                                    <table class='table'>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class='account-td-40'>Mã</td>
                                                                                <td><b>ABCDEF</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class='account-td-40'>Hạn sử dụng</td>
                                                                                <td>31/12/2021</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" class='account-td-40'>
                                                                                    <div class='d-flex flex-column'>
                                                                                        <span>Điều kiện:</span>
                                                                                        <ul class='mt-10'>
                                                                                            <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="main-btn p-5">Bỏ chọn</div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                {{-- chọn khuyến mãi --}}
                                                {{-- <span class="pointer-cs main-color-text" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                                                    <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
                                                </span> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- tính tiền --}}
                                    <tr>
                                        <td>
                                            <div class='p-10 d-flex flex-column'>
                                                <div class='d-flex justify-content-between pt-5 pb-5'>
                                                    <span>Tạm tính(4 sản phẩm):</span>
                                                    <b>70.000.000<sup>đ</sup></b>
                                                </div>
                                                <div class='d-flex justify-content-between pt-5 pb-5'>
                                                    <span>Giảm:</span>
                                                    <b>-3.000.000<sup>đ</sup></b>
                                                </div>
                                                <div class='d-flex justify-content-between pt-5 pb-5'>
                                                    <span>Mã giảm giá:</span>
                                                    <b class='main-color-text'>-3.000.000<sup>đ</sup></b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- tổng tiền --}}
                                    <tr>
                                        <td>
                                            <div class='d-flex align-items-center justify-content-between p-10'>
                                                <b>Tổng tiền:</b>
                                                <b class='price-color fz-22'>70.000.000<sup>đ</sup></b>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- modal chọn khuyến mãi --}}
<div class="modal fade" id="modal-promotion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-between p-20">
                    <h4>Mã khuyến mãi của tôi</h4>
                    <button type='button' class="btn-close" data-bs-dismiss='modal'></button>
                </div>
                <div class="cart-list-pro pl-50 pr-50 mt-20">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="pb-30">
                            <div class='account-voucher'>
                                {{-- số phần trăm giảm --}}
                                <div class='voucher-left w-20 p-70'>
                                    <div class='voucher-left-content fz-40'>-10%</div>
                                </div>
                                {{-- nội dung --}}
                                <div class='voucher-right w-80'>
                                    <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                        {{-- icon xem chi tiết --}}
                                        <div class="d-flex justify-content-end">
                                            <div class="relative promotion-info-icon">
                                                <i class="fal fa-info-circle fz-20"></i>
                                                <div class='voucher-content box-shadow p-20 '>
                                                    <table class='table'>
                                                        <tbody>
                                                            <tr>
                                                                <td class='account-td-40'>Mã</td>
                                                                <td><b>ABCDEF</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td class='account-td-40'>Hạn sử dụng</td>
                                                                <td>31/12/2021</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class='account-td-40'>
                                                                    <div class='d-flex flex-column'>
                                                                        <span>Điều kiện:</span>
                                                                        <ul class='mt-10'>
                                                                            <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- nội dung --}}
                                        <div class="flex-fill">
                                            <span>Áp dụng cho đơn hàng từ 5.000.000 VND</span>
                                        </div>
                                        {{-- hạn sử dụng --}}
                                        <div class="d-flex justify-content-between">
                                            <span class="d-flex align-items-end">HSD: 31/12/2021</span>
                                            <div class="main-btn p-5">Áp dụng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                    <div class="pb-30">
                        <div class='account-voucher'>
                            {{-- số phần trăm giảm --}}
                            <div class='dis-voucher-left w-20 p-70'>
                                <div class='dis-voucher-left-content fz-40'>-10%</div>
                            </div>
                            {{-- nội dung --}}
                            <div class='dis-voucher-right w-80'>
                                <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                    {{-- icon xem chi tiết --}}
                                    <div class="d-flex justify-content-end">
                                        <div class="relative dis-promotion-info-icon">
                                            <i class="fal fa-info-circle fz-20"></i>
                                            <div class='voucher-content box-shadow p-20 '>
                                                <table class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='account-td-40'>Mã</td>
                                                            <td><b>ABCDEF</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='account-td-40'>Hạn sử dụng</td>
                                                            <td>31/12/2021</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class='account-td-40'>
                                                                <div class='d-flex flex-column'>
                                                                    <span>Điều kiện:</span>
                                                                    <ul class='mt-10'>
                                                                        <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- nội dung --}}
                                    <div class="flex-fill">
                                        <span>Áp dụng cho đơn hàng từ 5.000.000 VND</span>
                                    </div>
                                    {{-- hạn sử dụng --}}
                                    <div class="d-flex justify-content-between">
                                        <span class="d-flex align-items-end">HSD: 31/12/2021</span>
                                        <div class="dis-condition-tag">Chưa thỏa điều kiện</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
