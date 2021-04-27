$(function () {
    var ctx = document.getElementById("dashboardChart").getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
            data: [
                $('[count-restaurant-purchases]').attr('count-restaurant-purchases'),
                $('[count-restaurant-plates]').attr('count-restaurant-plates'),
                $('[count-restaurant-ratings]').attr('count-restaurant-ratings')
            ],
            backgroundColor: [
                '#fa3734',
                '#47c363',
                '#ffa426'
            ],
            label: 'Dataset 1'
            }],
            labels: [
                'Pedidos Recebidos',
                'Pratos Cadastrados',
                'Avaliações Recebidas'
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