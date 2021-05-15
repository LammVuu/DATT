<section class="index-bg pt-50 pb-70">
    <div class="container white-bg border p-0">
        <div class="d-flex align-items-center justify-content-between p-20">
            <div class="d-flex align-items-center">
                <div class="fz-22 font-weight-600">ĐIỆN THOẠI NỔI BẬT NHẤT</div>
                <div class="relative ml-10">
                    <div class="fire-animation"><i class="fas fa-fire"></i></div>
                </div>
            </div>
            <div>
                <a href="#" class="index-brand-tag">Apple</a>
                <a href="#" class="index-brand-tag">Samsung</a>
                <a href="#" class="index-brand-tag">Xiaomi</a>
                <a href="#" class="index-brand-tag">Oppo</a>
                <a href="#" class="index-brand-tag">Vivo</a>
                <a href="#" class="index-brand-tag">Xem tất cả</a>
            </div>
        </div>
        <hr class="m-0">
        
        <div class="d-flex flex-wrap">
            @for ($i = 0; $i < 10; $i++)
            <a href='#' class="index-promotion-phone w-20 relative">
                {{-- khuyến mãi tag --}}
                <div class='shop-promotion-tag'><span class='shop-promotion-text'>-15%</span></div>
                <img src="images/iphone/iphone_11_black.jpg">
                <div class='pt-10 font-weight-600 black'>iPhone 12 PRO MAX</div>
                <div class="pt-10">
                    <span class="price-color font-weight-600">21.000.000<sup>đ</sup></span>
                    <span class="text-strike gray-1 ml-10">25.000.000<sup>đ</sup></span>
                </div>
                <div class='d-flex align-items-center pt-10'>
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
    </div>
</section>