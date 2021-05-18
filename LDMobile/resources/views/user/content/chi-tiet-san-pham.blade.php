@extends("user.layout")
@section("content")

@section("direct")SẢN PHẨM @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='product-shop-wrapper pb-30'>
    <div class='container'>
        <div class='d-flex flex-row align-items-center'>
            <div class='detail-product-name-title'>iPhone 11 PRO MAX</div>
            <div class='pl-20'>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star uncheck"></i>
                <span class='ml-10'>21 đánh giá</span>
            </div>
        </div> <hr>

        {{-- điện thoại --}}
        <div class='row'>
            {{-- hình ảnh --}}
            <div class='col-md-4 mb-20'>
                <div class='d-flex flex-column'>
                    {{-- ảnh sản phẩm --}}
                    <div class='detail-product-image-div'>
                        <img id='main-img' src="images/phone/iphone/iphone_11_black.jpg" alt="product-image">
                    </div>
                    {{-- ảnh khác --}}
                    <div class='detail-another-div'>
                        <div id='detail-carousel' class="owl-carousel owl-theme">
                            @for ($i = 0; $i < 2; $i++)
                                <img class="another-img" src="images/phone/iphone/iphone_12_black.jpg">
                                <img class="another-img" src="images/phone/iphone/iphone_12_red.jpg">
                                <img class="another-img" src="images/phone/iphone/iphone_12_blue.jpg">
                                <img class="another-img" src="images/phone/iphone/iphone_12_green.jpg">
                            @endfor
                        </div>
                        <div style='display: flex'>
                            <div id='prev-owl-carousel'><i class="far fa-chevron-left fz-26"></i></div>
                            <div id='next-owl-carousel'><i class="far fa-chevron-right fz-26"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- dung lượng & màu sắc & khuyến mãi & bảo hành --}}
            <div class='col-md-4 mb-20 fz-14'>
                <div class='d-flex flex-column'>
                    {{-- giá --}}
                    <div class='d-flex flex-row align-items-center'>
                        <div class='font-weight-600 price-color' style='font-size: 22px'>25.000.000 <sup>đ</sup></div>
                        <div class='ml-10 text-strike'>29.000.000 <sup>đ</sup></div>
                    </div>
                    {{-- dung lượng --}}
                    <div class='detail-title'>Dung lượng</div>
                    <div class='d-flex flex-row justify-content-start flex-wrap'>
                        <a href='#' class='detail-option box-shadow selected'>
                            <span>512GB</span><br>
                            <b>30.000.000 <sup>đ</sup></b>
                        </a>
                        <?php for($i = 0; $i < 2; $i++) : ?>
                        <a href='#' class='detail-option box-shadow'>
                            <span>512GB</span><br>
                            <b>30.000.000 <sup>đ</sup></b>
                        </a>
                        <?php endfor ?>
                    </div>
                    {{-- màu sắc --}}
                    <div class='detail-title'>Màu sắc</div>
                    <div class='d-flex flex-row justify-content-start flex-wrap'>
                        <?php for($i = 0; $i < 5; $i++) : ?>
                        <a href='#' class='detail-option box-shadow'>
                            <span>Đen</span><br>
                            <b>30.000.000 <sup>đ</sup></b>
                        </a>
                        <?php endfor ?>
                        <a href='#' class='detail-option box-shadow selected'>
                            <span>Đen</span><br>
                            <b>30.000.000 <sup>đ</sup></b>
                        </a>
                    </div>
                    {{-- khuyến mãi --}}
                    <div class='detail-promotion mt-40 mb-10'>
                        <div class='detail-promotion-text'><i class="fas fa-gift" style='margin-right: 5px'></i>KHUYẾN MÃI</div>
                        <div class='detail-title'><i class="fas fa-check-circle" style='margin-right: 5px; color: #078fd8'></i>Giảm 10% cho sinh viên năm cuối</div>
                        <div class='detail-promotion-content'>
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quibusdam qui accusamus corrupti esse doloribus ab, tempora cumque cupiditate odio nam quo, culpa voluptatibus mollitia natus alias quis atque excepturi inventore!
                        </div>
                    </div>
                    {{-- bảo hành --}}
                    <div class='detail-warranty p-10 mb-20'>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-check mr-10 fz-18 main-color-text"></i>
                            <span>Bảo hành chính hãng 12 tháng</span>
                        </div>
                    </div>
                    {{-- mua ngay --}}
                    <a href="#" class='main-btn p-5 fz-20 font-weight-600'>MUA NGAY</a>
                </div>
            </div>
            {{-- chi nhánh & thông số kỹ thuật --}}
            <div class='col-md-4 mb-20'>
                <div class="d-flex mb-20">
                    <img src="images/logo/apple-square-logo.png" alt="" class="w-30 circle-img border">
                    <div class="d-flex flex-column ml-20 mt-20">
                        <div>Cung cấp bởi <b>Apple Việt Nam</b></div>
                    </div>
                </div>
                <div class='detail-check-qty mb-3'>10 CỬA HÀNG CÓ SẴN SẢN PHẨM</div>

                {{-- xem cửa hàng --}}
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
                <div class="list-branch">
                    @foreach ($lstChiNhanh as $lst)
                        <div class="single-branch default-cs" data-area='<?php echo $lst['ID_TT'] ?>'>
                            <div class="branch-text">
                                <i class="fas fa-store mr-5"></i>
                                <?php echo $lst['DiaChi'] ?>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div> <hr>

        {{-- Bài giới thiệu & thông số --}}
        <div class='row'>
            {{-- bài giới thiệu --}}
            <div class='col-md-8'>
                <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/phone/iphone/iphone_12_red.jpg" class="d-block w-100" alt="...">
                        </div>
                        @for ($i = 0; $i < 9; $i++)
                        <div class="carousel-item">
                            <img src="images/phone/iphone/iphone_12_red.jpg" class="d-block w-100" alt="...">
                        </div>
                        @endfor
                    </div>
                    <div class="slideshow-btn-prev" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <i class="far fa-chevron-left fz-30"></i>
                    </div>
                    <div class='slideshow-btn-next' data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <i class="far fa-chevron-right fz-30"></i>
                    </div>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                            aria-current="true"></button>
                        @for ($i = 1; $i < 10; $i++)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i ?>"></button>
                        @endfor
                    </div>
                </div>
            </div>
            
            {{-- thông số kỹ thuật --}}
            <div class='col-md-4'>
                <div class='detail-specifications'>Thông số kỹ thuật</div>
                    <table class='table border'>
                        <tbody class='fz-14 '>
                            <tr>
                                <td class='td-40 center-td'>Màn hình</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>Camera sau</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>Camera trước</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>Hệ điều hành</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>CPU</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>RAM</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>Bộ nhớ trong</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>SIM</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td class='td-40 center-td'>Pin</td>
                                <td class='font-weight-600'>ABC</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class='main-btn w-100 p-10 fz-16' data-bs-toggle="modal" data-bs-target="#specifications-modal">Xem thêm</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div><hr>

        <div class='row'>
            {{-- sản phẩm tương tự --}}
            <div class='col-md-12'>
                <div class='fz-20 font-weight-600'>Các sản phẩm tương tự</div>
                <div class='row'>
                    @for ($i = 0; $i < 4; $i++)
                    <div class='col-md-3 col-sm-3 pt-30 pb-30'>
                        <div class='d-flex flex-column fz-14'>
                            {{-- hình ảnh --}}
                            <img src="images/phone/iphone/iphone_12_black.jpg" class='w-70' alt="">
                            {{-- tên điện thoại --}}
                            <div class='pt-20'>
                                <a href="#" class='font-weight-600 black'>iPhone 12 PRO MAX 128GB</a>
                            </div>
                            {{-- giá --}}
                            <div class='d-flex flex-column pt-10 fz-14'>
                                <span class="price-color font-weight-600">25.000.000 <sup>đ</sup></span>
                                <div>
                                    <span class='text-strike'>29.000.000 <sup>đ</sup></span>
                                    <span class='pl-5 pr-5'>|</span>
                                    <span class='price-color'>-10%</span>
                                </div>
                            </div>
                            {{-- đánh giá --}}
                            <div class='pt-10'>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star uncheck"></i>
                                <span class='ml-10'>21 đánh giá</span>
                            </div>
                            {{-- so sánh --}}
                            <div class='pt-10'>
                                <a href="#" >So sánh</a>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div><hr>

        {{-- gửi đánh giá --}}
        <div class='row'>
            <div class='col-md-12'>
                <div class='fz-20 font-weight-600'><?php echo $evaluate['total-rating'] ?> đánh giá iPhone 12 PRO MAX</div>
                <div class='pt-20 pb-20'>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                {{-- sao trung bình --}}
                                <td class='td-10'>
                                    <div class='d-flex justify-content-center align-items-center p-20'>
                                        <span class='detail-vote-avg'></span>
                                        <i class="fas fa-star checked fz-34 ml-5"></i>
                                    </div>
                                </td>
                                {{-- số lượt đánh giá --}}
                                <td class='td-40'>
                                    {{-- tổng số lương đánh giá --}}
                                    <input type="hidden" id='total-rating' value='100'>

                                    {{-- sao đánh giá --}}
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class='d-flex justify-content-between p-5 fz-14'>
                                            <div class='d-flex align-items-center w-5'>
                                                <span><?php echo $i ?></span>
                                                <i class="fas fa-star checked ml-5"></i>
                                            </div>
                                            <div class='d-flex align-items-center w-65'>
                                                <div class='detail-progress-bar'>
                                                    <div id='<?php echo 'percent-' . $i . '-star' ?>' 
                                                        data-id='<?php echo $evaluate['rating'][$i] ?>'></div>
                                                </div>
                                            </div>
                                            <div id='vote-5-star' class='d-flex align-items-center w-20'><?php echo $evaluate['rating'][$i] ?> đánh giá</div>
                                        </div>
                                    @endfor
                                </td>
                                {{-- đánh giá --}}
                                <td class='td-50'>
                                    {{-- chưa đăng nhập --}}
                                    {{-- <div class='d-flex flex-column align-items-center'>
                                        <div class='gray-1'>Vui lòng đăng nhập để có thể đánh giá</div>
                                        <div class='pt-20 pb-20'>
                                            <i class="fal fa-smile fz-46 success-color ml-5 mr-5"></i>
                                            <i class="fal fa-frown fz-46 warning-color ml-5 mr-5"></i>
                                        </div>
                                        <a href="#" class='main-btn pt-5 pr-20 pb-5 pl-20'>Đăng nhập</a>
                                    </div> --}}

                                    {{-- mua hàng mới cho đánh giá --}}
                                    

                                    {{-- gửi đánh giá --}}
                                    <div class='relative p-10'>
                                        <div class='d-flex flex-column'>
                                            <div class="d-flex">
                                                <span>Chọn đánh giá của bạn</span>
                                                <div class='ml-10'>
                                                    <div class='d-flex'>
                                                        <i id='star-1' class="star-rating fas fa-star gray-2 fz-24 pr-5" data-id='1'></i>
                                                        <i id='star-2' class="star-rating fas fa-star gray-2 fz-24 pr-5" data-id='2'></i>
                                                        <i id='star-3' class="star-rating fas fa-star gray-2 fz-24 pr-5" data-id='3'></i>
                                                        <i id='star-4' class="star-rating fas fa-star gray-2 fz-24 pr-5" data-id='4'></i>
                                                        <i id='star-5' class="star-rating fas fa-star gray-2 fz-24 pr-5" data-id='5'></i>
                                                        <input type="hidden" id='star-rating' value='0'>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class='pt-30'>
                                                <label for="comment" class='form-label'>Đánh giá</label>
                                                <textarea name="comment" class="form-control" rows="3"></textarea>
                                                
                                                <div class='d-flex justify-content-between align-items-center pt-10'>
                                                    <div class="d-flex flex-fill align-items-center justify-content-between">
                                                        <div class="d-flex">
                                                            <input type="hidden" id='qty-img' value='0'>
                                                            <input class='upload-inp' type="file" multiple accept="image/*">
                                                            <div class='detail-upload-link'>
                                                                <i class="fas fa-camera"></i>
                                                                <span>Ảnh đính kèm
                                                            </div>
                                                            <div class='btn-expanded-evaluate ml-5' aria-expanded="true">
                                                                <span class='qty-img'></span></span>
                                                                <i class="fas fa-chevron-up expand-icon ml-5"></i>
                                                            </div>
                                                        </div>
                                                        <div class="btn-remove-all mr-40"><i class="far fa-times-circle mr-5"></i>Xóa tất cả ảnh</div>
                                                    </div>
                                                    <div class='main-btn p-10'>Gửi đánh giá</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="evaluate-img-div border">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><hr>

        {{-- đánh giá sản phẩm --}}
        <div id='list-comment'>
            @for ($i = 0; $i < 5; $i++)
            <div class='d-flex flex-column pt-20 pb-20'>
                {{-- ảnh đại diện & tên & sao & ngày đăng --}}
                <div class='d-flex'>
                    <img src="images/avt1620997169.jpg" class='circle-img w-6' alt="">
                    <div class='d-flex flex-column justify-content-between pl-20'>
                        <b>Vũ Hoàng Lâm</b>
                        <div class='d-flex'>
                            <i class="fas fa-star checked" data-id='1'></i>
                            <i class="fas fa-star checked" data-id='2'></i>
                            <i class="fas fa-star checked" data-id='3'></i>
                            <i class="fas fa-star checked" data-id='4'></i>
                            <i class="fas fa-star checked" data-id='5'></i>
                            <input type="hidden" id='star-rating' value='0'>
                        </div>
                        <div>26/04/2021 20:56</div>
                    </div>
                </div>

                {{-- nội dung --}}
                <div class='pt-10'>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate aliquid qui debitis accusamus repellat tempora, unde consectetur at illo, quidem, dicta architecto ipsa perferendis. Officiis, eligendi! Debitis illo eaque cupiditate! Lorem ipsum, dolor sit amet consectetur adipisicing elit. Non laudantium quam nihil ea! Voluptatem, totam ipsa. Doloremque ullam architecto cupiditate voluptates optio, autem error rerum labore quidem rem beatae unde!
                </div>

                {{-- hình ảnh --}}
                <div class='pt-10'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                </div>

                {{-- like & phản hồi --}}
                <div class='d-flex align-items-center pt-20'>
                    {{-- like --}}
                    <div class='font-weight-600 fz-18 main-color-text'>
                        <i class="fas fa-thumbs-up mr-5"></i>360
                    </div>
                    <div class='pl-20 pr-20'>|</div>
                    
                    {{-- thích --}}
                    <div class='pointer-cs black'>
                        <i class="fal fa-thumbs-up mr-5"></i>Thích
                    </div>

                    {{-- đã thích --}}
                    {{-- <a href="#" class='main-color-text'>
                        <i class="fal fa-thumbs-up mr-5"></i>Đã thích
                    </a> --}}
                    
                    {{-- trả lời --}}
                    <div id='<?php echo 'comment-' .  $i ?>' data-id='<?php echo $i ?>' class='reply-cmt pointer-cs ml-30 black'>
                        <i class="fas fa-reply mr-5"></i>Trả lời
                    </div>
                </div>
            </div>

            {{-- <div class='d-flex flex-column pl-70 pt-10 pb-10'>
                <div class='row'>
                    <div class="col-md-1">
                        <img src="images/avt1620997169.jpg" class='circle-img' alt="">
                    </div>
                    <div class="col-md-11">
                        <textarea name="" id="" class="form-control" rows="3" placeholder="Viết câu trả lời"></textarea>
                        <div class="d-flex justify-content-end align-items-center pt-10">
                            <div class="pointer-cs price-color"><i class="fal fa-times-circle mr-5"></i>Hủy</div>
                            <div class='pl-10 pr-10'>|</div>
                            <div class="pointer-cs main-color-text"><i class="fas fa-reply mr-5"></i>Trả lời</div>    
                        </div>
                    </div>
                </div>
            </div> --}}
        
                {{-- phản hồi --}}
                <!--<div class='d-flex flex-column pl-70 pt-20 pb-20'>
                    {{-- ảnh đại diện & tên & ngày đăng --}}
                    <div class='d-flex'>
                        <img src="images/avt1620997169.jpg" class='circle-img w-6' alt="">
                        <div class='d-flex flex-column pl-20'>
                            <b>Vũ Hoàng Lâm</b>
                            <div>26/04/2021 20:56</div>
                        </div>
                    </div>
        
                    {{-- nội dung --}}
                    <div class='pt-10'>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente ipsa saepe vitae nemo perferendis incidunt deserunt consectetur, voluptas iure possimus accusantium maxime excepturi hic laboriosam dolorem veritatis similique. Ipsa, adipisci.
                    </div>
        
                    {{-- like --}}
                    <div class='d-flex align-items-center pt-20'>
                        <div class='font-weight-600 fz-18 main-color-text'>
                            <i class="fas fa-thumbs-up mr-5"></i>360
                        </div>
                        <div class='pl-20 pr-20'>|</div>
                        <div>
                            {{-- thích --}}
                            <a href="#" class='black'>
                                <i class="fal fa-thumbs-up mr-5"></i>Thích
                            </a>
        
                            {{-- đã thích --}}
                            {{-- <a href="#" class='main-color-text'>
                                <i class="fal fa-thumbs-up mr-5"></i>Đã thích
                            </a> --}}
                        </div>
                    </div>
                </div>
                <div class='d-flex flex-column pl-70 pt-20 pb-20'>
                    {{-- ảnh đại diện & tên & sao & ngày đăng --}}
                    <div class='d-flex'>
                        <img src="images/avt1620997169.jpg" class='circle-img w-6' alt="">
                        <div class='d-flex flex-column pl-20'>
                            <b>Vũ Hoàng Lâm</b>
                            <div>26/04/2021 20:56</div>
                        </div>
                    </div>
        
                    {{-- nội dung --}}
                    <div class='pt-10'>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente ipsa saepe vitae nemo perferendis incidunt deserunt consectetur, voluptas iure possimus accusantium maxime excepturi hic laboriosam dolorem veritatis similique. Ipsa, adipisci.
                    </div>
        
                    {{-- like --}}
                    <div class='d-flex align-items-center pt-20'>
                        <div class='font-weight-600 fz-18 main-color-text'>
                            <i class="fas fa-thumbs-up mr-5"></i>360
                        </div>
                        <div class='pl-20 pr-20'>|</div>
                        <div>
                            {{-- thích --}}
                            <a href="#" class='black'>
                                <i class="fal fa-thumbs-up mr-5"></i>Thích
                            </a>
        
                            {{-- đã thích --}}
                            {{-- <a href="#" class='main-color-text'>
                                <i class="fal fa-thumbs-up mr-5"></i>Đã thích
                            </a> --}}
                        </div>
                    </div>
                </div>-->

            <hr>
            @endfor
        </div>
    </div>
