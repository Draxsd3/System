document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('fluxoCaixaChart').getContext('2d');
    var fluxoCaixaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
            datasets: [{
                    label: 'Entradas',
                    data: entradas,  // Referenciando a variável global passada pelo PHP
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Saídas',
                    data: saidas,  // Referenciando a variável global passada pelo PHP
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
