$(function() {
    $(window).on('load', function(){
        var scroll_chitiet = sessionStorage.getItem('reload_chitiet');
        if(scroll_chitiet){
            sessionStorage.removeItem('reload_chitiet');
            $('#toast').append('<span id="edit-evaluate-toast" class="alert-toast">'+scroll_chitiet+'</span');
            showToast('#edit-evaluate-toast');
        }
        $('.loader').fadeOut(250);
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
            }, 5000);
    
            $(id).css({
                'transform': 'translateY(0)'
            });
        }, 200);
    }

    // xóa đối tượng
    $('#delete-btn').click(function(){
        var object = $(this).data('object');

        if(object == 'hinhanh'){
            var id = $(this).data('id');
            deleteObject(object, id);
        }
    });

    function deleteObject(object, id) {
        $('#delete-modal').modal('hide');
        $('.loader').show();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/ajax-delete-object',
            type: 'POST',
            data: {'object': object, 'id': id},
            success: function(){
                $('.loader').fadeOut();

                // xóa phần tử trên trang
                $('tr[data-id="1"]').remove();

                // toast thông báo
                if($('#toast').children().length){
                    $('#toast').children().remove();
                }
                $('#toast').append('<span id="hinhanh-toast" class="alert-toast-right alert-toast-right-danger">Xóa thành công</span>');
                showToast('#hinhanh-toast');
            }
        })
    }
    
    /*=======================================================================================================================
                                                           Header
    =======================================================================================================================*/
    
    // đóng/mở sidebar menu
    $('#btn-expand-menu').click(function(){
        if($(this).attr('aria-expanded') == 'true'){
            $('.sidebar-avt').hide();
            $('.sidebar-menu').hide();
            $('.sidebar').css('width', '0');
            $('.content').css('margin-left', '0');
            $(this).attr('aria-expanded', 'false');
        } else {
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
    
    // toast thông báo
    if($('#toast-message').length){
        var toast = $('<div id="message" class="alert-toast"><div class="d-flex align-items-center"><span>'+ $('#toast-message').data('message') +'</span></div></div>');
        $("#toast-message").after(toast);
        showToast('#message');
        $('#toast-message').remove();
    }

    /*=======================================================================================================================
                                                           Hình ảnh
    =======================================================================================================================*/

    // hiển thị modal tạo mới hình ảnh
    $('.create-hinhanh-modal-show').off('click').click(function(){
        // gán dữ liệu cho modal
        $('#modal-title').text('Tạo mới hình ảnh');

        // thiết lập nút gửi là thêm mới
        $('#action-hinhanh-btn').attr('data-type', 'create');
        $('#action-hinhanh-btn').text('Thêm');

        // hiển thị modal
        $('#hinhanh-modal').modal('show');
    });

    // hiển thị modal chỉnh sủa hình ảnh
    $('.edit-hinhanh-modal-show').off('click').click(function(){
        var id = $(this).data('id');

        // gán dữ liệu cho modal
        $('#modal-title').text('Chỉnh sửa hình ảnh');

        // thiết lập nút gửi là cập nhật
        $('#action-hinhanh-btn').attr('data-type', 'edit');
        $('#action-hinhanh-btn').text('Cập nhật');

        // lấy dòng theo id gán vào modal
        $.ajax({
            headers: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/ajax-get-hinhanh',
                type: 'POST',
                data: {'id': id},
                success:function(data){
                    // gán dữ liệu từ data vào các thẻ input trên modal
                }
            }
        });

        // hiển thị modal
        $('#hinhanh-modal').modal('show');
    });

    // thêm|sửa hình ảnh
    $('#action-hinhanh-btn').click(function(){
        // bẫy lỗi

        // ẩn modal
        $('#hinhanh-modal').modal('hide');
        $('.loader').show();

        // bẫy lỗi xong kiểm tra loại

        //  thêm mới
        if($(this).attr('data-type') == 'create'){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/hinhanh/create',
                data: {'data': true},
                success:function(data){
                    $('.loader').fadeOut();
    
                    // render dòng mới vào view
                    $('#lst_hinhanh').append(data);
                    
                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="create-hinhanh-toast" class="alert-toast-right alert-toast-right-success">Thêm mới thành công</span>');
                    showToast('#create-hinhanh-toast');
                }
            });
        }
        // chỉnh sửa
        else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'admin/hinhanh/1/edit',
                data: {'data': true},
                success:function(data){
                    $('.loader').fadeOut();
    
                    // thay thế dòng hiện tại bằng dòng mới chỉnh sửa
                    $('tr[data-id="1"]').replaceWith(data);
                    
                    // toast
                    if($('#toast').children().length){
                        $('#toast').children().remove();
                    }
                    $('#toast').append('<span id="create-hinhanh-toast" class="alert-toast-right alert-toast-right-success">Chỉnh sửa thành công</span>');
                    showToast('#create-hinhanh-toast');
                }
            });
        }
    });

    // hiển thị modal xóa hình ảnh
    $('.delete-hinhanh-btn').click(function(){
        // gán dữ liệu cho modal xóa
        $('#delete-content').text('Xóa hình ảnh của mẫu sản phẩm này?')
        $('#delete-btn').attr('data-object', 'hinhanh');
        $('#delete-btn').attr('data-id', $(this).data('id'));
        $('#delete-modal').modal('show');
    });

    // khi view có phần tử mới xuất hiên, muốn thực hiện sự kiện trên phần tử mới đó
    // thì phải sử dụng DOMSubtreeModified, kiểm tra sự thay đổi của phần con trong phần tử cha
    $('#lst_hinhanh').bind('DOMSubtreeModified', function(){
        // chỉnh sửa hình ảnh
        $('.edit-hinhanh-modal-show').off('click').click(function(){
            var id = $(this).data('id');
    
            // gán dữ liệu cho modal
            $('#modal-title').text('Chỉnh sửa hình ảnh');
    
            // thiết lập nút gửi là cập nhật
            $('#action-hinhanh-btn').attr('data-type', 'edit');
            $('#action-hinhanh-btn').text('Cập nhật');
    
            // lấy dòng theo id gán vào modal
            $.ajax({
                headers: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/ajax-get-hinhanh',
                    type: 'POST',
                    data: {'id': id},
                    success:function(data){
                        // gán dữ liệu từ data vào các thẻ input trên modal
                    }
                }
            });
    
            // hiển thị modal
            $('#hinhanh-modal').modal('show');
        });

        // hiển thị modal xóa hình ảnh
        $('.delete-hinhanh-btn').click(function(){
            // gán dữ liệu cho modal xóa
            $('#delete-content').text('Xóa hình ảnh của mẫu sản phẩm này?')
            $('#delete-btn').attr('data-object', 'hinhanh');
            $('#delete-btn').attr('data-id', $(this).data('id'));
            $('#delete-modal').modal('show');
        });
    });
});