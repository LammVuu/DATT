<div class='row'>
    <div class='col-md-3'>
        @section("acc-order-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <table class='table border'>
            <thead>
                <tr>
                    <th>
                        <div class='pt-15 pb-15'>Mã đơn hàng</div>
                    </th>
                    <th>
                        <div class='pt-15 pb-15'>Ngày mua</div>
                    </th>
                    <th>
                        <div class='pt-15 pb-15'>Sản phẩm</div>
                    </th>
                    <th>
                        <div class='pt-15 pb-15'>Tổng tiền</div>
                    </th>
                    <th>
                        <div class='pt-15 pb-15'>Trạng thái đơn hàng</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 0; $i < 5; $i++) : ?>
                <tr>
                    {{-- mã đơn hàng --}}
                    <td class='center-td'>
                        <div class='p-20'>
                            <a href="{{route('user/tai-khoan-chi-tiet-don-hang')}}"><?php echo $i ?></a>
                        </div>
                    </td>
                    {{-- ngày mua --}}
                    <td class='center-td'>
                        <div>18/04/2021</div>
                    </td>
                    {{-- sản phẩm mua --}}
                    <td class='account-td-40'>
                        <div class='pt-15 pb-15'>
                            <div class='d-flex justify-content-between align-items-end'>
                                <div id=<?php echo 'temp_' . $i ?> class='d-flex'>
                                    <img src="images/phone/iphone_12_red.jpg" alt="" width="60px">
                                    <div class="d-flex flex-column fz-14">
                                        <div><span>iPhone 12 Pro Max</span><span class='ml-10'>Màu: Đỏ</span></div>
                                        <span>Dung lượng: 128GB</span>
                                        <span>Số lượng: 2</span>
                                    </div>
                                </div>
                                <a data-bs-toggle="collapse" id=<?php echo $i ?> class='show-order-list-pro' data-id=<?php echo $i ?> href=<?php echo "#acc-order-list-pro-" . $i ?> role="button" aria-expanded="false" aria-controls="collapseExample">Xem thêm</a>
                            </div>
                            {{-- danh sách sản phẩm mua --}}
                            <div class="collapse border" id=<?php echo "acc-order-list-pro-" . $i ?>>
                                <?php for($j = 0; $j < 5; $j++) : ?>
                                <div class='d-flex justify-content-between align-items-end pt-10 pb-10'>
                                    <div class='d-flex'>
                                        <img src="images/phone/iphone_12_red.jpg" alt="" width="60px">
                                        <div class="d-flex flex-column fz-14">
                                            <div><span>iPhone 12 Pro Max</span><span class='ml-10'>Màu: Đỏ</span></div>
                                            <span>Dung lượng: 128GB</span>
                                            <span>Số lượng: 2</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endfor ?>
                            </div>
                        </div>
                    </td>
                    {{-- giá  --}}
                    <td class='center-td'>
                        <div>15.000.000 VND</div>
                    </td>
                    {{-- trạng thái đơn hàng --}}
                    <td class='center-td'>
                        <div>Giao hàng thành công</div>
                    </td>
                </tr>
                <?php endfor ?>
            </tbody>
        </table>
    </div>
</div>