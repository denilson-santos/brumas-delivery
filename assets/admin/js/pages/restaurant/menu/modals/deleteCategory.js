$(function() {
    $('#modalDeleteCategory button#delete').on('click', function() {
        $('.category .deleting').closest('.category').remove();

        if (!$('.category').length) $('.no-category').css('display', 'block');
    });

    $('#modalDeleteCategory button#back').on('click', function() {
        $('.category .deleting').removeClass('deleting');
    });

    $('#modalDeleteCategory').on('hidden.bs.modal', function() {
        $('.category a.delete-category').removeClass('deleting');
    });
});