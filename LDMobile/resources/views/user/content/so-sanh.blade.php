@extends("user.layout")
@section("content")

<div class='container'>
    <div class='pt-30 pb-30 fz-24 text-center'>
        So sánh điện thoại <b>iPhone 12</b> và <b>Samsung</b>
    </div>
    
    <section class='pb-100'>
        <table class="table">
            <tbody>
                <tr>
                    <td class='w-25'></td>
                    <td class='w-25'>
                        <div class='d-flex flex-column pb-20'>
                            {{-- tên --}}
                            <div class="border p-10">iPhone 12</div>
                            {{-- hình --}}
                            <img src="images/iphone/iphone_12_red.jpg" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="price-color">25.000.000<sup>đ</sup></b>
                                <span class='text-strike ml-10'>29.000.000<sup>đ</sup></span>
                            </div>
                            <div>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star uncheck"></i>
                                <span class='ml-10'>21 đánh giá</span>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="w-20">
                                    <img src="images/iphone/iphone_12_black.jpg" alt="" >
                                    <div class='text-center mt-5 fz-14'>Đen</div>
                                </div>
                                <div class="w-20">
                                    <img src="images/iphone/iphone_12_blue.jpg" alt="" >
                                    <div class='text-center mt-5 fz-14'>Xanh lam</div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class='w-25'>
                        <div class='d-flex flex-column pb-20'>
                            {{-- tên --}}
                            <div class="d-flex align-items-center justify-content-between border p-10">Samsung S21 Plus 5G 256GB
                                <a href="#" class="d-flex align-items-center gray-1"><i class="fal fa-times-circle fz-22"></i></a>
                            </div>
                            {{-- hình --}}
                            <img src="images/iphone/iphone_12_red.jpg" alt="" class='w-80 center-img pt-20 pb-10'>
                            {{-- giá & đánh giá --}}
                            <div class='pt-10 pb-10'>
                                <b class="price-color">25.000.000<sup>đ</sup></b>
                                <span class='text-strike ml-10'>29.000.000<sup>đ</sup></span>
                            </div>
                            <div>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star checked"></i>
                                <i class="fas fa-star uncheck"></i>
                                <span class='ml-10'>21 đánh giá</span>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <div class="w-20">
                                    <img src="images/iphone/iphone_12_white.jpg" alt="" >
                                    <div class='text-center mt-5 fz-14'>Trắng</div>
                                </div>
                                <div class="w-20">
                                    <img src="images/iphone/iphone_12_green.jpg" alt="" >
                                    <div class='text-center mt-5 fz-14'>Xanh lá</div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class='w-25 center-td'>
                        <button type='button' data-bs-toggle="modal" data-bs-target="#compare-phone" class="compare-btn-add-phone mb-120">
                            <img src="images/add-phone.png" alt="" class='w-30 center-img'>
                            <div class='pt-20 font-weight-600'>Thêm điện thoại để so sánh</div>
                        </button>
                    </td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>cấu hính sản phẩm</div>
                    </td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Màn hình</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Hệ điều hành</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Camera sau</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Camera trước</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Chip</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>RAM</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Bộ nhớ trong</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>SIM</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr>
                    <td class='border font-weight-600'>Pin, sạc</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
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
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chất liệu</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kích thước</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Khối lượng</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>màn hình</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Công nghệ màn hình</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kích thước màn hình</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Mặt kính cảm ứng</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>Camrera sau</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Quay phim</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Đèn Flash</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>Camrera trước</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Độ phân giải</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>hệ điều hành & cpu</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Hệ điều hành</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chip xử lý (CPU)</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tốc độ CPU</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Chip đồ họa (GPU)</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>   
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>bộ nhớ & lưu trữ</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>RAM</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bộ nhớ trong</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bộ nhớ còn lại (Khả dụng)</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Thẻ nhớ</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>kết nối</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Mạng di động</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>SIM</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Wifi</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>GPS</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bluetooth</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Cổng kết nối/sạc</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Jack tai nghe</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Kết nối khác</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>pin & sạc</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Loại pin</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Dung lượng pin</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Công nghệ pin</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>tiện ích</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Bảo mật nâng cao</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Tính năng đặc biệt</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Ghi âm</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Radio</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Xem phim</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Nghe nhạc</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='compare-detail'>
                    <td colspan="4" class='p-0'>
                        <div class='detail-specifications-title-2'>thông tin khác</div>
                    </td>
                </tr>
                <tr class='compare-detail'>
                    <td class='border font-weight-600'>Thời điểm ra mắt</td>
                    <td class='border'>ABC</td>
                    <td class='border'>ABC</td>
                    <td class='border'></td>
                </tr>
                <tr class='border'>
                    <td></td>
                    <td>
                        <div class='p-10'>
                            <a href="#" class="main-btn w-100 p-10">Mua ngay</a>
                        </div>
                    </td>
                    <td>
                        <div class='p-10'>
                            <a href="#" class="main-btn w-100 p-10">Mua ngay</a>
                        </div>
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

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop