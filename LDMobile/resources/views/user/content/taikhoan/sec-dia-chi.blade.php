<div class='row'>
    <div class='col-md-3'>
        @section("acc-address-active") account-sidebar-active @stop
        @include("user.content.taikhoan.sec-thanh-chuc-nang")
    </div>
    <div class="col-md-9">
        <div id="new-address-show" class="btn-new-address mb-30">
            <i class="far fa-plus mr-10"></i>Thêm địa chỉ mới
        </div>

        @if (count($data['lst_address']) != 0)
            {{-- địa chỉ mặc định --}}
            @foreach ($data['lst_address'] as $key)
                @if($key['macdinh'] == 1)
                    <div id="address-{{$key['id']}}" data-default="true" class="white-bg p-20 border-success mb-30">
                        <div class="d-flex justify-content-between pb-10">
                            <div class="d-flex">
                                <b id="adr-fullname-{{$key['id']}}" class="text-uppercase">{{ $key['hoten'] }}</b>
                                <div class="d-flex align-items-center success-color fw-600 ml-15"><i class="far fa-check-circle mr-5"></i>Đang sử dụng</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div type="button" data-id="{{$key['id']}}" data-diachi="{{$key['diachi']}}"
                                data-phuongxa="{{$key['phuongxa']}}" data-quanhuyen="{{$key['quanhuyen']}}"
                                data-tinhthanh="{{$key['tinhthanh']}}" class="btn-edit-address main-color-text"><i class="fas fa-pen mr-5"></i>Chỉnh sửa</div>
                            </div>
                        </div>
            
                        <div class="d-flex mb-5">
                            <div class="gray-1">Địa chỉ:</div>
                            <div class="ml-5 black">
                                {{$key['diachi'].', '.$key['phuongxa'].', '.$key['quanhuyen'].', '.$key['tinhthanh']}}
                            </div>
                        </div>
            
                        <div class="d-flex">
                            <div class="gray-1">Điện thoại:</div>
                            <div id="adr-tel-{{$key['id']}}" class="ml-5 black">{{$key['sdt']}}</div>
                        </div>
                    </div>
                    @break
                @endif
            @endforeach

            {{-- địa chỉ khác --}}
            @foreach ($data['lst_address'] as $key)
                @if($key['macdinh'] == 0)
                    <div id="address-{{$key['id']}}" data-default="false" class="white-bg p-20 box-shadow mb-30">
                        <div class="d-flex justify-content-between pb-10">
                            <div class="d-flex">
                                <b id="adr-fullname-{{$key['id']}}" class="text-uppercase">{{ $key['hoten'] }}</b>
                            </div>
                            {{-- nút chức năng --}}
                            <div class="d-flex align-items-center">
                                <div type="button" data-id="{{$key['id']}}" data-diachi="{{$key['diachi']}}"
                                data-phuongxa="{{$key['phuongxa']}}" data-quanhuyen="{{$key['quanhuyen']}}"
                                data-tinhthanh="{{$key['tinhthanh']}}" class="btn-edit-address main-color-text mr-10"><i class="fas fa-pen mr-5"></i>Chỉnh sửa</div>
                                <div type="button" data-id="{{$key['id']}}" class="btn-delete-address red"><i class="fas fa-trash mr-5"></i>Xóa</div>
                            </div>
                        </div>
            
                        <div class="d-flex mb-5">
                            <div class="gray-1">Địa chỉ:</div>
                            <div class="ml-5 black">
                                {{$key['diachi'].', '.$key['phuongxa'].', '.$key['quanhuyen'].', '.$key['tinhthanh']}}
                            </div>
                        </div>
            
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <div class="gray-1">Điện thoại:</div>
                                <div id="adr-tel-{{$key['id']}}" class="ml-5 black">{{$key['sdt']}}</div>
                            </div>
                            <div type="button" data-id="{{$key['id']}}" class="btn-set-default-btn">Đặt làm mặc định</div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="p-70 box-shadow text-center">Bạn chưa có địa chỉ giao hàng.</div>
        @endif
    </div>
</div>

{{-- modal thêm|sửa địa chỉ --}}
@include("user.content.modal.dia-chi-modal")

{{-- modal xóa --}}
@include("user.content.modal.xoa-modal")

<div id="toast"></div>