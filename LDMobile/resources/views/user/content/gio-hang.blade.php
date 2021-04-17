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
                                <th colspan="2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href={{route('user/san-pham')}} class='cart-back-shop'><i class="fas fa-chevron-left mr-5"></i>Xem sản phẩm khác</a>
                                        <div class='cart-title'>Giỏ hàng</div> 
                                    </div>                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i = 0; $i < 3; $i++) : ?>
                            <tr>
                                <td class='cart-img-td'>
                                    <img src="images/iphone/iphone_12_red.jpg" class='pt-10'>
                                </td>
                                <td class='cart-pro-info-td'>
                                    <div class='d-flex flex-column pr-10 pt-10 pb-10'>
                                        {{-- tên & giá --}}
                                        <div class='d-flex justify-content-between mbot-5'>
                                            <div class='d-flex flex-column'>
                                                <b class='fz-18'>iPhone 12</b>
                                                <div class='fz-12'>Dung lượng: 128GB</div>
                                                <div class='fz-12'>Màu sắc: Đỏ</div>
                                            </div>
                                            <div class='font-weight-600 price-color fz-18'>29.000.000 VND</div>
                                        </div>
                                        
                                        {{-- giảm giá --}}
                                        <div class='cart-promotion p-10 mb-10 d-flex align-items-center'>
                                            <a href="#" class="black fz-18" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample_<?php echo $i ?>" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="fal fa-info-circle mr-10 fz-18"></i>
                                            </a>
                                            Giảm<b class='ml-5 mr-5'>3.000.000</b>còn lại<b class='price-color ml-5'>26.000.000</b>
  
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
                                            <a href="#" class='cart-remove-item mr-10'><i class="fas fa-trash mr-5"></i></a>
                                            <div class='cart-qty-input'>
                                                <button type='button' data-id='<?php echo $i ?>' class='minus'><i class="fas fa-minus"></i></button>
                                                <b class='<?php echo 'qty_' . $i; ?>'>1</b>
                                                <button type='button' data-id='<?php echo $i ?>' class='plus'><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endfor ?>
                            <tr>
                                <td colspan="2">
                                    <div class='p-20'>
                                        <form action="#">
                                            <div class="d-flex align-items-center">
                                                <b class='mr-10'>Mã giảm giá:</b>
                                                <input type="text" class='cart-voucher-input mr-10'>
                                                <a href="#" class='cart-btn-voucher'>Áp dụng</a>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="d-flex flex-column p-20">
                                        <div class='d-flex flex-column mb-20'>
                                            <div class='d-flex justify-content-between mb-10'>
                                                <span>Tạm tính (3 sản phẩm):</span>
                                                <b>100.000.000 <sup>đ</sup></b> 
                                            </div>
                                            <div class='d-flex justify-content-between mb-10'>
                                                <span>Giảm:</span>
                                                <b>-9.000.000 <sup>đ</sup></b> 
                                            </div>  
                                            <div class='d-flex justify-content-between mb-10'>
                                                <span>Mã giảm giá:</span>
                                                <b>-9.000.000 <sup>đ</sup></b> 
                                            </div>  
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class='d-flex justify-content-between p-10'>
                                        <b>Tổng tiền:</b>
                                        <b class='price-color'>91.000.000 <sup>đ</sup></b> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="#" class='cart-btn-checkout main-color-bg mt-10 mb-10'>THANH TOÁN</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@stop