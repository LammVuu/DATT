@extends("user.layout")

@section("content")

@section("direct")TÀI KHOẢN @stop
@include("user.content.section.sec-thanh-dieu-huong")

<section class='profile-wrapper pt-50 pb-100'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-10 offset-1'>
                {{-- ảnh bìa --}}
                <div class="cover-avatar-image-div">
                    <div class='cover-image'>
                        <div class='overlay-cover-image'>
                            <a href="#" class='change-image'>Thay đổi</a>
                        </div>
                    </div>
                </div>

                {{-- avatar --}}
                <div class='avatar-div d-flex justify-content-between align-items-end'>
                    <div class='flex-column'>
                        <div class='mb-auto'></div>
                        <h4>Hoang Lam</h4>
                        <i>vuhoanglam2000vn@gmail.com</i>
                    </div>
                    <div class='cover-avatar-image-div'>
                        <div class='conver-image'>
                            <div class='overlay-avatar'>
                                <a href="#" class='change-image'>Thay đổi</a>
                            </div>
                        </div>
                        <img src="images/icon/user-icon-blank.png" alt="avatar" class='avatar'>
                    </div>
                </div>
                
                {{-- thông tin tài khoản --}}
                <div style='position: relative; top: -50px'>
                    <div class='profile-title'>
                        <h5 class='d-flex justify-content-between align-items-end' style='padding: 0 5px 0'>
                            THÔNG TIN CÁ NHÂN
                            <a href="#">SỬA</a>
                        </h5>
                    </div>
    
                    <div class='row single-details-item'>
                        <div class='col-sm-3'>Họ Tên</div>
                        <div class='col-sm-9' style='color: black'>Hoang Lam</div>
                    </div>
    
                    <div class='row single-details-item'>
                        <div class='col-sm-3'>Email</div>
                        <div class='col-sm-9' style='color: black'>vuhoanglam2000vn@gmail.com</div>
                    </div>
    
                    <div class='row single-details-item'>
                        <div class='col-sm-3'>SDT</div>
                        <div class='col-sm-9' style='color: black'>0779792000</div>
                    </div>
    
                    <div class='row single-details-item'>
                        <div class='col-sm-3'>Địa chỉ</div>
                        <div class='col-sm-9' style='color: black'>403/10, Lê Văn Sỹ, P2, Q.Tân Bình</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@include('user.content.section.sec-dang-ky')
@include('user.content.section.sec-logo')

@stop