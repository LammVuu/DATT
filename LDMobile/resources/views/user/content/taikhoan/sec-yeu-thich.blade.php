<div class='row'>
    <div class='col-md-3'>
        @section("acc-favorite-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <table class='table border'>
            <thead>
                <th class='center-td'>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class='ml-20 font-weight-600'>Danh sách yêu thích</h4>
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
                </th>
            </thead>
            <tbody>
                @if (count($data['lst_favorite']) != 0)
                    @foreach ($data['lst_favorite'] as $key)
                        <tr class="relative block-dp">
                            <td class="block-dp">
                                <div class='d-flex justify-content-between'>
                                    <div class="d-flex p-20">
                                        <img src="images/phone/iphone_12_black.jpg" alt="" width="150px">
                                        <div class='d-flex flex-column'>
                                            <a href="#" class="font-weight-600 black">iPhone 12 PRO MAX</a>
                                            <div class='d-flex mt-10'>
                                                <i class="fas fa-star checked"></i>
                                                <i class="fas fa-star checked"></i>
                                                <i class="fas fa-star checked"></i>
                                                <i class="fas fa-star checked"></i>
                                                <i class="fas fa-star uncheck"></i>
                                                <span class='fz-14 ml-10'>21 đánh giá</span>
                                            </div>
                                            <span class='mt-10'>Màu sắc: Đen</span>
                                            <span>Dung Lượng: 128GB</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-fill justify-content-end p-20">
                                        <div class='d-flex flex-column'>
                                            <b class='price-color'>21.000.000 VND</b>
                                            <span><span class='text-strike fz-14'>25.000.000 VND</span>
                                        </div>
                                    </div>
                                    {{-- nút xóa --}}
                                    <i class="fav-btn-delete far fa-times fz-26 gray-1 mr-5"></i>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>
                            <div class="p-70 text-center">Chưa có sản phẩm nào. <a href="{{route('user/dien-thoai')}}" class="ml-5">Xem sản phẩm</a></div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>