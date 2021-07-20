@extends("admin.layout")
@section("sidebar-account-voucher") sidebar-link-selected @stop
@section("content-title")Tài Khoản Voucher @stop
@section("content")
{{-- function button --}}
<div class="d-flex justify-content-between align-items-center mb-20">
    {{-- create button --}}

    <div><i></i></div>
    {{-- filter & sort --}}
    <div class="d-flex">
        <div class="head-input-grp pb-20 w-70 ">
            <input type="text" class='head-search-input border' id="account-voucher-search" placeholder="Tìm kiếm id, id_tk,...">
            <span  class='input-icon-right' id="submit-account-voucher-search"><i class="fal fa-search"></i></span>
        </div>
    </div>
</div>

{{-- table --}}
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID_TK</th>
            <th>ID_VC</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="lst_account_voucher">
        @foreach ($listTaiKhoanVoucher as $voucher)
        <tr data-id="{{$voucher->id}}">
            <td class="vertical-center w-10">{{$voucher->id}}</td>
            <td class="vertical-center w-25">{{$voucher->id_tk}}</td>
            <td class="vertical-center w-25">{{$voucher->id_vc}}</td>
    
            {{-- nút --}}
            <td class="vertical-center w-15">
                <div class="d-flex justify-content-evenly">
                    <div data-id="{{$voucher->id}}" data-object="accountvoucher" class="delete-account-voucher-btn delete-btn">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="auto-load text-center">
    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
        <path fill="#078FDB"
            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                from="0 50 50" to="360 50 50" repeatCount="indefinite" />
        </path>
    </svg>
</div>
{{-- modal thêm|sửa hình ảnh --}}
<div class="modal fade" id="accountvoucher-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scollable">
        <div class="modal-content">
            {{-- modal header --}}
            <div class="modal-header">
                <div id="modal-title" class="fw-600 fz-22"></div>
            </div>
            <div class="modal-body p-40">
                {{-- danh sách mẫu sp --}}
                
                {{-- chọn hình ảnh --}}
                
                <div class="d-flex justify-content-end mt-50">
                    <div class="checkout-btn p-10" data-bs-dismiss="modal">Đóng</div>
                    <div id="action-account-voucher-btn" class="main-btn p-10 ml-10"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-account-voucher-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
                <div class="modal-body p-60">
                    <div id="delete-content" class="fz-20"></div>
                    <div class="mt-30 d-flex justify-content-between">
                        <div class="cancel-btn p-10 w-48" data-bs-dismiss="modal">Hủy</div>
                        <div id="delete-account-voucher-btn" data-id="" class="checkout-btn p-10 w-48">Xóa</div>
                    </div>
                </div>
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="object" name="object">
        </div>
    </div>
</div>
<div id="toast"></div>

@stop