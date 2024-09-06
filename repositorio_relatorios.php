<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatórios Emitidos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/repositorio_relatorio.css">

    <style>

    </style>
</head>

<body>
    <!-- Barra de título com logo (navbar fixa) -->
    <div class="navbar-fixed bg-dark text-white py-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <img src="images/logo.jpg" alt="Logo" width="50" height="50">
                </div>
                <div class="col-8 d-flex align-items-center justify-content-center">
                    <h1 class="mb-0 text-center">Relatórios Emitidos</h1>
                </div>
                <div class="col-2 d-flex align-items-center justify-content-end">
                    <!-- Deixando a coluna vazia para manter o layout uniforme -->
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="container content mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Relatórios de Voos Finalizados</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php
                    $sql = "SELECT * FROM pre_voos WHERE finalizado = 1 ORDER BY data_voo DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                            echo "<span>" . $row['piloto'] . " - " . $row['data_voo'] . " - " . $row['local'] . "</span>";
                            echo "<div>";
                            echo "<a href='relatorio_pdf.php?id=" . $row['id'] . "' class='btn btn-secondary btn-sm mr-2' target='_blank'>Visualizar PDF</a>";
                            echo "<a href='excluir_relatorio.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Tem certeza que deseja excluir este relatório?');\">";
                            echo "<img src='https://img.icons8.com/ios-glyphs/20/ffffff/trash.png' alt='Excluir'>";
                            echo " Excluir";
                            echo "</a>";
                            echo "</div>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li class='list-group-item'>Nenhum relatório finalizado encontrado</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Botão Voltar ao Início (lado inferior esquerdo) -->
        <a href="pre_voo.php" class="btn btn-secondary mt-4">Voltar ao Início</a>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Sistema de Controle de Aviação Agrícola. Todos os direitos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
