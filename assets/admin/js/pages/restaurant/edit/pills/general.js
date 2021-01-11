$(function () {
    // Adapts the select multiple of the selectric to accept only 2 checked options
    var mainCategories = $('.restaurant-main-categories').selectric();
    
    mainCategories.on('selectric-before-change', function(event, element, selectric) {                
        if (selectric.state.selectedIdx.length == 3) {
            $(`.selectric-restaurant-main-categories ul li:nth-child(${selectric.state.selectedIdx[0] + 1})`).removeClass('selected');
            selectric.state.selectedIdx.shift();
        }        
    });
});