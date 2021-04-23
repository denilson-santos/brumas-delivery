$(function() {
    $('#modalDeleteCategory button#delete').on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/category/delete',
            data: {
                restaurant_id: $('.menu-container input[name="restaurantId"]').val(),
                category_id: $('.category .deleting').closest('.category').attr('data-category-id')
            },
            beforeSend: function() {
                $('#modalDeletePlate button#delete').attr('disabled', true);
            },
            success: function (response) {                
                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Categoria deletada com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });

                // Refresh page
                setTimeout(function() {
                    location.reload();
                }, 2100);
            }
        });
        
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