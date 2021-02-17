$(function () {
    $('#pills-payment-methods a.collapsable-online').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $(this).find('i').removeClass('fa-chevron-down');
            $(this).find('i').addClass('fa-chevron-right');
        } else {
            $(this).find('i').removeClass('fa-chevron-right');
            $(this).find('i').addClass('fa-chevron-down');
        }
    });

    $('#pills-payment-methods a.collapsable-on-delivery').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $(this).find('i').removeClass('fa-chevron-down');
            $(this).find('i').addClass('fa-chevron-right');
        } else {
            $(this).find('i').removeClass('fa-chevron-right');
            $(this).find('i').addClass('fa-chevron-down');
        }
    });
});

