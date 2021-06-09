<section class="index-bg pt-40">
    <div class="container white-bg border p-0">
        <div class="d-flex align-items-center fz-22 font-weight-600 p-20">
            <div>KHUYẾN MÃI HOT NHẤT</div>
            <div class="relative ml-10">
                <div class="fire-animation"><i class="fas fa-fire"></i></div>
            </div>
        </div>

        <div class='relative'>
            <div id='index-promotion-carousel' class="owl-carousel owl-theme m-0">
                @foreach($lst_promotion as $key)
                <a href="{{ route('user/chi-tiet', ['id' => $key['id']]) }}" class="index-promotion-phone">
                    {{-- hình ảnh --}}
                    <img src="{{ $url_phone.$key['hinhanh'] }}">
                    
                    {{-- tên sản phẩm --}}
                    <div class='font-weight-600 black text-center'>{{ $key['tensp'] }}</div>

                    <div>
                        <div class='index-sale-tag'>{{ 'SALE '.($key['khuyenmai'] * 100).'%' }}</div>

                        <div class="pt-20">
                            <span class="price-color font-weight-600">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                            <span class="text-strike gray-1 ml-10">{{ number_format($key['gia']) }}<sup>đ</sup></span>
                        </div>
                        
                        <div class='d-flex align-items-center pt-20'>
                            @if ($key['danhgia']['qty'] != 0)
                            @for ($i = 1; $i <= 5; $i++)
                                @if($key['danhgia']['star'] > $i)
                                <i class="fas fa-star checked"></i>
                                @else
                                <i class="fas fa-star uncheck"></i>
                                @endif
                            @endfor
                            <span class='fz-12 ml-10 black'>{{ $key['danhgia']['qty'].' đánh giá' }}</span>
                            @else
                            <span class='fz-12 ml-10 white'>none</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="d-flex">
                <div id='prev-owl-carousel' class="d-flex align-items-center index-btn-owl-left"><i class="fas fa-chevron-left fz-26"></i></div>
                <div id='next-owl-carousel' class="d-flex align-items-center index-btn-owl-right"><i class="fas fa-chevron-right fz-26"></i></div>
            </div>
        </div>  
    </div>
</section>