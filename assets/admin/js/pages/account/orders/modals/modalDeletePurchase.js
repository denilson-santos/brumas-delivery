$(function () {
    $('#modalDeletePurchase button#deletePurchase').on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/account/orders/change-status',
            data: {
                purchase_id: $('.table-account-orders button.deleting').attr('purchase-id'),
                status: 2 // status declined
            },
            beforeSend: function() {
                $('#modalDeletePurchase button#deletePurchase').attr('disabled', true);
            },
            success: function (response) {
                $.ajax({
                    type: 'POST',
                    url: '/account/orders/delete',
                    data: {
                        purchase_id: $('.table-account-orders button.deleting').attr('purchase-id')
                    },
                    success: function (response) {
                        $('.table-account-orders button.deleting').closest('tr').remove();
                
                        iziToast.success({
                            title: 'Sucesso!',
                            message: 'Pedido deletado com sucesso!',
                            position: 'topRight',
                            timeout: 2000,
                        });
                    }
                });
            }
        });
    });

    $('#modalDeletePurchase').on('hidden.bs.modal', function() {
        $('.table-account-orders button.delete').removeClass('deleting');
    });
});