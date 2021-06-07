<section class='index-bg pt-20 pb-20'>
    <div class='container'>
        <div class='row'>
            <div class='col-lg-8 col-md-12 col-sm-12'>
                <div id="carouselExampleIndicators" class="relative carousel carousel-dark slide" data-bs-ride="carousel">
                    <div class="carousel-inner box-shadow">
                        <a href="#" class="carousel-item active">
                            <img src="<?php echo $url_slide.$lst_slide[0]['hinhanh'] ?>" class='carousel-img' alt="...">
                        </a>    
                        @for($i = 1; $i < count($lst_slide); $i++)
                            <a href="#" class="carousel-item">
                                <img src="<?php echo $url_slide.$lst_slide[$i]['hinhanh'] ?>" class='carousel-img' alt="...">
                            </a>    
                        @endfor
                    </div>
                    <div class="slideshow-btn-prev" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <i class="far fa-chevron-left fz-30"></i>
                    </div>
                    <div class='slideshow-btn-next' data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <i class="far fa-chevron-right fz-30"></i>
                    </div>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                            aria-current="true"></button>
                        @for ($i = 1; $i < $qty_slide; $i++)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i ?>"></button>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- banner --}}
            <div class='col-lg-4 col-md-12 col-sm-12'>
                <div class='banner'>
                    <div class='d-flex flex-column'>
                        @foreach ($lst_banner as $key)
                        <a href="{{ $key['link'] }}">
                            <div class='mb-20 box-shadow'>
                                <img src="{{ $url_banner.$key['hinhanh'] }}" class='single-banner' alt="banner-1">
                            </div>
                        </a>    
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>