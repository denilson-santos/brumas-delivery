// Add new rules in plugin validation
$.validator.addMethod('filesize', function (value, element, param) {
    return element.files[0].size <= (param * 1000000);
}, 'File size must be less');

$('.profile button#submitProfile').on('click', function () {
   // Registration Form Validation
$('.profile').validate( {
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
        }
    },
    messages: {
        accountPhoto: {
            required: 'Adicione uma foto',
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
    }, 
    submitHandler: function (form) {
        // var data = {};
        // var row = {};

        // var form = new FormData(form);

        // //  Add restaurant operation in FormData
        // $('.selected-week-days input').each(function (index, element) {
        //     data = $(element).data();
        
        //     for (const key in data) {
        //         if (!Array.isArray(row[key])) row[key] = [];
        //         form.append(`${key}[]`, data[key]);
        //     }
        // });

        // $.ajax({
        //     type: "POST",
        //     url: "/be-a-partner-action",
        //     contentType : false,
        //     processData : false,
        //     data: form,
        //     beforeSend: function() {
        //         $('form.register-partner #submitRegisterPartner').attr('disabled', true);
        //     },
        //     success: function (response) {
        //         response = JSON.parse(response);
        //         console.log(response);
        //         if (!response.validate) {
        //             tooltip = '<ul>';

        //             var errors = response.errors;
        //             // Get messages of server valiadation
        //             for (var field in errors) {
        //                 var error = errors[field];
        //                 tooltip += `<li>${error[0]}</li>`;
        //             }

        //             tooltip += '</ul>';
                    
        //             $('.register-partner .server-validation a').attr('data-original-title', tooltip);
        //             $('.register-partner .server-validation').css('display', 'block');
        //         } else {
        //             $('.register-partner .server-validation a').attr('data-original-title', '');
        //             $('.register-partner .server-validation').css('display', 'none');

        //             // Redirect to login page
        //             window.location.href = BASE_URL+'/login';
        //         }
        //     },
        //     complete: function() {
        //         $('form.register-partner #submitRegisterPartner').attr('disabled', false);
        //     }
        // });

        return false;
    }
} );    
});

// Validate selectric on change
$('.profile select').on('change', function(e) {
    if ($(this).valid()) {
        $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#28a745');
    } else {
        $(this).closest('.selectric-wrapper').find('div.selectric').css('border-color', '#fa2724');
    }
});

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

$('.profile button#submitProfile').on('click', function () {
    a = isValid($(this).closest('form'));
    console.log(a);
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
    // var formElements = form.find('input,select');
    
    // $(formElements).each(function (index, element) {
    //     if (!formElements.eq(index).valid()) validation = false;
    // });

    // return validation;
}