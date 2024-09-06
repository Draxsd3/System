<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Excluir registros dependentes na tabela 'finalizados'
    $sql = "DELETE FROM finalizados WHERE pre_voo_id = $id";
    if ($conn->query($sql) === TRUE) {
        // Excluir o relatório da tabela 'pre_voos' após remover os registros dependentes
        $sql = "DELETE FROM pre_voos WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Relatório excluído com sucesso!'); window.location.href = 'repositorio_relatorios.php';</script>";
        } else {
            echo "Erro ao excluir o relatório: " . $conn->error;
        }
    } else {
        echo "Erro ao excluir registros dependentes: " . $conn->error;
    }
} else {
    echo "ID do relatório não fornecido.";
}
?>
