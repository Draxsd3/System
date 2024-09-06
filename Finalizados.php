<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatórios Finalizados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="container">
    <h1 class="mt-4">Relatórios Finalizados</h1>
    <ul class="list-group mb-4">
        <?php
        $sql = "SELECT f.id, p.piloto, p.tecnico, p.data_voo, f.observacoes, f.data_finalizacao 
                FROM finalizados f 
                JOIN pre_voos p ON f.pre_voo_id = p.id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item'><strong>" . $row['piloto'] . "</strong> - " . $row['data_voo'] . " - " . $row['data_finalizacao'] . "<br><strong>Observações:</strong> " . $row['observacoes'] . "</li>";
            }
        } else {
            echo "<li class='list-group-item'>Nenhum relatório finalizado</li>";
        }
        ?>
    </ul>

    <a href="index.php" class="btn btn-secondary">Voltar</a>
</body>

</html>