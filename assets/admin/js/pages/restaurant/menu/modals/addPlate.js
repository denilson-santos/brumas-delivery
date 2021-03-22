$(function() {
    $('#modalAddPlate').on('show.bs.modal', function() {
        var categoryId = $('.adding').closest('.category').attr('data-category-id');
        var categoryName = $('.adding').closest('.category').attr('data-category-name');
        
        $('select#plateCategory option').val(categoryId);
        $('select#plateCategory option').text(categoryName);
        $('select#plateCategory').selectric('refresh');
    });

    $('button#saveAddPlate').on('click', function() {
        $('form.add-plate').submit();
    });

    // Custom methos to validation 
    $.validator.addMethod('filesize', function (value, element, param) {
        return !element.files[0] || (element.files[0].size <= (param * 1000000));
    }, 'File size must be less');

    // Validation
    $('form.add-plate').validate( {
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
                    return $('input[name="platePromo"]').is(':checked');
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
        ignore: 'div#pills-complement input',
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
        submitHandler: function (form) {
            console.log(form);

            var data = {};
            var row = {};

            var form = new FormData(form);

            //  Add complements in FormData
            $('.complements input').each(function (index, element) {
                data = {
                    'complementRow': $(element).attr('complement-row'),
                    'complementName': $(element).attr('complement-name'),
                    'complementRequired': $(element).attr('complement-required'),
                    'complementMultiple': $(element).attr('complement-multiple')                    
                }
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });

            //  Add itens in FormData
            $('.itens input').each(function (index, element) {
                data = {
                    'itemRow': $(element).attr('item-row'),
                    'itemComplementRow': $(element).attr('complement-row'),
                    'itemName': $(element).attr('item-name'),
                    'itemPrice': $(element).attr('item-price')
                }

                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });

            form.set('platePrice', form.get('platePrice').replace('.', '').replace(',', ''));

            if (form.get('platePromoPrice')) form.set('platePromoPrice', form.get('platePromoPrice').replace('.', '').replace(',', ''));
               
            $.ajax({
                type: 'POST',
                url: '/restaurant/menu',
                contentType : false,
                processData : false,
                data: form,
                beforeSend: function() {
                    $('form.add-plate #saveAddPlate').attr('disabled', true);
                },
                success: function (response) {
                    response = JSON.parse(response);
                    console.log(response);
                    if (!response.validate) {
                        tooltip = '<ul>';

                        var errors = response.errors;
                        // Get messages of server valiadation
                        for (var field in errors) {
                            var error = errors[field];
                            tooltip += `<li>${error[0]}</li>`;
                        }

                        tooltip += '</ul>';
                        
                        $('form.add-plate .server-validation a').attr('data-original-title', tooltip);
                        $('form.add-plate .server-validation').css('display', 'block');

                        iziToast.error({
                            title: 'Error!',
                            message: 'Ocorreu um erro ao adicionar o prato, tente novamente!',
                            position: 'topRight',
                            timeout: 2500,
                        });
                    } else {
                        $('form.add-plate .server-validation a').attr('data-original-title', '');
                        $('form.add-plate .server-validation').css('display', 'none');

                        iziToast.success({
                            title: 'Sucesso!',
                            message: 'Prato adicionado com sucesso!',
                            position: 'topRight',
                            timeout: 2000,
                        });
    
                        // Refresh page
                        setTimeout(function() {
                            location.reload();
                        }, 2100);
                    }

                },
                complete: function() {
                    $('form.add-plate #saveAddPlate').attr('disabled', false);
                }
            });

            return false;
        }
    } );    

    // Validation input on change
    // $().on('change', );


    // Validation selectric on change
    $('form.add-plate select').on('change', function(e) {
        if ($(this).valid()) {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
        } else {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
        }
    });

    // Show plate preview
    $('.plate-img-area').on('click', function() {
        $('#plateImage').click();
    });

    $("#plateImage").on('change', function() {
        if ($(this).valid()) platePreview(this);
    });

    $('.img-overlay span').on('click', function() {
        $('.plate-img-area').css('display', 'block');
        $('.plate-img-preview').css('display', 'none');
        $('#plateImage').val('');
    });

    var count = 0;
    var countItem = 0;

    $('form.add-plate button#addComplement').on('click', function() {
        count++;
        
        $('.no-complement').css('display', 'none');

        $('#modalAddPlate button#addComplement').attr('disabled', true);
        $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

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

        `).appendTo('.complement-container').on('click', 'a.edit-complement', function() {
            $('#modalAddPlate button#addComplement').attr   ('disabled', true);
            $('#modalAddPlate button#saveAddPlate').attr('disabled', true);

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
            
            if ($('.editing').length) {
                $('#modalAddPlate button#addComplement').attr   ('disabled', true);
            } else {
                if ($(this).closest('.complement').find('.item').length) {
                    $('#modalAddPlate button#addComplement').attr('disabled', false);
                    $('#modalAddPlate button#saveAddPlate').attr('disabled', false);
                } else {
                    $('#modalAddPlate button#addComplement').attr('disabled', true);
                    $('#modalAddPlate button#saveAddPlate').attr('disabled', true);
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
            var currentComplement = $(`.complements input[complement-row="${currentComplementRow}"]`);

            if (currentComplement.length) {
                currentComplement.attr('complement-name', complementName);
                currentComplement.attr('complement-required', complementRequired);
                currentComplement.attr('complement-multiple', complementMultiple);

            } else {
                $('.complements').append(`
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
                    currentItem.attr('item-price', $(element).find('input[name="itemPrice"]').val());

                } else {
                    $('.itens').append(`
                    <input 
                        type="hidden" 
                        item-row="${currentItemRow}" 
                        complement-row="${currentComplementRow}" 
                        item-name="${$(element).find('input[name="itemName"]').val()}" 
                        item-price="${$(element).find('input[name="itemPrice"]').val()}" 
                    /> 
                    `);
                }
            });

            $(this).addClass('d-none');
            $(this).closest('.actions').find('a.edit-complement').removeClass('d-none');
            $(this).closest('.complement').find('.content .actions').addClass('d-none');

        }).on('click', 'a.delete-complement', function() {
            $(this).closest('.complement').remove();
            
            if (!$('.complement').length) $('.no-complement').css('display', 'block');

            var currentComplementRow = $(this).closest('.complement').attr('complement-row');

            var currentComplement = $(`.complements input[complement-row="${currentComplementRow}"]`);
            
            $('.complements-deleted').append(currentComplement);
            
            $(`.complements input[complement-row="${currentComplementRow}"]`).remove();            
            
            $(`.itens input[complement-row="${currentComplementRow}"]`).remove();            

            if ($('.editing').length) {
                $('#modalAddPlate button#addComplement').attr   ('disabled', true);
                $('#modalAddPlate button#saveAddPlate').attr   ('disabled', true);
            } else {
                $('#modalAddPlate button#addComplement').attr('disabled', false);
                $('#modalAddPlate button#saveAddPlate').attr('disabled', false);
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

                var currentItem = $(`.itens input[item-row="${currentItemRow}"]`);

                $('.itens-deleted').append(currentItem);
                
                $(`.itens input[item-row="${currentItemRow}"]`).remove();            
            });
        });
    });

    $('form.add-plate input[name="platePromo"]').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).val('1');
            $('form.add-plate input[name="platePromoPrice"]').attr('disabled', false);
        } else {
            $(this).val('0');
            $('form.add-plate input[name="platePromoPrice"]').attr('disabled', true);
            $('form.add-plate input[name="platePromoPrice"]').val('');
            $('form.add-plate input[name="platePromoPrice"]').removeClass('is-valid').removeClass('is-invalid');
        }
    });

    // Apply money mask
    $('form.add-plate').on('focus', 'input.money', function (e) {
        $(this).maskMoney();
    });

    $('#modalAddPlate').on('hidden.bs.modal', function() {
        $('.category a.add-plate').removeClass('adding');
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
            $('.plate-img-preview img').attr('src', e.target.result);
            $('.plate-img-area').css('display', 'none');
            $('.plate-img-preview').css('display', 'block');
          }

          reader.onloadend = function() {
            $('.plate-img-preview').css('pointerEvents', 'none');

            setTimeout(function() {
                $('.plate-img-preview').css('pointerEvents', 'auto');
            }, 100)
          }

          reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    
});