<div class='box-shadow shop-filter-box'>
    {{-- chọn giá & bộ lọc & sắp xếp --}}
    <div class='d-flex flex-row justify-content-between shop-filter-font'>
        <span>Chọn mức giá:</span>
        <a href="#">Từ 2 - 4 triệu</a>
        <a href="#">Từ 4 - 7 triệu</a>
        <a href="#">Từ 7 - 13 triệu</a>
        <a href="#">Từ 13 - 20 triệu</a>
        <a href="#">Trên 20 triệu</a>

        {{-- bộ lọc --}}
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Bộ lọc</a>
            <div class="dropdown-menu dropdown-menu-end" style='width: 500px; padding: 20px; border-radius: 10px;'>
                <div class='d-flex flex-row justify-content-lg-between'>
                    {{-- màu sắc --}}
                    <div class='d-flex flex-column'>
                        <b>Màu sắc</b>
        
                        <div class='mt-10'>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='color'>
                                <label for="color" class='form-check-label'>Đỏ</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='color'>
                                <label for="color" class='form-check-label'>Xanh dương</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='color'>
                                <label for="color" class='form-check-label'>Đen</label>
                            </div>
                        </div>
                    </div>
        
                    {{-- dung lượng --}}
                    <div class='d-flex flex-column'>
                        <b>Dung lượng</b>
        
                        <div class='mt-10'>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>32 GB</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>64 GB</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>128 GB</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>256 GB</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>512 GB</label>
                            </div>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='capacity'>
                                <label for="capacity" class='form-check-label'>1 TB</label>
                            </div>
                        </div>
                    </div>
        
                    {{-- khuyến mãi --}}
                    <div class='d-flex flex-column'>
                        <b>Khuyến mãi</b>
        
                        <div class='mt-10'>
                            <div class="input group filter-item">
                                <input type="checkbox" class='form-check-input' name='promotion'>
                                <label for="promotion" class='form-check-label'>Sản phẩm có khuyến mãi</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- sắp xếp --}}
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sắp xếp</a>
            <div class="dropdown-menu dropdown-menu-end" style='padding: 20px; border-radius: 10px'>
                <div class='d-flex flex-row justify-content-lg-between'>
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