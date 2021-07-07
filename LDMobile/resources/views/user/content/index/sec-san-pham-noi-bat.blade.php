<section class="index-bg pt-50 pb-70">
    <div class="container white-bg border p-0">
        <div class="d-flex align-items-center justify-content-between p-20">
            <div class="d-flex align-items-center">
                <div class="fz-22 fw-600">ĐIỆN THOẠI NỔI BẬT NHẤT</div>
                <div class="relative ml-10">
                    <div class="fire-animation"><i class="fas fa-fire"></i></div>
                </div>
            </div>
            <div>
                <a href="{{route('user/dien-thoai-theo-hang', ['brand' => 'Apple'])}}" class="index-brand-tag">Apple</a>
                <a href="{{route('user/dien-thoai-theo-hang', ['brand' => 'Samsung'])}}" class="index-brand-tag">Samsung</a>
                <a href="{{route('user/dien-thoai-theo-hang', ['brand' => 'Xiaomi'])}}" class="index-brand-tag">Xiaomi</a>
                <a href="{{route('user/dien-thoai-theo-hang', ['brand' => 'Oppo'])}}" class="index-brand-tag">Oppo</a>
                <a href="{{route('user/dien-thoai-theo-hang', ['brand' => 'Vivo'])}}" class="index-brand-tag">Vivo</a>
                <a href="{{route('user/dien-thoai')}}" class="index-brand-tag">Xem tất cả</a>
            </div>
        </div>
        <hr class="m-0">
        
        <div class="d-flex flex-wrap">
            @foreach($lst_featured as $key)
            <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class="index-featured-phone w-20 relative">
                {{-- khuyến mãi tag --}}
                <div class='shop-promotion-tag'>
                    @if($key['khuyenmai'] != 0)
                    <span class='shop-promotion-text'>{{ '-'.($key['khuyenmai']*100).'%'}}</span>
                    @endif
                </div>
                {{-- hình ảnh --}}
                <img src="{{ $url_phone.$key['hinhanh'] }}">

                {{-- tên sản phẩm --}}
                <div class='fw-600 black text-center'>{{ $key['tensp'] }}</div>

                <div>
                    <div class="pt-10">
                        <span class="price-color fw-600">{{ number_format($key['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>
                        <span class="text-strike gray-1 ml-10">{{ number_format($key['gia'], 0, '', '.') }}<sup>đ</sup></span>
                    </div>
                    @if ($key['danhgia']['qty'] != 0)
                    <div class='d-flex align-items-center pt-10'>
                        @for ($i = 1; $i <= 5; $i++)
                            @if($key['danhgia']['star'] >= $i)
                            <i class="fas fa-star checked"></i>
                            @else
                            <i class="fas fa-star uncheck"></i>
                            @endif
                        @endfor
                        <span class='fz-12 ml-10 black'>{{ $key['danhgia']['qty'].' đánh giá' }}</span>
                    </div>
                    @else
                    <span class='fz-12 ml-10 white'>none</span>
                    @endif
                </div>
            </a>  
            @endforeach
        </div>
    </div>
</section>