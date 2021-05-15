<!doctype html>
<html lang="en">

@include("user.header.head")
<body>
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-10 mx-auto box-shadow">
                    <div class="d-flex flex-column">
                        <img id='success-img' src="images/check.gif" alt="" class="center-img">
                    </div>
                    <div id='success-checkout'>
                        <div class="text-center font-weight-600 fz-20">Cảm ơn bạn đã mua hàng tại LDMobile</div>
                        <div class="row p-50">
                            <div class="col-md-5">
                                <div class="fz-14">Mã đơn hàng của bạn</div>
                                <div class="d-flex">
                                    <div class="order-id">123456</div>
                                </div>
                                <div class="fz-14">Bạn có thể xem lại <a href="#">Đơn hàng của tôi</a></div>
                            </div>
                            <div class="col-md-2 d-flex justify-content-center">
                                <div class="vl"></div>
                            </div>
                            <div class="col-md-5 d-flex justify-content-center align-items-center">
                                
                                <a href="#" class="main-btn p-5 w-100">Tiếp tục mua sắm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@include("user.footer.footer-link")
</body>
</html>