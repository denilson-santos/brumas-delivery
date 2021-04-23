$(function () {
    $('.category button.edit').on('click', function() {
        $(this).addClass('editing');
    });

    $('.item button.delete').on('click', function() {
        $(this).addClass('deleting');
    });    
});