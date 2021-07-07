{{-- modal thêm|sửa địa chỉ --}}
<div class="modal fade" id="address-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-8 mx-auto pt-50 pb-50">
                    <h3 id="address-modal-title" class="text-end"></h3>
                    <hr class="mt-5 mb-40">
                    <form id="address-form" method="POST" action="{{route('user/create-update-address')}}">
                        @csrf
                        {{-- create/edit --}}
                        <input type="hidden" name="address_type" name="address-type">
                        {{-- id --}}
                        <input type="hidden" name="tk_dc_id">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="fw-600 mb-5">Họ và Tên</label>
                                    <input type="text" name="adr_fullname_inp" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="fw-600 mb-5">Số điện thoại nhận hàng</label>
                                    <input type="text" name="adr_tel_inp" class="form-control" maxlength="10">
                                </div>
                            </div>
                        </div>
                        {{-- khu vực --}}
                        <div class="mb-3">
                            <label class="fw-600 mb-5">Địa chỉ</label>
                            <div class="row">
                                {{-- chọn tỉnh thành --}}
                                <div class='col-md-6 mb-3'>
                                    <div class="select">
                                        <div id='TinhThanh-selected' class="select-selected">
                                            <div id='TinhThanh-name'>{{ $lstTinhThanh[0]['Name'] }}</div>
                                            <input type="hidden" name="TinhThanh_name_inp" value="{{$lstTinhThanh[0]['Name']}}">
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
                                                <div id='{{ $lst['ID'] }}' data-type='{{ $lst['Name'] . '/TinhThanh' }}' class="option-tinhthanh select-single-option">{{ $lst['Name'] }}</div>
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
                                            <input type="hidden" name="QuanHuyen_name_inp">
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
                                                <div id='{{ $lst['ID'] }}' data-type='{{ $lst['Name'] . '/QuanHuyen' }}' class="option-quanhuyen select-single-option">{{ $lst['Name'] }}</div>
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
                                            <input type="hidden" name="PhuongXa_name_inp">
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
                                    <input name="address_inp" type="text" class='form-control'
                                        placeholder="Số nhà, tên đường" required>
                                </div>
                            </div>
                        </div>

                        {{-- đặt mặc định --}}
                        <div class="mb-3">
                            <input type="checkbox" id="set_default_address" name="set_default_address">
                            <label for="set_default_address">Đặt làm địa chỉ mặc định</label>
                        </div>

                        {{-- nút --}}
                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                <div class="cancel-btn p-10 mr-10" data-bs-dismiss="modal">Hủy</div>
                                <div class="address-action-btn main-btn p-10"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>