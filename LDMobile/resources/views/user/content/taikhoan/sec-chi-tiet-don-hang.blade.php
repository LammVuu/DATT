@section("breadcrumb")
    <a href="{{route('user/thanh-toan')}}" class="bc-item active">Thanh toán</a>
@stop
<div class='row'>
    <div class='col-md-3'>
        @section("acc-order-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <div class='d-flex justify-content-between align-items-center box-shadow p-10 fz-22'>
            <div class="d-flex">
                <div>Chi tiết đơn hàng</div>
                <div class='black fw-600 ml-10'>#{{$order['order']->id}}</div>    
            </div>
            @if ($order['order']->trangthaidonhang != 'Đã hủy')
                <div class='account-deliver-success'>{{$order['order']->trangthaidonhang}}</div>
            @else
                <div class='account-deliver-fail'>Đã hủy</div>
            @endif
            
            
        </div>   
        <div class="d-flex justify-content-end mt-5">
            <span>Ngày mua: <b>{{$order['order']->thoigian}}</b></span>
        </div>

        {{-- hủy đơn hàng --}}
        @if ($order['order']->trangthaidonhang == 'Đã tiếp nhận')
            <div class="d-flex justify-content-center mt-30 mb-30">
                <div id="cancel-order-btn" data-id="{{$order['order']->id}}" class="checkout-btn p-10 w-50">hủy đơn hàng</div>
            </div>
        @endif

        <div class='mt-50'>
            <div class='row'>
                @if ($order['order']->hinhthuc == 'Giao hàng tận nơi')
                    <div class='col-lg-6'>
                        <div class='fw-600 pb-10'>Địa chỉ người nhận</div>
                        <div id="DCGH-div">
                            <div class='box-shadow p-20'>
                                <div class="d-flex flex-column fz-14">
                                    <b class='text-uppercase pb-5'>{{$order['order']->diachigiaohang->hoten}}</b>
                                    <div class="d-flex mb-5">
                                        <div class="gray-1 mr-5">Địa chỉ:</div>
                                        <div class="black">
                                            {{$order['order']->diachigiaohang->diachi.', '.$order['order']->diachigiaohang->phuongxa.', '.$order['order']->diachigiaohang->quanhuyen.', '.$order['order']->diachigiaohang->tinhthanh}}
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="gray-1 mr-5">SĐT:</div>
                                        <div class="black">{{$order['order']->diachigiaohang->sdt}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- hình thức thanh toán --}}
                    <div class='col-lg-6'>
                        <div class='fw-600 pb-10'>Phương thức thanh toán</div>
                        <div id="HTGH-div">
                            <div class='box-shadow p-20 h-100'>
                                <div class="black">{{$order['order']->pttt}}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class='col-lg-6'>
                        <div class='fw-600 pb-10'>Nhận tại cửa hàng</div>
                        <div class='box-shadow p-20'>
                            <div class="d-flex flex-column fz-14">
                                <div class="d-flex mb-5">
                                    <div class="gray-1 mr-5">Địa chỉ:</div>
                                    <div class="black">
                                        {{$order['order']->chinhanh->diachi}}
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="gray-1 mr-5">SĐT:</div>
                                    <div class="black">{{$order['order']->chinhanh->sdt}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
            </div>
        </div>

        {{-- danh sách sản phẩm --}}
        <div class='mt-50 box-shadow'>
            <table class='table'>
                <thead>
                    <tr>
                        <th><div class='pt-10 pb-10'>Sản phẩm</div></th>
                        <th><div class='pt-10 pb-10'>Giá</div></th>
                        <th><div class='pt-10 pb-10'>Số lượng</div></th>
                        <th><div class='pt-10 pb-10'>Giảm giá</div></th>
                        <th><div class='pt-10 pb-10'>Tạm tính</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $provisional = 0 ?>
                    @foreach ($order['detail'] as $key)
                        <tr>
                            {{-- sản phẩm --}}
                            <td class='w-40 vertical-center'>
                                <div class='d-flex flex-row pt-10 pb-10'>
                                    <img src="{{$url_phone.$key['sanpham']['hinhanh']}}" alt="" width="90px">
                                    <div class='d-flex flex-column fz-14'>
                                        <a href='{{route('user/chi-tiet', ['name' => $key['sanpham']['tensp_url']])}}' class="black fw-600">{{$key['sanpham']['tensp']}}</a>
                                        <span>Dung Lượng: {{$key['sanpham']['dungluong']}}</span>
                                        <span>Màu: {{$key['sanpham']['mausac']}}</span>
                                    </div>
                                </div>
                            </td>
                            {{-- giá --}}
                            <td class="vertical-center">{{number_format($key['sanpham']['gia'], 0, '', '.')}}<sup>đ</sup></td>
                            {{-- số lượng --}}
                            <td class="vertical-center">{{$key['sl']}}</td>
                            {{-- giảm giá --}}
                            <td class="vertical-center">
                                {{$key['sanpham']['khuyenmai'] != 0 ? '-'.$key['sanpham']['khuyenmai']*100 .'%' : '0'}}
                            </td>
                            {{-- tạm tính --}}
                            <td class="vertical-center">{{number_format($key['sanpham']['giakhuyenmai']*$key['sl'], 0, '', '.')}}<sup>đ</sup></td>
                            <?php $provisional += $key['sanpham']['giakhuyenmai']*$key['sl'] ?>
                        </tr>
                    @endforeach
                    {{-- mã giảm giá --}}
                    @if ($order['order']->id_vc)
                        <?php $voucher = $order['order']->voucher ?>
                        <tr>
                            <td colspan="5" class="p-0">
                                <div class='d-flex'>
                                    <div class="w-20 bg-gray-4 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-ticket-alt mr-10"></i>Mã giảm giá
                                    </div>
                                    
                                    <div class="w-30 p-10">
                                        <div class='account-voucher'>
                                            {{-- số phần trăm giảm --}}
                                            <div class='voucher-left-small w-20 p-30'>
                                                <div class='voucher-left-small-content fz-18'>-{{$voucher->chietkhau*100}}%</div>
                                            </div>
                                            {{-- nội dung --}}
                                            <div class='voucher-right-small w-80 d-flex align-items-center justify-content-between p-10'>
                                                {{-- icon xem chi tiết --}}
                                                <b>{{$voucher->code}}</b>
                                                <div class="relative promotion-info-icon">
                                                    <i class="fal fa-info-circle main-color-text fz-20"></i>
                                                    <div class='voucher-content box-shadow p-20 '>
                                                        <table class='table'>
                                                            <tbody>
                                                                <tr>
                                                                    <td class='w-40'>Mã</td>
                                                                    <td><b>{{$voucher->code}}</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='w-40'>Nội dung</td>
                                                                    <td>{{$voucher->noidung}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" class='w-40'>
                                                                        <div class='d-flex flex-column'>
                                                                            <span>Điều kiện:</span>
                                                                            @if ($voucher->dieukien != 0)
                                                                                <ul class='mt-10'>
                                                                                    <li>Áp dụng cho đơn hàng từ {{number_format($voucher->dieukien, 0, '', '.')}}<sup>đ</sup></li>
                                                                                </ul>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class='w-40'>Hạn sử dụng</td>
                                                                    <td>{{$voucher->ngayketthuc}}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>    
                    @endif
                    
                    {{-- tính tiền --}}
                    <tr>
                        <td class="vertical-center">
                            <div class='p-20'>
                                <div class='d-flex justify-content-between pb-10'>
                                    <div class="gray-1">Tạm tính:</div>
                                    <div>{{number_format($provisional, 0, '', '.')}}<sup>đ</sup></div>
                                </div>
                                @if ($order['order']->id_vc)
                                    <div class='d-flex justify-content-between pb-10'>
                                        <div class="gray-1">Mã giảm giá:</div>
                                        <div class="main-color-text">-{{$order['order']->voucher->chietkhau*100}}%</div>
                                    </div>    
                                @endif
                                
                                <div class='d-flex justify-content-between'>
                                    <div class="gray-1">Tổng tiền:</div>
                                    <div class="price-color fz-20 fw-600">{{number_format($order['order']->tongtien, 0, '', '.')}}<sup>đ</sup></div>
                                </div>
                            </div>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                    {{-- quay về --}}
                    <tr>
                        <td colspan="5">
                            <div class='p-10'>
                                <a href="{{route('user/tai-khoan-don-hang')}}"><i class="fas fa-chevron-left mr-10"></i>Quay về</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include("user.content.modal.xoa-modal")