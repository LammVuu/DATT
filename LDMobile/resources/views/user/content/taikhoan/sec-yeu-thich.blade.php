<div class='row'>
    <div class='col-md-3'>
        @section("acc-favorite-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        {{-- favorite header --}}
        <div class="p-10 box-shadow mb-20">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-600 fz-24">Danh sách yêu thích</div>
                {{-- nút 3 chấm --}}
                <div class='d-flex justify-content-end fz-24'>
                    <div class='account-btn-option' aria-expanded="false">
                        <i class="far fa-ellipsis-v"></i>
                        <div class='account-option-div border font-weight-300 fz-16'>
                            <div class='d-flex flex-column text-center'>
                                <div id='fav-btn-delete-all' class='pointer-cs black p-10'>Bỏ thích tất cả</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- danh sách yêu thích --}}
        <div id="lst_favorite">
            @if (count($data['lst_favorite']) != 0)
                @foreach ($data['lst_favorite'] as $key)
                    <div id="favorite-{{$key['id']}}" class="single-favorite box-shadow mb-30">
                        <div class="d-flex justify-content-between">
                            {{-- điện thoại --}}
                            <div class="d-flex p-20">
                                <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" width="150px">
                                <div class='d-flex flex-column'>
                                    <a href="{{route('user/chi-tiet', ['name' => $key['sanpham']['tensp_url']])}}" class="fw-600 black">{{$key['sanpham']['tensp']}}</a>
                                    <div class='d-flex mt-10'>
                                        @if ($key['sanpham']['danhgia']['qty'] != 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($key['sanpham']['danhgia']['star'] >= $i)
                                                <i class="fas fa-star checked"></i>
                                                @else
                                                <i class="fas fa-star uncheck"></i>
                                                @endif
                                            @endfor
                                            <span class='fz-14 ml-10'>{{ $key['sanpham']['danhgia']['qty'] . ' đánh giá'}}</span>
                                        @endif
                                    </div>
                                    <span class='mt-10'>Màu sắc: {{$key['sanpham']['mausac']}}</span>
                                    <span>Dung Lượng: {{$key['sanpham']['dungluong']}}</span>
                                </div>
                            </div>

                            {{-- giá & nút xóa --}}
                            <div class="d-flex">
                                <div class='d-flex flex-column p-20'>
                                    <b class='price-color fz-20'>{{number_format($key['sanpham']['giakhuyenmai'], 0, '', '.')}}<sup>đ</sup></b>
                                    @if ($key['sanpham']['khuyenmai'] != 0)
                                        <span class='text-strike fz-14'>{{number_format($key['sanpham']['gia'], 0, '', '.')}}<sup>đ</sup></span>    
                                    @endif
                                </div>
                                {{-- nút xóa --}}
                                <div type="button" data-id="{{$key['id']}}" class="fav-btn-delete d-flex align-items-center h-100 p-10"><i class="fal fa-trash-alt fz-24"></i></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-70 box-shadow d-flex justify-content-center">Bạn chưa có sản phẩm nào. <a href="dienthoai" class="ml-5">Xem sản phẩm</a></div>
            @endif
        </div>
    </div>
</div>

<div id="toast"></div>