
//===== Preloader
$(window).on('load', function(){
    $('.loader').fadeOut(250);
});

$(function() {
    var url = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];

    // hiển thị button cuộn lên đầu
    $(window).scroll(function(e){
        var scrollTop = $(window).scrollTop();
        var docHeight = $(document).height();
        var winHeight = $(window).height();
        var scrollPercent = (scrollTop) / (docHeight - winHeight);
        var scrollPercentRounded = Math.round(scrollPercent*100);

        if(scrollPercentRounded >= 20){
            $('#btn-scroll-top').css({
                '-ms-transform' : 'translateY(0)',
                'transform' : 'translateY(0)',
            });
        } else {
            $('#btn-scroll-top').css({ 
                '-ms-transform' : 'translateY(100px)',
                'transform' : 'translateY(100px)',
            });
        }
    });

    // xử lý cuộn lên đầu trang
    $('#btn-scroll-top').on('click', function(){
        $(window).scrollTop(0);
    });

    // slide logo các hãng
    $('#logo-carousel').owlCarousel({
        items:5,
        margin: 40,
        loop:true,
        dots:false,
        nav: false,
        autoplay:true,
        autoplayTimeout:1500,
        autoplayHoverPause:true,
        smartSpeed: 1000,
    });

/*============================================================================================================================
                                                            Login
=============================================================================================================================*/    

    // đăng nhập
    $('#btn-login').click(function(){
        var telInp = $('#login_tel');
        var pwInp = $('#login_pw');
        var valiLogin = validateLogin(telInp, pwInp);

        if(valiLogin){
            $('#login-form').submit();
        }
    });

    function validateLogin(telInp, pwInp){
        if(telInp.hasClass('required') || pwInp.hasClass('required')){
            return;
        }

        var phoneno = /^\d{10}$/;

        // chưa nhập sdt
        if(telInp.val().length == 0){
            telInp.addClass('required');
            var required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            telInp.after(required);
            return false;
        } else if(!telInp.val().match(phoneno)){ // không đúng định dạng
            var required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');
            telInp.addClass('required');
            telInp.after(required);
            return false;
        }

        // chưa nhập mật khẩu
        if(pwInp.val().length == 0){
            pwInp.addClass('required');
            var required = $('<span class="required-text">Vui lòng nhập mật khẩu</span>');
            pwInp.after(required);
            return false;
        }

        return true;
    }

    $('#login_tel').keyup(function(){
        valiPhonenumberTyping($(this));
    });

    $('#login_pw').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

/*============================================================================================================================
                                                            Sign Up
=============================================================================================================================*/
    
    // cấu hình firebase
    if(url == 'dangky'){
        var firebaseConfig = {
            apiKey: "AIzaSyBmuQyKi5Xer3D4hFYHMkYTMx0Jb3Bcgrs",
            authDomain: "ldmobileauth-eb072.firebaseapp.com",
            projectId: "ldmobileauth-eb072",
            storageBucket: "ldmobileauth-eb072.appspot.com",
            messagingSenderId: "285654392090",
            appId: "1:285654392090:web:7dd64e8651ed9fdad660a3",
            measurementId: "G-YHSGQC11SQ"
        };

        // khởi tạo
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();
        firebase.auth().useDeviceLanguage();
        firebase.auth().signOut();

        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('signup-step-1', {
            'size': 'invisible',
            'callback': (response) => {
                sendVerifyCode();
            },
            'expired-callback': () => {
                location.reload();
            }
        });

        recaptchaVerifier.render().then(function(widgetId) {
            window.recaptchaWidgetId = widgetId;
        });
    }

    /*=====================================================
                        Nhập thông tin                  
    =======================================================*/
    // đăng ký tài khoản
    $('#signup-step-1').click(function(){
        sendVerifyCode();
    });

    // gửi mã xác nhận
    function sendVerifyCode(){
        var nameInp = $('#su_fullname');
        var telInp = $('#su_tel');

        // kiểm tra bẫy lỗi
        var valiPhone = validateInformationSignUp(nameInp, telInp);

        // sdt không hợp lệ
        if(!valiPhone){
            return;
        }  

        $('.loader').fadeIn();
        var tel = '+1' + telInp.val();
        var appVerifier = window.recaptchaVerifier;

        window.signingIn = true;

        // gửi mã thành công
        firebase.auth().signInWithPhoneNumber(tel, appVerifier).then(function (confirmationResult) {
            window.confirmationResult = confirmationResult;
            coderesult = confirmationResult;
            window.signingIn = false;

            // tiếp tục bước tiếp theo
            $('#enter-information').addClass('none-dp');

            // hiển thị gửi code vào số điện thoại
            var displayTel = tel.replace('+1','');
            $('#tel-confirm').text(displayTel);
            $('#enter-verify-code').removeClass('none-dp');

        }).catch(function (error) { // gửi mã thất bại
            console.log(error);
            window.signingIn = false;
            alert('Đã có lỗi xảy ra vui lòng thử lại')
            grecaptcha.reset(window.recaptchaWidgetId);
            location.reload();
        });
        $('.loader').fadeOut();
    }

    function validateInformationSignUp(nameInp, telInp){
        // nếu đã kiểm tra rồi thì return
        if(nameInp.hasClass('required') || telInp.hasClass('required')){
            return;
        }

        var phoneno = /^\d{10}$/;

        // chưa nhập họ tên
        if(nameInp.val() == ''){
            nameInp.addClass('required');
            var required = $('<span class="required-text">Vui lòng nhập họ và tên</span>');
            nameInp.after(required);
            return false;
        }

        // chưa nhập sdt
        if(telInp.val().length == 0){
            telInp.addClass('required');
            var required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            telInp.after(required);
            return false;
        } else if(!telInp.val().match(phoneno)){ // không đúng định dạng
            var required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');
            telInp.addClass('required');
            telInp.after(required);
            return false;
        }

        return true;
    }

    $('#su_fullname').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    // kiểm tra nhập số diện thoại
    $('#su_tel').keyup(function(){
        valiPhonenumberTyping($(this));
    });

    function valiPhonenumberTyping(telInp){
        if(telInp.hasClass('required')){
            telInp.next().remove();
        }

        var phoneno = /^\d{10}$/;
        var required; 

        // chưa nhập
        if(telInp.val() == ''){
            required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            telInp.addClass('required');
            telInp.after(required);
        } else if(!telInp.val().match(phoneno) || telInp.val().charAt(0) != 0){ // không đúng định dạng | ký tự đầu k phải số 0
            if(telInp.next().hasClass('required-text')){
                return;
            }
            required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');    
            telInp.addClass('required');
            telInp.after(required);
        } else { // xóa required
            telInp.removeClass('required');
            telInp.next().remove();
        }
    }

    /*=====================================================
                        Xác minh mã xác nhận                  
    =======================================================*/

    // quay lại bước nhập sdt
    $('#back-to-enter-tel').click(function(){
        // reset sdt
        $('#su-tel').val('');
        // reset reCAPTCHA
        grecaptcha.reset(window.recaptchaWidgetId);

        window.confirmationResult = null;

        // quay lại phần nhập sdt
        $('#enter-phone-number').removeClass('none-dp');
        $('#enter-verify-code').addClass('none-dp');
    });

    // Xác nhận code
    $('#signup-step-2').click(function(){
        var codeInput = $('#verify-code-inp');
        var valiVerfifyCode = validateVerifyCode(codeInput);

        // code không hợp lệ
        if(!valiVerfifyCode){
            return;
        } else {    // code hợp lệ
            codeVerify(codeInput);
        }
    });

    $('#verify-code-inp').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    // kiểm tra bẫy lỗi mã xác nhận
    function validateVerifyCode(codeInput){
        // nếu đã kiểm tra rồi thì return
        if(codeInput.hasClass('required')){
            return;
        }

        var code = codeInput.val().trim();

        // code hợp lệ
        if(code != '' && !isNaN(code) && code.length == 6){
            return true;
        }

        // reset nhập code
        codeInput.val('');

        // hiển thị thông báo lỗi
        codeInput.addClass('required');(errMessage);
        var errMessage = $('<div class="required-text text-center">Mã xác thực không hợp lệ, vui lòng kiểm tra lại</div>');
        codeInput.after(errMessage);
        return false;
    }

    function codeVerify(codeInput) {
        $('.loader').fadeIn();

        var code = codeInput.val();
    
        // xác nhận code hợp lệ
        coderesult.confirm(code).then(function (result) {
            window.verifyingCode = false;
            window.confirmationResult = null;

            // tạo mật khẩu
            $('#enter-verify-code').addClass('none-dp');
            $('#enter-password').removeClass('none-dp');

        }).catch(function (error) { // code không hợp lệ
            window.verifyingCode = false;
            console.log(error);

            // hiển thị thông báo lỗi
            codeInput.addClass('required');(errMessage);
            var errMessage = $('<div class="required-text text-center">Mã xác thực không hợp lệ, vui lòng kiểm tra lại</div>');
            codeInput.after(errMessage);
        });

        $('.loader').fadeOut();
    }

    /*=====================================================
                        Nhập mật khẩu
    =======================================================*/

    $('#signup-step-3').click(function(){
        var passwordInp = $('#su_pw');
        var rePasswordInp = $('#su_re_pw');
        var valiPw = validatePassword(passwordInp, rePasswordInp);

        if(valiPw){
            $('#signup-form').submit();
        }
    });

    function validatePassword(passwordInp, rePasswordInp){
        var pw = passwordInp.val();
        var rePw = rePasswordInp.val();

        if(passwordInp.hasClass('required') || rePasswordInp.hasClass('required')){
            return;
        }
        
        // thông tin hợp lệ
        if(pw != '' && rePw != '' && pw.localeCompare(rePw) == 0){
            return true;
        }

        // chưa nhập mật khẩu
        if(pw == ''){
            passwordInp.addClass('required');
            var errMess = $('<div class="required-text">Vui lòng nhập mật khẩu</div>');
            passwordInp.after(errMess);
            return false;
        }

        // chưa nhập lại mật khẩu
        if(rePw == ''){
            rePasswordInp.addClass('required');
            var errMess = $('<div class="required-text">Vui lòng nhập lại mật khẩu</div>');
            rePasswordInp.after(errMess);
            return false;
        }

        // nhập lại không khớp
        if(pw.localeCompare(rePw) != 0){
            rePasswordInp.addClass('required');
            var errMess = $('<div class="required-text">Nhập lại mật khẩu không trùng khớp</div>');
            rePasswordInp.after(errMess);
            return false;
        }
    }

    $('#su_pw').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    $('#su_re_pw').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

/*============================================================================================================================
                                                            Header
=============================================================================================================================*/

    // hiển thị offcanvas
    $('#show-offcanvas').on('click', function(){
        if($(this).attr('aria-expanded') == 'false'){
            $('.head-offcanvas-box').css({
                'width' : '50%',
            });
            $('.backdrop').fadeIn();
            $(this).attr('aria-expanded', 'true');
        } else {
            $('.head-offcanvas-box').css({
                'width' : '0',
            });
            $('.backdrop').fadeOut();
            $(this).attr('aria-expanded', 'false');
        }
    });

    // nút đóng offcanvas
    $('#btn-close-offcanvas').on('click', function(){
        $('.head-offcanvas-box').css({
            'width' : '0',
        });
        $('.backdrop').css('display', 'none');
        $('#show-offcanvas').attr('aria-expanded', 'false');
    });

    // đóng offcanvas theo kích thước màn hình
    $(window).resize(function(){
        if($(window).width() >= 1091) {
            $('.backdrop').fadeOut();
            $('.head-offcanvas-box').css({
                'width' : '0',
            });
            $('#show-offcanvas').attr('aria-expanded', 'false');
        }
    });

    // dropdown tài khoản trong offcanvas
    $('.head-offcanvas-account').hover(function(){
            $('.head-offcanvas-account-option').css('display', 'block');
    },function(){
            $('.head-offcanvas-account-option').css('display' ,'none');
    });

    // hiển thị giỏ hàng trên header
    $('.head-cart').hover(function(){
        $('.head-cart-box').css('display', 'block');
    }, function(){
        $('.head-cart-box').css('display', 'none');
    });

    // thanh tìm kiếm
    $('.head-search-input').focus(function(){
        $('.backdrop').fadeIn();
        if($('.head-search-input').val() != ''){
            $('.head-search-result').css('display', 'block');
        }
    });
    // $('.head-search-input').focusout(function(){
    //     $('.backdrop').fadeOut();
    //     $('.head-search-result').css('display', 'none');
    // });


    var enterKey = false;
    $('.head-search-input').keypress(function(e){
        if(e.keyCode == '13'){
            var val = $(this).val().toLowerCase().trim();
            enterKey = true;
            location.href = 'timkiem/' + val;
        }
    });

    var timer;
    $('.head-search-input').keyup(function(e){
        if(enterKey == true){
            return;
        }
        if($(this).val() == ''){
            $('.head-search-result').css('display', 'none');
            $('.head-search-result').children().remove();
        } else {
            clearTimeout(timer);
            timer = setTimeout(() =>{
                var val = $(this).val().toLowerCase().trim();
                if(val == ''){
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-search-phone',
                    type: 'POST',
                    cache: false,
                    data: {'str': val},
                    success:function(data){
                        $('.head-search-result').children().remove();
                        if(data['phone'].length == 0){
                            $('.head-search-result').css('display', 'none');
                            return;
                        }

                        var count = data['phone'].length;
                        for(var i = 0; i < count; i++){
                            var phone = $('<a href="dienthoai/'+ data['phone'][i]['tensp_url'] +'" class="head-single-result black fz-14">' +
                                            '<div class="d-flex">' +
                                                '<div class="w-25 p-10">' +
                                                    '<img src="'+ data['url_phone'] + data['phone'][i]['hinhanh'] +'" alt="">' +
                                                '</div>' +
                                                '<div class="d-flex flex-column w-75 p-10">' +
                                                    '<b>'+ data['phone'][i]['tensp'] +'</b>' +
                                                    '<div class="d-flex align-items-center mt-5">' +
                                                        '<span class="price-color font-weight-600">'+ data['phone'][i]['gia'] +'<sup>đ</sup></span>' +
                                                        '<span class="text-strike ml-10">'+ data['phone'][i]['giakhuyenmai'] +'<sup>đ</sup></span>' +
                                                        '<span class="price-color ml-10">-'+ (data['phone'][i]['khuyenmai'] * 100) + '%</span>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</a>');
                                
                            phone.appendTo('.head-search-result');
                        }
                        $('.head-search-result').scrollTop(0);

                        $('.head-search-result').css('display', 'block');
                    }
                });
            }, 300);
        }
    });

