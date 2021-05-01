$(function () {    
    $('.form-purchase #addToCart').on('click', function (e) {
        e.preventDefault();
        $(this).attr("disabled", true);
    });

    $(".rating-page-restaurant .rating-read-only").rateYo({
        rating: 3.1,
        starWidth: '18px',
        readOnly: true,
        ratedFill: '#ffc929',
        normalFill: '#ddd',
        spacing: '3px',
        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
    });    
    
    $(".rating-page-restaurant-by-user.rating-read-only").rateYo({
        rating: 5,
        starWidth: '18px',
        readOnly: true,
        ratedFill: '#ffc929',
        normalFill: '#ddd',
        spacing: '3px',
        starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
    }); 
    
    $('.plate-content').on('click', function() {
        $(this).addClass('showing');
        $('#showPlate').modal('show');
    });
});





