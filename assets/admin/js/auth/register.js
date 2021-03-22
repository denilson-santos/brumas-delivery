$(function () {
    // steps
    var step, currentStep, nextStep, previousStep;

    // style
    var left, opacity, scale, animating;

    $('form.register .next').on('click', function(e) { 
        step = $(this).data('step');

        if (!step) step = $(this).data('last-step');

        if (!isValid($(`form.register .register-step-${step}`))) {
            return false;
        } 
        
        currentStep = $(`.register-step-${step}`);
        nextStep = $(`.register-step-${step+1}`);
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
            complete: function(){
                currentStep.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    $("form.register .previous").on('click', function(e) {
        if(animating) return false;
        animating = true;
        
        step = $(this).data('step');
        currentStep = $(`.register-step-${step}`);
        previousStep = $(`.register-step-${step-1}`);
        
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

    // Client Validation
    $('form.register').validate( {
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
                    url: BASE_URL + '/register/check-email'
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
            form = $(form).serialize();
            
            // console.log(form);

            $.ajax({
                type: "POST",
                url: "/register-action",
                data: form,
                beforeSend: function() {
                    $('form.register #submitRegister').attr('disabled', true);
                },
                success: function (response) {
                    response = JSON.parse(response);
                    
                    if (!response.validate) {
                        tooltip = '<ul>';

                        var errors = response.errors;
                        console.log(errors);
                        // Get messages of server valiadation
                        for (var field in errors) {
                            var error = errors[field];
                            tooltip += `<li>${error[0]}</li>`;
                        }

                        tooltip += '</ul>';
                        
                        $('.register .server-validation a').attr('data-original-title', tooltip);
                        $('.register .server-validation').css('display', 'block');
                    } else {
                        $('.register .server-validation a').attr('data-original-title', '');
                        $('.register .server-validation').css('display', 'none');

                        // Redirect to login page
                        window.location.href = BASE_URL+'/login';
                    }
                },
                complete: function() {
                    $('form.register #submitRegister').attr('disabled', false);
                }
            });
            return false;
        }
    });

    // Validate selectric on change
    $('.register select').on('change', function(e) {
        if ($(this).valid()) {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
        } else {
            $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
        }
    });

    function isValid(currentStep) {
        var validation = true;
        var formStepElements = currentStep.find('input,select');

        for (let i = 0; i < formStepElements.length; i++) {
            if (!formStepElements.eq(i).valid()) validation = false;
        }
        
        return validation;
    }
});

