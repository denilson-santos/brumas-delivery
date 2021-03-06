$(function () {    
    $('.rating-restaurant-widget .id-restaurant').each((index, element) => { 
        var idRestaurant = element.value;
        var rating = parseFloat($(`span.restaurant-rating-${idRestaurant}`).html());
        
        $(`.rating-restaurant-widget-${idRestaurant} .rating-read-only`).rateYo({
            rating: rating,
            starWidth: '18px',
            readOnly: true,
            ratedFill: '#ffc929',
            normalFill: '#ddd',
            spacing: '3px',
            starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>'
        });         
    });    

    $('span.favorite i').on('click', function(e) {
        if ($(this).hasClass('favorited')) {
            $(this).removeClass('favorited');
        } else {
            $(this).addClass('favorited');
        }

        var userId = $(this).closest('span').attr('user-id');
        var restaurantId = $(this).closest('span').attr('restaurant-id');
        var status = $(this).hasClass('favorited') ? 1 : 0;

        $.ajax({
            type: 'POST',
            url: '/account/favorite-status',
            data: {
                user_id: userId,
                restaurant_id: restaurantId,
                status: status
            },
            success: function (response) {
                
            }
        });

        return false;
    });
});
