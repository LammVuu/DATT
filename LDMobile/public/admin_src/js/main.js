/*=======================================================================================================================*/
/*                                                       Header
/*=======================================================================================================================*/

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