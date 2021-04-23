$(function() {
    $('#modalDeletePlate button#deletePlate').on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/plate/delete',
            data: {
                plate_id: $('.item button.deleting').attr('plate-id')
            },
            beforeSend: function() {
                $('#modalDeletePlate button#delete').attr('disabled', true);
            },
            success: function (response) {                
                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Prato deletado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });

                // Refresh page
                setTimeout(function() {
                    location.reload();
                }, 2100);
            }
        });
        
        $('.item button.deleting').closest('.item').remove();
    });

    $('#modalDeletePlate button#backPlate').on('click', function() {
        $('.item button.deleting').removeClass('deleting');
    });

    $('#modalDeletePlate').on('hidden.bs.modal', function() {
        $('.item button.delete').removeClass('deleting');
    });
});