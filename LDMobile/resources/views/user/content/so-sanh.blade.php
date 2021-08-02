@extends("user.layout")
@section("content")

<div class='container'>
    <div id="compare-title" class='pt-30 pb-30 fz-24 d-flex justify-content-center align-items-center'>
        <div>So sánh điện thoại</div>
        <b class="ml-5 mr-5">{{ $currentProduct['sanpham']['tensp']}}</b>
        <div>|</div>
        <b class="ml-5 mr-5">{{ $compareProduct['sanpham']['tensp']}}</b>
        @if (!empty($thirdProduct))
        <div>|</div>
        <b class="ml-5 mr-5">{{ $thirdProduct['sanpham']['tensp']}}</b>
        @endif
    </div>
    
    <section class='pb-100'>
        <table class="table">
            <tbody>
                <tr>
                    <td class='w-25'></td>
                    {{-- sản phẩm hiện tại --}}
                    <td class='w-25'>
                        <div class='d-flex flex-column pb-20'>
                            {{-- tên --}}
                            <div class="border p-10">{{ $currentProduct['sanpham']['tensp']}}</div>
                            {{-- hình --}}
                            <img src="{{ $url_phone.$currentProduct['sanpham']['hinhanh'] }}" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="red">{{ number_format($currentProduct['sanpham']['gia'], 0, '', '.') }}<sup>đ</sup></b>
                                <span class='text-strike ml-10'>{{ number_format($currentProduct['sanpham']['giakhuyenmai'], 0, '', '.')}}<sup>đ</sup></span>
                            </div>
                            <div>
                                @if ($currentProduct['sanpham']['danhgia']['qty'] != 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($currentProduct['sanpham']['danhgia']['star'] >= $i)
                                        <i class="fas fa-star checked"></i>
                                        @else
                                        <i class="fas fa-star uncheck"></i>
                                        @endif
                                    @endfor
                                    <span class='ml-10'>{{ $currentProduct['sanpham']['danhgia']['qty'] }} đánh giá</span>
                                @else
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                @endif
                            </div>
                            <hr>
                            {{-- màu sắc --}}
                            <div class="d-flex flex-wrap">
                                @foreach($currentProduct['variation']['color'] as $key)
                                <div class="w-20 mb-5">
                                    <img src="{{ $url_phone.$key['hinhanh'] }}" alt="" >
                                    <div class='text-center mt-5 fz-14'>{{ $key['mausac'] }}</div>
                                </div>  
                                @endforeach
                            </div>
                        </div>
                    </td>

                    {{-- sản phẩm so sánh --}}
                    <td class='w-25'>
                        <div class='d-flex flex-column pb-20'>
                            {{-- tên --}}
                            <div class="d-flex align-items-center justify-content-between border p-10">
                                {{ $compareProduct['sanpham']['tensp'] }}
                                <div type='button' data-order="2" class="delete-compare-btn d-flex align-items-center gray-1"><i class="fal fa-times-circle fz-22"></i></div>
                            </div>
                            {{-- hình --}}
                            <img src="{{ $url_phone.$compareProduct['sanpham']['hinhanh'] }}" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="red">{{ number_format($compareProduct['sanpham']['gia'], 0, '', '.') }}<sup>đ</sup></b>
                                <span class='text-strike ml-10'>{{ number_format($compareProduct['sanpham']['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>
                            </div>
                            {{-- đánh giá --}}
                            <div>
                                @if ($compareProduct['sanpham']['danhgia']['qty'] != 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($compareProduct['sanpham']['danhgia']['star'] >= $i)
                                        <i class="fas fa-star checked"></i>
                                        @else
                                        <i class="fas fa-star uncheck"></i>
                                        @endif
                                    @endfor
                                    <span class='ml-10'>{{ $compareProduct['sanpham']['danhgia']['qty'] }} đánh giá</span>
                                @else
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                    <i class="fas fa-star uncheck"></i>
                                @endif
                            </div>
                            <hr>
                            <div class="d-flex flex-wrap">
                                @foreach($compareProduct['variation']['color'] as $key)
                                <div class="w-20">
                                    <img src="{{ $url_phone.$key['hinhanh'] }}" alt="" >
                                    <div class='text-center mt-5 fz-14'>{{ $key['mausac']}}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </td>

                    {{-- thêm điên thoại để so sánh --}}
                    <td class='w-25'>
                        @if (empty($thirdProduct))
                            <button type='button' data-order="3" class="compare-btn-add-phone mt-120">
                                <img src="images/add-phone.png" alt="" class='w-30 center-img'>
                                <div class='pt-20 fw-600'>Thêm điện thoại để so sánh</div>
                            </button>    
                        @else
                            <div class='d-flex flex-column pb-20'>
                                {{-- tên --}}
                                <div class="d-flex align-items-center justify-content-between border p-10">
                                    {{ $thirdProduct['sanpham']['tensp'] }}
                                    <div type='button' data-order="3" class="delete-compare-btn d-flex align-items-center gray-1"><i class="fal fa-times-circle fz-22"></i></div>
                                </div>
                                {{-- hình --}}
                                <img src="{{ $url_phone.$thirdProduct['sanpham']['hinhanh'] }}" alt="" class='w-80 center-img pt-20 pb-10'>
                                {{-- giá & đánh giá --}}
                                <div class='pt-10 pb-10'>
                                    <b class="red">{{ number_format($thirdProduct['sanpham']['gia'], 0, '', '.') }}<sup>đ</sup></b>
                                    <span class='text-strike ml-10'>{{ number_format($thirdProduct['sanpham']['giakhuyenmai'], 0, '', '.') }}<sup>đ</sup></span>
                                </div>
                                {{-- đánh giá --}}
                                <div>
                                    @if ($thirdProduct['sanpham']['danhgia']['qty'] != 0)
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if($thirdProduct['sanpham']['danhgia']['star'] >= $i)
                                            <i class="fas fa-star checked"></i>
                                            @else
                                            <i class="fas fa-star uncheck"></i>
                                            @endif
                                        @endfor
                                        <span class='ml-10'>{{ $thirdProduct['sanpham']['danhgia']['qty'] }} đánh giá</span>
                                    @else
                                        <i class="fas fa-star uncheck"></i>
                                        <i class="fas fa-star uncheck"></i>
                                        <i class="fas fa-star uncheck"></i>
                                        <i class="fas fa-star uncheck"></i>
                                        <i class="fas fa-star uncheck"></i>
                                    @endif
                                </div>
                                <hr>
                                <div class="d-flex flex-wrap">
                                    @foreach($thirdProduct['variation']['color'] as $key)
                                    <div class="w-20">
                                        <img src="{{ $url_phone.$key['hinhanh'] }}" alt="" >
                                        <div class='text-center mt-5 fz-14'>{{ $key['mausac']}}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>

                {{-- so sánh thông số kỹ thuật --}}
                <?php 
                    $current = $currentProduct['cauhinh'];
                    $compare = $compareProduct['cauhinh'];
                    $third = empty($thirdProduct) ? [] : $thirdProduct['cauhinh'];
                ?>
                <tr>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>cấu hính sản phẩm</div>
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Màn hình</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] . ', ' .
                            $current['thong_so_ky_thuat']['man_hinh']['ty_le_mh'].'"'
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] . ', ' .
                            $compare['thong_so_ky_thuat']['man_hinh']['ty_le_mh'].'"'
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] . ', ' . $third['thong_so_ky_thuat']['man_hinh']['ty_le_mh'].'"' : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Hệ điều hành</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['HDH']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['HDH']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['HDH'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Camera sau</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['camera_sau']['do_phan_giai'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Camera trước</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['camera_truoc']['do_phan_giai'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>CPU</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['CPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['CPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['CPU'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>RAM</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['RAM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['RAM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['RAM'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Bộ nhớ trong</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['bo_nho_trong'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>SIM</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['ket_noi']['SIM'] . ', ' . $current['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['ket_noi']['SIM'] . ', ' . $compare['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['ket_noi']['SIM'] . ', ' . $compare['thong_so_ky_thuat']['ket_noi']['mang_mobile'] : ''
                        }}
                    </td>
                </tr>
                <tr>
                    <td class='border fw-600'>Pin, sạc</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['pin']['loai'] . ', ' . $current['thong_so_ky_thuat']['pin']['dung_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['pin']['loai'] . ', ' . $compare['thong_so_ky_thuat']['pin']['dung_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['pin']['loai'] . ', ' . $compare['thong_so_ky_thuat']['pin']['dung_luong'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-btn-see-detail border'>
                    <td></td>
                    <td colspan="{{empty($third) ? '2' : '3'}}">
                        <div class='compare-btn-see-detail main-btn-2 p-10'>Xem so sánh cấu hình chi tiết<i class="fas fa-caret-down ml-10"></i></div>
                    </td>
                    @if(empty($third))
                    <td></td>
                    @endif
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>thiết kế & trọng lượng</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Thiết kế</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['thiet_ke_trong_luong']['thiet_ke']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['thiet_ke_trong_luong']['thiet_ke']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['thiet_ke_trong_luong']['thiet_ke'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Chất liệu</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['thiet_ke_trong_luong']['chat_lieu']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['thiet_ke_trong_luong']['chat_lieu']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['thiet_ke_trong_luong']['chat_lieu'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Kích thước</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['thiet_ke_trong_luong']['kich_thuoc']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['thiet_ke_trong_luong']['kich_thuoc']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['thiet_ke_trong_luong']['kich_thuoc'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Khối lượng</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['thiet_ke_trong_luong']['khoi_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['thiet_ke_trong_luong']['khoi_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['thiet_ke_trong_luong']['khoi_luong'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>màn hình</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Công nghệ màn hình</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['man_hinh']['cong_nghe_mh'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Độ phân giải</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['man_hinh']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['man_hinh']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['man_hinh']['do_phan_giai'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Kích thước màn hình</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['man_hinh']['ty_le_mh']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['man_hinh']['ty_le_mh']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['man_hinh']['ty_le_mh'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Mặt kính cảm ứng</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['man_hinh']['kinh_cam_ung']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['man_hinh']['kinh_cam_ung']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['man_hinh']['kinh_cam_ung'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>Camrera sau</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Độ phân giải</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['camera_sau']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['camera_sau']['do_phan_giai'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Quay phim</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['camera_sau']['quay_phim'] as $key)
                            <div class="mb-5">{{ $key['chat_luong'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['camera_sau']['quay_phim'] as $key)
                            <div class="mb-5">{{ $key['chat_luong'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['camera_sau']['quay_phim'] as $key)
                                <div class="mb-5">{{ $key['chat_luong'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Đèn Flash</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['camera_sau']['den_flash']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['camera_sau']['den_flash']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['camera_sau']['den_flash'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Tính năng</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['camera_sau']['tinh_nang'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['camera_sau']['tinh_nang'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['camera_sau']['tinh_nang'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>Camrera trước</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Độ phân giải</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['camera_truoc']['do_phan_giai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['camera_truoc']['do_phan_giai'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Tính năng</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['camera_truoc']['tinh_nang'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['camera_truoc']['tinh_nang'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['camera_truoc']['tinh_nang'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>hệ điều hành & cpu</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Hệ điều hành</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['HDH']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['HDH']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['HDH'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Chip xử lý (CPU)</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['CPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['CPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['CPU'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Tốc độ CPU</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['CPU_speed']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['CPU_speed']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['CPU_speed'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Chip đồ họa (GPU)</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['HDH_CPU']['GPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['HDH_CPU']['GPU']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['HDH_CPU']['GPU'] : ''
                        }}
                    </td>
                </tr>   
                <tr class='compare-detail'>
                    <td colspan="4" class=' border p-0'>
                        <div class='detail-specifications-title-2'>bộ nhớ & lưu trữ</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>RAM</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['RAM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['RAM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['RAM'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Bộ nhớ trong</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['bo_nho_trong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['bo_nho_trong'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Bộ nhớ còn lại (Khả dụng)</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['bo_nho_con_lai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['bo_nho_con_lai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['bo_nho_con_lai'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Thẻ nhớ</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['luu_tru']['the_nho']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['luu_tru']['the_nho']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['luu_tru']['the_nho'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>kết nối</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Mạng di động</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['ket_noi']['mang_mobile']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['ket_noi']['mang_mobile'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>SIM</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['ket_noi']['SIM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['ket_noi']['SIM']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['ket_noi']['SIM'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Wifi</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['ket_noi']['wifi'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['ket_noi']['wifi'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['ket_noi']['wifi'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>GPS</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['ket_noi']['GPS'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['ket_noi']['GPS'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['ket_noi']['GPS'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif  
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Bluetooth</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['ket_noi']['bluetooth'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['ket_noi']['bluetooth'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['ket_noi']['bluetooth'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Cổng kết nối/sạc</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['ket_noi']['cong_sac']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['ket_noi']['cong_sac']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['ket_noi']['cong_sac'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Jack tai nghe</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['ket_noi']['jack_tai_nghe']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['ket_noi']['jack_tai_nghe']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['ket_noi']['jack_tai_nghe'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Kết nối khác</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['ket_noi']['ket_noi_khac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['ket_noi']['ket_noi_khac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['ket_noi']['ket_noi_khac'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>pin & sạc</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Loại pin</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['pin']['loai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['pin']['loai']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['pin']['loai'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Dung lượng pin</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['pin']['dung_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['pin']['dung_luong']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['pin']['dung_luong'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Công nghệ pin</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['pin']['cong_nghe'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['pin']['cong_nghe'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['pin']['cong_nghe'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>tiện ích</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Bảo mật nâng cao</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['tien_ich']['bao_mat'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['tien_ich']['bao_mat'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['tien_ich']['bao_mat'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Tính năng đặc biệt</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['tien_ich']['tinh_nang_khac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['tien_ich']['tinh_nang_khac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['tien_ich']['tinh_nang_khac'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Ghi âm</td>
                    <td class='border'>
                        {{
                            $current['thong_so_ky_thuat']['tien_ich']['ghi_am']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_so_ky_thuat']['tien_ich']['ghi_am']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_so_ky_thuat']['tien_ich']['ghi_am'] : ''
                        }}
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Xem phim</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['tien_ich']['xem_phim'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['tien_ich']['xem_phim'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['tien_ich']['xem_phim'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Nghe nhạc</td>
                    <td class='border'>
                        @foreach ($current['thong_so_ky_thuat']['tien_ich']['nghe_nhac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @foreach ($compare['thong_so_ky_thuat']['tien_ich']['nghe_nhac'] as $key)
                            <div class="mb-5">{{ $key['name'] }}</div>
                        @endforeach
                    </td>
                    <td class='border'>
                        @if (!empty($third))
                            @foreach ($third['thong_so_ky_thuat']['tien_ich']['nghe_nhac'] as $key)
                                <div class="mb-5">{{ $key['name'] }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='border p-0'>
                        <div class='detail-specifications-title-2'>thông tin khác</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border fw-600'>Thời điểm ra mắt</td>
                    <td class='border'>
                        {{
                            $current['thong_tin_khac']['thoi_diem_ra_mat']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            $compare['thong_tin_khac']['thoi_diem_ra_mat']
                        }}
                    </td>
                    <td class='border'>
                        {{
                            !empty($third) ? $third['thong_tin_khac']['thoi_diem_ra_mat'] : ''
                        }}
                    </td>
                </tr>
                <tr class='border'>
                    <td></td>
                    <td>
                        <div data-id="{{$currentProduct['sanpham']['id']}}"class="buy-now main-btn w-100 p-10">Mua ngay</div>
                    </td>
                    <td>
                        <div data-id="{{$compareProduct['sanpham']['id']}}"class="buy-now main-btn w-100 p-10">Mua ngay</div>
                    </td>
                    @if (!empty($third))
                        <td>
                            <div data-id="{{$thirdProduct['sanpham']['id']}}"class="buy-now main-btn w-100 p-10">Mua ngay</div>
                        </td>    
                    @else
                        <td></td>
                    @endif
                    
                </tr>
            </tbody>
        </table>
    </section>
</div>

{{-- modal thêm sản phẩm so sánh --}}
<div class="modal fade" id="compare-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class='fz-22 fw-600'>Chọn điện thoại để so sánh</div>
                <div type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></div>
            </div>
            <div class="modal-body p-20">
                <div class='pt-10 pb-10'>
                    <input type="text" id='compare-search-phone' placeholder="Nhập tên điện thoại muốn so sánh">
                    <div class='compare-list-search-phone mt-20 border'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('user.content.section.sec-logo')

@stop