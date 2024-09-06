<?php
include 'conexao.php';

// Consulta para obter o histórico completo de operações
$sql_historico = "SELECT tipo_operacao, quantidade, data_hora FROM controle_bomba ORDER BY data_hora DESC";
$result_historico = $conn->query($sql_historico);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Repositório de Operações</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/repositorio_op.css">
    <style>

    </style>
</head>

<body>
    <!-- Navbar fixa -->
    <div class="navbar-fixed bg-dark text-white py-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <img src="images/logo.jpg" alt="Logo" width="50" height="50">
                </div>
                <div class="col-8 d-flex align-items-center justify-content-center">
                    <h1 class="mb-0 text-center">Repositório de Operações</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Tipo de Operação</th>
                        <th>Quantidade (litros)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_historico->num_rows > 0) {
                        while ($row = $result_historico->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . date('d/m/Y H:i:s', strtotime($row['data_hora'])) . "</td>
                                    <td>" . ucfirst($row['tipo_operacao']) . "</td>
                                    <td>" . $row['quantidade'] . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhuma operação registrada</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
        </div>
       
        <div class="col-1 d-flex align-items-center justify-content-end">
        <a href="controle_bomba.php" class="btn btn-secondary mt-3">Voltar  </a>
        </div>
    </div>


    <!-- Footer fixo -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Sistema de Controle de Aviação Agrícola. Todos os direitos reservados.</p>
    </footer>

    <?php
    $conn->close(); // Fecha a conexão com o banco de dados
    ?>
</body>

</html>