$(function () {
    $('#modalAddCategory').on('show.bs.modal', function() {
        var categoriesSelected = $('.category');
        
        $('select#addCategory option:not(:first-child)').removeAttr('disabled');

        categoriesSelected.each(function (index, element) {
            var category = $(element).data('category-id');

            $(`select#addCategory option[value="${category}"]`).attr('disabled', true);                
        });
        
        $('#modalAddCategory select#addCategory').prop('selectedIndex', 0);
        
        // Refresh select
        $('#modalAddCategory select#addCategory').selectric('refresh');

        $('#modalAddCategory button#saveAddCategory').attr('disabled', true);
    });

    $('#modalAddCategory select#addCategory').on('change', function (e) {
        var category = $(this).val();

        if (!category) {
            $('button#saveAddCategory').attr('disabled', true);
        } else {
            $('button#saveAddCategory').attr('disabled', false);
        }
    });

    $('#modalAddCategory #saveAddCategory').on('click', function() {
        $('.no-category').css('display', 'none');

        var categoryId = $('#modalAddCategory select#addCategory').val();
        var categoryName = $('#modalAddCategory select#addCategory').data('value');

        $('.menu-container').append(`
            <div class="category mt-4" data-category-id="${categoryId}" data-category-name="${categoryName}">
                <div class="header">
                    <div class="name">
                        <h5 class="mb-0">${categoryName}</h5>
                    </div>

                    <div class="actions">
                        <a href="javascript:void(0)" class="edit-category"><i class="fas fa-pencil-alt mr-1" class="edit-category"></i></a>
                        <a href="javascript:void(0)" class="delete-category"><i class="fas fa-trash-alt mr-1"></i></a>
                    </div>
                </div>

                <div class="content"></div>

                <div class="footer">
                    <a href="javascript:void(0)" class="add-plate"><i class="fas fa-plus ml-0 mr-1"></i>Adicionar Prato</a>
                </div>
            </div> 
        `).on('click', 'a.add-plate', function() {
            $(this).addClass('adding');
            $('#modalAddPlate').modal('show');
        }).on('click', 'a.edit-category', function() {
            $(this).addClass('editing');
            $('#modalEditCategory').modal('show');
        }).on('click', 'a.delete-category', function() {
            $(this).addClass('deleting');
            $('#modalDeleteCategory').modal('show');
        });
    });

    $('.menu-container').on('click', 'a.add-plate', function() {
        $(this).addClass('adding');
        $('#modalAddPlate').modal('show');
    }).on('click', 'a.edit-category', function() {
        $(this).addClass('editing');
        $('#modalEditCategory').modal('show');
    }).on('click', 'a.delete-category', function() {
        $(this).addClass('deleting');
        $('#modalDeleteCategory').modal('show');
    });
});