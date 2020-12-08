$(function () {
    // steps
    var step, currentStep, nextStep, previousStep;

    // style
    var left, opacity, scale, animating;

    $('form.register-partner .next').click(function(e) { 
        step = $(this).data('step');

        if (!step) step = $(this).data('last-step');

        if (!isValid($(`form.register-partner .register-partner-step-${step}`), step)) {
            return false;
        } 
                
        currentStep = $(`.register-partner-step-${step}`);
        nextStep = $(`.register-partner-step-${step+1}`);
        animating = true;

        $("#progressbar li").eq($("fieldset").index(nextStep)).addClass("active");
    
        //show the next fieldset
        nextStep.show(); 
        
        //hide the current fieldset with style
        currentStep.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50)+"%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                currentStep.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute',
                    'width': '96%'
                });
                nextStep.css({'left': left, 'opacity': opacity});
        }, 
            duration: 800, 
            complete: function() {
                currentStep.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $("form.register-partner .previous").click(function(e) {
        if(animating) return false;
        animating = true;
        
        step = $(this).data('step');
        currentStep = $(`.register-partner-step-${step}`);
        previousStep = $(`.register-partner-step-${step-1}`);
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(currentStep)).removeClass("active");
        
        //show the previous fieldset
        previousStep.show(); 
        //hide the current fieldset with style
        currentStep.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                currentStep.css({'left': left});
                previousStep.css({
                    'transform': 'scale('+scale+')', 
                    'opacity': opacity
                });
            }, 
            duration: 800, 
            complete: function(){
                currentStep.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });
    
    // Adapts the select multiple of the selectric to accept only 2 checked options
    var mainCategories = $('.restaurant-main-categories').selectric();
    
    mainCategories.on('selectric-before-change', function(event, element, selectric) {                
        if (selectric.state.selectedIdx.length == 3) {
            $(`.selectric-restaurant-main-categories ul li:nth-child(${selectric.state.selectedIdx[0] + 1})`).removeClass('selected');
            selectric.state.selectedIdx.shift();
        }        
    });
    
    // Add new rules in plugin validation
    $.validator.addMethod('cnpj', function(value, element) {
        return validateCnpj(value);
    }, 'Invalid cnpj');

    $.validator.addMethod('arrayLengthMax', function(value, element, param) {
        return value.length <= param;
    }, 'Invalid array length very long');

    $.validator.addMethod('filesize', function (value, element, param) {
        return element.files[0].size <= (param * 1000000);
    }, 'File size must be less');

    // Registration Form Validation
    $('.register-partner').validate( {
        rules: {
            // Personal Information
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
            accountNeighborhood: {
                required: true,
                digits: true
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
            accountComplement: {
                required: false,
                maxlength: 50
            },

            // Restaurant Information
            restaurantBrand: {
                required: true,
                accept: 'jpg,jpeg,png',
                // Value in mb
                filesize: 30
            },
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
            
            // Account Information
            accountUserName: {
                required: true,
                minlength: 2,
                maxlength: 30,
                remote: {
                    type: 'POST',
                    url: BASE_URL + '/register/check-user', 
                }
            },
            accountPassword: {
                required: true,
                minlength: 4,
                maxlength: 255
            },
            accountConfirmPassword: {
                required: true,
                minlength: 4,
                maxlength: 255,
                equalTo: '#accountPassword'
            },
            accountTerms: 'required'
        },
        messages: {
            // Personal Information
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
            accountNeighborhood: {
                required: 'Informe seu bairro',
                digits: 'Informe um bairro válido'
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
            accountComplement: {
                maxlength: 'O complemento precisa ter no máximo 50 caracteres'
            },
            
            // Restaurant Information
            restaurantBrand: {
                required: 'Adicione uma logo para o restaurante',
                accept: 'Formato inválido, use: (.jpg, .jpeg ou .png)',
                filesize: 'O limite para upload é de 30mb'
            },
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
            },

            // Account Information
            accountUserName: {
                required: 'Digite seu usuário',
                minlength: 'O usuário precisa ter no mínimo 2 caracteres',
                maxlength: 'O usuário precisa ter no máximo 30 caracteres',
                remote: 'O usuário já existe'
            },
            accountPassword: {
                required: 'Digite sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 255 caracteres'
            },
            accountConfirmPassword: {
                required: 'Digite novamente sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 255 caracteres',
                equalTo: 'As senhas não conferem, tente novamente'
            },
            accountTerms: 'Aceite os termos'
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
                $('.restaurant-brand-area').css('borderColor', '#fa2724');  
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
        }, 
        submitHandler: function (form) {
            var data = {};
            var row = {};

            var form = new FormData(form);

            //  Add restaurant operation in FormData
            $('.selected-week-days input').each(function (index, element) {
                data = $(element).data();
            
                for (const key in data) {
                    if (!Array.isArray(row[key])) row[key] = [];
                    form.append(`${key}[]`, data[key]);
                }
            });

            $.ajax({
                type: "POST",
                url: "/be-a-partner-action",
                contentType : false,
                processData : false,
                data: form,
                beforeSend: function() {
                    $('form.register-partner #submitRegisterPartner').attr('disabled', true);
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
                        
                        $('.register-partner .server-validation a').attr('data-original-title', tooltip);
                        $('.register-partner .server-validation').css('display', 'block');
                    } else {
                        $('.register-partner .server-validation a').attr('data-original-title', '');
                        $('.register-partner .server-validation').css('display', 'none');

                        // Redirect to login page
                        window.location.href = BASE_URL+'/login';
                    }
                },
                complete: function() {
                    $('form.register-partner #submitRegisterPartner').attr('disabled', false);
                }
            });

            return false;
        }
    } );

    // Validate selectric on change
    $('.register-partner select').on('change', function(e) {
        if ($(this).valid()) {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
        } else {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
        }
    });

    // Prevents the operation modal from being called when pressing enter
    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            return false;
        }
    });

    $('.restaurant-brand-area').on('click', function() {
        $('#restaurantBrand').click();
    });

    $("#restaurantBrand").on('change', function() {
        if ($(this).valid()) restaurantPreview(this);
    });

    $('.img-overlay span').on('click', function() {
        $('.restaurant-brand-area').css('display', 'block');
        $('.restaurant-img-preview').css('display', 'none');
        $('#restaurantBrand').val('');
    });

    function restaurantPreview(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
            $('.restaurant-img-preview img').attr('src', e.target.result);
            $('.restaurant-brand-area').css('display', 'none');
            $('.restaurant-img-preview').css('display', 'block');
          }

          reader.onloadend = function() {
            $('.restaurant-img-preview').css('pointerEvents', 'none');

            setTimeout(function() {
                $('.restaurant-img-preview').css('pointerEvents', 'auto');
            }, 100)
          }

          reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    function isValid(currentStep, step) {
        var validation = true;
        var formStepElements = currentStep.find('input,select');
        
        $(formStepElements).each(function (index, element) {
            if (!formStepElements.eq(index).valid()) validation = false;
        });
        
        if (step === 2) {
            if (!$('.selected-week-days input').length || $('.selected-week-days input').length != $('.table-operation tbody tr').length) {
                $('#restaurantOperation-error.error').remove();
                $('button#restaurantAddOperation').after('<div id="restaurantOperation-error" class="error invalid-feedback" style="display: block;                margin-top: 0.40rem;">Horários inválidos</div>');
                validation = false;
            } else {
                $('#restaurantOperation-error.error').remove();
            }

            $('#fire-modal-1').on('hidden.bs.modal', function (e) {
                if (!$('.selected-week-days input').length || $('.selected-week-days input').length != $('.table-operation tbody tr').length) {
                    $('#restaurantOperation-error.error').remove();
                    $('button#restaurantAddOperation').after('<div id="restaurantOperation-error" class="error invalid-feedback" style="display: block;                margin-top: 0.40rem;">Horários inválidos</div>');
                    validation = false;
                } else {
                    $('#restaurantOperation-error.error').remove();
                }   
            });
            
        }
        
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

