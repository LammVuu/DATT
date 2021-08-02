<div class='row'>
    <div class='col-md-3'>
        @section("acc-order-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        @if (count($data['lst_order']['order']) != 0)
            {{-- đơn hàng đang xử lý --}}
            <?php $processing = false ?>
            @foreach ($data['lst_order']['order'] as $key)
                @if ($key->trangthaidonhang != 'Thành công' && $key->trangthaidonhang != 'Đã hủy')
                    <?php $processing = true ?>
                    @break
                @endif
            @endforeach

            @if ($processing)
                <table class='table box-shadow mb-50'>
                    <thead>
                        <tr>
                            <th><div class="pt-10 pb-10">Mã đơn hàng</div></th>
                            <th><div class="pt-10 pb-10">Thời gian</div></th>
                            <th><div class="pt-10 pb-10">Sản phẩm</div></th>
                            <th><div class="pt-10 pb-10">Tổng tiền</div></th>
                            <th><div class="pt-10 pb-10">Trạng thái đơn hàng</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['lst_order']['order'] as $key)
                            @if ($key->trangthaidonhang != 'Thành công' && $key->trangthaidonhang != 'Đã hủy')
                            <tr>
                                {{-- mã đơn hàng --}}
                                <td class='vertical-center'>
                                    <div class='p-20'>
                                        <a href="{{route('user/tai-khoan-chi-tiet-don-hang', ['id' => $key['id']])}}">{{$key['id']}}</a>
                                    </div>
                                </td>
                                {{-- ngày mua --}}
                                <td class='vertical-center'>
                                    <div>{{explode(' ', $key['thoigian'])[0]}}</div>
                                </td>
                                {{-- sản phẩm mua --}}
                                <?php $detail = $data['lst_order']['detail'][$key['id']] ?>
                                <td class='w-40 vertical-center'>
                                    <div class='pt-15 pb-15'>
                                        <div class='d-flex'>
                                            <img src="{{$url_phone.$detail[0]['sanpham']['hinhanh']}}" alt="" width="90px">
                                            <div class="d-flex flex-column">
                                                <div class="fw-600">{{$detail[0]['sanpham']['tensp'].' - '.$detail[0]['sanpham']['mausac']}}</div>
                                                <div class="fz-14">Dung lượng: {{$detail[0]['sanpham']['dungluong']}}</div>
                                                <div class="fz-14">Số lượng: {{$detail[0]['sl']}}{{count($detail) > 1 ? ' ... và '.(count($detail) - 1).' sản phẩm khác' : ''}}</div>
                                                <a href="{{route('user/tai-khoan-chi-tiet-don-hang', ['id' => $key['id']])}}">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- giá  --}}
                                <td class='vertical-center'>
                                    <div>{{number_format($key['tongtien'], 0, '', '.')}}<sup>đ</sup></div>
                                </td>
                                {{-- trạng thái đơn hàng --}}
                                <td class='vertical-center'>
                                    <div>{{$key['trangthaidonhang']}}</div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- đơn hàng đã xử lý --}}
            <?php $done = false ?>
            @foreach ($data['lst_order']['order'] as $key)
                @if ($key->trangthaidonhang == 'Thành công' || $key->trangthaidonhang == 'Đã hủy')
                    <?php $done = true ?>
                    @break
                @endif
            @endforeach

            @if ($done)
                <table class='table box-shadow'>
                    <thead>
                        <tr>
                            <th><div class="pt-10 pb-10">Mã đơn hàng</div></th>
                            <th><div class="pt-10 pb-10">Thời gian</div></th>
                            <th><div class="pt-10 pb-10">Sản phẩm</div></th>
                            <th><div class="pt-10 pb-10">Tổng tiền</div></th>
                            <th><div class="pt-10 pb-10">Trạng thái đơn hàng</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['lst_order']['order'] as $key)
                            @if ($key->trangthaidonhang == 'Thành công' || $key->trangthaidonhang == 'Đã hủy')
                                <tr>
                                    {{-- mã đơn hàng --}}
                                    <td class='vertical-center'>
                                        <div class='p-20'>
                                            <a href="{{route('user/tai-khoan-chi-tiet-don-hang', ['id' => $key['id']])}}">{{$key['id']}}</a>
                                        </div>
                                    </td>
                                    {{-- ngày mua --}}
                                    <td class='vertical-center'>
                                        <div>{{explode(' ', $key['thoigian'])[0]}}</div>
                                    </td>
                                    {{-- sản phẩm mua --}}
                                    <?php $detail = $data['lst_order']['detail'][$key['id']] ?>
                                    <td class='w-40 vertical-center'>
                                        <div class='pt-15 pb-15'>
                                            <div class='d-flex'>
                                                <img src="{{$url_phone.$detail[0]['sanpham']['hinhanh']}}" alt="" width="90px">
                                                <div class="d-flex flex-column">
                                                    <div class="fw-600">{{$detail[0]['sanpham']['tensp'].' - '.$detail[0]['sanpham']['mausac']}}</div>
                                                    <div class="fz-14">Dung lượng: {{$detail[0]['sanpham']['dungluong']}}</div>
                                                    <div class="fz-14">Số lượng: 2{{count($detail) > 1 ? ' ... và '.count($detail).' sản phẩm khác' : ''}}</div>
                                                    <a href="{{route('user/tai-khoan-chi-tiet-don-hang', ['id' => $key['id']])}}">Xem chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- giá  --}}
                                    <td class='vertical-center'>
                                        <div>{{number_format($key['tongtien'], 0, '', '.')}}<sup>đ</sup></div>
                                    </td>
                                    {{-- trạng thái đơn hàng --}}
                                    <td class='vertical-center'>
                                        <div>{{$key['trangthaidonhang']}}</div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        @else
            <div class="p-70 box-shadow text-center">
                Bạn chưa có đơn hàng nào. <a href="{{route('user/dien-thoai')}}" class="ml-5">Mua hàng</a>
            </div>
        @endif
    </div>
</div>

{{-- modal xem thêm đơn hàng --}}
<div class="modal fade" id="view-more-order" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div type='button' class="btn-close" data-bs-dismiss='modal'></div>
                <div class="d-flex flex-column">
                    <div class="fz-20 mb-5">Đơn hàng <b>123</b></div>
                    <div class="fz-14">Ngày mua: 12/12/2021</div>
                </div>
            </div>
            <div class="modal-body p-0">
                {{-- danh sách điện thoại --}}
                <div class="list-phone-order">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Điện thoại</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td class="vertical-center text-center"><?php echo $i ?></td>
                                <td>{{-- điện thoại --}}
                                    <div class="p-10">
                                        <div class="d-flex">
                                            <img src="images/phone/iphone_12_black.jpg" alt="" width="100px">
                                            <div class="ml-10">
                                                <a href="#" class="fw-600 mb-5 black">iPhone 12 PRO MAX</a>
                                                <div class="fz-14">Dung lượng: 128GB</div>
                                                <div class="fz-14">Màu sắc: Đen</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="vertical-center text-center fw-600">x1</td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>