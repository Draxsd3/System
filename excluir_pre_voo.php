<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Excluir o relatório pré-voo
    $sql = "DELETE FROM pre_voos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-4'>Relatório pré-voo excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger mt-4'>Erro ao excluir relatório: " . $conn->error . "</div>";
    }

    $stmt->close();
    $conn->close();

    // Redirecionar de volta para a página inicial
    header('Location: index.php');
    exit();
}
?>