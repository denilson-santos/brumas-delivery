$(function () {
    $('#formNewsletter').on('submit', function (e) { 
        e.preventDefault();
        var email = $('#email').val();

        $("#subscribe").attr("disabled", true);
        
        $.ajax({
            type: 'POST',
            url: BASE_URL+'/newsletter/subscribe',
            data: {email : email},
            dataType: 'json',
            success: function(data) {
                if (data.status == 1) {
                    $('#modalNewsletter .modal-body').html(`<i class="far fa-check-circle success"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-primary');
                    $('#modalNewsletter .modal-footer button').addClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');

                } else if (data.status == 0){
                    $('#modalNewsletter .modal-body').html(`<i class="far fa-check-circle success"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-primary');
                    $('#modalNewsletter .modal-footer button').addClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').text('Entendi');
                    $('#modalNewsletter').modal('toggle');
                } else {
                    $('#modalNewsletter .modal-body').html(`<i class="fas fa-exclamation-circle error"></i><p>${data.message}</p>`);
                    $('#modalNewsletter .modal-footer button').removeClass('btn-cc-green');
                    $('#modalNewsletter .modal-footer button').addClass('btn-primary');
                    $('#modalNewsletter .modal-footer button').text('Fechar');
                    $('#modalNewsletter').modal('toggle');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#modalNewsletter .modal-body').html(`<i class="fas fa-exclamation-circle btn-red"></i><p>${data.message}</p>`);
                $('#modalNewsletter .modal-footer button').removeClass('btn-green');
                $('#modalNewsletter .modal-footer button').addClass('btn-red');
                $('#modalNewsletter .modal-footer button').text('Fechar');
                $('#modalNewsletter').modal('toggle');
            },
            complete: function() {
                $("#subscribe").attr("disabled", false);
            }
        });
    });
});