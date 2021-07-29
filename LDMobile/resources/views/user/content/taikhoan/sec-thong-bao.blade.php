<div class='row'>
    <div class='col-md-3'>
        @section("acc-noti-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        {{-- header --}} 
        <div class="d-flex align-items-center justify-content-between p-10 box-shadow mb-20">
            <div class='fz-22 fw-600'>Thông báo</div>
            
            {{-- nút 3 chấm --}}
            <div class='d-flex justify-content-end fz-24'>
                <div class='account-btn-option' aria-expanded="false">
                    <i class="far fa-ellipsis-v"></i>
                    <div class='account-option-div border font-weight-300 fz-16'>
                        <div class='d-flex flex-column text-center'>
                            <div id='noti-btn-read-all' class='pointer-cs black p-10'>Đánh dấu đọc tất cả</div>
                            <div id='noti-btn-delete-all' class='pointer-cs black p-10'>Xóa tất cả thông báo</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-40">
            <div data-type="all" class="noti-type noti-type-selected">
                <i class="fas fa-list mr-10"></i>Tất cả
            </div>
            <div data-type="not-seen" class="noti-type">
                <i class="fas fa-eye-slash mr-10"></i>Chưa đọc
            </div>
            <div data-type="seen" class="noti-type">
                <i class="fas fa-eye mr-10"></i>Đã đọc
            </div>
            <div data-type="order" class="noti-type">
                <i class="fas fa-truck mr-10"></i>Đơn hàng
            </div>
            <div data-type="voucher" class="noti-type">
                <i class="fas fa-badge-percent mr-10"></i>Mã giảm giá
            </div>
            <div data-type="reply" class="noti-type">
                <i class="fas fa-reply mr-10"></i>Phản hồi
            </div>
        </div>

        {{-- danh sách thông báo --}}
        @if (count($data['lst_noti']['noti']) != 0)
            <div id="lst_noti">
                @foreach ($data['lst_noti']['noti'] as $key)
                    <div id={{ 'noti-' . $key['id'] }} class='single-noti {{$key['trangthaithongbao'] == 0 ? 'account-noti-wait' : 'account-noti-checked'}} box-shadow mb-20'>
                        {{-- tiêu đề --}}
                        <div class="d-flex align-items-center justify-content-between p-10 border-bottom">
                            <div class="d-flex align-items-center">
                                <div>
                                    @if ($key['tieude'] == 'Đơn đã tiếp nhận')
                                        <i class="fas fa-file-alt fz-28 info-color"></i>
                                    @elseif ($key['tieude'] == 'Đơn đã xác nhận')
                                        <i class="fas fa-file-check fz-28 success-color"></i>
                                    @elseif ($key['tieude'] == 'Giao hàng thành công')
                                        <i class="fas fa-box-check fz-28 success-color"></i>
                                    @elseif ($key['tieude'] == 'Mã giảm giá')
                                        <i class="fas fa-badge-percent fz-28 yellow"></i>
                                    @elseif ($key['tieude'] == 'Phản hồi')
                                        <i class="fas fa-reply fz-28 purple"></i>
                                    @endif
                                </div>
                                <div class="fw-600 fz-18 ml-10">{{$key['tieude']}}</div>
                            </div>
                            <div class="d-flex align-items-end">
                                @if ($key['trangthaithongbao'] == 0)
                                <div type="button" class="noti-btn-read main-color-text mr-10" data-id='{{$key['id']}}'>Đánh dấu đã đọc</div>
                                @endif
                                <div type="button" class='noti-btn-delete red' data-id='{{$key['id']}}'>xóa</div>
                            </div>
                        </div>
                        {{-- nội dung --}}
                        <div class="d-flex pt-20 pb-20 pl-10 pr-10">
                            <div id={{ 'noti-content-' . $key['id'] }}>
                                <div>{!! $key['noidung'] !!}</div>
                                <div class="mt-10 fz-14">{{$key['thoigian']}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-70 box-shadow text-center">Bạn không có thông báo nào.</div>
        @endif
    </div>
</div>

<div id="toast"></div>