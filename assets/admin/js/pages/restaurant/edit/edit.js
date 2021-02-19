$(function () {
    // Adapts the select multiple of the selectric to accept only 2 checked options
    var mainCategories = $('.restaurant-main-categories').selectric();
    
    mainCategories.on('selectric-before-change', function(event, element, selectric) {                
        if (selectric.state.selectedIdx.length == 3) {
            $(`.selectric-restaurant-main-categories ul li:nth-child(${selectric.state.selectedIdx[0] + 1})`).removeClass('selected');
            selectric.state.selectedIdx.shift();
        }        
    });

    // Add new rules in plugin validation
    $.validator.addMethod('filesize', function (value, element, param) {
        return element.files[0].size <= (param * 1000000);
    }, 'File size must be less');

    $.validator.addMethod('cnpj', function(value, element) {
        return validateCnpj(value);
    }, 'Invalid cnpj');

    $.validator.addMethod('arrayLengthMax', function(value, element, param) {
        return value.length <= param;
    }, 'Invalid array length very long');

    // Registration Form Validation
    $('form.restaurant-edit').validate( {
        onfocusout: false,
        onkeyup: false,
        onsubmit: false,
        rules: {
            // Restaurant Brand
            restaurantBrand: {
                required: true,
                accept: 'jpg,jpeg,png',
                // Value in mb
                filesize: 30
            },

            // Pill General
            restaurantName: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            restaurantCnpj: {
                required: true,
                // 14 digits without mask / 18 digits with mask
                minlength: 18,
                maxlength: 18,
                cnpj: true
            },
            restaurantEmail: {
                required: true,
                minlength: 7,
                maxlength: 100,
                email : true,
                remote: {
                    type: 'POST',
                    url: BASE_URL + '/register/check-email', 
                }
            },
            restaurantPhone: {
                required: true,
                // 10 digits without mask / 14 digits with mask
                minlength: 14,
                maxlength: 14
            },
            restaurantCellPhone: {
                required: true,
                // 11 digits without mask / 16 digits with mask
                minlength: 16,
                maxlength: 16
            },
            'restaurantMainCategories[]': {
                required: true,
                arrayLengthMax: 2
            },

            // Pill Address
            restaurantAddress: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            restaurantNeighborhood: {
                required: true,
                digits: true
            },
            restaurantNumber: {
                required: true,
                maxlength: 11
            },
            restaurantState: {
                required: true,
                digits: true
            },
            restaurantCity: {
                required: true,
                digits: true
            },
            restaurantComplement: {
                required: false,
                maxlength: 50
            },

            // Payment methods
            restaurantPaymentMethods: 'required'
        },
        messages: {
            // Restaurant Brand
            restaurantBrand: {
                required: 'Adicione uma logo para o restaurante',
                accept: 'Formato inválido, use: (.jpg, .jpeg ou .png)',
                filesize: 'O limite para upload é de 30mb'
            },

            // General Pill
            restaurantName: {
                required: 'Digite o nome do restaurante',
                minlength: 'O nome do restaurante precisa ter no mínimo 2 caracteres',
                maxlength: 'O nome do restaurante precisa ter no máximo 50 caracteres'
            },
            restaurantCnpj: {
                required: 'Digite o cnpj do restaurante',
                minlength: 'O cnpj precisa ter no mínimo 14 dígitos',
                maxlength: 'O cnpj precisa ter no máximo 14 dígitos',
                cnpj: 'Digite um cnpj válido'
            },
            restaurantEmail: {
                required: 'Digite o email do restaurante',
                minlength: 'O email precisa ter no mínimo 7 caracteres',
                maxlength: 'O email precisa ter no máximo 100 caracteres',
                email: 'Digite um email válido',
                remote: 'Email já cadastrado'
            },
            restaurantPhone: {
                required: 'Digite o telefone do restaurante',
                minlength: 'O telefone precisa ter no mínimo o DDD + 8 dígitos',
                maxlength: 'O telefone precisa ter no máximo o DDD + 8 dígitos'
            },
            restaurantCellPhone: {
                required: 'Digite o celular do restaurante',
                minlength: 'O celular precisa ter no mínimo o DDD + 9 dígitos',
                maxlength: 'O celular precisa ter no máximo o DDD + 9 dígitos'
            },
            'restaurantMainCategories[]': {
                required: 'Selecione 1 ou no máx 2 categorias principais para o restaurante',
                arrayLengthMax: 'Informe categorias válidas'
            },

            // Address Pill
            restaurantAddress: {
                required: 'Digite o endereço do restaurante',
                minlength: 'O endereço precisa ter no mínimo 4 caracteres',
                maxlength: 'O endereço precisa ter no máximo 50 caracteres'
            },
            restaurantNeighborhood: {
                required: 'Informe o bairro do restaurante',
                digits: 'Informe um bairro válido'
            },
            restaurantNumber: {
                required: 'Número ?',
                maxlength: 'O número precisa ter no máximo 11 caracteres'
            },
            restaurantState: {
                required: 'Informe o estado',
                digits: 'Informe um estado válido'
            },
            restaurantCity: {
                required: 'Informe a cidade',
                digits: 'Informe uma cidade válida'
            },
            restaurantComplement: {
                maxlength: 'O complemento precisa ter no máximo 50 caracteres'
            }
        },
        errorElement: 'div',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass('invalid-feedback');

            if (element.prop('name') === 'restaurantPaymentMethods') {
                error = {};
                $('input[name="restaurantPaymentMethods"]').addClass('is-invalid').removeClass('is-valid');
                // return;
            } else if ( element.prop('type') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('type') === 'file') {
                $('.user-photo-area').css('borderColor', '#fa2724');      
            } else {
                error.insertAfter( element );
            }

            // $('input[name="restaurantPaymentMethods[]"]').removeClass('is-invalid');
            // check if element has Selectric initialized on it
            var selectTric = element.data('selectric');
            
            if (selectTric) {
                error.appendTo( selectTric ? element.closest( '.' + selectTric.classes.wrapper ).parent() : element.parent() );
                
                element.closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa3734');
            }
        },
        highlight: function (element, errorClass, validClass) {
            $( element ).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            if ($(element).prop('name') === 'restaurantPaymentMethods') {
                // error = {};
                $('input[name="restaurantPaymentMethods"]').removeClass('is-invalid');
            } else {
                $( element ).addClass('is-valid').removeClass('is-invalid');
            }
        }
    });    

    // Change image preview
    $('.user-photo-area').on('click', function() {
        $('#restaurantBrand').click();
    });

    $("#restaurantBrand").on('change', function() {
        if ($(this).valid()) userPreview(this);
    });

    $('.img-overlay span').on('click', function() {
        $('.user-photo-area').css('display', 'block');
        $('.user-img-preview').css('display', 'none');
        $('#accountPhoto').val('');
    });

    // Validation inputs on change
    $('form.restaurant-edit input:not([type="checkbox"])').on('change', function () {
        if ($(this).valid()) {
            $(this).addClass('changed');
        } else {
            $(this).removeClass('changed');
        }
    });

    // Validation inputs checked/unchecked on change
    $('form.restaurant-edit input[type="checkbox"]').on('change', function () {
        if ($(this).valid()) {
            if ($(this).is(':checked')) {
                $(this).removeClass('unchecked');
                $(this).addClass('checked');
            } else {
                $(this).removeClass('checked');
                $(this).addClass('unchecked');
            }
        } else {
            $(this).removeClass('checked');
            $(this).removeClass('unchecked');
        }
    });

    // Validate selectric on change
    $('form.restaurant-edit select').on('change', function(e) {
        if ($(this).valid()) {
            $(this).addClass('changed');
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
        } else {
            $(this).removeClass('changed')
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
        }
    });

    // Enable/Disabled submit button
    $('form.restaurant-edit input, form.restaurant-edit select').on('change', function () {
        if ($('form.restaurant-edit').find('.changed, .checked, .unchecked').length && !$('form.restaurant-edit').find('.is-invalid').length) {
            if ($(this).hasClass('change-password') && (!$('.change-password').eq(0).val() || !$('.change-password').eq(1).val() || !$('.change-password').eq(2).val())) {
                $('button.submit-restaurant-edit').attr('disabled', true);
            } else {
                $('button.submit-restaurant-edit').attr('disabled', false);
            }
        } else {
            $('button.submit-restaurant-edit').attr('disabled', true);
        }
    });

    $('#modalAddOperation').on('hidden.bs.modal', function (e) {
        if (!$('.selected-week-days input:not(.saved)').length || $('.selected-week-days input').length != $('.table-operation tbody tr').length) {
            $('button.submit-restaurant-edit').attr('disabled', true);
        } else {
            $('button.submit-restaurant-edit').attr('disabled', false);
        }
        
        if ($('.deleted-week-days input').length) {
            $('button.submit-restaurant-edit').attr('disabled', false);
        }
    });

    $('form.restaurant-edit').on('submit', function (e) {
        e.preventDefault();
        
        var fieldsChanged = $(this).find('.changed');
        var fieldsChangedOperation = $('.selected-week-days input:not(.saved)');
        var fieldsDeletedOperation = $('.deleted-week-days input');
        var fieldsChangedSocialMedias = $('.selected-social-medias input:not(.saved)');
        var fieldsDeletedSocialMedias = $('.deleted-social-medias input');
        var fieldsCheckedPaymentMethods = $('form.restaurant-edit input.checked');
        var fieldsUncheckedPaymentMethods = $('form.restaurant-edit input.unchecked');
        
        // console.log(fieldsChanged[0]);

        var data = {};
        var row = {};

        var form = new FormData();
        var field = '';
        var userId = $('#info').data('user-id');
        var restaurantId = $('#info').data('restaurant-id');
        var addressId = $('#info').data('address-id');

        if (fieldsChanged.length) {
            // Add fields changed in FormData
            fieldsChanged.each(function (index, element) {
                field = $(element).attr('name');
    
                if (field == 'restaurantBrand') {
                    form.append(field, $(element)[0].files[0] ?? '');
                } else {
                    form.append(field, $(element).val());
                }
            });            
        }
        
        if (fieldsChangedOperation.length) {
            fieldsChangedOperation.each(function (index, element) {
                data = {
                    'idOperation': $(element).attr('data-id-operation'),
                    'row': $(element).attr('data-row'),
                    'dayIndex': $(element).attr('data-day-index'),
                    'weekDay': $(element).attr('data-week-day'),
                    'open1': $(element).attr('data-open1'),
                    'close1': $(element).attr('data-close1'),
                    'open2': $(element).attr('data-open2'),
                    'close2': $(element).attr('data-close2'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsDeletedOperation.length) {
            fieldsDeletedOperation.each(function (index, element) {
                data = {
                    'idOperationDeleted': $(element).attr('data-id-operation'),
                    'rowDeleted': $(element).attr('data-row'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsChangedSocialMedias.length) {
            fieldsChangedSocialMedias.each(function (index, element) {
                data = {
                    'idSocialMedia': $(element).attr('data-id-social-media'),
                    'socialMediaIndex': $(element).attr('data-social-media-index'),
                    'socialMedia': $(element).attr('data-social-media'),
                    'socialMediaRow': $(element).attr('data-social-media-row'),
                    'linkOrPhone': $(element).attr('data-link-or-phone'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsDeletedSocialMedias.length) {
            fieldsDeletedSocialMedias.each(function (index, element) {
                data = {
                    'idSocialMediaDeleted': $(element).attr('data-id-social-media'),
                    'socialMediaRowDeleted': $(element).attr('data-social-media-row'),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsCheckedPaymentMethods.length) {
            fieldsCheckedPaymentMethods.each(function (index, element) {
                data = {
                    'idPayment': $(element).val(),
                    'paymentSaved': $(element).attr('data-payment-saved')
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (fieldsUncheckedPaymentMethods.length) {
            fieldsUncheckedPaymentMethods.each(function (index, element) {
                data = {
                    'idPaymentDeleted': $(element).val(),
                };
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });
        }

        if (!fieldsChanged.length && !fieldsChangedOperation.length && !fieldsDeletedOperation.length && !fieldsChangedSocialMedias.length && !fieldsDeletedSocialMedias.length && !fieldsCheckedPaymentMethods.length && !fieldsUncheckedPaymentMethods.length) return;

        $.ajax({
            type: "POST",
            headers: { 
                'Restaurant-Id': restaurantId,
                'User-Id': userId,
                'Address-Id': addressId
            },
            url: "/restaurant/edit-action",
            contentType : false,
            processData : false,
            data: form,
            beforeSend: function() {
                $('form.restaurant-edit .submit-restaurant-edit').attr('disabled', true);
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
                    
                    $('form.restaurant-edit .server-validation a').attr('data-original-title', tooltip);
                    $('form.restaurant-edit .server-validation').css('display', 'block');

                    iziToast.error({
                        title: 'Error!',
                        message: 'Ocorreu um erro ao atualizar as informações, tente novamente!',
                        position: 'topRight',
                        timeout: 2500,
                    });
                } else {
                    $('form.restaurant-edit .server-validation a').attr('data-original-title', '');
                    $('form.restaurant-edit .server-validation').css('display', 'none');
    
                    iziToast.success({
                        title: 'Sucesso!',
                        message: 'Informações atualizadas com sucesso!',
                        position: 'topRight',
                        timeout: 2000,
                    });

                    // Refresh page
                    setTimeout(function() {
                        window.location.href = BASE_URL+'/restaurant/edit';
                    }, 2100);
                }
            },
            complete: function() {
                $('form.restaurant-edit .submit-restaurant-edit').attr('disabled', false);
            }
        });

        return false;
    });

    $('#pills-restaurant-edit .nav-link').on('click', function () {
        var pillActive = $(this).data('name');
        $('.breadcrumb-item.active').text(pillActive);
    });
    
    function userPreview(input) {
        if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('.user-img-preview img').attr('src', e.target.result);
            $('.user-photo-area').css('display', 'none');
            $('.user-img-preview').css('display', 'block');
        }
    
        reader.onloadend = function() {
            $('.user-img-preview').css('pointerEvents', 'none');
    
            setTimeout(function() {
                $('.user-img-preview').css('pointerEvents', 'auto');
            }, 100)
        }
    
        reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    
    function isValid(form) {
        var formElements = form.find('input,select');
        
        $(formElements).each(function (index, element) {
            if (!formElements.eq(index).valid()) validation = false;
        });
    
        return validation;
    }
    
    function validateCnpj(cnpj) {
     
        cnpj = cnpj.replace(/[^\d]+/g,'');
     
        if(cnpj == '') return false;
         
        if (cnpj.length != 14)
            return false;
     
        // Elimina CNPJs invalidos conhecidos
        if (cnpj == "00000000000000" || 
            cnpj == "11111111111111" || 
            cnpj == "22222222222222" || 
            cnpj == "33333333333333" || 
            cnpj == "44444444444444" || 
            cnpj == "55555555555555" || 
            cnpj == "66666666666666" || 
            cnpj == "77777777777777" || 
            cnpj == "88888888888888" || 
            cnpj == "99999999999999")
            return false;
             
        // Valida DVs
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
             
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;
               
        return true;   
    }
});

