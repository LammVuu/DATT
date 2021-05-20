
//===== Preloader
// $(window).on('load', function(){
//     $('.loader').fadeOut(250);
// });


$(function() {

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
        items:4,
        loop:true,
        dots:false,
        nav: false,
        autoplay:true,
        autoplayTimeout:1500,
        autoplayHoverPause:true,
        smartSpeed: 1000,
    });

    //===================================================== Header ============================================================

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

    // dropdown điện thoại
    $('.head-phone-drop').hover(function(){
       $('.head-phone-drop-content').css('display', 'block'); 
    }, function(){
       $('.head-phone-drop-content').css('display', 'none'); 
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
    });
    $('.head-search-input').focusout(function(){
        $('.backdrop').fadeOut();
        $('.head-search-result').css('display', 'none');
    })
    $('.head-search-input').keyup(function(){
        if($(this).val() == ''){
            $('.head-search-result').css('display', 'none');
        } else {
            $('.head-search-result').css('display', 'block');
        }
    });

    //===================================================== Index ============================================================

    $('#index-promotion-carousel').owlCarousel({
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

    var owl_promotion = $('#index-promotion-carousel');
    owl_promotion.owlCarousel();

    $('#prev-owl-carousel').on('click', function(){
        owl_promotion.trigger('prev.owl.carousel', [300]);
    });

    $('#next-owl-carousel').on('click', function(){
        owl_promotion.trigger('next.owl.carousel');
    });

    //===================================================== Account ============================================================

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
                            showToast('#avt-toast', closeToast);
                            refreshZoomVal();
                        } else {
                            $('.account-cover-img').css('background-image', 'url(' + data + ')');
                            showToast('#cover-toast', closeToast);
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
    function showToast(id, close){
        $(id).css({
            'transform': 'translateY(0)'
        });
        close();
    }

    // hàm callback tự động đóng toast sau 5s
    function closeToast(){
        setTimeout(function(){
            $('.alert-toast').css({
                'transform': 'translateY(100px)'
            });
        }, 5000);
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
            $(this).html('Xem thêm');
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

    //===================================================== Shop ============================================================
    
    // nút hiển thị bộ lọc
    $('#btn-show-filter').click(function(){
        // nếu sắp xếp đang mở thì ẩn
        if($('#btn-show-sort').attr('aria-expanded') == 'true'){
            $('.shop-sort-box').hide();
            $('#btn-show-sort').attr('aria-expanded', 'false');
        }
        if($(this).attr('aria-expanded') == 'false'){
            $('.shop-filter-box').show('blind', 250);
            $(this).attr('aria-expanded', 'true');
        } else {
            $('.shop-filter-box').hide('blind', 250);
            $(this).attr('aria-expanded', 'false');
        }
    });

    // nút hiển thị sắp xếp
    $('#btn-show-sort').click(function(){
        if($('#btn-show-filter').attr('aria-expanded') == 'true'){
            $('.shop-filter-box').hide();
            $('#btn-show-filter').attr('aria-expanded', 'false');
        }
        if($(this).attr('aria-expanded') == 'false'){
            $('.shop-sort-box').show('blind', 250);
            $(this).attr('aria-expanded', 'true');
        } else {
            $('.shop-sort-box').hide('blind', 250);
            $(this).attr('aria-expanded', 'false');
        }
    });

    // ẩn/hiện nút xem kết quả lọc
    $('input[name="checkbox-filter"]').click(function(){
        var count = $('#flag').val();
        if($(this).prop('checked') == true){
            $('#flag').val(++count);
            $('.shop-btn-see-result').css('display', 'block');
        } else {
            $('#flag').val(--count);
        }
        if(count == 0){
            $('.shop-btn-see-result').css('display', 'none');
        }
    })

    // gỡ bỏ chọn tất cả checkbox bộ lọc
    $('.shop-btn-remove-filter').click(function(){
        $('input[name="checkbox-filter"]').each(function(){
            this.checked = false;
            $('.shop-btn-see-result').css('display', 'none');
            $('#flag').val(0);
        });
    });

    //===================================================== Chi tiết ============================================================
    
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

    $('.another-img').click(function(){
        var data = $(this).attr('src');
        $('#main-img').attr('src', data);
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

    $('.detail-upload-link').click(function(){
        $('.upload-inp').trigger('click');
    });

    $('.btn-expanded-evaluate').click(function(){
        if($(this).attr('aria-expanded') == 'true'){
            $('.evaluate-img-div').hide('blind', 250);
            $(this).attr('aria-expanded', 'false');
            $('.expand-icon').css('transform' , 'rotate(180deg)');
        } else {
            $('.evaluate-img-div').show('blind', 250);
            $(this).attr('aria-expanded', 'true');
            $('.expand-icon').css('transform' , 'rotate(0)');
        }
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

        $('.btn-expanded-evaluate').css('display', 'block');
        $('.evaluate-img-div').css({
            'display': 'flex',
            'flex-wrap': 'wrap',
        });
        $('.btn-remove-all').css('display', 'block');

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
    
    // xóa tất cả hình ảnh đánh giá
    $('.btn-remove-all').click(function(){
        hideEvaluateDiv();
    });

    function hideEvaluateDiv(){
        $('.evaluate-img-div').html('');
        $('#qty-img').val(0);

        // ẩn các nút
        $('.btn-expanded-evaluate').css('display', 'none');
        $('.evaluate-img-div').css('display', 'none');
        $('.btn-remove-all').css('display', 'none');
    }

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
                                    '<textarea name="" id="" class="form-control" rows="3" placeholder="Viết câu trả lời"></textarea>'+
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
    

    //===================================================== Giỏ hàng ============================================================

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

    //===================================================== Thanh toán ============================================================

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

    //================================ nhận tại cửa hàng ==========================

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
        if($('#area-name').parent().hasClass('required')){
            $('#area-name').parent().removeClass('required');
            $('#area-name').parent().next().hide();
        }
        $('#area-name').attr('data-flag', '1');

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

    $('input[name="branch"]').click(function(){
        if($(this).parent().parent().hasClass('required')){
            $(this).parent().parent().removeClass('required');
            $(this).parent().parent().next().hide();
        }
    });

    $('#btn-confirm-checkout').click(function(){
        var receciveMethod = $('input[name="receive-method"]:checked').val();
        
        if(receciveMethod == 'atStore'){
            var fullName = $('input[id="HoTen"]');
            var tel = $('#SDT');
            var areaSelected = $('#area-name');
            var storeSelected = $('input[name="branch"]');

            var valiName = validateFullname(fullName);
            var valiPhone = validatePhoneNumber(tel);
            var valiSelect = validateReceiveAtStore(areaSelected, storeSelected);
        }

        if(valiName && valiSelect){
            
        } else {
            $(window).scrollTop(0);
        }
    });

    // kiểm tra nhận tại cửa hàng có rỗng không
    function validateReceiveAtStore(areaSelected, storeSelected){   
        if(areaSelected.attr('data-flag') == null){
            areaSelected.parent().addClass('required');
            areaSelected.parent().next().show();
            return false;
        } if(!storeSelected.is(':checked')){
            storeSelected.parent().parent().addClass('required');
            storeSelected.parent().parent().next().show();
            return false; 
        }

        return true;
    }

    // kiểm tra họ tên
    function validateFullname(fullName){
        if(fullName.val() == ''){
            fullName.addClass('required');
            fullName.next().show();
            return false;
        }

        return true;
    }

    // kiểm tra số điện thoại
    function validatePhoneNumber(tel){
        var length = tel.val().length;
        var phoneno = /^\d{10}$/;

        if(tel.length == 0){
            tel.addClass('required');
            tel.next().show();
            return false;
        } else if(!tel.val().match(phoneno)){
            tel.addClass('required');
            tel.next().next().show();
            return false;
        } else if(length < 10){
            tel.addClass('required');
            tel.next().next().show();
            return false;
        }

        return true;
    }

    $('input[name="checkout-inp"]').keyup(function(){
        $(this).removeClass('required');
        $(this).next().hide();
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
    

    //===================================================== Compare ============================================================
    
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

    //===================================================== Check IMEI ============================================================

    $('#imei-inp').keyup(function(){
        if($(this).hasClass('required')){
            $(this).removeClass('required');
            $('.required-text').hide();
        }
    });

    // 
    $('#btn-check-imei').click(function(){
        var IMEI = $('#imei-inp').val();

        if(IMEI == ''){
            $('#imei-inp').addClass('required');
            $('#imei-inp').next().show();
            return;
        }

        $('#check-imei').hide();
        $('#valid-imei').removeAttr('class');
    });

    $('#btn-check-imei-2').click(function(){
        $('#imei-inp').val('');
        $('#check-imei').show();
        $('#valid-imei').addClass('none-dp');
        $(window).scrollTop(0);
    });
});





