@extends("user.layout")
@section("content")

@section("direct")SẢN PHẨM @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='product-shop-wrapper pb-30'>
    <div class='container'>
        {{-- tên điện thoại, sao, lượt đánh giá --}}
        <div class='d-flex flex-row align-items-center justify-content-between'>
            <div class="d-flex align-items-center">
                <div class='fz-32 font-weight-600'>{{ $phone['tensp'] }}</div>
                <div class='pl-20'>
                    @if ($phone['danhgia']['qty'] != 0)
                        @for ($i = 1; $i <= 5; $i++)
                            @if($phone['danhgia']['star'] > $i)
                            <i class="fas fa-star checked"></i>
                            @else
                            <i class="fas fa-star uncheck"></i>
                            @endif
                        @endfor
                        <span class='ml-10'>{{ $phone['danhgia']['qty'].' đánh giá' }}</span>
                    @endif
                </div>
            </div>
            {{-- nút yêu thích --}}
            <div class="favorite-tag"><i class="far fa-heart"></i></div>
        </div><hr>

        {{-- điện thoại --}}
        <div class='row'>
            {{-- hình ảnh --}}
            <div class='col-md-4 mb-20'>
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
                            <div class='prev-owl-carousel'><i class="far fa-chevron-left fz-26"></i></div>
                            <div class='next-owl-carousel'><i class="far fa-chevron-right fz-26"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- dung lượng & màu sắc & khuyến mãi & bảo hành --}}
            <div class='col-md-4 mb-20 fz-14'>
                <div class='d-flex flex-column'>
                    {{-- giá --}}
                    <div class='d-flex flex-row align-items-end'>
                        <div class='font-weight-600 price-color fz-26'>{{ number_format($phone['giakhuyenmai']) }}<sup>đ</sup></div>
                        <div class="d-flex ml-20 fz-18">
                            <div>Giá niêm yết :</div>
                            <b class='ml-5 text-strike'>{{ number_format($phone['gia']) }}<sup>đ</sup></b>
                        </div>
                        
                    </div>
                    {{-- dung lượng --}}
                    <div class='detail-title pt-10'>Dung lượng</div>
                    <div class='row'>
                        @foreach ($lst_variation['capacity'] as $key)
                            <div class="col-md-4 col-sm-3 p-10">
                            @if ($key['dungluong'] == $phone['dungluong'] && $key['ram'] == $phone['ram'])
                                <div type='button' class='detail-option box-shadow selected'>
                                    <div class="font-weight-600">{{ $key['dungluong'] }}</div>
                                    <div class="price-color font-weight-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @else
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='detail-option box-shadow'>
                                    <div>{{ $key['dungluong'] }}</div>
                                    <div class="price-color font-weight-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
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
                                <div type='button' class='color-option detail-option box-shadow selected'
                                    data-image="{{ $url_phone.$key['hinhanh'] }}">
                                    <div class="font-weight-600">{{ $key['mausac'] }}</div>
                                    <div class="price-color font-weight-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @else
                                <div type="button" class='color-option detail-option box-shadow'
                                    data-image="{{ $url_phone.$key['hinhanh'] }}">
                                    <div>{{ $key['mausac'] }}</div>
                                    <div class="price-color font-weight-600 mt-5">{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></div>
                                </div>
                            @endif  
                            </div>  
                        @endforeach
                    </div>

                    {{-- khuyến mãi --}}
                    <div class='detail-promotion mt-40 mb-10'>
                        <div class='detail-promotion-text'><i class="fas fa-gift mr-5"></i>KHUYẾN MÃI</div>
                        <div class='detail-title'><i class="fas fa-check-circle mr-5 main-color-text"></i>{{ $phone['khuyenmai']['tenkm']}}</div>
                        <div class='detail-promotion-content'>
                            {{ $phone['khuyenmai']['noidung'] }}                           
                        </div>
                    </div>
                    {{-- mua ngay --}}
                    <a href="#" class='main-btn p-5 fz-20 font-weight-600'>MUA NGAY</a>
                </div>
            </div>
            {{-- chi nhánh --}}
            <div class='col-md-4 mb-20'>
                {{-- nhà cung cấp --}}
                <div class="d-flex mb-20">
                    <img src="{{ $url_logo.$supplier['anhdaidien'] }}" alt="" class="detail-supplier-img">
                    <div class="d-flex flex-column ml-20 mt-20">
                        <div>Cung cấp bởi <b>{{ $supplier['tenncc'] }}</b></div>
                        <img src="images/icon/genuine-icon.png" alt="" width="130px">
                    </div>
                </div>
                {{-- bảo hành --}}
                <div class='detail-warranty p-10 mb-20'>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-check mr-10 fz-18 main-color-text"></i>
                        <span>Bảo hành chính hãng {{ $phone['baohanh'] }}</span>
                    </div>
                </div>
                <div class='detail-check-qty mb-3'>CÁC CỬA HÀNG CÓ SẴN SẢN PHẨM</div>

                {{-- xem cửa hàng --}}
                <div class="select">
                    <div id='area-selected' class="select-selected">
                        <div id='area-name'>Chọn khu vực</div>
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
                <div class="list-branch">
                    @foreach ($lst_branch as $key)
                        <div class="single-branch default-cs" data-area='{{ $key['id_tt'] }}'>
                            <i class="fas fa-store mr-10"></i>{{ $key['diachi'] }}
                            <a href="#" class="ml-10">Đặt giữ hàng tại đây</a>
                        </div>
                    @endforeach
                </div>

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
            <div class='col-md-12 detail-item'>
                <div class='detail-item-title'>Cùng thương hiệu {{ $supplier['brand'] }}</div>
                <div class='relative'>
                    <div id='same-brand-pro-carousel' class="owl-carousel owl-theme m-0">
                        @foreach($lst_proSameBrand as $key)
                        <div class='detail-item-content'>
                            {{-- hình ảnh --}}
                            <img src="{{ $url_phone.$key['hinhanh'] }}" alt="">
                            
                            {{-- tên điện thoại --}}
                            <div class='text-center pl-10 pr-10'>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='font-weight-600 black'>{{ $key['tensp'] }}</a>
                            </div>
                            {{-- giá --}}
                            <div class="d-flex justify-content-center">
                                <div>
                                    <div class='d-flex flex-column fz-14'>
                                        <span class="price-color font-weight-600">{{ number_format($key['gia']) }}<sup>đ</sup></span>
                                        <div>
                                            <span class='text-strike'>{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                                            <span class='pl-5 pr-5'>|</span>
                                            <span class='price-color'>{{ '-'.($key['khuyenmai']*100).'%' }}</span>
                                        </div>
                                    </div>
                                    {{-- đánh giá --}}
                                    <div class='pt-10'>
                                        @if ($key['danhgia']['qty'] != 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($key['danhgia']['star'] > $i)
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
            <div class='col-md-12 detail-item'>
                <div class='detail-item-title'>Sản phẩm tương tự</div>
                <div class='relative'>
                    <div id='similar-pro-carousel' class="owl-carousel owl-theme m-0">
                        @foreach($lst_similarPro as $key)
                        <div class='detail-item-content'>
                            {{-- hình ảnh --}}
                            <img src="{{ $url_phone.$key['hinhanh'] }}" alt="">
                            {{-- tên điện thoại --}}
                            <div class='text-center pl-10 pr-10'>
                                <a href="{{route('user/chi-tiet', ['name' => $key['tensp_url']])}}" class='font-weight-600 black'>{{ $key['tensp'] }}</a>
                            </div>
                            {{-- giá --}}
                            <div class="d-flex justify-content-center">
                                <div>
                                    <div class='d-flex flex-column fz-14'>
                                        <span class="price-color font-weight-600">{{ number_format($key['gia']) }}<sup>đ</sup></span>
                                        <div>
                                            <span class='text-strike'>{{ number_format($key['giakhuyenmai']) }}<sup>đ</sup></span>
                                            <span class='pl-5 pr-5'>|</span>
                                            <span class='price-color'>{{ '-'.($key['khuyenmai']*100).'%' }}</span>
                                        </div>
                                    </div>
                                    {{-- đánh giá --}}
                                    <div class='pt-10'>
                                        @if ($key['danhgia']['qty'] != 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($key['danhgia']['star'] > $i)
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
        <div class='row pt-50'>
            <div class='col-md-12'>
                @if ($lst_evaluate['total-rating'] != 0)
                <div class='fz-20 font-weight-600'>{{ $lst_evaluate['total-rating']}} đánh giá {{ $phone['tensp'] }}</div>
                @else
                <div class='fz-20 font-weight-600'>Hãy là người đầu tiên đánh giá {{ $phone['tensp'] }}</div>    
                @endif
                
                <div class='pt-20 pb-20'>
                    {{-- nếu đã có đánh giá --}}
                    @if ($lst_evaluate['total-rating'] != 0)
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    {{-- sao trung bình --}}
                                    <td class='w-10 vertical-center'>
                                        <div class='d-flex justify-content-center align-items-center p-20'>
                                            <span class='detail-vote-avg'></span>
                                            <i class="fas fa-star checked fz-34 ml-5"></i>
                                        </div>
                                    </td>
                                    {{-- số lượt đánh giá --}}
                                    <td class='w-40 vertical-center'>
                                        {{-- tổng số lương đánh giá --}}
                                        <input type="hidden" id='total-rating' value='{{ $lst_evaluate['total-rating'] }}'>

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
                                                            data-id='<?php echo $lst_evaluate['rating'][$i] ?>'></div>
                                                    </div>
                                                </div>
                                                <div class='d-flex align-items-center w-20'><?php echo $lst_evaluate['rating'][$i] ?> đánh giá</div>
                                            </div>
                                        @endfor
                                    </td>
                                    {{-- đánh giá --}}
                                    <td class='w-50 vertical-center'>
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
                                                {{-- chọn sao --}}
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

                                                {{-- đánh giá --}}
                                                <div class='pt-30'>
                                                    <label for="comment" class='form-label'>Đánh giá</label>
                                                    <textarea name="comment" rows="3" placeholder="Hãy chia sẽ cảm nhận của bạn về sản phẩm"></textarea>
                                                    
                                                    {{-- ảnh đính kèm & gửi đánh giá --}}
                                                    <div class='d-flex justify-content-between align-items-center pt-10'>
                                                        <div class="d-flex flex-fill align-items-center justify-content-between">
                                                            <div class="d-flex">
                                                                <input type="hidden" id='qty-img' value='0'>
                                                                <input class='upload-inp' type="file" multiple accept="image/*">
                                                                <div id='btn-photo-attached' class='pointer-cs'>
                                                                    <i class="fas fa-camera"></i>
                                                                    <span>Ảnh đính kèm</span>
                                                                    <span class='qty-img'></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='main-btn p-10'>Gửi đánh giá</div>
                                                    </div>
                                                    <div class="evaluate-img-div"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        {{-- đánh giá --}}
                        <div class='w-50 vertical-center border'>
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
                                    {{-- chọn sao --}}
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

                                    {{-- đánh giá --}}
                                    <div class='pt-30'>
                                        <label for="comment" class='form-label'>Đánh giá</label>
                                        <textarea name="comment" rows="3" placeholder="Hãy chia sẽ cảm nhận của bạn về sản phẩm (Tối thiểu 10 ký tự)"></textarea>
                                        
                                        {{-- ảnh đính kèm & gửi đánh giá --}}
                                        <div class='d-flex justify-content-between align-items-center pt-10'>
                                            <div class="d-flex flex-fill align-items-center justify-content-between">
                                                <div class="d-flex">
                                                    <input type="hidden" id='qty-img' value='0'>
                                                    <input class='upload-inp' type="file" multiple accept="image/*">
                                                    <div id='btn-photo-attached' class='pointer-cs'>
                                                        <i class="fas fa-camera"></i>
                                                        <span>Ảnh đính kèm</span>
                                                        <span class='qty-img'></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='main-btn p-10'>Gửi đánh giá</div>
                                        </div>
                                        <div class="evaluate-img-div"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    @endif
                </div>
            </div>
        </div>

        {{-- đánh giá sản phẩm --}}
        @if ($lst_evaluate['total-rating'] != 0)
        <hr>
        <div id='list-comment'>
            @foreach($lst_evaluate['evaluate'] as $key)
            <div class='d-flex flex-column pt-20 pb-20'>
                {{-- ảnh đại diện & tên & sao & ngày đăng --}}
                <div class='d-flex'>
                    <img src="images/avt1620997169.jpg" class='circle-img w-6' alt="">
                    <div class='d-flex flex-column justify-content-between pl-20'>
                        <b>Vũ Hoàng Lâm</b>
                        <div class='d-flex align-items-center'>
                            @for ($i = 1; $i <= 5; $i++)
                                @if($key['danhgia'] > $i)
                                <i class="fas fa-star checked"></i>
                                @else
                                <i class="fas fa-star uncheck"></i>
                                @endif
                            @endfor
                            <div class="ml-10 mr-10 gray-1">|</div>
                            <div class="gray-1">Màu: {{ $key['sanpham']['mausac'] }}</div>
                        </div>
                        <div>{{ $key['thoigian']}} 20:56</div>
                    </div>
                </div>

                {{-- nội dung --}}
                <div class='pt-10'>
                    {{ $key['noidung'] }}
                </div>

                {{-- hình ảnh --}}
                @if (count($key['hinhanh']) != 0)
                <div class='pt-10'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                    <img src="images/product-details-bg.jpg" alt="" class='img-evaluate'>
                </div>    
                @endif

                {{-- like & phản hồi --}}
                <div class='d-flex align-items-center pt-20'>
                    {{-- like --}}
                    <div class='font-weight-600 fz-18 main-color-text'>
                        <i class="fas fa-thumbs-up mr-5"></i>{{ $key['soluotthich'] }}
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
        
                {{-- phản hồi --}}
                <div class='d-flex flex-column pl-70 pt-20 pb-20'>
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
            <hr>
            @endforeach
        </div>
        @endif
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
                            <td class='w-30 font-weight-600'>Thiết kế</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['thiet_ke']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Chất liệu</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['chat_lieu']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Kích thước</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['thiet_ke_trong_luong']['kich_thuoc']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Khối lượng</td>
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
                            <td class='w-30 font-weight-600'>Công nghệ màn hình</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['man_hinh']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Mặt kính cảm ứng</td>
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
                            <td class='w-30 font-weight-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Quay phim</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['quay_phim'] as $key)
                                    <div class="mb-5">{{ $key['chat_luong'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Đèn Flash</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_sau']['den_flash']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Tính năng</td>
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
                            <td class='w-30 font-weight-600'>Độ phân giải</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Tính năng</td>
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
                            <td class='w-30 font-weight-600'>Hệ điều hành</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['HDH']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Chip xử lý (CPU)</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Tốc độ CPU</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['HDH_CPU']['CPU_speed']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Chip đồ họa (GPU)</td>
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
                            <td class='w-30 font-weight-600'>RAM</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['RAM']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Bộ nhớ trong</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Bộ nhớ còn lại (khả dụng) khoảng</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['luu_tru']['bo_nho_con_lai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Thẻ nhớ</td>
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
                            <td class='w-30 font-weight-600'>Mạng di động</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>SIM</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['SIM']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Wifi</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['wifi'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>GPS</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['GPS'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Bluetooth</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['bluetooth'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Cổng kết nối/sạc</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['cong_sac']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Jack tai nghe</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['ket_noi']['jack_tai_nghe']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Kêt nối khác</td>
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
                            <td class='w-30 font-weight-600'>Dung lượng pin</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['dung_luong']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Loại pin</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['pin']['loai']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Công nghệ pin</td>
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
                            <td class='w-30 font-weight-600'>Bảo mật nâng cao</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['bao_mat'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Tính năng đặc biệt</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['tinh_nang_khac'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Ghi âm</td>
                            <td>
                                {{
                                    $phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['ghi_am']
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Xem phim</td>
                            <td>
                                @foreach ($phone['cauhinh']['thong_so_ky_thuat']['tien_ich']['xem_phim'] as $key)
                                    <div class="mb-5">{{ $key['name'] }}</div>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class='w-30 font-weight-600'>Nghe nhạc</td>
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
                            <td class='w-30 font-weight-600'>Thời điểm ra mắt</td>
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
                    <div type="button" class="main-btn p-5" data-bs-dismiss="modal">Đã hiểu</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('user.content.section.sec-logo')

@stop