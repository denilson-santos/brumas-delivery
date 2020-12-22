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