<div class='row'>
    <div class='col-md-3'>
        @section("acc-address-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class="col-md-9">
        <div id="btn-new-address" class="btn-new-address mb-30">
            <i class="far fa-plus mr-10"></i>Thêm địa chỉ mới
        </div>

        @if (count($data['lst_address']) != 0)
            @foreach ($data['lst_address'] as $key)
                {{-- địa chỉ giao hàng --}}
                <div class="col-md-12 white-bg p-20 border mb-30">
                    {{-- họ tên --}}
                    <div class="d-flex justify-content-between pb-10">
                        <div class="d-flex">
                            <b class="text-uppercase">Vũ Hoàng Lâm</b>
                            <div class="d-flex align-items-center success-color-2 ml-15"><i class="far fa-check-circle mr-5"></i>Đang sử dụng</div>
                        </div>
                        <div class="d-flex">
                            <div id='btn-edit-address' class="pointer-cs main-color-text">Chỉnh sửa</div>
                            {{-- <div id='btn-remove-address' class="price-color ml-20">Xóa</div> --}}
                        </div>
                    </div>

                    {{-- địa chỉ --}}
                    <div class="d-flex mb-5">
                        <div class="gray-1">Địa chỉ:</div>
                        <div class="ml-5 black">403/10, Lê Văn sỹ, Phường 2, Quận Tân Bình, Hồ Chí Minh</div>
                    </div>

                    {{-- SĐT --}}
                    <div class="d-flex">
                        <div class="gray-1">Điện thoại:</div>
                        <div class="ml-5 black">0779792000</div>
                    </div>
                </div>

                <div class="col-md-12 white-bg p-20 border mb-20">
                    {{-- họ tên --}}
                    <div class="d-flex justify-content-between pb-10">
                        <div class="d-flex">
                            <b class="text-uppercase">Vũ Hoàng Lâm</b>
                            {{-- <div class="d-flex align-items-center success-color-2 ml-15"><i class="far fa-check-circle mr-5"></i>Đang sử dụng</div> --}}
                        </div>
                        <div class="d-flex">
                            <div id='btn-edit-add' class="pointer-cs main-color-text">Chỉnh sửa</div>
                            <div class="btn-remove-address pointer-cs price-color ml-20">Xóa</div>
                        </div>
                    </div>

                    {{-- địa chỉ --}}
                    <div class="d-flex mb-5">
                        <div class="gray-1">Địa chỉ:</div>
                        <div class="ml-5 black">403/10, Lê Văn sỹ, Phường 2, Quận Tân Bình, Hồ Chí Minh</div>
                    </div>

                    {{-- SĐT --}}
                    <div class="d-flex">
                        <div class="gray-1">Điện thoại:</div>
                        <div class="ml-5 black">0779792000</div>
                    </div>
                </div>  
            @endforeach
        @else
            <div class="p-70 box-shadow text-center">Bạn chưa có địa chỉ giao hàng.</div>
        @endif
    </div>
</div>

{{-- modal thêm địa chỉ mới --}}
<div class="modal fade" id="new-address-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-8 mx-auto pt-50 pb-50">
                    <h3 class="text-end">Tạo địa chỉ mới</h3>
                    <hr class="mt-5 mb-40">
                    <form action="#">
                        @csrf
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="new-add-hoten" class="font-weight-600 mb-5">Họ và Tên</label>
                                    <input type="text" id="new-add-hoten" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label for="new-add-tel" class="font-weight-600 mb-5">Số điện thoại</label>
                                    <input type="text" id="new-add-tel" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-600 mb-5">Địa chỉ</label>
                            <div class="row">
                                {{-- chọn tỉnh thành --}}
                                <div class='col-md-6 mb-3'>
                                    <div class="select">
                                        <div id='TinhThanh-selected' class="select-selected">
                                            <div id='TinhThanh-name'>
                                                <?php echo $lstTinhThanh[0]['Name'] ?>
                                            </div>
                                            <i class="far fa-chevron-down fz-14"></i>
                                        </div>
                                        <div id='TinhThanh-box' class="select-box">
                                            {{-- tìm kiếm --}}
                                            <div class="select-search">
                                                <input id='search-tinh-thanh' type="text" class="select-search-inp" placeholder="Nhập tên Tỉnh / Thành">
                                                <i class="select-search-icon far fa-search"></i>
                                            </div>

                                            {{-- option --}}
                                            <div id='list-tinh-thanh' class="select-option">
                                                @foreach($lstTinhThanh as $lst)
                                                <div id='<?php echo $lst['ID'] ?>' data-type='<?php echo $lst['Name'] . '/TinhThanh' ?>' class="option-tinhthanh select-single-option"><?php echo $lst['Name'] ?></div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- chọn quận huyện --}}
                                <div class='col-md-6 mb-3'>
                                    <div class="select">
                                        <div id='QuanHuyen-selected' class="select-selected">
                                            <div id='QuanHuyen-name'>Chọn Quận / Huyện</div>
                                            <i class="far fa-chevron-down fz-14"></i>
                                        </div>
                                        <div id='QuanHuyen-box' class="select-box">
                                            {{-- tìm kiếm --}}
                                            <div class="select-search">
                                                <input id='search-quan-huyen' type="text" class="select-search-inp" placeholder="Nhập tên Quận / Huyện">
                                                <i class="select-search-icon far fa-search"></i>
                                            </div>

                                            {{-- option --}}
                                            <div id='list-quan-huyen' class="select-option">
                                                @foreach($lstQuanHuyen as $lst)
                                                <div id='<?php echo $lst['ID'] ?>' data-type='<?php echo $lst['Name'] . '/QuanHuyen' ?>' class="option-quanhuyen select-single-option"><?php echo $lst['Name'] ?></div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- chọn phường xã --}}
                                <div class='col-md-6'>
                                    <div class="select">
                                        <div id='PhuongXa-selected' class="select-disable">
                                            <div id="PhuongXa-name">Chọn Phường / Xã</div>
                                            <i class="far fa-chevron-down fz-14"></i>
                                        </div>
                                        <div id='PhuongXa-box' class="select-box">
                                            {{-- tìm kiếm --}}
                                            <div class="select-search">
                                                <input id='search-phuong-xa' type="text" class="select-search-inp" placeholder="Nhập tên Phường / Xã">
                                                <i class="select-search-icon far fa-search"></i>
                                            </div>

                                            {{-- option --}}
                                            <div id='list-phuong-xa' class="select-option"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- số nhà, tên đường --}}
                                <div class='col-md-6'>
                                    <input id='address-inp' type="text" class='form-control'
                                        placeholder="Số nhà, tên đường" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="checkbox" id='set-default-address'>
                            <label for="set-default-address">Đặt làm địa chỉ mặc định</label>
                        </div>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                <div class="cancel-btn p-10 mr-10" data-bs-dismiss="modal">Hủy</div>
                                <div class="main-btn p-10">Thêm</div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal xác nhận --}}
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center p-20 text-center">
                    <i class="fas fa-info-circle fz-50 info-color mb-20"></i>
                    <h4>Bạn có muốn xóa địa chỉ này ?</h4>
                </div>
                
                <div class="d-flex justify-content-end align-items-center pt-20">
                    <div type=button id='btn-cancel-modal' class="cancel-btn p-5 mr-10" data-bs-dismiss="modal">Hủy</div>
                    <div class="main-btn p-5">OK</div>
                </div>
            </div>
        </div>
    </div>
</div>