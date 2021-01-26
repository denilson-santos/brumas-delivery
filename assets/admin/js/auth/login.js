$(function () {
    $('form.login #submitLogin').on('click', function(e) { 
        if (!isValid($('form.login'))) {
            return false;
        } 
    });

    // Client Validation
    $('form.login').validate( {
        rules: {            
            // Account Information
            accountUserOrEmail: 'required',
            accountPassword: 'required'
        },
        messages: {            
            // Account Information
            accountUserOrEmail: 'Digite seu usuário ou email',
            accountPassword: 'Digite sua senha'
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
            
            console.log(form);

            $.ajax({
                type: "POST",
                url: "/login-action",
                data: form,
                beforeSend: function() {
                    $('form.login #submitLogin').attr('disabled', true);
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
                            tooltip += `<li>${error}</li>`;
                        }

                        tooltip += '</ul>';
                        
                        $('.login .server-validation a').attr('data-original-title', tooltip);
                        $('.login .server-validation').css('display', 'block');
                        $('.login #accountUserOrEmail, .login #accountPassword').removeClass('is-valid');
                        $('.login #accountUserOrEmail, .login #accountPassword').addClass('is-invalid');
                    } else {
                        $('.login .server-validation a').attr('data-original-title', '');
                        $('.login .server-validation').css('display', 'none');

                        // Redirect to home page
                        window.location.href = BASE_URL;
                    }
                },
                complete: function() {
                    $('form.login #submitLogin').attr('disabled', false);
                }
            });
            return false;
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

