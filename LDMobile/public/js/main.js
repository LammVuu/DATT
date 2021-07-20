$(function() {
    //===== Preloader
    $(window).on('load', function(){
        var scroll_chitiet = sessionStorage.getItem('reload_chitiet');
        if(scroll_chitiet){
            sessionStorage.removeItem('reload_chitiet');
            $('#toast').append('<span id="edit-evaluate-toast" class="alert-toast">'+scroll_chitiet+'</span');
            showToast('#edit-evaluate-toast');
        }
        $('.loader').fadeOut(250);
    });

    var url = window.location.pathname.split('/')[1];
    var timer = null;

    if (window.location.hash == '#_=_') {
        window.location.hash = ''; // for older browsers, leaves a # behind
        history.pushState('', document.title, window.location.pathname); // nice and clean
    }

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

    // toast thông báo
    if($('#toast-message').length){
        var toast = $('<div id="message" class="alert-toast"><div class="d-flex align-items-center"><span>'+ $('#toast-message').data('message') +'</span></div></div>');
        $("#toast-message").after(toast);
        showToast('#message');
        $('#toast-message').remove();
    }

    // hết hạn đăng nhập
    if($('#invalid-login-modal').length){
        $('#invalid-login-modal').modal('show');
    }

    // đóng modal, xóa session login status
    $('.close-invalid-login-modal').off('click').click(function(){
        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'ajax-forget-login-status-session'
        });
    });

    // thanh tìm kiếm
    $('.head-search-input').focus(function(){
        $('.backdrop').fadeIn();
        if($('.head-search-input').val() != ''){
            $('.head-search-result').css('display', 'block');
        }
    });
    $('.head-search-input').focusout(function(){
        setTimeout(() => {
            $('.backdrop').fadeOut();
            $('.head-search-result').css('display', 'none');
        }, 100);
    });

    var enterKey = false;
    $('.head-search-input').keypress(function(e){
        if(e.keyCode == '13'){
            var val = $(this).val().toLowerCase().trim();
            enterKey = true;
            location.href = 'timkiem/' + val;
        }
    });
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
                                                        '<span class="price-color fw-600">'+ numberWithDot(data['phone'][i]['gia']) +'<sup>đ</sup></span>' +
                                                        '<span class="text-strike ml-10">'+ numberWithDot(data['phone'][i]['giakhuyenmai']) +'<sup>đ</sup></span>' +
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
    /*============================================================================================================
                                                            Header
        ==============================================================================================================*/
        // hiển thị offcanvas
        $('#show-offcanvas').on('click', function(){
            if($(this).attr('aria-expanded') == 'false'){
                $('.head-offcanvas-box').css({
                    'width' : '40%',
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

    /*============================================================================================================
                                                        Đăng ký
    ==============================================================================================================*/
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

        /*=====================================================
                        Nhập thông tin                  
        =======================================================*/
        // đăng ký tài khoản
        $('#signup-step-1').click(function(){
            sendVerifyCode();
        });

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

    } 
    /*============================================================================================================
                                                        Đăng nhập
    ==============================================================================================================*/
    else if(url == 'dangnhap'){
        // đăng nhập
        $('#btn-login').click(function(){
            var telInp = $('#login_tel');
            var pwInp = $('#login_pw');
            var valiLogin = validateLogin(telInp, pwInp);
            $('#remember').is(':checked') ? $('#remember').val(true) : $('#remember').val(false);

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

        $('#login_pw').keypress(function(e){
            if(e.keyCode == 13){
                $('#btn-login').trigger('click');   
            }
        });

        $('#login_pw').keyup(function(){
            if($(this).hasClass('required')){
                $(this).removeClass('required');
                $(this).next().remove();
            }
        });
    }
    /*============================================================================================================
                                                        Index
    ==============================================================================================================*/
    else if(url == ''){
        /*============================================================================================================
                                                            Index
        ==============================================================================================================*/
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
    }
    /*============================================================================================================
                                                        Tài khoản
    ==============================================================================================================*/
    else if(url == 'taikhoan'){
        /*==================================================================================
                                        thông tin tài khoản
        //==================================================================================*/
        var modal_avt = $('#change-avt');
        var image = document.getElementById('pre-avt-big');
        var cropper_avt;
        var zoom_range = document.getElementById('zoom-range');
        var zoom_in = 0, zoom_out = 0;

        // thay đổi ảnh đại diện
        $('#btn-change-avt').click(function(){
            $('#change-avt-inp').trigger('click');
        });

        // hiển thị modal tiến hành cắt ảnh dại diện
        $('#change-avt-inp').change(function(e){
            startCropImg(cropper_avt, e);
        });

        function startCropImg(cropper, e){
            var files = e.target.files;

            // hiển thị hình ảnh
            if(files && files.length > 0){
                var reader = new FileReader();
                reader.onload = function(){
                    if(cropper != null){
                        cropper.replace(reader.result);
                        return;
                    } else {
                        image.src = reader.result;
                        modal_avt.modal('show');
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

        // reset crop img avt
        $('#reset-canvas').click(function(){
            cropper_avt.reset();
            refreshZoomVal();
        });

        // chọn ảnh khác
        $('.reselect-img').click(function(){
            $('#change-avt-inp').trigger('click');
            refreshZoomVal();
        });

        // cắt ảnh
        $(".crop-img").click(function(){
            var canvas = cropper_avt.getCroppedCanvas({
                width: 320,
                height: 320
            });

            canvas.toBlob(function(blob){
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function(){
                    var base64data = reader.result;
                    $('input[name="base64data"]').val(base64data);
                    refreshZoomVal();
                    $('#change-avatar-form').submit();
                    
                    // $.ajax({
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //     },
                    //     url: '/ajax-change-avatar',
                    //     type: 'POST',
                    //     cache: false,
                    //     data: {'image':base64data},
                    //     success:function(data){
                    //         modal_avt.modal('hide');

                    //         $('#avt-img').attr('src', data);
                    //         $('#avt-header').attr('src', data);

                    //         var toast = $('<div id="avt-toast" class="alert-toast"><div class="d-flex align-items-center"><span>Cập nhật ảnh đại diện thành công</span></div></div>');
                    //         $("#toast").after(toast);
                    //         showToast('#avt-toast');

                    //         refreshZoomVal(); 
                    //     }
                    // });
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

        // thay đổi thông tin tài khoản
        $('#btn-change-info').click(function(){
            $('#change-info-div').toggle('blind', 300);
        });

        $('#cancel-change-info').click(function(){
            $('#change-info-div').toggle('blind', 300);
        });

        // thay đổi tên người dùng
        $('#change-fullname-btn').click(function(){
            var valiFullname = validateFullname($('input[name="new_fullname_inp"]'));

            if(valiFullname){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-change-fullname',
                    type: 'POST',
                    data: {'hoten':$('input[name="new_fullname_inp"]').val() },
                    success:function(data){
                        $('#user_fullname').text(data);

                        var toast = $('<div id="avt-toast" class="alert-toast"><div class="d-flex align-items-center"><span>Cập nhật họ và tên thành công</span></div></div>');
                        $("#toast").after(toast);
                        showToast('#avt-toast');

                        $('#change-info-div').toggle('blind', 300);
                    }
                });
            }
        });

        // thay đổi mật khẩu
        $('#change-pw-btn').click(function(){
            // chưa nhập mật khẩu
            if($('#old_pw').val().length == 0){
                $('#old_pw').addClass('required');
                var errMess = $('<div class="required-text">Vui lòng nhập mật khẩu</div>');
                $('#old_pw').after(errMess);
                return;
            }
            valiPW = validatePassword($('#new_pw'), $('#retype_pw'));

            if(valiPW){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-change-password',
                    type: 'POST',
                    data: {'old_pw': $('#old_pw').val(), 'new_pw': $('#new_pw').val()},
                    success:function(data){
                        if(data['status'] == 'invalid password'){
                            $('#old_pw').addClass('required');
                            var errMess = $('<div class="required-text">Mật khẩu cũ không chính xác</div>');
                            $('#old_pw').after(errMess);
                        } else {
                            $('#change-pw-modal').modal('hide');
                            
                            var toast = $('<div id="success-toast" class="alert-toast"><div class="d-flex align-items-center"><span>Thay đổi mật khẩu thành công</span></div></div>');
                            $("#toast").append(toast);
                            showToast('#success-toast');
                        }
                    }
                });
            }
        });

        // reset modal change password
        $('#change-pw-modal').on('hidden.bs.modal', function(){
            $('#old_pw').val('');
            $('#new_pw').val('');
            $('#retype_pw').val('');

            removeRequried($('#old_pw'));
            removeRequried($('#new_pw'));
            removeRequried($('#retype_pw'));
        });

        $('#old_pw').keyup(function(){
            removeRequried($(this));
        });

        $('#new_pw').keyup(function(){
            removeRequried($(this));
        });

        $('#retype_pw').keyup(function(){
            removeRequried($(this));
        });

        $('input[name="new_fullname_inp"]').keyup(function(){
            if($(this).hasClass('required')){
                $(this).removeClass('required');
                $(this).next().remove();
            }
        });

        /*==================================================================================
                                            Thông báo
        //==================================================================================*/

        // đánh dấu đã đọc
        $('.noti-btn-read').off('click').click(function(){
            var id = $(this).data('id');

            $.ajax({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-check-noti',
                type: 'POST',
                data: {'id': id},
                success:function(){
                    $('#noti-' + id).addClass('account-noti-checked').removeClass('account-noti-wait');

                    // cập nhật số lượng thông báo chưa đọc
                    var qty = parseInt($('#not-seen-qty').text());
                    if(qty == 1){
                        $('#not-seen-qty').hide();
                        $('#not-seen-qty-header').hide();
                    } else {
                        qty--;
                        $('#not-seen-qty').text(qty);
                        $('#not-seen-qty-header').text(qty);
                    }
                    // xóa nút "đánh dấu đã đọc"
                    $('.noti-btn-read[data-id="'+id+'"]').remove();
                }
            });
        });

        // xóa thông báo
        $('.noti-btn-delete').off('click').click(function(){
            var id = $(this).data('id');

            $.ajax({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-delete-noti',
                type: 'POST',
                data: {'id':id},
                success:function(){
                    setTimeout(() => {
                        $('#noti-' + id).remove();
                        if($('#lst_noti').children().length == 0){
                            var elmnt = $('<div class="p-70 box-shadow text-center">Bạn không có thông báo nào.</div>');
                            elmnt.show('fade');
                            $('#lst_noti').append(elmnt);
                        }
                    }, 500);

                    $('#noti-' + id).hide('drop');

                    // cập nhật số lượng thông báo chưa đọc
                    if($('.noti-btn-read[data-id="'+id+'"]').length){
                        var qty = parseInt($('#not-seen-qty').text());
                        if(qty == 1){
                            $('#not-seen-qty').hide();
                            $('#not-seen-qty-header').hide();
                        } else {
                            qty--;
                            $('#not-seen-qty').text(qty);
                            $('#not-seen-qty-header').text(qty);
                        }
                    }

                    var toast = $('<span id="success-toast" class="alert-toast">Đã xóa thông báo</span>');
                    $('#toast').append(toast);
                    showToast('#success-toast');
                }
            });
        });

        // đánh dấu đọc tất cả
        $('#noti-btn-read-all').click(function(){
            $.ajax({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-check-all-noti',
                success:function(){
                    $('.noti-btn-read').remove();
                    $('.account-noti-wait').addClass('account-noti-checked').removeClass('account-noti-wait');
                    
                    $('#not-seen-qty').text(0);
                    $('#not-seen-qty-header').text(0);
                    $('#not-seen-qty').hide();
                    $('#not-seen-qty-header').hide();

                    var toast = $('<span id="success-toast" class="alert-toast">Đã đọc tất cả thông báo</span>');
                    $('#toast').append(toast);
                    showToast('#success-toast');
                }
            });
        });

        // xóa tất cả thông báo
        $('#noti-btn-delete-all').click(function(){
            $.ajax({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-delete-all-noti',
                success:function(){
                    setTimeout(() => {
                        var elmnt = $('<div class="p-70 box-shadow text-center">Bạn không có thông báo nào.</div>');
                        elmnt.show('fade');
                        $('#lst_noti').append(elmnt);
                        $('.single-noti').remove();
                    }, 800);

                    $('.single-noti').hide('drop');

                    $('#not-seen-qty').text(0);
                    $('#not-seen-qty-header').text(0);
                    $('#not-seen-qty').hide();
                    $('#not-seen-qty-header').hide();
                    
                    $('#toast').html('<span id="success-toast" class="alert-toast">Đã xóa tất cả thông báo</span>');
                    showToast('#success-toast');
                }
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

        /*==================================================================================
                                        Sản phẩm yêu thích
        //==================================================================================*/

        // xóa điện thoại yêu thích
        $('.fav-btn-delete').off('click').click(function(){
            var id = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-delete-favorite',
                type: 'POST',
                data: {'id': id},
                success:function(){
                    setTimeout(() => {
                        $('#favorite-' + id).remove();
                        if($('#lst_favorite').children().length == 0){
                            var elmnt = $('<div class="p-70 box-shadow d-flex justify-content-center">Bạn chưa có sản phẩm nào. <a href="dienthoai" class="ml-5">Xem sản phẩm</a></div></div>');
                            elmnt.show('fade');
                            elmnt.appendTo($('#lst_favorite'));
                        }
                    }, 500);

                    $('#favorite-' + id).hide('drop');

                    // toast
                    $('#toast').html('<span id="favorite-toast" class="alert-toast">Đã xóa sản phẩm khỏi danh sách yêu thích</span>');
                    showToast('#favorite-toast');
                }
            });
        });

        // xóa tất cả điện thoại yêu thích
        $('#fav-btn-delete-all').click(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-delete-all-favorite',
                success:function(){
                    setTimeout(() => {
                        $('.signle-favorite').remove();

                        var elmnt = $('<div class="p-70 box-shadow d-flex justify-content-center">Bạn chưa có sản phẩm nào. <a href="dienthoai" class="ml-5">Xem sản phẩm</a></div></div>');
                        elmnt.show('fade');
                        elmnt.appendTo($('#lst_favorite'));
                    }, 500);

                    $('.single-favorite').hide('drop');

                    // toast
                    $('#toast').html('<span id="favorite-toast" class="alert-toast">Đã xóa danh sách yêu thích</span>');
                    showToast('#favorite-toast');
                }
            })
        });

        //=======================================================================
        //============================== đơn hàng ===============================
        //=======================================================================

        //================================================================================
        //============================== chi tiết đơn hàng ===============================
        //================================================================================

        // tùy chỉnh độ cao
        if($('#DCGH-div').length){
            var height = $('#DCGH-div').height();
            $('#HTGH-div').css('height', height);
            $('#HTTT-div').css('height', height);
        }

        // hủy đơn hàng
        $('#cancel-order-btn').click(function(){
            $('#delete-content').text('Bạn muốn hủy đơn hàng này?');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-btn').attr('data-object', 'order');
            $('#delete-btn').text('Hủy');
            $('#delete-modal').modal('show');
        });

        //=========================================================================
        //============================== sổ địa chỉ ===============================
        //=========================================================================

        // thêm địa chỉ giao hàng mới
        $('#btn-new-address').click(function(){
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

    }
    /*============================================================================================================
                                                        Điện thoại
    ==============================================================================================================*/
    else if(url == 'dienthoai'){
        // nút hiển thị sắp xếp
        $('#btn-show-sort').click(function(){
            $('.shop-sort-box').toggle('blind', 250);
        });

        // gỡ bỏ chọn tất cả bộ lọc
        $('.shop-btn-remove-filter').click(function(){
            // icon loading
            var loading = $('<div class="spinner-border text-light" role="status" style="width: 24px; height: 24px"></div>');
            $('#btn-see-filter').text('');
            loading.appendTo($('#btn-see-filter'));

            dataFilterSort = {
                'filter': {},
                'sort' : '',
            };
            $('div[name="filter-item"]').removeClass('filter-selected');
            filterProduct(dataFilterSort);
        });

        var dataFilterSort = {
            'filter': {},
            'sort' : '',
        };
        var filterSortSuccess = null;

        $('#filter-modal').on('shown.bs.modal', function(){
            $('.shop-sort-box').hide('blind', 250);
        })

        // thêm | xóa bộ lọc
        $('.filter-item').off('click').click(function(e){
            e.preventDefault();
            
            // icon loading
            var loading = $('<div class="spinner-border text-light" role="status" style="width: 24px; height: 24px"></div>');
            $('#btn-see-filter').text('');
            loading.appendTo($('#btn-see-filter'));
                            
            var type = $(this).data('data').split('_')[0];
            var keyword = $(this).data('data').split('_')[1];
            var elmnt = $('#' + $(this).data('data'));

            // hủy 1 bộ lọc
            if($(elmnt).hasClass('filter-selected')){
                if(type == 'brand'){
                    const index = dataFilterSort.filter['brand'].indexOf(keyword);
                    if (index > -1) {
                        dataFilterSort.filter['brand'].splice(index, 1);
                    }
                    if(dataFilterSort.filter['brand'].length == 0){
                        delete dataFilterSort.filter['brand'];
                    }
                    filterProduct(dataFilterSort);
                    $(elmnt).removeClass('filter-selected');
                } else if(type == 'price'){
                    const index = dataFilterSort.filter['price'].indexOf(keyword);
                    if (index > -1) {
                        dataFilterSort.filter['price'].splice(index, 1);
                    }
                    if(dataFilterSort.filter['price'].length == 0){
                        delete dataFilterSort.filter['price'];
                    }
                    filterProduct(dataFilterSort);
                    $(elmnt).removeClass('filter-selected');
                } else if(type == 'os'){
                    const index = dataFilterSort.filter['os'].indexOf(keyword);
                    if (index > -1) {
                        dataFilterSort.filter['os'].splice(index, 1);
                    }
                    if(dataFilterSort.filter['os'].length == 0){
                        delete dataFilterSort.filter['os'];
                    }
                    filterProduct(dataFilterSort);
                    $(elmnt).removeClass('filter-selected');
                } else if(type == 'ram'){
                    const index = dataFilterSort.filter['ram'].indexOf(keyword);
                    if (index > -1) {
                        dataFilterSort.filter['ram'].splice(index, 1);
                    }
                    if(dataFilterSort.filter['ram'].length == 0){
                        delete dataFilterSort.filter['ram'];
                    }
                    filterProduct(dataFilterSort);
                    $(elmnt).removeClass('filter-selected');
                } else if(type == 'capacity') {
                    const index = dataFilterSort.filter['capacity'].indexOf(keyword);
                    if (index > -1) {
                        dataFilterSort.filter['capacity'].splice(index, 1);
                    }
                    if(dataFilterSort.filter['capacity'].length == 0){
                        delete dataFilterSort.filter['capacity'];
                    }
                    filterProduct(dataFilterSort);
                    $(elmnt).removeClass('filter-selected');
                }
            } else { // thêm bộ lọc
                if(type == 'brand'){
                    addFilter(dataFilterSort.filter, 'brand', keyword);
                    filterProduct(dataFilterSort);
                    $(elmnt).addClass('filter-selected');
                } else if(type == 'price'){
                    addFilter(dataFilterSort.filter, 'price', keyword);
                    filterProduct(dataFilterSort);
                    $(elmnt).addClass('filter-selected');
                } else if(type == 'os'){
                    addFilter(dataFilterSort.filter, 'os', keyword);
                    filterProduct(dataFilterSort);
                    $(elmnt).addClass('filter-selected');
                } else if(type == 'ram'){
                    addFilter(dataFilterSort.filter, 'ram', keyword);
                    filterProduct(dataFilterSort);
                    $(elmnt).addClass('filter-selected');
                } else if(type == 'capacity') {
                    addFilter(dataFilterSort.filter, 'capacity', keyword);
                    filterProduct(dataFilterSort);
                    $(elmnt).addClass('filter-selected');
                }
            }
        });

        // sắp xếp
        $('input[name="sort"]').change(function(){
            $('.loader').show();

            var sortType = $('input[name="sort"]:checked').attr('id');
            dataFilterSort['sort'] = sortType;
            filterProduct(dataFilterSort);
            
            setTimeout(() => {
                $('#btn-see-filter').trigger('click');
                $('.loader').fadeOut();
            }, 1000);
        });

        function addFilter(dataFilterSort, filter, keyword){
            if(dataFilterSort[filter] == null){
                dataFilterSort[filter] = [];
            }
            dataFilterSort[filter].push(keyword);
        }

        function filterProduct(dataFilterSort){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-filter-product',
                type: 'POST',
                data: {dataFilterSort:dataFilterSort},
                success: function(data){
                    filterSortSuccess = data;

                    // số tiêu chí lọc
                    if(Object.keys(dataFilterSort.filter).length == 0){
                        $('.filter-badge').text('');
                        $('.filter-badge').css('display', 'none');    
                    } else {
                        $('.filter-badge').text(Object.keys(dataFilterSort.filter).length);
                        $('.filter-badge').css('display', 'block');
                    }
                    
                    $('.see-result-filter').css('display', 'block');
                    $('#btn-see-filter').text('Xem ' + data.length + ' kết quả');

                    if(data.length == 0){
                        $('#btn-see-filter').attr('enable', 'false');
                    } else {
                        $('#btn-see-filter').attr('enable', 'true')
                    }
                }
            });
        }

        // xem danh sách kết quả lọc
        $('#btn-see-filter').click(function(){
            if($(this).attr('enable') == 'false'){
                return;
            }

            $('#filter-modal').modal('hide');
            $('.loader').show();
            loadFilterProduct(filterSortSuccess);
            $('.loader').fadeOut(250);
            $('.shop-sort-box').hide('blind', 250);
        });

        // hiển thị danh sách kết quả bộ lọc
        function loadFilterProduct(data){
            $('#lst_product').children().remove();
            $('#qty-product').text(data.length + ' điện thoại');
            for(var i = 0; i < data.length; i++){
                var product = $('<div class="col-lg-3 col-md-4 col-sm-6">' +
                                    '<div id="product_'+ data[i]['id'] +'" class="shop-product-card box-shadow">' +
                                        '<div class="shop-overlay-product"></div>' +
                                        '<div type="button" data-id="'+ data[i]['id'] +'" class="shop-cart-link"><i class="fas fa-cart-plus mr-10"></i>Thêm vào giỏ hàng</div>' +
                                        '<a href="/dienthoai/'+ data[i]['tensp_url'] +'" class="shop-detail-link">Xem chi tiết</a>' +
                                        '<div>' +
                                            '<div class="pt-20 pb-20">' +
                                                '<img src="/images/phone/'+ data[i]['hinhanh'] +'" class="shop-product-img-card">' +
                                            '</div>' +
                                            '<div class="pb-20 text-center d-flex flex-column">' +
                                                '<b class="mb-10">'+ data[i]['tensp'] +'</b>' +
                                                '<div>' +
                                                    '<span class="fw-600 price-color">'+ numberWithDot(data[i]['giakhuyenmai']) +'<sup>đ</sup></span>' +
                                                    '<span class="ml-5 text-strike">'+ numberWithDot(data[i]['gia']) +'<sup>đ</sup></span>' +
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
                    $('#product_' + data[i]['id']).prepend(promotionTag);
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

        $('#lst_product').on('DOMSubtreeModified', function(){
            $('.shop-cart-link').off('click').click(function(){
                var id_sp = $(this).data('id');
        
                chooseColor(id_sp);
            });
        });
        
        // modal chọn màu sắc để thêm vào giỏ hàng
        $('.shop-cart-link').off('click').click(function(){
            var id_sp = $(this).data('id');
            
            chooseColor(id_sp)
        });

        function chooseColor(id_sp){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-choose-color',
                type: 'POST',
                data: {id_sp:id_sp},
                success:function(data){
                    // yêu cầu đăng nhập
                    if(data == false){
                        $('#info-modal-main-btn').replaceWith($('<a href="/dangnhap" id="info-modal-main-btn" class="main-btn p-10 w-100">Đăng nhập</a>'));
                        $('#info-modal-content').text('Vui lòng đăng nhập để thực hiện chức năng này');
                        $('#info-modal').modal('show');
                        return;
                    }
                    $('#choose-color-phone-name').text(data['tensp']);
                    $('#choose-color-promotion-price').html(numberWithDot(data['giakhuyenmai']) + 'đ'.sup());
                    $('#choose-color-price').html(numberWithDot(data['gia']) + 'đ'.sup());

                    for(var i = 0; i < data['mausac'].length; i++){
                        var colorElmnt = $('<div type="button" data-id="'+ data['mausac'][i]['id'] +'" class="choose-color-item">' +
                                                '<img src="'+ data['url_phone'] + data['mausac'][i]['hinhanh'] +'" alt="">' +
                                                '<div id="color-name" class="pt-5">'+ data['mausac'][i]['mausac'] +'</div>' +
                                            '</div>');
                        colorElmnt.appendTo($('#phone-color'));
                    }
                    
                    $('#choose-color-modal').modal('show');
                }
            });
        }

        // chọn màu
        $('#phone-color').on('DOMSubtreeModified', function(){
            $('.choose-color-item').off('click').click(function(){
                $('#phone-color').removeClass('required');
                $('#phone-color').next().remove();
                $('.choose-color-item').removeClass('choose-color-selected');
                $(this).addClass('choose-color-selected');

                // số lượng tồn kho
                $.ajax({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-get-qty-in-stock',
                    type: 'POST',
                    data: {'id_sp': $(this).data('id')},
                    success:function(data){
                        // ngưng kinh doanh
                        if(data == 'false'){
                            $('#phone-color').after('<div class="required text-center price-color p-5 mt-20">Màu sắc này đã ngừng kinh doanh.</div>')
                            $('#btn-add-cart').hide();
                            $('#qty-div').hide();
                        }
                        // hết hàng
                        else if(data == 0){
                            $('#max-qty').val(data);
                            $('#phone-color').after('<div class="required text-center price-color p-5 mt-20">Màu sắc này tạm hết hàng.</div>')
                            $('#btn-add-cart').hide();
                            $('#qty-div').hide();
                        }
                        // còn hàng
                        else{
                            $('#max-qty').val(data);
                            $('#phone-color').next().remove();
                            $('#btn-add-cart').show();
                            $('#qty-div').show();
                        }
                    }
                });
            });
        });

        // thêm giỏ hàng
        $('#btn-add-cart').click(function(){
            var id_sp = $('.choose-color-selected').data('id');
            var sl = parseInt($('#qty').text());

            // chưa chọn màu
            if(!id_sp){
                $('#phone-color').addClass('required');
                var required = $('<span class="required-text">Vui lòng chọn 1 màu sắc</span>');
                $('#phone-color').after(required);
                return;
            }

            // mua quá số lượng
            if(parseInt($('#qty').text()) > $('#max-qty').val()){
                clearTimeout(timer);
                $('.tooltip-qty').text('Số lượng tối đa có thể mua là ' + $('#max-qty').val());
                $('.tooltip-qty').show();
                timer = setTimeout(() => {
                    $('.tooltip-qty').hide('fade');
                }, 3000);

                return;
            }

            $('#choose-color-modal').modal('hide');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-add-cart',
                type: 'POST',
                data: {id_sp:id_sp, sl:sl},
                success:function(data){
                    // chưa đăng nhập
                    if(data['status'] == false){
                        $('#info-modal-main-btn').replaceWith($('<a href="/dangnhap" id="info-modal-main-btn" class="main-btn p-10 w-100">Đăng nhập</a>'));
                        $('#info-modal-content').text('Vui lòng đăng nhập để thực hiện chức năng này');
                        $('#info-modal').modal('show');
                        return;
                    } 
                    // mua quá số lượng tồn kho
                    else if(data['status'] == 'invalid qty'){
                        $('#info-modal-content').text('Số lượng tối đa có thể mua là ' + $('#max-qty').val());
                        $('#info-modal-main-btn').attr('dismiss', 'true');
                        $('#info-modal-main-btn').text('Đã hiểu');
                        $('#info-modal').modal('show');
                        return;
                    }
                    // cập nhật số lượng badge giỏ hàng
                    else if(data['status'] == 'success'){
                        if($('.head-qty-cart').hasClass('none-dp')){
                            $('.head-qty-cart').removeClass('none-dp');
                        }
                        var qtyHeadCart = parseInt($('.head-qty-cart').text());
                        $('.head-qty-cart').text(++qtyHeadCart);
                    }

                    var addCartSuccess = $('<div class="add-cart-success">' +
                                                '<div class="d-flex align-items-center"><i class="fas fa-check-circle success-color-2 mr-10"></i>Thêm giỏ hàng thành công!</div>' +
                                                '<a href="giohang" class="checkout-btn p-10 w-100 mt-20">Xem giỏ hàng và thanh toán</a>' +
                                            '</div>');
                    addCartSuccess.appendTo($('#add-cart-success'));

                    setTimeout(() => {
                        setTimeout(() => {
                            $('.add-cart-success').remove();
                        }, 1000);
                        $('.add-cart-success').hide('fade', 300);
                    }, 5000);
                }
            });
        });

        // reset choose color modal
        $('#choose-color-modal').on('hidden.bs.modal', function(){
            $('#choose-color-phone-name').text('');
            $('#choose-color-promotion-price').text('');
            $('#choose-color-price').text('');
            $('#phone-color').text('');
            $('#phone-color').next().remove();
            $('#qty').text('1');
            $('#max-qty').val('');
            $('#btn-add-cart').show();
            $('#qty-div').show();
        });

        /*===========================================================================
                                        Chi tiết
        =============================================================================*/

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

        $('#prev-another-img').on('click', function(){
            owl_detail.trigger('prev.owl.carousel', [300]);
        });

        $('#next-another-img').on('click', function(){
            owl_detail.trigger('next.owl.carousel');
        });

        $('.another-img').off('click').click(function(){
            var data = $(this).attr('src');
            $('#main-img').attr('src', data);
        });

        // thay đổi màu sắc
        $('.color-option').off('click').click(function(){
            var image = $(this).data('image');
            var id_sp = $(this).data('id');

            // thay đổi hình sản phẩm
            $('#main-img').attr('src', image);

            // gỡ check các màu khác
            $('.color-option').removeClass('selected');
            $('.color-name').removeClass('fw-600');

            // check vào màu đang chọn
            $(this).addClass('selected');
            $($(this).children()[0]).addClass('fw-600');
            
            // kiểm tra yêu thích sản phẩm
            if($(this).attr('favorite') == "true"){
                // thay đổi icon
                $('.favorite-tag').html('<i class="fas fa-heart"></i>');
            } else {
                // thay đổi icon
                $('.favorite-tag').html('<i class="far fa-heart"></i>');
            }

            // cập nhật id nút mua ngay
            $('.buy-now').attr('data-id', id_sp);

            // kiểm tra còn hàng
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-get-qty-in-stock',
                type: 'POST',
                data: {'id_sp': id_sp},
                success:function(data){
                    // ngừng kinh doanh
                    if(data == 'false'){
                        $('#qty-in-stock-status').text('ngừng kinh doanh');
                        $('.buy-now').hide();
                    }
                    // hết hàng
                    else if(data == 0){
                        $('#qty-in-stock-status').text('tạm hết hàng');
                        $('.buy-now').hide();
                    }
                    // còn hàng
                    else {
                        $('#qty-in-stock-status').text('');
                        $('.buy-now').show();
                    }
                }
            });
        });

        // đánh đấu đã yêu thích sản phẩm
        if($('.favorite-tag').length){
            var favorite = $('.color-option.selected').attr('favorite');
            var id_sp = $('.color-option.selected').data('id');
            // đã yêu thích
            if(favorite == "true"){
                // thay đổi icon
                $('.favorite-tag').children().remove();
                var heartClicked = $('<i class="fas fa-heart"></i>');
                heartClicked.appendTo($('.favorite-tag'));
            }
            
            // kiểm tra còn hàng
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-get-qty-in-stock',
                type: 'POST',
                data: {'id_sp': id_sp},
                success:function(data){
                    // ngừng kinh doanh
                    if(data == 'false'){
                        $('#qty-in-stock-status').text('ngừng kinh doanh');
                        $('.buy-now').hide();
                    }
                    // hết hàng
                    else if(data == 0){
                        $('#qty-in-stock-status').text('tạm hết hàng');
                        $('.buy-now').hide();
                    }
                    // còn hàng
                    else {
                        $('#qty-in-stock-status').text('');
                        $('.buy-now').show();
                    }
                }
            });
        }

        // yêu thích sản phẩm
        $('.favorite-tag').click(function(){
            var id_sp = $('.color-option.selected').data('id');
            var phoneName = $(this).data('name');
            var color = $('.color-option.selected').data('color');

            clearTimeout(timer);
            $('#add-favorite').remove();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-add-delete-favorite',
                type: 'POST',
                data: {'id_sp':id_sp},
                success:function(data){
                    // yêu cầu đăng nhập
                    if(data['status'] == 'login required'){
                        $('#info-modal-main-btn').replaceWith($('<a href="/dangnhap" id="info-modal-main-btn" class="main-btn p-10 w-100">Đăng nhập</a>'));
                        $('#info-modal-content').text('Vui lòng đăng nhập để thực hiện chức năng này');
                        $('#info-modal').modal('show');
                    }
                    // thêm thành công
                    else if(data['status'] == 'add success'){
                        // thay đổi icon
                        $('.favorite-tag').html('<i class="fas fa-heart"></i>');

                        // hiển thị toast
                        var toast = $('<div id="add-favorite" class="alert-toast"><div id="toast-message" class="d-flex align-items-center"><span>Đã thêm <b>'+phoneName+' - '+color+'</b> vào danh sách yêu thích</span></div></div>');
                        $('.favorite-tag').after(toast);
                        showToast('#add-favorite');

                        // đánh dấu yêu thích của màu sắc
                        $('.color-option.selected').attr('favorite', 'true');
                    }
                    // xóa thành công
                    else if(data['status'] == 'delete success'){
                        // thay đổi icon
                        $('.favorite-tag').html('<i class="far fa-heart"></i>');

                        var toast = $('<div id="add-favorite" class="alert-toast"><div id="toast-message" class="d-flex align-items-center"><span>Đã xóa <b>'+phoneName+' - '+color+'</b> khỏi danh sách yêu thích</span></div></div>');
                        $('.favorite-tag').after(toast);
                        showToast('#add-favorite');

                        // đánh dấu yêu thích của màu sắc
                        $('.color-option.selected').attr('favorite', 'false');
                    }
                }
            });
        });

        // kiểm tra còn hàng
        $('#check-qty-in-stock-btn').click(function(){
            var id_sp = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-choose-color',
                type: 'POST',
                data: {'id_sp': id_sp},
                success:function(data){
                    $('#check-qty-in-stock-phone-name').text(data['tensp']);
                    for(var i = 0; i < data['mausac'].length; i++){
                        var colorElmnt = $('<div type="button" data-id="'+ data['mausac'][i]['id'] +'" class="choose-color-item-check-qty-in-stock">' +
                                                '<img src="'+ data['url_phone'] + data['mausac'][i]['hinhanh'] +'" alt="">' +
                                                '<div id="color-name" class="pt-5">'+ data['mausac'][i]['mausac'] +'</div>' +
                                            '</div>');
                        colorElmnt.appendTo($('#check-qty-in-stock-lst-color'));
                    }
                    $('#check-qty-in-stock-modal').modal('show');
                }
            })
        });

        // reset modal check stock
        $('#check-qty-in-stock-modal').on('hidden.bs.modal', function(){
            $('#check-qty-in-stock-lst-color').children().remove();
            $('#area-selected').removeAttr('data-flag');
            $('#area-selected').removeAttr('data-id');
            $('#area-name').text('Chọn khu vực');
            $('.list-branch').children().remove();
            $('.list-branch').hide();
            $('#check-qty-in-stock-status').html('');
        });

        // chọn màu sắc kiểm tra còn hàng
        $('#check-qty-in-stock-lst-color').bind('DOMSubtreeModified', function(){
            $('.choose-color-item-check-qty-in-stock').off('click').click(function(){
                var id_sp = $(this).data('id');

                $('.choose-color-item-check-qty-in-stock').removeClass('choose-color-selected');
                $(this).addClass('choose-color-selected');
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-check-qty-in-stock',
                    type: 'POST',
                    data: {'id_sp': id_sp},
                    success:function(data){

                        var areaId = $('#area-selected').attr('data-id') == null ? null : $('#area-selected').attr('data-id');

                        // ngừng kinh doanh
                        if(data == 'false'){
                            $('#check-qty-in-stock-branch').hide();
                            $('#check-qty-in-stock-status').html('<div class="required price-color text-center p-5">Màu sắc này đã ngừng kinh doanh.</div>')
                        }

                        // màu sắc hết hàng
                        else if(data.length == 0){
                            $('#check-qty-in-stock-branch').hide();
                            $('#check-qty-in-stock-status').html('<div class="required price-color text-center p-5">Màu sắc này tạm hết hàng.</div>')
                        }

                        // còn hàng
                        else {
                            $('.list-branch').children().remove();
                            $('#check-qty-in-stock-status').html('');

                            for(var i = 0; i < data.length; i++){
                                var branch = $('<div class="single-branch default-cs" data-area="'+data[i]['id_tt']+'">' +
                                                    '<i class="fas fa-store mr-10"></i>'+data[i]['diachi']+
                                                '</div>');
                                branch.appendTo($('.list-branch'));
                            }

                            loadBranchList(areaId, $($('#area-selected[data-flag="1"]').children()[0]).text());
                            $('#check-qty-in-stock-branch').show();
                        }
                    }
                })
            });
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

        // hiển thị đánh giá sản phẩm
        if($('#total_rating').length){
            var total_rating = parseInt($('#total_rating').val());

            // tổng số lượng đánh giá từng sao
            var total_star_5 = 5 * parseInt($('#percent-5-star').data('id'));
            var total_star_4 = 4 * parseInt($('#percent-4-star').data('id'));
            var total_star_3 = 3 * parseInt($('#percent-3-star').data('id'));
            var total_star_2 = 2 * parseInt($('#percent-2-star').data('id'));
            var total_star_1 = 1 * parseInt($('#percent-1-star').data('id'));

            // tính sao trung bình = tổng của tổng số lượng đánh giá từng sao / tổng số đánh giá
            var avg_star = (total_star_5 + total_star_4 + total_star_3 + total_star_2 + total_star_1) / total_rating;
            avg_star = avg_star.toString();

            // nếu như là số lẻ thì làm tròn 1 chữ số
            if(avg_star.indexOf('.') != -1){
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
                    $('.star-rating[data-id="'+i+'"]').css('color' , 'orange');
                }
            }, 
            // mouse leave
            function(){
                $('.star-rating').removeAttr('style');
                var star = $('#star_rating').val();
                if(star != 0){
                    for(var i = 1; i <= star; i++){
                        $('.star-rating[data-id="'+i+'"]').css('color' , 'orange');
                    }
                } else {
                    $('.star-rating').removeAttr('style');
                }
            }
        );

        // chọn sao
        $('.star-rating').off('click').click(function(){
            if($('.star_rating_required').length){
                $('.star_rating_required').remove();
            }
            var star = $(this).data('id');
            $('#star_rating').val(star);

            //$('.star-rating').removeAttr('style');

            for(var i = 1; i <= star; i++){
                $('.star-rating[data-id="'+i+'"]').css('color' , 'orange');
            }
        });

        $('#btn-photo-attached').click(function(){
            $('.upload-evaluate-image').trigger('click');
        });

        // hiển thị modal chọn sản phẩm đánh giá
        $('#phone-evaluate-show').click(function(){
            $('#phone-evaluate-modal').modal('show');
        });

        // chọn sản phẩm muốn đánh giá
        $('.phone-evaluate').off('click').click(function(){
            removeRequried($('.phone-evaluate-div'));

            // gỡ chọn
            if($(this).hasClass('phone-evaluate-selected')){
                $(this).removeClass('phone-evaluate-selected');
                $('#all_phone_evaluate').prop('checked', false);
            } else {
                $('#all_phone_evaluate').prop('checked', false);
                $(this).addClass('phone-evaluate-selected');
            }            
        });

        // chọn tất cả sản phẩm muốn đánh giá
        $('#all_phone_evaluate').click(function(){
            if($(this).is(':checked')){
                $('.phone-evaluate').addClass('phone-evaluate-selected');
                removeRequried($('.phone-evaluate-div'));
            } else {
                $('.phone-evaluate').removeClass('phone-evaluate-selected');
            }
        });

        // chọn sản phẩm đánh giá
        $('#choose-phone-evaluate').click(function(){
            // chưa chọn
            if($('.phone-evaluate.phone-evaluate-selected').length == 0){
                if($('.phone-evaluate-div').hasClass('required')){
                    return;
                }
                $('.phone-evaluate-div').addClass('required');
                $('.phone-evaluate-div').after('<span class="required-text">Vui lòng ít nhất 1 sản phẩm</span>');
                return;
            }

            // các sản phẩm được chọn
            var lst_id = [];
            for(var i = 0; i < $('.phone-evaluate.phone-evaluate-selected').length; i++){
                lst_id.push($($('.phone-evaluate.phone-evaluate-selected')[i]).data('id'));
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-choose-phone-to-evaluate',
                type: 'POST',
                data: {'lst_id': lst_id},
                success:function(data){
                    $('#phone-evaluate-div').children().remove();

                    if(data.length == 1){
                        var elmnt = $('<div class="d-flex align-items-center">'+
                                            '<img src="images/phone/'+data[0]['hinhanh']+'" alt="" width="35px">'+
                                            '<div>'+data[0]['tensp']+' - '+data[0]['mausac']+'</div>'+
                                            '<div id="phone-evaluate-show" type="button" class="main-color-text ml-10">Thay đổi</div>'+
                                        '</div>');
                        elmnt.appendTo($('#phone-evaluate-div'));
                    } else {
                        var elmnt = $('<span class="d-flex">'+
                                            '<div class="d-flex border p-10">'+
                                                '<div class="mr-20">Đánh giá cho '+data.length+' sản phẩm</div>'+
                                                '<div id="phone-evaluate-show" type="button" class="main-color-text">Thay đổi</div>'+
                                            '</div>'+
                                        '</span>');
                        elmnt.appendTo($('#phone-evaluate-div'));
                    }

                    $('#phone-evaluate-modal').modal('hide');
                    $('#phone-evaluate-div').attr('data-flag', '1');
                    $('#choose-phone-evaluate-required').remove();
                    $('#lst_id').val(lst_id);
                    
                    //console.log(data);
                }
            });
        });

        $('#phone-evaluate-div').bind('DOMSubtreeModified', function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                $('#phone-evaluate-div').click(function(){
                    $('#phone-evaluate-modal').modal('show');
                });
            },100);
        });

        // thêm hình đánh giá
        var arrayEvaluateImage = [];
        
        $('.upload-evaluate-image').change(function(){
            //số lượng hình upload
            var count = this.files.length;

            // tổng số lượng hình hiện tại
            var qty_img = parseInt($('.qty-img-inp').val());

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
                $('#info-modal-content').text('Bạn chỉ được phép chọn 3 ảnh đính kèm');
                $('#info-modal-main-btn').attr('dismiss', 'true');
                $('#info-modal-main-btn').text('Đã hiểu');
                $('#info-modal').modal('show');
            }

            // tạo thẻ div, nút xóa, hình đánh giá
            for(var i = 0; i < count; i++){
                // nếu số lượng hình > 3 thì hiển thị modal thông báo
                if(qty_img >= 3){
                    $('#info-modal-content').text('Bạn chỉ được phép chọn 3 ảnh đính kèm');
                    $('#info-modal-main-btn').attr('dismiss', 'true');
                    $('#info-modal-main-btn').text('Đã hiểu');
                    $('#info-modal').modal('show');
                    break;
                }

                var id = (qty_img + i) + 1;

                // hình đánh giá
                var imageEvaluate = $('<div id="img-rating-'+id+'" class="img-rating">' +
                                            '<div class="img-rating-overlay"></div>'+
                                            '<div type="button" class="btn-remove-single-img"><i class="fal fa-times-circle fz-30"></i></div>' +
                                            '<img class="w-100" src="'+URL.createObjectURL(this.files[i])+'">' +
                                        '</div>');

                imageEvaluate.appendTo('.evaluate-img-div');

                // mảng hình đánh giá
                getBase64FromUrl(URL.createObjectURL(this.files[i]), function(data){
                    arrayEvaluateImage.push(data);
                });

                qty_img++;
            }


            $('.qty-img-inp').val(qty_img);

            // hiển thị số lượng hình ảnh đang có
            $('.qty-img').html('(' + qty_img + ')');
        });
        
        // xóa từng hình
        $('.evaluate-img-div').on('DOMSubtreeModified', function(){
            $('.btn-remove-single-img').off('click').click(function(){
                var id = $(this).parent().attr('id');
                var index = $('#' + id).index();

                console.log(index);

                // cập nhật mảng hình đánh giá
                arrayEvaluateImage.splice(index, 1);

                // xóa input hình
                $('.array_evaluate_image').children().remove();

                if(arrayEvaluateImage.length == 0){
                    $('.upload-evaluate-image').val(null);
                }

                // tổng số lượng hình hiện tại
                var qty_img = parseInt($('.qty-img-inp').val());

                // số lượng hình -1
                qty_img--;

                // cập nhật hiển thị số lượng
                $('.qty-img').html('(' + qty_img + ')');

                $(this).parent('div').remove();
                $('.qty-img-inp').val(qty_img);

                if($('.qty-img-inp').val() == 0){
                    $('.evaluate-img-div').children().remove();
                    $('.qty-img').hide();
                }
            });
        });

        // gửi đánh giá
        $('#send-evaluate-btn').click(function(){
            var valiStarRating = validateStarRating($('#star_rating'));
            var valiPhoneEvaluate = validatePhoneEvaluate($('#phone-evaluate-div'));

            $('.array_evaluate_image').children().remove();

            // chuyển hình đánh giá -> base64
            // for(var i = 0; i < arrayEvaluateImage.length; i++){
            //     // tạo các input chứa base64 string từng hình
            //     var imageInp = $('<input type="hidden" data-index="'+i+'" name="image_evaluate[]" value="'+arrayEvaluateImage[i]+'">');
            //     $('.array_evaluate_image').append(imageInp);
            // }

            if(valiStarRating && valiPhoneEvaluate){
                setTimeout(() => {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/ajax-create-evaluate',
                        type: 'POST',
                        data:{  
                                'lst_id': $('#lst_id').val(),
                                'evaluateStarRating': $('#star_rating').val(),
                                'evaluateContent': $('#evaluate_content').val(),
                                'evaluateImage' : arrayEvaluateImage},
                        success:function(){
                            sessionStorage.setItem('reload_chitiet', 'Đã đánh giá sản phẩm');
                            location.reload();
                            // sessionStorage.setItem("reload_chitiet", "true");
                            // location.reload();
                        }
                    });
                }, 200);
                $('.loader').show();
            }
        });

        // kiểm tra đã chọn sao đánh giá chưa
        function validateStarRating(starRating){
            // nếu kiểm tra rồi thì return
            if($('.star_rating_required').length){
                return;
            }

            if(starRating.val() == '0'){
                var required = $('<span class="star_rating_required required-text">Vui lòng chọn đánh giá</span>');
                starRating.after(required);
                return false;
            }

            return true;
        }

        // kiểm tra đã chọn sản phẩm để đánh giá chưa
        function validatePhoneEvaluate(phoneEvaluate) {
            if($('#choose-phone-evaluate-required').length){
                return;
            }

            // chưa chọn
            if(phoneEvaluate.data('flag') == null){
                var required = $('<span id="choose-phone-evaluate-required" class="required-text">Vui lòng chọn sản phẩm đánh giá</span>');
                phoneEvaluate.after(required);
                return false;
            }

            return true;
        }

        // xem ảnh đánh giá
        $('.img-evaluate').off('click').click(function(){
            var id_img = $(this).data('id');
            var id_dg = $(this).data('evaluate');
            seeReviewImage(id_img, id_dg);
        });

        $('#another-review-image').bind('DOMSubtreeModified', function(){
            $('.img-evaluate').off('click').click(function(){
                var id_dg = $(this).data('evaluate');
                var id_img = $(this).data('id');
                seeReviewImage(id_img, id_dg);
            });
        });;

        function seeReviewImage(id_img, id_dg) {
            // id_dg cho nút đóng
            $('.close-see-review-image').attr('evaluate', id_dg);

            // ảnh lớn
            $('#review-image-main').attr('src', $('img[data-id="'+id_img+'"]').attr('src'));

            // ảnh nhỏ
            $('#another-review-image').children().remove();
            var evaluateImage = $('img[data-evaluate="'+id_dg+'"]');
            evaluateImage.clone().appendTo($('#another-review-image'));

            // đánh dấu ảnh đang xem
            $('img[data-evaluate="'+id_dg+'"]').removeClass('img-evaluate-selected');
            $('img[data-id="'+id_img+'"]').addClass('img-evaluate-selected');

            $('body').css('overflow', 'hidden');
            $('.backdrop').css('z-index', 110);
            $('.backdrop').fadeIn();
            $('.see-review-image').show('drop');
        }

        // đóng xem ảnh đánh giá
        $('.close-see-review-image').click(function(){
            $('.backdrop').fadeOut();
            $('.see-review-image').hide('drop');
            $('body').removeAttr('style');

            var id_dg = $(this).attr('evaluate');

            // xóa ảnh đang chọn
            $('img[data-evaluate="'+id_dg+'"]').removeClass('img-evaluate-selected');

            $('#another-review-image').children().remove();
        });

        // nút prev, next xem ảnh đánh giá
        $('.prev-see-review-image').click(function(){
            var imgSelected = $('#another-review-image').find($('.img-evaluate.img-evaluate-selected'));

            // hình ảnh phía trước
            var prevImg = imgSelected.prev();

            // đang ở hình ảnh đầu tiên => hiển thị hình cuối cùng
            if(prevImg.length == 0){
                $('#another-review-image').children().last().click();
            } else {
                prevImg.click();
            }
        });
        $('.next-see-review-image').click(function(){
            var imgSelected = $('#another-review-image').find($('.img-evaluate.img-evaluate-selected'));

            // hình ảnh phía sau
            var nextImg = imgSelected.next();

            // đang ở hình ảnh đầu tiên => hiển thị hình cuối cùng
            if(nextImg.length == 0){
                $('#another-review-image').children().first().click();
            } else {
                nextImg.click();
            }
        });

        // so sánh
        $('.compare-btn').off('click').click(function(){
            var currentName = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];
            var compareName = $(this).attr('id').split('_')[1];

            var redirectPage ='/sosanh/' + currentName + 'vs' + compareName;

            location.href = redirectPage;
        });

        // thích bình luận
        $('.like-comment').off('click').click(function(){
            var id_dg = $(this).data('id');

            clearTimeout(timer);
            $('#comment-toast').remove();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-like-comment',
                type: 'POST',
                data: {'id_dg': id_dg},
                success:function(data){
                    // chưa đăng nhập
                    if(data['status'] == 'login required'){
                        $('#info-modal-content').text('Vui lòng đăng nhập để thực hiện chức năng này');
                        var loginBtn = $('<a href="dangnhap" id="" class="main-btn p-10">Đăng nhập</a>');
                        $('#info-modal-main-btn').replaceWith(loginBtn);
                        $('#info-modal').modal('show');
                    }
                    // thích
                    else if(data['status'] == 'like success'){
                        // thay đổi nút
                        $('.like-comment[data-id="'+id_dg+'"]').addClass('liked-comment');
                        $('#like-icon').removeClass('fal fa-thumbs-up').addClass('fas fa-thumbs-up');

                        // tăng lượt thích
                        var qty = parseInt($('.qty-like-comment[data-id="'+id_dg+'"]').text());
                        $('.qty-like-comment[data-id="'+id_dg+'"]').text(++qty);

                        var toast = $('<span id="comment-toast" class="alert-toast">Đã thích</span>');
                        $('#toast').append(toast);
                        showToast('#comment-toast');
                    }
                    // bỏ thích
                    else {
                        // thay đổi nút
                        $('.like-comment[data-id="'+id_dg+'"]').removeClass('liked-comment');
                        $('#like-icon').removeClass('fas fa-thumbs-up').addClass('fal fa-thumbs-up');

                        // giảm lượt thích
                        var qty = parseInt($('.qty-like-comment[data-id="'+id_dg+'"]').text());
                        $('.qty-like-comment[data-id="'+id_dg+'"]').text(--qty);

                        var toast = $('<span id="comment-toast" class="alert-toast">Đã hủy thích</span>');
                        $('#toast').append(toast);
                        showToast('#comment-toast');
                    }
                },
            });
        });

        // phản hồi
        $('.reply-btn').off('click').click(function(){
            var id_dg = $(this).data('id');

            $('.reply-div[data-id="'+id_dg+'"]').toggle('blind');
        });

        // hủy phản hồi
        $('.cancel-reply').off('click').click(function(){
            var id_dg = $(this).data('id');

            $('.reply-div[data-id="'+id_dg+'"]').hide('blind');

            // xóa nội dung
            $('#reply-content-' + id_dg).val('');
        });

        // gửi phản hồi
        $('.send-reply').off('click').click(function(){
            var id_dg = $(this).data('id');

            // kiểm tra chưa nhập
            var replyContent = $('#reply-content-' + id_dg);
            if(replyContent.val().length == 0){
                replyContent.addClass('required');
                replyContent.after('<span class="required-text">Vui lòng nhập câu trả lời</span>');
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-reply',
                type: 'POST',
                data: {'id_dg': id_dg, 'replyContent': replyContent.val()},
                success:function(){
                    sessionStorage.setItem('reload_chitiet', 'Đã trả lời đánh giá');
                    location.reload();
                }
            });
        });

        $('[name="reply-content"]').keyup(function(){
            if($(this).hasClass('required')){
                removeRequried($(this));
            }
        });

        // xem thêm phản hồi
        $('.see-more-reply').click(function(){
            var id_dg = $(this).data('id');
            $('.see-more-reply[data-id="'+id_dg+'"]').remove();
            $('.reply-content-div-hidden[data-id="'+id_dg+'"]').show();
        });

        /*=======================================================
                            chỉnh sửa đánh giá
        =========================================================*/
        
        // chỉnh sửa đánh giá
        $('.edit-evaluate').off('click').click(function(){
            var id_dg = $(this).data('id');
            $('#evaluate_id').val(id_dg);

            // gán sao
            var star = $('#evaluate-rating-' + id_dg).val();
            $('#edit_star_rating').val(star);
            for(var i = 1; i <= star; i++){
                $('.edit-star-rating[data-id="'+i+'"]').css('color' , 'orange');
            }

            // gán nội dung
            var content = $('#evaluate-content-' + id_dg).val();
            $('#edit_evaluate_content').val(content);

            // gán hình
            // số lượng hình
            $('.edit-qty-img-inp').val($('img[data-evaluate="'+id_dg+'"]').length);
            $('.edit-qty-img').show();
            var qty_img = $('.edit-qty-img-inp').val();

            // hiển thị div chứa hình ảnh
            $('.edit-evaluate-img-div').css({
                'display': 'flex',
            });

            // load hình ảnh
            for(var i = 0; i < $('img[data-evaluate="'+id_dg+'"]').length; i++){
                var src = $($('img[data-evaluate="'+id_dg+'"]')[i]).attr('src');

                // hình đánh giá
                var imageEvaluate = $('<div id="edit-img-rating-'+(i + 1)+'" class="img-rating">' +
                                            '<div class="img-rating-overlay"></div>'+
                                            '<div type="button" class="btn-remove-single-img"><i class="fal fa-times-circle fz-30"></i></div>' +
                                            '<img class="w-100" src="'+src+'">' +
                                        '</div>');

                imageEvaluate.appendTo('.edit-evaluate-img-div');

                // thêm hình vào mảng hình đánh giá
                getBase64FromUrl(src, function(dataUrl){
                    arrayBase64.push(dataUrl);
                });
            }

            $('.edit-qty-img-inp').val(qty_img);

            // hiển thị số lượng hình ảnh đang có
            $('.edit-qty-img').html('(' + qty_img + ')');

            // hiển thị modal
            $('#edit-evaluate-modal').modal('show');
        });
        
        // reset modal
        $('#edit-evaluate-modal').on('hidden.bs.modal', function(){
            arrayBase64 = [];
            $('.edit-evaluate-img-div').children().remove();
        });

        // đánh giá sao sản phẩm
        $('.edit-star-rating').hover(
            // mouse enter
            function(){
                $('.edit-star-rating').removeAttr('style');
                var star = $(this).data('id');
                for(var i = 1; i <= star; i++){
                    $('.edit-star-rating[data-id="'+i+'"]').css('color' , 'orange');
                }
            }, 
            // mouse leave
            function(){
                $('.edit-star-rating').removeAttr('style');
                var star = $('#edit_star_rating').val();
                if(star != 0){
                    for(var i = 1; i <= star; i++){
                        $('.edit-star-rating[data-id="'+i+'"]').css('color' , 'orange');
                    }
                } else {
                    $('.edit-star-rating').removeAttr('style');
                }
            }
        );

        // chọn sao
        $('.edit-star-rating').off('click').click(function(){
            var star = $(this).data('id');
            $('#edit_star_rating').val(star);

            $('.edit-star-rating').removeAttr('style');

            for(var i = 1; i <= star; i++){
                $('.edit-star-rating[data-id="'+i+'"]').css('color' , 'orange');
            }
        });

        $('#edit-btn-photo-attached').click(function(){
            $('.edit-upload-evaluate-image').trigger('click');
        });

        // thêm hình đánh giá
        var arrayBase64 = [];
        
        $('.edit-upload-evaluate-image').change(function(){
            //số lượng hình upload
            var count = this.files.length;

            // tổng số lượng hình hiện tại
            var qty_img = parseInt($('.edit-qty-img-inp').val());

            // nếu không chọn hình nào thì thoát 
            if(count == 0){
                return;
            }

            // số lượng hình
            $('.edit-qty-img').show();

            // hiển thị div chứa hình ảnh
            $('.edit-evaluate-img-div').css({
                'display': 'flex',
            });

            // nếu số lượng hình upload > 3 thì hiển thị modal thông báo
            if(count > 3){
                $('#info-modal-content').text('Bạn chỉ được phép chọn 3 ảnh đính kèm');
                $('#info-modal-main-btn').attr('dismiss', 'true');
                $('#info-modal-main-btn').text('Đã hiểu');
                $('#info-modal').modal('show');
            }

            // tạo thẻ div, nút xóa, hình đánh giá
            for(var i = 0; i < count; i++){
                // nếu số lượng hình > 3 thì hiển thị modal thông báo
                if(qty_img >= 3){
                    $('#info-modal-content').text('Bạn chỉ được phép chọn 3 ảnh đính kèm');
                    $('#info-modal-main-btn').attr('dismiss', 'true');
                    $('#info-modal-main-btn').text('Đã hiểu');
                    $('#info-modal').modal('show');
                    break;
                }

                var id = (qty_img + i) + 1;

                // hình đánh giá
                var imageEvaluate = $('<div id="img-rating-'+id+'" class="img-rating">' +
                                            '<div class="img-rating-overlay"></div>'+
                                            '<div type="button" class="btn-remove-single-img"><i class="fal fa-times-circle fz-30"></i></div>' +
                                            '<img class="w-100" src="'+URL.createObjectURL(this.files[i])+'">' +
                                        '</div>');

                imageEvaluate.appendTo('.edit-evaluate-img-div');

                // thêm hình vào mảng hình đánh giá
                getBase64FromUrl(URL.createObjectURL(this.files[i]), function(dataUrl){
                    arrayBase64.push(dataUrl);
                });

                qty_img++;
            }

            $('.edit-qty-img-inp').val(qty_img);

            // hiển thị số lượng hình ảnh đang có
            $('.edit-qty-img').html('(' + qty_img + ')');
        });
        
        // xóa từng hình
        $('.edit-evaluate-img-div').on('DOMSubtreeModified', function(){
            $('.btn-remove-single-img').off('click').click(function(){
                // vị trí hình
                var id = $(this).parent().attr('id');
                var index = $('#' + id).index();

                // xóa base64 tương ứng
                arrayBase64.splice(index ,1);

                // xóa input hình
                $('.edit_array_evaluate_image').children().remove();

                if(arrayBase64.length == 0){
                    $('.edit-upload-evaluate-image').val(null);
                }

                // tổng số lượng hình hiện tại
                var qty_img = parseInt($('.edit-qty-img-inp').val());

                // số lượng hình -1
                qty_img--;

                // cập nhật hiển thị số lượng
                $('.edit-qty-img').html('(' + qty_img + ')');

                // xóa hình
                $(this).parent('div').remove();
                $('.edit-qty-img-inp').val(qty_img);

                if($('.edit-qty-img-inp').val() == 0){
                    $('.edit-evaluate-img-div').children().remove();;
                    $('edit-qty-img').val(0);
                    $('.edit-qty-img').hide();
                }
            });
        });

        // url image => base64
        function getBase64FromUrl(url, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function () {
                var reader = new FileReader();
                reader.onloadend = function () {
                    callback(reader.result);
                }
                reader.readAsDataURL(xhr.response);
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
        }

        // gửi đánh giá chỉnh sửa
        $('#edit-send-evaluate-btn').click(function(){
            $('.edit_array_evaluate_image').children().remove();

            // chuyển hình đánh giá -> base64
            for(var i = 0; i < arrayBase64.length; i++){
                // tạo các input chứa base64 string từng hình
                var imageInp = $('<input type="hidden" data-index="'+i+'" name="edit_image_evaluate[]" value="'+arrayBase64[i]+'">');
                $('.edit_array_evaluate_image').append(imageInp);
            }
            
            $('#edit-evaluate-modal').modal('hide');

            setTimeout(() => {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-edit-evaluate',
                    type: 'POST',
                    data:{  'id_dg': $('#evaluate_id').val(),
                            'evaluateStarRating': $('#edit_star_rating').val(),
                            'evaluateContent': $('#edit_evaluate_content').val(),
                            'evaluateImage' : arrayBase64},
                    success:function(){
                        sessionStorage.setItem("reload_chitiet", "Chỉnh sửa đánh giá thành công");
                        location.reload();
                    }
                });
            }, 200);
            $('.loader').show();
        });

        // xóa đánh giá
        $('.delete-evaluate').off('click').click(function(){
            $('#delete-content').text('Bạn muốn xóa đánh giá này?')
            $('#delete-btn').attr('data-object', 'evaluate');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-modal').modal('show');
        });

    }
    /*============================================================================================================
                                                        Giỏ hàng
    ==============================================================================================================*/
    else if(url == 'giohang'){
        // modal xác nhận xóa giỏ hàng
        $('.remove-all-cart').click(function(){
            $('#delete-content').text('Xóa tất cả sản phẩm trong giỏ hàng?')
            $('#delete-btn').attr('data-object', 'all-cart');
            $('#delete-modal').modal('show');
        });

        // modal xác nhận xóa 1 sản phẩm trong giỏ hàng
        $('.remove-cart-item').click(function(){
            $('#delete-content').text('Bạn có muốn xóa sản phẩm này?')
            $('#delete-btn').attr('data-object', 'item-cart');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-modal').modal('show');
        });
    }
    /*============================================================================================================
                                                        Thanh toán
    ==============================================================================================================*/
    else if(url == 'thanhtoan'){
        console.log(performance.getEntriesByType("navigation")[0].type);
        if(performance.getEntriesByType("navigation")[0].type == "back_forward"){
            location.reload();
        }

        /*=============================================================================
                                        nhận tại nhà
        ===============================================================================*/
        if($('input[name="receive-method"]').is(':checked')){
            var method = $('input[name="receive-method"]:checked').val();
            if(method == 'Giao hàng tận nơi'){
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
            location.href = "diachigiaohang";
        });

        /*=============================================================================
                                  nhận tại cửa hàng
        ===============================================================================*/

        // kiểm tra còn hàng tại chi nhánh không
        $('input[name="branch"]').off('click').click(function(){
            removeRequried($('.list-branch'));

            var id_cn = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-check-qty-in-stock-branch',
                data: {'id': id_cn},
                type: 'POST',
                success:function(data){
                    $('.info-qty-in-stock').children().remove();
                    $('.info-qty-in-stock').attr('data-flag', '0');

                    // hiển thị danh sách đt đã hết hàng
                    for(var i = 0; i < data.length; i++){
                        if(data[i]['trangthai'] == 'Tạm hết hàng'){
                            var elmnt = $('<div class="d-flex price-color pb-10 fz-14">'+
                                                '<img src="images/phone/'+data[i]['hinhanh']+'" width="60px">'+
                                                '<div>'+
                                                    data[i]['tensp'] +' - '+data[i]['mausac']+
                                                    '<div>ram: '+data[i]['ram']+'</div>'+
                                                    '<div class="d-flex align-items-center">'+
                                                        '<b>'+data[i]['trangthai']+'</b>'+
                                                        '<div type="button" data-id="'+data[i]['id']+'" class="checkout-delete-item-cart ml-10 text-decoration-underline price-color">Xóa sản phẩm</div>' +
                                                    '</div>'+
                                                '</div>'+
                                            '</div>');
                            elmnt.appendTo($('.info-qty-in-stock'));

                            // có sản phẩm hết hàng
                            $('.info-qty-in-stock').attr('data-flag', '1');
                        }
                        // không đủ hàng 
                        else if(data[i]['trangthai'] == 'Không đủ'){
                            var elmnt = $('<div class="d-flex price-color pb-10 fz-14">'+
                                                '<img src="images/phone/'+data[i]['hinhanh']+'" width="60px">'+
                                                '<div>'+
                                                    data[i]['tensp'] +' - '+data[i]['mausac']+
                                                    '<div>ram: '+data[i]['ram']+'</div>'+
                                                    '<div class="d-flex align-items-center">'+
                                                        '<b>Chỉ còn '+data[i]['slton']+' sản phẩm</b>'+
                                                        '<div type="button" data-id="'+data[i]['id']+'" class="checkout-delete-item-cart ml-10 text-decoration-underline price-color">Xóa sản phẩm</div>' +
                                                    '</div>'+
                                                '</div>'+
                                            '</div>');
                            elmnt.appendTo($('.info-qty-in-stock'));
                            $('.info-qty-in-stock').attr('data-flag', '1');
                        }
                    }
                    
                    // danh sách dt còn hàng
                    for(var i = 0; i < data.length; i++){
                        if(data[i]['trangthai'] == 'Còn hàng'){
                            var elmnt = $('<div class="d-flex pb-10 fz-14">'+
                                                '<img src="images/phone/'+data[i]['hinhanh']+'" width="60px">'+
                                                '<div>'+
                                                    data[i]['tensp'] +' - '+data[i]['mausac']+
                                                    '<div>ram: '+data[i]['ram']+'</div>'+
                                                    '<div class="d-flex align-items-center">'+
                                                        '<b>'+data[i]['trangthai']+'</b>'+
                                                        '<i class="fas fa-check-circle success-color-2 ml-10"></i>' +
                                                    '</div>'+
                                                '</div>'+
                                            '</div>');
                            elmnt.appendTo($('.info-qty-in-stock'));
                        }
                    }
                    $('.info-qty-in-stock').parent().removeClass('none-dp');
                }
            });
        });

        // xóa sản phẩm đã hết hàng tại chi nhánh
        $('.info-qty-in-stock').bind('DOMSubtreeModified', function(){
            $('.checkout-delete-item-cart').off('click').click(function(){
                $('#delete-btn').attr('data-object', 'item-cart');
                $('#delete-btn').attr('data-id', $(this).data('id'));
                $('#delete-btn').click();
            });
        });

        // xác nhận thanh toán
        $('#btn-confirm-checkout').click(function(){
            // tổng tiền
            $('#cartTotal').val($('#total').data('total'));

            // hình thức nhận hàng
            var receciveMethod = $('input[name="receive-method"]:checked').val();
            
            if(receciveMethod == 'Giao hàng tận nơi'){
                // chưa có địa chỉ giao hàng
                if($('.atHome').data('flag') == 0){
                    $('.atHome').addClass('required');
                    var required = $('<span class="required-text">Bạn chưa có địa chỉ giao hàng</span>');
                    $('.atHome').after(required);
                    return;
                }

                // hình thức nhận hàng
                $('#receciveMethod').val('Giao hàng tận nơi');

                checkout();
            }
            // nhận tại cửa hàng
            else {
                var areaSelected = $('#area-selected');
                var storeSelected = $('input[name="branch"]');
                var valiChooseStore = validateReceiveAtStore(areaSelected, storeSelected);

                // nếu chưa đủ thông tin hoặc thông tin không hợp lệ
                if(valiChooseStore){
                    // có sản phẩm đã hết hàng tại chi nhánh
                    if($('.info-qty-in-stock').data('flag') == '1'){
                        $('#info-modal-content').text('Bạn không thể thanh toán khi sản phẩm đã hết hàng hoặc số lượng không đủ.');
                        $('#info-modal-main-btn').attr('dismiss', 'true');
                        $('#info-modal-main-btn').text('Đã hiểu');
                        $('#info-modal').modal('show');
                        return;
                    }
                    // hình thức nhận hàng
                    $('#receciveMethod').val('Nhận tại cửa hàng');
                    $('#id_cn').val($('input[name="branch"]:checked').attr('id').split('-')[1]);

                    checkout();
                } else {
                    $(window).scrollTop(0);
                }
            }
        });

        function checkout(){
            // phương thức thanh toán
            var paymentMethod = $('input[name="payment-method"]:checked').val();
            
            // thanh toán khi nhận hàng | zalo pay
            paymentMethod == 'cash' ? $('#paymentMethod').val('cash') : $('#paymentMethod').val('zalo-pay');

            $('#checkout-form').submit();
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
    }
    /*============================================================================================================
                                                    Địa chỉ giao hàng
    ==============================================================================================================*/
    else if(url == 'diachigiaohang'){
        // chọn địa chỉ giao hàng
        $('.choose-address-delivery').click(function(){
            $('#address_id').val($(this).data('id'));
            $('#change-address-delivery-form').submit();
        });
    }
    /*============================================================================================================
                                                        So sánh
    ==============================================================================================================*/
    else if(url == 'sosanh'){
        // tìm kiếm điện thoại để so sánh
        $('#compare-search-phone').keyup(function(){
            var data = $(this).val();
            if(data == ''){
                setTimeout(() => {
                    $('.compare-list-search-phone').children().remove();
                }, 500);
                $('.compare-list-search-phone').hide('blind');
                
            } else{
                // <div class='compare-single-phone'>iPhone</div>
                clearTimeout(timer);
                timer = setTimeout(() => {
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
                            $('.compare-list-search-phone').children().remove();
                            if(data['phone'].length == 0){
                                $('.compare-list-search-phone').hide('blind');
                                return;
                            }

                            var count = data['phone'].length;
                            for(var i = 0; i < count; i++){
                                var phone = $('<div type="button" data-name="'+data['phone'][i]['tensp_url']+'" class="head-single-result black fz-14">' +
                                                '<div class="d-flex">' +
                                                    '<div class="w-25 p-10">' +
                                                        '<img src="'+ data['url_phone'] + data['phone'][i]['hinhanh'] +'" alt="">' +
                                                    '</div>' +
                                                    '<div class="d-flex flex-column w-75 p-10">' +
                                                        '<b>'+ data['phone'][i]['tensp'] +'</b>' +
                                                        '<div class="d-flex align-items-center mt-5">' +
                                                            '<span class="price-color fw-600">'+ numberWithDot(data['phone'][i]['gia']) +'<sup>đ</sup></span>' +
                                                            '<span class="text-strike ml-10">'+ numberWithDot(data['phone'][i]['giakhuyenmai']) +'<sup>đ</sup></span>' +
                                                            '<span class="price-color ml-10">-'+ (data['phone'][i]['khuyenmai'] * 100) + '%</span>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>');
                                    
                                phone.appendTo($('.compare-list-search-phone'));
                            }
                            $('.compare-list-search-phone').scrollTop(0);

                            $('.compare-list-search-phone').show('blind');
                        }
                    });
                }, 300);
            }
        });

        // thêm điện thoại để so sánh
        $('.compare-list-search-phone').bind('DOMSubtreeModified', function(){
            $('.head-single-result').off('click').click(function(){
                var order = $('.compare-btn-add-phone').attr('data-order');
                if(order == 2){
                    var url = window.location.pathname.split('/')[2];
                    var currentName = url.split('vs')[0];
                    var compareName = $(this).data('name');
    
                    location.href = 'sosanh/' + currentName +'vs' + compareName;
                } else {
                    var url = window.location.pathname.split('/')[2];
                    var currentName = url.split('vs')[0];
                    var compareName = url.split('vs')[1];
                    var thirdName = $(this).data('name');

                    location.href = 'sosanh/' + currentName +'vs' + compareName + 'vs' + thirdName;
                }
            });
        });

        // nút xem so sánh cấu hình chi tiết
        $('.compare-btn-see-detail').click(function(){
            $(this).css('display', 'none');
            $('.compare-detail').css('display', 'table-row');
        });

        // xóa điện thoại so sánh
        $('.delete-compare-btn').off('click').click(function(){
            // thứ tự điện thoại
            var order = $(this).data('order');

            // nếu xóa dt thứ 2 mà đt thứ 3 k có thì hiển thị modal chọn đt
            if(order == 2 && $('.delete-compare-btn[data-order="'+(order + 1)+'"]').length == 0){
                $('.compare-btn-add-phone').attr('data-order', order);
                $('#compare-modal').modal('show');
            }
            // xóa đt theo order ở url
            else {
                var url = window.location.pathname.split('/')[2];
                var lst_urlName = url.split('vs');
                lst_urlName.splice(order - 1, 1);
                location.href = 'sosanh/' + lst_urlName[0] +'vs' + lst_urlName[1];
            }
        });

        // mdoal thêm điện thoại để so sánh
        $('.compare-btn-add-phone').click(function(){
            $('#compare-modal').modal('show');
        });
    }
    /*============================================================================================================
                                                        Tra cứu
    ==============================================================================================================*/
    else if(url == 'tracuu'){
        $('#imei-inp').keyup(function(){
            removeRequried($(this));
        });
    
        // tra cứu imei
        $('#btn-check-imei').click(function(){
            var IMEI = $('#imei-inp').val();
            var required;
    
            // nếu chưa nhập IMEI
            if(IMEI == ''){
                required = $('<div class="required-text text-center">Số IMEI không được bỏ trống</div>');
                $('#imei-inp').addClass('required');
                $('#imei-inp').after(required);
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-check-imei',
                type: 'POST',
                data: {'imei': IMEI},
                success:function(data){
                    // imei không hợp lệ
                    if(data['status'] == 'invalid imei'){
                        required = $('<div class="required-text text-center">Số IMEI không hợp lệ</div>');
                        $('#imei-inp').addClass('required');
                        $('#imei-inp').after(required);
                    }
                    // hợp lệ
                    else{
                        console.log(data);
                        $('#check-imei').hide();

                        // trạng thái bảo hành
                        $warrantyStatus = data['product']['trangthaibaohanh'] == 'true' ? true : false;
                        
                        var elmnt = '<div class="col-md-8 mx-auto">'+
                                        '<div class="d-flex">'+
                                            '<div class="w-50">'+
                                                '<img src="images/phone/'+data['product']['hinhanh']+'" alt="" class="w-80 center-img">'+
                                                '<div class="d-flex flex-column justify-content-center align-items-center">'+
                                                    '<div class="fz-26 fw-600 pt-20 pb-20">'+data['product']['tensp']+'</div>'+
                                                    '<div class="d-flex pb-10">'+
                                                        '<div>Màu sắc: <b>'+data['product']['mausac']+'</b></div>'+
                                                        '<div class="ml-20">Dung lượng: <b> '+data['product']['dungluong']+'</b></div>'+
                                                    '</div>'+
                                                    '<div class="pb-10">IMEI: <b>'+IMEI+'</b></div>'+
                                                    '<div id="btn-check-another-imei" class="main-color-text pointer-cs">Kiểm tra số IMEI khác<i class="far fa-chevron-right ml-10"></i></div>'+ 
                                                '</div>'+
                                            '</div>'+
                                            '<div class="w-50">'+
                                                '<div class="fz-26 fw-600">'+
                                                    '<i class="fas fa-shield-check mr-10"></i>Bảo hành'+
                                                '</div>'+
                                                '<div class="d-flex fz-20 mt-10">'+
                                                    '<div>Trạng thái bảo hành:</div>';
                                                    elmnt += $warrantyStatus == true ? '<b class="success-color-2 ml-10">Trong bảo hành</b>' : '<b class="warning-color ml-10">Hết hạn bảo hành</b>';
                                                    elmnt += '</div>'+
                                                '<div class="mt-10">Bảo hành: <b>'+data['product']['baohanh']+'</b></div>'+
                                                '<div class="d-flex mt-10">'+
                                                    '<div>Bắt đầu: <b>'+data['product']['ngaymua']+'</b></div>'+
                                                    '<div class="ml-10">Kết thúc: <b>'+data['product']['ngayketthuc']+'</b></div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $(elmnt).appendTo($('#valid-imei'));
                    }
                }
            });
        });
    
        // check imei khác
        $('#valid-imei').bind('DOMSubtreeModified', function(){
            $('#btn-check-another-imei').click(function(){
                $('#imei-inp').val('');
                $('#check-imei').show();
                $('#valid-imei').children().remove();
                $(window).scrollTop(0);
            });
        });
    }

    /*============================================================================================================
                                                        Function
    ==============================================================================================================*/

    // modal info
    $('#info-modal-main-btn').click(function(){
        if($(this).attr('dismiss') == 'true'){
            $('#info-modal').modal('hide');
        }
    });

    // modal thêm địa chỉ mới
    $('#new-address-show').click(function(){
        if($(this).data('default') == true){
            $('#set_default_address').attr('checked', true);
        }

        // tạo mới
        $('input[name="address_type"]').val('create');

        // tiêu đề modal
        $('#address-modal-title').text('Tạo địa chỉ mới');

        // thiết lập nút
        $('.address-action-btn').attr('data-type', 'create');
        $('.address-action-btn').text('Thêm');

        $('#address-modal').modal('show');
    });

    // modal chỉnh sửa địa chỉ
    $('.btn-edit-address').off('click').click(function(){
        var id = $(this).data('id');

        var diachi = $(this).data('diachi');
        var phuongxa = $(this).data('phuongxa');
        var quanhuyen = $(this).data('quanhuyen');
        var tinhthanh = $(this).data('tinhthanh');

        var defaultAdr = $('#address-' + id).data('default');

        editAddressModal(id, defaultAdr, diachi, phuongxa, quanhuyen, tinhthanh);
    });

    function editAddressModal(id, defaultAdr = false, diachi, phuongxa, quanhuyen, tinhthanh){
        // gán tỉnh thành
        $('#TinhThanh-selected').trigger('click');
        $('.option-tinhthanh[data-type="'+tinhthanh+'/TinhThanh"]').trigger('click');

        // gán quận huyện
        setTimeout(() => {
            $('.option-quanhuyen[data-type="'+quanhuyen+'/QuanHuyen"]').trigger('click');
        }, 300);

        // gán phường xã
        setTimeout(() => {
            $('.option-phuongxa[data-type="'+phuongxa+'/PhuongXa"]').trigger('click');
        }, 600);

        // gán địa chỉ
        $('input[name="address_inp"]').val(diachi);

        // tiêu đề modal
        $('#address-modal-title').text('Chỉnh sửa địa chỉ');

        if(defaultAdr == true){
            $('#set_default_address').attr('checked', true);
        } else {
            $('#set_default_address').attr('checked', false);
        }

        // thiết lập nút
        $('.address-action-btn').attr('data-type', 'edit');
        $('.address-action-btn').attr('data-id', id);
        $('.address-action-btn').text('Cập nhật');

        // chỉnh sửa
        $('input[name="address_type"]').val('edit');
        $('input[name="tk_dc_id"]').val(id);

        // gán họ tên, sdt
        $('input[name="adr_fullname_inp"]').val($('#adr-fullname-' + id).text());
        $('input[name="adr_tel_inp"]').val($('#adr-tel-' + id).text());

        $('#address-modal').modal('show');
    }

    // thêm|sửa địa chỉ mới
    $('.address-action-btn').off('click').click(function(){
        var valiFullname = validateFullname($('input[name="adr_fullname_inp"]'));
        var valiTel = validatePhoneNumber($('input[name="adr_tel_inp"]'));
        var valiQuanHuyenPhuongXa = validateDistrict_Wards($('#QuanHuyen-name'), $('#PhuongXa-name'));
        var valiAddressInp = validateAddressInput($('input[name="address_inp"]'));
        var setDefault = $('#set_default_address');
        setDefault.is(':checked') ? setDefault.val(1) : setDefault.val(0);

        if(valiFullname && valiTel && valiQuanHuyenPhuongXa && valiAddressInp){
            $('#address-form').submit();
        }
    });

    $('input[name="adr_fullname_inp"]').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    $('input[name="adr_tel_inp"]').keyup(function(){
        valiPhonenumberTyping($(this));
    });

    $('input[name="address_inp"]').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $(this).next().remove();
        }
    });

    // đặt làm mặc định
    $('.btn-set-default-btn').off('click').click(function(){
        var id = $(this).data('id');
        location.href = 'set-default-address/' + id;
    });

    // modal xóa 1 địa chỉ
    $('.btn-delete-address').off('click').click(function(){
        $('#delete-content').text('Bạn có muốn xóa địa chỉ này ?');
        $('#delete-btn').attr('data-id', $(this).data('id'));
        $('#delete-btn').attr('data-object', 'address');
        $('#delete-modal').modal('show');
    });

    // xóa đối tượng
    $('#delete-btn').click(function(){
        var object = $(this).data('object');

        if(object == 'address'){
            var id = $(this).data('id');
            deleteElement(id, object);
        }
        else if(object == 'all-cart'){
            deleteElement(0, object);
        } else if(object == 'item-cart') {
            var id =  $(this).data('id');
            deleteElement(id, object);
        } else if(object == 'order'){
            var id =  $(this).data('id');
            deleteElement(id, object);
        } else if(object == 'evaluate'){
            var id =  $(this).data('id');
            deleteElement(id, object);
        }
    });

    function deleteElement(id, object) {
        $('#id').val(id);
        $('#object').val(object);

        $('#delete-object-form').submit(); 
    }

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
    $('.option-tinhthanh').off('click').click(function(){
        var id = $(this).attr('id');
        var name = $(this).data('type').split('/')[0];
        var type = $(this).data('type').split('/')[1];

        choosePlace(id, name, type);
    });

    // thay đổi quận, huyện
    $('.option-quanhuyen').off('click').click(function(){
        var id = $(this).attr('id');
        var name = $(this).data('type').split('/')[0];
        var type = $(this).data('type').split('/')[1];

        choosePlace(id, name, type);
    });

    $('#list-quan-huyen').on('DOMSubtreeModified', function(){
        $('.option-quanhuyen').off('click').click(function(){
            var id = $(this).attr('id');
            var name = $(this).data('type').split('/')[0];
            var type = $(this).data('type').split('/')[1];
            choosePlace(id, name, type);
        });
    });

    // thay đổi phường, xã
    $('#list-phuong-xa').on('DOMSubtreeModified', function(){
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
            $('input[name="TinhThanh_name_inp"]').val(name);
            $('#TinhThanh-box').toggle('blind', 250);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-change-location',
                type: 'POST',
                cache: false,
                data: {'type':type,'id':id},
                success:function(data){
                    $('#QuanHuyen-box').toggle('blind', 250);
                    $('#list-quan-huyen').children().remove();
                    $('#QuanHuyen-name').text('Chọn Quận / Huyện');
                    $('#QuanHuyen-name').removeAttr('data-flag');

                    $('#list-phuong-xa').children().remove();
                    $('#PhuongXa-name').text('Chọn Phường / Xã');
                    $('#PhuongXa-name').removeAttr('data-flag');
                    $('#PhuongXa-selected').addClass('select-disable').removeClass('select-selected');
                    
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
            $('input[name="QuanHuyen_name_inp"]').val(name);
            $('#QuanHuyen-name').attr('data-flag', '1');

            if($('#QuanHuyen-name').parent().hasClass('required')){
                $('#QuanHuyen-name').parent().removeClass('required');
                $('#QuanHuyen-name').parent().next().remove();
            }

            $('#QuanHuyen-box').toggle('blind', 250);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-change-location',
                type: 'POST',
                cache: false,
                data: {'type':type,'id':id},
                success:function(data){
                    $('#PhuongXa-selected').removeClass('select-disable');
                    $('#PhuongXa-selected').addClass('select-selected');
                    $('#list-phuong-xa').children().remove();

                    $('#PhuongXa-name').text('Chọn Phường / Xã');
                    $('#PhuongXa-name').removeAttr('data-flag');
                    for(var i = 0; i < data.length; i++){
                        var div = $('<div>',{
                            id: data[i]['ID'],
                            'data-type': data[i]['Name'] + '/PhuongXa',
                            class: 'option-phuongxa select-single-option',
                            text: data[i]['Name']
                        });
                        div.appendTo($('#list-phuong-xa'));
                    }
                    $('#PhuongXa-box').toggle('blind', 250);
                }
            });
        } else {
            $('#PhuongXa-name').text(name);
            $('input[name="PhuongXa_name_inp"]').val(name);
            $('#PhuongXa-name').attr('data-flag', '1');
            if($('#PhuongXa-name').parent().hasClass('required')){
                $('#PhuongXa-name').parent().removeClass('required');
                $('#PhuongXa-name').parent().next().remove();
            }
            $('#PhuongXa-box').toggle('blind', 250);
            $('input[name="address_inp"]').focus();
        }
    }

    $('input[name="address_inp"]').focus(function(){
        $('#TinhThanh-box').hide('blind', 250);
        $('#QuanHuyen-box').hide('blind', 250);
        $('#PhuongXa-box').hide('blind', 250);
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
        }
        // không đúng định dạng | ký tự đầu k phải số 0 
        else if(!telInp.val().match(phoneno) || telInp.val().charAt(0) != 0){
            if(telInp.next().hasClass('required-text')){
                return;
            }
            required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');    
            telInp.addClass('required');
            telInp.after(required);
        } 
        // hợp lệ
        else {
            telInp.removeClass('required');
            telInp.next().remove();
        }
    }

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

    function validatePassword(passwordInp, rePasswordInp){
        var pw = passwordInp.val();
        var rePw = rePasswordInp.val();

        if(passwordInp.hasClass('required') || rePasswordInp.hasClass('required')){
            return;
        }

        // chưa nhập mật khẩu
        if(pw == ''){
            passwordInp.addClass('required');
            var errMess = $('<div class="required-text">Vui lòng nhập mật khẩu</div>');
            passwordInp.after(errMess);
            return false;
        }

        // độ dài tối thiểu
        if(pw.length < 6 || pw.length > 16){
            passwordInp.addClass('required');
            var errMess = $('<div class="required-text">Độ dài mật khẩu từ 6-16 ký tự</div>');
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

        return true;
    }

    // hiển thị toast
    function showToast(id){
        setTimeout(() => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                // xóa toast
                setTimeout(() => {
                    $(id).remove();
                },100);
    
                $(id).css({
                    'transform': 'translateY(100px)'
                });
            }, 5000);
    
            $(id).css({
                'transform': 'translateY(0)'
            });
        }, 200);
    }

    function numberWithDot(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // tổng tiền giỏ hàng
    if($('#total').length){
        var isVoucher = $('#total').data('voucher');
        var provisional = $('#provisional').data('provisional');
        var total = 0;

        // có sử dụng voucher
        if(isVoucher == '1'){
            var discountCode = $('#voucher').data('voucher');
            
            total = provisional - (provisional * discountCode);
            $('#total').html(numberWithDot(total) + 'đ'.sup());
            $('#total').attr('data-total', total);
        } else {
            total = provisional;
            $('#total').html(numberWithDot(total) + 'đ'.sup());
            $('#total').attr('data-total', total);
        }
    }

    // sử dụng voucher
    $('.use-voucher-btn').off('click').click(function(){
        var id_vc = $(this).data('id');
        
        location.href = 'use-voucher/' + id_vc;
    });

    $('.choose-voucher-div').bind('DOMSubtreeModified', function(){
        $('.use-voucher-btn').off('click').click(function(){
            var id_vc = $(this).data('id');
            
            location.href = 'use-voucher/' + id_vc;
        });
    });
    
    // cập nhật số lượng
    $('.update-qty').off('click').click(function(){
        var component = $(this).data('id').split('_')[0];

        if(component == 'color'){
            var qty = parseInt($('#qty').text());
            if($(this).hasClass('plus')){
                // kiểm tra nếu có số lượng tối đa
                if($('#max-qty').val() != ''){
                    // vượt số lượng tối đa
                    if(qty >= $('#max-qty').val()){
                        clearTimeout(timer);
                        $('.tooltip-qty').text('Số lượng tối đa có thể mua là ' + $('#max-qty').val());
                        $('.tooltip-qty').show();
                        timer = setTimeout(() => {
                            $('.tooltip-qty').hide('fade');
                        }, 3000);
    
                        return;
                    } 
                }
                
                $('#qty').text(++qty);
            } else {
                if(qty == 1){
                    return;
                }

                $('#qty').text(--qty);
            }
        } else {
            var id = $(this).data('id').split('_')[1];
            
            var type = $(this).hasClass('plus') ? 'plus' : 'minus';

            if(type == 'minus' && parseInt($('#qty_' + id).text()) == 1){
                $('#delete-content').text('Bạn có muốn xóa sản phẩm này?')
                $('#delete-btn').attr('data-object', 'item-cart');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {id:id, type:type},
                url: '/ajax-update-cart',
                success:function(data){
                    // số lượng sản phẩm
                    $('#qty_' + id).text(data['newQty']);

                    // thành tiền sản phẩm
                    $('#provisional_' + id).html(numberWithDot(data['newPrice']) + 'đ'.sup());

                    // tạm tính
                    $('#provisional').html(numberWithDot(data['provisional']) + 'đ'.sup());
                    $('#provisional').attr('data-provisional', data['provisional']);

                    // tổng tiền
                    var total = 0;
                    var provisional = data['provisional'];

                    // có sử dụng voucher
                    if($('#total').data('voucher') == '1'){
                        var discountCode = parseFloat($('#voucher').data('voucher'));
                        total = provisional - (provisional * discountCode);
                    } else {
                        total = provisional;
                    }

                    // cập nhật voucher thỏa điều kiện
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/ajax-check-voucher-conditions',
                        type: 'POST',
                        data: {'cartTotal': provisional},
                        success:function(data){
                            $('.choose-voucher-div').children().remove();
                            $('.choose-voucher-div').append(data);
                        }
                    });
                    
                    $('#total').html(numberWithDot(total) + 'đ'.sup());
                }
            });
        }
    });

    // hiển thị khu vực
    $('#area-selected').click(function(){
        $('#area-box').toggle('blind', 250);
    });

    // chọn khu vực
    $('.option-area').off('click').click(function(){
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
        $('#area-selected').attr('data-id', areaID);
        $('input[name="branch"]:checked').prop('checked', false);

        var parent = $('.list-branch');
        var count = parent.children().length;

        if(count == 0){
            return;
        }
        
        for(var i = 0; i < count; i++){
            var element = $(parent.children()[i]);
            if(element.attr('data-area') == areaID){
                element.show();
                element.attr('data-show', '1');
            } else {
                element.hide();
                element.removeAttr('data-show', '1');
            }
        }

        // không còn hàng tại chi nhánh
        if($('.single-branch[data-show="1"]').length == 0){
            var branch = $('<div class="single-branch default-cs" data-area="'+areaID+'">' +
                                '<div class="text-center">Không có chi nhánh còn hàng</div>'+
                            '</div>');
            branch.appendTo($('.list-branch'));
            loadBranchList(areaID, name);
        }

        $('.list-branch').show('blind', 250);
    }

    // kiểm tra họ tên
    function validateFullname(fullName){
        // nếu đã kiểm tra rồi thì return
        if(fullName.hasClass('required')){
            return;
        }

        // nếu chưa nhập họ tên
        if(fullName.val().length == 0){
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

    // kiểm tra đã chọn quận huyện, phường xã chưa
    function validateDistrict_Wards(district, wards){
        // nếu kiểm tra rồi thì return
        if(district.parent().hasClass('required') || wards.parent().hasClass('required')){
            return;
        }

        // chưa chọn quận huyện
        if(district.attr('data-flag') == null){
            district.parent().addClass('required');
            required = $('<span class="required-text">Vui lòng chọn Quận / Huyện</span>');
            district.parent().after(required);
            return false;
        }

        // chưa chọn phường xã
        if(wards.attr('data-flag') == null){
            wards.parent().addClass('required');
            required = $('<span class="required-text">Vui lòng chọn Phường / Xã</span>');
            wards.parent().after(required);
            return false;
        }

        return true;
    }

    // kiểm tra nhập số nhà, tên đường
    function validateAddressInput(address){
        // nếu đã kiểm tra rồi thì return
        if(address.hasClass('required')){
            return;
        }

        // nếu chưa nhập
        if(address.val() == ''){
            address.addClass('required');
            required = $('<span class="required-text">Vui lòng nhập địa chỉ</span>');
            address.after(required);
            return false;
        }

        return true;
    }

    if($('#success-img').length){
        setTimeout(() => {
            $('#success-checkout').css('opacity', '1');
        },1200);
    }

    function removeRequried(element){
        if(element.hasClass('required')){
            element.removeClass('required');
            element.next().remove();
        }
    }

    // nút mua ngay
    $('.buy-now').off('click').click(function(){
        var id_sp = $(this).data('id');

        buyNow(id_sp);
    });

    function buyNow(id_sp) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"').attr('content')
            },
            url: '/ajax-buy-now',
            type: 'POST',
            data: {'id_sp': id_sp},
            success:function(data){
                // yêu cầu đăng nhập
                if(data['status'] == 'login required'){
                    $('#info-modal-main-btn').replaceWith($('<a href="/dangnhap" id="info-modal-main-btn" class="main-btn p-10 w-100">Đăng nhập</a>'));
                    $('#info-modal-content').text('Vui lòng đăng nhập để thực hiện chức năng này');
                    $('#info-modal').modal('show');
                }
                // hết hàng
                else if(data['status'] == 'out of stock'){
                    $('#info-modal-content').text('Màu sắc này hiện đang tạm hết hàng.');
                    $('#info-modal-main-btn').attr('dismiss', 'true');
                    $('#info-modal-main-btn').text('Đã hiểu');
                    $('#info-modal').modal('show');
                }
                // thêm giỏ hàng
                else {
                    location.href = '/giohang';
                }
            }
        });
    }
});





