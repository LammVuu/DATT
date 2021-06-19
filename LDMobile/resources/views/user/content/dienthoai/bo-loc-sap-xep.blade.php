<div class="d-flex align-items-center justify-content-between box-shadow p-15 mb-20">
    <b id="qty-product">{{ $fs_title }}</b>
    <div class="d-flex">
        {{-- bộ lọc --}}
        <div class='relative mr-20'>
            <span id='btn-show-filter' data-bs-toggle="modal" data-bs-target="#filter-modal"><i class="fal fa-filter mr-5"></i>Bộ lọc</span>
            <div class="filter-badge"></div>
        </div>

        {{-- sắp xếp --}}
        <div class="relative">
            <span id='btn-show-sort'><i class="fal fa-sort mr-5"></i>Sắp xếp</span>
            <div class="shop-sort-box border">
                <div class="d-flex justify-content-center">
                    <div class='d-flex flex-column'>
                        <div class="mb-3">
                            <input type="radio" name='sort' id='high-to-low'>
                            <label for="high-to-low">Giá cao đến thấp</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" name='sort' id='low-to-high'>
                            <label for="low-to-high">Giá thấp đến cao</label>
                        </div>
                        <div>
                            <input type="radio" name='sort' id='sale-off-percent'>
                            <label for="sale-off-percent">% giảm</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal bộ lọc --}}
<div class="modal fade" id="filter-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-20">
                {{-- nút đóng --}}
                <button class="btn-close" data-bs-dismiss="modal"></button>
                {{-- hãng --}}
                <div class="filter-title">Hãng</div>
                <div class="d-flex align-items-center flex-wrap">
                    @foreach ($lst_brand as $key)
                        <div type="button" name="filter-item" id="brand_{{ $key['brand'] }}" data-data="brand_{{ $key['brand'] }}" class="filter-item brand filter-brand">{{ $key['brand'] }}</div>
                    @endforeach
                </div><hr>

                {{-- giá, hệ điều hành --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="filter-title">Giá</div>
                        <div class="d-flex align-items-center flex-wrap">
                            <div id="price_2" type="button" name="filter-item" data-data="price_2" class="filter-item">Dưới 2 triệu</div>
                            <div id="price_3-4" type="button" name="filter-item" data-data="price_3-4" class="filter-item">Từ 3 - 4 triệu</div>
                            <div id="price_4-7" type="button" name="filter-item" data-data="price_4-7" class="filter-item">Từ 4 - 7 triệu</div>
                            <div id="price_7-13" type="button" name="filter-item" data-data="price_7-13" class="filter-item">Từ 7 - 13 triệu</div>
                            <div id="price_13-20" type="button" name="filter-item" data-data="price_13-20" class="filter-item">Từ 13 - 20 triệu</div>
                            <div id="price_20" type="button" name="filter-item" data-data="price_20" class="filter-item">Trên 20 triệu</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-title">Hệ điều hành</div>
                        <div class="d-flex align-items-center flex-wrap">
                            <div id="os_Android" type="button" name="filter-item" data-data="os_Android" class="filter-item">Android</div>
                            <div id="os_iOS" type="button" name="filter-item" data-data="os_iOS" class="filter-item">iOS</div>
                        </div>
                    </div>
                </div><hr>

                {{-- ram, dung lượng --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="filter-title">Ram</div>
                        <div class="d-flex align-items-center flex-wrap">
                            @foreach ($lst_ram as $key)
                            <div id="ram_{{ explode(' ', $key)[0].explode(' ', $key)[1] }}" type="button" name="filter-item" data-data="ram_{{ explode(' ', $key)[0].explode(' ', $key)[1] }}" class="filter-item">{{ $key }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-title">Dung lượng</div>
                        <div class="d-flex align-items-center flex-wrap">
                            @foreach ($lst_capacity as $key)
                            <div id="capacity_{{ explode(' ', $key)[0].explode(' ', $key)[1] }}" type="button" name="filter-item" data-data="capacity_{{ explode(' ', $key)[0].explode(' ', $key)[1] }}" class="filter-item">{{ $key }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class='see-result-filter pt-20'>
                    <div id='btn-see-filter' class="main-btn p-10"></div>
                    <div class='shop-btn-remove-filter pt-10'>Bỏ chọn tất cả</div>
                </div>
            </div>
        </div>
    </div>
</div>