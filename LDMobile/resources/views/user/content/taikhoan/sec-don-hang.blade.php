<div class='row'>
    <div class='col-md-3'>
        @section("acc-order-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        @if (count($data['lst_order']['order']) != 0)
            @foreach ($data['lst_order']['order'] as $key)
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
                            <td class='vertical-center'>
                                <div class='p-20'>
                                    <a href="{{route('user/tai-khoan-chi-tiet-don-hang')}}"><?php echo $i ?></a>
                                </div>
                            </td>
                            {{-- ngày mua --}}
                            <td class='vertical-center'>
                                <div>18/04/2021</div>
                            </td>
                            {{-- sản phẩm mua --}}
                            <td class='w-40 vertical-center'>
                                <div class='pt-15 pb-15'>
                                    <div class='d-flex justify-content-between align-items-end'>
                                        <div id=<?php echo 'temp_' . $i ?> class='d-flex'>
                                            <img src="images/phone/iphone_12_red.jpg" alt="" width="60px">
                                            <div class="d-flex flex-column fz-14">
                                                <div><span>iPhone 12 Pro Max</span><span class='ml-10'>Màu: Đỏ</span></div>
                                                <span>Dung lượng: 128GB</span>
                                                <span>Số lượng: 2
                                                    <a type='button' data-bs-toggle='modal' data-bs-target='#view-more-order' class="ml-20">Xem thêm</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            {{-- giá  --}}
                            <td class='vertical-center'>
                                <div>15.000.000 VND</div>
                            </td>
                            {{-- trạng thái đơn hàng --}}
                            <td class='vertical-center'>
                                <div>Giao hàng thành công</div>
                            </td>
                        </tr>
                        <?php endfor ?>
                    </tbody>
                </table>
            @endforeach
        @else
            <div class="p-70 box-shadow text-center">
                Bạn chưa có đơn hàng nào. <a href="{{route('user/dien-thoai')}}" class="ml-5">Mua hàng</a>
            </div>
        @endif
    </div>
</div>

{{-- modal xem thêm đơn hàng --}}
<div class="modal fade" id="view-more-order" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div type='button' class="btn-close" data-bs-dismiss='modal'></div>
                <div class="d-flex flex-column">
                    <div class="fz-20 mb-5">Đơn hàng <b>123</b></div>
                    <div class="fz-14">Ngày mua: 12/12/2021</div>
                </div>
            </div>
            <div class="modal-body p-0">
                {{-- danh sách điện thoại --}}
                <div class="list-phone-order">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Điện thoại</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td class="vertical-center text-center"><?php echo $i ?></td>
                                <td>{{-- điện thoại --}}
                                    <div class="p-10">
                                        <div class="d-flex">
                                            <img src="images/phone/iphone_12_black.jpg" alt="" width="100px">
                                            <div class="ml-10">
                                                <a href="#" class="font-weight-600 mb-5 black">iPhone 12 PRO MAX</a>
                                                <div class="fz-14">Dung lượng: 128GB</div>
                                                <div class="fz-14">Màu sắc: Đen</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="vertical-center text-center font-weight-600">x1</td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>