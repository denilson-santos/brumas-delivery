$(function () {
    $('.filter-area').find('input, select').on('change', function () {
        $('.filter-area form').submit();
    }); 

    var filterType = $('.payment-type').select2({
        theme: 'bootstrap4'
    });

    $('#search').on('click', function (e) { 
       e.preventDefault();
       $('.filter-area form input[name="term"] ').html('');
       $('.filter-area form input[name="category"] ').html('');

       var searchTerm = $('input[name="term"]').val();
    
       console.log('term',searchTerm);

       $('.filter-area form input[name="term"] ').val(searchTerm);
       $(".filter-area form").attr("action", BASE_URL+"search");
       $('.filter-area form').submit();
    });

    $(".rating-filter-page-home.rating").rateYo({
        rating: parseInt($('input.rating-page-home').val()),
        starWidth: '18px',
        ratedFill: '#ffc929',
        normalFill: '#ddd',
        fullStar: true,
        spacing: '3px',
        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>',
    }).on('rateyo.set', function (e, data) {
        $('input.rating-page-home').val(data.rating);
        $('.filter-area form').submit();
    });   

    $("#category-slide").slick({
        dots: false,
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        zIndex: -999,
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
    });

    var categoryClicked = window.location.pathname.split('/').pop();

    $('#category-slide').append(`<input type="hidden" class="category-${categoryClicked}">`);
    $(`#category-slide .category-${categoryClicked}`).css('opacity', 0.7);
});