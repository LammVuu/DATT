@extends("user.layout")
@section("content")

@section("direct")SẢN PHẨM @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='product-shop-wrapper pb-30'>
    <div class='container'>
        <div class='d-flex flex-row'>
            <div class='detail-product-name-title'>iPhone 11 PRO MAX</div>
            <div class='detail-star-vote'>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star checked"></i>
                <i class="fas fa-star uncheck"></i>&nbsp;
                <i class='vote-text'>21 đánh giá</i>
            </div>
        </div> <hr>
        <div class='row'>
            <div class='col-md-4 mb-20'>
                <div class='d-flex flex-column'>
                    {{-- ảnh sản phẩm --}}
                    <div class='detail-product-image-div'>
                        <img src="images/iphone/iphone_11_black.jpg" alt="product-image">
                    </div>
                    {{-- ảnh khác --}}
                    <div class='detail-another-div'>
                        <div id='detail-carousel' class="owl-carousel owl-theme">
                            <img src="images/iphone/iphone_11_black.jpg">
                            <img src="images/iphone/iphone_11_black.jpg">
                            <img src="images/iphone/iphone_11_black.jpg">
                            <img src="images/iphone/iphone_11_black.jpg">
                            <img src="images/iphone/iphone_11_black.jpg">
                        </div>
                        <div style='display: flex'>
                            <div id='prev-owl-carousel'><i class="far fa-chevron-left"></i></div>
                            <div id='next-owl-carousel'><i class="far fa-chevron-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-4 mb-20' style='font-size: 14px'>
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
                    <div class='detail-promotion mt-40 mb-20'>
                        <div class='detail-promotion-text'><i class="fas fa-gift" style='margin-right: 5px'></i>KHUYẾN MÃI</div>
                        <div class='detail-title'><i class="fas fa-check-circle" style='margin-right: 5px; color: #078fd8'></i>Giảm 10% cho sinh viên năm cuối</div>
                        <div class='detail-promotion-content'>
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quibusdam qui accusamus corrupti esse doloribus ab, tempora cumque cupiditate odio nam quo, culpa voluptatibus mollitia natus alias quis atque excepturi inventore!
                        </div>
                    </div>
                    {{-- mua ngay --}}
                    <a href="#" class='detail-buy'>MUA NGAY</a>
                </div>
            </div>
            <div class='col-md-4 mb-20'>
                <div class='detail-check-qty mb-3'>10 CỬA HÀNG CÓ SẴN SẢN PHẨM</div>
                <div class='mb-3'>
                    <label for="city" class='form-label'>Chọn Tỉnh Thành</label>
                    <select name="city" id='city' class='form-select'>
                        <option value="0" selected>Chọn thành phố</option>
                        <option value="1">Hà Nội</option>
                        <option value="2">TP. Hồ CHí Minh</option>
                    </select>
                </div>
                
                <div class='mb-3'>
                    <label for="city" class='form-label'>Chọn chi nhánh</label>
                    <select name="city" id='address' class='form-select'>
                        <option value="0" selected>Chọn chi nhánh</option>
                        <option value="1">Hà Nội</option>
                        <option value="2">TP. Hồ CHí Minh</option>
                    </select>   
                </div>

                <div class='detail-branch-list mb-10'>
                    <?php for($i =0; $i < 15; $i++) : ?>
                    <div class='detail-branch white-bg'>403/10, Lê Văn Sỹ, P.2, Q.Tân Bình</div>
                    <?php endfor ?>
                </div>
                
                <span><b class='detail-title'>Bảo hành</b>&nbsp;12 Tháng</span>
            </div>
        </div>
    </div>
</section>
<!--====== Product Details Style 1 Part Ends ======-->

<!--====== Reviews Part Start ======-->
<section class="reviews-wrapper pt-30 pb-30 ">
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
                    <li class="nav-item" role="presentation">
                        <a id="specifications-tab" data-bs-toggle="tab" href="#specifications" role="tab" aria-controls="specifications"
                            aria-selected="false">
                            specifications
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
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <div class="specifications-wrapper">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="reviews-title">
                                    <h4 class="title">Oculus VR</h4>
                                </div>
                                <p class="mb-15 pt-30">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime quod sequi vitae
                                    atque
                                    perspiciatis voluptas recusandae explicabo ea dolores numquam ratione, obcaecati ullam, ipsam minima
                                    vero nostrum
                                    nesciunt facere laudantium? Facere animi rem veniam quibusdam nam sed ex maxime laboriosam a vero
                                    nesciunt tenetur,
                                    eius autem fugiat quod expedita dignissimos.</p>
                                <p class="mb-30">Repellendus, doloribus illum expedita, dolorem voluptas doloremque voluptatibus, magni
                                    tempora
                                    laboriosam deserunt suscipit labore dolorum aperiam cum veniam accusamus? Consequatur dolore facere
                                    perferendis
                                    repellat, modi in consectetur ipsum atque quos natus.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!--====== Reviews Part Ends ======-->

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop