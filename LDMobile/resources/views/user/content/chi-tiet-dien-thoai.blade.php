@extends("user.layout")
@section("content")

@section("breadcrumb")
    <a href="{{route('user/dien-thoai')}}" class="bc-item">Điện thoại</a>
    <div class="bc-divider"><i class="fas fa-chevron-right"></i></div>
    <a href="{{route('user/chi-tiet', ['name' => $phone['tensp_url']])}}" class="bc-item active">{{$phone['tensp']}}</a>
@stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='pt-10 pb-50'>
    <div class='container'>
        {{-- tên điện thoại, sao, lượt đánh giá --}}
        <div class='d-flex flex-row align-items-center justify-content-between'>
            <div class="d-flex align-items-center">
                <div class='fz-32 fw-600'>{{ $phone['tensp'] }}</div>
                <div class='pl-20'>
                    @if ($lst_evaluate['total-rating'] != 0)
                        @for ($i = 1; $i <= 5; $i++)
                            @if($lst_evaluate['total-star'] >= $i)
                            <i class="fas fa-star checked"></i>
                            @else
                            <i class="fas fa-star uncheck"></i>
                            @endif
                        @endfor
                        <span class='ml-10'>{{ $phone['danhgia']['qty'].' đánh giá' }}</span>
                    @endif
                </div>
                <div id="qty-in-stock-status" class="ml-20 price-color text-uppercase fw-600 fz-20"></div>
            </div>
            {{-- nút yêu thích --}}
            <div data-name="{{$phone['tensp']}}" class="favorite-tag">
                <i class="far fa-heart"></i>
            </div>
        </div><hr>

        {{-- điện thoại --}}
        <div class='row'>
            {{-- hình ảnh --}}
            <div class='col-lg-4 col-md-6 mb-20'>
                <div class='d-flex flex-column'>
                    {{-- ảnh sản phẩm --}}
                    <div class='detail-product-image-div'>
                        <img id='main-img' src="{{ $url_phone.$phone['hinhanh'] }}" alt="product-image">
                    </div>
                    {{-- ảnh khác --}}
                    <div class='detail-another-div'>
                        <div id='detail-carousel' class="owl-carousel owl-theme">
                            @foreach($lst_variation['image'] as $key)
                                <img class="another-img" src="{{ $url_phone.$key['hinhanh'] }}">
                            @endforeach
                        </div>
                        <div style='display: flex'>
                            <div id="prev-another-img" class='prev-owl-carousel'><i class="far fa-chevron-left fz-26"></i></div>
                            <div id="next-another-img" class='next-owl-carousel'><i class="far fa-chevron-right fz-26"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- dung lượng & màu sắc & khuyến mãi & bảo hành --}}
            <div class='col-lg-4 col-md-6 mb-20 fz-14'>
                <div class='d-flex flex-column'>
                    {{-- giá --}}
                    <div class='d-flex flex-row align-items-end'>
                        <div class='fw-600 price-color fz-26'>{{ number_format($phone['giakhuyenmai']) }}<sup>đ</sup></div>
                        {{-- khuyến mãi còn hạn --}}
                        @if (!empty($phone['khuyenmai']) && $phone['khuyenmai']['trangthaikhuyenmai'])
                            <div class="d-flex align-items-end ml-20">
                                <div class="fz-14">Giá niêm yết :</div>
                                <b class='ml-5 text-strike fz-16'>{{ number_format($phone['gia']) }}<sup>đ</sup></b>
                            </div>    
                        @endif
                    </div>
                    {{-- dung lượng --}}
                    <div class='detail-title pt-10'>Dung lượng</div>
                    <div class='row'>
                        @foreach ($lst_variation['capacity'] as $key)
                            <div class="col-md-4 col-sm-3 p-10">
                            @if ($key['dungluong'] == $phone['dungluong'] && $key['ram'] == $phone['ram'])
                                <div type='button' class='detail-option box-shadow selected'>
                                    <div class="fw-600">{{ $key['dungluong'] }}</div>
                                    <div class="price-color fw-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @else
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='detail-option box-shadow'>
                                    <div>{{ $key['dungluong'] }}</div>
                                    <div class="price-color fw-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </a>
                            @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- màu sắc --}}
                    <div class='detail-title pt-10'>Màu sắc</div>
                    <div class='row'>
                        @foreach ($lst_variation['color'] as $key)
                            <div class="col-md-4 col-sm-3 p-10">
                            @if ($key['mausac'] == $phone['mausac'])
                                <div type='button' class='color-option detail-option box-shadow selected' data-image="{{ $url_phone.$key['hinhanh'] }}"
                                    data-id="{{$key['id']}}" data-color="{{$key['mausac']}}" favorite="{{$key['yeuthich']}}">
                                    <div class="color-name fw-600">{{ $key['mausac'] }}</div>
                                    <div class="price-color fw-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @else
                                <div type="button" class='color-option detail-option box-shadow' data-image="{{ $url_phone.$key['hinhanh'] }}"
                                    data-id="{{$key['id']}}" data-color="{{$key['mausac']}}" favorite="{{$key['yeuthich']}}">
                                    <div class="color-name">{{ $key['mausac'] }}</div>
                                    <div class="price-color fw-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @endif  
                            </div>  
                        @endforeach
                    </div>

                    {{-- khuyến mãi --}}
                    {{-- khuyến mãi còn hạn --}}
                    @if (!empty($phone['khuyenmai']) && $phone['khuyenmai']['trangthaikhuyenmai'])
                        <div class='detail-promotion mt-40'>
                            <div class='detail-promotion-text'><i class="fas fa-gift mr-5"></i>KHUYẾN MÃI</div>
                            <div class='detail-title'><i class="fas fa-check-circle mr-5 main-color-text"></i>{{ $phone['khuyenmai']['tenkm']}}</div>
                            <div class='detail-promotion-content'>
                                {{ $phone['khuyenmai']['noidung'] }}                           
                            </div>
                        </div>
                    @endif
                    {{-- mua ngay --}}
                    <div type="button" data-id="{{$phone['id']}}" class='buy-now main-btn p-5 fz-20 fw-600 mt-20'>MUA NGAY</div>
                </div>
            </div>

            {{-- chi nhánh --}}
            <div class='col-lg-4 col-md-8 mb-20'>
                {{-- nhà cung cấp --}}
                <div class="d-flex mb-40">
                    <img src="{{ $url_logo.$supplier['anhdaidien'] }}" alt="" class="detail-supplier-img">
                    <div class="d-flex flex-column ml-20 mt-20">
                        <div>Cung cấp bởi <b>{{ $supplier['tenncc'] }}</b></div>
                        <img src="images/icon/genuine-icon.png" alt="" width="130px">
                    </div>
                </div>
                {{-- bảo hành --}}
                <div class='detail-warranty p-10 mb-30'>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-check mr-10 fz-18 main-color-text"></i>
                        <span>Bảo hành chính hãng {{ $phone['baohanh'] }}</span>
                    </div>
                </div>
                
                <div id="check-qty-in-stock-btn" type="button" class="main-color-text ml-10" data-id="{{$phone['id']}}"><i class="fas fa-store mr-5"></i> Xem các chi nhánh còn hàng</div>
            </div>
        </div> <hr>

        {{-- Bài giới thiệu & thông số --}}
        <div class='row'>
            {{-- bài giới thiệu --}}
            <div class='col-md-8'>
                <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-interval="false" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <iframe height="400" class="w-100" allowfullscreen
                                src="{{ 'https://www.youtube.com/embed/' . $phone['id_youtube'] }}">
                            </iframe>
                        </div>
                        @foreach($slide_model as $key)
                        <div class="carousel-item">
                            <img src="{{ $url_model_slide.$key['hinhanh'] }}" class="carousel-img" alt="...">
                        </div>
                        @endforeach
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
                        @for ($i = 1; $i < count($slide_model) + 1; $i++)
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
                                <td class='w-40 center-td'>Màn hình</td>
                                <td>
                                    {{  
                                        $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] . ', ' .
                                        $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['ty_le_mh'] .  '"' 
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>Camera sau</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>Camera trước</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>Hệ điều hành</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['HDH']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>CPU</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>RAM</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['RAM']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>Bộ nhớ trong</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>SIM</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['SIM'] . ', ' .
                                        $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td class='w-40 center-td'>Pin</td>
                                <td>
                                    {{
                                        $phone['cauhinh']['thong_so_ky_thuat']['pin']['loai'] . ', ' .
                                        $phone['cauhinh']['thong_so_ky_thuat']['pin']['dung_luong']
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class='main-btn w-100 p-10 fz-16' data-bs-toggle="modal" data-bs-target="#specifications-modal">Xem thêm</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>

        {{-- sản phẩm cùng hãng --}}
        <div class='row pt-50'>
            <div class='col-lg-12 detail-item'>
                <div class='detail-item-title'>Cùng thương hiệu {{ $supplier['brand'] }}</div>
                <div class='relative'>
                    <div id='same-brand-pro-carousel' class="owl-carousel owl-theme m-0">
                        @foreach($lst_proSameBrand as $key)
                        <div class='detail-item-content'>
                            {{-- hình ảnh --}}
                            <img src="{{ $url_phone.$key['hinhanh'] }}" alt="">
                            
                            {{-- tên điện thoại --}}
                            <div class='text-center pl-10 pr-10'>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='fw-600 black'>{{ $key['tensp'] }}</a>
                            </div>
                            {{-- giá --}}
                            <div class="d-flex justify-content-center">
                                <div>
                                    <div class='d-flex flex-column fz-14'>
                                        <span class="price-color fw-600">{{ number_format($key['gia']) }}<sup>đ</sup></span>
                                        {{-- khuyến mãi hết hạn --}}
                                        @if($key['khuyenmai'] != 0)
                                            <div>
                                                <span class='text-strike'>{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                                                <span class='pl-5 pr-5'>|</span>
                                                <span class='price-color'>{{ '-'.($key['khuyenmai']*100).'%' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- đánh giá --}}
                                    <div class='pt-10'>
                                        @if ($key['danhgia']['qty'] != 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($key['danhgia']['star'] >= $i)
                                                <i class="fas fa-star checked"></i>
                                                @else
                                                <i class="fas fa-star uncheck"></i>
                                                @endif
                                            @endfor
                                            <span class='ml-10'>{{ $key['danhgia']['qty'] }} đánh giá</span>
                                            @else
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                        @endif
                                    </div>
                                    
                                    {{-- so sánh --}}
                                    <div class='pt-10'>
                                        <div id="{{ 'brand_'.$key['tensp_url'] }}" type='button' class="compare-btn main-color-text">So sánh</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex">
                        <div id='prev-brand' class="prev-owl-carousel d-flex align-items-center btn-owl-left-style-1"><i class="fas fa-chevron-left fz-26"></i></div>
                        <div id='next-brand' class="next-owl-carousel d-flex align-items-center btn-owl-right-style-1"><i class="fas fa-chevron-right fz-26"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- sản phẩm tương tự --}}
        <div class='row pt-50'>
            <div class='col-lg-12 detail-item'>
                <div class='detail-item-title'>Sản phẩm tương tự</div>
                <div class='relative'>
                    <div id='similar-pro-carousel' class="owl-carousel owl-theme m-0">
                        @foreach($lst_similarPro as $key)
                        <div class='detail-item-content'>
                            {{-- hình ảnh --}}
                            <img src="{{ $url_phone.$key['hinhanh'] }}" alt="">
                            {{-- tên điện thoại --}}
                            <div class='text-center pl-10 pr-10'>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='fw-600 black'>{{ $key['tensp'] }}</a>
                            </div>
                            {{-- giá --}}
                            <div class="d-flex justify-content-center">
                                <div>
                                    <div class='d-flex flex-column fz-14'>
                                        <span class="price-color fw-600">{{ number_format($key['gia']) }}<sup>đ</sup></span>
                                        {{-- khuyến mãi hết hạn --}}
                                        @if($key['khuyenmai'] != 0)
                                            <div>
                                                <span class='text-strike'>{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                                                <span class='pl-5 pr-5'>|</span>
                                                <span class='price-color'>{{ '-'.($key['khuyenmai']*100).'%' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- đánh giá --}}
                                    <div class='pt-10'>
                                        @if ($key['danhgia']['qty'] != 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($key['danhgia']['star'] >= $i)
                                                <i class="fas fa-star checked"></i>
                                                @else
                                                <i class="fas fa-star uncheck"></i>
                                                @endif
                                            @endfor
                                            <span class='ml-10'>21 đánh giá</span>
                                            @else
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                            <i class="fas fa-star uncheck"></i>
                                        @endif
                                    </div>
                                    {{-- so sánh --}}
                                    <div class='pt-10'>
                                        <div id="{{ 'similar_'.$key['tensp_url'] }}" type='button' class="compare-btn main-color-text">So sánh</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex">
                        <div id='prev-similar' class="prev-owl-carousel d-flex align-items-center btn-owl-left-style-1"><i class="fas fa-chevron-left fz-26"></i></div>
                        <div id='next-similar' class="next-owl-carousel d-flex align-items-center btn-owl-right-style-1"><i class="fas fa-chevron-right fz-26"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- gửi đánh giá --}}
        <div class='row pt-50 pb-50'>
            <div class='fz-20 fw-600 mb-10 p-0'>
                @if ($lst_evaluate['total-rating'] != 0)
                    {{ $lst_evaluate['total-rating']}} đánh giá {{ $phone['tensp'] }}
                @else
                    <div class='fz-20 fw-600'>Hãy là người đầu tiên đánh giá {{ $phone['tensp'] }}</div>    
                @endif
            </div>
            
            {{-- nếu đã có đánh giá --}}
            @if ($lst_evaluate['total-rating'] != 0)
                <div class="col-lg-2 col-md-4 d-flex align-items-center justify-content-center p-0 border">
                    <div class='d-flex justify-content-center align-items-center'>
                        <span class='detail-vote-avg'></span>
                        <i class="fas fa-star checked fz-34 ml-5"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 d-flex align-items-center justify-content-center p-0 border">
                    {{-- tổng số lương đánh giá --}}
                    <input type="hidden" id='total_rating' value='{{ $lst_evaluate['total-rating'] }}'>

                    {{-- sao đánh giá --}}
                    <div class="w-90 mt-20 mb-20">
                        @for ($i = 5; $i >= 1; $i--)
                            <div class='d-flex justify-content-between p-5 fz-16'>
                                <div class='d-flex align-items-center w-5'>
                                    <span>{{ $i }}</span>
                                    <i class="fas fa-star checked ml-5"></i>
                                </div>
                                <div class='d-flex align-items-center w-60'>
                                    <div class='detail-progress-bar'>
                                        <div id='{{'percent-' . $i . '-star'}}' 
                                            data-id='{{ $lst_evaluate['rating'][$i] }}'>
                                        </div>
                                    </div>
                                </div>
                                <div class='d-flex align-items-center w-20'>{{ $lst_evaluate['rating'][$i] }} đánh giá</div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif
            <div class="col-lg-6 col-md-12 d-flex align-items-center justify-content-center p-0 border">
                {{-- chưa mua hàng --}}
                @if (empty($haveNotEvaluated))
                    {{-- chưa mua sản phẩm này --}}
                    @if (!$bought)
                        <div class='d-flex align-items-center justify-content-center p-40'>
                            <div class='gray-1 mr-10'>Nhanh tay sở hữu sản phẩm.</div>
                            <div type="button" data-id="{{$phone['id']}}" class='buy-now main-color-text'>Mua ngay</div>
                        </div>
                    @endif
                {{-- gửi đánh giá --}}
                @else
                    <div class="w-100">
                        <div class='relative p-20'>
                            <div class='d-flex flex-column'>
                                {{-- sao đánh giá --}}
                                <div class="d-flex align-items-center">
                                    <span>Chọn đánh giá của bạn</span>
                                    <div class='d-flex align-items-center ml-10'>
                                        <i class="star-rating fas fa-star gray-2 fz-20 pr-5" data-id='1'></i>
                                        <i class="star-rating fas fa-star gray-2 fz-20 pr-5" data-id='2'></i>
                                        <i class="star-rating fas fa-star gray-2 fz-20 pr-5" data-id='3'></i>
                                        <i class="star-rating fas fa-star gray-2 fz-20 pr-5" data-id='4'></i>
                                        <i class="star-rating fas fa-star gray-2 fz-20 pr-5" data-id='5'></i>

                                        <input type="hidden" id='star_rating' name='star_rating' value='0'>
                                    </div>
                                </div>

                                {{-- sản phẩm đánh giá --}}
                                <div id="phone-evaluate-div" class="mt-20">
                                    <div id="phone-evaluate-show" type="button" class="main-color-text"><i class="fal fa-mobile mr-10"></i>Chọn điện thoại muốn đánh giá</div>
                                </div>
                                <input type="hidden" id="lst_id" name="lst_id">
                                
                                {{-- đánh giá --}}
                                <div class='pt-20'>
                                    <div class="fw-600 mb-5">Đánh giá</div>
                                    <textarea id="evaluate_content" name="evaluate_content" rows="3" maxlength="250"
                                        placeholder="Hãy chia sẽ cảm nhận của bạn về sản phẩm (Tối đa 250 ký tự)"></textarea>

                                    {{-- ảnh đính kèm & gửi đánh giá --}}
                                    <div class='d-flex justify-content-between align-items-center pt-10'>
                                        <div class="d-flex">
                                            <input type="hidden" class='qty-img-inp' value='0'>
                                            <input class='upload-evaluate-image none-dp' type="file" multiple accept="image/*">
                                            <div class="array_evaluate_image"></div>
                                            <div id='btn-photo-attached' class='pointer-cs'>
                                                <i class="fas fa-camera"></i>
                                                <span>Ảnh đính kèm</span>
                                                <span class='qty-img'></span></span>
                                            </div>
                                        </div>
                                        <div id="send-evaluate-btn" class='main-btn p-10'>Gửi đánh giá</div>
                                    </div>
                                    <div class="evaluate-img-div"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- đánh giá sản phẩm và đã đăng nhập --}}
        @if (session('user'))
            @if ($lst_evaluate['total-rating'] != 0)
                <div id='list-comment' class="row">
                    {{-- đánh giá của người dùng --}}
                    <div class="fw-600 fz-20 mb-10">Đánh giá của tôi</div>
                    @foreach($lst_evaluate['evaluate'] as $key)
                        @if ($key['taikhoan']['id'] == session('user')->id)
                            <div class="col-lg-8 col-sm-10">
                                <div class='d-flex flex-column pt-20 pb-20'>
                                    {{-- ảnh đại diện & tên & sao & ngày đăng --}}
                                    <div class='d-flex'>
                                        <img src="{{$key['taikhoan']['anhdaidien']}}" class='circle-img' alt="" width="80px">
                                        <div class='d-flex flex-column justify-content-between pl-20'>
                                            <div class="d-flex align-items-center">
                                                <b>{{ $key['taikhoan']['hoten'] }}</b>
                                                <div class="ml-10 success-color fz-14"><i class="fas fa-check-circle mr-5"></i>Đã mua hàng tại LDMobile</div>
                                            </div>
                                            
                                            <div class='d-flex align-items-center'>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if($key['danhgia'] >= $i)
                                                    <i class="fas fa-star checked"></i>
                                                    @else
                                                    <i class="fas fa-star uncheck"></i>
                                                    @endif
                                                @endfor
                                                <div class="ml-10 mr-10 gray-1">|</div>
                                                <div class="gray-1">Màu sắc: {{ $key['sanpham']['mausac'] }}</div>
                                            </div>
                                            {{-- sao đánh giá --}}
                                            <input type="hidden" id="evaluate-rating-{{$key['id']}}" value="{{$key['danhgia']}}">
                                            <div>{{ $key['thoigian']}}</div>
                                        </div>
                                    </div>

                                    {{-- nội dung --}}
                                    <div class='evaluate-content-div pt-10'>
                                        <div>{{ $key['noidung'] }}</div>
                                    </div>
                                    <input type="hidden" id="evaluate-content-{{$key['id']}}" value="{{ $key['noidung'] }}">

                                    {{-- hình ảnh --}}
                                    @if (count($key['hinhanh']) != 0)
                                        <div class='pt-10'>
                                            @foreach ($key['hinhanh'] as $img)
                                                <img type="button" data-id="{{$img['id']}}" data-evaluate="{{$key['id']}}"
                                                    src="{{$url_evaluate.$img['hinhanh']}}" alt="" class='img-evaluate'>    
                                            @endforeach
                                        </div>    
                                    @endif

                                    {{-- like & reply & edit & delete --}}
                                    <div class='pt-20 d-flex align-items-center'>
                                        {{-- nút thích --}}
                                        <div type="button" data-id="{{$key['id']}}" class='like-comment {{$key['liked'] ? 'liked-comment' : ''}}'>
                                            <i id="like-icon" class="{{$key['liked'] ? "fas fa-thumbs-up" : "fal fa-thumbs-up"}} ml-5 mr-5"></i>Hữu ích
                                            (<div data-id="{{$key['id']}}" class="qty-like-comment">{{ $key['soluotthich'] }}</div>)
                                        </div>
                                        {{-- trả lời --}}
                                        <div data-id="{{$key['id']}}" class="reply-btn">
                                            <i class="fas fa-reply mr-5"></i>Trả lời
                                        </div>
                                        {{-- chỉnh sửa & xóa --}}
                                        <div class="d-flex ml-40">
                                            <div data-id="{{$key['id']}}" class="edit-evaluate main-btn p-10 mr-10"><i class="fas fa-pen"></i></div>
                                            <div data-id="{{$key['id']}}" class="delete-evaluate checkout-btn p-10"><i class="fal fa-trash-alt"></i></div>
                                        </div>
                                    </div>
                                    {{-- trả lời --}}
                                    <div data-id="{{$key['id']}}" class="reply-div mt-20">
                                        <div class="d-flex">
                                            <img src="{{session('user')->htdn == 'nomal' ? $url_user.session('user')->anhdaidien : session('user')->anhdaidien}}" alt="" width="40px" height="40px" class="circle-img">
                                            <div class="d-flex flex-column ml-10">
                                                <textarea data-id="{{$key['id']}}" name="reply-content" id="reply-content-{{$key['id']}}" 
                                                cols="100" rows="1" placeholder="Nhập câu trả lời (Tối đa 250 ký tự)"
                                                maxlength="250"></textarea>
                                            </div>
                                            
                                        </div>
                                        <div class="d-flex justify-content-end mt-5">
                                            <div data-id="{{$key['id']}}" type="button" class="cancel-reply price-color mr-10">Hủy</div>
                                            <div data-id="{{$key['id']}}" type="button" class="send-reply main-color-text">Trả lời</div>
                                        </div>
                                    </div>
                                    {{-- danh sách trả lời --}}
                                    <div class="mt-20">
                                        @if (!empty($key['phanhoi']))
                                            <?php $count = count($key['phanhoi']); ?>
                                            @if ($count == 1)
                                                <div class="d-flex mb-20">
                                                    <img src="{{$key['phanhoi'][0]['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                    <div class="reply-content-div ml-10">
                                                        {{-- họ tên & thời gian reply --}}
                                                        <div class="d-flex align-items-center">
                                                            <b>{{$key['phanhoi'][0]['taikhoan']['hoten']}}</b>
                                                            <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                            <div class="gray-1">{{$key['phanhoi'][0]['thoigian']}}</div>
                                                        </div>
                                                        {{-- nội dung --}}
                                                        <div class="mt-5">{{$key['phanhoi'][0]['noidung']}}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex mb-20">
                                                    <img src="{{$key['phanhoi'][0]['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                    <div class="reply-content-div ml-10">
                                                        {{-- họ tên & thời gian reply --}}
                                                        <div class="d-flex align-items-center">
                                                            <b>{{$key['phanhoi'][0]['taikhoan']['hoten']}}</b>
                                                            <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                            <div class="gray-1">{{$key['phanhoi'][0]['thoigian']}}</div>
                                                        </div>
                                                        {{-- nội dung --}}
                                                        <div class="mt-5">{{$key['phanhoi'][0]['noidung']}}</div>
                                                    </div>
                                                </div>
                                                <div type="button" data-id="{{$key['id']}}" class="see-more-reply main-color-text fw-600"><i class="far fa-level-up mr-10" style="transform: rotate(90deg)"></i>Xem thêm {{$count - 1}} câu trả lời</div>
                                                <div data-id="{{$key['id']}}" class="reply-content-div-hidden">
                                                    @foreach($key['phanhoi'] as $idx => $key)
                                                        @if($idx == 0) @continue @endif
                                                        <div class="d-flex mb-20">
                                                            <img src="{{$key['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                            <div class="reply-content-div ml-10">
                                                                {{-- họ tên & thời gian reply --}}
                                                                <div class="d-flex align-items-center">
                                                                    <b>{{$key['taikhoan']['hoten']}}</b>
                                                                    <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                                    <div class="gray-1">{{$key['thoigian']}}</div>
                                                                </div>
                                                                {{-- nội dung --}}
                                                                <div class="mt-5">{{$key['noidung']}}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endif
                    @endforeach

                    {{-- đánh giá khác --}}
                    <div class="fw-600 fz-20 mb-10">Đánh giá khác</div>
                    @foreach($lst_evaluate['evaluate'] as $key)
                        @if ($key['taikhoan']['id'] != session('user')->id)
                            <div class="col-lg-8 col-sm-10">
                                <div class='d-flex flex-column pt-20 pb-20'>
                                    {{-- ảnh đại diện & tên & sao & ngày đăng --}}
                                    <div class='d-flex'>
                                        <img src="{{$key['taikhoan']['anhdaidien']}}" class='circle-img' alt="" width="80px">
                                        <div class='d-flex flex-column justify-content-between pl-20'>
                                            <div class="d-flex align-items-center">
                                                <b>{{ $key['taikhoan']['hoten'] }}</b>
                                                <div class="ml-10 success-color fz-14"><i class="fas fa-check-circle mr-5"></i>Đã mua hàng tại LDMobile</div>
                                            </div>
                                            <div class='d-flex align-items-center'>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if($key['danhgia'] >= $i)
                                                    <i class="fas fa-star checked"></i>
                                                    @else
                                                    <i class="fas fa-star uncheck"></i>
                                                    @endif
                                                @endfor
                                                <div class="ml-10 mr-10 gray-1">|</div>
                                                <div class="gray-1">Màu sắc: {{ $key['sanpham']['mausac'] }}</div>
                                            </div>
                                            <div>{{ $key['thoigian']}}</div>
                                        </div>
                                    </div>

                                    {{-- nội dung --}}
                                    <div class='evaluate-content-div pt-10'>
                                        <div>{{ $key['noidung'] }}</div>
                                    </div>

                                    {{-- hình ảnh --}}
                                    @if (count($key['hinhanh']) != 0)
                                        <div class='pt-10'>
                                            @foreach ($key['hinhanh'] as $img)
                                                <img type="button" data-id="{{$img['id']}}" data-evaluate="{{$key['id']}}"
                                                    src="{{$url_evaluate.$img['hinhanh']}}" alt="" class='img-evaluate'>    
                                            @endforeach
                                        </div>    
                                    @endif

                                    {{-- like --}}
                                    <div class='pt-20 d-flex align-items-center'>
                                        {{-- nút thích --}}
                                        <div type="button" data-id="{{$key['id']}}" class='like-comment {{$key['liked'] ? 'liked-comment' : ''}}'>
                                            <i id="like-icon" class="{{$key['liked'] ? "fas fa-thumbs-up" : "fal fa-thumbs-up"}} ml-5 mr-5"></i>Hữu ích
                                            (<div data-id="{{$key['id']}}" class="qty-like-comment">{{ $key['soluotthich'] }}</div>)
                                        </div>
                                        {{-- trả lời --}}
                                        <div data-id="{{$key['id']}}" class="reply-btn">
                                            <i class="fas fa-reply mr-5"></i>Trả lời
                                        </div>
                                    </div>
                                    {{-- trả lời --}}
                                    <div data-id="{{$key['id']}}" class="reply-div mt-20">
                                        <div class="d-flex">
                                            <img src="{{session('user')->htdn == 'nomal' ? $url_user.session('user')->anhdaidien : session('user')->anhdaidien}}" alt="" width="40px" height="40px" class="circle-img">
                                            <div class="d-flex flex-column ml-10">
                                                <textarea data-id="{{$key['id']}}" name="reply-content" id="reply-content-{{$key['id']}}" 
                                                cols="100" rows="1" placeholder="Nhập câu trả lời (Tối đa 250 ký tự)"
                                                maxlength="250"></textarea>
                                            </div>
                                            
                                        </div>
                                        <div class="d-flex justify-content-end mt-5">
                                            <div data-id="{{$key['id']}}" type="button" class="cancel-reply price-color mr-10">Hủy</div>
                                            <div data-id="{{$key['id']}}" type="button" class="send-reply main-color-text">Trả lời</div>
                                        </div>
                                    </div>
                                    {{-- danh sách trả lời --}}
                                    <div class="mt-20">
                                        @if (!empty($key['phanhoi']))
                                            <?php $count = count($key['phanhoi']); ?>
                                            @if ($count == 1)
                                                <div class="d-flex mb-20">
                                                    <img src="{{$key['phanhoi'][0]['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                    <div class="reply-content-div ml-10">
                                                        {{-- họ tên & thời gian reply --}}
                                                        <div class="d-flex align-items-center">
                                                            <b>{{$key['phanhoi'][0]['taikhoan']['hoten']}}</b>
                                                            <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                            <div class="gray-1">{{$key['phanhoi'][0]['thoigian']}}</div>
                                                        </div>
                                                        {{-- nội dung --}}
                                                        <div class="mt-5">{{$key['phanhoi'][0]['noidung']}}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex mb-20">
                                                    <img src="{{$key['phanhoi'][0]['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                    <div class="reply-content-div ml-10">
                                                        {{-- họ tên & thời gian reply --}}
                                                        <div class="d-flex align-items-center">
                                                            <b>{{$key['phanhoi'][0]['taikhoan']['hoten']}}</b>
                                                            <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                            <div class="gray-1">{{$key['phanhoi'][0]['thoigian']}}</div>
                                                        </div>
                                                        {{-- nội dung --}}
                                                        <div class="mt-5">{{$key['phanhoi'][0]['noidung']}}</div>
                                                    </div>
                                                </div>
                                                <div type="button" data-id="{{$key['id']}}" class="see-more-reply main-color-text fw-600"><i class="far fa-level-up mr-10" style="transform: rotate(90deg)"></i>Xem thêm {{$count - 1}} câu trả lời</div>
                                                <div data-id="{{$key['id']}}" class="reply-content-div-hidden">
                                                    @foreach($key['phanhoi'] as $idx => $key)
                                                        @if($idx == 0) @continue @endif
                                                        <div class="d-flex mb-20">
                                                            <img src="{{$key['taikhoan']['anhdaidien']}}" alt="" width="40px" height="40px" class="circle-img">
                                                            <div class="reply-content-div ml-10">
                                                                {{-- họ tên & thời gian reply --}}
                                                                <div class="d-flex align-items-center">
                                                                    <b>{{$key['taikhoan']['hoten']}}</b>
                                                                    <div class="ml-10 mr-10 fz-6 gray-1"><i class="fas fa-circle"></i></div>
                                                                    <div class="gray-1">{{$key['thoigian']}}</div>
                                                                </div>
                                                                {{-- nội dung --}}
                                                                <div class="mt-5">{{$key['noidung']}}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @else
            <div class="row">
                <div class="col-lg-12 border">
                    <div class="p-50 text-center">Vui lòng đăng nhập để xem đánh giá.</div>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- xem ảnh đánh giá --}}
<div class="relative">
    <div class="see-review-image">
        {{-- nút đóng --}}
        <div class="close-see-review-image"><i class="fas fa-times mr-10"></i>Đóng</div>
        {{-- ảnh đang xem và 2 nút prev, next --}}
        <div class="d-flex align-items-center justify-content-center">
            <div class="prev-see-review-image"><i class="fas fa-chevron-left fz-30"></i></div>
            <img id="review-image-main" src="images/No-image.jpg" alt="review image main">
            <div class="next-see-review-image"><i class="fas fa-chevron-right fz-30"></i></div>
        </div>
        <hr>
        {{-- ảnh khác --}}
        <div id="another-review-image" class="d-flex justify-content-center align-items-center"></div>
    </div>
</div>

{{-- modal kiểm tra còn hàng --}}
<div class="modal fade" id="check-qty-in-stock-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
                <div class="p-50">
                    {{-- tên điện thoại --}}
                    <div id="check-qty-in-stock-phone-name" class="fw-600 fz-22"></div>
                    <hr>
                    {{-- chọn màu cần kiểm tra --}}
                    <div class="fw-600 mb-10">Chọn màu cần kiểm tra</div>
                    <div id="check-qty-in-stock-lst-color" class="d-flex flex-wrap mb-20"></div>
                    <div id="check-qty-in-stock-status"></div>

                    {{-- chọn chi nhánh --}}
                    <div id="check-qty-in-stock-branch">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="select">
                                    <div id='area-selected' data-id="{{$lst_area[0]->id}}" data-flag="1" class="select-selected">
                                        <div id='area-name'>{{$lst_area[0]->tentt}}</div>
                                        <i class="far fa-chevron-down fz-14"></i>
                                    </div>
                
                                    <div id='area-box' class="select-box">
                                        {{-- option --}}
                                        <div class="select-option">
                                            @foreach ($lst_area as $key)
                                                <div class="option-area select-single-option" data-area='{{ $key->id }}'>{{ $key->tentt }}</div>
                                            @endforeach 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- danh sách chi nhánh --}}
                            <div class="col-lg-8">
                                <div class="list-branch mt-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <div class='col-md-6 mx-auto'>
                            <img src="{{ $url_phone.$phone['hinhanh'] }}" alt="">
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
                            <td class='w-30 fw-600'>Thiết kế</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['thiet_ke']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Chất liệu</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['chat_lieu']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Kích thước</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['kich_thuoc']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Khối lượng</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['khoi_luong']
                                }}
                            </td>
                        </tr>

                        {{-- màn hình --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>màn hình</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Công nghệ màn hình</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Mặt kính cảm ứng</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['kinh_cam_ung']
                                }}
                            </td>
                        </tr>

                        {{-- Camera sau --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>camera sau</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Quay phim</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['quay_phim'] as $key)
                                    <div class="mb-5">{{ $key['chat_luong'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Đèn Flash</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['den_flash']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Tính năng</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['tinh_nang'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>

                        {{-- Camera trước --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>camera trước</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Tính năng</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['camera_truoc']['tinh_nang'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>

                        {{-- hệ điều hành & CPU --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>hệ điều hành & cpu</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Hệ điều hành</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['HDH']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Chip xử lý (CPU)</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Tốc độ CPU</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU_speed']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Chip đồ họa (GPU)</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['GPU']
                                }}
                            </td>
                        </tr>
                        
                        {{-- bộ nhớ & lưu trữ --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>bộ nhớ & lưu trữ</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>RAM</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['RAM']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Bộ nhớ trong</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Bộ nhớ còn lại (khả dụng) khoảng</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_con_lai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Thẻ nhớ</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['the_nho']
                                }}
                            </td>
                        </tr>

                        {{-- kết nối --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>kết nối</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Mạng di động</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>SIM</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['SIM']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Wifi</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['wifi'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>GPS</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['GPS'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Bluetooth</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['bluetooth'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Cổng kết nối/sạc</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['cong_sac']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Jack tai nghe</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['jack_tai_nghe']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Kêt nối khác</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['ket_noi_khac'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>

                        {{-- Pin & Sạc --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>pin & sạc</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Dung lượng pin</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['dung_luong']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Loại pin</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['loai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Công nghệ pin</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['pin']['cong_nghe'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>

                        {{-- Tiện ích --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>tiện ích</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Bảo mật nâng cao</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['bao_mat'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Tính năng đặc biệt</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['tinh_nang_khac'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Ghi âm</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['ghi_am']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Xem phim</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['xem_phim'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Nghe nhạc</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['nghe_nhac'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>

                        {{-- thông tin khác --}}
                        <tr>
                            <td colspan="2" class="p-0">
                                <div class='detail-specifications-title'>thông tin khác</div>
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 fw-600'>Thời điểm ra mắt</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_tin_khac']['thoi_diem_ra_mat']
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>

{{-- modal chỉnh sửa đánh giá --}}
<div class="modal fade" id="edit-evaluate-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fz-22 fw-600">Đánh giá của tôi</div>
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
            </div>
            <div class="modal-body p-0">
                <div class='relative p-30'>
                    <div class='d-flex flex-column'>
                        <input type="hidden" id="evaluate_id" value="0">
                        {{-- sao đánh giá --}}
                        <div class="d-flex align-items-center">
                            <span class="fw-600">Chọn đánh giá của bạn</span>
                            <div class='d-flex align-items-center ml-10'>
                                <i class="edit-star-rating fas fa-star gray-2 fz-20 pr-5" data-id='1'></i>
                                <i class="edit-star-rating fas fa-star gray-2 fz-20 pr-5" data-id='2'></i>
                                <i class="edit-star-rating fas fa-star gray-2 fz-20 pr-5" data-id='3'></i>
                                <i class="edit-star-rating fas fa-star gray-2 fz-20 pr-5" data-id='4'></i>
                                <i class="edit-star-rating fas fa-star gray-2 fz-20 pr-5" data-id='5'></i>

                                <input type="hidden" id='edit_star_rating' name='edit_star_rating' value='0'>
                            </div>
                        </div>
                        
                        {{-- đánh giá --}}
                        <div class='pt-20'>
                            <div class="fw-600 mb-5">Đánh giá</div>
                            <textarea id="edit_evaluate_content" name="edit_evaluate_content" rows="3" maxlength="250"
                            placeholder="Hãy chia sẽ cảm nhận của bạn về sản phẩm (Tối đa 250 ký tự)"></textarea>
                            
                            {{-- ảnh đính kèm & gửi đánh giá --}}
                            <div class='d-flex justify-content-between align-items-center pt-10'>
                                <div class="d-flex">
                                    {{-- input số lượng hình --}}
                                    <input type="hidden" class='edit-qty-img-inp' value='0'>
                                    {{-- input chọn hình --}}
                                    <input class='edit-upload-evaluate-image none-dp' type="file" multiple accept="image/*">
                                    {{-- danh sách input base64 --}}
                                    <div class="edit_array_evaluate_image"></div>
                                    {{-- icon chọn hình --}}
                                    <div id='edit-btn-photo-attached' class='pointer-cs'>
                                        <i class="fas fa-camera"></i>
                                        <span>Ảnh đính kèm</span>
                                        {{-- số lượng hình --}}
                                        <span class='edit-qty-img'></span></span>
                                    </div>
                                </div>
                            </div>
                            {{-- hình xem trước --}}
                            <div class="edit-evaluate-img-div"></div>
                            <div id="edit-send-evaluate-btn" class='main-btn w-100 p-10 mt-20'>Cập nhật</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal chọn điện thoại để đánh giá --}}
<div class="modal fade" id="phone-evaluate-modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fz-22 fw-600">Sản phẩm đã mua</div>
            </div>
            <div class="modal-body">
                <div class="p-20 phone-evaluate-div">
                    <div class="row">
                        @foreach ($haveNotEvaluated as $key)
                            <div class="col-lg-6 mb-20">
                                <div data-id="{{$key['id']}}" class="d-flex phone-evaluate p-10">
                                    <img src="{{$url_phone.$key['hinhanh']}}" alt="" width="100px">
                                    <div>
                                        <div class="fw-600">{{$key['tensp']}}</div>
                                        <div>Màu sắc: {{$key['mausac']}}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="pl-20 pr-20 pb-20">
                    <div class="mt-10">
                        <input type="checkbox" id="all_phone_evaluate" name="all_phone_evaluate">
                        <label for="all_phone_evaluate">Chọn tất cả</label>
                    </div>
    
                    <div class="mt-40">
                        <div id="choose-phone-evaluate" class="main-btn p-10 none-dp">Tiếp tục</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- info modal --}}
@include("user.content.modal.thongbao-modal")

{{-- delete modal --}}
@include("user.content.modal.xoa-modal")

<div id="toast"></div>

@include('user.content.section.sec-logo')

@stop