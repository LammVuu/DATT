$(function() {
    var url = window.location.pathname.split('/')[2];
    if(url == undefined){
        url = '';
    }
    console.log(url);

    $(window).on('load', function(){
        $('.loader').fadeOut(250);
        // cuộn sidebar tới link đang chọn
        setTimeout(() => {
            var position = $('.sidebar-link.sidebar-link-selected').position().top;
            if(position > 700){
                $('.sidebar.custom-scrollbar').animate({scrollTop: position});
            }
        }, 300);
    });


    const SUCCESS = '#D2F4EA';
    const DANGER = '#F8D7DA';
    const CREATE_MESSAGE = 'Thêm thành công';
    const EDIT_MESSAGE = 'Chỉnh sửa thành công';
    const DELETE_MESSAGE = 'Xóa thành công';

    var loadMoreFlag = false;

    // hiển thị button cuộn lên đầu
    $(window).scroll(function(e){
        var scrollTop = $(window).scrollTop();
        var docHeight = $(document).height();
        var winHeight = $(window).height();
        var scrollPercent = (scrollTop) / (docHeight - winHeight);
        var scrollPercentRounded = Math.round(scrollPercent*100);

        // hiển thị button cuộn
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

        // load more
        if(scrollPercentRounded >= 80){
            if(!loadMoreFlag){
                loadMoreFlag = true;
                $('#loadmore').show();

                if(url == 'donhang'){
                    if($('#lst_data').attr('data-loadmore') != 'done'){
                        var row = $('#lst_data').children().length;
                        var sort = $('input[name="sort"]:checked').val();
                        if(sort == undefined){
                            sort = '';
                        }
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/ajax-load-more',
                            type: 'POST',
                            data: {'url': url, 'row': row, 'sort': sort},
                            success:function(data){
                                loadMoreFlag = false;
                                if(data != 'done'){
                                    $('#lst_data').append(data);
                                } else {
                                    $('#lst_data').attr('data-loadmore', 'done');
                                    $('#loadmore').hide();
                                }
                            }
                        });
                    } else {
                        $('#loadmore').hide();
                    }
                } else if(url == 'sanpham'){
                    if($('#lst_data').attr('data-loadmore') != 'done'){
                        var row = $('#lst_data').children().length;
                        var sort = $('input[name="sort"]:checked').val();
                        if(sort == undefined){
                            sort = '';
                        }
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/ajax-load-more',
                            type: 'POST',
                            data: {'url': url, 'row': row, 'sort': sort},
                            success:function(data){
                                loadMoreFlag = false;
                                if(data != 'done'){
                                    $('#lst_data').append(data);
                                } else {
                                    $('#lst_data').attr('data-loadmore', 'done');
                                    $('#loadmore').hide();
                                }
                            }
                        });
                    } else {
                        $('#loadmore').hide();
                    }
                } else if(url == 'imei'){
                    if($('#lst_data').attr('data-loadmore') != 'done'){
                        var row = $('#lst_data').children().length;
                        var keyword = $('#search').val().toLocaleLowerCase();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/ajax-load-more',
                            type: 'POST',
                            data: {'url': url, 'row': row, 'keyword': keyword},
                            success:function(data){
                                loadMoreFlag = false;
                                if(data != 'done'){
                                    $('#lst_data').append(data);
                                } else {
                                    $('#lst_data').attr('data-loadmore', 'done');
                                    $('#loadmore').hide();
                                }
                            }
                        });
                    } else {
                        $('#loadmore').hide();
                    }
                } else {
                    if($('#lst_data').attr('data-loadmore') != 'done'){
                        var row = $('#lst_data').children().length;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/ajax-load-more',
                            type: 'POST',
                            data: {'url': url, 'row': row},
                            success:function(data){
                                loadMoreFlag = false;
                                if(data != 'done'){
                                    $('#lst_data').append(data);
                                } else {
                                    $('#lst_data').attr('data-loadmore', 'done');
                                    $('#loadmore').hide();
                                }
                            }
                        });
                    } else {
                        $('#loadmore').hide();
                    }
                }
            }
        }
    });

    // xử lý cuộn lên đầu trang
    $('#btn-scroll-top').on('click', function(){
        $(window).scrollTop(0);
    });

    var timer = null;

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
            }, 3000);
    
            $(id).css({
                'transform': 'translateY(0)'
            });
        }, 200);
    }

    function removeRequried(element){
        if(element.hasClass('required')){
            element.removeClass('required');
            element.next().remove();
        }
    }

    // kiểm nhập tên
    function validateName(Name){
        // nếu đã kiểm tra rồi thì return
        if(Name.hasClass('required')){
            $('.modal-body').animate({scrollTop: Name.position().top});
            return false;
        }

        // nếu chưa nhập tên
        if(Name.val().length == 0){
            var required = $('<span class="required-text">Vui lòng nhập tên</span>');
            Name.addClass('required');
            Name.after(required);
            $('.modal-body').animate({scrollTop: Name.position().top});
            return false;
        }

        // tên không hợp lệ
        if(!isNaN(Name.val())){
            var required = $('<span class="required-text">Tên không hợp lệ</span>');
            Name.addClass('required');
            Name.after(required);
            $('.modal-body').animate({scrollTop: Name.position().top});
            return false;
        }

        return true;
    }

    // kiểm tra số điện thoại
    function validatePhoneNumber(tel){
        // nếu đã kiểm tra rồi thì return
        if(tel.hasClass('required')){
            return false;
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
    function valiPhonenumberTyping(tel){
        if(tel.hasClass('required')){
            tel.next().remove();
        }

        var phoneno = /^\d{10}$/;
        var required; 

        // chưa nhập
        if(tel.val() == ''){
            required = $('<span class="required-text">Vui lòng nhập số diện thoại</span>');
            tel.addClass('required');
            tel.after(required);
        }
        // không đúng định dạng | ký tự đầu k phải số 0 
        else if(!tel.val().match(phoneno) || tel.val().charAt(0) != 0){
            if(tel.next().hasClass('required-text')){
                return;
            }
            required = $('<span class="required-text">Số diện thoại không hợp lệ</span>');    
            tel.addClass('required');
            tel.after(required);
        } 
        // hợp lệ
        else {
            tel.removeClass('required');
            tel.next().remove();
        }
    }

    // bẫy lỗi địa chỉ
    function validateAddress(address) {
        if(address.hasClass('required')){
            $('.modal-body').animate({scrollTop: address.position().top});
            return false;
        }

        // chưa nhập
        if(address.val() == ''){
            address.addClass('required');
            address.after('<span class="required-text">Vui lòng nhập địa chỉ</span>');
            $('.modal-body').animate({scrollTop: address.position().top});
            return false;
        }

        // không hợp lệ
        if(!isNaN(address.val())){
            address.addClass('required');
            address.after('<span class="required-text">Địa chỉ không hợp lệ</span>');
            $('.modal-body').animate({scrollTop: address.position().top});
            return false;
        }

        return true;
    }

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

    // kiểm tra email
    function validateEmail(email) {
        if(email.hasClass('required')){
            return false;
        }

        // chưa nhập
        if(email.val().length == 0){
            email.addClass('required');
            email.after('<span class="required-text">Vui lòng nhập Email<span>');
            return false;
        }

        // không hợp lệ
        if(!isNaN(email.val())){
            email.addClass('required');
            email.after('<span class="required-text">Email không hợp lệ<span>');
            return false;
        }

        // không có @
        if(email.val().indexOf('@') == -1){
            email.addClass('required');
            email.after('<span class="required-text">Email không đúng định dạng<span>');
            return false;
        }

        return true;
    }

    // bẫy lỗi chiết khẩu
    function validateDiscount(discount) {
        if(discount.hasClass('required')){
            return false;
        }

        // chưa nhập
        if(discount.val() == ''){
            discount.addClass('required');
            discount.after('<span class="required-text">Vui lòng nhập chiết khẩu</span>');
            return false;
        }

        // không hợp lệ
        if(discount.val() > 100){
            discount.addClass('required');
            discount.after('<span class="required-text">Chiết khấu không hợp lệ</span>');
            return false;
        }

        return true;
    }

    // bẫy lỗi ngày bắt đầu
    function validateDateStart(date) {
        if(date.hasClass('required')){
            return false;
        }

        // chưa nhập
        if(date.val() == ''){
            date.addClass('required');
            date.after('<span class="required-text">Vui lòng chọn ngày bắt đầu</span>');
            return false;
        }

        return true;
    }

    // bẫy lỗi ngày kết thúc
    function validateDateEnd(dateEnd, dateStart) {
        if(dateEnd.hasClass('required')){
            return false;
        }

        // chưa chọn
        if(dateEnd.val() == ''){
            dateEnd.addClass('required');
            dateEnd.after('<span class="required-text">Vui lòng chọn ngày kết thúc</span>');
            return false;
        }

        // ngày kết thúc < ngày bắt đầu
        if(dateStart.val() != ''){
            if(dateEnd.val() < dateStart.val()){
                dateEnd.addClass('required');
                dateEnd.after('<span class="required-text">Ngày kết thúc không hợp lệ</span>');
                return false;
            }
        }

        return true;
    }

    function capitalize (text) {
        var textArray = text.split(' ');
        var capitalizedText = '';
        for (var i = 0; i < textArray.length; i++) {
          capitalizedText += textArray[i].charAt(0).toUpperCase() + textArray[i].slice(1) + ' '
        }
        return capitalizedText.trim();
    }
    
    /*=======================================================================================================================
                                                           Header
    =======================================================================================================================*/
    
    // đóng/mở sidebar menu
    $('#btn-expand-menu').click(function(){
        // đóng
        if($(this).attr('aria-expanded') == 'true'){
            $('.sidebar-avt').hide();
            $('.sidebar-menu').hide();
            $('.sidebar').css('width', '0');
            $('.content').css('margin-left', '0');
            $(this).attr('aria-expanded', 'false');
        }
        // mở
        else {
            $('.content').css('margin-left', '250px');
            $('.sidebar').css('width', '250px');
            setTimeout(() => {
                $('.sidebar-avt').fadeIn(); 
                $('.sidebar-menu').fadeIn();
            },100);
            
            $(this).attr('aria-expanded', 'true');
        }
    });
    
    // đóng/mở tùy chọn tài khoản
    $('#btn-expand-account').click(function(){
        $('.account-option').toggle('blind', 300);
    });
    /*=======================================================================================================================
                                                           Dashboard
    =======================================================================================================================*/
    if(url == ''){
        // phần trăm trạng thái đơn hàng
        orderStatusPercent();

        function orderStatusPercent(){
            // tổng số lượng đơn hàng
            var total = $('#total-order').val();
            // số lượng đơn tiếp nhận
            var receivedQty = $('#received-order').data('qty');
            // số lượng đơn xác nhận
            var confirmedQty = $('#confirmed-order').data('qty');
            // số lượng đơn thành công
            var successQty = $('#successfull-order').data('qty');
            // số lượng đơn đã hủy
            var cancelledQty = $('#cancelled-order').data('qty');
            console.log(total, receivedQty, confirmedQty, successQty, cancelledQty);

            var avg = 0;
            // progress bar tiếp nhận
            avg = (receivedQty / total) * 100;
            $('.received-progress-bar').css('width', avg + '%');

            // progress bar xác nhận
            avg = (confirmedQty / total) * 100;
            $('.confirmed-progress-bar').css('width', avg + '%');

            // progress bar thành công
            avg = (successQty / total) * 100;
            $('.success-progress-bar').css('width', avg + '%');

            // progress bar tiếp nhận
            avg = (cancelledQty / total) * 100;
            $('.cancelled-progress-bar').css('width', avg + '%');
        }

        // area chart
        var arrSalesData = $('#sales-data').val().split('-');
        console.log(arrSalesData);
        
        var salesChart = new Chart($('#sales-chart')[0], {
            type: 'line',
            data: {
                labels: [
                    'T1',
                    'T2',
                    'T3',
                    'T4',
                    'T5',
                    'T6',
                    'T7',
                    'T8',
                    'T9',
                    'T10',
                    'T11',
                    'T12',
                ],
                datasets: [{
                    label: 'Doanh thu',
                    backgroundColor: '#9EF1F4',
                    borderColor: '#9EF1F4',
                    fill: {
                        target: 'origin',
                        above: '#9EF1F4',
                        below: '#9EF1F4'
                      },
                    data: arrSalesData
                }]
            }, options: {
            }
        });

        // thay đổi năm thống kê
        $('#sales-year').change(function(){
            var year = $(this).val();
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/ajax-get-sales-of-year',
                type: 'POST',
                data: {'year': year},
                success: function(data){
                    console.log(data);
                    // không có dữ liệu
                    if(data == ''){
                        setTimeout(() => {
                            if(!$('#sales-chart').next().length){
                                var elmnt = $('<div class="pt-50 fz-20 text-center">Không có dữ liệu</div>');
                                elmnt.show('fade');
                                $('#sales-chart').after(elmnt);
                            }
                        }, 300);
                        $('#sales-chart').hide();
                    } else {
                        $('#sales-chart').next().remove();
                        $('#sales-chart').show();

                        data = data.split('-');
                        salesChart.data.datasets[0].data = data;
                        salesChart.reset();
                        salesChart.update();
                    }
                    
                }
            })
        });

        /*Donut chart*/
        window.areaChart = Morris.Donut({
            element: 'branch-chart',
            redraw: true,
            data: [
                { label: "Samsung", value: 50 },
                { label: "Apple", value: 15 },
                { label: "Oppo", value: 20 },
                { label: "Xiaomi", value: 10 },
                { label: "Vivo", value: 5 },
            ],
            colors: ['#5FBEAA', '#34495E', '#FF9F55']
        });
    }
    /*=======================================================================================================================
                                                           Mẫu sp
    =======================================================================================================================*/
    else if(url == 'mausanpham'){
        // mô tả mẫu sp
        let editor;
        ClassicEditor
            .create(document.querySelector('#mausp_des'))
            .then(newEditor => {
                editor = newEditor;
                $('#modal').on('hidden.bs.modal', function(){
                    newEditor.setData('');
                });
            })
            .catch( error => {
                console.error( error );
            });

        // hiển thị modal tạo mới mẫu sp
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới mẫu sản phẩm');

            // trạng thái = 1
            $('#mausp_status').hide();
            $('label[for="mausp_status"]').hide();

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal xem chi tiết mẫu sp
        $('.info-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            $('#modal-title').text('Chi tiết mẫu sản phẩm');
            bindMausp(id, true);
        });

        // hiển thị modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa mẫu sản phẩm');
            bindMausp(id);
        });

        // hiển thị modal xóa
        $('.delete-btn').click(function(){
            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa mẫu sản phẩm này?');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-modal').modal('show');
        });

        function bindMausp(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/mausanpham/ajax-get-mausp',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // gán dữ liệu cho modal

                    $('#mausp_name').val(data.tenmau);
                    $('#mausp_name').attr('readonly', bool);

                    $('#mausp_supplier option[value="'+data.id_ncc+'"]').prop('selected', true);
                    $('#mausp_supplier').attr('disabled', bool);

                    if(data.mota != null){
                        editor.setData(data.mota);
                    }

                    $('#mausp_youtube').val(data.id_youtube);
                    $('#mausp_youtube').attr('readonly', bool);
                    showYoutubeVideo(data.id_youtube);

                    $('#mausp_warranty option[value="'+data.baohanh+'"]').prop('selected', true);
                    $('#mausp_warranty').attr('disabled', bool);
                    
                    $('#mausp_warranty_address').val(data.diachibaohanh);
                    $('#mausp_warranty_address').attr('readonly', bool);

                    $('#mausp_status').show();
                    $('label[for="mausp_status"]').show();
                    $('#mausp_status option[value="'+data.trangthai+'"]').prop('selected', true);
                    $('#mausp_status').attr('disabled', bool);

                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiName = validateName($('#mausp_name'));

            // bẫy lỗi xong kiểm tra loại
            if(valiName){
                var tenmau = $('#mausp_name').val();
                var id_ncc = $('#mausp_supplier').val();
                var id_youtube = $('#mausp_youtube').val();
                var baohanh = $('#mausp_warranty').val();
                var diachibaohanh = $('#mausp_warranty_address').val();
                var trangthai = $('#mausp_status').val();

                var data = {
                    'tenmau': tenmau,
                    'mota': editor.getData(),
                    'id_ncc': id_ncc,
                    'id_youtube': id_youtube,
                    'baohanh': baohanh,
                    'diachibaohanh': diachibaohanh,
                    'trangthai': trangthai,
                };
                
                $('.loader').show();

                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/mausanpham',
                        type: 'POST',
                        data:data,
                        success:function(data){
                            $('.loader').fadeOut();
                            // trùng tên
                            if(data == 'invalid name'){
                                $('#mausp_name').addClass('required');
                                $('#mausp_name').after('<span class="required-text">Tên mẫu sản phẩm đã tồn tại</span>');
                                return;
                            }

                            $('#modal').modal('hide');

                            // render dòng mới vào view
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="create-mausp-toast" class="alert-toast-right alert-toast-right-success">Thêm mới thành công</span>');
                            showToast('#create-mausp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/mausanpham/'+id,
                        type: 'PUT',
                        data: {
                            'id': id,
                            'tenmau': tenmau,
                            'mota': '',
                            'id_ncc': id_ncc,
                            'id_youtube': id_youtube,
                            'baohanh': baohanh,
                            'diachibaohanh': diachibaohanh,
                            'trangthai': trangthai,
                        },
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế dòng hiện tại bằng dòng mới chỉnh sửa
                            $('tr[data-id="'+id+'"]').replaceWith(data);
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="create-mausp-toast" class="alert-toast-right alert-toast-right-success">Chỉnh sửa thành công</span>');
                            showToast('#create-mausp-toast');


                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        $('#mausp_name').keyup(function(){
            removeRequried($(this));
        });

        $('#mausp_youtube').keyup(function(){
            if($(this).val() == ''){
                setTimeout(() => {
                    $('#youtube_iframe').hide();
                }, 500);
                return;
            }
            showYoutubeVideo($(this).val());
        });

        // reset modal mausp
        $('#modal').on('hidden.bs.modal', function(){
            $('#mausp_name').val('');
            removeRequried($('#mausp_name'));
            $('#mausp_name').attr('readonly', false);

            $($('#mausp_supplier').children()[0]).prop('selected', true);
            $('#mausp_supplier').attr('disabled', false);

            $('#mausp_des').val('');

            $('#mausp_youtube').val('');
            $('#mausp_youtube').attr('readonly', false);
            $('#youtube_iframe').attr('src', '');
            $('#youtube_iframe').hide();

            $($('#mausp_warranty').children()[0]).prop('selected', true);
            $('#mausp_warranty').attr('disabled', false);

            $('#mausp_warranty_address').val('');
            $('#mausp_warranty_address').attr('readonly', false);

            $($('#mausp_status').children()[0]).prop('selected', true);
            $('#mausp_status').attr('disabled', false);

            $('#action-btn').show();
            mota = null;
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal xem chi tiết mẫu sp
            $('.info-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                $('#modal-title').text('Chi tiết mẫu sản phẩm');
                bindMausp(id, true);
            });

            // hiển thị modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa mẫu sản phẩm');
                bindMausp(id);
            });

            // hiển thị modal xóa
            $('.delete-btn').click(function(){
                // gán dữ liệu cho modal xóa
                $('#delete-content').text('Xóa mẫu sản phẩm này?');
                $('#delete-btn').attr('data-id', $(this).data('id'));
                $('#delete-modal').modal('show');
            });

            // phục hồi
            $('.undelete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                restore(id);
            });
        });
        
        function showYoutubeVideo(youtubeId) {
            clearTimeout(timer);
            timer = setTimeout(() => {
                setTimeout(() => {
                    $('#youtube_iframe').show('blind');
                }, 500);
                $('#youtube_iframe').attr('src', 'https://www.youtube.com/embed/' + youtubeId);
            }, 500);
        }

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $('.loader').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/mausanpham/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // nút khôi phục
                    var restoreBtn = $('<div data-id="'+id+'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>');
                    $('.delete-btn[data-id="'+id+'"]').replaceWith(restoreBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Ngừng kinh doanh');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="mausp-toast" class="alert-toast-right alert-toast-right-danger">Xóa thành công</span>');
                    showToast('#mausp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // phục hồi
        $('.undelete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            restore(id);
        });

        function restore(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/mausanpham/ajax-restore',
                type: 'POST',
                data: {'id': id},
                success:function(){
                    $('.loader').fadeOut();

                    // hiện nút xóa
                    var deleteBtn = $('<div data-id="'+id+'" class="delete-btn"><i class="fas fa-trash"></i></div>');
                    $('.undelete-btn[data-id="'+id+'"]').replaceWith(deleteBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Kinh doanh');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="sanpham-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                    showToast('#sanpham-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            });
        }

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                if(Object.keys(arrFilter).length != 0){
                    filter();
                    return;
                }
                var keyword = $(this).val().toLocaleLowerCase();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/mausanpham/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success:function(data){
                        $('#lst_data').append(data);
                        loadMoreFlag = keyword == '' ? false : true;
                        $('#loadmore').hide();
                    }
                });
            },300);

            $('#lst_data').children().remove();
            $('#loadmore').show();
        });

        var arrFilter = {};
        // show lọc
        $('#filter-mausp').click(function(){
            $('.filter-div').toggle('blind');
        });

        // lọc 
        $('[name="filter"]').change(function(){
            var object = $(this).data('object');

            if(object == 'supplier'){
                // thêm
                if($(this).is(':checked')){
                    if(arrFilter.supplier == null){
                        arrFilter.supplier = [];
                    }
                    arrFilter.supplier.push($(this).val());
                }
                // gỡ chọn
                else {
                    var i = arrFilter.supplier.indexOf($(this).val());
                    arrFilter.supplier.splice(i, 1);
                    if(arrFilter.supplier.length == 0){
                        delete arrFilter.supplier;
                    }
                }
            } else {
                // thêm
                if($(this).is(':checked')){
                    if(arrFilter.status == null){
                        arrFilter.status = [];
                    }
                    arrFilter.status.push($(this).val());
                }
                // gỡ chọn
                else {
                    var i = arrFilter.status.indexOf($(this).val());
                    arrFilter.status.splice(i, 1);
                    if(arrFilter.status.length == 0){
                        delete arrFilter.status;
                    }
                }
            }
            filter();
        });

        function filter(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/mausanpham/ajax-filter',
                type: 'POST',
                data: {'arrFilter': arrFilter, 'keyword': $('#search').val().toLocaleLowerCase()},
                success: function(data){
                    $('#lst_data').children().remove();
                    $('#lst_data').append(data);
                    if(Object.keys(arrFilter).length == 0){
                        $('.filter-badge').hide();    
                        loadMoreFlag = false;
                    } else {
                        $('.filter-badge').text(Object.keys(arrFilter).length);
                        $('.filter-badge').show();
                        loadMoreFlag = true;
                    }
                    $('#loadmore').hide();
                }
            });
        }
    }
    /*=======================================================================================================================
                                                           Khuyến mãi
    =======================================================================================================================*/
    else if(url == 'khuyenmai'){
        // hiển thị modal tạo mới khuyến mãi
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới khuyến mãi');

            // thiết lập nút gửi là thêm mới
            $('#action-khuyenmai-btn').attr('data-type', 'create');
            $('#action-khuyenmai-btn').text('Thêm');

            // hiển thị modal
            $('#khuyenmai-modal').modal('show');
        });

        // hiển thị modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa khuyến mãi');
            bindKhuyenMai(id);
        });

        function bindKhuyenMai(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/khuyenmai/ajax-get-khuyenmai',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // gán dữ liệu cho modal

                    $('#khuyenmai_name').val(data.tenkm);
                    $('#khuyenmai_name').attr('readonly', bool);

                    $('#khuyenmai_content').val(data.noidung);
                    $('#khuyenmai_content').attr('readonly', bool);

                    $('#khuyenmai_discount').val(data.chietkhau * 100);
                    $('#khuyenmai_discount').attr('readonly', bool);

                    $('#khuyenmai_start').val(data.ngaybatdau);
                    $('#khuyenmai_start').attr('readonly', bool);

                    $('#khuyenmai_end').val(data.ngayketthuc);
                    $('#khuyenmai_end').attr('readonly', bool);

                    // thiết lập nút gửi là cập nhật
                    $('#action-khuyenmai-btn').attr('data-type', 'edit');
                    $('#action-khuyenmai-btn').text('Cập nhật');
                    $('#action-khuyenmai-btn').attr('data-id', id);
                    
                    console.log(data);

                    // hiển thị modal
                    $('#khuyenmai-modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-khuyenmai-btn').show() : $('#action-khuyenmai-btn').hide();
        }

        // thêm|sửa
        $('#action-khuyenmai-btn').click(function(){
            // bẫy lỗi
            var valiName = validateName($('#khuyenmai_name'));
            var valiContent = validatePromotionContent($('#khuyenmai_content'));
            var valiDiscount = validateDiscount($('#khuyenmai_discount'));
            var valiStart = validateDateStart($('#khuyenmai_start'));
            var valiEnd = validateDateEnd($('#khuyenmai_end'), $('#khuyenmai_start'));

            // bẫy lỗi xong kiểm tra loại
            if(valiName && valiContent && valiDiscount && valiStart && valiEnd){
                var tenkm = $('#khuyenmai_name').val();
                var noidung = $('#khuyenmai_content').val();
                var chietkhau = $('#khuyenmai_discount').val() / 100;
                var ngaybatdau = $('#khuyenmai_start').val();
                var ngayketthuc = $('#khuyenmai_end').val();

                var data = {
                    'tenkm': tenkm,
                    'noidung': noidung,
                    'chietkhau': chietkhau,
                    'ngaybatdau': ngaybatdau,
                    'ngayketthuc': ngayketthuc,
                };
                
                $('.loader').show();

                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/khuyenmai',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // đã tồn tại khuyến mãi
                            if(data == 'already exist'){
                                $('#khuyenmai_name').addClass('required');
                                $('#khuyenmai_name').after('<span class="required-text">Khuyến mãi này đã tồn tại</span>')
                                return;
                            }

                            $('#khuyenmai-modal').modal('hide');
                            
                            // cuộn xuống cuối trang
                            $(document).scrollTop($(document).height());

                            // render dòng mới vào view
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="create-mausp-toast" class="alert-toast-right alert-toast-right-success">Thêm mới thành công</span>');
                            showToast('#create-mausp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/khuyenmai/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // đã tồn tại khuyến mãi
                            if(data == 'already exist'){
                                $('#khuyenmai_name').addClass('required');
                                $('#khuyenmai_name').after('<span class="required-text">Khuyến mãi này đã tồn tại</span>')
                                return;
                            }
                            
                            $('#khuyenmai-modal').modal('hide');

                            // thay thế dòng hiện tại bằng dòng mới chỉnh sửa
                            $('tr[data-id="'+id+'"]').replaceWith(data.html);
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="create-mausp-toast" class="alert-toast-right alert-toast-right-success">Chỉnh sửa thành công</span>');
                            showToast('#create-mausp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi nội dung khuyến mãi
        function validatePromotionContent(promotion) {
            if(promotion.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(promotion.val() == ''){
                promotion.addClass('required');
                promotion.after('<span class="required-text">Vui lòng nhập nội dung khuyến mãi</span>');
                return false;
            }

            return true;
        }

        $('#khuyenmai_name').keyup(function(){
            removeRequried($(this));
        });

        $('#khuyenmai_content').keyup(function(){
            removeRequried($(this));
        });

        $('#khuyenmai_discount').keyup(function(){
            removeRequried($(this));
        });

        $('#khuyenmai_start').click(function(){
            removeRequried($(this));
        });

        $('#khuyenmai_end').click(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#khuyenmai-modal').on('hidden.bs.modal', function(){
            $('#khuyenmai_name').val('');
            $('#khuyenmai_name').attr('readonly', false);
            removeRequried($('#khuyenmai_name'));

            $('#khuyenmai_content').val('');
            $('#khuyenmai_content').attr('readonly', false);
            removeRequried($('#khuyenmai_content'));

            $('#khuyenmai_discount').val('');
            $('#khuyenmai_discount').attr('readonly', false);
            removeRequried($('#khuyenmai_discount'));

            $('#khuyenmai_start').val('');
            $('#khuyenmai_start').attr('readonly', false);
            removeRequried($('#khuyenmai_start'));

            $('#khuyenmai_end').val('');
            $('#khuyenmai_end').attr('readonly', false);
            removeRequried($('#khuyenmai_end'));

            $('#khuyenmai_status option[value="1"]').prop('selected', true);
            $('#khuyenmai_status').attr('disabled', false);

            $('#action-khuyenmai-btn').show();
        });

        // hiển thị modal xóa
        $('.delete-btn').click(function(){
            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa khuyến mãi này?')
            $('#delete-btn').attr('data-object', 'khuyenmai');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-modal').modal('show');
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // hiển thị modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa khuyến mãi');
                bindKhuyenMai(id);
            });

            // modal xem chi tiết mẫu sp
            $('.info-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                $('#modal-title').text('Chi tiết khuyến mãi');
                bindKhuyenMai(id, true);
            });

            // hiển thị modal xóa
            $('.delete-btn').click(function(){
                // gán dữ liệu cho modal xóa
                $('#delete-content').text('Xóa mẫu khuyến mãi này?')
                $('#delete-btn').attr('data-object', 'khuyenmai');
                $('#delete-btn').attr('data-id', $(this).data('id'));
                $('#delete-modal').modal('show');
            });
        });

        // modal xem chi tiết mẫu sp
        $('.info-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            $('#modal-title').text('Chi tiết khuyến mãi');
            bindKhuyenMai(id, true);
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $('.loader').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/khuyenmai/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="mausp-toast" class="alert-toast-right alert-toast-right-danger">Xóa thành công</span>');
                    showToast('#mausp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/khuyenmai/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success:function(data){
                        $('#lst_data').children().remove()
                        $('#lst_data').append(data);
                        loadMoreFlag = true;
                        $('#loadmore').hide();
                    }
                });
            },300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });

        var arrFilter = {};
        // show lọc
        $('.filter-btn').click(function(){
            $('.filter-div').toggle('blind');
        });
    }
    /*=======================================================================================================================
                                                           Sản phẩm
    =======================================================================================================================*/
    else if(url == 'sanpham'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/ajax-get-model-status-false',
                type: 'POST',
                data: {'data': ''},
                success: function(data){
                    if(data.length != 0){
                        for(var i = 0; i < data.length; i++){
                            $('#sanpham_model option[value="'+data[i]+'"]').hide();
                        }
                    }
                }
            });
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới sản phẩm');

            // trạng thái = 1
            $('#sanpham_status').hide();
            $('label[for="sanpham_status"]').hide();

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            $('#product-color-carousel').parent().hide();

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal xem chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            $('#modal-title').text('Chi tiết sản phẩm');
            bindSanPham(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa sản phẩm');
            bindSanPham(id);
        });

        // modal xóa
        $('.delete-btn').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa <b>'+name+'?</b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-btn').attr('data-name', name);
            $('#delete-modal').modal('show');
        });

        // show dialog chọn hình ảnh
        $('#sanpham_choose_image').click(function(){
            $('#sanpham_image').click();
        });

        // chọn hình ảnh
        $('#sanpham_image').change(function(){
            // hủy chọn hình
            if($(this).val() == ''){
                return;
            }

            removeRequried($('#sanpham_review_image'));

            // kiểm tra file hình
            var fileName = this.files[0].name.split('.');
            var extend = fileName[fileName.length - 1];

            // xem trước hình ảnh
            if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#sanpham_review_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                getBase64FromUrl(URL.createObjectURL($('#sanpham_image')[0].files[0]), function(dataUrl){
                    $('#sanpham_image_base64').val(dataUrl);
                });
            }
            // không phải hình ảnh
            else{
                $('#sanpham_review_image').attr('src', 'images/600x600.png');
                $(this).val('');
                alert('file upload không hợp lệ');
            }
        });

        // chọn mẫu sản phẩm và gán khuyến mãi, gán tên sp
        $('#sanpham_model').change(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/ajax-get-promotionID-by-modelID',
                type: 'POST',
                data: {'id': $(this).val()},
                success:function(data){
                    if(data.id_km != null){
                        $('#sanpham_promotion option[value="'+data.id_km+'"]').prop('selected', true);
                    }
                }
            });

            var modelName = $(this).find(':selected').text();
            $('#sanpham_name').val(modelName);
        });

        // chọn file cấu hình
        $('#sanpham_specifications').change(function(){
            $(this).val() != 'create' ? $('#create-specifications-div').hide('blind') : $('#create-specifications-div').show('blind');
        });

        var owl = $('#product-color-carousel');
        owl.owlCarousel({
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

        function bindSanPham(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/sanpham/ajax-get-sanpham',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    var product = data.product;
                    var specifications = data.specifications;

                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    // gán dữ liệu cho modal
                    $('#sanpham_name').val(product.tensp);
                    $('#sanpham_model').val(product.id_msp);
                    $('#sanpham_color').val(product.mausac);
                    $('#sanpham_ram').val(product.ram);
                    $('#sanpham_capacity').val(product.dungluong);
                    $('#sanpham_price').val(product.gia);
                    $('#sanpham_promotion').val(product.id_km);
                    $('#sanpham_specifications').val(product.cauhinh);
                    $('#sanpham_status').show();
                    $('label[for="sanpham_status"]').show();
                    $('#sanpham_status option[value="'+product.trangthai+'"]').prop('selected', true);
                    $('#sanpham_status_model').val(product.trangthaimausp);
                    $('#sanpham_review_image').attr('src', 'images/phone/' + product.hinhanh);
                    bool == true ? $('#sanpham_choose_image').hide() : $('#sanpham_choose_image').show();

                    // các màu sắc khác
                    if(data.lst_color.length != 1){
                        var html = '';
                        var type = bool == true ? 'readonly' : 'edit';
                        for(var i = 0; i < data.lst_color.length; i++){
                            var img = '<img data-id="'+data.lst_color[i].id+'" data-type="'+type+'" class="product-color" src="images/phone/'+data.lst_color[i].hinhanh+'">';
                            html += img;
                        }
                        owl.trigger('replace.owl.carousel', html).trigger('refresh.owl.carousel');
                        $('#product-color-carousel').parent().show();
                        data.lst_color.length <= 4 ? $('#prev-next-btn').hide() : $('#prev-next-btn').show();
                    } else {
                        $('#product-color-carousel').parent().hide();
                    }
                    

                    // file thông số
                    $('#sanpham_specifications option[value="create"]').hide();
                    // màn hình
                    $('#cong_nghe_mh').val(specifications.thong_so_ky_thuat.man_hinh.cong_nghe_mh);
                    $('#do_phan_giai').val(specifications.thong_so_ky_thuat.man_hinh.do_phan_giai);
                    $('#ty_le_mh').val(specifications.thong_so_ky_thuat.man_hinh.ty_le_mh);
                    $('#kinh_cam_ung').val(specifications.thong_so_ky_thuat.man_hinh.kinh_cam_ung);

                    // camera sau
                    $('#cam_sau_do_phan_giai').val(specifications.thong_so_ky_thuat.camera_sau.do_phan_giai);
                    var str = '';
                    if(specifications.thong_so_ky_thuat.camera_sau.quay_phim[0].chat_luong != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.camera_sau.quay_phim.length; i++){
                            var obj = specifications.thong_so_ky_thuat.camera_sau.quay_phim[i].chat_luong;
                            if(i == specifications.thong_so_ky_thuat.camera_sau.quay_phim.length - 1){
                                str += obj
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#cam_sau_quay_phim').val(str);
                    $('#cam_sau_den_flash').val(specifications.thong_so_ky_thuat.camera_sau.den_flash);
                    str = '';
                    if(specifications.thong_so_ky_thuat.camera_sau.tinh_nang[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.camera_sau.tinh_nang.length; i++){
                            var obj = specifications.thong_so_ky_thuat.camera_sau.tinh_nang[i].name;
                            if(i == specifications.thong_so_ky_thuat.camera_sau.tinh_nang.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#cam_sau_tinh_nang').val(str);

                    // camera trước
                    $('#cam_truoc_do_phan_giai').val(specifications.thong_so_ky_thuat.camera_truoc.do_phan_giai);
                    str = '';
                    if(specifications.thong_so_ky_thuat.camera_truoc.tinh_nang[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.camera_truoc.tinh_nang.length; i++){
                            var obj = specifications.thong_so_ky_thuat.camera_truoc.tinh_nang[i].name;
                            if(i == specifications.thong_so_ky_thuat.camera_truoc.tinh_nang.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#cam_truoc_tinh_nang').val(str);

                    // HDH & CPU
                    $('#HDH').val(specifications.thong_so_ky_thuat.HDH_CPU.HDH);
                    $('#CPU').val(specifications.thong_so_ky_thuat.HDH_CPU.CPU);
                    $('#CPU_speed').val(specifications.thong_so_ky_thuat.HDH_CPU.CPU_speed);
                    $('#GPU').val(specifications.thong_so_ky_thuat.HDH_CPU.GPU);

                    // lưu trữ
                    $('#RAM').val(specifications.thong_so_ky_thuat.luu_tru.RAM);
                    $('#bo_nho_trong').val(specifications.thong_so_ky_thuat.luu_tru.bo_nho_trong);
                    $('#bo_nho_con_lai').val(specifications.thong_so_ky_thuat.luu_tru.bo_nho_con_lai);
                    $('#the_nho').val(specifications.thong_so_ky_thuat.luu_tru.the_nho);

                    // kết nối
                    $('#mang_mobile').val(specifications.thong_so_ky_thuat.ket_noi.mang_mobile);
                    $('#SIM').val(specifications.thong_so_ky_thuat.ket_noi.SIM);
                    str = '';
                    if(specifications.thong_so_ky_thuat.ket_noi.wifi[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.ket_noi.wifi.length; i++){
                            var obj = specifications.thong_so_ky_thuat.ket_noi.wifi[i].name;
                            if(i == specifications.thong_so_ky_thuat.ket_noi.wifi.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#wifi').val(str);
                    str = '';
                    if(specifications.thong_so_ky_thuat.ket_noi.GPS[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.ket_noi.GPS.length; i++){
                            var obj = specifications.thong_so_ky_thuat.ket_noi.GPS[i].name;
                            if(i == specifications.thong_so_ky_thuat.ket_noi.GPS.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#GPS').val(str);
                    str = '';
                    if(specifications.thong_so_ky_thuat.ket_noi.bluetooth[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.ket_noi.bluetooth.length; i++){
                            var obj = specifications.thong_so_ky_thuat.ket_noi.bluetooth[i].name;
                            if(i == specifications.thong_so_ky_thuat.ket_noi.bluetooth.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#bluetooth').val(str);
                    $('#cong_sac').val(specifications.thong_so_ky_thuat.ket_noi.cong_sac);
                    $('#jack_tai_nghe').val(specifications.thong_so_ky_thuat.ket_noi.jack_tai_nghe);
                    str = '';
                    if(specifications.thong_so_ky_thuat.ket_noi.ket_noi_khac[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.ket_noi.ket_noi_khac.length; i++){
                            var obj = specifications.thong_so_ky_thuat.ket_noi.ket_noi_khac[i].name;
                            if(i == specifications.thong_so_ky_thuat.ket_noi.ket_noi_khac.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#ket_noi_khac').val(str);

                    // thiết kế trọng lượng
                    $('#thiet_ke').val(specifications.thong_so_ky_thuat.thiet_ke_trong_luong.thiet_ke);
                    $('#chat_lieu').val(specifications.thong_so_ky_thuat.thiet_ke_trong_luong.chat_lieu);
                    $('#kich_thuoc').val(specifications.thong_so_ky_thuat.thiet_ke_trong_luong.kich_thuoc);
                    $('#khoi_luong').val(specifications.thong_so_ky_thuat.thiet_ke_trong_luong.khoi_luong);

                    // pin
                    $('#loai').val(specifications.thong_so_ky_thuat.pin.loai);
                    $('#dung_luong').val(specifications.thong_so_ky_thuat.pin.dung_luong);
                    str = '';
                    if(specifications.thong_so_ky_thuat.pin.cong_nghe[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.pin.cong_nghe.length; i++){
                            var obj = specifications.thong_so_ky_thuat.pin.cong_nghe[i].name;
                            if(i == specifications.thong_so_ky_thuat.pin.cong_nghe.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#cong_nghe').val(str);

                    // tiện ích
                    str = '';
                    if(specifications.thong_so_ky_thuat.tien_ich.bao_mat[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.tien_ich.bao_mat.length; i++){
                            var obj = specifications.thong_so_ky_thuat.tien_ich.bao_mat[i].name;
                            if(i == specifications.thong_so_ky_thuat.tien_ich.bao_mat.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#bao_mat').val(str);
                    str = '';
                    if(specifications.thong_so_ky_thuat.tien_ich.tinh_nang_khac[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.tien_ich.tinh_nang_khac.length; i++){
                            var obj = specifications.thong_so_ky_thuat.tien_ich.tinh_nang_khac[i].name;
                            if(i == specifications.thong_so_ky_thuat.tien_ich.tinh_nang_khac.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#tinh_nang_khac').val(str);
                    $('#ghi_am').val(specifications.thong_so_ky_thuat.tien_ich.ghi_am);
                    str = '';
                    if(specifications.thong_so_ky_thuat.tien_ich.xem_phim[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.tien_ich.xem_phim.length; i++){
                            var obj = specifications.thong_so_ky_thuat.tien_ich.xem_phim[i].name;
                            if(i == specifications.thong_so_ky_thuat.tien_ich.xem_phim.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#xem_phim').val(str);
                    str = '';
                    if(specifications.thong_so_ky_thuat.tien_ich.nghe_nhac[0].name != null){
                        for(var i = 0; i < specifications.thong_so_ky_thuat.tien_ich.nghe_nhac.length; i++){
                            var obj = specifications.thong_so_ky_thuat.tien_ich.nghe_nhac[i].name;
                            if(i == specifications.thong_so_ky_thuat.tien_ich.nghe_nhac.length - 1){
                                str += obj    
                            } else {
                                str += obj + '\n';
                            }
                        }
                    }
                    $('#nghe_nhac').val(str);

                    // thông tin khác
                    var thoidiemramat;
                    if(specifications.thong_tin_khac.thoi_diem_ra_mat != null){
                        var temp = specifications.thong_tin_khac.thoi_diem_ra_mat.split('/');
                        thoidiemramat = temp[1] + '-' + temp[0];
                    } else {
                        thoidiemramat = '';
                    }
                    
                    $('#thoi_diem_ra_mat').val(thoidiemramat);

                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thay đổi màu sắc
        $('#product-color-carousel').bind('DOMSubtreeModified', function(){
            $('.product-color').off('click').click(function(){
                var id = $(this).data('id');
                var bool = $(this).data('type') == 'readonly' ? true : false;
                bindSanPham(id, bool);
            });
        });

        // nút prev, next
        $('#prev-product-color').on('click', function(){
            owl.trigger('prev.owl.carousel', [300]);
        });
        $('#next-product-color').on('click', function(){
            owl.trigger('next.owl.carousel');
        });

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiName = validateName($('#sanpham_name'));
            var valiColor = validateColor($('#sanpham_color'));
            var valiPrice = validatePrice($('#sanpham_price'));
            var valiImage = validateProductImage($('#sanpham_image'));

            // bẫy lỗi xong kiểm tra loại
            if(valiName & valiColor & valiPrice & valiImage){
                var tensp = $('#sanpham_name').val();
                var id_msp = $('#sanpham_model').val();
                var mausac = $('#sanpham_color').val();
                var ram = $('#sanpham_ram').val();
                var dungluong = $('#sanpham_capacity').val();
                var gia = $('#sanpham_price').val();
                var id_km = $('#sanpham_promotion').val();
                var trangthai = $('#sanpham_status').val();
                var hinhanhBase64 = $('#sanpham_image_base64').val();
                var cauhinhName = $('#sanpham_specifications').val();
                var cauhinh = {
                    "thong_so_ky_thuat": {
                        "man_hinh": {
                            "cong_nghe_mh": $('#cong_nghe_mh').val(),
                            "do_phan_giai": $('#do_phan_giai').val(),
                            "ty_le_mh": $('#ty_le_mh').val(),
                            "kinh_cam_ung": $('#kinh_cam_ung').val()
                        },
                        "camera_sau": {
                            "do_phan_giai": $('#cam_sau_do_phan_giai').val(),
                            "quay_phim": [],
                            "den_flash": $('#cam_sau_den_flash').val(),
                            "tinh_nang": []
                        },
                        "camera_truoc": {
                            "do_phan_giai": $('#cam_truoc_do_phan_giai').val(),
                            "tinh_nang": []
                        },
                        "HDH_CPU": {
                            "HDH": $('#HDH').val(),
                            "CPU": $('#CPU').val(),
                            "CPU_speed": $('#CPU_speed').val(),
                            "GPU": $('#GPU').val()
                        },
                        "luu_tru": {
                            "RAM": $('#RAM').val(),
                            "bo_nho_trong": $('#bo_nho_trong').val(),
                            "bo_nho_con_lai": $('#bo_nho_con_lai').val(),
                            "the_nho": $('#the_nho').val()
                        },
                        "ket_noi": {
                            "mang_mobile": $('#mang_mobile').val(),
                            "SIM": $('#SIM').val(),
                            "wifi": [],
                            "GPS": [],
                            "bluetooth": [],
                            "cong_sac": $('#cong_sac').val(),
                            "jack_tai_nghe": $('#jack_tai_nghe').val(),
                            "ket_noi_khac": []
                        },
                        "thiet_ke_trong_luong": {
                            "thiet_ke": $('#thiet_ke').val(),
                            "chat_lieu": $('#chat_lieu').val(),
                            "kich_thuoc": $('#kich_thuoc').val(),
                            "khoi_luong": $('#khoi_luong').val()
                        },
                        "pin": {
                            "loai": $('#loai').val(),
                            "dung_luong": $('#dung_luong').val(),
                            "cong_nghe": []
                        },
                        "tien_ich": {
                            "bao_mat": [],
                            "tinh_nang_khac": [],
                            "ghi_am": $('#ghi_am').val(),
                            "xem_phim": [],
                            "nghe_nhac": []
                        }
                    },
                    "thong_tin_khac": {
                        "thoi_diem_ra_mat": ''
                    }
                };

                if($('#thoi_diem_ra_mat').val() == ''){
                    cauhinh.thong_tin_khac.thoi_diem_ra_mat = '';    
                } else {
                    var thoi_diem_ra_mat = $('#thoi_diem_ra_mat').val().split('-'); 
                    cauhinh.thong_tin_khac.thoi_diem_ra_mat = thoi_diem_ra_mat[1] + '/' + thoi_diem_ra_mat[0];
                }
            
                // camera sau
                var cam_sau_quay_phim = $('#cam_sau_quay_phim').val().split('\n');
                for(var i = 0; i < cam_sau_quay_phim.length; i++){
                    if(cam_sau_quay_phim[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.camera_sau.quay_phim.push({"chat_luong": cam_sau_quay_phim[i]});
                }
                if(cauhinh.thong_so_ky_thuat.camera_sau.quay_phim.length == 0){
                    cauhinh.thong_so_ky_thuat.camera_sau.quay_phim.push({"chat_luong": ''});
                }
                
                var cam_sau_tinh_nang = $('#cam_sau_tinh_nang').val().split('\n');
                for(var i = 0; i < cam_sau_tinh_nang.length; i++){
                    if(cam_sau_tinh_nang[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.camera_sau.tinh_nang.push({"name": cam_sau_tinh_nang[i]});
                }
                if(cauhinh.thong_so_ky_thuat.camera_sau.tinh_nang.length == 0) {
                    cauhinh.thong_so_ky_thuat.camera_sau.tinh_nang.push({"name": ''});
                }
                
                // camera truoc
                var cam_truoc_tinh_nang = $('#cam_truoc_tinh_nang').val().split('\n');
                for(var i = 0; i < cam_truoc_tinh_nang.length; i++){
                    if(cam_truoc_tinh_nang[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.camera_truoc.tinh_nang.push({"name": cam_truoc_tinh_nang[i]});
                }
                if(cauhinh.thong_so_ky_thuat.camera_truoc.tinh_nang.length == 0) {
                    cauhinh.thong_so_ky_thuat.camera_truoc.tinh_nang.push({"name": ''});
                }

                // kết nối
                var wifi = $('#wifi').val().split('\n');
                for(var i = 0; i < wifi.length; i++){
                    if(wifi[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.ket_noi.wifi.push({"name": wifi[i]});
                }
                if(cauhinh.thong_so_ky_thuat.ket_noi.wifi.length == 0) {
                    cauhinh.thong_so_ky_thuat.ket_noi.wifi.push({"name": ''});
                }
                
                var GPS = $('#GPS').val().split('\n');
                for(var i = 0; i < GPS.length; i++){
                    if(GPS[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.ket_noi.GPS.push({"name": GPS[i]});
                }
                if(cauhinh.thong_so_ky_thuat.ket_noi.GPS.length == 0) {
                    cauhinh.thong_so_ky_thuat.ket_noi.GPS.push({"name": ''});
                }
                
                var bluetooth = $('#bluetooth').val().split('\n');
                for(var i = 0; i < bluetooth.length; i++){
                    if(bluetooth[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.ket_noi.bluetooth.push({"name": bluetooth[i]});
                }
                if(cauhinh.thong_so_ky_thuat.ket_noi.bluetooth.length == 0) {
                    cauhinh.thong_so_ky_thuat.ket_noi.bluetooth.push({"name": ''});
                }
                
                var ket_noi_khac = $('#ket_noi_khac').val().split('\n');
                for(var i = 0; i < ket_noi_khac.length; i++){
                    if(ket_noi_khac[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.ket_noi.ket_noi_khac.push({"name": ket_noi_khac[i]});
                }
                if(cauhinh.thong_so_ky_thuat.ket_noi.ket_noi_khac.length == 0){
                    cauhinh.thong_so_ky_thuat.ket_noi.ket_noi_khac.push({"name": ''});
                }

                // pin
                var cong_nghe = $('#cong_nghe').val().split('\n');
                for(var i = 0; i < cong_nghe.length; i++){
                    if(cong_nghe[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.pin.cong_nghe.push({"name": cong_nghe[i]});
                }
                if(cauhinh.thong_so_ky_thuat.pin.cong_nghe.length == 0){
                    cauhinh.thong_so_ky_thuat.pin.cong_nghe.push({"name": ''});
                }

                // tiện ích
                var bao_mat = $('#bao_mat').val().split('\n');
                for(var i = 0; i < bao_mat.length; i++){
                    if(bao_mat[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.tien_ich.bao_mat.push({"name": bao_mat[i]});
                }
                if(cauhinh.thong_so_ky_thuat.tien_ich.bao_mat.length == 0){
                    cauhinh.thong_so_ky_thuat.tien_ich.bao_mat.push({"name": ''});
                }
                var tinh_nang_khac = $('#tinh_nang_khac').val().split('\n');
                for(var i = 0; i < tinh_nang_khac.length; i++){
                    if(tinh_nang_khac[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.tien_ich.tinh_nang_khac.push({"name": tinh_nang_khac[i]});
                }
                if(cauhinh.thong_so_ky_thuat.tien_ich.tinh_nang_khac.length == 0){
                    cauhinh.thong_so_ky_thuat.tien_ich.tinh_nang_khac.push({"name": ''});
                }
                var xem_phim = $('#xem_phim').val().split('\n');
                for(var i = 0; i < xem_phim.length; i++){
                    if(xem_phim[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.tien_ich.xem_phim.push({"name": xem_phim[i]});
                }
                if(cauhinh.thong_so_ky_thuat.tien_ich.xem_phim.length == 0){
                    cauhinh.thong_so_ky_thuat.tien_ich.xem_phim.push({"name": ''});
                }
                var nghe_nhac = $('#nghe_nhac').val().split('\n');
                for(var i = 0; i < nghe_nhac.length; i++){
                    if(nghe_nhac[i] == ''){
                        continue;
                    }
                    cauhinh.thong_so_ky_thuat.tien_ich.nghe_nhac.push({"name": nghe_nhac[i]});
                }
                if(cauhinh.thong_so_ky_thuat.tien_ich.nghe_nhac.length == 0){
                    cauhinh.thong_so_ky_thuat.tien_ich.nghe_nhac.push({"name": ''});
                }

                $('.loader').show();

                var data = {
                    'tensp': tensp,
                    'id_msp': id_msp,
                    'mausp': $('#sanpham_model').find(':selected').text(),
                    'hinhanh': hinhanhBase64,
                    'mausac': mausac,
                    'ram': ram,
                    'dungluong': dungluong,
                    'gia': gia,
                    'id_km': id_km,
                    'khuyenmai': $('#sanpham_promotion').find(':selected').text(),
                    'cauhinhName': cauhinhName,
                    'cauhinh': cauhinh,
                    'trangthai': trangthai,
                    'trangthaimausp': $('#sanpham_status_model').val(),
                };

                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/sanpham',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            // sản phẩm đã tồn tại
                            if(data == 'exists'){
                                $('.loader').fadeOut();
                                $('#sanpham_name').addClass('required');
                                $('#sanpham_name').after('<span class="required-text">Sản phẩm này đã tồn tại</span>');
                                return;
                            }

                            $('#modal').modal('hide');

                            // render dòng mới vào view
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="sanpham-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#sanpham-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': '#b5ffd4',
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/sanpham/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế dòng mới
                            $('tr[data-id="'+id+'"]').replaceWith(data);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="sanpham-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#sanpham-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': '#b5ffd4',
                                'transition': '.5s'
                            });
                            
                        }
                    });
                }
            }
        });

        // bẫy lỗi màu sắc
        function validateColor(color) {
            if(color.hasClass('required')){
                $('.modal-body').animate({scrollTop: color.position().top});
                return false;
            }

            // chưa nhập
            if(color.val() == ''){
                color.addClass('required');
                color.after('<span class="required-text">Nhập màu</span>');
                $('.modal-body').animate({scrollTop: color.position().top});
                return false;
            }

            // không hợp lệ
            if(!isNaN(color.val())){
                color.addClass('required');
                color.after('<span class="required-text">Không hợp lệ</span>');
                $('.modal-body').animate({scrollTop: color.position().top});
                return false;
            }

            return true;
        }

        // bẫy lỗi giá
        function validatePrice(price) {
            if(price.hasClass('required')){
                $('.modal-body').animate({scrollTop: price.position().top});
                return false;
            }

            // chưa nhập
            if(price.val() == ''){
                price.addClass('required');
                price.after('<span class="required-text">Nhập giá</span>');
                $('.modal-body').animate({scrollTop: price.position().top});
                return false;
            }

            // 1.000.000 <= giá <= 100.000.000
            if(price.val() < 1000000 || price.val() > 1000000000){
                price.addClass('required');
                price.after('<span class="required-text">Giá từ 1.000.000<sup>đ</sup> - 100.000.000<sup>đ</sup></span>');
                $('.modal-body').animate({scrollTop: price.position().top});
                return false;
            }

            return true;
        }

        // bẫy lỗi hình ảnh
        function validateProductImage(image) {
            if($('#sanpham_review_image').hasClass('required')){
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('#sanpham_review_image').attr('src') == 'images/600x600.png'){
                $('#sanpham_review_image').addClass('required');
                $('#sanpham_review_image').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            return true;
        }

        $('#sanpham_name').keyup(function(){
            removeRequried($(this));
        });
        $('#sanpham_color').keyup(function(){
            removeRequried($(this));
        });
        $('#sanpham_price').keyup(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('shown.bs.modal', function(){
            // chọn sẵn khuyến mãi theo mẫu sản phẩm
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/ajax-get-promotionID-by-modelID',
                type: 'POST',
                data: {'id': $('#sanpham_model option:selected').val()},
                success:function(data){
                    if(data.id_km != null){
                        $('#sanpham_promotion option[value="'+data.id_km+'"]').prop('selected', true);
                    }
                }
            });

            // gán tên sản phẩm
            var modelName = $('#sanpham_model').find(':selected').text();
            $('#sanpham_name').val(modelName);
        })
        $('#modal').on('hidden.bs.modal', function(){
            $('#sanpham-form').trigger('reset');
            $('input, textarea').attr('readonly', false);
            $('select').attr('disabled', false);
            removeRequried($('#sanpham_name'));
            removeRequried($('#sanpham_review_image'));
            removeRequried($('#sanpham_color'));
            removeRequried($('#sanpham_price'));
            $('#sanpham_review_image').attr('src', 'images/600x600.png');
            $('#sanpham_choose_image').show();
            $('#create-specifications-div').show('blind');
            $('#sanpham_specifications option[value="create"]').show();
            $('#action-btn').show();
            $('#sanpham_model').children().show();
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // ẩn nút xóa
                    var restoreBtn = $('<div data-id="'+id+'" data-name="'+name+'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>');
                    $('.delete-btn[data-id="'+id+'"]').replaceWith(restoreBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Ngừng kinh doanh');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="sanpham-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#sanpham-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // phục hồi
        $('.undelete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            restore(id, name);            
        });

        function restore(id, name) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/ajax-restore',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    if(data == 'false'){
                        $('#info-modal-content').text('Không thể khôi phục do mẫu sản phẩm đã xóa trước đó.');
                        $('#info-modal-main-btn').attr('data-bs-dismiss', 'modal');
                        $('#info-modal-main-btn').text('Đã hiểu');
                        $('#info-modal').modal('show');
                        return;
                    }
                    // hiện nút xóa
                    var deleteBtn = $('<div data-id="'+id+'" data-name="'+name+'" class="delete-btn"><i class="fas fa-trash"></i></div>');
                    $('.undelete-btn[data-id="'+id+'"]').replaceWith(deleteBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Kinh doanh');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="sanpham-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                    showToast('#sanpham-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            });
        }

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // hiển thị modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                $('#modal-title').text('Chỉnh sửa sản phẩm');
                bindSanPham(id);
            });

            // modal xem chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                $('#modal-title').text('Chi tiết sản phẩm');
                bindSanPham(id, true);
            });

            // modal xóa
            $('.delete-btn').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa <b>'+name+'?</b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-btn').attr('data-name', name);
                $('#delete-modal').modal('show');
            });

            // phục hồi
            $('.undelete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                restore(id, name);            
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                // có lọc || sắp xếp
                if(Object.keys(arrFilterSort.filter).length != 0 || arrFilterSort.sort != ''){
                    filterSort();
                } else {
                    var keyword = $(this).val().toLocaleLowerCase();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/sanpham/ajax-search',
                        type: 'POST',
                        data: {'keyword': keyword},
                        success:function(data){
                            $('#lst_data').children().remove();
                            $('#lst_data').append(data);
                            loadMoreFlag = keyword == '' ? false : true;
                            $('#loadmore').hide();
                        }
                    });
                }
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });

        // show bộ lọc
        $('#filter-sanpham').click(function(){
            $('.filter-div').toggle('blind');
            $('.sort-div').hide('blind');
        });

        // show sắp xếp
        $('#sort-sanpham').click(function(){
            $('.filter-div').hide('blind');
            $('.sort-div').toggle('blind');
        });

        var arrFilterSort = {
            filter: {},
            sort: '',
        };
        // thêm bộ lọc
        $('[name="filter"]').change(function(){
            var obj = $(this).data('object');

            if(obj == 'ram'){
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.ram == null){
                        arrFilterSort.filter.ram = [];
                    }

                    arrFilterSort.filter.ram.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.ram.indexOf($(this).val());
                    arrFilterSort.filter.ram.splice(i, 1);
                    if(arrFilterSort.filter.ram.length == 0){
                        delete arrFilterSort.filter.ram;
                    }
                }
            } else if(obj == 'capacity'){
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.capacity == null){
                        arrFilterSort.filter.capacity = [];
                    }

                    arrFilterSort.filter.capacity.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.capacity.indexOf($(this).val());
                    arrFilterSort.filter.capacity.splice(i, 1);
                    if(arrFilterSort.filter.capacity.length == 0){
                        delete arrFilterSort.filter.capacity;
                    }
                }
            } else {
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.status == null){
                        arrFilterSort.filter.status = [];
                    }

                    arrFilterSort.filter.status.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.status.indexOf($(this).val());
                    arrFilterSort.filter.status.splice(i, 1);
                    if(arrFilterSort.filter.status.length == 0){
                        delete arrFilterSort.filter.status;
                    }
                }
            }
            filterSort();
        });

        // chọn sắp xếp
        $('[name="sort"]').change(function(){
            var sort = $(this).val();

            arrFilterSort.sort = sort;
            filterSort()
        });

        // danh sách kết quả lọc & sắp xếp
        function filterSort() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/sanpham/ajax-filtersort',
                type: 'POST',
                data: {'arrFilterSort': arrFilterSort, 'search': $('#search').val().toLocaleLowerCase()},
                success: function(data){
                    $('#lst_data').children().remove();
                    $('#lst_data').append(data);
                    if(Object.keys(arrFilterSort.filter).length == 0){
                        $('.filter-badge').hide();
                    } else {
                        $('.filter-badge').text(Object.keys(arrFilterSort.filter).length);
                        $('.filter-badge').show();
                    }
                    if(arrFilterSort.sort != ''){
                        $('.sort-badge').show();
                    }
                    loadMoreFlag = true;
                    $('#loadmore').hide();
                }
            })
        }
    }
    /*=======================================================================================================================
                                                           Nhà cung cấp
    =======================================================================================================================*/
    else if(url == 'nhacungcap'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới nhà cung cấp');

            // trạng thái = 1
            $('#ncc_status').hide();
            $('label[for="ncc_status"]').hide();

            // thiết lập nút gửi là thêm mới
            $('#action-ncc-btn').attr('data-type', 'create');
            $('#action-ncc-btn').text('Thêm');

            $('#product-color-carousel').parent().hide();

            // hiển thị modal
            $('#ncc-modal').modal('show');
        });

        // modal xem chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết nhà cung cấp');
            bindNCC(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa nhà cung cấp');
            bindNCC(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa nhà cung cấp <b>'+name+'?</b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-btn').attr('data-name', name);
            $('#delete-modal').modal('show');
        });

        // show dialog chọn hình ảnh
        $('#ncc_choose_image').click(function(){
            $('#ncc_image_inp').click();
        });

        // chọn hình ảnh
        $('#ncc_image_inp').change(function(){
            // hủy chọn hình
            if($(this).val() == ''){
                return;
            }

            removeRequried($('#ncc_review_image'));

            // kiểm tra file hình
            var fileName = this.files[0].name.split('.');
            var extend = fileName[fileName.length - 1];

            // xem trước hình ảnh
            if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#ncc_review_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
                getBase64FromUrl(URL.createObjectURL(this.files[0]), function(dataUrl){
                    $('#ncc_image_base64').val(dataUrl);
                });
            }
            // không phải hình ảnh
            else{
                $('#ncc_review_image').attr('src', 'images/320x320.png');
                $(this).val('');
                alert('Bạn chỉ được phép upload hình ảnh');
            }
        });

        function bindNCC(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/nhacungcap/ajax-get-ncc',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    // gán dữ liệu cho modal
                    $('#ncc_review_image').attr('src', 'images/logo/' + data.anhdaidien);
                    $('#ncc_name').val(data.tenncc);
                    $('#ncc_address').val(data.diachi);
                    $('#ncc_tel').val(data.sdt);
                    $('#ncc_email').val(data.email);
                    $('#ncc_status option[value="'+data.trangthai+'"]').prop('selected', true);
                    $('label[for="ncc_status"]').show();
                    $('#ncc_status').show();

                    bool == true ? $('#ncc_choose_image').hide() : $('#ncc_choose_image').show();
                    

                    // thiết lập nút gửi là cập nhật
                    $('#action-ncc-btn').attr('data-type', 'edit');
                    $('#action-ncc-btn').text('Cập nhật');
                    $('#action-ncc-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#ncc-modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-ncc-btn').show() : $('#action-ncc-btn').hide();
        }

        // thêm|sửa
        $('#action-ncc-btn').click(function(){
            // bẫy lỗi
            var valiName = validateName($('#ncc_name'));
            var valiAddress = validateAddress($('#ncc_address'));
            var valitel = validatePhoneNumber($('#ncc_tel'));
            var valiEmail = validateEmail($('#ncc_email'));
            var valiImage = validateSupplierImage($('#ncc_image_inp'))

            // bẫy lỗi xong kiểm tra loại
            if(valiName & valiAddress & valitel & valiEmail && valiImage){
                $('.loader').show();

                var data = {
                    'tenncc': $('#ncc_name').val(),
                    'anhdaidien': $('#ncc_image_base64').val(),
                    'diachi': $('#ncc_address').val(),
                    'sdt': $('#ncc_tel').val(),
                    'email': $('#ncc_email').val(),
                    'trangthai': $('#ncc_status').val(),
                };

                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/nhacungcap',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // đã tồn tại
                            if(data == 'exists'){
                                $('.loader').fadeOut();
                                $('#ncc_name').addClass('required');
                                $('#ncc_name').after('<span class="required-text">Nhà cung cấp này đã tồn tại</span>');
                                return;
                            }

                            $('#ncc-modal').modal('hide');

                            // render vào view
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="ncc-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#ncc-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/nhacungcap/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#ncc-modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="ncc-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#ncc-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi hình ảnh
        function validateSupplierImage(image) {
            if($('#ncc_review_image').hasClass('required')){
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('#ncc_review_image').attr('src') == 'images/320x320.png'){
                $('#ncc_review_image').addClass('required');
                $('#ncc_review_image').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            return true;
        }

        $('#ncc_name').keyup(function(){
            removeRequried($(this));
        });
        $('#ncc_address').keyup(function(){
            removeRequried($(this));
        });
        $('#ncc_tel').keyup(function(){
            valiPhonenumberTyping($(this));
        });
        $('#ncc_email').keyup(function(){
            removeRequried($(this));
        })

        // reset modal
        $('#ncc-modal').on('hidden.bs.modal', function(){
            $('#ncc-form').trigger('reset');
            $('input, textarea').attr('readonly', false);
            $('select').attr('disabled', false);
            removeRequried($('#ncc_name'));
            removeRequried($('#ncc_review_image'));
            removeRequried($('#ncc_address'));
            removeRequried($('#ncc_tel'));
            removeRequried($('#ncc_email'));
            $('#ncc_choose_image').show();
            $('#ncc_review_image').attr('src', 'images/320x320.png');
            $('#ncc_image_base64').val('');
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal xem chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết nhà cung cấp');
                bindNCC(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa nhà cung cấp');
                bindNCC(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa nhà cung cấp <b>'+name+'?</b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-btn').attr('data-name', name);
                $('#delete-modal').modal('show');
            });

            // khôi phục
            $('.undelete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                restore(id, name);
            });
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/nhacungcap/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // thay nút
                    var restoreBtn = $('<div data-id="'+id+'" data-name="'+name+'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>')
                    $('.delete-btn[data-id="'+id+'"]').replaceWith(restoreBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Ngừng kinh doanh');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="ncc-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#ncc-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // khôi phục
        $('.undelete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            restore(id, name);
        });

        function restore(id, name) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/nhacungcap/ajax-restore',
                type: 'POST',
                data: {'id': id},
                success:function(){
                    // thay nút
                    var deleteBtn = $('<div data-id="'+id+'" data-name="'+name+'" class="delete-btn"><i class="fas fa-trash"></i></div>')
                    $('.undelete-btn[data-id="'+id+'"]').replaceWith(deleteBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Hoạt động');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="ncc-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                    showToast('#ncc-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            });
        }

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/nhacungcap/ajax-search',
                        type: 'POST',
                        data: {'keyword': keyword},
                        success:function(data){
                            $('#lst_data').children().remove();
                            $('#lst_data').append(data);
                            loadMoreFlag = true;
                            $('#loadmore').hide();
                        }
                    });
            },300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Slideshow msp
    =======================================================================================================================*/
    else if(url == 'slideshow-msp'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/slideshow-msp/ajax-get-model-havenot-slideshow',
                type: 'POST',
                data: {'data': ''},
                success:function(data){
                    if(data.length == 0){
                        if($('#toast').children().length){
                            $('#toast').children().remove();
                        }
                        $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">Tất cả mẫu sản phẩm đã có hình ảnh</span>');
                        showToast('#slideshow-msp-toast');
                    } else {
                        // gán dữ liệu cho modal
                        $('#modal-title').text('Tạo mới slideshow mẫu sản phẩm');
                        $('#model').children().remove();
                        for(var i = 0; i < data.length; i++){
                            var option = $('<option value="'+data[i].id+'">'+data[i].tenmau+'</option>');
                            option.appendTo($('#model'));
                        }

                        // thiết lập nút gửi là thêm mới
                        $('#action-btn').attr('data-type', 'create');
                        $('#action-btn').text('Thêm');

                        // hiển thị modal
                        $('#modal').modal('show');
                    }
                }
            });
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết slideshow mẫu sản phẩm');
            bindSlideshowMSP(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa slideshow mẫu sản phẩm');
            bindSlideshowMSP(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa slideshow <b>'+name+'?</b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-btn').attr('data-name', name);
            $('#delete-modal').modal('show');
        });

        // show dialog chọn hình ảnh
        $('#choose_image').click(function(){
            $('#image_inp').click();
        });

        // chọn hình ảnh
        var idx = 1;
        $('#image_inp').change(function(){
            // hủy chọn hình
            if($(this).val() == ''){
                return;
            }

            removeRequried($('.image-preview-div'));
            $('#qty-image').show();

            // số hình trong input file
            var length = this.files.length;

            // tổng số hình hiện tại
            var qty = $('.image-preview-div').children().length;

            for(var i = 0; i < length; i++){
                if(qty >= 20){
                    alert('Tối đa 20 hình');
                    break;
                }

                // kiểm tra file hình
                var fileName = this.files[i].name.split('.');
                var extend = fileName[fileName.length - 1];

                // kiểm tra có phải là hình ảnh không
                if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                    // tạo hình
                    var elmnt = $('<div id="image-'+idx+'" data-id="'+idx+'" class="col-lg-3 p-10">'+
                                        '<div class="image-preview">'+
                                            '<div class="overlay-image-preview"></div>'+
                                            '<div data-id="'+idx+'" class="delete-image-preview"><i class="far fa-times-circle fz-40"></i></div>'+
                                            '<img data-id="'+idx+'" class="image_preview_img" src="'+URL.createObjectURL(this.files[i])+'" alt="">'+
                                        '</div>'+
                                    '</div>');
                    elmnt.appendTo($('.image-preview-div'));
                }
                // không phải hình ảnh
                else{
                    alert('Bạn chỉ được phép upload hình ảnh');
                }
                idx++;
                qty++;
            }

            $('#qty-image').text('('+qty+')');
        });

        $('.image-preview-div').bind('DOMSubtreeModified', function(){
            // xóa hình
            $('.delete-image-preview').off('click').click(function(){
                var id = $(this).data('id');
                $('#image-' + id).remove();
                var qty = $('.image-preview-div').children().length;
                if(qty == 0){
                    $('#qty-image').hide();
                } else {
                    $('#qty-image').text('('+qty+')');
                }
            });
        });

        function bindSlideshowMSP(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/slideshow-msp/ajax-get-slideshow-msp',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    var lst_slide = data.lst_slide;
                    var lst_model = data.lst_model;

                    // gán dữ liệu cho modal
                    setTimeout(() => {
                        // gán hình ảnh
                        $('.image-preview-div').children().remove();
                        for(var i = 0; i < lst_slide.length; i++){
                            var elmnt = $('<div id="image-'+idx+'" data-id="'+idx+'" class="col-lg-3 p-10">'+
                                            '<div class="image-preview">'+
                                                '<div class="overlay-image-preview"></div>'+
                                                '<div data-id="'+idx+'" class="delete-image-preview"><i class="far fa-times-circle fz-40"></i></div>'+
                                                '<img data-id="'+idx+'" class="image_preview_img" src="images/phone/slideshow/'+lst_slide[i].hinhanh+'" alt="">'+
                                            '</div>'+
                                        '</div>');
                            elmnt.appendTo($('.image-preview-div'));

                            idx++;
                        }

                        // gán mẫu sp
                        $('#model').children().remove();
                        for(var i = 0; i < lst_model.length; i++){
                            var option = $('<option value="'+lst_model[i].id+'">'+lst_model[i].tenmau+'</option>');
                            option.appendTo($('#model'));
                        }

                        $('#model option[value="'+id+'"]').prop('selected', true);

                        if(bool == true){
                            $('.overlay-image-preview').remove();
                            $('.delete-image-preview').remove();
                        }
                    }, 500);

                    $('#qty-image').text('('+lst_slide.length+')');

                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
            bool == false ? $('#choose_image').show() : $('#choose_image').hide();
        }

        $('#model').change(function(){
            var id = $(this).val();
            bindSlideshowMSP(id);
        });

        // thêm|sửa
        var arrayBase64 = [];
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiImage = validateSlideshowImage($('#image_inp'))

            // bẫy lỗi xong kiểm tra loại
            if(valiImage){
                $('.loader').show();

                // hình => base64
                for(var i = 0; i < $('.image-preview-div').children().length; i++){
                    var id = $($('.image-preview-div').children()[i]).attr('data-id');
                    getBase64FromUrl($('.image_preview_img[data-id="'+id+'"]').attr('src'), function(dataUrl){
                        arrayBase64.push(dataUrl);
                    });    
                }

                var data = {
                    'id_msp': $('#model').val(),
                    'image_slideshow': arrayBase64,
                };

                setTimeout(() => {
                    // thêm mới
                    if($(this).attr('data-type') == 'create'){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/slideshow-msp',
                            type: 'POST',
                            data: data,
                            success:function(data){
                                $('.loader').fadeOut();
                                $('#modal').modal('hide');

                                // render vào view
                                $('#lst_data').children().remove();
                                $('#lst_data').append(data.html);

                                // toast
                                if($('#toast').children().length){
                                    $('#toast').children().remove();
                                }
                                $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                                showToast('#slideshow-msp-toast');

                                // đánh dấu dòng được thêm/chỉnh sửa
                                var tr = $('tr[data-id="'+data.id+'"]'); 
                                setTimeout(() => {
                                    setTimeout(() => {
                                        tr.removeAttr('style');    
                                    }, 1000);
                                    tr.css({
                                        'background-color': 'white',
                                    });    
                                }, 3000);
                                $('html, body').animate({scrollTop: tr.position().top});
                                tr.css({
                                    'background-color': SUCCESS,
                                    'transition': '.5s'
                                });
                            }
                        });
                    }
                    // chỉnh sửa
                    else {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/slideshow-msp/' + id,
                            type: 'PUT',
                            data: data,
                            success:function(data){
                                $('.loader').fadeOut();
                                $('#modal').modal('hide');

                                // thay thế
                                $('tr[data-id="'+id+'"]').replaceWith(data); 
                                
                                // toast
                                if($('#toast').children().length){
                                    $('#toast').children().remove();
                                }
                                $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                                showToast('#slideshow-msp-toast');

                                // đánh dấu dòng được thêm/chỉnh sửa
                                var tr = $('tr[data-id="'+id+'"]'); 
                                setTimeout(() => {
                                    setTimeout(() => {
                                        tr.removeAttr('style');    
                                    }, 1000);
                                    tr.css({
                                        'background-color': 'white',
                                    });    
                                }, 3000);
                                $('html, body').animate({scrollTop: tr.position().top});
                                tr.css({
                                    'background-color': SUCCESS,
                                    'transition': '.5s'
                                });
                            }
                        });
                    }
                }, 1000);
            }
        });

        // bẫy lỗi hình ảnh
        function validateSlideshowImage(image) {
            if($('.image-preview-div').hasClass('required')){
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('.image-preview-div').children().length == 0){
                $('.image-preview-div').addClass('required');
                $('.image-preview-div').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            return true;
        }

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('select').attr('disabled', false);
            $('#model').children().remove();
            removeRequried($('.image-preview-div'));
            $('.image-preview-div').children().remove();
            $('#choose_image').show();
            $('#action-btn').show();
            idx = 1;
            arrayBase64 = [];
            $('#qty-image').text('');
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết slideshow mẫu sản phẩm');
                bindSlideshowMSP(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa slideshow mẫu sản phẩm');
                bindSlideshowMSP(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa slideshow <b>'+name+'?</b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-btn').attr('data-name', name);
                $('#delete-modal').modal('show');
            });
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/slideshow-msp/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // ẩn nút xóa
                    $('.delete-btn[data-id="'+id+'"]').remove();

                    // cập nhật số lượng hình
                    $('.qty-image[data-id="'+id+'"]').text('0 Hình');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/slideshow-msp/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').append(data);
                        loadMoreFlag = keyword == '' ? false : true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Hình ảnh
    =======================================================================================================================*/
    else if(url == 'hinhanh'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/hinhanh/ajax-get-model-havenot-image',
                type: 'POST',
                data: {'data': ''},
                success:function(data){
                    if(data.length == 0){
                        if($('#toast').children().length){
                            $('#toast').children().remove();
                        }
                        $('#toast').append('<span id="hinhanh-toast" class="alert-toast-right alert-toast-right-success">Tất cả mẫu sản phẩm đã có hình ảnh</span>');
                        showToast('#hinhanh-toast');
                    } else {
                        // gán dữ liệu cho modal
                        $('#modal-title').text('Tạo mới hình ảnh mẫu sản phẩm');
                        $('#model').children().remove();
                        for(var i = 0; i < data.length; i++){
                            var option = $('<option value="'+data[i].id+'">'+data[i].tenmau+'</option>');
                            option.appendTo($('#model'));
                        }

                        // thiết lập nút gửi là thêm mới
                        $('#action-btn').attr('data-type', 'create');
                        $('#action-btn').text('Thêm');

                        // hiển thị modal
                        $('#modal').modal('show');
                    }
                }
            });
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết hình ảnh mẫu sản phẩm');
            bindHinhAnh(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa hình ảnh mẫu sản phẩm');
            bindHinhAnh(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa hình ảnh <b>'+name+'?</b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-btn').attr('data-name', name);
            $('#delete-modal').modal('show');
        });

        // show dialog chọn hình ảnh
        $('#choose_image').click(function(){
            $('#image_inp').click();
        });

        // chọn hình ảnh
        var idx = 1;
        $('#image_inp').change(function(){
            // hủy chọn hình
            if($(this).val() == ''){
                return;
            }

            removeRequried($('.image-preview-div'));
            $('#qty-image').show();

            // số hình trong input file
            var length = this.files.length;

            // tổng số hình hiện tại
            var qty = $('.image-preview-div').children().length;

            for(var i = 0; i < length; i++){
                if(qty >= 20){
                    alert('Tối đa 20 hình');
                    break;
                }

                // kiểm tra file hình
                var fileName = this.files[i].name.split('.');
                var extend = fileName[fileName.length - 1];

                // kiểm tra có phải là hình ảnh không
                if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                    // tạo hình
                    var elmnt = $('<div id="image-'+idx+'" data-id="'+idx+'" class="col-lg-3 p-10">'+
                                        '<div class="image-preview">'+
                                            '<div class="overlay-image-preview"></div>'+
                                            '<div data-id="'+idx+'" class="delete-image-preview"><i class="far fa-times-circle fz-40"></i></div>'+
                                            '<img data-id="'+idx+'" class="image_preview_img" src="'+URL.createObjectURL(this.files[i])+'" alt="">'+
                                        '</div>'+
                                    '</div>');
                    elmnt.appendTo($('.image-preview-div'));
                }
                // không phải hình ảnh
                else{
                    alert('Bạn chỉ được phép upload hình ảnh');
                }
                idx++;
                qty++;
            }

            $('#qty-image').text('('+qty+')');
        });

        $('.image-preview-div').bind('DOMSubtreeModified', function(){
            // xóa hình
            $('.delete-image-preview').off('click').click(function(){
                var id = $(this).data('id');
                $('#image-' + id).remove();
                var qty = $('.image-preview-div').children().length;
                if(qty == 0){
                    $('#qty-image').hide();
                } else {
                    $('#qty-image').text('('+qty+')');
                }
            });
        });

        function bindHinhAnh(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/hinhanh/ajax-get-hinhanh',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    var lst_image = data.lst_image;
                    var lst_model = data.lst_model;

                    // gán dữ liệu cho modal
                    setTimeout(() => {
                        // gán hình ảnh
                        $('.image-preview-div').children().remove();
                        for(var i = 0; i < lst_image.length; i++){
                            var elmnt = $('<div id="image-'+idx+'" data-id="'+idx+'" class="col-lg-3 p-10">'+
                                            '<div class="image-preview">'+
                                                '<div class="overlay-image-preview"></div>'+
                                                '<div data-id="'+idx+'" class="delete-image-preview"><i class="far fa-times-circle fz-40"></i></div>'+
                                                '<img data-id="'+idx+'" class="image_preview_img" src="images/phone/'+lst_image[i].hinhanh+'" alt="">'+
                                            '</div>'+
                                        '</div>');
                            elmnt.appendTo($('.image-preview-div'));

                            idx++;
                        }

                        // gán mẫu sp
                        $('#model').children().remove();
                        for(var i = 0; i < lst_model.length; i++){
                            var option = $('<option value="'+lst_model[i].id+'">'+lst_model[i].tenmau+'</option>');
                            option.appendTo($('#model'));
                        }

                        $('#model option[value="'+id+'"]').prop('selected', true);

                        if(bool == true){
                            $('.overlay-image-preview').remove();
                            $('.delete-image-preview').remove();
                        }
                    }, 500);

                    $('#qty-image').text('('+lst_image.length+')');

                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
            bool == false ? $('#choose_image').show() : $('#choose_image').hide();
        }

        $('#model').change(function(){
            var id = $(this).val();
            bindHinhAnh(id);
        });

        // thêm|sửa
        var arrayBase64 = [];
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiImage = validateSlideshowImage($('#image_inp'))

            // bẫy lỗi xong kiểm tra loại
            if(valiImage){
                $('.loader').show();

                // hình => base64
                for(var i = 0; i < $('.image-preview-div').children().length; i++){
                    var id = $($('.image-preview-div').children()[i]).attr('data-id');
                    getBase64FromUrl($('.image_preview_img[data-id="'+id+'"]').attr('src'), function(dataUrl){
                        arrayBase64.push(dataUrl);
                    });    
                }

                var data = {
                    'id_msp': $('#model').val(),
                    'lst_base64': arrayBase64,
                };

                setTimeout(() => {
                    // thêm mới
                    if($(this).attr('data-type') == 'create'){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/hinhanh',
                            type: 'POST',
                            data: data,
                            success:function(data){
                                $('.loader').fadeOut();
                                $('#modal').modal('hide');

                                // render vào view
                                $('#lst_data').children().remove();
                                $('#lst_data').append(data.html);

                                // xóa option
                                $('#model option[value="'+data.id+'"]').remove();

                                // toast
                                if($('#toast').children().length){
                                    $('#toast').children().remove();
                                }
                                $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                                showToast('#slideshow-msp-toast');

                                // đánh dấu dòng được thêm/chỉnh sửa
                                var tr = $('tr[data-id="'+data.id+'"]'); 
                                setTimeout(() => {
                                    setTimeout(() => {
                                        tr.removeAttr('style');    
                                    }, 1000);
                                    tr.css({
                                        'background-color': 'white',
                                    });    
                                }, 3000);
                                $('html, body').animate({scrollTop: tr.position().top});
                                tr.css({
                                    'background-color': SUCCESS,
                                    'transition': '.5s'
                                });
                            }
                        });
                    }
                    // chỉnh sửa
                    else {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: 'admin/hinhanh/' + id,
                            type: 'PUT',
                            data: data,
                            success:function(data){
                                $('.loader').fadeOut();
                                $('#modal').modal('hide');

                                // thay thế
                                $('tr[data-id="'+id+'"]').replaceWith(data); 
                                
                                // toast
                                if($('#toast').children().length){
                                    $('#toast').children().remove();
                                }
                                $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                                showToast('#slideshow-msp-toast');

                                // đánh dấu dòng được thêm/chỉnh sửa
                                var tr = $('tr[data-id="'+id+'"]'); 
                                setTimeout(() => {
                                    setTimeout(() => {
                                        tr.removeAttr('style');    
                                    }, 1000);
                                    tr.css({
                                        'background-color': 'white',
                                    });    
                                }, 3000);
                                $('html, body').animate({scrollTop: tr.position().top});
                                tr.css({
                                    'background-color': SUCCESS,
                                    'transition': '.5s'
                                });
                            }
                        });
                    }
                }, 1000);
            }
        });

        // bẫy lỗi hình ảnh
        function validateSlideshowImage(image) {
            if($('.image-preview-div').hasClass('required')){
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('.image-preview-div').children().length == 0){
                $('.image-preview-div').addClass('required');
                $('.image-preview-div').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                $('.modal-body').animate({scrollTop: image.position().top});
                return false;
            }

            return true;
        }

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('select').attr('disabled', false);
            removeRequried($('.image-preview-div'));
            $('.image-preview-div').children().remove();
            $('#choose_image').show();
            $('#action-btn').show();
            idx = 1;
            arrayBase64 = [];
            $('#qty-image').text('');
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết hình ảnh mẫu sản phẩm');
                bindHinhAnh(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết hình ảnh mẫu sản phẩm');
                bindHinhAnh(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa hình ảnh <b>'+name+'?</b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-btn').attr('data-name', name);
                $('#delete-modal').modal('show');
            });
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/hinhanh/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // ẩn nút xóa
                    $('.delete-btn[data-id="'+id+'"]').remove();

                    // cập nhật số lượng hình
                    $('.qty-image[data-id="'+id+'"]').text('0 Hình');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"').attr('content')
                    },
                    url: 'admin/hinhanh/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').append(data);
                        loadMoreFlag = keyword == '' ? false : true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Kho
    =======================================================================================================================*/
    else if(url == 'kho'){
        // thay đổi chi nhánh thêm|sửa kho
        $('#branch').change(function(){
            var id_cn = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/kho/ajax-get-product-isnot-in-stock',
                type: 'POST',
                data: {'id_cn': id_cn},
                success:function(data){
                    $('#product').children().remove();
                    $('#list-product').children().remove();

                    // kho tại chi nhánh đã có sản phẩm
                    if(data.length == 0){
                        var elmnt = $('<div class="p-10">Tất cả sản phẩm đã được thêm vào kho.</div>');
                        elmnt.appendTo($('#product'));
                        $('#action-btn').hide();
                    } else {
                        renderProductOption(data, id_cn);

                        $('#action-btn').show();
                    }
                }
            });
        });

        function renderProductOption(product, id_cn) {
            var elmnt = $('<img src="images/phone/'+product[0].hinhanh+'" alt="product image" width="70px">'+
                            '<div class="ml-10 fz-14">'+
                                '<div class="d-flex align-items-center fw-600">'+
                                    product[0].tensp+
                                    '<i class="fas fa-circle ml-5 mr-5 fz-5"></i>'+
                                    product[0].mausac+
                                '</div>'+
                                '<div>Ram: '+product[0].ram+'</div>'+
                                '<div>Dung lượng: '+product[0].dungluong+'</div>'+
                            '</div>');

            elmnt.appendTo($('#product'));

            // input id
            $('#product_id_inp').val(product[0].id);

            for(var i = 0; i < product.length; i++){
                var option = $('<div data-id="'+product[i].id+'" data-name="'+product[i].tensp+'" data-branch="'+id_cn+'" class="product-option select-single-option">'+
                                    '<div class="d-flex">'+
                                        '<img src="images/phone/'+product[i].hinhanh+'" alt="product image" width="70px">'+
                                        '<div class="ml-10 fz-14">'+
                                            '<div class="d-flex align-items-center fw-600">'+
                                                product[i].tensp+
                                                '<i class="fas fa-circle ml-5 mr-5 fz-5"></i>'+
                                                product[i].mausac+
                                            '</div>'+
                                            '<div>Ram: '+product[i].ram+'</div>'+
                                            '<div>Dung lượng: '+product[i].dungluong+'</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>');

                option.appendTo($('#list-product'));
            }
        }

        // show options sản phẩm
        $('#product-selected').click(function(){
            $('#product-box').toggle('blind', 250);
        });

        // tìm kiếm sp thêm|sửa kho
        $('#search-product').keyup(function(){
            var value = $(this).val().toLocaleLowerCase();

            if(value == ''){
                $('#list-product').children().show();
                return;
            }

            var length = $('#list-product').children().length;

            for(var i = 0; i < length; i++){
                var child = $($('#list-product').children()[i]);
                var name = child.data('name').toLocaleLowerCase();

                if(name.includes(value)){
                    child.show();
                } else {
                    child.hide();
                }
            }
        });

        // chọn sản phẩm thêm|sửa kho
        $('#list-product').bind('DOMSubtreeModified', function(){
            $('.product-option').off('click').click(function(){
                var id_sp = $(this).data('id');
                var id_cn = $(this).data('branch');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/kho/ajax-get-product-by-id',
                    type: 'POST',
                    data: {'id_sp': id_sp, 'id_cn': id_cn},
                    success:function(data){
                        // input id
                        $('#product_id_inp').val(id_sp);
                        $('#product').children().remove();
                        $('#product').append(data.html);
                        $('#product-box').hide('blind', 250);

                        if(data.warehouse != null){
                            $('#action-btn').attr('data-id', data.warehouse.id);
                            $('#qty_in_stock').val(data.warehouse.slton);
                        }
                    }
                });
            });
        });

        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            var id_cn = $('#branch').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/kho/ajax-get-product-isnot-in-stock',
                type: 'POST',
                data: {'id_cn': id_cn},
                success:function(data){
                    if(data == 'false'){
                        if($('#toast').children().length){
                            $('#toast').children().remove();
                        }
                        $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">Tất cả sản phẩm đã được nhập vào kho</span>');
                        showToast('#alert-toast');
                    } else {
                        // gán sản phẩm
                        // kho tại chi nhánh đã nhập tất cả sản phẩm
                        if(data.length == 0){
                            var elmnt = $('<div class="p-10">Tất cả sản phẩm đã được thêm vào kho tại chi nhánh.</div>');
                            elmnt.appendTo($('#product'));
                            $('#action-btn').hide();
                        } else {
                            renderProductOption(data, id_cn);
                        }
                        // gán dữ liệu cho modal
                        $('#modal-title').text('Thêm vào kho');

                        // thiết lập nút gửi là thêm mới
                        $('#action-btn').attr('data-type', 'create');
                        $('#action-btn').text('Thêm');

                        // hiển thị modal
                        $('#modal').modal('show');
                    }
                }
            });
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa kho');
            bindKho(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-branch');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa sản phẩm này khỏi kho tại chi nhánh <b>'+name+'?<b></b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/kho/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove(); 
                }
            });
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa kho');
                bindKho(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-branch');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa sản phẩm này khỏi kho tại chi nhánh <b>'+name+'?<b></b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });
        });

        function bindKho(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/kho/ajax-get-kho',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', !bool);

                    // gán dữ liệu cho modal
                    var id_cn = data.chinhanh.id;

                    $('#branch option[value="'+data.chinhanh.id+'"]').prop('selected', true);
                    $('#qty_in_stock').val(data.slton);

                    // sản phẩm đang xem
                    setTimeout(() => {
                        renderProductOption(data.sanpham, id_cn);
                        var id_sp = data.sanpham[0].id;
                        
                        // các sản phẩm khác
                        for(var i = 0; i < data.lst_product.length; i++){
                            var product = data.lst_product[i][0];
                            if(product.id != id_sp){
                                var option = $('<div data-id="'+product.id+'" data-name="'+product.tensp+'" data-branch="'+id_cn+'" class="product-option select-single-option">'+
                                                    '<div class="d-flex">'+
                                                        '<img src="images/phone/'+product.hinhanh+'" alt="product image" width="70px">'+
                                                        '<div class="ml-10 fz-14">'+
                                                            '<div class="d-flex align-items-center fw-600">'+
                                                                product.tensp+
                                                                '<i class="fas fa-circle ml-5 mr-5 fz-5"></i>'+
                                                                product.mausac+
                                                            '</div>'+
                                                            '<div>Ram: '+product.ram+'</div>'+
                                                            '<div>Dung lượng: '+product.dungluong+'</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>');
    
                                option.appendTo($('#list-product'));
                            }
                        }
                    }, 200);
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiQtyInStock = validateQtyInStock($('#qty_in_stock'));

            // bẫy lỗi xong kiểm tra loại
            if(valiQtyInStock){
                $('.loader').show();

                var data = {
                    'id_cn': $('#branch').val(),
                    'id_sp': $('#product_id_inp').val(),
                    'slton': $('#qty_in_stock').val(),
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/kho',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');
                            // render vào view đầu danh sách
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                            
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/kho/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data.html); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi số lượng
        function validateQtyInStock(qty) {
            if(qty.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(qty.val() == ''){
                qty.addClass('required');
                qty.after('<span class="required-text">Vui lòng nhập số lượng</span>');
                return false;
            }

            // số lượng không hợp lệ
            if(qty.val() > 100){
                qty.addClass('required');
                qty.after('<span class="required-text">Số lượng tối đa là 100</span>');
                return false;
            }

            return true;
        }

        $('#qty_in_stock').keyup(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('select').attr('disabled', false);
            $('input, textarea').attr('readonly', false);
            $('#action-btn').show();
            $('#product').children().remove();
            $('#list-product').children().remove();
            $('#product-box').hide();
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                if(arrFilter.length != 0){
                    filter();
                } else {
                    var keyword = $(this).val().toLocaleLowerCase();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/kho/ajax-search',
                        type: 'POST',
                        data: {'keyword': keyword},
                        success: function(data){
                            $('#lst_data').children().remove();
                            $('#lst_data').append(data);
                            loadMoreFlag = keyword == '' ? false : true;
                            $('#loadmore').hide();
                        }
                    });
                }
            }, 500);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });

        // show lọc
        $('#filter-kho').click(function(){
            $('.filter-div').toggle('blind');
        });

        var arrFilter = [];
        // danh sách kết quả lọc
        $('[name="filter"]').change(function(){
            if($(this).is(':checked')){
                arrFilter.push($(this).val())
            } else {
                var i = arrFilter.indexOf($(this).val());
                arrFilter.splice(i, 1);
            }

            filter();
        });

        function filter() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/kho/ajax-filter',
                type: 'POST',
                data: {'arrFilter': arrFilter, 'keyword': $('#search').val().toLocaleLowerCase()},
                success: function(data){
                    $('#lst_data').children().remove();
                    $('#lst_data').append(data);
                    if(arrFilter.length == 0){
                        loadMoreFlag = false;
                    } else {
                        loadMoreFlag = true;
                    }
                    $('#loadmore').hide();
                }
            });
        }
    }
    /*=======================================================================================================================
                                                           Chi nhánh
    =======================================================================================================================*/
    else if(url == 'chinhanh'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới chi nhánh');
            $('#status').hide();
            $('label[for="status"]').hide();

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết chi nhánh');
            bindChiNhanh(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa chi nhánh');
            bindChiNhanh(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa chi nhánh?</b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        function bindChiNhanh(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/chinhanh/ajax-get-chinhanh',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    // gán dữ liệu cho modal
                    $('#address').val(data.diachi);
                    $('#tel').val(data.sdt);
                    $('#province option[value="'+data.id_tt+'"]').prop('selected', true);
                    $('#status option[value="'+data.trangthai+'"]').prop('selected', true);

                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiAddress = validateAddress($('#address'));
            var valiTel = validatePhoneNumber($('#tel'));

            // bẫy lỗi xong kiểm tra loại
            if(valiAddress && valiTel){
                $('.loader').show();

                var data = {
                    'diachi': $('#address').val(),
                    'sdt': $('#tel').val(),
                    'id_tt': $('#province').val(),
                    'trangthai': $('#status').val(),
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/chinhanh',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thêm vào đầu danh sách
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/chinhanh/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data.html); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        $('#address').keyup(function(){
            removeRequried($(this));
        });
        $('#tel').keyup(function(){
            valiPhonenumberTyping($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('select').attr('disabled', false);
            $('input, textarea').attr('readonly', false);
            removeRequried($('#address'));;
            removeRequried($('#tel'));
            $('#status').show();
            $('label[for="status"]').show();
            $('#action-btn').show();
            idx = 1;
            arrayBase64 = [];
            $('#qty-image').text('');
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết chi nhánh');
                bindChiNhanh(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa chi nhánh');
                bindChiNhanh(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa chi nhánh?</b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });

            // phục hồi
            $('.undelete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                restore(id);
            });
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/chinhanh/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // thay nút
                    var restoreBtn = $('<div data-id="'+id+'" class="undelete-btn"><i class="fas fa-trash-undo"></i></div>')
                    $('.delete-btn[data-id="'+id+'"]').replaceWith(restoreBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Ngừng hoạt động');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            });
        });

        // phục hồi
        $('.undelete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            restore(id);
        });

        function restore(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/chinhanh/ajax-restore',
                type: 'POST',
                data: {'id': id},
                success: function(){
                    // thay nút
                    var deleteBtn = $('<div data-id="'+id+'" class="delete-btn"><i class="fas fa-trash"></i></div>')
                    $('.undelete-btn[data-id="'+id+'"]').replaceWith(deleteBtn);

                    // cập nhật trạng thái
                    $('.trangthai[data-id="'+id+'"]').text('Hoạt động');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            })
        }

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/chinhanh/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').children().remove();
                        $('#lst_data').append(data);
                        loadMoreFlag = true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Tỉnh thành
    =======================================================================================================================*/
    else if(url == 'tinhthanh'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới tỉnh thành');

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa tỉnh thành');
            bindTinhThanh(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Xóa <b>'+name+'?<b></b>');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/tinhthanh/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove(); 
                }
            });
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa tỉnh thành');
                bindTinhThanh(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Xóa <b>'+name+'?<b></b>');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });
        });

        function bindTinhThanh(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/tinhthanh/ajax-get-tinhthanh',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', !bool);

                    // gán dữ liệu cho modal
                    $('#name').val(data.tentt);
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiName = validateName($('#name'));

            // bẫy lỗi xong kiểm tra loại
            if(valiName){
                $('.loader').show();

                var data = {
                    'tentt': capitalize($('#name').val())
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/tinhthanh',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // tỉnh thành đã tồn tại
                            if(data == 'exists'){
                                $('#name').addClass('required');
                                $('#name').after('<span class="required-text">Tỉnh thành này đã tồn tại</span>');
                                return;
                            }
                            $('#modal').modal('hide');

                            // thêm vào đầu danh sách
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/tinhthanh/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // tỉnh thành đã tồn tại
                            if(data == 'exists'){
                                $('#name').addClass('required');
                                $('#name').after('<span class="required-text">Tỉnh thành này đã tồn tại</span>');
                                return;
                            }
                            
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data.html); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        $('#name').keyup(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('input, textarea').attr('readonly', false);
            $('#action-btn').show();
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/tinhthanh/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').children().remove();
                        $('#lst_data').append(data);
                        loadMoreFlag = true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Voucher
    =======================================================================================================================*/
    else if(url == 'voucher'){
        let content;
        ClassicEditor
            .create($('#content')[0])
            .then(editor => {
                content = editor;
                $('#modal').on('hidden.bs.modal', function(){
                    editor.setData('');
                });
            })
            .catch( error => {
                console.error( error );
            });

        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới voucher');

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết voucher');
            bindVoucher(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa voucher');
            bindVoucher(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');

            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa voucher này ?');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/voucher/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');
                }
            });
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết voucher');
                bindVoucher(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa voucher');
                bindVoucher(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');

                // gán dữ liệu cho modal xóa
                $('#delete-content').text('Xóa voucher này ?');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });
        });

        function bindVoucher(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/voucher/ajax-get-voucher',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);
                    $('select').attr('disabled', bool);

                    // gán dữ liệu cho modal
                    $('#code').val(data.code);
                    $('#discount').val(data.chietkhau * 100);
                    $('#condition').val(data.dieukien);
                    $('#qty').val(data.sl);
                    content.setData(data.noidung);
                    $('#start').val(data.ngaybatdau);
                    $('#end').val(data.ngayketthuc);
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiCode = validateCode($('#code'));
            var valiDiscount = validateDiscount($('#discount'));
            var valiQty = validateQty($('#qty'));
            var valiContent = validateVoucherContent(content);
            var valiStart = validateDateStart($('#start'));
            var valiEnd = validateDateEnd($('#end'), $('#start'));

            // bẫy lỗi xong kiểm tra loại
            if(valiCode && valiDiscount && valiQty && valiContent && valiStart && valiEnd){
                $('.loader').show();

                var data = {
                    'code': $('#code').val(),
                    'noidung': content.getData(),
                    'chietkhau': ($('#discount').val()/100),
                    'dieukien': $('#condition').val(),
                    'ngaybatdau': $('#start').val(),
                    'ngayketthuc': $('#end').val(),
                    'sl': $('#qty').val(),
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/voucher',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // voucher đã tồn tại
                            if(data == 'exists'){
                                $('#code').addClass('required');
                                $('#code').after('<span class="required-text">Voucher này đã tồn tại</span>');
                                return;
                            }
                            $('#modal').modal('hide');

                            // thêm vào đầu danh sách
                            $('#lst_data').prepend(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/voucher/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();

                            // tỉnh thành đã tồn tại
                            if(data == 'exists'){
                                $('#code').addClass('required');
                                $('#code').after('<span class="required-text">Voucher này đã tồn tại</span>');
                                return;
                            }
                            
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi code
        function validateCode(code) {
            if(code.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(code.val() == ''){
                code.addClass('required');
                code.after('<span class="required-text">Vui lòng nhập Code</span>');
                return false;
            }

            return true;
        }

        // bẫy lỗi số lượng voucher
        function validateQty(qty) {
            if(qty.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(qty.val() == ''){
                qty.addClass('required');
                qty.after('<span class="required-text">Vui lòng nhập số lượng</span>');
                return false;
            }

            return true;
        }

        // bẫy lỗi nội dung voucher
        function validateVoucherContent(content) {
            if($('#content').hasClass('required')){
                return false;
            }

            // chưa nhập
            if(content.getData() == ''){
                $('#content').addClass('required');
                $('#content').after('<span class="required-text">Vui lòng nhập nội dung</span>');
                return false;
            }

            return true;
        }

        $('#code').keyup(function(){
            removeRequried($(this));
            var upper = $(this).val().toUpperCase();
            $(this).val(upper);
        });

        $('#discount').keyup(function(){
            removeRequried($(this));
        });

        $('#condition').keyup(function(){
            removeRequried($(this));
        });

        $('#qty').keyup(function(){
            removeRequried($(this));
        });

        $('#start').change(function(){
            removeRequried($(this));
        });

        $('#end').change(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('input, textarea').attr('readonly', false);
            $('select').attr('disabled', false);
            removeRequried($('#code'));
            removeRequried($('#discount'));
            removeRequried($('#condition'));
            removeRequried($('#qty'));
            removeRequried($('#start'));
            removeRequried($('#end'));
            $('#status').show();
            $('label[for="status"]').show();
            $('#action-btn').show();
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/voucher/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').children().remove();
                        $('#lst_data').append(data);
                        loadMoreFlag = keyword == '' ? false : true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Đơn hàng
    =======================================================================================================================*/
    else if(url == 'donhang'){
        // xác nhận đơn hàng
        $('.confirm-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            orderConfirmatino(id);
        });

        function orderConfirmatino(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/donhang/ajax-order-confirmation',
                type: 'POST',
                data: {'id': id},
                success: function(){
                    // cập nhật trạng thái
                    $('.trangthaidonhang[data-id="'+id+'"]').text('Đã xác nhận');

                    // chuyển thành nút thành công
                    var successBtn = $('<div data-id="'+id+'" class="success-btn"><i class="fas fa-box-check"></i></div>');
                    $('.confirm-btn[data-id="'+id+'"]').replaceWith(successBtn);

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">Đã xác nhận đơn hàng</span>');
                    showToast('#alert-toast');

                    // đánh dấu dòng
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            })
        }

        // đơn hàng thành công
        $('.success-btn').off('click').click(function(){
            var id = $(this).attr('data-id');
            successfulOrder(id);
        });

        function successfulOrder(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/donhang/ajax-successful-order',
                type: 'POST',
                data: {'id': id}, 
                success:function(){
                    // cập nhật trạng thái
                    $('.trangthaidonhang[data-id="'+id+'"]').text('Thành công');

                    // ẩn nút thành công
                    $('.success-btn[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');

                    // đánh dấu dòng
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    tr.css({
                        'background-color': SUCCESS,
                        'transition': '.5s'
                    });
                }
            });
        }

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết đơn hàng #'+id);
            bindOrder(id, true);
        });

        function bindOrder(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/donhang/ajax-get-donhang',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // gán dữ liệu cho modal
                    $('.modal-body[id="order-modal"]').prepend(data);
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // modal hủy đơn hàng
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');

            // gán dữ liệu cho modal xóa
            $('#delete-content').html('Hủy đơn hàng <b>#'+id+'</b>?');
            $('#delete-btn').attr('data-id', id);
            $('#delete-btn').text('Hủy');
            $('.cancel-btn[data-bs-dismiss="modal"]').text('Đóng');
            $('#delete-modal').modal('show');
        });

        // hủy đơn hàng
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/donhang/' + id,
                type: 'DELETE',
                success: function(){
                    $('#delete-modal').modal('hide');
                    
                    // cập nhật trạng thái
                    $('.trangthaidonhang[data-id="'+id+'"]').text('Đã hủy');

                    // ẩn nút xóa
                    $('.delete-btn[data-id="'+id+'"]').remove();

                    // ẩn nút xác nhận & thành công
                    $('.confirm-btn[data-id="'+id+'"]').remove();
                    $('.success-btn[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-danger">Đã hủy đơn hàng</span>');
                    showToast('#alert-toast');
                    
                    // đánh dấu dòng được thêm/chỉnh sửa
                    var tr = $('tr[data-id="'+id+'"]'); 
                    setTimeout(() => {
                        setTimeout(() => {
                            tr.removeAttr('style');    
                        }, 1000);
                        tr.css({
                            'background-color': 'white',
                        });    
                    }, 3000);
                    $('html, body').animate({scrollTop: tr.position().top});
                    tr.css({
                        'background-color': DANGER,
                        'transition': '.5s'
                    });
                }
            })
        });

        $('.modal-body').bind('DOMSubtreeModified', function(){
            setTimeout(() => {
                var height = $('#receiveMethod').height();
                $('#paymentMethod').css('height', height);
            }, 200);
            
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('.modal-body[id="order-modal"]').children()[0].remove();
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết đơn hàng #'+id);
                bindOrder(id, true);
            });
            // xác nhận đơn hàng
            $('.confirm-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                orderConfirmatino(id);
            });

            // thành công
            $('.success-btn').off('click').click(function(){
                var id = $(this).attr('data-id');
                successfulOrder(id);
            });

            // modal hủy đơn hàng
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');

                // gán dữ liệu cho modal xóa
                $('#delete-content').html('Hủy đơn hàng <b>#'+id+'</b>?');
                $('#delete-btn').attr('data-id', id);
                $('#delete-btn').text('Hủy');
                $('.cancel-btn[data-bs-dismiss="modal"]').text('Đóng');
                $('#delete-modal').modal('show');
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                if(Object.keys(arrFilterSort.filter).length != 0 || Object.keys(arrFilterSort.sort) != ''){
                    filterSort();
                } else {
                    var keyword = $(this).val().toLocaleLowerCase();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/donhang/ajax-search',
                        type: 'POST',
                        data: {'keyword': keyword},
                        success: function(data){
                            $('#lst_data').children().remove();
                            $('#lst_data').append(data);
                            loadMoreFlag = true;
                            $('#loadmore').hide();
                        } 
                    });
                }
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });

        var arrFilterSort = {
            filter: {},
            sort: '',
        };
        // show lọc
        $('#filter-donhang').click(function(){
            $('.filter-div').toggle('blind');
            $('.sort-div').hide('blind');
        });

        // show sắp xếp
        $('#sort-donhang').click(function(){
            $('.filter-div').hide('blind');
            $('.sort-div').toggle('blind');
        });

        // thêm bộ lọc
        $('[name="filter"]').change(function(){
            var obj = $(this).data('object');
            if(obj == 'paymentMethod'){
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.paymentMethod == null){
                        arrFilterSort.filter.paymentMethod = [];
                    }
                    arrFilterSort.filter.paymentMethod.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.paymentMethod.indexOf($(this).val());
                    arrFilterSort.filter.paymentMethod.splice(i, 1);
                    if(arrFilterSort.filter.paymentMethod.length == 0){
                        delete arrFilterSort.filter.paymentMethod;
                    }
                }
            } else if(obj == 'receiveMethod'){
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.receiveMethod == null){
                        arrFilterSort.filter.receiveMethod = [];
                    }
                    arrFilterSort.filter.receiveMethod.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.receiveMethod.indexOf($(this).val());
                    arrFilterSort.filter.receiveMethod.splice(i, 1);
                    if(arrFilterSort.filter.receiveMethod.length == 0){
                        delete arrFilterSort.filter.receiveMethod;
                    }
                }
            } else if(obj == 'status'){
                if($(this).is(':checked')){
                    if(arrFilterSort.filter.status == null){
                        arrFilterSort.filter.status = [];
                    }
                    arrFilterSort.filter.status.push($(this).val());
                } else {
                    var i = arrFilterSort.filter.status.indexOf($(this).val());
                    arrFilterSort.filter.status.splice(i, 1);
                    if(arrFilterSort.filter.status.length == 0){
                        delete arrFilterSort.filter.status;
                    }
                }
            }

            filterSort();
        });

        // chọn sắp xếp
        $('[name="sort"]').change(function(){
            arrFilterSort.sort = $(this).val();

            filterSort();
        });

        // danh sách kết quả lọc & sắp xếp
        function filterSort(){
            var keyword = $('#search').val().toLocaleLowerCase();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/donhang/ajax-filter-sort',
                type: 'POST',
                data: {'arrFilterSort': arrFilterSort, 'keyword': keyword},
                success: function(data){
                    $('#lst_data').children().remove();
                    $('#lst_data').append(data);
                    loadMoreFlag = true;

                    if(Object.keys(arrFilterSort.filter).length == 0){
                        $('.filter-badge').hide();    
                    } else {
                        $('.filter-badge').text(Object.keys(arrFilterSort.filter).length);
                        $('.filter-badge').show();
                    }
                    if(arrFilterSort.sort != ''){
                        $('.sort-badge').show();
                    }
                    $('#loadmore').hide();
                }
            })
        }
    }
    /*=======================================================================================================================
                                                           Bảo hành
    =======================================================================================================================*/
    else if(url == 'baohanh'){
        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết bảo hành');
            bindBaoHanh(id);
        });

        function bindBaoHanh(id) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/baohanh/ajax-get-baohanh',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // gán dữ liệu cho modal
                    $('#product-img').attr('src', 'images/phone/' + data.sanpham.hinhanh);
                    $('#product-name').text(data.sanpham.tensp);
                    $('#product-ram').text(data.sanpham.ram);
                    $('#product-capacity').text(data.sanpham.dungluong);
                    $('#product-color').text(data.sanpham.mausac);
                    $('#product-imei').text(data.imei);
                    if(data.trangthai == 1){
                        $('#warranty-status').text('Trong bảo hành');
                        $('#warranty-status').addClass('success-color');
                    } else {
                        $('#warranty-status').text('Hết hạn');
                        $('#warranty-status').addClass('warning-color');
                    }
                    $('#warranty').text(data.baohanh);
                    $('#start').text(data.ngaymua);
                    $('#end').text(data.ngayketthuc);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });
        }

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết bảo hành');
                bindBaoHanh(id);
            });
        });

        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/baohanh/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').children().remove();
                        $('#lst_data').append(data);
                        loadMoreFlag = keyword == '' ? false : true;
                        $('#loadmore').hide();
                    }
                });
            }, 300);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        });
    }
    /*=======================================================================================================================
                                                           Slideshow
    =======================================================================================================================*/
    else if(url == 'slideshow'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới Slide');

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết Slideshow');
            bindSlideshow(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa Slideshow');
            bindSlideshow(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');

            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa slide này ?');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/slideshow/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');
                }
            });
        });

        function bindSlideshow(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/slideshow/ajax-get-slideshow',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);

                    // gán dữ liệu cho modal
                    $('#link').val(data.link);
                    $('#image-preview').attr('src', 'images/slideshow/' + data.hinhanh);
                    bool == true ? $('#choose_image').hide() : $('#choose_image').show();
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // dialog chọn hình
        $('#choose_image').click(function(){
            $('#image_inp').click();
        });

        // chọn hình
        $('#image_inp').change(function(){
            if($(this).val() == ''){
                return;
            }

            removeRequried($('#image-preview'));

            // kiểm tra file hình
            var fileName = this.files[0].name.split('.');
            var extend = fileName[fileName.length - 1];

            // kiểm tra có phải là hình ảnh không
            if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                $('#image-preview').attr('src', URL.createObjectURL(this.files[0]));
                // url image => base64
                getBase64FromUrl(URL.createObjectURL(this.files[0]), function(base64){
                    $('#base64').val(base64);
                });
            }
            // không phải hình ảnh
            else{
                alert('Bạn chỉ được phép upload hình ảnh');
                $(this).val('');
                $('#image-preview').attr('src', 'images/700x400.png');
            }
        });

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiLink = validateLink($('#link'));
            var valiImage = validateImageSlideshow($('#image_inp'));

            // bẫy lỗi xong kiểm tra loại
            if(valiLink && valiImage){
                $('.loader').show();

                var data = {
                    'link': $('#link').val(),
                    'hinhanh': $('#base64').val(),
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/slideshow',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thêm vào cuối danh sách
                            $('#lst_data').append(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/slideshow/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi link
        function validateLink(link) {
            if(link.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(link.val() == ''){
                link.addClass('required');
                link.after('<span class="required-text">Vui lòng nhập đường dẫn liên kết</span>');
                return false;
            }

            return true;
        }

        // bẫy lỗi hình ảnh
        function validateImageSlideshow(image) {
            if($('#image-preview').hasClass('required')){
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('#image-preview').attr('src') == 'images/700x400.png'){
                $('#image-preview').addClass('required');
                $('#image-preview').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                return false;
            }

            return true;
        }

        $('#link').keyup(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('input').attr('readonly', false);
            removeRequried($('#link'));
            removeRequried($('#image-preview'));
            $('#image-preview').attr('src', 'images/700x400.png');
            $('#base64').val('');
            $('#image_inp').val('');
            $('#choose_image').show();
            $('#action-btn').show();
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết Slideshow');
                bindSlideshow(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa Slideshow');
                bindSlideshow(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');

                // gán dữ liệu cho modal xóa
                $('#delete-content').text('Xóa slide này ?');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });
        });
    }
    /*=======================================================================================================================
                                                           Banner
    =======================================================================================================================*/
    else if(url == 'banner'){
        // modal tạo mới
        $('.create-btn').off('click').click(function(){
            // gán dữ liệu cho modal
            $('#modal-title').text('Tạo mới Banner');

            // thiết lập nút gửi là thêm mới
            $('#action-btn').attr('data-type', 'create');
            $('#action-btn').text('Thêm');

            // hiển thị modal
            $('#modal').modal('show');
        });

        // modal chi tiết
        $('.info-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chi tiết Banner');
            bindBanner(id, true);
        });

        // modal chỉnh sửa
        $('.edit-btn').off('click').click(function(){
            var id = $(this).data('id');
            $('#modal-title').text('Chỉnh sửa Banner');
            bindBanner(id);
        });

        // modal xóa
        $('.delete-btn').off('click').click(function(){
            var id = $(this).attr('data-id');

            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa banner này ?');
            $('#delete-btn').attr('data-id', id);
            $('#delete-modal').modal('show');
        });

        // xóa
        $('#delete-btn').click(function(){
            var id = $(this).attr('data-id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/banner/' + id,
                type: 'DELETE',
                success:function(){
                    $('.loader').fadeOut();
                    $('#delete-modal').modal('hide');

                    // xóa dòng
                    $('tr[data-id="'+id+'"]').remove();

                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-danger">'+DELETE_MESSAGE+'</span>');
                    showToast('#slideshow-msp-toast');
                }
            });
        });

        function bindBanner(id, bool = false) {
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/banner/ajax-get-banner',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // thiết lập quyền
                    $('input, textarea').attr('readonly', bool);

                    // gán dữ liệu cho modal
                    $('#link').val(data.link);
                    $('#image-preview').attr('src', 'images/banner/' + data.hinhanh);
                    bool == true ? $('#choose_image').hide() : $('#choose_image').show();
                
                    // thiết lập nút gửi là cập nhật
                    $('#action-btn').attr('data-type', 'edit');
                    $('#action-btn').text('Cập nhật');
                    $('#action-btn').attr('data-id', id);

                    // hiển thị modal
                    $('#modal').modal('show');
                }
            });

            // ẩn/hiện nút thêm (cập nhật);
            bool == false ? $('#action-btn').show() : $('#action-btn').hide();
        }

        // dialog chọn hình
        $('#choose_image').click(function(){
            $('#image_inp').click();
        });

        // chọn hình
        $('#image_inp').change(function(){
            if($(this).val() == ''){
                return;
            }

            removeRequried($('#image-preview'));

            // kiểm tra file hình
            var fileName = this.files[0].name.split('.');
            var extend = fileName[fileName.length - 1];

            // kiểm tra có phải là hình ảnh không
            if(extend == 'jpg' || extend == 'jpeg' || extend == 'png'){
                $('#image-preview').attr('src', URL.createObjectURL(this.files[0]));
                // url image => base64
                getBase64FromUrl(URL.createObjectURL(this.files[0]), function(base64){
                    $('#base64').val(base64);
                });
            }
            // không phải hình ảnh
            else{
                alert('Bạn chỉ được phép upload hình ảnh');
                $(this).val('');
                $('#image-preview').attr('src', 'images/700x400.png');
            }
        });

        // thêm|sửa
        $('#action-btn').click(function(){
            // bẫy lỗi
            var valiLink = validateLink($('#link'));
            var valiImage = validateImageBanner($('#image_inp'));

            // bẫy lỗi xong kiểm tra loại
            if(valiLink && valiImage){
                $('.loader').show();

                var data = {
                    'link': $('#link').val(),
                    'hinhanh': $('#base64').val(),
                };
                // thêm mới
                if($(this).attr('data-type') == 'create'){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/banner',
                        type: 'POST',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thêm vào cuối danh sách
                            $('#lst_data').append(data.html);

                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="alert-toast" class="alert-toast-right alert-toast-right-success">'+CREATE_MESSAGE+'</span>');
                            showToast('#alert-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+data.id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
                // chỉnh sửa
                else {
                    var id = $(this).attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: 'admin/banner/' + id,
                        type: 'PUT',
                        data: data,
                        success:function(data){
                            $('.loader').fadeOut();
                            $('#modal').modal('hide');

                            // thay thế
                            $('tr[data-id="'+id+'"]').replaceWith(data); 
                            
                            // toast
                            if($('#toast').children().length){
                                $('#toast').children().remove();
                            }
                            $('#toast').append('<span id="slideshow-msp-toast" class="alert-toast-right alert-toast-right-success">'+EDIT_MESSAGE+'</span>');
                            showToast('#slideshow-msp-toast');

                            // đánh dấu dòng được thêm/chỉnh sửa
                            var tr = $('tr[data-id="'+id+'"]'); 
                            setTimeout(() => {
                                setTimeout(() => {
                                    tr.removeAttr('style');    
                                }, 1000);
                                tr.css({
                                    'background-color': 'white',
                                });    
                            }, 3000);
                            $('html, body').animate({scrollTop: tr.position().top});
                            tr.css({
                                'background-color': SUCCESS,
                                'transition': '.5s'
                            });
                        }
                    });
                }
            }
        });

        // bẫy lỗi link
        function validateLink(link) {
            if(link.hasClass('required')){
                return false;
            }

            // chưa nhập
            if(link.val() == ''){
                link.addClass('required');
                link.after('<span class="required-text">Vui lòng nhập đường dẫn liên kết</span>');
                return false;
            }

            return true;
        }

        // bẫy lỗi hình ảnh
        function validateImageBanner(image) {
            if($('#image-preview').hasClass('required')){
                return false;
            }

            // chưa chọn
            if(image.val() == '' && $('#image-preview').attr('src') == 'images/500x120.png'){
                $('#image-preview').addClass('required');
                $('#image-preview').after('<span class="required-text">Vui lòng chọn hình ảnh</span>');
                return false;
            }

            return true;
        }

        $('#link').keyup(function(){
            removeRequried($(this));
        });

        // reset modal
        $('#modal').on('hidden.bs.modal', function(){
            $('#form').trigger('reset');
            $('input').attr('readonly', false);
            removeRequried($('#link'));
            removeRequried($('#image-preview'));
            $('#image-preview').attr('src', 'images/700x400.png');
            $('#base64').val('');
            $('#image_inp').val('');
            $('#choose_image').show();
            $('#action-btn').show();
        });

        $('#lst_data').bind('DOMSubtreeModified', function(){
            // modal chi tiết
            $('.info-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chi tiết Banner');
                bindBanner(id, true);
            });

            // modal chỉnh sửa
            $('.edit-btn').off('click').click(function(){
                var id = $(this).data('id');
                $('#modal-title').text('Chỉnh sửa Banner');
                bindBanner(id);
            });

            // modal xóa
            $('.delete-btn').off('click').click(function(){
                var id = $(this).attr('data-id');

                // gán dữ liệu cho modal xóa
                $('#delete-content').text('Xóa banner này ?');
                $('#delete-btn').attr('data-id', id);
                $('#delete-modal').modal('show');
            });
        });
    }
    /*=======================================================================================================================
                                                           IMEI
    =======================================================================================================================*/
    else if(url == 'imei'){
        // tìm kiếm
        $('#search').keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(() => {
                var keyword = $(this).val().toLocaleLowerCase();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'admin/imei/ajax-search',
                    type: 'POST',
                    data: {'keyword': keyword},
                    success: function(data){
                        $('#lst_data').attr('data-loadmore', '');
                        $('#lst_data').children().remove();
                        $('#lst_data').append(data);
                        $('#loadmore').hide();
                    }
                })
            }, 500);
            $('#lst_data').children().remove();
            $('#loadmore').show();
        }); 
    }
});