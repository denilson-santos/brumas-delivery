$(function () {
    var ctx = document.getElementById("dashboardChart").getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    $('input[name="countRestaurantPendingPurchases"]').val(),
                    $('input[name="countRestaurantAcceptedPurchases"]').val(),
                    $('input[name="countRestaurantInProductionPurchases"]').val(),
                    $('input[name="countRestaurantSentPurchases"]').val(),
                    $('input[name="countRestaurantDeliveredPurchases"]').val(),
                    $('input[name="countRestaurantRecusedPurchases"]').val()
                ],
                backgroundColor: [
                    '#cdd3d8',
                    '#47c363',
                    '#ffc107',
                    '#3abaf4',
                    '#438c54',
                    '#fc544b'
                ],
            }],
            labels: [
                'Pedidos Pendentes',
                'Pedidos Aceitos',
                'Pedidos Em produção',
                'Pedidos Enviados',
                'Pedidos Entregues',
                'Pedidos Recusados'
            ],
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
        }
    });
});