</section>

{{-- modal thông số kỹ thuật --}}
<div class="modal fade" id="specifications-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
            <div class='col-md-10 mx-auto'>
                <div class="d-flex justify-content-between fz-18">
                    <div>Thông số kỹ thuật <b>iPhone 12 PRO MAX</b></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- hình ảnh --}}
                <div class='pt-40 pb-40'>
                    <div class='row'>
                        <div class='col-md-6'>
                            <img src="images/phone/iphone/iphone_12_black.jpg" alt="">
                        </div>
                        <div class='col-md-6'>
                            <img src="images/phone/iphone/iphone_12_black.jpg" alt="">
                        </div>
                    </div>
                </div>
                {{-- thông số --}}
                <table class='table'>
                    <tbody class="fz-14">
                        {{-- thiết kế & trọng lượng --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>thiết kế & trọng lượng</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Thiết kế</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Chất liệu</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Kích thước</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Khối lượng</td>
                            <td>ABC</td>
                        </tr>

                        {{-- màn hình --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>màn hình</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Công nghệ màn hình</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Độ phân giải</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Tần số quét</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Mặt kính cảm ứng</td>
                            <td>ABC</td>
                        </tr>

                        {{-- Camera sau --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>camera sau</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Độ phân giải</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Quay phim</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Đèn Flash</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Tính năng</td>
                            <td>ABC</td>
                        </tr>

                        {{-- Camera trước --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>camera trước</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Độ phân giải</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Quay phim</td>
                            <td>ABC</td>
                        </tr>

                        {{-- hệ điều hành & CPU --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>hệ điều hành & cpu</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Hệ điều hành</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Chip xử lý (CPU)</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Tốc độ CPU</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Chip đồ họa (GPU)</td>
                            <td>ABC</td>
                        </tr>
                        
                        {{-- bộ nhớ & lưu trữ --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>bộ nhớ & lưu trữ</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>RAM</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Bộ nhớ trong</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Bộ nhớ còn lại (khả dụng) khoảng</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Thẻ nhớ</td>
                            <td>ABC</td>
                        </tr>

                        {{-- kết nối --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>kết nối</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Mạng di động</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>SIM</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Wifi</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>GPS</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Bluetooth</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Cổng kết nối/sạc</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Jack tai nghe</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Kêt nối khác</td>
                            <td>ABC</td>
                        </tr>

                        {{-- Pin & Sạc --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>pin & sạc</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Dung lượng pin</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Loại pin</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Công nghệ pin</td>
                            <td>ABC</td>
                        </tr>

                        {{-- Tiện ích --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>tiện ích</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Bảo mật nâng cao</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Tính năng đặc biệt</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Ghi âm</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Radio</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Xem phim</td>
                            <td>ABC</td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Nghe nhạc</td>
                            <td>ABC</td>
                        </tr>

                        {{-- thông tin khác --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>thông tin khác</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='td-30 font-weight-600'>Thời điểm ra mắt</td>
                            <td>ABC</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>

{{-- modal thông báo chỉ được phép upload tối đa 3 hình ảnh --}}
<div class="modal fade" id="modal-warning-1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="text-center pt-20"><i class="fal fa-info-circle fz-70 main-color-text"></i></div>
                    <div class="text-center font-weight-600 pt-20 pb-40">Bạn chỉ được phép chọn tối đa 3 ảnh đính kèm</div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="main-btn p-5" data-bs-dismiss="modal">Đã hiểu</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!--====== Reviews Part Start ======-->
{{-- <section class="reviews-wrapper pt-30 pb-30 ">
    <div class="container">
        <div class="reviews-style">
            <div class="reviews-menu">

                <ul class="breadcrumb-list-grid nav nav-tabs border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            Details
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false">
                            Reviews
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="details-wrapper">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="reviews-title">
                                    <h4 class="title">Oculus VR</h4>
                                </div>
                                <p class="mb-15 pt-30">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime quod sequi vitae atque
                                    perspiciatis voluptas recusandae explicabo ea dolores numquam ratione, obcaecati ullam, ipsam minima vero nostrum
                                    nesciunt facere laudantium? Facere animi rem veniam quibusdam nam sed ex maxime laboriosam a vero nesciunt tenetur,
                                    eius autem fugiat quod expedita dignissimos.</p>
                                <p class="mb-30">Repellendus, doloribus illum expedita, dolorem voluptas doloremque voluptatibus, magni tempora
                                    laboriosam deserunt suscipit labore dolorum aperiam cum veniam accusamus? Consequatur dolore facere perferendis
                                    repellat, modi in consectetur ipsum atque quos natus.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="review-wrapper">
                        <div class="reviews-title">
                            <h4 class="title">Customer Reviews (38)</h4>
                        </div>
                    
                        <div class="reviews-rating-wrapper flex-wrap">
                            <div class="reviews-rating-star">
                                <p class="rating-review"><i class="mdi mdi-star"></i> <span>4.5</span> of 5</p>
                    
                                <div class="reviews-rating-bar">
                                    <div class="single-reviews-rating-bar">
                                        <p class="value">5 Starts</p>
                                        <div class="rating-bar-inner">
                                            <div class="bar-inner" style="width: 60%;"></div>
                                        </div>
                                        <p class="percent">60%</p>
                                    </div>
                                </div>
                                <div class="reviews-rating-bar">
                                    <div class="single-reviews-rating-bar">
                                        <p class="value">4 Starts</p>
                                        <div class="rating-bar-inner">
                                            <div class="bar-inner" style="width: 20%;"></div>
                                        </div>
                                        <p class="percent">20%</p>
                                    </div>
                                </div>
                                <div class="reviews-rating-bar">
                                    <div class="single-reviews-rating-bar">
                                        <p class="value">3 Starts</p>
                                        <div class="rating-bar-inner">
                                            <div class="bar-inner" style="width: 10%;"></div>
                                        </div>
                                        <p class="percent">10%</p>
                                    </div>
                                </div>
                                <div class="reviews-rating-bar">
                                    <div class="single-reviews-rating-bar">
                                        <p class="value">2 Starts</p>
                                        <div class="rating-bar-inner">
                                            <div class="bar-inner" style="width: 5%;"></div>
                                        </div>
                                        <p class="percent">05%</p>
                                    </div>
                                </div>
                                <div class="reviews-rating-bar">
                                    <div class="single-reviews-rating-bar">
                                        <p class="value">1 Starts</p>
                                        <div class="rating-bar-inner">
                                            <div class="bar-inner" style="width: 0;"></div>
                                        </div>
                                        <p class="percent">0%</p>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="reviews-rating-form">
                                <div class="rating-star">
                                    <p>Click on star to review</p>
                                    <ul id="stars" class="stars">
                                        <li class="star" data-value='1'><i class="mdi mdi-star"></i></li>
                                        <li class="star" data-value='2'><i class="mdi mdi-star"></i></li>
                                        <li class="star" data-value='3'><i class="mdi mdi-star"></i></li>
                                        <li class="star" data-value='4'><i class="mdi mdi-star"></i></li>
                                        <li class="star" data-value='5'><i class="mdi mdi-star"></i></li>
                                    </ul>
                                </div>
                                <div class="rating-form">
                                    <form action="#">
                                        <div class="single-form form-default">
                                            <label>Write your Review</label>
                                            <div class="form-input">
                                                <textarea placeholder="Your review here"></textarea>
                                                <i class="mdi mdi-message-text-outline"></i>
                                            </div>
                                        </div>
                                        <div class="single-rating-form flex-wrap">
                                            <div class="rating-form-file">
                                                <div class="file">
                                                    <input type="file" id="file-1">
                                                    <label for="file-1"><i class="mdi mdi-camera"></i></label>
                                                </div>
                                                <div class="file">
                                                    <input type="file" id="file-1">
                                                    <label for="file-1"><i class="mdi mdi-attachment"></i></label>
                                                </div>
                                            </div>
                                            <div class="rating-form-btn">
                                                <button class="main-btn primary-btn">write a Review</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    
                        <div class="reviews-btn flex-wrap">
                            <div class="reviews-btn-left">
                                <div class="dropdown-style">
                                    <div class="dropdown dropdown-white">
                                        <button class="main-btn primary-btn" type="button" id="dropdownMenu-1" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="true"> All Stars (213) <i
                                                class="mdi mdi-chevron-down"></i></button>
                    
                                        <ul class="dropdown-menu sub-menu-bar" aria-labelledby="dropdownMenu-1">
                                            <li><a href="#">Dropped Menu 1</a></li>
                                            <li><a href="#">Dropped Menu 2</a></li>
                                            <li><a href="#">Dropped Menu 3</a></li>
                                            <li><a href="#">Dropped Menu 4</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="dropdown-style">
                                    <div class="dropdown dropdown-white">
                                        <button class="main-btn primary-btn-border" type="button" id="dropdownMenu-1" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="true"> Sort by Latest <i
                                                class="mdi mdi-chevron-down"></i></button>
                    
                                        <ul class="dropdown-menu sub-menu-bar" aria-labelledby="dropdownMenu-1">
                                            <li><a href="#">Dropped Menu 1</a></li>
                                            <li><a href="#">Dropped Menu 2</a></li>
                                            <li><a href="#">Dropped Menu 3</a></li>
                                            <li><a href="#">Dropped Menu 4</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="reviews-btn-right">
                                <a href="#" class="main-btn">with photo (18)</a>
                                <a href="#" class="main-btn">additional feedback (23)</a>
                            </div>
                        </div>
                    
                        <div class="reviews-comment">
                            <ul class="comment-items">
                                <li>
                                    <div class="single-review-comment">
                                        <div class="comment-user-info">
                                            <div class="comment-author">
                                                <img src="images/review/author-1.jpg" alt="">
                                            </div>
                                            <div class="comment-content">
                                                <h6 class="name">User Name</h6>
                    
                                                <p>
                                                    <i class="mdi mdi-star"></i>
                                                    <span class="rating"><strong>4</strong> of 5</span>
                                                    <span class="date">20 Nov 2019 22:01</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="comment-user-text">
                                            <p>Good headphones. The sound is clear. AND the bottoms repyat and top ring. Currently
                                                are really not taken. For me quiet. With my Beats of course can not be compared. But
                                                for the money and definitely recommend. The one who took happy as an elephant.
                                                Product as advertised, looks good Quality, sound is not the best but because of
                                                cost-benefit ratio it seems very good to me, recommended the seller .</p>
                                            <ul class="comment-meta">
                                                <li><i class="mdi mdi-thumb-up"></i> <span>31</span></li>
                                                <li><a href="#">Like</a></li>
                                                <li><a href="#">Replay</a></li>
                                            </ul>
                                        </div>
                                    </div>
                    
                                    <ul class="comment-replay">
                                        <li>
                                            <div class="single-review-comment">
                                                <div class="comment-user-info">
                                                    <div class="comment-author">
                                                        <img src="images/review/author-2.jpg" alt="">
                                                    </div>
                                                    <div class="comment-content">
                                                        <h6 class="name">User Name</h6>
                    
                                                        <p>
                                                            <i class="mdi mdi-star"></i>
                                                            <span class="rating"><strong>4</strong> of 5</span>
                                                            <span class="date">20 Nov 2019 22:01</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="comment-user-text">
                                                    <p>Good headphones. The sound is clear. AND the bottoms repyat and top ring.
                                                        Currently are really not taken.</p>
                                                    <div class="comment-image flex-wrap">
                                                        <div class="image">
                                                            <img src="images/review/attachment-1.jpg" alt="">
                                                        </div>
                                                        <div class="image">
                                                            <img src="images/review/attachment-2.jpg" alt="">
                                                        </div>
                                                    </div>
                                                    <ul class="comment-meta">
                                                        <li><i class="mdi mdi-thumb-up"></i> <span>31</span></li>
                                                        <li><a href="#">Like</a></li>
                                                        <li><a href="#">Replay</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="single-review-comment">
                                                <div class="comment-user-info">
                                                    <div class="comment-author">
                                                        <img src="images/review/author-3.jpg" alt="">
                                                    </div>
                                                    <div class="comment-content">
                                                        <h6 class="name">User Name</h6>
                    
                                                        <p>
                                                            <i class="mdi mdi-star"></i>
                                                            <span class="rating"><strong>4</strong> of 5</span>
                                                            <span class="date">20 Nov 2019 22:01</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="comment-user-text">
                                                    <p>Good headphones. The sound is clear. AND the bottoms repyat and top ring.
                                                        Currently are really not taken.</p>
                                                    <ul class="comment-meta">
                                                        <li><i class="mdi mdi-thumb-up"></i> <span>31</span></li>
                                                        <li><a href="#">Like</a></li>
                                                        <li><a href="#">Replay</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="single-review-comment">
                                        <div class="comment-user-info">
                                            <div class="comment-author">
                                                <img src="images/review/author-4.jpg" alt="">
                                            </div>
                                            <div class="comment-content">
                                                <h6 class="name">User Name</h6>
                    
                                                <p>
                                                    <i class="mdi mdi-star"></i>
                                                    <span class="rating"><strong>4</strong> of 5</span>
                                                    <span class="date">20 Nov 2019 22:01</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="comment-user-text">
                                            <p>Good headphones. The sound is clear. AND the bottoms repyat and top ring. Currently
                                                are really not taken. For me quiet. With my Beats of course can not be compared. But
                                                for the money and definitely recommend. The one who took happy as an elephant.
                                                Product as advertised, looks good Quality, sound is not the best but because of
                                                cost-benefit ratio it seems very good to me, recommended the seller .</p>
                                            <ul class="comment-meta">
                                                <li><i class="mdi mdi-thumb-up"></i> <span>31</span></li>
                                                <li><a href="#">Like</a></li>
                                                <li><a href="#">Replay</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section> --}}
<!--====== Reviews Part Ends ======-->

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop