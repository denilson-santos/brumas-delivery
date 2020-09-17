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
            firstName: 'required',
            lastName: 'required',
            email: {
                required: true,
                email : true
            },
            phone: {
                required: true,
                // 8 digits without mask / 14 digits with mask
                minlength: 14
            },
            address: 'required',
            neighborhood: 'required',
            number: 'required',
            state: 'required',
            city: 'required',   
            complement: false,

            // Restaurant Information
            restaurantName: 'required',
            restaurantCnpj: {
                required: true,
                // 14 digits without mask / 18 digits with mask
                minlength: 18
            },
            restaurantEmail: {
                required: true,
                email : true
            },
            restaurantPhone: {
                required: true,
                // 8 digits without mask / 14 digits with mask
                minlength: 14
            },
            restaurantMainCategories: 'required',
            restaurantAddress: 'required',
            restaurantNeighborhood: 'required',
            restaurantNumber: 'required',
            restaurantState: 'required',
            restaurantCity: 'required',
            restaurantComplement: false,
            
            // Account Information
            accountUserName: 'required',
            accountPassword: {
                required: true,
                minlength: 4
            },
            accountConfirmPassword: {
                required: true,
                minlength: 4,
                equalTo: '#accountPassword'
            },
            accountTerms: "required"
        },
        messages: {
            // Personal Information
            firstName: 'Digite seu primeiro nome',
            lastName: 'Digite seu sobrenome',
            email: {
                required: 'Digite seu email',
                email : 'Digite um email válido'
            },
            phone: {
                required: 'Digite seu telefone',
                minlength: 'O telefone precisa ter no mínimo 8 dígitos'
            },
            address: 'Digite seu endereço',
            neighborhood: 'Digite seu bairro',
            number: 'Número ?',
            state: 'Informe o seu estado',
            city: 'Informe a sua cidade',   
            
            // Restaurant Information
            restaurantName: 'Digite o nome do restaurante',
            restaurantCnpj: {
                required:'Digite o cnpj do restaurante',
                minlength: 'O cnpj precisa ter no mínimo 14 dígitos'
            },
            restaurantEmail: {
                required: 'Digite o email do restaurante',
                email : 'Digite um email válido'
            },
            restaurantPhone: {
                required: 'Digite o telefone do restaurante',
                minlength: 'O telefone precisa ter no mínimo 8 dígitos'
            },
            restaurantMainCategories: 'Selecione 1 ou no máx 2 categorias principais para o restaurante',
            restaurantAddress: 'Digite o endereço do restaurante',
            restaurantNeighborhood: 'Digite o bairro do restaurante',
            restaurantNumber: 'Número ?',
            restaurantState: 'Informe o estado',
            restaurantCity: 'Informe a cidade',   

            // Account Information
            accountUserName: 'Digite seu usuário',
            accountPassword: {
                required: 'Digite sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres'
            },
            accountConfirmPassword: {
                required: 'Digite novamente sua senha',
                minlength: 'A senha precisa ter no mínimo 4 caracteres',
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
        submitHandler: function () {
            alert('Novo Cadastro Realizado com Sucesso!');
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

