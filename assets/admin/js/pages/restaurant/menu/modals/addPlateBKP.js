$(function() {
    $('form.add-plate').on('change', function() {

    });

    var count = 1;
    var countItem = 0;
    var editing = false;

    $('form.add-plate button#addComplement').on('click', function() {
        
        $('.no-complement').css('display', 'none');

        count++;

        $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

        $(`
            <div class="complement mt-4">
                <div class="header">
                    <div class="name m-auto col px-0">
                        <input type="text" name="complementName" class="form-control" placeholder="Adicione um complemento">
                        <h5 class="mb-0 d-none"></h5>
                    </div>

                    <div class="d-flex ml-3 mr-4">
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" id="complementRequired${count}" name="complementRequired"  class="custom-control-input">
                            <label class="custom-control-label" for="complementRequired${count}">
                                Obrigatório
                            </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="complementMultiple${count}" name="complementMultiple"  class="custom-control-input">
                            <label class="custom-control-label" for="complementMultiple${count}">
                                Múltiplo
                            </label>
                        </div>
                    </div>

                    <div class="actions m-auto text-right"> 
                        <a href="javascript:void(0)" class="edit-complement d-none"><i class="fas fa-pencil-alt mr-1"></i></a>
                        <a href="javascript:void(0)" class="save-complement"><i class="fas fa-check mr-2"></i></a>
                        <a href="javascript:void(0)" class="delete-complement"><i class="fas fa-trash-alt mr-1"></i></a>
                    </div>
                </div>

                <div class="content"></div>

                <div class="footer">
                    <a href="javascript:void(0)" class="new-item disabled-item"><i class="fas fa-plus ml-0 mr-1"></i>Adicionar Item</a>
                </div>

                <div class="complements d-none"></div>
                <div class="itens d-none"></div>
        `).appendTo('.complement-container').on('click', 'a.edit-complement', function() {
            editingComplement = true;

            $('#modalAddPlate a.new-item').addClass('disabled-item');
            // $('#modalAddPlate button#addComplement').attr('disabled', true);
            $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

            $(this).addClass('d-none');
            $(this).closest('.actions').find('a.save-complement').removeClass('d-none');

            var complement = $(this).closest('.header').find('h5').text();
            
            $(this).closest('.header').find('h5').addClass('d-none');
            $(this).closest('.header').find('input[name="complementName"]').removeClass('d-none').val(complement);

        }).on('click', 'a.save-complement', function() {
            editingComplement = false;
            console.log(count);

            // Validation complement
            $(this).closest('.complement').find('input[name="complementName"]').each(function (index, element) {
                validateField($(element));
            });

            if ($(this).closest('.complement').find('.is-invalid').length) {
                $('#modalAddPlate button#saveAddPlate').attr('disabled', true);
                return;
            }

            $(this).closest('.complement').attr('data-row', count);

            $('#modalAddPlate .new-item').removeClass('disabled-item');
            $('#modalAddPlate button#saveAddPlate').attr('disabled', false);

            $(this).addClass('d-none');
            $(this).closest('.actions').find('a.edit-complement').removeClass('d-none');
            
            var complement = $(this).closest('.header').find('input[name="complementName"]').val();
            
            $(this).closest('.header').find('input[name="complementName"]').addClass('d-none');
            $(this).closest('.header').find('h5').removeClass('d-none').text(complement);

            if ($(this).closest('.complement').find(`.complements input[data-row="${count}"]`).length) {
                $(this).closest('.complement').find('.complements input').attr('data-name', complement);

            } else {
                $(this).closest('.complement').find('.complements').append(`
                    <input type="hidden" name="complement" data-name="${complement}" data-row="${count}">
                `);
            }

        }).on('click', 'a.delete-complement', function() {
            $(this).closest('.complement').remove();

            if (!$('.complement').length) {
                $('.no-complement').css('display', 'block');
                $('#modalAddPlate button#addComplement').attr('disabled', false);
            }

        }).on('click', 'a.new-item', function() {    
            if (editing) return;
        
            countItem++;
            editing = true;

            $('#modalAddPlate button.edit-item').attr('disabled', true);
            $('#modalAddPlate button.delete-item').attr('disabled', true);
            $(this).addClass('disabled-item');
            // $('#modalAddPlate button#addComplement').attr('disabled', true);
            $('#modalAddPlate button#saveAddPlate').attr('disabled', true);
            
            $(`
                <div class="item">
                    <div class="name col pl-0">
                        <input type="text" name="itemName" class="form-control" placeholder="Nome do item">
                        <div class="d-none"></div>
                    </div>
                        
                    <div class="price col">
                        <input type="text" name="itemPrice" class="form-control col-3 money" placeholder="Preço" data-thousands="." data-decimal=",">
                        <div class="d-none"></div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-primary btn-sm edit-item action d-none"><i class="fas fa-pencil-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm save-item action"><i class="fas fa-check"></i></button>
                        <button type="button" class="btn btn-primary btn-sm delete-item action" disabled><i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `).appendTo($(this).closest('.complement').find('.content')).on('click', 'button.edit-item', function() {
                if (editing) return;

                editing = true;
                
                $(this).attr('disabled', true);
                $('#modalAddPlate button.edit-item').attr('disabled', true);
                $('#modalAddPlate button.delete-item').attr('disabled', true);
                $('#modalAddPlate a.new-item').addClass('disabled-item');
                // $('#modalAddPlate button#addComplement').attr('disabled', true);
                $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

                $(this).addClass('d-none');
                $(this).closest('.actions').find('button.save-item').removeClass('d-none');

                var item = $(this).closest('.item').find('.name div').addClass('d-none').text();
                var price = $(this).closest('.item').find('.price div').addClass('d-none').text();

                $(this).closest('.item').find('.name input[name="itemName"]').removeClass('d-none').val(item);
                $(this).closest('.item').find('.price input[name="itemPrice"]').removeClass('d-none').val(price);
            }).on('click', 'button.save-item', function() {
                editing = false;
                // Validation complement itens
                $(this).closest('.item').find('input').each(function (index, element) {
                    validateField($(element));
                });

                if ($(this).closest('.complement').find('.is-invalid').length) {
                    $('#modalAddPlate button#saveAddPlate').attr('disabled', true);
                    return;
                }

                $('#modalAddPlate button.edit-item').attr('disabled', false);
                $('#modalAddPlate button.delete-item').attr('disabled', false);
                $('#modalAddPlate a.new-item').removeClass('disabled-item');
                $('#modalAddPlate button#saveAddPlate').attr('disabled', false);

                $(this).addClass('d-none');
                $(this).closest('.actions').find('button.edit-item').removeClass('d-none');

                var item = $(this).closest('.item').find('input[name="itemName"]').addClass('d-none').val();
                var price = $(this).closest('.item').find('input[name="itemPrice"]').addClass('d-none').val();

                $(this).closest('.item').find('.name div').removeClass('d-none').text(item);
                $(this).closest('.item').find('.price div').removeClass('d-none').text(price);

                $(this).closest('.complement').find('.complements').append(`
                    <input type="hidden" name="complementItem" data-name="${item}" data-price="${price}">
                `);

            }).on('click', 'button.delete-item', function() {
                if (editing) return;

                editing = false;

                $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

                $(this).closest('.item').remove();
            });
        });
    });

    $('form.add-plate input[name="promo"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('form.add-plate input[name="promoPrice"]').attr('disabled', false);
        } else {
            $('form.add-plate input[name="promoPrice"]').attr('disabled', true);
        }
    });

    // Apply money mask
    $('form.add-plate').on('focus', 'input.money', function (e) {
        $(this).maskMoney();
    });

    function validateField(field) {
        if (field.val()) {
            field.removeClass('is-invalid');
            return true;
        } else {
            field.addClass('is-invalid');
            return false;
        }
    }
});