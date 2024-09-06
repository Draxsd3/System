<?php
session_start(); // Inicializa a sessão
include 'conexao.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabeçalho
    function Header()
    {
        $this->Image('./images/logo.jpg', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, utf8_decode('Relatório Pré-Voo'), 0, 1, 'C');
        $this->Ln(20);
    }

    // Rodapé
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $piloto = utf8_decode($_POST['piloto']);
    $tecnico = utf8_decode($_POST['tecnico']);
    $data_voo = utf8_decode($_POST['data_voo']);
    $local = utf8_decode($_POST['local']);
    $detalhes = utf8_decode($_POST['detalhes']);

    $sql = "INSERT INTO pre_voos (piloto, tecnico, data_voo, local, detalhes) VALUES ('$piloto', '$tecnico', '$data_voo', '$local', '$detalhes')";

    if ($conn->query($sql) === TRUE) {
        $id = $conn->insert_id;

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(0, 10, utf8_decode("Piloto: $piloto"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Técnico: $tecnico"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Data do Voo: $data_voo"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Local: $local"), 0, 1);
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, utf8_decode("Detalhes: $detalhes"));
        $pdf->Ln(10);

        $file_name = "relatorio_pre_voo_$id.pdf";
        $pdf->Output('F', $file_name);

        $_SESSION['message'] = "Relatório pré-voo salvo com sucesso! <a href='$file_name' target='_blank'>Imprimir PDF</a>";
    } else {
        $_SESSION['message'] = "Erro ao salvar o relatório: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatórios Pré-Voo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/pre_voo.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Relatório Pré-Voo</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-2"> <!-- Ajustado para `mt-2` para reduzir o espaçamento superior -->
        <!-- Mensagem de sucesso ou erro -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['message']; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Coluna Esquerda: Emitir Novo Relatório -->
            <div class="col-md-6">
                <div class="card mt-2"> <!-- Ajustado para `mt-2` para reduzir o espaçamento superior -->
                    <div class="card-header bg-primary text-white">
                        Criar Novo Relatório Pré-Voo
                    </div>
                    <div class="card-body">
                        <form action="pre_voo.php" method="post">
                            <div class="form-group">
                                <label for="piloto">Piloto</label>
                                <input type="text" name="piloto" id="piloto" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="tecnico">Técnico</label>
                                <input type="text" name="tecnico" id="tecnico" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="data_voo">Data do Voo</label>
                                <input type="date" name="data_voo" id="data_voo" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="local">Local</label>
                                <input type="text" name="local" id="local" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="detalhes">Detalhes</label>
                                <textarea name="detalhes" id="detalhes" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Relatório</button>
                        </form>
                        <a href="index.php" class="btn btn-secondary mt-2">Voltar ao Início</a> <!-- Ajustado para `mt-2` -->
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Relatórios em Aberto -->
            <div class="col-md-6">
                <div class="card mt-2"> <!-- Ajustado para `mt-2` -->
                    <div class="card-header bg-success text-white">
                        Relatórios em Aberto
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php
                            $sql = "SELECT * FROM pre_voos WHERE finalizado = 0";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                    echo "<a href='finaliza_voo.php?id=" . $row['id'] . "' class='text-decoration-none'>" . $row['piloto'] . " - " . $row['data_voo'] . "</a>";
                                    echo "<span class='badge badge-primary badge-pill'>Pendente</span>";
                                    echo "</li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>Nenhum relatório pendente</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Botão para acessar o Repositório de Relatórios Emitidos -->
                <div class="card mt-2"> <!-- Ajustado para `mt-2` -->
                    <div class="card-body text-center">
                        <a href="repositorio_relatorios.php" class="btn btn-light">
                            <img src="https://img.icons8.com/ios-glyphs/30/000000/document.png" alt="Relatórios" class="mr-2">
                            Repositório de Relatórios Emitidos
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Sistema de Controle de Aviação Agrícola. Todos os direitos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
