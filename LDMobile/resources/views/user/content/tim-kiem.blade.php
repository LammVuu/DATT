@extends("user.layout")
@section("content")

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="mt-20 mb-20 fz-24">Kết quả tìm kiếm cho <b>"{{ $name }}"</b></div><hr>
            @if (count($lst_product) == 0)
                <div class="required p-7 fz-20">không tìm thấy kết quả nào phù hợp với từ khóa <b>"{{ $name }}"</b></div>
                <a href="{{route('user/index')}}" class="mt-20">< Về trang chủ</a>
            @else
                <div class="fz-20">Tìm thấy <b>{{ count($lst_product) }}</b> kết quả</div>

                {{-- danh sách sản phẩm --}}
                <div class="row">
                    @foreach ($lst_product as $key)
                        <div class='col-lg-3 col-md-4 col-sm-6'>
                            <div class='shop-product-card box-shadow'>
                                {{-- khuyến mãi tag --}}
                                <div class='shop-promotion-tag'>
                                    @if($key['khuyenmai'] != 0)
                                    <span class='shop-promotion-text'>{{ '-'.($key['khuyenmai']*100).'%'}}</span>
                                    @endif
                                </div>

                                {{-- thêm giỏ hàng --}}
                                <div class='shop-overlay-product'></div>
                                <a href="#" class='shop-cart-link'><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</a>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='shop-detail-link'>Xem chi tiết</a>
                                {{-- thông tin sản phẩm --}}
                                <div>
                                    <div class='pt-20 pb-20'>
                                        <img src="{{ $url_phone.$key['hinhanh'] }}" class='shop-product-img-card'>
                                    </div>
                                    <div class='pb-20 text-center d-flex flex-column'>
                                        <b class='mb-10'>{{ $key['tensp'] }}</b>
                                        <div>
                                            <span class='font-weight-600 price-color'>{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                                            <span class='ml-5 text-strike'>{{ number_format($key['gia']) }}<sup>đ</sup></span>
                                        </div>
                                        <div>
                                            <div class='flex-row pt-5'>
                                                @if ($key['danhgia']['qty'] != 0)
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if($key['danhgia']['star'] > $i)
                                                        <i class="fas fa-star checked"></i>
                                                        @else
                                                        <i class="fas fa-star uncheck"></i>
                                                        @endif
                                                    @endfor
                                                    <span class='fz-14 ml-10'><?php echo $key['danhgia']['qty'] . ' đánh giá '?></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@include('user.content.section.sec-logo')
@stop