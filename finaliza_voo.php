<?php
include 'conexao.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabeçalho
    function Header()
    {
        // Logo
        $this->Image('images/logo.jpg', 10, 6, 30);
        // Fonte Arial negrito 15
        $this->SetFont('Arial', 'B', 15);
        // Move para a direita
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, utf8_decode('Relatório de Voo Finalizado'), 0, 1, 'C');
        // Linha de quebra
        $this->Ln(20);
    }

    // Rodapé
    function Footer()
    {
        // Posiciona a 1.5 cm do fim da página
        $this->SetY(-15);
        // Fonte Arial itálico 8
        $this->SetFont('Arial', 'I', 8);
        // Número da página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $observacoes = utf8_decode($_POST['observacoes']);

    $sql = "UPDATE pre_voos SET finalizado = 1 WHERE id = $id";
    $conn->query($sql);

    $sql = "INSERT INTO finalizados (pre_voo_id, observacoes) VALUES ('$id', '$observacoes')";
    $conn->query($sql);

    // Pega as informações do relatório pré-voo
    $sql = "SELECT * FROM pre_voos WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $piloto = utf8_decode($row['piloto']);
    $tecnico = utf8_decode($row['tecnico']);
    $data_voo = utf8_decode($row['data_voo']);
    $local = utf8_decode($row['local']);
    $detalhes = utf8_decode($row['detalhes']);

    // Gera o PDF do relatório finalizado
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Adicionar conteúdo
    $pdf->Cell(0, 10, utf8_decode("Piloto: $piloto"), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Técnico: $tecnico"), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Data do Voo: $data_voo"), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Local: $local"), 0, 1);
    $pdf->Ln(10); // Quebra de linha
    $pdf->MultiCell(0, 10, utf8_decode("Detalhes: $detalhes"));
    $pdf->Ln(10); // Outra quebra de linha
    $pdf->MultiCell(0, 10, utf8_decode("Observações: $observacoes"));

    // Salva o PDF
    $file_name = "relatorio_voo_finalizado_$id.pdf";
    $pdf->Output('F', $file_name);

    echo "<div class='alert alert-success mt-4'>Relatório finalizado! <a href='$file_name' target='_blank'>Imprimir PDF</a></div>";
}

$sql = "SELECT * FROM pre_voos WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Finalizar Relatório de Voo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="container">
    <h1 class="mt-4">Finalizar Relatório de Voo</h1>
    <p><strong>Piloto:</strong> <?= htmlspecialchars($row['piloto'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Técnico:</strong> <?= htmlspecialchars($row['tecnico'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Data do Voo:</strong> <?= htmlspecialchars($row['data_voo'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Local:</strong> <?= htmlspecialchars($row['local'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Detalhes:</strong> <?= htmlspecialchars($row['detalhes'], ENT_QUOTES, 'UTF-8') ?></p>

    <form action="finaliza_voo.php?id=<?= $id ?>" method="post">
        <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" id="observacoes" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Finalizar Relatório</button>
    </form>

    <a href="index.php" class="btn btn-secondary mt-4">Voltar</a>
</body>

</html>