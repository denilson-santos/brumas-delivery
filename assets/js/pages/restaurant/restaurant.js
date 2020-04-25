$('.form-purchase #buy').on('click', function (e) {
    e.preventDefault();
    $(this).attr("disabled", true);
});

$('.form-purchase #addToCart').on('click', function (e) {
    e.preventDefault();
    $(this).attr("disabled", true);
});

$('.form-purchase input[type="button"]').on('click', function (e) {
    var quantity = parseInt($('.form-purchase #quantity').val());

    if($(this).val() == '-') {
        if (quantity > 1) {
            quantity--;
        }
    } else {
        if (quantity >= 1) {
            quantity++;
        }
    }

    $('.form-purchase #quantity').val(quantity);
});  

$('.form-purchase #quantity').on('keyup', function (e) {
    var quantity = parseInt($(this).val());

    if (quantity == 0 || $(this).val() == '') {
        $('.form-purchase #quantity').val(1);
    }
});    

$(".rating-page-restaurant .rating-read-only").rateYo({
    rating: 3.1,
    starWidth: '18px',
    readOnly: true,
    ratedFill: '#ffcb2e',
    normalFill: '#ddd',
    spacing: '3px',
    starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
});    

$(".rating-page-restaurant-by-user.rating-read-only").rateYo({
    rating: 5,
    starWidth: '18px',
    readOnly: true,
    ratedFill: '#ffcb2e',
    normalFill: '#ddd',
    spacing: '3px',
    starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
});   

$('.rating-read-only').on('hover', function () {
    console.log('1');
    
}, function(){
    console.log('1');
});






