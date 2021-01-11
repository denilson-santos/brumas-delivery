$(function () {
    $('.table-restaurant-rates .more-order-details').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $('.table-restaurant-rates .more-order-details i').removeClass('fa-chevron-right');
            $('.table-restaurant-rates .more-order-details i').addClass('fa-chevron-down');
            $('.table-restaurant-rates .more-order-details i').addClass('text-primary');
        } else {
            $('.table-restaurant-rates .more-order-details i').removeClass('fa-chevron-down');
            $('.table-restaurant-rates .more-order-details i').removeClass('text-primary');
            $('.table-restaurant-rates .more-order-details i').addClass('fa-chevron-right');
        }
    });

    $(".rating-page-restaurant-rates .rating-read-only").rateYo({
        rating: 3.1,
        starWidth: '18px',
        readOnly: true,
        ratedFill: '#ffc929',
        normalFill: '#ddd',
        spacing: '3px',
        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
    });
});    