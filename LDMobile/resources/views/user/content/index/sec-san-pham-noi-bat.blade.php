<section class="user-bg-color pt-50 pb-70">
    <div class="container white-bg border p-0">
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="d-flex align-items-center p-20">
                    <div class="fz-22 fw-600">ĐIỆN THOẠI NỔI BẬT NHẤT</div>
                    <div class="relative ml-10">
                        <div class="fire-animation"><i class="fas fa-fire"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <div class="d-flex justify-content-end flex-wrap p-20">
                    @foreach ($lst_brand as $key)
                        <a href="{{route('user/dien-thoai', ['hang' => $key['brand']])}}" class="index-brand-tag">{{$key['brand']}}</a>    
                    @endforeach
                    <a href="{{route('user/dien-thoai')}}" class="index-brand-tag">Xem tất cả</a>
                </div>
            </div>
        </div>
        <hr class="m-0">
        
        <div class="d-flex flex-wrap">
            @foreach($lst_featured as $key)
            <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class="index-featured-phone">
                {{-- khuyến mãi tag --}}
                @if($key['khuyenmai'] != 0)
                    <div class='shop-promotion-tag'>
                        <span class='shop-promotion-text'>{{ '-'.($key['khuyenmai']*100).'%'}}</span>
                    </div>
                @endif
                {{-- hình ảnh --}}
                <img src="{{ $url_phone.$key['hinhanh'] }}">

                {{-- tên sản phẩm --}}
                <div class='fw-600 black text-center'>{{ $key['tensp'] }}</div>

                <div class="text-center">
                    <div class="pt-10">
                        <span class="red fw-600">{{ number_format($key['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>    
                        @if ($key['khuyenmai'] != 0)
                        <span class="text-strike gray-1 ml-10">{{ number_format($key['gia'], 0, '', '.') }}<sup>đ</sup></span>
                        @endif
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