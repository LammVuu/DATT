<?php $user = session('user') ?>

@section("breadcrumb")
    <a href="{{route('user/dien-thoai')}}" class="bc-item">điện thoại</a>
@stop

<div class='row'>
    <div class='col-md-3'>
        @section("acc-info-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9 account-div-border'>
        <div class="row">
            <div class="col-lg-4">
                {{-- avatar --}}
                <div class='account-avatar-div'>
                    @if (session('user')->htdn == 'nomal')
                    <div class='overlay-avatar'>
                        <input id='change-avt-inp' data-modal='avt' type="file" class="none-dp" accept="image/*">
                        <div id='btn-change-avt' class='account-change-img pointer-cs'>Thay đổi</div>
                    </div>
                    @endif
                    <img id='avt-img' src="{{ session('user')->htdn == 'nomal' ? $url_user.$user->anhdaidien : $user->anhdaidien}}" alt="avatar" class='account-avt-img'>
                </div>

                {{-- họ tên --}}                    
                @if (session('user')->htdn == 'nomal')
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="text-center fz-24 black">{{session('user')->hoten}}</div>
                        <div type="button" id='btn-change-info' class="ml-10"><i class="fas fa-user-edit"></i></div>
                    </div>
                    <div id="change-info-div" class="none-dp">
                        <span id="cancel-change-info" type="button" class="price-color mb-5">Hủy</span>
                        <div class="d-flex">
                            <div class="w-70 pr-5">
                                <input type="text" placeholder="Họ và tên">
                            </div>
                            <div class="main-btn w-30">Cập nhật</div>
                        </div>
                    </div>
                @else
                    <div class="text-center fz-24 black">{{session('user')->hoten}}</div>
                @endif
            </div>
            <div class="col-lg-8">
                {{-- địa chỉ giao hàng --}}
                <div class="font-weight-600 fz-20 mb-10">Thông tin giao hàng</div>
                @if ($addressDefault != null)
                    <div class="white-bg p-20 border mb-30">
                        {{-- họ tên --}}
                        <div class="d-flex justify-content-between pb-10">
                            <div class="d-flex">
                                <b class="text-uppercase">{{$addressDefault->hoten}}</b>
                                <div class="d-flex align-items-center success-color-2 ml-15"><i class="far fa-check-circle mr-5"></i>Đang sử dụng</div>
                            </div>
                            <div class="d-flex">
                                <div id='btn-edit-address' class="pointer-cs main-color-text">Chỉnh sửa</div>
                            </div>
                        </div>
            
                        {{-- địa chỉ --}}
                        <div class="d-flex mb-5">
                            <div class="gray-1">Địa chỉ:</div>
                            <div class="ml-5 black">{{$addressDefault->diachi}}</div>
                        </div>
            
                        {{-- SĐT --}}
                        <div class="d-flex">
                            <div class="gray-1">Điện thoại:</div>
                            <div class="ml-5 black">{{$addressDefault->sdt}}</div>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center white-bg p-20 border mb-30">
                        Bạn chưa có địa chỉ giao hàng.
                        <div id="btn-new-address" type="button" class="main-color-text ml-5">Thêm địa chỉ</div>
                    </div>
                @endif

                {{-- đổi mật khẩu --}}
                @if (session('user')->htdn == 'nomal')
                    <div id='btn-change-pw' type="button" data-bs-toggle="modal" data-bs-target="#change-pw-modal" class="d-flex align-items-center main-color-text"><i class="fas fa-key mr-10"></i>Thay đổi mật khẩu</div>
                @endif
                
            </div>
        </div>
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

{{-- modal cập nhật mật khẩu --}}
<div class="modal fade" id="change-pw-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div type="button" class="btn-close" data-bs-dismiss="modal"></div>
                <div class="p-50">
                    <form id="change-pw-form" method="POST">
                        <div class="mb-3">
                            <label for="old-pw" class="font-weight-600 mb-5">Mật khẩu cũ</label>
                            <input type="password" id='old-pw' placeholder="Nhập mật khẩu cũ">
                        </div>
                        <div class="mb-3">
                            <label for="new-pw" class="font-weight-600 mb-5">Mật khẩu mới</label>
                            <input type="password" id='new-pw' placeholder="Mật khẩu từ 6-16 ký tự">
                        </div>
                        <div class="mb-3">
                            <input type="password" id='retype-pw' placeholder="Nhập lại mật khẩu mới">
                        </div>
                        <div class="mb-3">
                            <div class="main-btn p-5 w-100">Cập nhật</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal thay đổi ảnh đại diện --}}
<div class="modal fade" id="change-avt" data-modal='avt-modal' data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="w-70 m-10">
                        <img id='pre-avt-big' src="" alt="">
                        <div class="mt-20">
                            <div class="d-flex align-items-center">
                                <b>Thu Phóng</b>
                                <b class="ml-10 mr-10">|</b>
                                <i id='reset-canvas' class="far fa-redo"></i>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between mt-10">
                                    <span><i class="far fa-search-minus"></i></span>
                                    <div class="flex-fill pl-20 pr-20">
                                        <div class="slidecontainer">
                                            <input type="range" step='0.1' min="-1" max="1" value='0' class="slider" id="zoom-range">
                                        </div>
                                    </div>
                                    <span><i class="far fa-search-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column w-30 m-10">
                        <div class="font-weight-600 text-end">Xem trước</div>
                        <div class="mt-20 mb-20">
                            <div class="preview-avt center-img"></div>
                        </div>
                        <div class="text-end">
                            <span data-modal='avt' class="reselect-img pointer-cs main-color-text">Chọn ảnh khác</span>
                        </div>
                        <hr>
                        <div class="d-flex flex-fill align-items-end justify-content-end">
                            <div class="cancel-btn p-5 mr-10" data-bs-dismiss="modal">Hủy</div>
                            <div class="crop-img main-btn p-5" data-crop='avt'>Cập nhật</div>               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast"></div>