<?php
// Incluindo a conexão com o banco de dados
include 'conexao.php';

// Buscar contas próximas do vencimento (exemplo: vencem nos próximos 7 dias)
$sql = "SELECT * FROM contas_a_pagar WHERE data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status = 'Pendente'";
$result = $conn->query($sql);

$contas_proximas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contas_proximas[] = $row;
    }
} else {
    $contas_proximas = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Financeiro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/financeiro.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Financeiro</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Botão de Contas a Pagar -->
    <div class="container mt-4">
        <a href="contas_a_pagar.php" class="btn btn-primary">
            <i class="fas fa-money-bill-wave"></i> Contas a Pagar
        </a>
    </div>

    <div class="container mt-4">
        <div class="row">
            <!-- Gráfico de Fluxo de Caixa -->
            <div class="col-md-6">
                <h2>Fluxo de Caixa</h2>
                <canvas id="fluxoCaixaChart"></canvas>

                <!-- Botão de Voltar ao Index -->
                <div class="mt-3">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar ao Início
                    </a>
                </div>
            </div>

            <!-- Contas a Pagar Próximas do Vencimento -->
            <div class="col-md-6">
                <h2>Contas a Pagar Próximas do Vencimento</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data de Vencimento</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($contas_proximas) > 0): ?>
                            <?php foreach ($contas_proximas as $conta): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($conta['descricao']); ?></td>
                                    <td>R$ <?php echo number_format($conta['valor'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($conta['data_vencimento']); ?></td>
                                    <td><?php echo htmlspecialchars($conta['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma conta próxima do vencimento.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <canvas id="fluxoCaixaChart"></canvas>

    <script>
        var entradas = [3000, 2000, 4000, 5000, 7000, 6000]; // Aqui você poderia passar valores do PHP, se necessário
        var saidas = [2000, 1500, 3500, 4000, 6500, 5000]; // Exemplo com valores fixos
    </script>

    <!-- Inclua o arquivo JavaScript -->
    <script src="js/financeiro.js"></script>


    <footer class="bg-dark text-white text-center py-3">
        &copy; 2024 Renan Ramos
    </footer>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>