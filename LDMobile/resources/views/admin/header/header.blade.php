<!-- header -->
<div class="navigation">
    <!-- left head -->
    <div class="w-30">
        <div class="d-flex align-items-center">
            <a href={{route('admin/dashboard')}} class="d-flex align-items-center">
                <img src="images/logo/LDMobile-logo.png" alt="" width="40px">
                <div class="fz-22 ml-10 white">ADMIN</div>
            </a>
            <div id='btn-expand-menu' aria-expanded="true"><i class="far fa-bars"></i></div>
        </div>
    </div>

    <!-- right head -->
    <div class="w-70 mr-40">
        <div class="relative d-flex justify-content-end align-items-center">
            <!-- account -->
            <div class="d-flex align-items-center">
                {{-- avatar --}}
                <img src="{{$url_user.$user->anhdaidien}}" alt="" width="40px" class="circle-img mr-10">
                <!-- name -->
                <div id='btn-expand-account' class="d-flex align-items-center">
                    <div class="pointer-cs white">{{$user->hoten}}<i class="far fa-chevron-down ml-10 fz-14"></i></div>
                </div>
            </div>

            {{-- account option --}}
            <div class="account-option">
                <a href="#" class="single-option"><i class="fas fa-user-alt mr-10"></i>Xem tài khoản</a>
                <a href="#" class="single-option"><i class="fas fa-palette mr-10"></i>Giao diện</a>
                <a href="#" class="single-option"><i class="far fa-power-off mr-10"></i>Đăng xuất</a>
            </div>
        </div>
    </div>
</div>