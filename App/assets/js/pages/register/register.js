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

    // Registration Form Validation
    $('.register').validate( {
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
                // 14 digits with mask, without mask => 8
                minlength: 14
            },
            address: 'required',
            neighborhood: 'required',
            number: 'required',
            state: 'required',
            city: 'required',   
            complement: false,
            
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

    $('.register select').on('change', function(e) {
        $(this).valid();

        $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
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

