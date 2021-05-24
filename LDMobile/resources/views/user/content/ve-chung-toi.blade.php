<!DOCTYPE html>
<html lang="en">
    @include('user.header.head')
    @include('user.header.header')
<body>
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row box-shadow">
                <div class="col-md-6 p-20">
                    <div class="fz-32 font-weight-600 main-color-text">Về chúng tôi</div>
                </div>
                <div class="col-md-6 p-20">
                    <div id="carouselExampleIndicators" class="relative carousel carousel-dark slide" data-bs-ride="carousel">
                        <div class="carousel-inner box-shadow">
                            <div class="carousel-item active">
                                <img src="images/header-1/header-big-1.jpg" class='carousel-img' alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="images/header-1/header-big-2.jpg" class='carousel-img' alt="...">
                            </div>
                        </div>
                        <div class="slideshow-btn-prev" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <i class="far fa-chevron-left fz-30"></i>
                        </div>
                        <div class='slideshow-btn-next' data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <i class="far fa-chevron-right fz-30"></i>
                        </div>
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('user.footer.footer-link')
</body>
</html>