/*============================================================================================================================
                                                            Index
=============================================================================================================================*/

    // alert thông báo
    if($('#alert-message').length){
        var toast = $('<div id="message" class="alert-toast"><div class="d-flex align-items-center"><span>'+ $('#alert-message').data('message') +'</span></div></div>');
        $("#alert-message").after(toast);
        showToast('#message');
        $('#alert-message').remove();
    }

    // sec khuyến mãi
    var owl_promotion = $('#index-promotion-carousel');
    owl_promotion.owlCarousel({
        nav: false,
        rewind: true,
        dots: false,
        responsiveClass:true,
        responsive: {
            0: {
                items: 5
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            },
            1200: {
                items: 5
            }
        }
    });

    $('#prev-index-promotion').on('click', function(){
        owl_promotion.trigger('prev.owl.carousel', [300]);
    });

    $('#next-index-promotion').on('click', function(){
        owl_promotion.trigger('next.owl.carousel');
    });

/*============================================================================================================================
                                                            Account
=============================================================================================================================*/

    //==================================================================================
    //============================== thông tin tài khoản ===============================
    //==================================================================================
    var modal_avt = $('#change-avt');
    var modal_cover = $('#change-cover');
    var typeModal;
    var image;
    var cropper_avt;
    var cropper_cover;
    var zoom_range = document.getElementById('zoom-range');
    var zoom_in = 0, zoom_out = 0;

    // thay đổi ảnh đại diện
    $('#btn-change-avt').click(function(){
        $('#change-avt-inp').trigger('click');
    });

    // thay đổi ảnh bìa
    $('#btn-change-cover').click(function(){
        $('#change-cover-inp').trigger('click');
    });

    // hiển thị modal tiến hành cắt ảnh dại diện
    $('#change-avt-inp').change(function(e){
        typeModal = $(this).data('modal');
        image = document.getElementById('pre-avt-big');
        startCropImg(cropper_avt, e);
    });

    // hiển thị modal tiến hành cắt ảnh bìa
    $('#change-cover-inp').change(function(e){
        typeModal = $(this).data('modal');
        image = document.getElementById('pre-cover-big');
        startCropImg(cropper_cover, e);
    });

    function startCropImg(cropper, e){
        var files = e.target.files;

        var modal;
        // gán biến modal = modal_avt nếu loại modal = avt và ngược lại
        typeModal == 'avt' ? modal = modal_avt : modal = modal_cover;


        // hiển thị hình ảnh
        if(files && files.length > 0){
            var reader = new FileReader();
            reader.onload = function(){
                if(cropper != null){
                    cropper.replace(reader.result);
                    return;
                } else {
                    image.src = reader.result;
                    modal.modal('show');
                }
            };
            reader.readAsDataURL(files[0]); 
        }
    }

    // xử lý tạo mới cropper khi modal avt hiển thị
    modal_avt.on('shown.bs.modal', function(){    
        cropper_avt = new Cropper(image, {
            aspectRatio: 1/1,
            viewMode: 1,
            responsive: true,
            dragMode: 'move',
            preview: '.preview-avt',
            zoomOnWheel: false,
            toggleDragModeOnDblclick: false,
        });
    }).on('hidden.bs.modal', function(){
        cropper_avt.destroy();
        cropper_avt = null;
        $('#change-avt-inp').val(null);
    }); // xử lý hủy cropper khi modal avt ẩn

    // xử lý tạo mới cropper khi modal cover hiển thị
    modal_cover.on('shown.bs.modal', function(){    
        cropper_cover = new Cropper(image, {
            viewMode: 1,
            responsive: true,
            preview: '.preview-cover',
            zoomOnWheel: false,
            toggleDragModeOnDblclick: false,
            cropBoxResizable: false,
            movable: false,
            dragMode: 'none',
            ready(){
                cropper_cover.setCropBoxData({
                    "left":0, "top":0,"width":800,"height":150
                });
            }
        });
    }).on('hidden.bs.modal', function(){
        cropper_cover.destroy();
        cropper_cover = null;
        $('#change-cover-inp').val(null);
    }); // xử lý hủy cropper khi modal cover ẩn

    // reset crop img avt
    $('#reset-canvas').click(function(){
        cropper_avt.reset();
        refreshZoomVal();
    });

    // chọn ảnh khác
    $('.reselect-img').click(function(){
        var type_modal = $(this).data('modal');

        if(type_modal == 'avt'){
            $('#change-avt-inp').trigger('click');
            // modal_avt.modal('hide');
        } else {
            $('#change-cover-inp').trigger('click');
            // modal_cover.modal('hide');
        }
        
        refreshZoomVal();
    });

    // cắt ảnh
    $(".crop-img").click(function(){
        // cắt avt/cover img
        var type = $(this).data('crop');
        var canvas, modal, cropper;
        
        if(type == 'avt'){
            modal = $('#change-avt');
            cropper = cropper_avt;
            canvas = cropper_avt.getCroppedCanvas({
                width: 320,
                height: 320
            });
        } else {
            modal = $('#change-cover');
            cropper = cropper_cover;
            canvas = cropper_cover.getCroppedCanvas({
                width: 800,
                height: 250
            });
        }

        canvas.toBlob(function(blob){
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/test',
                    type: 'POST',
                    cache: false,
                    data: {'image':base64data},
                    success:function(data){
                        modal.modal('hide');
                        if(type == 'avt'){
                            $('#avt-img').attr('src', data);
                            var toast = $('<div id="avt-toast" class="alert-toast"><div class="d-flex align-items-center"><span>Cập nhật ảnh đại diện thành công</span></div></div>');
                            $("#toast").after(toast);
                            showToast('#avt-toast');
                            refreshZoomVal();
                        } else {
                            $('.account-cover-img').css('background-image', 'url(' + data + ')');
                            var toast = $('<div id="cover-toast" class="alert-toast"><div class="d-flex align-items-center"><span>Cập nhật ảnh bìa thành công</span></div></div>');
                            $("#toast").after(toast);
                            showToast('#cover-toast');
                        }
                    }
                });
            };
        });
    });

    // thanh thu/phóng
    if($("#zoom-range").length){
        zoom_range.oninput = function(){
            var val = this.valueAsNumber;
    
            if(val == 0){
                // đang phóng lớn
                if(zoom_in == 0){
                    cropper_avt.zoom(0.1);
                    zoom_out = 0;
                } else { // đang thu nhỏ
                    cropper_avt.zoom(-0.1);
                    zoom_in = 0;
                }
            } else if(val < 0){ // thu nhỏ
                zoom_in = 0;
                if(zoom_out > val){
                    zoom_out = val;
                    cropper_avt.zoom(-0.1);
                } else {
                    zoom_out = val;
                    cropper_avt.zoom(0.1);
                }
            } else { // phóng lớn
                if(zoom_in < val){
                    zoom_in = val;
                    cropper_avt.zoom(0.1);
                } else {
                    zoom_in = val;
                    cropper_avt.zoom(-0.1);
                }
            }
        }   
    }

    // hàm gán lại giá trị ban đầu của biến
    function refreshZoomVal(){
        $('#zoom-range').val(0);
        zoom_in = 0, zoom_out = 0;
    }

    // nút đóng toast
    $('.btn-close-toast').click(function(){
        $('.alert-toast').css({
            'transform': 'translateY(100px)'
        });
    });

    // hiển thị toast
    function showToast(id){
        setTimeout(() => {
            setTimeout(() => {
                // xóa toast
                setTimeout(() => {
                    $(id).remove();
                },100);
    
                $(id).css({
                    'transform': 'translateY(100px)'
                });
            }, 3000);
    
            $(id).css({
                'transform': 'translateY(0)'
            });
        }, 200);
    }

    // hiển thị form đổi mật khẩu
    $('#btn-change-pw').click(function(){
        $('#btn-close-change-pw').show();
        $('#change-pw-div').show('blind', 250);
    });

    // đóng form đổi mật khẩu
    $('#btn-close-change-pw').click(function(){
        $(this).hide();
        $('#change-pw-div').hide('blind', 250);
    });

    // thay đổi thông tin tài khoản
    $('#btn-change-info').click(function(){
        $('#change-info').modal('show');
    });

    // hiển thị tỉnh thành
    $('#TinhThanh-selected').click(function(){
        $('#TinhThanh-box').toggle('blind', 250);
        $('#QuanHuyen-box').hide('blind', 250);
        $('#PhuongXa-box').hide('blind', 250);
    });

    // hiển thị quận huyện
    $('#QuanHuyen-selected').click(function(){
        $('#QuanHuyen-box').toggle('blind', 250);
        $('#TinhThanh-box').hide('blind', 250);
        $('#PhuongXa-box').hide('blind', 250);
    });

    // hiển thị phường xã
    $('#PhuongXa-selected').click(function(){
        if($(this).attr('class') == 'select-disable'){
            return;
        }
        $('#PhuongXa-box').toggle('blind', 250);
        $('#QuanHuyen-box').hide('blind', 250);
        $('#TinhThanh-box').hide('blind', 250);
    });

    // tìm kiếm tỉnh/thành
    $('#search-tinh-thanh').keyup(function(){
        var val = $(this).val();
        var selectBox = $('#list-tinh-thanh');
        
        searchPlace(val, selectBox);
    });

    // tìm kiếm quận huyện
    $('#search-quan-huyen').keyup(function(){
        var val = $(this).val();
        var selectBox = $('#list-quan-huyen');
        
        searchPlace(val, selectBox);
    });

    // tìm kiếm phường xã
    $('#search-phuong-xa').keyup(function(){
        var val = $(this).val();
        var selectBox = $('#list-phuong-xa');
        
        searchPlace(val, selectBox);
    });

    function searchPlace(val, selectBox){
        if(val == ''){
            selectBox.children().show();    
            return;
        }

        val = val.toLocaleLowerCase();

        var count = selectBox.children().length;
        
        for(var i = 0; i < count; i++){
            var element = selectBox.children()[i];
            var name = $(element).data('type').split('/')[0].toLocaleLowerCase();
            
            if(!name.includes(val)){
                $(element).hide();
            } else {
                $(element).show();
            }
        }
    }

    // thay đổi tỉnh/thành
    $('.option-tinhthanh').click(function(){
        var id = $(this).attr('id');
        var name = $(this).data('type').split('/')[0];
        var type = $(this).data('type').split('/')[1];

        choosePlace(id, name, type);
    });

    // thay đổi quận, huyện
    $('.option-quanhuyen').click(function(){
        var id = $(this).attr('id');
        var name = $(this).data('type').split('/')[0];
        var type = $(this).data('type').split('/')[1];

        choosePlace(id, name, type);
    });

    $('#list-quan-huyen').bind('DOMSubtreeModified', function(){
        $('.option-quanhuyen').off('click').click(function(){
            var id = $(this).attr('id');
            var name = $(this).data('type').split('/')[0];
            var type = $(this).data('type').split('/')[1];
            choosePlace(id, name, type);
        });
    });

    // thay đổi phường, xã
    $('#list-phuong-xa').bind('DOMSubtreeModified', function(){
        $('.option-phuongxa').off('click').click(function(){
            var id = $(this).attr('id');
            var name = $(this).data('type').split('/')[0];
            var type = $(this).data('type').split('/')[1];
            choosePlace(id, name, type);
        });
    });

    function choosePlace(id, name, type){
        if(type == 'TinhThanh'){
            $('#TinhThanh-name').text(name);
            $('#TinhThanh-box').toggle('blind', 250);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/test2',
                type: 'POST',
                cache: false,
                data: {'type':type,'id':id},
                success:function(data){
                    $('#QuanHuyen-box').toggle('blind', 250);
                    $('#list-quan-huyen').children().remove();
                    for(var i = 0; i < data.length; i++){
                        var div = $('<div>',{
                            id: data[i]['ID'],
                            'data-type': data[i]['Name'] + '/QuanHuyen',
                            class: 'option-quanhuyen select-single-option',
                            text: data[i]['Name']
                        });
                        div.appendTo($('#list-quan-huyen'));
                    }
                }
            });
        } else if(type == 'QuanHuyen') {
            $('#QuanHuyen-name').text(name);
            $('#QuanHuyen-name').attr('data-flag', '1');
            if($('#QuanHuyen-name').parent().hasClass('required')){
                $('#QuanHuyen-name').parent().removeClass('required');
                $('#QuanHuyen-name').parent().next().hide();
            }
            $('#QuanHuyen-box').toggle('blind', 250);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/test2',
                type: 'POST',
                cache: false,
                data: {'type':type,'id':id},
                success:function(data){
                    $('#PhuongXa-selected').addClass('select-selected').removeClass('select-disable');
                    $('#PhuongXa-box').toggle('blind', 250);
                    $('#list-phuong-xa').children().remove();
                    for(var i = 0; i < data.length; i++){
                        var div = $('<div>',{
                            id: data[i]['ID'],
                            'data-type': data[i]['Name'] + '/PhuongXa',
                            class: 'option-phuongxa select-single-option',
                            text: data[i]['Name']
                        });
                        div.appendTo($('#list-phuong-xa'));
                    }
                }
            });
        } else {
            $('#PhuongXa-name').text(name);
            $('#PhuongXa-name').attr('data-flag', '1');
            if($('#PhuongXa-name').parent().hasClass('required')){
                $('#PhuongXa-name').parent().removeClass('required');
                $('#PhuongXa-name').parent().next().hide();
            }
            $('#PhuongXa-box').toggle('blind', 250);
            $('#address-inp').focus();
        }
    }
    
    $('#address-inp').focus(function(){
        $('#TinhThanh-box').hide('blind', 250);
        $('#QuanHuyen-box').hide('blind', 250);
        $('#PhuongXa-box').hide('blind', 250);
    });

    //========================================================================
    //============================== thông báo ===============================
    //========================================================================

    // đánh dấu đã đọc
    $('.noti-btn-read').click(function(){
        var id = $(this).data('id');
        var td_content = '#noti-content-' + id;

        $(td_content).removeClass('w-70');
        $(td_content).parent().addClass('account-noti-checked').removeClass('account-noti-wait');
        $(td_content).attr('colspan', '2');

        // xóa nút "đánh dấu đã đọc"
        $(this).parent('td').remove();
    });

    // xóa thông báo
    $('.noti-btn-delete').click(function(){
        $(this).parent().parent().remove();    
    });

    // đánh dấu đọc tất cả
    $('#noti-btn-read-all').click(function(){
        $('.noti-btn-read').each(function(){
            $(this).trigger('click');
        });
    });

    // xóa tất cả thông báo
    $('#noti-btn-delete-all').click(function(){
        $('.noti-btn-delete').each(function(){
            $(this).trigger('click');
        });
    });

    // nút 3 chấm trang tài khoản - thông báo
    $('.account-btn-option').on('click', function(){
        if($(this).attr('aria-expanded') == 'false'){
            $('.account-option-div').css('display', 'block');
            $(this).attr('aria-expanded', 'true');
        } else {
            $('.account-option-div').css('display', 'none');
            $(this).attr('aria-expanded', 'false');
        }
    });

    //===================================================================================
    //============================== điện thoại yêu thích ===============================
    //===================================================================================

    // xóa điện thoại yêu thích
    $('.fav-btn-delete').click(function(){
        $(this).parent().parent().parent().hide('drop');

        setTimeout(() => {
            $(this).parent().parent().parent().remove();
        }, 500);
    });

    // xóa tất cả điện thoại yêu thích
    $('#fav-btn-delete-all').click(function(){
        $('.fav-btn-delete').each(function(){
            $(this).trigger('click');
        });
    });

    //=======================================================================
    //============================== đơn hàng ===============================
    //=======================================================================

    // xem chi tiết sản phẩm theo mã hóa đơn
    $('.show-order-list-pro').off('click').on('click', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        if($(this).attr('aria-expanded') == 'true'){
            $('#temp_' + id).css({
                'visibility' : 'hidden',
            });
            $(this).html('Ẩn bớt');
        } else {
            $('#temp_' + id).css({
                'visibility' : 'visible',
            });
            $(this).html('Xem chi tiết');
        }
    })

    //================================================================================
    //============================== chi tiết đơn hàng ===============================
    //================================================================================

    // tùy chỉnh độ cao
    if($('#DCNN-div').length){
       var height = $('#DCNN-div').height();
       $('#HTGH-div').css('height', height);
       $('#HTTT-div').css('height', height);
    }

    //=========================================================================
    //============================== sổ địa chỉ ===============================
    //=========================================================================

    // thêm địa chỉ giao hàng mới
    $('.btn-new-address').click(function(){
        $('#new-address-modal').modal('show');
    });

    $('#new-address-modal').on('hidden.bs.modal', function(){
        $('#TinhThanh-box').hide();
        $('#QuanHuyen-box').hide();
        $('#PhuongXa-box').hide();
    });

    // xóa 1 địa chỉ
    $('.btn-remove-address').click(function(){
        $('#confirm-modal').modal('show');
    });

