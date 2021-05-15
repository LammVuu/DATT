<section class="index-bg pt-100">
    <div class="container white-bg border p-0">
        <div class="d-flex align-items-center fz-22 font-weight-600 p-20">
            <div>KHUYẾN MÃI HOT NHẤT</div>
            <div class="relative ml-10">
                <div class="fire-animation"><i class="fas fa-fire"></i></div>
            </div>
        </div>
        <hr class="m-0">

        <div class='relative'>
            <div id='index-promotion-carousel' class="owl-carousel owl-theme m-0">
                @for ($i = 0; $i < 10; $i++)
                <a href='#' class="index-promotion-phone">
                    <img src="images/iphone/iphone_11_black.jpg">
                    <div class='pt-20 font-weight-600 black'>iPhone 12 PRO MAX</div>
                    <div class="pt-20">
                        <div class='index-sale-tag'>SALE 10%</div>
                    </div>
                    <div class="pt-20">
                        <span class="price-color font-weight-600">21.000.000<sup>đ</sup></span>
                        <span class="text-strike gray-1 ml-10">25.000.000<sup>đ</sup></span>
                    </div>
                    <div class='d-flex align-items-center pt-20'>
                        <i class="fas fa-star checked"></i>
                        <i class="fas fa-star checked"></i>
                        <i class="fas fa-star checked"></i>
                        <i class="fas fa-star checked"></i>
                        <i class="fas fa-star uncheck"></i>
                        <span class='fz-12 ml-10 black'>21 đánh giá</span>
                    </div>
                </a>
                @endfor
            </div>
            <div class="d-flex">
                <div id='prev-owl-carousel' class="d-flex align-items-center index-btn-owl-left"><i class="fas fa-chevron-left fz-26"></i></div>
                <div id='next-owl-carousel' class="d-flex align-items-center index-btn-owl-right"><i class="fas fa-chevron-right fz-26"></i></div>
            </div>
        </div>  
    </div>
</section>