$(function () {
    $('#search').on('click', function (e) { 
        e.preventDefault();

        search($('.search-area input[name="search"]').val());
    });    

    $('input[name="search"]').on('keyup', function(e) {
        if (e.keyCode == 13) search($('.search-area input[name="search"]').val());
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

    function search(searchTerm) {
        $('.filter-area form input[name="filters[search]"]').html('');
        $('.filter-area form input[name="filters[category]"]').html('');
    
        if (searchTerm) {
            $('.filter-area form input[name="filters[search]"]').val(searchTerm);
            $('.filter-area form').attr('action', `/?search=${searchTerm}`);
            $('.filter-area form').submit();
        }
    }
});