/*============================================================================================================================
                                                            Shop
=============================================================================================================================*/

    // nút hiển thị sắp xếp
    $('#btn-show-sort').click(function(){
        $('.shop-sort-box').toggle('blind', 250);
        $('.shop-filter-box').hide();
    });

    // gỡ bỏ chọn tất cả bộ lọc
    $('.shop-btn-remove-filter').click(function(){
        // icon loading
        var loading = $('<div class="spinner-border text-light" role="status" style="width: 20px; height: 20px"></div>');
        $('#btn-see-filter').text('');
        loading.appendTo($('#btn-see-filter'));

        dataFilter = {};
        $('div[name="filter-item"]').removeClass('filter-selected');
        filterProduct(dataFilter);
    });

    var dataFilter = {};

    // thêm | xóa bộ lọc
    $('.filter-item').off('click').click(function(e){
        e.preventDefault();

        // icon loading
        var loading = $('<div class="spinner-border text-light" role="status" style="width: 20px; height: 20px"></div>');
        $('#btn-see-filter').text('');
        loading.appendTo($('#btn-see-filter'));
                        
        var type = $(this).data('data').split('_')[0];
        var keyword = $(this).data('data').split('_')[1];
        var elmnt = $('#' + $(this).data('data'));

        // hủy 1 bộ lọc
        if($(elmnt).hasClass('filter-selected')){
            if(type == 'brand'){
                const index = dataFilter['brand'].indexOf(keyword);
                if (index > -1) {
                    dataFilter['brand'].splice(index, 1);
                }
                if(dataFilter['brand'].length == 0){
                    delete dataFilter['brand'];
                }
                filterProduct(dataFilter);
                $(elmnt).removeClass('filter-selected');
            } else if(type == 'price'){
                const index = dataFilter['price'].indexOf(keyword);
                if (index > -1) {
                    dataFilter['price'].splice(index, 1);
                }
                if(dataFilter['price'].length == 0){
                    delete dataFilter['price'];
                }
                filterProduct(dataFilter);
                $(elmnt).removeClass('filter-selected');
            } else if(type == 'os'){
                const index = dataFilter['os'].indexOf(keyword);
                if (index > -1) {
                    dataFilter['os'].splice(index, 1);
                }
                if(dataFilter['os'].length == 0){
                    delete dataFilter['os'];
                }
                filterProduct(dataFilter);
                $(elmnt).removeClass('filter-selected');
            } else if(type == 'ram'){
                const index = dataFilter['ram'].indexOf(keyword);
                if (index > -1) {
                    dataFilter['ram'].splice(index, 1);
                }
                if(dataFilter['ram'].length == 0){
                    delete dataFilter['ram'];
                }
                filterProduct(dataFilter);
                $(elmnt).removeClass('filter-selected');
            } else if(type == 'capacity') {
                const index = dataFilter['capacity'].indexOf(keyword);
                if (index > -1) {
                    dataFilter['capacity'].splice(index, 1);
                }
                if(dataFilter['capacity'].length == 0){
                    delete dataFilter['capacity'];
                }
                filterProduct(dataFilter);
                $(elmnt).removeClass('filter-selected');
            }
        } else { // thêm bộ lọc
            if(type == 'brand'){
                addFilter(dataFilter, 'brand', keyword);
                filterProduct(dataFilter);
                $(elmnt).addClass('filter-selected');
            } else if(type == 'price'){
                addFilter(dataFilter, 'price', keyword);
                filterProduct(dataFilter);
                $(elmnt).addClass('filter-selected');
            } else if(type == 'os'){
                addFilter(dataFilter, 'os', keyword);
                filterProduct(dataFilter);
                $(elmnt).addClass('filter-selected');
            } else if(type == 'ram'){
                addFilter(dataFilter, 'ram', keyword);
                filterProduct(dataFilter);
                $(elmnt).addClass('filter-selected');
            } else if(type == 'capacity') {
                addFilter(dataFilter, 'capacity', keyword);
                filterProduct(dataFilter);
                $(elmnt).addClass('filter-selected');
            }
        }
    });

    function addFilter(dataFilter, filter, keyword){
        if(dataFilter[filter] == null){
            dataFilter[filter] = [];
        }
        dataFilter[filter].push(keyword);
    }

    function filterProduct(dataFilter){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/ajax-filter-product',
            type: 'POST',
            data: {dataFilter:dataFilter},
            success: function(data){
                // số tiêu chí lọc
                if(Object.keys(dataFilter).length == 0){
                    $('.filter-badge').text('');
                    $('.filter-badge').css('display', 'none');    
                } else {
                    $('.filter-badge').text(Object.keys(dataFilter).length);
                    $('.filter-badge').css('display', 'block');
                }
                
                $('.see-result-filter').css('display', 'block');
                $('#btn-see-filter').text('Xem ' + data.length + ' kết quả');

                if(data.length == 0){
                    $('#btn-see-filter').attr('enable', 'false');
                } else {
                    $('#btn-see-filter').attr('enable', 'true')
                }
                //console.log(data);

                // xem danh sách kết quả lọc
                $('#btn-see-filter').click(function(){
                    if($(this).attr('enable') == 'false'){
                        return;
                    }

                    $('#filter-modal').modal('hide');
                    $('.loader').show();
                    loadFilterProduct(data)
                    $('.loader').fadeOut(250);
                });
            }
        });
    }

    // hiển thị danh sách kết quả bộ lọc
    function loadFilterProduct(data){
        $('#lst_product').children().remove();
        $('#qty-product').text(data.length + ' điện thoại');
        for(var i = 0; i < data.length; i++){
            var product = $('<div class="col-lg-3 col-md-4 col-sm-6">' +
                                '<div id="product_'+ i +'" class="shop-product-card box-shadow">' +
                                    '<div class="shop-overlay-product"></div>' +
                                    '<a href="#" class="shop-cart-link"><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</a>' +
                                    '<a href="/dienthoai/'+ data[i]['tensp_url'] +'" class="shop-detail-link">Xem chi tiết</a>' +
                                    '<div>' +
                                        '<div class="pt-20 pb-20">' +
                                            '<img src="/images/phone/'+ data[i]['hinhanh'] +'" class="shop-product-img-card">' +
                                        '</div>' +
                                        '<div class="pb-20 text-center d-flex flex-column">' +
                                            '<b class="mb-10">'+ data[i]['tensp'] +'</b>' +
                                            '<div>' +
                                                '<span class="font-weight-600 price-color">'+ new Intl.NumberFormat().format(data[i]['giakhuyenmai']) +'<sup>đ</sup></span>' +
                                                '<span class="ml-5 text-strike">'+ new Intl.NumberFormat().format(data[i]['gia']) +'<sup>đ</sup></span>' +
                                            '</div>' +
                                            '<div>' +
                                                '<div id="evaluate_'+ i +'" class="flex-row pt-5">' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>');

            product.appendTo('#lst_product');
                            
            // khuyến mãi
            if(data[i]['khuyenmai'] != 0){
                var promotionTag = $('<div class="shop-promotion-tag">' +
                                        '<span class="shop-promotion-text">'+ (data[i]['khuyenmai']*100) + '%' +'</span>' +
                                    '</div>');
                $('#product_' + i).prepend(promotionTag);
            }

            // đánh giá
            if(data[i]['danhgia']['qty'] != 0){
                for(var j = 1; j <= 5; j++){
                    if(data[i]['danhgia']['star'] > j){
                        var star = $('<i class="fas fa-star checked"></i>');
                        star.appendTo($('#evaluate_' + i));
                    } else {
                        var star = $('<i class="fas fa-star uncheck"></i>');
                        star.appendTo($('#evaluate_' + i));
                    }
                }
                var qtyEvaluate = $('<span class="fz-14 ml-10">' + data[i]['danhgia']['qty'] + ' đánh giá </span>');
                qtyEvaluate.appendTo($('#evaluate_' + i));
            }
        }
    }

/*============================================================================================================================
                                                            Detail
=============================================================================================================================*/
    
    // slide hình ảnh khác của sản phẩm
    $('#detail-carousel').owlCarousel({
        stagePadding: 5,
        nav: false,
        rewind: true,
        dots: false,
        responsiveClass:true,
        responsive: {
            0: {
                items: 4
            },
            600: {
                items: 4
            },
            1000: {
                items: 4
            }
        }
    });

    // chuyển đổi hình ảnh khác
    var owl_detail = $('#detail-carousel');
    owl_detail.owlCarousel();

    $('#prev-owl-carousel').on('click', function(){
        owl_detail.trigger('prev.owl.carousel', [300]);
    });

    $('#next-owl-carousel').on('click', function(){
        owl_detail.trigger('next.owl.carousel');
    });

    $('.another-img').off('click').click(function(){
        var data = $(this).attr('src');
        $('#main-img').attr('src', data);
    });

    // yêu thích sản phẩm
    $('.favorite-tag').click(function(){
        // kiểm tra nếu chưa yêu thích sản phẩm
        if($(this).attr('data-flag') == null){
            if($('#add-favorite').length){
                alert('Vui lòng thao tác chậm');
                return;
            }
            
            // gắn cờ
            $(this).attr('data-flag', 1);

            // thay đổi icon
            $(this).children().remove();
            var heartClicked = $('<i class="fas fa-heart"></i>');
            heartClicked.appendTo($(this));            

            // hiển thị toast
            var toast = $('<div id="add-favorite" class="alert-toast"><div id="toast-message" class="d-flex align-items-center"><span>Đã thêm <b>iPhone 12 PRO MAX</b> vào danh sách yêu thích</span></div></div>');
            $(this).after(toast);
            showToast('#add-favorite');
            
        } else { // hủy yêu thích
            if($('#add-favorite').length){
                alert('Vui lòng thao tác chậm');
                return;
            } 
            // xóa cờ
            $(this).removeAttr('data-flag');

            // thay đổi icon
            $(this).children().remove();
            var heartClicked = $('<i class="far fa-heart"></i>');
            heartClicked.appendTo($(this));

            // hiển thị toast
            var toast = $('<div id="add-favorite" class="alert-toast"><div id="toast-message" class="d-flex align-items-center"><span>Đã xóa <b>iPhone 12 PRO MAX</b> khỏi danh sách yêu thích</span></div></div>');
            $(this).after(toast);
            showToast('#add-favorite');
        }
    });

    // sản phẩm cùng hãng
    var owl_sameBrand = $('#same-brand-pro-carousel');
    owl_sameBrand.owlCarousel({
        nav: false,
        rewind: true,
        dots: false,
        responsiveClass:true,
        responsive: {
            0: {
                items: 5
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            },
            1200: {
                items: 5
            }
        }
    });

    $('#prev-brand').on('click', function(){
        owl_sameBrand.trigger('prev.owl.carousel', [300]);
    });

    $('#next-brand').on('click', function(){
        owl_sameBrand.trigger('next.owl.carousel');
    });


    // sản phẩm tương tự
    var owl_similar = $('#similar-pro-carousel');
    owl_similar.owlCarousel({
        nav: false,
        rewind: true,
        dots: false,
        responsiveClass:true,
        responsive: {
            0: {
                items: 5
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            },
            1200: {
                items: 5
            }
        }
    });

    $('#prev-similar').on('click', function(){
        owl_similar.trigger('prev.owl.carousel', [300]);
    });

    $('#next-similar').on('click', function(){
        owl_similar.trigger('next.owl.carousel');
    });

    // thay đổi màu sắc
    $('.color-option').off('click').click(function(){
        var image = $(this).data('image');
        $('#main-img').attr('src', image);

        $('.color-option').removeClass('selected');
        $(this).addClass('selected');
    });

    // hiển thị đánh giá sản phẩm
    if($('#total-rating').length){
        var total_rating = parseInt($('#total-rating').val());

        // tổng số lượng đánh giá từng sao
        var total_star_5 = 5 * parseInt($('#percent-5-star').data('id'));
        var total_star_4 = 4 * parseInt($('#percent-4-star').data('id'));
        var total_star_3 = 3 * parseInt($('#percent-3-star').data('id'));
        var total_star_2 = 2 * parseInt($('#percent-2-star').data('id'));
        var total_star_1 = 1 * parseInt($('#percent-1-star').data('id'));

        // tính sao trung bình = tổng của tổng số lượng đánh giá từng sao / tổng số đánh giá
        var avg_star = (total_star_5 + total_star_4 + total_star_3 + total_star_2 + total_star_1) / total_rating;
        avg_star = String(avg_star);
        // console.log(avg_star);

        // nếu như là số lẻ thì làm tròn 1 chữ số
        if(avg_star.search('.') != -1){
            var round = String(avg_star.split('.')[1]);
            var num_1 = round.charAt(0);
            if(round.length > 1){
                var num_2 = round.charAt(1);
                if(num_2 >= 5){
                    num_1++;
                }
            }
            avg_star = String(avg_star).split('.')[0] + '.' + num_1;
        }

        $('.detail-vote-avg').text(avg_star);

        // hiển thị chi tiết số lượng đánh giá của từng sao
        for(var i = 1; i <= 5; i++){
            var element = '#percent-' + i + '-star';
            var qtyOfStar = $(element).data('id');
            var total = total_rating;
            var id = '#percent-' + i + '-star';
            ratingStar(id, qtyOfStar, total);
        }
    }

    // hàm hiển thị phần trăm thanh progress
    function ratingStar(id, qtyOfStar, total){
        var avg = (qtyOfStar / total) * 100 + '%';
        $(id).css('width', avg);
    }

    // đánh giá sao sản phẩm
    $('.star-rating').hover(
        // mouse enter
        function(){
            $('.star-rating').removeAttr('style');
            var star = $(this).data('id');
            for(var i = 1; i <= star; i++){
                $('#star-' + i).css('color' , 'orange');
            }
        }, 
        // mouse leave
        function(){
            $('.star-rating').removeAttr('style');
            var star = $('#star-rating').val();
            if(star != 0){
                for(var i = 1; i <= star; i++){
                    $('#star-' + i).css('color' , 'orange');
                }
            } else {
                $('.star-rating').removeAttr('style');
            }
        }
    );

    // chọn sao
    $('.star-rating').click(function(){
        var star = $(this).data('id');
        $('#star-rating').val(star);

        $('.star-rating').removeAttr('style');

        for(var i = 1; i <= star; i++){
            $('#star-' + i).css('color' , 'orange');
        }
    });

    $('#btn-photo-attached').click(function(){
        $('.upload-inp').trigger('click');
    });

    // thêm hình đánh giá
    $('.upload-inp').change(function(){
        //số lượng hình upload
        var count = this.files.length;

        // tổng số lượng hình hiện tại
        var qty_img = parseInt($('#qty-img').val());

        // nếu không chọn hình nào thì thoát 
        if(count == 0){
            return;
        }

        // số lượng hình
        $('.qty-img').show();

        // hiển thị div chứa hình ảnh
        $('.evaluate-img-div').css({
            'display': 'flex',
        });

        // nếu số lượng hình upload > 3 thì hiển thị modal thông báo
        if(count > 3){
            $('#modal-warning-1').modal('show');
        }

        // tạo thẻ div, nút xóa, hình đánh giá
        for(var i = 0; i < count; i++){
            // nếu số lượng hình > 3 thì hiển thị modal thông báo
            if(qty_img >= 3){
                $('#modal-warning-1').modal('show');
                break;
            }

            var id = (qty_img + i) + 1;
            var id_div = '#img-rating-' + id;

            // thẻ div chứa hình và nút xóa
            var div = $('<div>',{
                id: id_div,
                class: "img-rating",
            });
            div.appendTo('.evaluate-img-div');

            // nút xóa hình
            var btn_remove = $('<div>',{
                class: 'btn-remove-single-img',
            });
            btn_remove.appendTo(div);

            // icon xóa
            var icon_remove = $('<i class="far fa-times-circle fz-20">');
            icon_remove.appendTo(btn_remove);

            // hình ảnh
            var img = $('<img>', {
                class: 'w-100',
                src: URL.createObjectURL(this.files[i]),
            });
            img.appendTo(div);

            qty_img++;
        }

        $('#qty-img').val(qty_img);

        // hiển thị số lượng hình ảnh đang có
        $('.qty-img').html('(' + qty_img + ')');
    });
    
    // xóa từng hình
    $('.evaluate-img-div').bind('DOMSubtreeModified', function(){
        $('.btn-remove-single-img').off('click').click(function(){
            // tổng số lượng hình hiện tại
            var qty_img = parseInt($('#qty-img').val());

            // số lượng hình -1
            qty_img--;

            // cập nhật hiển thị số lượng
            $('.qty-img').html('(' + qty_img + ')');

            $(this).parent('div').remove();
            $('#qty-img').val(qty_img);

            if($('#qty-img').val() == 0){
                hideEvaluateDiv();
            }
        });
    });

    function hideEvaluateDiv(){
        $('.evaluate-img-div').children().remove();;
        $('#qty-img').val(0);
        $('.qty-img').hide();
    }

    // phản hồi
    $('.reply-cmt').click(function(){
        var id = $(this).data('id');

        if($('#reply-' + id).length){
            return;
        }

        var replyDiv = $('<div id="reply-' + id + '" class="d-flex flex-column pl-70 pt-10 pb-10">' + 
                            '<div class="row">' + 
                                '<div class="col-md-1">' + 
                                    '<img src="images/avt1620997169.jpg" class="circle-img" alt="">' + 
                                '</div>' + 
                                '<div class="col-md-11">'+
                                    '<textarea name="" id="" rows="3" placeholder="Viết câu trả lời"></textarea>'+
                                    '<div class="d-flex justify-content-end align-items-center pt-10">'+
                                        '<div class="remove-reply pointer-cs price-color"><i class="fal fa-times-circle mr-5"></i>Hủy</div>'+
                                        '<div class="pl-10 pr-10">|</div>'+
                                        '<div class="pointer-cs main-color-text"><i class="fas fa-reply mr-5"></i>Trả lời</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>');

        $(this).parent().parent().after(replyDiv);
    });

    $('#list-comment').bind('DOMSubtreeModified', function(){
        // xóa reply
        $('.remove-reply').off('click').click(function(){
            $(this).parent().parent().parent().parent().hide('blind', 250);
            setTimeout(() => {
                $(this).parent().parent().parent().parent().remove();
            }, 250);
        });
    });

    // so sánh
    $('.compare-btn').off('click').click(function(){
        var currentName = url;
        var compareName = $(this).attr('id').split('_')[1];

        var redirectPage ='/sosanh/' + currentName + 'vs' + compareName;

        location.href = redirectPage;
    });
    

/*============================================================================================================================
                                                            Cart
=============================================================================================================================*/

    // giảm số lượng sản phẩm trong giỏ hàng
    $('.minus').off('click').on('click', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        var qty = parseInt($('#qty_' + id).text());
        if(qty == 0) {
            return;
        } else  {
            $('#qty_' + id).text(--qty);
            $('.qty_' + id).text(qty);
        }
    });

    // tăng số lượng sản phẩm trong giỏ hàng
    $('.plus').off('click').on('click', function(){
        var id = $(this).data('id');
        var qty = parseInt($('#qty_' + id).text());
        $('#qty_' + id).text(++qty);
        $('.qty_' + id).text(qty);
    });

