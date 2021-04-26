$(function () { 
    $('.table-restaurant-orders .more-order-details').on('click', function() {
        if ($(this).hasClass('collapsed')) {
            $(this).find('i').removeClass('fa-chevron-right');
            $(this).find('i').addClass('fa-chevron-down');
            $(this).find('i').addClass('text-primary');
        } else {
            $(this).find('i').removeClass('fa-chevron-down');
            $(this).find('i').addClass('fa-chevron-right');
            $(this).find('i').removeClass('text-primary');
        }
    });

    $('.table-restaurant-orders button.accept').on('click', function() {
        var currentButton = $(this);

        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.accept').attr('purchase-id'),
                status: 1 // status accepted
            },
            success: function (response) {
                currentButton.closest('tr').find('td:nth-child(3)').html('');
                currentButton.closest('tr').find('td:nth-child(3)').append(`
                    <span class="badge badge-success">Aceito</span>
                `);

                currentButton.addClass('d-none');
                currentButton.closest('tr').find('button.refuse').addClass('d-none');
                currentButton.closest('tr').find('button.put-into-production').removeClass('d-none');

                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Status alterado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        });
    });

    $('.table-restaurant-orders button.refuse').on('click', function() {
        var currentButton = $(this);

        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.refuse').attr('purchase-id'),
                status: 2 // status declined
            },
            success: function (response) {
                currentButton.closest('tr').find('td:nth-child(3)').html('');
                currentButton.closest('tr').find('td:nth-child(3)').append(`
                    <span class="badge badge-danger">Recusado</span>
                `);

                currentButton.addClass('d-none');
                currentButton.closest('tr').find('button.accept').addClass('d-none');
                
                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Status alterado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        });
    });

    $('.table-restaurant-orders button.put-into-production').on('click', function() {
        var currentButton = $(this);

        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.put-into-production').attr('purchase-id'),
                status: 3 // status in-production
            },
            success: function (response) {
                currentButton.closest('tr').find('td:nth-child(3)').html('');
                currentButton.closest('tr').find('td:nth-child(3)').append(`
                    <span class="badge badge-warning">Em produção</span>
                `);

                currentButton.addClass('d-none');
                currentButton.closest('tr').find('button.sent').removeClass('d-none');

                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Status alterado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        });
    });

    $('.table-restaurant-orders button.sent').on('click', function() {
        var currentButton = $(this);

        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.sent').attr('purchase-id'),
                status: 4 // status sent
            },
            success: function (response) {
                currentButton.closest('tr').find('td:nth-child(3)').html('');
                currentButton.closest('tr').find('td:nth-child(3)').append(`
                    <span class="badge badge-info">Enviado</span>
                `);

                currentButton.addClass('d-none');
                currentButton.closest('tr').find('button.deliver').removeClass('d-none');

                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Status alterado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        });
    });

    $('.table-restaurant-orders button.deliver').on('click', function() {
        var currentButton = $(this);

        $.ajax({
            type: 'POST',
            url: '/restaurant/orders/change-status',
            data: {
                purchase_id: $('.table-restaurant-orders button.deliver').attr('purchase-id'),
                status: 5 // status delivered
            },
            success: function (response) {
                currentButton.closest('tr').find('td:nth-child(3)').html('');
                currentButton.closest('tr').find('td:nth-child(3)').append(`
                    <span class="badge badge-success">Entregue</span>
                `);

                currentButton.addClass('d-none');

                iziToast.success({
                    title: 'Sucesso!',
                    message: 'Status alterado com sucesso!',
                    position: 'topRight',
                    timeout: 2000,
                });
            }
        });
    });

    $('.table-restaurant-orders button.delete').on('click', function() {
        $(this).addClass('deleting');
    });
});