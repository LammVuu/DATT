<div class='row'>
    <div class='col-md-3'>
        @section("acc-voucher-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        @if (count($data['lst_voucher']) != 0)
            <div class="fw-600 fz-22 p-10 box-shadow">Mã giảm giá của tôi</div>
            @foreach ($data['lst_voucher'] as $key)
                <div class='row mt-30'>
                    {{-- mã giảm giá --}}
                    <div class='col-md-7 col-sm-12'>
                        <div class='account-voucher'>
                            {{-- số phần trăm giảm --}}
                            <div class='voucher-left w-20 p-70'>
                                @if ($key->sl_voucher != 1)
                                <div class="voucher-qty">{{$key->sl_voucher}}x</div>
                                @endif
                                <div class='voucher-left-content fz-40'>-{{$key->chietkhau*100}}%</div>
                            </div>
                            {{-- nội dung --}}
                            <div class='voucher-right w-80'>
                                <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                    {{-- icon xem chi tiết --}}
                                    <div class="d-flex justify-content-end">
                                        <div class="relative promotion-info-icon">
                                            <i class="fal fa-info-circle main-color-text fz-20"></i>
                                            <div class='voucher-content box-shadow p-20 '>
                                                <table class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='w-40'>Mã</td>
                                                            <td><b>{{$key->code}}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='w-40'>Nội dung</td>
                                                            <td>{{$key->noidung}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class='w-40'>
                                                                <div class='d-flex flex-column'>
                                                                    <span>Điều kiện</span>
                                                                    @if ($key->dieukien != 0)
                                                                        <ul class='mt-10'>
                                                                            <li>Áp dụng cho đơn hàng từ {{number_format($key->dieukien, 0, '', '.')}}<sup>đ</sup></li>
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='w-40'>Hạn sử dụng</td>
                                                            <td>{{$key->ngayketthuc}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- nội dung --}}
                                    <div class="flex-fill">
                                        <span>{{$key->noidung}}</span>
                                    </div>
                                    {{-- hạn sử dụng --}}
                                    <div>
                                        <span>HSD: {{$key->ngayketthuc}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-70 text-center box-shadow">Bạn chưa có voucher nào. <a href="{{route('user/dien-thoai')}}" class="ml-5">Mua hàng</a></div>
        @endif
    </div>
</div>