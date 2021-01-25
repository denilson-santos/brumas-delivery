$(function () {
    $('.table-account-orders .more-order-details').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $('.table-account-orders .more-order-details i').removeClass('fa-chevron-down');
            $('.table-account-orders .more-order-details i').addClass('fa-chevron-up');
            $('.table-account-orders .more-order-details i').addClass('text-primary');
        } else {
            $('.table-account-orders .more-order-details i').removeClass('fa-chevron-up');
            $('.table-account-orders .more-order-details i').removeClass('text-primary');
            $('.table-account-orders .more-order-details i').addClass('fa-chevron-down');
        }
    });
});