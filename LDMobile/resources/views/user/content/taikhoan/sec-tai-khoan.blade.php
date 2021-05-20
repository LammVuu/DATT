<div class='row'>
    <div class='col-md-3'>
        @section("acc-info-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        {{-- ảnh bìa --}}
        <div class="account-cover-avatar-div">
            <div class='account-cover-img'>
                <div class='overlay-cover'>
                    <input id='change-cover-inp' data-modal='cover' type="file" class="none-dp" accept="image/*">
                    <div id='btn-change-cover' class='account-change-img pointer-cs'>Thay đổi</div>
                </div>
            </div>
        </div>

        {{-- avatar --}}
        <div class='account-avatar-div d-flex justify-content-between align-items-end'>
            <div class='flex-column'>
                <h4>Hoang Lam</h4>
                <i>vuhoanglam2000vn@gmail.com</i>
            </div>
            <div class='account-cover-avatar-div'>
                <div class='overlay-avatar'>
                    <input id='change-avt-inp' data-modal='avt' type="file" class="none-dp" accept="image/*">
                    <div id='btn-change-avt' class='account-change-img pointer-cs'>Thay đổi</div>
                </div>
                <img id='avt-img' src="images/icon/user-icon-1.png" alt="avatar" class='account-avt-img'>
            </div>
        </div>
        
        {{-- thông tin tài khoản --}}
        <div style='position: relative; top: -50px'>
            <div class='account-profile-title'>
                <h5 class='d-flex justify-content-between align-items-end pl-5'>
                    THÔNG TIN TÀI KHOẢN
                    <div id='btn-change-info' class="pointer-cs"><i class="fas fa-user-edit"></i></div>
                </h5>
            </div>

            <div class='row pt-10 pl-40'>
                <div class='col-sm-3'>Họ Tên</div>
                <div class='col-sm-9'>Hoang Lam</div>
            </div>

            <div class='row pt-10 pl-40'>
                <div class='col-sm-3'>Email</div>
                <div class='col-sm-9'>vuhoanglam2000vn@gmail.com</div>
            </div>

            <div class='row pt-10 pl-40'>
                <div class='col-sm-3'>SDT</div>
                <div class='col-sm-9'>0779792000</div>
            </div>

            <div class='row pt-10 pl-40'>
                <div class='col-sm-3'>Địa chỉ</div>
                <div class='col-sm-9'>403/10, Lê Văn Sỹ, P2, Q.Tân Bình</div>
            </div><hr>

            {{-- đổi mật khẩu --}}
            <div class="ml-40">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between">
                        <div id='btn-change-pw' class="main-color-text pointer-cs"><i class="fas fa-key mr-10"></i>Thay đổi mật khẩu</div>
                        <div id='btn-close-change-pw' class="pointer-cs price-color none-dp"><i class="far fa-times-circle mr-5"></i>Hủy</div>
                    </div>
                    <div id='change-pw-div' class="none-dp mt-20">
                        <form action="#">
                            <div class="row mb-3">
                                <label for="old-pw" class="col-md-3 col-form-label">Mật khẩu cũ</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id='old-pw' placeholder="Nhập mật khẩu cũ">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="new-pw" class="col-md-3 col-form-label">Mật khẩu mới</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id='new-pw' placeholder="Mật khẩu từ 6-32 ký tự">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="retype-pw" class="col-md-3 col-form-label">Nhập lại</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id='retype-pw' placeholder="Nhập lại mật khẩu mới">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 offset-9">
                                    <div class="main-btn p-5 w-100">Cập nhật</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- modal chỉnh sửa thông tin tài khoản --}}
<div class="modal fade" id="change-info" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-8 mx-auto pt-50 pb-50">
                    <h3 class="text-end">Chỉnh sửa thông tin</h3>
                    <hr class="mt-5 mb-40">
                    <form action="#">
                        @csrf
                        <div class="row mb-3">
                            <label for="HoTen" class="col-md-2 col-form-label">Họ Tên</label>
                            <div class="col-md-10">
                                <input type="text" id="Hoten" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="SDT" class="col-md-2 col-form-label">SĐT</label>
                            <div class="col-md-10">
                                <input type="text" id="SDT" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="DiaChi" class="col-md-2 col-form-label">Địa chỉ</label>
                            <div class="col-md-10">
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
                        </div>
                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                <button class="cancel-btn p-5 mr-10" data-bs-dismiss="modal">Hủy</button>
                                <div class="main-btn p-5">Cập nhật</div>
                            </div>
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

{{-- modal thay đổi ảnh bìa --}}
<div class="modal fade" id="change-cover" data-modal='cover-modal' data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="w-50 m-10">
                        <img id='pre-cover-big' src="" alt="">
                    </div>
                    <div class="d-flex flex-column w-50 m-10">
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-600">Xem trước</div>
                            <span data-modal='cover' class="reselect-img pointer-cs main-color-text">Chọn ảnh khác</span>
                        </div>
                        <div class="mt-20 mb-20">
                            <div class="relative mb-50">
                                <div class="preview-cover"></div>
                                <div class="avt-temp">
                                    <img src="images/icon/user-icon-1.png" class="circle-img">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-fill align-items-end justify-content-end">
                            <div class="cancel-btn p-5 mr-10" data-bs-dismiss="modal">Hủy</div>
                            <div class="crop-img main-btn p-5" data-crop='cover'>Cập nhật</div>               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- toast avatar --}}
<div id='avt-toast' class="alert-toast">
    <div class="d-flex align-items-center">
        <span>Cập nhật ảnh đại diện thành công</span>
        <span class="btn-close-toast ml-20"><i class="fal fa-times"></i></span>
    </div>
</div>

{{-- toast cover --}}
<div id='cover-toast' class="alert-toast">
    <div class="d-flex align-items-center">
        <span>Cập nhật ảnh bìa thành công</span>
        <span class="btn-close-toast ml-20"><i class="fal fa-times"></i></span>
    </div>
</div>