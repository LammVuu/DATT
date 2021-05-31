@extends("user.layout")
@section("content")

@section("direct") GIỎ HÀNG @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='pt-50 pb-50'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-6 mx-auto'>
                <div class='box-shadow cart-box'>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href={{route('user/san-pham')}} class="main-color-text font-weight-300"><i class="fas fa-chevron-left mr-5"></i>Xem sản phẩm khác</a>
                                        <div class='cart-title'>Giỏ hàng</div> 
                                    </div>                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i = 0; $i < 3; $i++) : ?>
                            {{-- điện thoại trong giỏ hàng --}}
                            <tr>
                                <td class="d-flex">
                                    <div class='w-30'>
                                        <img src="images/phone/iphone_12_red.jpg" class='pt-10'>
                                    </div>
                                    <div class='w-70'>
                                        <div class='d-flex flex-column pr-10 pt-10 pb-10'>
                                            {{-- tên & giá --}}
                                            <div class='d-flex justify-content-between mb-5'>
                                                <div class='d-flex flex-column'>
                                                    <b class='fz-18'>iPhone 12</b>
                                                    <div class='fz-12'>Dung lượng: 128GB</div>
                                                    <div class='fz-12'>Màu sắc: Đỏ</div>
                                                </div>
                                                <div class='font-weight-600 price-color fz-18'>29.000.000<sup>đ</sup></div>
                                            </div>
                                            
                                            {{-- giảm giá --}}
                                            <div class='cart-promotion p-10 mb-10 d-flex align-items-center'>
                                                <div class="btn-promotion-info black fz-18" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample_<?php echo $i ?>" aria-expanded="false" aria-controls="collapseExample">
                                                    <i class="fal fa-info-circle mr-10 fz-18"></i>
                                                </div>
                                                Giảm<b class='ml-5 mr-5'>3.000.000<sup>đ</sup></b>còn lại<b class='price-color ml-5'>26.000.000<sup>đ</sup></b>
      
                                            </div>
                                            <div class="collapse" id="collapseExample_<?php echo $i ?>">
                                                <div class="card card-body">
                                                    <div class='main-color-text font-weight-600'><i class="fas fa-check-circle mr-5"></i>Giảm 10% cho sinh viên năm cuối</div>
                                                    <div class='detail-promotion-content'>
                                                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quibusdam qui accusamus corrupti esse doloribus ab, tempora cumque cupiditate odio nam quo, culpa voluptatibus mollitia natus alias quis atque excepturi inventore!
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- số lượng --}}
                                            <div class='d-flex flex-row justify-content-end align-items-center mt-10'>
                                                <div class='cart-qty-input'>
                                                    <button type='button' data-id='<?php echo $i ?>' class='plus'><i class="fas fa-plus"></i></button>
                                                    <b class='<?php echo 'qty_' . $i; ?>'>1</b>
                                                    <button type='button' data-id='<?php echo $i ?>' class='minus'><i class="fas fa-minus"></i></button>
                                                </div>
                                                <a href="#" class='price-color fz-18 ml-20'><i class="fas fa-trash mr-5"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endfor ?>
                            {{-- mã giảm giá --}}
                            <tr>
                                <td class="p-0 d-flex">
                                    <div class='w-30 p-20 bg-gray-4 d-flex justify-content-center align-items-center'>
                                        <b></i>Mã khuyến mãi</b>
                                    </div>
                                    <div class="w-70 p-10 d-flex justify-content-center align-items-center">
                                        {{-- mã --}}
                                        <!--<div class="w-70">
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
                                        </div>-->

                                        {{-- chọn khuyến mãi --}}
                                        <span class="pointer-cs main-color-text" data-bs-toggle="modal" data-bs-target="#modal-promotion">
                                            <i class="fas fa-ticket-alt mr-10"></i>Chọn Mã khuyến mãi
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            {{-- tính tiền --}}
                            <tr>
                                <td>
                                    <div class="d-flex flex-column p-20">
                                        <div class='d-flex justify-content-between mb-10'>
                                            <span>Tạm tính (3 sản phẩm):</span>
                                            <b>100.000.000 <sup>đ</sup></b> 
                                        </div>
                                        <div class='d-flex justify-content-between mb-10'>
                                            <span>Giảm:</span>
                                            <b>-9.000.000 <sup>đ</sup></b> 
                                        </div>  
                                        <div class='d-flex justify-content-between'>
                                            <span>Mã giảm giá:</span>
                                            <b class="main-color-text">-9.000.000 <sup>đ</sup></b> 
                                        </div>  
                                    </div>
                                </td>
                            </tr>
                            {{-- thành tiền --}}
                            <tr>
                                <td>
                                    <div class='d-flex justify-content-between p-10'>
                                        <span>Thành tiền:</span>
                                        <b class='price-color fz-22'>91.000.000 <sup>đ</sup></b> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class='checkout-btn p-10 w-100 mt-10 mb-10'>Tiến hành đặt hàng</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- modal chọn khuyến mãi --}}
<div class="modal fade" id="modal-promotion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="fz-20 font-weight-600">Mã khuyến mãi của tôi</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
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