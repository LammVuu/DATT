<!DOCTYPE html>
<html lang="en">
@include('user.header.head')
@include('user.header.header')
<body>
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-10 mx-auto box-shadow p-40">
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column justify-content-between pr-20 pb-20">
                            {{-- title --}}
                            <div class="fz-50 fw-600 main-color-text text-end">Liên hệ</div>

                            {{-- hình minh họa --}}
                            <img src="images/customer-service-cartoon.png" alt="" class="center-img pt-40 pb-40">
                                
                            {{-- icon --}}
                            <div class="d-flex fz-20">
                                <a href="#" class="main-color-text"><i class="fab fa-facebook-f ml-10 mr-10"></i></a>
                                <a href="#" class="main-color-text"><i class="fab fa-instagram ml-10 mr-10"></i></a>
                                <a href="#" class="main-color-text"><i class="fab fa-twitter ml-10 mr-10"></i></a>
                                <a href="#" class="main-color-text"><i class="fab fa-pinterest-p ml-10 mr-10"></i></a>
                                <a href="#" class="main-color-text"><i class="fab fa-whatsapp ml-10 mr-10"></i></a>
                            </div>
                        </div>
                        {{-- form --}}
                        <div class="col-md-6">
                            <form action="#">
                                @csrf
                                <div class="p-20">
                                    <div class="mb-20">
                                        <input type="text" id="HoTen" placeholder="Nhập họ và tên">
                                    </div>
                                    <div class="mb-20">
                                        <input type="email" id="Email" placeholder="Nhập Email của bạn">
                                    </div>
                                    <div class="mb-20">
                                        <textarea id="message" rows="4" placeholder="Nhập nội dung"></textarea>
                                    </div>
                                    <div type='button' class="main-btn p-10">Gửi</div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('user.footer.footer-link')
</body>
</html>