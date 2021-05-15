<div class='row'>
    <div class='col-md-3'>
        @section("acc-voucher-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <div class="font-weight-600 fz-22 p-10 box-shadow">Mã giảm giá của tôi</div>
        <?php for($i = 0; $i < 3; $i++) : ?>
        <div class='row mt-50'>
            {{-- mã giảm giá --}}
            <div class='col-md-7 col-sm-12'>
                <div class='account-voucher'>
                    {{-- số phần trăm giảm --}}
                    <div class='voucher-left w-20 p-70'>
                        <div class='voucher-left-content fz-40'>-10%</div>
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
                                                    <td class='account-td-40'>Mã</td>
                                                    <td><b>ABCDEF</b></td>
                                                </tr>
                                                <tr>
                                                    <td class='account-td-40'>Hạn sử dụng</td>
                                                    <td>31/12/2021</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class='account-td-40'>
                                                        <div class='d-flex flex-column'>
                                                            <span>Điều kiện:</span>
                                                            <ul class='mt-10'>
                                                                <li>Áp dụng cho đơn hàng từ 5.000.000 VND</li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- nội dung --}}
                            <div class="flex-fill">
                                <span>Áp dụng cho đơn hàng từ 5.000.000 VND</span>
                            </div>
                            {{-- hạn sử dụng --}}
                            <div>
                                <span>HSD: 31/12/2021</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endfor ?>
    </div>
</div>