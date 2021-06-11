@extends("user.layout")
@section("content")

<div class='container'>
    <div class='pt-30 pb-30 fz-24 text-center'>
        So sánh điện thoại <b>{{ $currentProduct['sanpham'][0]['tensp']}}</b> và <b>{{ $compareProduct['sanpham'][0]['tensp']}}</b>
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
                            <div class="border p-10">{{ $currentProduct['sanpham'][0]['tensp']}}</div>
                            {{-- hình --}}
                            <img src="{{ $url_phone.$currentProduct['sanpham'][0]['hinhanh'] }}" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="price-color">{{ number_format($currentProduct['sanpham'][0]['gia']) }}<sup>đ</sup></b>
                                <span class='text-strike ml-10'>{{ $currentProduct['sanpham'][0]['giakhuyenmai']}}<sup>đ</sup></span>
                            </div>
                            <div>
                                @if ($currentProduct['sanpham'][0]['danhgia']['qty'] != 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($currentProduct['sanpham'][0]['danhgia']['star'] > $i)
                                        <i class="fas fa-star checked"></i>
                                        @else
                                        <i class="fas fa-star uncheck"></i>
                                        @endif
                                    @endfor
                                    <span class='ml-10'>{{ $currentProduct['sanpham'][0]['danhgia']['qty'] }} đánh giá</span>
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
                            <div class="d-flex align-items-center justify-content-between border p-10">{{ $compareProduct['sanpham'][0]['tensp'] }}
                                <div type='button' class="d-flex align-items-center gray-1"><i class="fal fa-times-circle fz-22"></i></div>
                            </div>
                            {{-- hình --}}
                            <img src="{{ $url_phone.$compareProduct['sanpham'][0]['hinhanh'] }}" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="price-color">{{ number_format($compareProduct['sanpham'][0]['gia']) }}<sup>đ</sup></b>
                                <span class='text-strike ml-10'>{{ number_format($compareProduct['sanpham'][0]['giakhuyenmai']) }}<sup>đ</sup></span>
                            </div>
                            {{-- đánh giá --}}
                            <div>
                                @if ($compareProduct['sanpham'][0]['danhgia']['qty'] != 0)
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($currentProduct['sanpham'][0]['danhgia']['star'] > $i)
                                        <i class="fas fa-star checked"></i>
                                        @else
                                        <i class="fas fa-star uncheck"></i>
                                        @endif
                                    @endfor
                                    <span class='ml-10'>{{ $currentProduct['sanpham'][0]['danhgia']['qty'] }} đánh giá</span>
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
                    <td class='w-25 vertical-center'>
                        <button type='button' data-bs-toggle="modal" data-bs-target="#compare-phone" class="compare-btn-add-phone mb-120">
                            <img src="images/add-phone.png" alt="" class='w-30 center-img'>
                            <div class='pt-20 font-weight-600'>Thêm điện thoại để so sánh</div>
                        </button>
                    </td>
                </tr>
                <tr>

                </tr>

                {{-- so sánh thông số kỹ thuật --}}
                <?php 
                    $current = $currentProduct['cauhinh'];
                    $compare = $compareProduct['cauhinh'];
                ?>
                <tr>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>cấu hính sản phẩm</div>
                    </td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Màn hình</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Hệ điều hành</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Camera sau</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Camera trước</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>CPU</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>RAM</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Bộ nhớ trong</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>SIM</td>
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
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Pin, sạc</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-btn-see-detail border'>
                    <td></td>
                    <td colspan="2">
                        <div class='main-btn-2 p-10'>Xem so sánh cấu hình chi tiết<i class="fas fa-caret-down ml-10"></i></div>
                    </td>
                    <td></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>thiết kế & trọng lượng</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Thiết kế</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chất liệu</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kích thước</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Khối lượng</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>màn hình</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Công nghệ màn hình</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kích thước màn hình</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Mặt kính cảm ứng</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>Camrera sau</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Quay phim</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Đèn Flash</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>Camrera trước</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>hệ điều hành & cpu</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Hệ điều hành</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chip xử lý (CPU)</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tốc độ CPU</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chip đồ họa (GPU)</td>
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
                    <td class='border'></td>
                </tr>   
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>bộ nhớ & lưu trữ</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>RAM</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bộ nhớ trong</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bộ nhớ còn lại (Khả dụng)</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Thẻ nhớ</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>kết nối</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Mạng di động</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>SIM</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Wifi</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>GPS</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bluetooth</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Cổng kết nối/sạc</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Jack tai nghe</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kết nối khác</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>pin & sạc</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Loại pin</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Dung lượng pin</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Công nghệ pin</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>tiện ích</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bảo mật nâng cao</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng đặc biệt</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Ghi âm</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Xem phim</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Nghe nhạc</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>thông tin khác</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Thời điểm ra mắt</td>
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
                    <td class='border'></td>
                </tr>
                <tr class='border'>
                    <td></td>
                    <td>
                        <a href="#" class="main-btn w-100 p-10">Mua ngay</a>
                    </td>
                    <td>
                        <a href="#" class="main-btn w-100 p-10">Mua ngay</a>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>
</div>

{{-- modal thêm sản phẩm so sánh --}}
<div class="modal fade" id="compare-phone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='relative'>
                <div class='text-center font-weight-600 p-20'>Chọn điện thoại để thêm vào so sánh</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class='pt-10 pb-10'>
                    <div class='col-md-10 mx-auto'>
                        <input type="text" id='compare-search-phone' class='form-control' placeholder="Nhập tên điện thoại muốn so sánh">
                        <div class='compare-list-search-phone mt-20 border'>
                            @for ($i = 0; $i < 50; $i++)
                            <div class='compare-single-phone'>iPhone</div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('user.content.section.sec-logo')

@stop