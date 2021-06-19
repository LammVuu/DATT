
//===== Preloader
$(window).on('load', function(){
    $('.loader').fadeOut(250);
});

$(function() {
    var url = window.location.pathname.split('/')[1];

    if (window.location.hash == '#_=_') {
        window.location.hash = ''; // for older browsers, leaves a # behind
        history.pushState('', document.title, window.location.pathname); // nice and clean
        e.preventDefault(); // no page reload
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
                                                            Header
        ==============================================================================================================*/
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
                                                            '<span class="price-color font-weight-600">'+ numberWithDot(data['phone'][i]['gia']) +'<sup>đ</sup></span>' +
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
                                                            Index
        ==============================================================================================================*/

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
    }
    /*============================================================================================================
                                                        Tài khoản
    ==============================================================================================================*/
    else if(url == 'taikhoan'){
        /*==================================================================================
                                        thông tin tài khoản
        //==================================================================================*/
        var modal_avt = $('#change-avt');
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

        // hiển thị modal tiến hành cắt ảnh dại diện
        $('#change-avt-inp').change(function(e){
            typeModal = $(this).data('modal');
            image = document.getElementById('pre-avt-big');
            startCropImg(cropper_avt, e);
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

        // thay đổi thông tin tài khoản
        $('#btn-change-info').click(function(){
            $('#change-info-div').toggle('blind', 300);
        });

        $('#cancel-change-info').click(function(){
            $('#change-info-div').toggle('blind', 300);
        });

        /*==================================================================================
                                            Thông báo
        //==================================================================================*/

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

        /*==================================================================================
                                        Sản phẩm yêu thích
        //==================================================================================*/

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
                                                    '<span class="font-weight-600 price-color">'+ numberWithDot(data[i]['giakhuyenmai']) +'<sup>đ</sup></span>' +
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

        $('#lst_product').bind('DOMSubtreeModified', function(){
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

        $('#phone-color').bind('DOMSubtreeModified', function(){
            $('.choose-color-item').off('click').click(function(){
                $('#phone-color').removeClass('required');
                $('#phone-color').next().remove();
                $('.choose-color-item').removeClass('choose-color-selected');
                $(this).addClass('choose-color-selected');
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

            $('#choose-color-modal').modal('hide');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax-add-cart',
                type: 'POST',
                data: {id_sp:id_sp, sl:sl},
                success:function(data){
                    if(data['status'] == false){
                        $('#login-requied-modal').modal('show');
                        return;
                    } else if(data['status'] == 'success'){ // cập nhật số lượng badge giỏ hàng
                        var qtyHeadCart = parseInt($('.head-qty-cart').text());

                        if(!qtyHeadCart){
                            var elmnt = $('<span class="head-qty-cart">1</span>');
                            elmnt.appendTo($('.head-cart'));
                        } else {
                            $('.head-qty-cart').text(++qtyHeadCart);
                        }
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

        $('#choose-color-modal').on('hidden.bs.modal', function(){
            $('#choose-color-phone-name').text('');
            $('#choose-color-promotion-price').text('');
            $('#choose-color-price').text('');
            $('#phone-color').text('');
            $('#qty').text('1');
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
            var currentName = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];
            var compareName = $(this).attr('id').split('_')[1];

            var redirectPage ='/sosanh/' + currentName + 'vs' + compareName;

            location.href = redirectPage;
        });
    }
    /*============================================================================================================
                                                        Giỏ hàng
    ==============================================================================================================*/
    else if(url == 'giohang'){
        // modal xác nhận xóa giỏ hàng
        $('.remove-all-cart').click(function(){
            $('#remove-cart-title').text('Xóa tất cả sản phẩm trong giỏ hàng?')
            $('.btn-remove-cart').attr('data-type', 'all');
            $('#remove-cart-modal').modal('show');
        });

        // modal xác nhận xóa 1 sản phẩm trong giỏ hàng
        $('.remove-cart-item').click(function(){
            $('#remove-cart-title').text('Bạn có muốn xóa sản phẩm này?')
            $('.btn-remove-cart').attr('data-type', 'item');
            $('.btn-remove-cart').attr('data-id', $(this).data('id'));
            $('#remove-cart-modal').modal('show');
        });

        // xóa sản phẩm trong giỏ hàng
        $('.btn-remove-cart').click(function(){
            var type = $(this).data('type');

            if(type == 'all'){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax-remove-all-cart',
                    success:function(data){
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {id_sp: $(this).data('id')},
                    url: '/ajax-remove-cart-item',
                    success:function(data){
                        location.reload();
                    }
                });
            }
        });
    }
    /*============================================================================================================
                                                        Thanh toán
    ==============================================================================================================*/
    else if(url == 'thanhtoan'){
        /*=============================================================================
                                        nhận tại nhà
        ===============================================================================*/
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
    }
    /*============================================================================================================
                                                        So sánh
    ==============================================================================================================*/
    else if(url == 'sosanh'){
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
    }
    /*============================================================================================================
                                                        Tra cứu
    ==============================================================================================================*/
    else if(url == 'tracuu'){
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
    }

    /*============================================================================================================
                                                        Function
    ==============================================================================================================*/

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

    function numberWithDot(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // cập nhật số lượng
    $('.update-qty').off('click').click(function(){
        var component = $(this).data('id').split('_')[0];

        if(component == 'color'){
            var qty = parseInt($('#qty').text());
            if($(this).hasClass('plus')){    
                $('#qty').text(++qty);
            } else {
                if(qty == 1){
                    return;
                }

                $('#qty').text(--qty);
            }
        } else {
            var id_sp = $(this).data('id').split('_')[1];
            
            var type = $(this).hasClass('plus') ? 'plus' : 'minus';

            if(type == 'minus' && parseInt($('#qty_' + id_sp).text()) == 1){
                $('#remove-cart-title').text('Bạn có muốn xóa sản phẩm này?')
                $('.btn-remove-cart').attr('data-type', 'item');
                $('.btn-remove-cart').attr('data-id', id_sp);
                $('#remove-cart-modal').modal('show');
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {id_sp:id_sp, type:type},
                url: '/ajax-update-cart',
                success:function(data){
                    $('#qty_' + id_sp).text(data['newQty']);
                    $('#provisional_' + id_sp).html(numberWithDot(data['newPrice'] + 'đ'.sup()));
                    $('#provisional').html(numberWithDot(data['newPrice'] + 'đ'.sup()));
                    $('#total').html(numberWithDot(data['newPrice'] + 'đ'.sup()));
                }
            });
        }
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

    if($('#success-img').length){
        setTimeout(() => {
            $('#success-checkout').css('opacity', '1');
        },1000);
    }
});





