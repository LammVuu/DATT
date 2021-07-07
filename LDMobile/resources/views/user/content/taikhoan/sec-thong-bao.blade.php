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

        {{-- danh sách thông báo --}}
        @if (count($data['lst_noti']['noti']) != 0)
            <div id="lst_noti">
                @foreach ($data['lst_noti']['noti'] as $key)
                    @if ($key['trangthaithongbao'] == 0)
                        <div id={{ 'noti-' . $key['id'] }} class='single-noti account-noti-wait box-shadow mb-20'>
                            {{-- tiêu đề --}}
                            <div class="d-flex align-items-center justify-content-between p-10 border-bottom">
                                <div class="d-flex align-items-end">
                                    <b>{{$key['tieude']}}</b>
                                    
                                    <div class="fz-14 ml-25">{{$key['thoigian']}}</div>
                                </div>
                                
                                <div class="d-flex">
                                    <div type="button" class="noti-btn-read main-color-text mr-10" data-id='{{$key['id']}}'>Đánh dấu đã đọc</div>
                                    <div type="button" class='noti-btn-delete price-color' data-id='{{$key['id']}}'>xóa</div>
                                </div>
                            </div>
                            {{-- nội dung --}}
                            <div class="d-flex p-10">
                                <div id={{ 'noti-content-' . $key['id'] }}>
                                    <div>{{ $key['noidung'] }}</div> 
                                </div>
                            </div>
                        </div>
                    @else
                        <div id={{ 'noti-' . $key['id'] }} class='single-noti account-noti-checked box-shadow mb-20'>
                            {{-- tiêu đề --}}
                            <div class="d-flex align-items-center justify-content-between p-10 border-bottom">
                                <div class="d-flex align-items-end">
                                    <b>{{$key['tieude']}}</b>
                                    
                                    <div class="fz-14 ml-25">{{$key['thoigian']}}</div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <div type="button" class='noti-btn-delete price-color' data-id='{{$key['id']}}'>xóa</div>
                                </div>
                            </div>
                            {{-- nội dung --}}
                            <div class="d-flex p-10">
                                <div id={{ 'noti-content-' . $key['id'] }}>
                                    <div>{{ $key['noidung'] }}</div> 
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="p-70 box-shadow text-center">Bạn không có thông báo nào.</div>
        @endif
    </div>
</div>

<div id="toast"></div>