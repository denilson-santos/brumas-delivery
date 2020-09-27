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
    
    // Registration Form Validation
    $('.register-partner').validate( {
        rules: {
            // Personal Information
            firstName: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            lastName: {
                required: true,
                minlength: 4,
                maxlength: 30
            },
            email: {
                required: true,
                minlength: 7,
                maxlength: 100,
                email : true
            },
            cellPhone: {
                required: true,
                // 9 digits without mask / 15 digits with mask
                minlength: 15,
                maxlength: 15
            },
            address: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            neighborhood: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            number: {
                required: true,
                maxlength: 11
            },
            state: {
                required: true,
                digits: true
            },
            city: {
                required: true,
                digits: true
            },   
            complement: {
                required: false,
                maxlength: 50
            },

            // Restaurant Information
            restaurantName: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            restaurantCnpj: {
                required: true,
                // 14 digits without mask / 18 digits with mask
                minlength: 18,
                maxlength: 18
            },
            restaurantEmail: {
                required: true,
                minlength: 7,
                maxlength: 100,
                email : true
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
            restaurantMainCategories: {
                required: true
            },
            restaurantAddress: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            restaurantNeighborhood: {
                required: true,
                minlength: 4,
                maxlength: 50,
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
                maxlength: 30
            },
            accountPassword: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            accountConfirmPassword: {
                required: true,
                minlength: 4,
                maxlength: 50,
                equalTo: '#accountPassword'
            },
            accountTerms: 'required'
        },
        messages: {
            // Personal Information
            firstName: {
                required: 'Digite seu primeiro nome',
                minlength: 'O seu primeiro nome precisa ter no mínimo 2 caracteres',
                maxlength: 'O seu primeiro nome precisa ter no máximo 50 caracteres'
            },
            lastName: { 
                required: 'Digite seu sobrenome',
                minlength: 'O seu sobrenome precisa ter no mínimo 4 caracteres',
                maxlength: 'O seu sobrenome precisa ter no máximo 30 caracteres'
            },
            email: {
                required: 'Digite seu email',
                minlength: 'O email precisa ter no mínimo 7 caracteres',
                maxlength: 'O email precisa ter no máximo 100 caracteres',
                email : 'Digite um email válido'
            },
            cellPhone: {
                required: 'Digite seu celular',
                minlength: 'O celular precisa ter no mínimo o DDD + 9 dígitos',
                maxlength: 'O celular precisa ter no máximo o DDD + 9 dígitos'
            },
            address: {
                required: 'Digite seu endereço',
                minlength: 'O endereço precisa ter no mínimo 4 caracteres',
                maxlength: 'O endereço precisa ter no máximo 50 caracteres'
            },
            neighborhood: {
                required: 'Digite seu bairro',
                minlength: 'O bairro precisa ter no mínimo 4 caracteres',
                maxlength: 'O bairro precisa ter no máximo 50 caracteres'
            },
            number: {
                required: 'Número ?',
                maxlength: 'O seu número precisa ter no máximo 11 caracteres'
            },
            state: {
                required: 'Informe seu estado',
                digits: 'Informe um estado válido',
            },
            city: {
                required: 'Informe sua cidade',
                digits: 'Informe uma cidade válida'
            },
            complement: {
                maxlength: 'O complemento precisa ter no máximo 50 caracteres'
            },
            
            // Restaurant Information
            restaurantName: {
                required: 'Digite o nome do restaurante',
                minlength: 'O nome do restaurante precisa ter no mínimo 2 caracteres',
                maxlength: 'O nome do restaurante precisa ter no máximo 50 caracteres'
            },
            restaurantCnpj: {
                required: 'Digite o cnpj do restaurante',
                minlength: 'O cnpj precisa ter no mínimo 14 dígitos',
                maxlength: 'O cnpj precisa ter no máximo 14 dígitos'
            },
            restaurantEmail: {
                required: 'Digite o email do restaurante',
                email : 'Digite um email válido'
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
            restaurantMainCategories: 'Selecione 1 ou no máx 2 categorias principais para o restaurante',
            restaurantAddress: {
                required: 'Digite o endereço do restaurante',
                minlength: 'O endereço precisa ter no mínimo 4 caracteres',
                maxlength: 'O endereço precisa ter no máximo 50 caracteres'
            },
            restaurantNeighborhood: {
                required: 'Digite o bairro do restaurante',
                minlength: 'O bairro precisa ter no máximo 4 caracteres',
                maxlength: 'O bairro precisa ter no máximo 50 caracteres'
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
            complement: {
                maxlength: 'O complemento precisa ter no máximo 50 caracteres'
            },

            // Account Information
            accountUserName: {
                required: 'Digite seu usuário',
                minlength: 'O usuário precisa ter no mínimo 2 caracteres',
                maxlength: 'O usuário precisa ter no máximo 30 caracteres'
            },
            accountPassword: {
                required: 'Digite sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 50 caracteres'
            },
            accountConfirmPassword: {
                required: 'Digite novamente sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
                maxlength: 'A senha precisa ter no máximo 50 caracteres',
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
            alert('Novo Cadastro Realizado com Sucesso!');

            // form = $(form).serialize();
            // console.log(form);
            // $.ajax({
            //     type: "POST",
            //     url: "/be-a-partner-action",
            //     data: form,
            //     success: function (response) {
            //         // response = JSON.parse(response);
            //         console.log(response);
            //         // if (!response.validate) {
            //         //     tooltip = '<ul>';

            //         //     var errors = response.errors;
            //         //     console.log(errors);
            //         //     // Get messages of server valiadation
            //         //     for (var field in errors) {
            //         //         var error = errors[field];
            //         //         tooltip += `<li>${error[0]}</li>`;
            //         //     }

            //         //     tooltip += '</ul>';
                        
            //         //     $('.register .server-validation a').attr('data-original-title', tooltip);
            //         //     $('.register .server-validation').css('display', 'block');
            //         // } else {
            //         //     $('.register .server-validation a').attr('data-original-title', '');
            //         //     $('.register .server-validation').css('display', 'none');
            //         // }

            //     }
            // });

            return true;
        }
    } );

    $('.register-partner select').on('change', function(e) {
        $(this).valid();

        $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
    });

    
    // $('.register-partner #restaurantAddOperation').on('click', function(e) {
    //     $(this).valid();

    //     $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
    // });

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
            
        }
        
        return validation;
    }
});

