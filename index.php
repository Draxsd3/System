<?php
include 'conexao.php';

// Obtém o total de entradas e saídas
$sql_entradas = "SELECT SUM(quantidade) AS total_entrada FROM controle_bomba WHERE tipo_operacao = 'entrada'";
$result_entradas = $conn->query($sql_entradas);
$total_entrada = $result_entradas->fetch_assoc()['total_entrada'] ?? 0;

$sql_saidas = "SELECT SUM(quantidade) AS total_saida FROM controle_bomba WHERE tipo_operacao = 'saida'";
$result_saidas = $conn->query($sql_saidas);
$total_saida = $result_saidas->fetch_assoc()['total_saida'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Bomba</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Sistema de Controle</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="pre_voo.php" title="Relatórios">
                            <img width="50" height="50" src="./images/relatorio.png" alt="graph-report" />
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="controle_bomba.php" title="Controle de Bomba">
                            <img width="50" height="50" src="./images/gasolina.png" alt="gas-station" />
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estoque.php" title="Controle de Estoque">
                            <img width="50" height="50" src="./images/estoque.png" alt="move-by-trolley" />
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="financeiro.php" title="Relatórios">
                            <img width="50" height="50" src="./images/financeiro.png" alt="graph-report" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container-fluid mt-4 d-flex flex-column align-items-center">
        <!-- Container de informações com fundo fechado -->
        <div class="info-container w-100">
            <div class="row">
                <!-- Seção à Direita: Gráfico de Percentual -->
                <div class="col-md-6 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <h3 class="card-title">Status da Bomba de Álcool</h3>
                            <canvas id="graficoPercentual" style="max-width: 100%; height: auto;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Seção à Esquerda: Relatórios em Aberto -->
                <div class="col-md-6 mb-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <h3 class="card-title">Relatórios em Aberto</h3>
                            <ul class="list-group" style="max-height: 300px; overflow-y: auto;">
                                <?php
                                $sql = "SELECT * FROM pre_voos WHERE finalizado = 0";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<li class='list-group-item'><a href='finaliza_voo.php?id=" . $row['id'] . "'>" . $row['piloto'] . " - " . $row['data_voo'] . "</a></li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item'>Nenhum relatório pendente</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto bg-dark text-white text-center py-3">
        &copy; 2024 Renan Ramos
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Passa os dados de PHP para JavaScript
        const totalEntrada = <?= $total_entrada ?>;
        const totalSaida = <?= $total_saida ?>;
    </script>
    <script src="js/index.js"></script>

    <?php
    $conn->close(); // Fecha a conexão com o banco de dados no final do script
    ?>
</body>

</html>
