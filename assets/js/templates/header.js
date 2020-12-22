$(function () {
    $('#search').on('click', function (e) { 
        e.preventDefault();
        $('.filter-area form input[name="filters[search]"]').html('');
        $('.filter-area form input[name="filters[category]"]').html('');
    
        var searchTerm = $('.search-area input[name="search"]').val();
     
        console.log('search',searchTerm);
    
        if (searchTerm) {
            $('.filter-area form input[name="filters[search]"]').val(searchTerm);
            $('.filter-area form').attr('action', `/?search=${searchTerm}`);
            $('.filter-area form').submit();
        }
    });    

    $('.dropdown-languages li').on('click', function() {
        var language = $(this).data('lang');
        var url = window.location.href;

        $.ajax({
            type: 'POST',
            url: '/lang',
            data: { language },
            success: function (response) {
                window.location.href = url;
            }
        });
    });
});