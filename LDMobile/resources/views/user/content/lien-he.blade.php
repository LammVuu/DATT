<!DOCTYPE html>
<html lang="en">
@section("title")Liên hệ | LDMobile @stop
@include('user.header.head')
@include('user.header.header')
<body>
    <section class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-10 mx-auto box-shadow p-40">
                    {{-- title --}}
                    <div class="fz-50 fw-600 main-color-text text-center mb-40">Liên hệ</div>
                    
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column justify-content-between">
                            {{-- hình minh họa --}}
                            <img src="images/customer-service-cartoon.png" alt="" class="center-img pb-40">
                                
                            {{-- icon --}}
                            <div class="d-flex fz-20">
                                <div class="main-color-text"><i class="fab fa-facebook-f ml-10 mr-10"></i></div>
                                <div class="main-color-text"><i class="fab fa-instagram ml-10 mr-10"></i></div>
                                <div class="main-color-text"><i class="fab fa-twitter ml-10 mr-10"></i></div>
                                <div class="main-color-text"><i class="fab fa-pinterest-p ml-10 mr-10"></i></div>
                                <div class="main-color-text"><i class="fab fa-whatsapp ml-10 mr-10"></i></div>
                            </div>
                        </div>
                        {{-- form --}}
                        <div class="col-md-6">
                            <div class="mb-20">
                                <div class="fw-600 fz-20 mb-10">Tổng đài hỗ trợ</div>
                                <ul>
                                    <li>Gọi mua: 077 979 2000</li>
                                    <li>Kỹ thuật: 038 415 1501</li>
                                </ul>
                            </div>
                            <div class="mb-20">
                                <div class="fw-600 fz-20 mb-10">Chi nhánh</div>
                                <ul>
                                    @foreach ($lst_branch as $branch)
                                        <li>{{$branch->diachi}}</li>
                                    @endforeach
                                </ul>
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