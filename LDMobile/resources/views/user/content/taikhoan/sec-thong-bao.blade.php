<div class='row'>
    <div class='col-md-3'>
        @section("acc-noti-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class='col-md-9'>
        <table class='table border'>
            <thead>
                <th colspan="3" class='center-td'>
                    <h4 class='ml-20 font-weight-600'>Thông báo</h4>
                </th>
                {{-- nút 3 chấm --}}
                <th class='center-td'>
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
                </th>
            </thead>
            <tbody>
                @if (count($data['lst_noti']['noti']) != 0)
                    @foreach ($data['lst_noti']['noti'] as $key)
                    <tr id={{ 'noti-' . $key['id'] }} class='account-noti-wait'>
                        {{-- ngày --}}
                        <td class='w-10 align-middle'>
                            <div class='p-20'>
                                <span>{{ $key['thoigian'] }}</span>
                            </div>
                        </td>
    
                        {{-- nội dung --}}
                        <td id={{ 'noti-content-' . $key['id'] }} class='w-70 align-middle'>
                            <div class='p-20'>
                                {{ $key['noidung'] }}
                            </div>                            
                        </td>
    
                        {{-- nút đánh dấu đã đọc --}}
                        <td class='w-15 align-middle'>
                            <div class="noti-btn-read pointer-cs main-color-text" data-id='<?php echo $i ?>'>Đánh dấu đã đọc</div>
                        </td>
    
                        {{-- nút xóa --}}
                        <td class='w-5 align-middle'>
                            <div class='noti-btn-delete pointer-cs price-color' data-id='<?php echo $i ?>'>xóa</div>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="4">
                        <div class="text-center p-70">Bạn không có thông báo nào.</div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>