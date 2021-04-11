$(function () {
    $('.table-account-orders .more-order-details').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $(this).find('i').removeClass('fa-chevron-right');
            $(this).find('i').addClass('fa-chevron-down');
            $(this).find('i').addClass('text-primary');
        } else {
            $(this).find('i').removeClass('fa-chevron-down');
            $(this).find('i').addClass('fa-chevron-right');
            $(this).find('i').removeClass('text-primary');
        }
    });
});