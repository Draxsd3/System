<?php
session_start();
include 'conexao.php'; // Inclui o arquivo de conexão com MySQLi

// Verificar se o formulário foi enviado para adicionar uma conta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data_vencimento = $_POST['data_vencimento'];

    // Preparar e executar a inserção usando MySQLi
    $sql = "INSERT INTO contas_a_pagar (descricao, valor, data_vencimento, status) VALUES (?, ?, ?, 'Pendente')";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sds', $descricao, $valor, $data_vencimento);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Conta registrada com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao registrar a conta.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Erro ao preparar a consulta: " . $conn->error;
    }
}

// Verificar se o formulário foi enviado para editar uma conta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data_vencimento = $_POST['data_vencimento'];

    // Atualizar a conta no banco de dados
    $sql = "UPDATE contas_a_pagar SET descricao = ?, valor = ?, data_vencimento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sdsi', $descricao, $valor, $data_vencimento, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Conta atualizada com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao atualizar a conta.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Erro ao preparar a consulta: " . $conn->error;
    }
}

// Verificar se a solicitação é para excluir uma conta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];

    // Excluir a conta do banco de dados
    $sql = "DELETE FROM contas_a_pagar WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir a conta.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar a consulta: ' . $conn->error]);
    }
    exit();
}

// Inicializar a variável $contas como um array vazio
$contas = [];

// Buscar todas as contas a pagar
$sql = "SELECT * FROM contas_a_pagar ORDER BY data_vencimento ASC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas a Pagar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Contas a Pagar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Botão Adicionar Conta -->
    <div class="container mt-3">
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addAccountModal">
            <i class="fas fa-plus-circle"></i> Adicionar Nova Conta
        </a>
    </div>

    <div class="container mt-4">
        <!-- Mensagem de sucesso ou erro -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Lista de todas as contas a pagar -->
        <h2>Todas as Contas a Pagar</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data de Vencimento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($contas) > 0): ?>
                    <?php foreach ($contas as $conta): ?>
                        <tr id="conta-<?php echo $conta['id']; ?>">
                            <td><?php echo htmlspecialchars($conta['descricao']); ?></td>
                            <td>R$ <?php echo number_format($conta['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($conta['data_vencimento']); ?></td>
                            <td><?php echo htmlspecialchars($conta['status']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editAccount(<?php echo $conta['id']; ?>)">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAccount(<?php echo $conta['id']; ?>)">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma conta cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para adicionar conta -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">Adicionar Nova Conta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="number" id="valor" name="valor" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="data_vencimento">Data de Vencimento</label>
                            <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Conta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar conta -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">Editar Conta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formEdit">
                        <input type="hidden"
                            <input type="hidden" name="action" value="edit">
                        <input type="hidden" id="idEdit" name="id">
                        <div class="form-group">
                            <label for="descricaoEdit">Descrição</label>
                            <input type="text" id="descricaoEdit" name="descricao" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="valorEdit">Valor</label>
                            <input type="number" id="valorEdit" name="valor" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="data_vencimentoEdit">Data de Vencimento</label>
                            <input type="date" id="data_vencimentoEdit" name="data_vencimento" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 fixed-bottom">
        &copy; 2024 Renan Ramos
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="js/contas_a_pagar.js"></script> <!-- Referência ao arquivo JavaScript externo -->
</body>

</html>