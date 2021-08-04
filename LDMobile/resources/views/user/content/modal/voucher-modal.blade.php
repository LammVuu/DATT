<div class="modal fade" id="modal-promotion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="fz-20 fw-600">Mã khuyến mãi của tôi</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="choose-voucher-div">
                    @if (count($data['lst_voucher']) != 0)
                        @foreach ($data['lst_voucher'] as $key)
                            {{-- chưa đủ điều kiện sử dụng voucher --}}
                            @if ($data['cart']['total'] < $key->dieukien)
                                <div class="col-lg-8 col-md-10 col-12 mx-auto pb-30">
                                    <div class='account-voucher'>
                                        {{-- số phần trăm giảm --}}
                                        <div class='dis-voucher-left w-20 p-70'>
                                            @if ($key->sl_voucher != 1)
                                                <div class="voucher-qty">{{$key->sl_voucher}}x</div>    
                                            @endif
                                            <div class='dis-voucher-left-content fz-40'>-{{$key->chietkhau*100}}%</div>
                                        </div>
                                        {{-- nội dung --}}
                                        <div class='dis-voucher-right w-80'>
                                            <div class="d-flex flex-column justify-content-between h-100 pt-10 pr-10 pb-10 pl-20">
                                                {{-- icon xem chi tiết --}}
                                                <div class="d-flex justify-content-end">
                                                    <div class="relative dis-promotion-info-icon">
                                                        <i class="fal fa-info-circle fz-20"></i>
                                                        <div class='voucher-content box-shadow p-20 '>
                                                            <table class='table'>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class='w-40'>Mã</td>
                                                                        <td><b>{{$key->code}}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="w-40">Nội dung</td>
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
                                                    {{$key->noidung}}
                                                </div>
                                                {{-- hạn sử dụng --}}
                                                <div class="d-flex justify-content-between">
                                                    <span class="d-flex align-items-end">HSD: {{$key->ngayketthuc}}</span>
                                                    <div class="dis-condition-tag">Chưa thỏa điều kiện</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{-- Đủ điều kiện --}}
                            @else
                                <div class="col-lg-8 col-md-10 col-12 mx-auto pb-30">
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
                                                        <i class="fal fa-info-circle fz-20"></i>
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
                                                <div class="flex-fill">{{$key->noidung}}</div>
                                                {{-- hạn sử dụng --}}
                                                <div class="d-flex justify-content-between">
                                                    <span class="d-flex align-items-end">HSD: {{$key->ngayketthuc}}</span>

                                                    {{-- áp dụng --}}
                                                    <div data-id="{{$key->id}}" class="use-voucher-btn main-btn p-10">Áp dụng</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center pt-50 pb-50 fw-600">
                            Bạn chưa có mã khuyến mãi nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>