/*============================================================================================================================
                                                            Checkout
=============================================================================================================================*/

    // kiểm tra nơi nhận hàng
    if($('input[name="receive-method"]').is(':checked')){
        var method = $('input[name="receive-method"]:checked').val();
        if(method == 'atHome'){
            $('.atStore').css('display', 'none');
            $('.atHome').css('display', 'block');
        } else {
            $('.atStore').css('display', 'block');
            $('.atHome').css('display', 'none');
        }
    }
    $('#TaiCuaHang').on('click', function(){
        $('.atStore').css('display', 'block');
        $('.atHome').css('display', 'none');
    });

    $('#TaiNha').on('click', function(){
        $('.atHome').css('display', 'block');
        $('.atStore').css('display', 'none');
    });

    // thay đổi địa chỉ giao hàng
    $('#btn-change-address-delivery').click(function(){
        $('#change-address-delivery-modal').modal('show');
    });

    // thêm mới địa chỉ giao hàng
    $('#btn-new-address-checkout').click(function(){
        $('#new-address-div').show();
        $('.list-address').animate({scrollTop: ($('.list-address').height() + 1000)}, '250');
    });

    // đóng thêm mới địa chỉ giao hàng
    $('#btn-close-add-new-address').click(function(){
        $('#new-address-div').hide();
    });

    /*=============================================================================
                                  nhận tại cửa hàng
    ===============================================================================*/

    // hiển thị khu vực
    $('#area-selected').click(function(){
        $('#area-box').toggle('blind', 250);
    });

    // chọn khu vực
    $('.option-area').click(function(){
        var areaID = $(this).data('area');
        var name = $(this).text();

        $('#area-box').toggle('blind', 250);

        loadBranchList(areaID, name);
    });

    function loadBranchList(areaID, name){
        $('#area-name').text(name);
        if($('#area-selected').hasClass('required')){
            $('#area-selected').removeClass('required');
            $('#area-selected').next().remove();
        }
        $('#area-selected').attr('data-flag', '1');

        var parent = $('.list-branch');
        var count = parent.children().length;
        
        for(var i = 0; i < count; i++){
            var element = $(parent.children()[i]);
            if(element.data('area') == areaID){
                element.show();
            } else {
                element.hide();
            }
        }

        $('.list-branch').show('blind', 250);
    }

    // xóa required ở chọn cửa hàng
    $('input[name="branch"]').click(function(){
        if($('.list-branch').hasClass('required')){
            $('.list-branch').removeClass('required');
            $('.list-branch').next().remove();
        }
    });

    // xác nhận thanh toán
    $('#btn-confirm-checkout').click(function(){
        var receciveMethod = $('input[name="receive-method"]:checked').val();
        
        // nếu nhận tại cửa hàng thì kiểm tra bẫy lỗi các input
        if(receciveMethod == 'atStore'){
            var fullName = $('input[id="HoTen"]');
            var tel = $('#SDT');
            var areaSelected = $('#area-selected');
            var storeSelected = $('input[name="branch"]');

            var valiName = validateFullname(fullName);
            var valiPhone = validatePhoneNumber(tel);
            var valiSelect = validateReceiveAtStore(areaSelected, storeSelected);

            // nếu chưa đủ thông tin hoặc thông tin không hợp lệ
            if(!valiName && !valiSelect && !valiPhone){
                $(window).scrollTop(0);
            } else { // tiến hành thanh toán
                checkout();
            }
        } else {    // nếu giao hàng tận nơi
            checkout();
        }
    });

    function checkout(){
        var paymentMethod = $('input[name="payment-method"]:checked').val();
        
        // thanh toán khi nhận hàng
        if(paymentMethod == 'cash'){

        } else { // thanh toán zalopay
            $('#checkout-form').submit();
        }
    }

    // kiểm tra nhận tại cửa hàng có rỗng không
    function validateReceiveAtStore(areaSelected, storeSelected){
        var required;
        var parent = storeSelected.parent().parent();

        // nếu đã kiểm tra rồi thì return
        if(areaSelected.hasClass('required') || parent.hasClass('required')){
            return;
        }

        // nếu chưa chọn khu vực
        if(areaSelected.attr('data-flag') == null){
            required = $('<span class="required-text">Vui lòng chọn khu vực</span>');
            areaSelected.addClass('required');
            areaSelected.after(required);
            return false;
        } if(!storeSelected.is(':checked')){ // nếu chưa chọn cửa hàng
            required = $('<span class="required-text">Vui lòng chọn cửa hàng để nhận hàng</span>')
            parent.addClass('required');
            parent.after(required);
            return false; 
        }

        return true;
    }

    // kiểm tra họ tên
    function validateFullname(fullName){
        // nếu đã kiểm tra rồi thì return
        if(fullName.hasClass('required')){
            return;
        }

        // nếu chưa nhập họ tên
        if(fullName.val() == ''){
            var required = $('<span class="required-text">Vui lòng nhập họ và tên</span>');
            fullName.addClass('required');
            fullName.after(required);
            return false;
        }

        return true;
    }

    // kiểm tra số điện thoại
    function validatePhoneNumber(tel){
        // nếu đã kiểm tra rồi thì return
        if(tel.hasClass('required')){
            return;
        }

        var length = tel.val().length;
        var phoneno = /^\d{10}$/;
        var required;

        // chưa nhập
        if(length == 0){
            tel.addClass('required');
            required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            tel.after(required);
            return false;
        } else if(!tel.val().match(phoneno)){ // không đúng định dạng
            required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');
            tel.addClass('required');
            tel.after(required);
            return false;
        }

        return true;
    }

    $('input[id="HoTen"]').keyup(function(){
        $(this).removeClass('required');
        $(this).next().remove();
    });

    // kiểm tra nhập số diện thoại
    $('input[id="SDT"]').keyup(function(){
        if($(this).hasClass('required')){
            $(this).next().remove();
        }

        var phoneno = /^\d{10}$/;
        var required; 

        // chưa nhập
        if($(this).val() == ''){
            required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            $(this).addClass('required');
            $(this).after(required);
        } else if(!$(this).val().match(phoneno) || $(this).val().charAt(0) != 0){ // không đúng định dạng | ký tự đầu k phải số 0
            if($(this).next().hasClass('required-text')){
                return;
            }
            required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');    
            $(this).addClass('required');
            $(this).after(required);
        } else { // xóa required
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    // đảo ngược button đóng/ mở giỏ hàng trang Thanh toán
    $('.checkout-btn-collapse-cart').on('click', function(){
        if($(this).attr('aria-expanded') == 'true'){
            $(this).css({
                '-ms-transform' : 'rotate(0)',
                'transform' : 'rotate(0)',
            });
        } else {
            $(this).css({
                '-ms-transform' : 'rotate(180deg)',
                'transform' : 'rotate(180deg)',
            });
        }
    });

    if($('#success-img').length){
        setTimeout(() => {
            $('#success-checkout').css('opacity', '1');
        },1000);
    }
    
/*============================================================================================================================
                                                            Compare
=============================================================================================================================*/
    
    // tìm kiếm điện thoại để so sánh
    $('#compare-search-phone').keyup(function(){
        var data = $(this).val();
        if(data == ''){
            $('.compare-list-search-phone').css('display', 'none');
        } else{
            $('.compare-list-search-phone').css('display', 'block');
        }
    });

    // nút xem so sánh cấu hình chi tiết
    $('.compare-btn-see-detail').click(function(){
        $(this).css('display', 'none');
        $('.compare-detail').css('display', 'table-row');
    });

/*============================================================================================================================
                                                            Check IMEI
=============================================================================================================================*/

    $('#imei-inp').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $('.required-text').hide();
        }
    });

    // 
    $('#btn-check-imei').click(function(){
        var IMEI = $('#imei-inp').val();
        var required;

        // nếu chưa nhập IMEI
        if(IMEI == ''){
            required = $('<span class="required-text">Số IMEI không được bỏ trống</span>');
            $('#imei-inp').addClass('required');
            $('#imei-inp').after(required);
            return;
        }

        $('#check-imei').hide();
        $('#imei-inp').next().remove();

        // hiển thị điện thoại hợp lệ
        $('#valid-imei').removeAttr('class');
    });

    $('#btn-check-imei-2').click(function(){
        $('#imei-inp').val('');
        $('#check-imei').show();
        $('#valid-imei').addClass('none-dp');
        $(window).scrollTop(0);
    });
});





