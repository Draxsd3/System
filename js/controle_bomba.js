var ctx = document.getElementById('graficoOperacoes').getContext('2d');
var graficoOperacoes = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Entrada', 'Saída'],
        datasets: [{
            label: 'Quantidade (litros)',
            data: [dataEntrada, dataSaida],
            backgroundColor: ['#007bff', '#dc3545'],
            borderColor: ['#0056b3', '#c82333'],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Esconder a mensagem após 1 segundo
setTimeout(function () {
    var mensagem = document.getElementById('mensagem');
    if (mensagem) {
        mensagem.style.display = 'none';
    }
}, 1000);
