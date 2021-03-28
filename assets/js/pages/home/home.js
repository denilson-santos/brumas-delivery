$(function () {
    $('.filter-area').find('input, select').on('change', function () {
        $('.filter-area form').submit();
    }); 

    $('.filter-area .neighborhood .filter-item:gt(5)').hide();
    
    $('.filter-area .neighborhood .show-all').on('click', function() {
        $(this).addClass('d-none');
        
        $('.filter-area .neighborhood .filter-item:gt(5)').show();
        
        $('.filter-area .neighborhood .show-less').removeClass('d-none');
    });
    
    $('.filter-area .neighborhood .show-less').on('click', function() {
        $(this).addClass('d-none');
        
        $('.filter-area .neighborhood .filter-item:gt(5)').hide();
        
        $('.filter-area .neighborhood .show-all').removeClass('d-none');
    });

    $('.payment-type').select2({
        theme: 'bootstrap4'
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

    function getAllUrlParams(url) {

        var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
        var obj = {};

        if (queryString) {
            queryString = queryString.split('#')[0];
        
            var arr = queryString.split('&');
        
            for (var i = 0; i < arr.length; i++) {
            var a = arr[i].split('=');
        
            var paramName = a[0];
            var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];
        
            paramName = paramName.toLowerCase();
            if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();
        
            if (paramName.match(/\[(\d+)?\]$/)) {
        
                var key = paramName.replace(/\[(\d+)?\]/, '');
                if (!obj[key]) obj[key] = [];
        
                if (paramName.match(/\[\d+\]$/)) {
                var index = /\[(\d+)\]/.exec(paramName)[1];
                obj[key][index] = paramValue;
                } else {
                obj[key].push(paramValue);
                }
            } else {
                if (!obj[paramName]) {
                obj[paramName] = paramValue;
                } else if (obj[paramName] && typeof obj[paramName] === 'string'){
                obj[paramName] = [obj[paramName]];
                obj[paramName].push(paramValue);
                } else {
                obj[paramName].push(paramValue);
                }
            }
            }
        }
        
        return obj;
    }

    var urlParams = getAllUrlParams(window.location.href);

    for (var prop in urlParams) {
        var value = urlParams[prop];
        
        if (prop.indexOf('category') >= 0) {
            var categoryClicked = value;
            break;
        }
    }

    // console.log(categoryClicked);
    
    $(`#category-slide .category-${categoryClicked}`).css('opacity', 0.7);       

});