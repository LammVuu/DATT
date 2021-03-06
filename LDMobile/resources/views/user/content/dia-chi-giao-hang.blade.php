<!DOCTYPE html>
<html lang="en">
    @include("user.header.head")
<body class="address-delivery-bg">
    <div class="address-delivery-header">
        <div class="container">
            <img src="images/logo/LDMobile-logo.png" alt="logo" width="80px">
        </div>
    </div>

    {{-- session --}}
    @if (session('toast_message'))
        <div id="toast-message" data-message="{{session('toast_message')}}"></div>
    @endif

    <div class="container">
        <div class="pt-20 pb-20">
            <div class="fz-22 font-weight-700 mb-10">Địa chỉ giao hàng</div>
            <b>Chọn địa chỉ giao hàng có sẵn bên dưới</b>

            <div class="row mt-20">
                {{-- địa chỉ mặc định --}}
                @foreach ($data['lst_address'] as $key)
                    @if($key['macdinh'] == 1)
                        <div class="col-lg-6">
                            <div id="address-{{$key['id']}}" data-default="true" class="white-bg p-20 border-success mb-30">
                                <div class="d-flex justify-content-between pb-10">
                                    <div class="d-flex">
                                        <b id="adr-fullname-{{$key['id']}}" class="text-uppercase">{{ $key['hoten'] }}</b>
                                        <div class="d-flex align-items-center success-color ml-15"><i class="far fa-check-circle mr-5"></i>Đang sử dụng</div>
                                    </div>
                                </div>
                    
                                <div class="d-flex mb-5">
                                    <div class="gray-1">Địa chỉ:</div>
                                    <div class="ml-5 black">
                                        {{$key['diachi'].', '.$key['phuongxa'].', '.$key['quanhuyen'].', '.$key['tinhthanh']}}
                                    </div>
                                </div>
                    
                                <div class="d-flex mb-20">
                                    <div class="gray-1">Điện thoại:</div>
                                    <div id="adr-tel-{{$key['id']}}" class="ml-5 black">{{$key['sdt']}}</div>
                                </div>

                                {{-- button --}}
                                <div class="d-flex">
                                    <form id="change-address-delivery-form" action="{{route('user/change-address-delivery')}}" method="POST">
                                        @csrf
                                        <input type="hidden" id="address_id" name="address_id">
                                    </form>
                                    <div data-id="{{$key['id']}}" class="choose-address-delivery main-btn p-10">Giao đến địa chỉ này</div>
                                    <div data-id="{{$key['id']}}" data-diachi="{{$key['diachi']}}"
                                        data-phuongxa="{{$key['phuongxa']}}" data-quanhuyen="{{$key['quanhuyen']}}"
                                        data-tinhthanh="{{$key['tinhthanh']}}" class="btn-edit-address cancel-btn p-10 ml-10">Sửa</div>
                                </div>
                            </div>
                        </div>
                        @break
                    @endif
                @endforeach

                {{-- địa chỉ khác --}}
                @foreach ($data['lst_address'] as $key)
                    @if($key['macdinh'] == 0)
                        <div class="col-lg-6">
                            <div id="address-{{$key['id']}}" data-default="false" class="white-bg p-20 border mb-30">
                                <div class="d-flex justify-content-between pb-10">
                                    <div class="d-flex">
                                        <b id="adr-fullname-{{$key['id']}}" class="text-uppercase">{{ $key['hoten'] }}</b>
                                    </div>
                                </div>
                    
                                <div class="d-flex mb-5">
                                    <div class="gray-1">Địa chỉ:</div>
                                    <div class="ml-5 black">
                                        {{$key['diachi'].', '.$key['phuongxa'].', '.$key['quanhuyen'].', '.$key['tinhthanh']}}
                                    </div>
                                </div>
                    
                                <div class="d-flex mb-20">
                                    <div class="d-flex">
                                        <div class="gray-1">Điện thoại:</div>
                                        <div id="adr-tel-{{$key['id']}}" class="ml-5 black">{{$key['sdt']}}</div>
                                    </div>
                                </div>
                                {{-- button --}}
                                <div class="d-flex">
                                    <div data-id="{{$key['id']}}" class="choose-address-delivery main-btn p-10 mr-10">Giao đến địa chỉ này</div>
                                    <div data-id="{{$key['id']}}" data-diachi="{{$key['diachi']}}"
                                        data-phuongxa="{{$key['phuongxa']}}" data-quanhuyen="{{$key['quanhuyen']}}"
                                        data-tinhthanh="{{$key['tinhthanh']}}" class="btn-edit-address cancel-btn p-10 mr-10">Sửa</div>

                                    <div data-id="{{$key['id']}}" class="btn-delete-address checkout-btn p-10">Xóa</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- thêm địa chỉ giao hàng mới --}}
            <div class="d-flex">
                Bạn muốn giao hàng đến địa chỉ khác?
                <div id='new-address-show' class="pointer-cs main-color-text ml-10">Thêm địa chỉ giao hàng mới</div>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    @include("user.content.modal.dia-chi-modal")
    @include("user.content.modal.xoa-modal")

    @include("user.footer.footer-link")
</body>
</html>