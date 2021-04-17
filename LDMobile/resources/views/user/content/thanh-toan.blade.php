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
                            <label for="HoTen" class='form-label font-weight-600'>Họ và tên (Bắt buộc)</label>
                            <input type="text" name='HoTen' class='form-control' required>
                        </div>
                        <div class='col-md-6'>
                            <label for="SDT" class='form-label font-weight-600'>Số điện thoại đặt hàng (Bắt
                                buộc)</label>
                            <input type="tel" name='SDT' class='form-control' pattern="[0-9][0-9][0-9]" required>
                        </div>
                    </div>

                    {{-- cách thức nhận hàng --}}
                    <div class='row'>
                        <div class='col-md-8 mb-20'>
                            <label for="CachThucNhanHang" class='form-label font-weight-600'>Chọn cách thức nhận
                                hàng</label>
                            <div name='CachThucNhanHang' class='d-flex'>
                                <div class='mr-20'>
                                    <input type="radio" name='NhanHang' id='TaiCuaHang'>
                                    <label for="TaiCuaHang">Nhận tại cửa hàng</label>
                                </div>
                                <div>
                                    <input type="radio" name='NhanHang' id="TaiNha">
                                    <label for="TaiNha">Giao hàng tận nơi</label>
                                </div>
                            </div>

                            <div class='atStore p-20'>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <select name="city" id='address' class='form-select'>
                                            <option value="0" selected>Chọn chi nhánh</option>
                                            <option value="1">Hà Nội</option>
                                            <option value="2">TP. Hồ CHí Minh</option>
                                        </select>
                                    </div>
                                </div>

                                <div class='detail-branch-list white-bg mt-10'>
                                    <table class="table">
                                        <tbody>
                                            <?php for($i =0; $i < 15; $i++) : ?>
                                            <tr>
                                                <td>
                                                    <input type="radio" name="branch" id='<?php echo 'branch_' . $i ?>'
                                                        class='branch' data-id='<?php echo $i ?>'>
                                                    <label for="<?php echo 'branch_' . $i ?>">403/10, Lê Văn Sỹ, P.2,
                                                        Q.Tân Bình</label>
                                                </td>
                                            </tr>
                                            <?php endfor ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class='atHome p-20'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <div class='mb-3'>
                                            <label for="DiaChi" class='form-label font-weight-600'>Chọn địa chỉ giao
                                                hàng</label>
                                            <div class='row' name='DiaChi'>
                                                <div class='col-md-6 mb-3'>
                                                    <select class='form-select'>
                                                        <option value="">Chọn tỉnh thành</option>
                                                        <option value="">Chọn tỉnh thành</option>
                                                    </select>
                                                </div>
                                                <div class='col-md-6 mb-3'>
                                                    <select class='form-select'>
                                                        <option value="">Chọn tỉnh thành</option>
                                                    </select>
                                                </div>
                                                <div class='col-md-6 mb-3'>
                                                    <select class='form-select'>
                                                        <option value="">Chọn tỉnh thành</option>
                                                    </select>
                                                </div>
                                                <div class='col-md-6 mb-3'>
                                                    <input type="text" class='form-control'
                                                        placeholder="Số nhà, tên đường" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3>Phương thức thanh toán</h3>
                <hr>
                <div class='pb-20'>
                    {{-- phương thức thanh toán --}}
                    <div class='row'>
                        <div class='col-md-12'>
                            <div name='PTTT'>
                                <div class='mb-3'>
                                    <input type="radio" name='PTTT' id="money">
                                    <label for="money">Thanh toán khi nhận hàng (free ship)</label>
                                </div>
                                <div class='mb-3'>
                                    <input type="radio" name='PTTT' id="online">
                                    <label for="online">Thanh toán online</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class='col-md-8 mx-auto pt-20 pb-20'>
                    <button type="button" class="checkout-btn-continue p-10" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">TIẾP TỤC</button>
                </div>
            </div>
            <div class='col-md-4'>
                <h3>Giỏ hàng</h3>
                <hr>
                <div class='pt-20 pb-20'>
                    <div class='checkout-cart-box box-shadow'>
                        <div class='d-flex justify-content-end white-bg p-10'>
                            <a href="#collapseExample" class='checkout-btn-collapse-cart' data-bs-toggle="collapse"
                                href="#collapseExample" role="button" aria-expanded="true"
                                aria-controls="collapseExample"><i class="fas fa-chevron-up"></i></a>
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
                                                    <img src="images/iphone/iphone_12_red.jpg" alt="" width="80px">
                                                    <div class='d-flex flex-column ml-5 fz-12'>
                                                        <b>iPhone 12 PRO MAX</b>
                                                        <span>Dung lượng: 128GB</span>
                                                        <span>Màu sắc: Đỏ</span>
                                                        <span>Số lượng: 2</span>
                                                    </div>
                                                </div>
                                                <b class='d-flex justify-content-end price-color'>26.000.000 VND</b>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endfor ?>
                                    {{-- mã giảm giá --}}
                                    <tr>
                                        <td>
                                            <div class='p-10'>
                                                <span>Mã giảm giá:<b class='main-color-text ml-10'>ABCDEF</b></span>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- tính tiền --}}
                                    <tr>
                                        <td>
                                            <div class='p-10 d-flex flex-column'>
                                                <div class='d-flex justify-content-between pad-5'>
                                                    <span>Tạm tính(4 sản phẩm):</span>
                                                    <b>70.000.000 VND</b>
                                                </div>
                                                <div class='d-flex justify-content-between pad-5'>
                                                    <span>Giảm:</span>
                                                    <b>-3.000.000 VND</b>
                                                </div>
                                                <div class='d-flex justify-content-between pad-5'>
                                                    <span>Mã giảm giá:</span>
                                                    <b class='main-color-text'>-3.000.000 VND</b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- tổng tiền --}}
                                    <tr>
                                        <td>
                                            <div class='d-flex justify-content-between pad-5'>
                                                <b>Tổng tiền:</b>
                                                <b class='price-color'>70.000.000 VND</b>
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

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Xác nhận thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
