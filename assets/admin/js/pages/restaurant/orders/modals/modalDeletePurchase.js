$(function () {
    $('#modalDeletePurchase button#deletePurchase').on('click', function() {
        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.deleting').attr('purchase-id'),
                status: 2 // status declined
            },
            beforeSend: function() {
                $('#modalDeletePurchase button#deletePurchase').attr('disabled', true);
            },
            success: function (response) {
                $.ajax({
                    type: 'POST',
                    url: '/restaurant/orders/delete',
                    data: {
                        purchase_id: $('.table-restaurant-orders button.deleting').attr('purchase-id')
                    },
                    success: function (response) {
                        $('.table-restaurant-orders button.deleting').closest('tr').remove();
                
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
        $('.table-restaurant-orders button.delete').removeClass('deleting');
    });
});