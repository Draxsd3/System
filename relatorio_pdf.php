<?php
include 'conexao.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabeçalho do PDF
    function Header()
    {
        $this->Image('./images/logo.jpg', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, utf8_decode('Relatório de Voo'), 0, 1, 'C');
        $this->Ln(20);
    }

    // Rodapé do PDF
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Verificar se o ID do relatório foi passado pela URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consultar o relatório no banco de dados
    $sql = "SELECT * FROM pre_voos WHERE id = $id AND finalizado = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Criar o PDF
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Adicionar conteúdo ao PDF
        $pdf->Cell(0, 10, utf8_decode("Piloto: " . $row['piloto']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Técnico: " . $row['tecnico']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Data do Voo: " . $row['data_voo']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Local: " . $row['local']), 0, 1);
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, utf8_decode("Detalhes: " . $row['detalhes']));
        $pdf->Ln(10);

        // Exibir o PDF
        $pdf->Output();
    } else {
        echo "Relatório não encontrado ou não finalizado.";
    }
} else {
    echo "ID do relatório não fornecido.";
}
?>
