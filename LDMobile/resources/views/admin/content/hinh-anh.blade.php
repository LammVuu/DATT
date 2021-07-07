@extends("admin.layout")
@section("sidebar-image") sidebar-link-selected @stop
@section("content-title") Hình ảnh @stop
@section("content")

{{-- function button --}}
<div class="d-flex justify-content-between align-items-center mb-20">
    {{-- create button --}}
    <div type="button" class="create-hinhanh-modal-show create-btn"><i class="fas fa-plus"></i></div>

    {{-- filter & sort --}}
    <div class="d-flex">
        <div class="filter-sort-btn mr-20"><i class="far fa-filter mr-5"></i>Bộ lọc</div>
        <div class="filter-sort-btn"><i class="far fa-sort mr-5"></i>Sắp xếp</div>
    </div>
</div>

{{-- table --}}
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sản phẩm</th>
            <th>Hình ảnh</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="lst_hinhanh">
        <tr data-id="1">
            <td class="vertical-center w-10">0</td>
            <td class="vertical-center w-30">iPhone 12 PRO MAX</td>
            <td class="vertical-center w-45">
                <div class="d-flex">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                </div>
            </td>
            {{-- nút --}}
            <td class="vertical-center w-15">
                <div class="d-flex justify-content-evenly">
                    <div class="info-hinhanh-btn info-btn"><i class="fas fa-info"></i></div>
                    <div data-id="1" class="edit-hinhanh-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                    <div data-id="1" data-object="hinhanh" class="delete-hinhanh-btn delete-btn">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr data-id="2">
            <td class="vertical-center w-10">0</td>
            <td class="vertical-center w-30">iPhone 12 PRO MAX</td>
            <td class="vertical-center w-45">
                <div class="d-flex">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                    <img src="images/phone/iphone_12_red.jpg" alt="" width="50px">
                </div>
            </td>
            {{-- nút --}}
            <td class="vertical-center w-15">
                <div class="d-flex justify-content-evenly">
                    <div class="info-hinhanh-btn info-btn"><i class="fas fa-info"></i></div>
                    <div data-id="2" class="edit-hinhanh-modal-show edit-btn"><i class="fas fa-pen"></i></div>
                    <div data-id="2" data-object="hinhanh" class="delete-hinhanh-btn delete-btn">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

{{-- modal thêm|sửa hình ảnh --}}
<div class="modal fade" id="hinhanh-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
                    <div id="action-hinhanh-btn" class="main-btn p-10 ml-10"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast"></div>

{{-- modal xóa --}}
@include("user.content.modal.xoa-modal")

@stop