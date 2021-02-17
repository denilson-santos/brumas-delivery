$(function () {
    // Add new rules in plugin validation
    $.validator.addMethod('filesize', function (value, element, param) {
        return element.files[0].size <= (param * 1000000);
    }, 'File size must be less');

    // Registration Form Validation
    $('form.profile').validate( {
        onfocusout: false,
        onkeyup: false,
        onsubmit: false,
        rules: {
            accountPhoto: {
                required: true,
                accept: 'jpg,jpeg,png',
                // Value in mb
                filesize: 30
            },
            accountFirstName: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            accountLastName: {
                required: true,
                minlength: 4,
                maxlength: 30
            },
            accountUserName: {
                required: true,
                minlength: 2,
                maxlength: 30,
                remote: {
                    type: 'POST',
                    url: BASE_URL + '/register/check-user', 
                }
            },
            accountEmail: {
                required: true,
                minlength: 7,
                maxlength: 100,
                email: true,
                remote: {
                    type: 'POST',
                    url: BASE_URL + '/register/check-email', 
                }
            },
            accountCellPhone: {
                required: true,
                // 9 digits without mask / 16 digits with mask
                minlength: 16,
                maxlength: 16
            },
            accountAddress: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            accountComplement: {
                required: false,
                maxlength: 50
            },
            accountNumber: {
                required: true,
                maxlength: 11
            },
            accountState: {
                required: true,
                digits: true
            },
            accountCity: {
                required: true,
                digits: true
            },
            accountNeighborhood: {
                required: true,
                digits: true
            },       
            accountOldPassword: {
                required: true,
                minlength: 4,
                maxlength: 255
            },
            accountNewPassword: {
                required: true,
                minlength: 4,
                maxlength: 255
            },
            accountConfirmNewPassword: {
                required: true,
                minlength: 4,
                maxlength: 255,
                equalTo: '#accountNewPassword'
            },
        },
        messages: {
            accountPhoto: {
                required: 'Adicione uma foto para seu perfil',
                accept: 'Formato inválido, use: (.jpg, .jpeg ou .png)',
                filesize: 'O limite para upload é de 30mb'
            },
            accountFirstName: {
                required: 'Digite seu primeiro nome',
                minlength: 'O seu primeiro nome precisa ter no mínimo 2 caracteres',
                maxlength: 'O seu primeiro nome precisa ter no máximo 50 caracteres'
            },
            accountLastName: { 
                required: 'Digite seu sobrenome',
                minlength: 'O seu sobrenome precisa ter no mínimo 4 caracteres',
                maxlength: 'O seu sobrenome precisa ter no máximo 30 caracteres'
            },
            accountUserName: {
                required: 'Digite seu usuário',
                minlength: 'O usuário precisa ter no mínimo 2 caracteres',
                maxlength: 'O usuário precisa ter no máximo 30 caracteres',
                remote: 'O usuário já existe'
            },
            accountEmail: {
                required: 'Digite seu email',
                minlength: 'O email precisa ter no mínimo 7 caracteres',
                maxlength: 'O email precisa ter no máximo 100 caracteres',
                email: 'Digite um email válido',
                remote: 'Email já cadastrado'
            },
            accountCellPhone: {
                required: 'Digite seu celular',
                minlength: 'O celular precisa ter no mínimo o DDD + 9 dígitos',
                maxlength: 'O celular precisa ter no máximo o DDD + 9 dígitos'
            },
            accountAddress: {
                required: 'Digite seu endereço',
                minlength: 'O endereço precisa ter no mínimo 4 caracteres',
                maxlength: 'O endereço precisa ter no máximo 50 caracteres'
            },
            accountComplement: {
                maxlength: 'O complemento precisa ter no máximo 50 caracteres'
            },
            accountNumber: {
                required: 'Número ?',
                maxlength: 'O seu número precisa ter no máximo 11 caracteres'
            },
            accountState: {
                required: 'Informe seu estado',
                digits: 'Informe um estado válido',
            },
            accountCity: {
                required: 'Informe sua cidade',
                digits: 'Informe uma cidade válida'
            },
            accountNeighborhood: {
                required: 'Informe seu bairro',
                digits: 'Informe um bairro válido'
            },
            accountOldPassword: {
                required: 'Digite sua senha antiga',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 255 caracteres'
            },
            accountNewPassword: {
                required: 'Digite sua nova senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 255 caracteres'
            },
            accountConfirmNewPassword: {
                required: 'Digite novamente sua nova senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 255 caracteres',
                equalTo: 'As senhas não conferem, tente novamente'
            }
        },
        errorElement: 'div',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass('invalid-feedback');

            if ( element.prop('type') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('') === 'checkbox') {
                error.insertAfter(element.parents('label').addClass('is-invalid').removeClass('is-valid') );
            } else if ( element.prop('type') === 'file') {
                $('.user-photo-area').css('borderColor', '#fa2724');  
            } else {
                error.insertAfter( element );
            }

            // check if element has Selectric initialized on it
            var selectTric = element.data('selectric');
            
            error.appendTo( selectTric ? element.closest( '.' + selectTric.classes.wrapper ).parent() : element.parent() );

            if (selectTric) {
                element.closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa3734');
            }
        },
        highlight: function (element, errorClass, validClass) {
            $( element ).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass('is-valid').removeClass('is-invalid');
        }
    });    

    // Change image preview
    $('.user-photo-area').on('click', function() {
        $('#accountPhoto').click();
    });

    $("#accountPhoto").on('change', function() {
        if ($(this).valid()) userPreview(this);
    });

    $('.img-overlay span').on('click', function() {
        $('.user-photo-area').css('display', 'block');
        $('.user-img-preview').css('display', 'none');
        $('#accountPhoto').val('');
    });

    // Validation inputs on change
    $('form.profile input').on('change', function () {
        if ($(this).valid()) {
            $(this).addClass('changed');
        } else {
            $(this).removeClass('changed')
        }
    });

    // Validate selectric on change
    $('form.profile select').on('change', function(e) {
        if ($(this).valid()) {
            $(this).addClass('changed');
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
        } else {
            $(this).removeClass('changed')
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
        }
    });

    // Enable/Disabled submit button
    $('form.profile input, form.profile select').on('change', function () {
        if ($('form.profile').find('.changed').length > 0 && !$(this).hasClass('is-invalid')) {
            if ($(this).hasClass('change-password') && (!$('.change-password').eq(0).val() || !$('.change-password').eq(1).val() || !$('.change-password').eq(2).val())) {
                $('button.submit-profile').attr('disabled', true);
            } else {
                $('button.submit-profile').attr('disabled', false);
            }
        } else {
            $('button.submit-profile').attr('disabled', true);
        }
    });

    $('form.profile').on('submit', function (e) {
        e.preventDefault();
        var fieldsChanged = $(this).find('.changed');
        
        // console.log(fieldsChanged[0]);
        
        if (fieldsChanged.length > 0) {
            var form = new FormData();
            var field = '';
            var userId = $('#accountPhoto').data('user-id');

            fieldsChanged.each(function (index, element) {
                field = $(element).attr('name');

                if (field == 'accountPhoto') {
                    form.append(field, $(element)[0].files[0] ?? '');
                } else {
                    form.append(field, $(element).val());
                }
            });

            $.ajax({
                type: "POST",
                headers: { 'User-Id': userId },
                url: "/account/profile-action",
                contentType : false,
                processData : false,
                data: form,
                beforeSend: function() {
                    $('form.profile .submit-profile').attr('disabled', true);
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
                        
                        $('form.profile .server-validation a').attr('data-original-title', tooltip);
                        $('form.profile .server-validation').css('display', 'block');

                        var changePasswordFields = ['accountOldPassword', 'accountNewPassword', 'accountConfirmNewPassword']

                        if (changePasswordFields.some((field) => Object.prototype.hasOwnProperty.call(response.errors, field))) {
                            $('form.profile #accountOldPassword, form.profile #accountNewPassword, form.profile #accountConfirmNewPassword').removeClass('is-valid');
                            $('form.profile #accountOldPassword, form.profile #accountNewPassword, form.profile #accountConfirmNewPassword').addClass('is-invalid');
                        }

                        iziToast.error({
                            title: 'Error!',
                            message: 'Ocorreu um erro ao atualizar as informações, tente novamente!',
                            position: 'topRight',
                            timeout: 2500,
                        });
                    } else {
                        $('form.profile .server-validation a').attr('data-original-title', '');
                        $('form.profile .server-validation').css('display', 'none');
        
                        iziToast.success({
                            title: 'Sucesso!',
                            message: 'Informações atualizadas com sucesso!',
                            position: 'topRight',
                            timeout: 2000,
                        });

                        // Refresh page
                        setTimeout(function() {
                            window.location.href = BASE_URL+'/account/profile';
                        }, 2100);
                    }
                },
                complete: function() {
                    $('form.profile .submit-profile').attr('disabled', false);
                }
            });
        }

        return false;
    });

    $('#pills-profile .nav-link').on('click', function () {
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
});
