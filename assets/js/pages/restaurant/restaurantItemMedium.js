$(function () {     
    $('.restaurant-item-medium .id-restaurant').each((index, element) => { 
        var idRestaurant = element.value;
        var rating = parseFloat($(`span.restaurant-rating-medium-${idRestaurant}`).html());
        
        $(`.restaurant-rating-widget-medium-${idRestaurant}.rating-read-only`).rateYo({
            rating: rating,
            starWidth: '18px',
            ratedFill: '#ffc929',
            normalFill: '#ddd',
            readOnly: true,
            spacing: '3px',
            starSvg: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>',
        });         
    });
}); 