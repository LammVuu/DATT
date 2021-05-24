@extends("admin.layout")
@section("sidebar-image") sidebar-link-selected @stop
@section("content-title") Hình ảnh @stop
@section("content")

{{-- function button --}}
<div class="d-flex justify-content-between align-items-center mb-20">
    {{-- add button --}}
    <a href="#" class="add-btn"><i class="fas fa-plus"></i></a>

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
    <tbody>
        <tr>
            <td class="vertical-center w-10">0</td>
            <td class="vertical-center w-30">iPhone 12 PRO MAX</td>
            <td class="vertical-center w-45">
                <img src="../../images/phone/iphone_12_red.jpg" alt="" width="150px">
            </td>
            <td class="vertical-center w-15">
                <a href="#" class="info-btn"><i class="fas fa-info"></i></a>
                <a href="#" class="edit-btn"><i class="fas fa-pen"></i></a>
                <a href="#" class="delete-btn"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
    </tbody>
</table>
@stop