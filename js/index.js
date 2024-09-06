document.addEventListener("DOMContentLoaded", function () {
    var canvas = document.getElementById('graficoPercentual');
    if (canvas) {
        var ctx = canvas.getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Entrada', 'Saída'],
                datasets: [{
                    label: 'Controle de Álcool',
                    data: [totalEntrada, totalSaida], // Dados dinâmicos passados pelo PHP
                    backgroundColor: ['#4CAF50', '#FF5722'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 70,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

    } else {
        console.error('Elemento gráfico não encontrado!');
    }
});
