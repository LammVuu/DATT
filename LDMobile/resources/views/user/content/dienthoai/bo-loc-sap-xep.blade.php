<div class="d-flex align-items-center justify-content-between box-shadow p-15 mb-20">
    <b>
        @if (empty($brand))
            {{ $qty . ' điện thoại'}}
        @else
            {{ $qty . ' điện thoại ' . $brand }}
        @endif
    </b>
    <div class="d-flex">
        {{-- bộ lọc --}}
        <div class='relative mr-20'>
            <span id='btn-show-filter'><i class="fal fa-filter mr-5"></i>Bộ lọc</span>
            <div class="shop-filter-box border">
                <div class='d-flex flex-row justify-content-between'>
                    <input type="hidden" id='flag' value='0'>
                    {{-- màu sắc --}}
                    <div class='d-flex flex-column'>
                        <b>Màu sắc</b>

                        <div class='mt-10'>
                            <div class="filter-item">
                                <input type="checkbox" class="color-filter" name='checkbox-filter' id='red' value='đỏ'>
                                <label for="red">Đỏ</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class="color-filter" name='checkbox-filter' id='blue' value='xanh'>
                                <label for="blue">Xanh dương</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class="color-filter" name='checkbox-filter' id='black' value='đen'>
                                <label for="black">Đen</label>
                            </div>
                        </div>
                    </div>

                    {{-- dung lượng --}}
                    <div class='d-flex flex-column'>
                        <b>Dung lượng</b>

                        <div class='mt-10'>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-32'>
                                <label for="capacity-32" class='form-check-label'>32 GB</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-64'>
                                <label for="capacity-64" class='form-check-label'>64 GB</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-128'>
                                <label for="capacity-128" class='form-check-label'>128 GB</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-256'>
                                <label for="capacity-256" class='form-check-label'>256 GB</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-512'>
                                <label for="capacity-512" class='form-check-label'>512 GB</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" class='form-check-input' name='checkbox-filter' id='capacity-1t'>
                                <label for="capacity-1t" class='form-check-label'>1 TB</label>
                            </div>
                        </div>
                    </div>

                    {{-- khuyến mãi --}}
                    <div class='d-flex flex-column'>
                        <b>Khuyến mãi</b>

                        <div class='mt-10'>
                            <div class="filter-item">
                                <input type="checkbox" name='checkbox-filter' id='promotion'>
                                <label for="promotion" >Sản phẩm có khuyến mãi</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='shop-btn-see-result pt-20'>
                    <div class="main-btn p-5">Xem 10 kết quả</div>
                    <div class='shop-btn-remove-filter pt-10'>Bỏ chọn tất cả</div>
                </div>
            </div>
        </div>

        {{-- sắp xếp --}}
        <div class="relative">
            <span id='btn-show-sort'><i class="fal fa-sort mr-5"></i>Sắp xếp</span>
            <div class="shop-sort-box border">
                <div class="d-flex justify-content-center">
                    <div class='d-flex flex-column'>
                        {{-- nổi bật nhất --}}
                        <div class="filter-item">
                            <input type="radio" name='sort' id='sort_1' checked>
                            <label for="sort_1">Nổi bật nhất</label>
                        </div>

                        {{-- bán chạy nhất --}}
                        <div class="filter-item">
                            <input type="radio" name='sort' id='sort_2'>
                            <label for="sort_2">Bán chạy nhất</label>
                        </div>

                        {{-- giá cao đến thấp --}}
                        <div class="filter-item">
                            <input type="radio" name='sort' id='sort_3'>
                            <label for="sort_3">Giá cao đến thấp</label>
                        </div>

                        {{-- giá thấp đến cao --}}
                        <div class="filter-item">
                            <input type="radio" name='sort' id='sort_4'>
                            <label for="sort_4">Giá thấp đến cao</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>