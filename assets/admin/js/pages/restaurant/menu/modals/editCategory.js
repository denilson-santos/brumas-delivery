$(function () {
    $('#modalEditCategory').on('show.bs.modal', function() {
        var categoriesSelected = $('.category');
        var categorySelected = $('.editing').closest('.category').attr('data-category-id');
        
        categoriesSelected.each(function (index, element) {
            var category = $(element).attr('data-category-id');

            if (category == categorySelected) {
                $(`select#editCategory option[value="${category}"]`).removeAttr('disabled');
                $('select#editCategory').prop('selectedIndex', category);
            } else {           
                $(`select#editCategory option[value="${category}"]`).attr('disabled', true);                
            }
        });
        
        // Refresh select
        $('#modalEditCategory select#editCategory').selectric('refresh');

        $('#modalEditCategory button#saveEditCategory').attr('disabled', true);
    });

    $('#modalEditCategory select#editCategory').on('change', function (e) {
        var category = $(this).val();

        if (!category) {
            $('button#saveEditCategory').attr('disabled', true);
        } else {
            $('button#saveEditCategory').attr('disabled', false);
        }

        console.log(category);
    });

    $('#modalEditCategory #saveEditCategory').on('click', function() {        
        var categoryId = $('#modalEditCategory select#editCategory').val();
        var categoryName = $('#modalEditCategory select#editCategory').data('value');

        $('.editing').closest('.category').attr('data-category-id', categoryId);
        $('.editing').closest('.category').attr('data-category-name', categoryName);
        $('.editing').closest('.header').find('h5').text(categoryName);
    });

    $('#modalEditCategory').on('hidden.bs.modal', function() {
        $('.category a.edit-category').removeClass('editing');
    });
});