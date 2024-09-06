<?php
include 'conexao.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_operacao = $_POST['tipo_operacao'];
    $quantidade = $_POST['quantidade'];
    $operador = $_POST['operador'];
    $observacoes = $_POST['observacoes'];

    // Inserção da operação com data e hora automáticas
    $sql = "INSERT INTO controle_bomba (tipo_operacao, quantidade, operador, observacoes, data_hora) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdss', $tipo_operacao, $quantidade, $operador, $observacoes);

    if ($stmt->execute()) {
        $mensagem = "<div id='mensagem' class='alert alert-success'>Operação registrada com sucesso!</div>";
    } else {
        $mensagem = "<div id='mensagem' class='alert alert-danger'>Erro ao registrar operação: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// Consulta para obter os dados para o gráfico
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mensal';

switch ($periodo) {
    case 'semanal':
        $intervalo = "INTERVAL 1 WEEK";
        break;
    case 'anual':
        $intervalo = "INTERVAL 1 YEAR";
        break;
    case 'mensal':
    default:
        $intervalo = "INTERVAL 1 MONTH";
        break;
}

$sql_grafico = "SELECT tipo_operacao, SUM(quantidade) AS total 
                FROM controle_bomba 
                WHERE data_hora >= DATE_SUB(NOW(), $intervalo)
                GROUP BY tipo_operacao";
$result_grafico = $conn->query($sql_grafico);

$data_entrada = 0;
$data_saida = 0;

if ($result_grafico->num_rows > 0) {
    while ($row = $result_grafico->fetch_assoc()) {
        if ($row['tipo_operacao'] == 'entrada') {
            $data_entrada = $row['total'];
        } elseif ($row['tipo_operacao'] == 'saida') {
            $data_saida = $row['total'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Controle da Bomba de Álcool</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/controle_bomba.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Controle Bomba de Alcool</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    </nav>

    <div class="container">
        <!-- Exibir mensagem de sucesso ou erro -->
        <?php echo $mensagem; ?>

        <div class="row-content mt-4">
            <!-- Coluna Esquerda: Formulário -->
            <div class="col-left">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Registrar Nova Operação
                    </div>
                    <div class="card-body">
                        <form action="controle_bomba.php" method="post">
                            <div class="form-group">
                                <label for="tipo_operacao">Tipo de Operação:</label>
                                <select name="tipo_operacao" id="tipo_operacao" class="form-control">
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="quantidade">Quantidade (litros):</label>
                                <input type="number" name="quantidade" id="quantidade" step="0.01" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="operador">Operador:</label>
                                <input type="text" name="operador" id="operador" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="observacoes">Observações:</label>
                                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
                        </form>
                        <a href="index.php" class="btn btn-secondary mt-4">Voltar ao Início</a>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Gráfico e Filtro de Período -->
            <div class="col-right">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Resumo das Operações
                    </div>
                    <div class="card-body">
                        <!-- Formulário de seleção de período -->
                        <form method="get" class="mb-4">
                            <div class="form-group">
                                <label for="periodo">Selecionar Período:</label>
                                <select name="periodo" id="periodo" class="form-control">
                                    <option value="semanal" <?php if ($periodo == 'semanal') echo 'selected'; ?>>Última Semana</option>
                                    <option value="mensal" <?php if ($periodo == 'mensal') echo 'selected'; ?>>Último Mês</option>
                                    <option value="anual" <?php if ($periodo == 'anual') echo 'selected'; ?>>Último Ano</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Atualizar Gráfico</button>
                        </form>
                        <canvas id="graficoOperacoes"></canvas>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <a href="repositorio_operacoes.php" class="btn btn-light">
                            <img src="https://img.icons8.com/ios-glyphs/30/000000/document.png" alt="Relatórios" class="mr-2">
                            Repositório de Operações
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer fixo -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Sistema de Controle de Aviação Agrícola. Todos os direitos reservados.</p>
    </footer>

    <script>
        var dataEntrada = <?php echo json_encode($data_entrada); ?>;
        var dataSaida = <?php echo json_encode($data_saida); ?>;
    </script>
    <script src="./js/controle_bomba.js"></script>

    <?php
    $conn->close(); // Fecha a conexão com o banco de dados
    ?>
</body>

</html>