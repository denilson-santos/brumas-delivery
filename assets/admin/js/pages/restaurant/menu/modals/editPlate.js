$(function() {
    var count = 0;
    var countItem = 0;

    $('#modalEditPlate').on('shown.bs.modal', function() {
        $.ajax({
            type: 'GET',
            url: `/plate/${$('.menu-container .editing').attr('plate-id')}`,
            dataType: 'json',
            beforeSend: function() {
                $('#modalEditPlate .spinner-container').removeClass('d-none');
                $('#modalEditPlate #saveEditPlate').attr('disabled', true);
            },
            success: function ({ plate }) {
                console.log(plate);

                $('form.edit-plate .edit-plate-img-preview img').attr('src', plate.image ?? '/media/categories/others/others-plates-img-default.jpg');
                $('form.edit-plate .edit-plate-img-preview').css('display', 'block');
                $('form.edit-plate .edit-plate-img-area').css('display', 'none');

                $('form.edit-plate input[name="plateName"]').val(plate.name);
                $('form.edit-plate input[name="plateName"]').val(plate.name);

                var categoryId = $('.menu-container .editing').closest('.category').attr('data-category-id');
                var categoryName = $('.menu-container .editing').closest('.category').attr('data-category-name');
                
                $('select#editPlateCategory option').val(categoryId);
                $('select#editPlateCategory option').text(categoryName);
                $('select#editPlateCategory').selectric('refresh');

                $('form.edit-plate textarea[name="plateDescription"]').text(plate.description);
                $('form.edit-plate input[name="platePrice"]').val(formatter.format(plate.price/100));
                
                if (plate.promo) {
                    $('form.edit-plate input[name="platePromo"]').prop('checked', true);
                    $('form.edit-plate input[name="platePromoPrice"]').attr('disabled', false);
                    $('form.edit-plate input[name="platePromoPrice"]').val(formatter.format(plate.promo_price/100));
                } else {
                    $('form.edit-plate input[name="platePromo"]').prop('checked', false);
                    $('form.edit-plate input[name="platePromoPrice"]').attr('disabled', true);
                }

                // $('form.edit-plate input[name="platePromo"]').change();

                count = plate.complements.length ? plate.complements[plate.complements.length-1].id_complement : count;
                countItem = 0;

                var items = '';

                // $('form.edit-plate button#editAddComplement').on('click', function() {
                
                $('#modalEditPlate .complement').remove();

                if (!plate.complements.length) $('#modalEditPlate .no-complement').css('display', 'block');

                plate.complements.forEach((complement, cindex) => {
                    items = '';
                    
                    $('#modalEditPlate .no-complement').css('display', 'none');
            
                    $('#modalEditPlate button#editAddComplement').attr('disabled', false);

                    // Complements saved
                    $('#modalEditPlate .complements').append(`
                        <input 
                            type="hidden"
                            class="saved"
                            complement-id="${complement.id_complement}"
                            complement-row="${complement.id_complement}" 
                            complement-name="${complement.name}" 
                            complement-required="${complement.required}" 
                            complement-multiple="${complement.multiple}" 
                        /> 
                    `);

                    if (complement.itens) {
                        // Complement itens of plate
                        complement.itens.forEach((item, iindex) => {
                            countItem = item.id_item > countItem ? item.id_item : countItem;
    
                            $('#modalEditPlate .itens').append(`
                                <input 
                                    type="hidden"
                                    class="saved"
                                    item-id="${item.id_item}"
                                    item-row="${item.id_item}"
                                    complement-id="${complement.id_complement}"
                                    complement-row="${complement.id_complement}"
                                    item-name="${item.name}" 
                                    item-price="${item.price}" 
                                /> 
                            `);
    
                            items += `
                                <div class="item" item-row="${item.id_item}">
                                    <div class="name col pl-0">
                                        <input type="text" name="itemName" class="form-control d-none" placeholder="Nome do item">
                                        <div class="item-name">${item.name}</div>
                                    </div>
                                        
                                    <div class="price col">
                                        <input type="text" name="itemPrice" class="form-control col-3 money d-none" placeholder="Preço" data-thousands="." data-decimal=",">
                                        <div class="item-price">${formatter.format(item.price/100)}</div>
                                    </div>
                
                                    <div class="actions d-none">
                                        <button type="button" class="btn btn-primary btn-sm delete-item action"><i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                    }
            
                    $(`
                        <div class="complement mt-4" complement-row="${complement.id_complement}">
                            <div class="header">
                                <div class="name m-auto col px-0">
                                    <input type="text" name="complementName" class="form-control d-none" placeholder="Adicione um complemento">
                                    <h5 class="mb-0">${complement.name}</h5>
                                </div>
            
                                <div class="d-flex ml-3 mr-4">
                                    <div class="complement-required-container custom-control custom-checkbox mr-3 d-none">
                                        <input type="checkbox" id="complementRequired${complement.id_complement}" name="complementRequired"  class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="complementRequired${complement.id_complement}">
                                            Obrigatório
                                        </label>
                                    </div>

                                    ${complement.required ? '<div class="complement-required"><i class="fab fa-diaspora mr-1"></i>Obrigatório</div>' : ''}
            
                                    <div class="complement-multiple-container custom-control custom-checkbox d-none">
                                        <input type="checkbox" id="complementMultiple${complement.id_complement}" name="complementMultiple"  class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="complementMultiple${complement.id_complement}">
                                            Múltiplo
                                        </label>
                                    </div>
                                    
                                    ${complement.multiple ? '<div class="complement-multiple ml-3"><i class="fas fa-check-double mr-1"></i>Múltiplo</div>' : ''}
                                </div>
            
                                <div class="actions m-auto text-right"> 
                                    <a href="javascript:void(0)" class="edit-complement"><i class="fas fa-pencil-alt mr-1"></i></a>
                                    <a href="javascript:void(0)" class="save-complement d-none"><i class="fas fa-check mr-2"></i></a>
                                    <a href="javascript:void(0)" class="delete-complement"><i class="fas fa-trash-alt mr-1"></i></a>
                                </div>
                            </div>
            
                            <div class="content">
                                ${items}
                            </div>
            
                            <div class="footer">
                                <a href="javascript:void(0)" class="new-item disabled-item"><i class="fas fa-plus ml-0 mr-1"></i>Adicionar Item</a>
                            </div>
            
                    `).appendTo('#modalEditPlate .complement-container').on('click', 'a.edit-complement', function() {
                        $('#modalEditPlate button#addComplement').attr('disabled', true);
                        $('#modalEditPlate button#saveAddPlate').attr('disabled', true);
            
                        $(this).closest('.complement').addClass('editing');
                        $(this).closest('.complement').find('a.new-item').removeClass('disabled-item');
            
                        $(this).addClass('d-none');
                        $(this).closest('.actions').find('a.save-complement').removeClass('d-none');
                        $(this).closest('.complement').find('.content .actions').removeClass('d-none');
            
                        var complementName = '';
                        var itemName = '';
                        var itemPrice = '';
            
                        complementName = $(this).closest('.header').find('h5').addClass('d-none').text();
                    
                        $(this).closest('.header').find('input[name="complementName"]').removeClass('d-none').val(complementName);
            
                        $(this).closest('.header').find('div.complement-required').addClass('d-none');
                        
                        $(this).closest('.header').find('div.complement-multiple').addClass('d-none');
                    
                        if (complement.required) {
                            $(this).closest('.header').find('div.complement-required-container input').prop('checked', true);
                        }

                        $(this).closest('.header').find('div.complement-required-container').removeClass('d-none');

                        if (complement.multiple) {
                            $(this).closest('.header').find('div.complement-multiple-container input').prop('checked', true);
                        }

                        $(this).closest('.header').find('div.complement-multiple-container').removeClass('d-none');
                        
                        $(this).closest('.complement').find('div.item-name').each(function (index, element) {
                            itemName = $(element).addClass('d-none').text();
            
                            $(element).closest('.name').find('input[name="itemName"]').removeClass('d-none').val(itemName);
                        });   
            
                        $(this).closest('.complement').find('div.item-price').each(function (index, element) {
                            itemPrice = $(element).addClass('d-none').text();
            
                            $(element).closest('.price').find('input[name="itemPrice"]').removeClass('d-none').val(itemPrice);                
                        });   
            
                    }).on('click', 'a.save-complement', function() {
                        // Validation complement
                        var validation = true;
            
                        $(this).closest('.complement').find('input[name="complementName"], input[name="itemName"], input[name="itemPrice"]').each(function (index, element) {
                            validation = validateField($(element)) && validation;
                        });
            
                        if (!validation) return;
            
                        $(this).closest('.complement').removeClass('editing');
                        $(this).closest('.complement').find('.new-item').addClass('disabled-item');
                        
                        if ($('form.edit-plate .editing').length) {
                            $('#modalEditPlate button#addComplement').attr('disabled', true);
                        } else {
                            if ($(this).closest('.complement').find('.item').length) {
                                $('#modalEditPlate button#addComplement').attr('disabled', false);
                                $('#modalEditPlate button#saveAddPlate').attr('disabled', false);
                            } else {
                                $('#modalEditPlate button#addComplement').attr('disabled', true);
                                $('#modalEditPlate button#saveAddPlate').attr('disabled', true);
                                $(this).closest('.complement').find('.new-item').removeClass('disabled-item');
                                return;
                            }
                        }     
            
                        var complementName = '';
                        var complementRequired = '';
                        var complementMultiple = '';
                        var itemName = '';
                        var itemPrice = '';
            
                        complementName = $(this).closest('.header').find('input[name="complementName"]').addClass('d-none').val();
                    
                        $(this).closest('.header').find('h5').removeClass('d-none').text(complementName);
            
                        $(this).closest('.header').find('div.complement-required-container').addClass('d-none');
                    
                        complementRequired = $(this).closest('.header').find('input[name="complementRequired"]').is(':checked') ? 1 : 0;
            
                        if (complementRequired) {
                            $(this).closest('.header').find('div.complement-required').removeClass('d-none');
                        } else {
                            $(this).closest('.header').find('div.complement-required').addClass('d-none');
                        }
            
                        $(this).closest('.header').find('div.complement-multiple-container').addClass('d-none');
                    
                        complementMultiple = $(this).closest('.header').find('input[name="complementMultiple"]').is(':checked') ? 1 : 0;
            
                        if (complementMultiple) {
                            $(this).closest('.header').find('div.complement-multiple').removeClass('d-none');
                        } else {
                            $(this).closest('.header').find('div.complement-multiple').addClass('d-none');
                        }
                        
                        $(this).closest('.complement').find('input[name="itemName"]').each(function (index, element) {
                            itemName = $(element).addClass('d-none').val();
            
                            $(element).closest('.name').find('div.item-name').removeClass('d-none').text(itemName);
                        });   
            
                        $(this).closest('.complement').find('input[name="itemPrice"]').each(function (index, element) {
                            itemPrice = $(element).addClass('d-none').val();
            
                            $(element).closest('.price').find('div.item-price').removeClass('d-none').text(itemPrice);                
                        });
            
                        var currentComplementRow = $(this).closest('.complement').attr('complement-row');
                        var currentComplement = $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`);
            
                        if (currentComplement.length) {
                            currentComplement.removeClass('saved');
                            currentComplement.attr('complement-name', complementName);
                            currentComplement.attr('complement-required', complementRequired);
                            currentComplement.attr('complement-multiple', complementMultiple);
            
                        } else {
                            $('#modalEditPlate .complements').append(`
                                <input 
                                    type="hidden"  
                                    complement-row="${count}" 
                                    complement-name="${complementName}" 
                                    complement-required="${complementRequired}" 
                                    complement-multiple="${complementMultiple}" 
                                /> 
                            `);
                        }
            
                        var currentItem = {};
                        var currentItemRow = 0;
            
                        $(this).closest('.complement').find('.item').each(function (index, element) {
                            currentItemRow = $(element).attr('item-row');
                            currentItem = $(`#modalEditPlate .itens input[item-row="${currentItemRow}"]`);
            
                            if (currentItem.length) {
                                currentItem.removeClass('saved');
                                currentItem.attr('item-name', $(element).find('input[name="itemName"]').val());
                                currentItem.attr('item-price', $(element).find('input[name="itemPrice"]').val().replace('.', '').replace(',', ''));
            
                            } else {
                                $('#modalEditPlate .itens').append(`
                                <input 
                                    type="hidden" 
                                    item-row="${currentItemRow}" 
                                    complement-row="${currentComplementRow}" 
                                    item-name="${$(element).find('input[name="itemName"]').val()}" 
                                    item-price="${$(element).find('input[name="itemPrice"]').val().replace('.', '').replace(',', '')}" 
                                /> 
                                `);
                            }
                        });
            
                        $(this).addClass('d-none');
                        $(this).closest('.actions').find('a.edit-complement').removeClass('d-none');
                        $(this).closest('.complement').find('.content .actions').addClass('d-none');

                        if (!$('form.edit-plate .is-invalid').length) {
                            $('#modalEditPlate button#saveEditPlate').attr('disabled', false);
                        }
            
                    }).on('click', 'a.delete-complement', function() {
                        console.log('Deleting');
                        $(this).closest('.complement').remove();
                        
                        if (!$('#modalEditPlate .complement').length) $('#modalEditPlate .no-complement').css('display', 'block');
            
                        var currentComplementRow = $(this).closest('.complement').attr('complement-row');
            
                        var currentComplement = $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`);
                        
                        $('#modalEditPlate .complements-deleted').append(currentComplement);
                        
                        $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`).remove();            
                        
                        $(`#modalEditPlate .itens input[complement-row="${currentComplementRow}"]`).remove();            
            
                        if ($('form.edit-plate .editing').length) {
                            $('#modalEditPlate button#editAddComplement').attr('disabled', true);
                            $('#modalEditPlate button#saveEditPlate').attr('disabled', true);
                        } else {
                            $('#modalEditPlate button#editAddComplement').attr('disabled', false);
                            $('#modalEditPlate button#saveEditPlate').attr('disabled', false);
                        }    
            
                    }).on('click', 'a.new-item', function() {  
                        countItem++;            
            
                        $(`
                            <div class="item" item-row="${countItem}">
                                <div class="name col pl-0">
                                    <input type="text" name="itemName" class="form-control" placeholder="Nome do item">
                                    <div class="item-name d-none"></div>
                                </div>
                                    
                                <div class="price col">
                                    <input type="text" name="itemPrice" class="form-control col-3 money" placeholder="Preço" data-thousands="." data-decimal=",">
                                    <div class="item-price d-none"></div>
                                </div>
            
                                <div class="actions">
                                    <button type="button" class="btn btn-primary btn-sm delete-item action"><i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        `).appendTo($(this).closest('.complement').find('.content'));
                    }); 
                });
            },
            complete: function() {
                $('#modalEditPlate .spinner-container').addClass('d-none');
            }
        });
    });

    $('#modalEditPlate').on('click', 'button.delete-item', function() {
        $(this).closest('.item').remove();

        var currentItemRow = $(this).closest('.item').attr('item-row');

        var currentItem = $(`#modalEditPlate .itens input[item-row="${currentItemRow}"]`);

        $('#modalEditPlate .itens-deleted').append(currentItem);
        
        $(`#modalEditPlate .itens input[item-row="${currentItemRow}"]`).remove();        
    });

    $('button#saveEditPlate').on('click', function() {
        $('form.edit-plate').submit();
    });

    // Custom methos to validation 
    $.validator.addMethod('filesize', function (value, element, param) {
        return !element.files[0] || (element.files[0].size <= (param * 1000000));
    }, 'File size must be less');

    // Validation
    $('form.edit-plate').validate( {
        onfocusout: false,
        onkeyup: false,
        onsubmit: false,
        rules: {
            // Informations
            plateImage: {
                required: false,
                accept: 'jpg,jpeg,png',
                // Value in mb
                filesize: 30
            },
            plateName: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            plateCategory: {
                required: true,
                digits: true
            },
            plateDescription: {
                required: false,
                maxlength: 100
            },
            platePrice: {
                required: true
            },

            // Promotion
            platePromoPrice: {
                required: function(element) {
                    return $('form.edit-plate input[name="platePromo"]').is(':checked');
                }
            }
        },
        messages: {
            // Information
            plateImage: {
                accept: 'Formato inválido, use: (.jpg, .jpeg ou .png)',
                filesize: 'O limite para upload é de 30mb'
            },
            plateName: {
                required: 'Digite o nome do prato',
                minlength: 'O nome do prato precisa ter no mínimo 2 caracteres',
                maxlength: 'O nome do prato precisa ter no máximo 50 caracteres'
            },
            plateCategory: { 
                required: 'Informe a categoria do prato',
                number: 'Informe uma categoria válida'
            },
            plateDescription: {
                maxlength: 'A descrição do prato precisa ter no máximo 100 caracteres'
            },
            platePrice: {
                required: 'Digite o preço do prato'
            },

            // Promotion
            platePromoPrice: {
                required: 'Digite o preço promocional do prato'
            }
        },
        ignore: 'div#pills-edit-complement input',
        errorElement: 'div',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass('invalid-feedback');

            if ( element.prop('type') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('type') === 'file') {
                $('.plate-img-area').css('borderColor', '#fa2724');  
            } else {
                error.insertAfter( element );
            }   
        },
        highlight: function (element, errorClass, validClass) {
            if ($(element).hasClass('selectric')) {
                $(element).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa3734');
            } else {
                $(element).addClass('is-invalid').removeClass('is-valid');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if ($(element).hasClass('selectric')) {
                $(element).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
            } else {
                $(element).addClass('is-valid').removeClass('is-invalid');
            }
        }, 
    } );    

    // Validation inputs on change
    $('form.edit-plate #pills-edit-info input, form.edit-plate #pills-edit-promo input, form.edit-plate textarea').on('change', function () {
        if ($(this).valid()) {
            $(this).addClass('changed');
        } else {
            $(this).removeClass('changed');
        }

        if ($('form.edit-plate').find('.changed').length && !$('form.edit-plate').find('.is-invalid').length) {
            $('button#saveEditPlate').attr('disabled', false);
            
        } else {
            $('button#saveEditPlate').attr('disabled', true);
        }
    });

    // Show plate preview
    $('form.edit-plate .edit-plate-img-area').on('click', function() {
        $('form.edit-plate #editPlateImage').click();
    });

    $("form.edit-plate #editPlateImage").on('change', function() {
        if ($(this).valid()) platePreview(this);
    });

    $('form.edit-plate .edit-img-overlay span').on('click', function() {
        $('form.edit-plate .edit-plate-img-area').css('display', 'block');
        $('form.edit-plate .edit-plate-img-preview').css('display', 'none');
        $('form.edit-plate #editPlateImage').val('');
    });

    $('form.edit-plate button#editAddComplement').on('click', function() {
        count++;
        
        $('#modalEditPlate .no-complement').css('display', 'none');

        $('#modalEditPlate button#editAddComplement').attr('disabled', true);
        $('#modalEditPlate button#saveEditPlate').attr('disabled', true);

        $(`
            <div class="complement editing mt-4" complement-row="${count}">
                <div class="header">
                    <div class="name m-auto col px-0">
                        <input type="text" name="complementName" class="form-control" placeholder="Adicione um complemento">
                        <h5 class="mb-0 d-none"></h5>
                    </div>

                    <div class="d-flex ml-3 mr-4">
                        <div class="complement-required-container custom-control custom-checkbox mr-3">
                            <input type="checkbox" id="complementRequired${count}" name="complementRequired"  class="custom-control-input" value="1">
                            <label class="custom-control-label" for="complementRequired${count}">
                                Obrigatório
                            </label>
                        </div>
                        <div class="complement-required d-none"><i class="fab fa-diaspora mr-1"></i>Obrigatório</div>

                        <div class="complement-multiple-container custom-control custom-checkbox">
                            <input type="checkbox" id="complementMultiple${count}" name="complementMultiple"  class="custom-control-input" value="1">
                            <label class="custom-control-label" for="complementMultiple${count}">
                                Múltiplo
                            </label>
                        </div>
                        <div class="complement-multiple ml-3 d-none"><i class="fas fa-check-double mr-1"></i>Múltiplo</div>
                    </div>

                    <div class="actions m-auto text-right"> 
                        <a href="javascript:void(0)" class="edit-complement d-none"><i class="fas fa-pencil-alt mr-1"></i></a>
                        <a href="javascript:void(0)" class="save-complement"><i class="fas fa-check mr-2"></i></a>
                        <a href="javascript:void(0)" class="delete-complement"><i class="fas fa-trash-alt mr-1"></i></a>
                    </div>
                </div>

                <div class="content"></div>

                <div class="footer">
                    <a href="javascript:void(0)" class="new-item"><i class="fas fa-plus ml-0 mr-1"></i>Adicionar Item</a>
                </div>

        `).appendTo('#modalEditPlate .complement-container').on('click', 'a.edit-complement', function() {
            $('#modalEditPlate button#addComplement').attr   ('disabled', true);
            $('#modalEditPlate button#saveAddPlate').attr('disabled', true);

            $(this).closest('.complement').addClass('editing');
            $(this).closest('.complement').find('a.new-item').removeClass('disabled-item');

            $(this).addClass('d-none');
            $(this).closest('.actions').find('a.save-complement').removeClass('d-none');
            $(this).closest('.complement').find('.content .actions').removeClass('d-none');

            var complementName = '';
            var itemName = '';
            var itemPrice = '';

            complementName = $(this).closest('.header').find('h5').addClass('d-none').text();
        
            $(this).closest('.header').find('input[name="complementName"]').removeClass('d-none').val(complementName);

            $(this).closest('.header').find('div.complement-required').addClass('d-none');
            $(this).closest('.header').find('div.complement-multiple').addClass('d-none');
        
            $(this).closest('.header').find('div.complement-required-container').removeClass('d-none');
            $(this).closest('.header').find('div.complement-multiple-container').removeClass('d-none');
            
            $(this).closest('.complement').find('div.item-name').each(function (index, element) {
                itemName = $(element).addClass('d-none').text();

                $(element).closest('.name').find('input[name="itemName"]').removeClass('d-none').val(itemName);
            });   

            $(this).closest('.complement').find('div.item-price').each(function (index, element) {
                itemPrice = $(element).addClass('d-none').text();

                $(element).closest('.price').find('input[name="itemPrice"]').removeClass('d-none').val(itemPrice);                
            });   

        }).on('click', 'a.save-complement', function() {
            // Validation complement
            var validation = true;

            $(this).closest('.complement').find('input[name="complementName"], input[name="itemName"], input[name="itemPrice"]').each(function (index, element) {
                validation = validateField($(element)) && validation;
            });

            if (!validation) return;

            $(this).closest('.complement').removeClass('editing');
            $(this).closest('.complement').find('.new-item').addClass('disabled-item');
            
            if ($('form.edit-plate .editing').length) {
                $('#modalEditPlate button#addComplement').attr('disabled', true);
            } else {
                if ($(this).closest('.complement').find('.item').length) {
                    $('#modalEditPlate button#addComplement').attr('disabled', false);
                    $('#modalEditPlate button#saveAddPlate').attr('disabled', false);
                } else {
                    $('#modalEditPlate button#addComplement').attr('disabled', true);
                    $('#modalEditPlate button#saveAddPlate').attr('disabled', true);
                    $(this).closest('.complement').find('.new-item').removeClass('disabled-item');
                    return;
                }
            }     

            var complementName = '';
            var complementRequired = '';
            var complementMultiple = '';
            var itemName = '';
            var itemPrice = '';

            complementName = $(this).closest('.header').find('input[name="complementName"]').addClass('d-none').val();
        
            $(this).closest('.header').find('h5').removeClass('d-none').text(complementName);

            $(this).closest('.header').find('div.complement-required-container').addClass('d-none');
        
            complementRequired = $(this).closest('.header').find('input[name="complementRequired"]').is(':checked') ? 1 : 0;

            if (complementRequired) {
                $(this).closest('.header').find('div.complement-required').removeClass('d-none');
            } else {
                $(this).closest('.header').find('div.complement-required').addClass('d-none');
            }

            $(this).closest('.header').find('div.complement-multiple-container').addClass('d-none');
        
            complementMultiple = $(this).closest('.header').find('input[name="complementMultiple"]').is(':checked') ? 1 : 0;

            if (complementMultiple) {
                $(this).closest('.header').find('div.complement-multiple').removeClass('d-none');
            } else {
                $(this).closest('.header').find('div.complement-multiple').addClass('d-none');
            }
            
            $(this).closest('.complement').find('input[name="itemName"]').each(function (index, element) {
                itemName = $(element).addClass('d-none').val();

                $(element).closest('.name').find('div.item-name').removeClass('d-none').text(itemName);
            });   

            $(this).closest('.complement').find('input[name="itemPrice"]').each(function (index, element) {
                itemPrice = $(element).addClass('d-none').val();

                $(element).closest('.price').find('div.item-price').removeClass('d-none').text(itemPrice);                
            });

            var currentComplementRow = $(this).closest('.complement').attr('complement-row');
            var currentComplement = $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`);

            if (currentComplement.length) {
                currentComplement.attr('complement-name', complementName);
                currentComplement.attr('complement-required', complementRequired);
                currentComplement.attr('complement-multiple', complementMultiple);

            } else {
                $('#modalEditPlate .complements').append(`
                    <input 
                        type="hidden" 
                        complement-row="${count}" 
                        complement-name="${complementName}" 
                        complement-required="${complementRequired}" 
                        complement-multiple="${complementMultiple}" 
                    /> 
                `);
            }

            var currentItem = {};
            var currentItemRow = 0;

            $(this).closest('.complement').find('.item').each(function (index, element) {
                currentItemRow = $(element).attr('item-row');
                currentItem = $(`.itens input[item-row="${currentItemRow}"]`);

                if (currentItem.length) {
                    currentItem.attr('item-name', $(element).find('input[name="itemName"]').val());
                    currentItem.attr('item-price', $(element).find('input[name="itemPrice"]').val().replace('.', '').replace(',', ''));

                } else {
                    $('#modalEditPlate .itens').append(`
                    <input 
                        type="hidden" 
                        item-row="${currentItemRow}" 
                        complement-row="${currentComplementRow}" 
                        item-name="${$(element).find('input[name="itemName"]').val()}" 
                        item-price="${$(element).find('input[name="itemPrice"]').val().replace('.', '').replace(',', '')}" 
                    /> 
                    `);
                }
            });

            $(this).addClass('d-none');
            $(this).closest('.actions').find('a.edit-complement').removeClass('d-none');
            $(this).closest('.complement').find('.content .actions').addClass('d-none');

            if (!$('form.edit-plate .is-invalid').length) {
                $('#modalEditPlate button#saveEditPlate').attr('disabled', false);
            }

        }).on('click', 'a.delete-complement', function() {
            $(this).closest('.complement').remove();
            
            if (!$('#modalEditPlate .complement').length) $('#modalEditPlate .no-complement').css('display', 'block');

            var currentComplementRow = $(this).closest('.complement').attr('complement-row');

            var currentComplement = $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`);
            
            $('#modalEditPlate .complements-deleted').append(currentComplement);
            
            $(`#modalEditPlate .complements input[complement-row="${currentComplementRow}"]`).remove();            
            
            $(`#modalEditPlate .itens input[complement-row="${currentComplementRow}"]`).remove();            

            if ($('form.edit-plate .editing').length) {
                $('#modalEditPlate button#editAddComplement').attr   ('disabled', true);
                $('#modalEditPlate button#saveEditPlate').attr   ('disabled', true);
            } else {
                $('#modalEditPlate button#editAddComplement').attr('disabled', false);
                $('#modalEditPlate button#saveEditPlate').attr('disabled', false);
            }    

        }).on('click', 'a.new-item', function() {  
            countItem++;            

            $(`
                <div class="item" item-row="${countItem}">
                    <div class="name col pl-0">
                        <input type="text" name="itemName" class="form-control" placeholder="Nome do item">
                        <div class="item-name d-none"></div>
                    </div>
                        
                    <div class="price col">
                        <input type="text" name="itemPrice" class="form-control col-3 money" placeholder="Preço" data-thousands="." data-decimal=",">
                        <div class="item-price d-none"></div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-primary btn-sm delete-item action"><i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `).appendTo($(this).closest('.complement').find('.content')).on('click', 'button.delete-item', function() {
                $(this).closest('.item').remove();

                var currentItemRow = $(this).closest('.item').attr('item-row');

                var currentItem = $(`#modalEditPlate .itens input[item-row="${currentItemRow}"]`);

                $('#modalEditPlate .itens-deleted').append(currentItem);
                
                $(`#modalEditPlate .itens input[item-row="${currentItemRow}"]`).remove();            
            });
        });
    });

    $('form.edit-plate input[name="platePromo"]').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).val('1');
            $('form.edit-plate input[name="platePromoPrice"]').prop('disabled', false);
        } else {
            $(this).val('0');
            $('form.edit-plate input[name="platePromoPrice"]').prop('disabled', true);
            $('form.edit-plate input[name="platePromoPrice"]').val('');
            $('form.edit-plate input[name="platePromoPrice"]').removeClass('is-valid').removeClass('is-invalid');
        }
    });

    // Apply money mask
    $('form.edit-plate').on('focus', 'input.money', function (e) {
        $(this).maskMoney();
    });

    $('#modalEditPlate').on('hidden.bs.modal', function() {
        $('.category button.edit').removeClass('editing');
    });

    $('form.edit-plate').on('submit', function (e) {
        e.preventDefault();
        
        var fieldsChanged = $(this).find('.changed');
        var fieldsChangedComplements = $('.complements input:not(.saved)');
        var fieldsDeletedComplements = $('.complements-deleted input');
        var fieldsChangedItems = $('.itens input:not(.saved)');
        var fieldsDeletedItems = $('.itens-deleted input');

        var data = {};
        var row = {};

        var form = new FormData();
        var field = '';

        form.append('userId', $('form.edit-plate input[name="userId"]').val());
        form.append('categoryId', $('.menu-container .editing').closest('.category').attr('data-category-id'));
        form.append('plateId', $('.menu-container .editing').attr('plate-id'));


        if (fieldsChanged.length) {
            // Add fields changed in FormData
            fieldsChanged.each(function (index, element) {
                field = $(element).attr('name');
    
                if (field == 'plateImage') {
                    form.append(field, $(element)[0].files[0] ?? '');
                } else if (field == 'platePrice' || field == 'platePromoPrice') {
                    form.append(field, $(element).val().replace('.', '').replace(',', ''));
                } else {
                    form.append(field, $(element).val());
                }
            });            
        }

        if (fieldsChangedComplements.length) {
            fieldsChangedComplements.each(function (index, element) {
                data = {
                    'idComplement': $(element).attr('complement-id') ?? '',
                    'complementRow': $(element).attr('complement-row'),
                    'complementName': $(element).attr('complement-name'),
                    'complementRequired': $(element).attr('complement-required'),
                    'complementMultiple': $(element).attr('complement-multiple')                    
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsDeletedComplements.length) {
            fieldsDeletedComplements.each(function (index, element) {
                data = {
                    'idComplementDeleted': $(element).attr('complement-id'),
                    'complementRowDeleted': $(element).attr('complement-row'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsChangedItems.length) {
            fieldsChangedItems.each(function (index, element) {
                data = {
                    'idItem': $(element).attr('item-id') ?? '',
                    'itemRow': $(element).attr('item-row'),
                    'itemComplementId': $(element).attr('complement-id'),
                    'itemComplementRow': $(element).attr('complement-row'),
                    'itemName': $(element).attr('item-name'),
                    'itemPrice': $(element).attr('item-price')
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsDeletedItems.length) {
            fieldsDeletedItems.each(function (index, element) {
                data = {
                    'idItemDeleted': $(element).attr('item-id'),
                    'itemRowDeleted': $(element).attr('item-row'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (!fieldsChanged.length && !fieldsChangedComplements.length && !fieldsDeletedComplements.length && !fieldsChangedItems.length && !fieldsDeletedItems.length) return;

        $.ajax({
            type: "POST",
            url: "/plate/edit",
            contentType : false,
            processData : false,
            data: form,
            beforeSend: function() {
                $('#modalEditPlate button#saveEditPlate').attr('disabled', true);
            },
            success: function (response) {
                response = JSON.parse(response);
                console.log(response);
                
                if (!response.validate) {
                    $('#modalEditPlate #saveEditPlate').attr('disabled', false);

                    tooltip = '<ul>';
    
                    var errors = response.errors;
                    // Get messages of server valiadation
                    for (var field in errors) {
                        var error = errors[field];
                        tooltip += `<li>${error[0]}</li>`;
                    }
    
                    tooltip += '</ul>';
                    
                    $('form.edit-plate .server-validation a').attr('data-original-title', tooltip);
                    $('form.edit-plate .server-validation').css('display', 'block');

                    iziToast.error({
                        title: 'Error!',
                        message: 'Ocorreu um erro ao atualizar as informações, tente novamente!',
                        position: 'topRight',
                        timeout: 2500,
                    });
                } else {
                    $('form.edit-plate .server-validation a').attr('data-original-title', '');
                    $('form.edit_plate .server-validation').css('display', 'none');
    
                    iziToast.success({
                        title: 'Sucesso!',
                        message: 'Informações atualizadas com sucesso!',
                        position: 'topRight',
                        timeout: 2000,
                    });

                    // Refresh page
                    setTimeout(function() {
                        location.reload();
                    }, 2100);
                }
            }
        });

        return false;
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

    function platePreview(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
            $('form.edit-plate .edit-plate-img-preview img').attr('src', e.target.result);
            $('form.edit-plate .edit-plate-img-area').css('display', 'none');
            $('form.edit-plate .edit-plate-img-preview').css('display', 'block');
          }

          reader.onloadend = function() {
            $('form.edit-plate .edit-plate-img-preview').css('pointerEvents', 'none');

            setTimeout(function() {
                $('form.edit-plate .edit-plate-img-preview').css('pointerEvents', 'auto');
            }, 100)
          }

          reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    
});