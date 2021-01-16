$(function () {
    $('.table-restaurant-orders .more-order-details').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $('.table-restaurant-orders .more-order-details i').removeClass('fa-chevron-down');
            $('.table-restaurant-orders .more-order-details i').addClass('fa-chevron-up');
            $('.table-restaurant-orders .more-order-details i').addClass('text-primary');
        } else {
            $('.table-restaurant-orders .more-order-details i').removeClass('fa-chevron-up');
            $('.table-restaurant-orders .more-order-details i').removeClass('text-primary');
            $('.table-restaurant-orders .more-order-details i').addClass('fa-chevron-down');
        }
    });
});