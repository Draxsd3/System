<?php
session_start();
include 'conexao.php';

// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar se a solicitação é uma requisição AJAX para adicionar um produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = $_POST['nome'];
    $quantidade = intval($_POST['quantidade']);
    $preco = floatval($_POST['preco']);

    $sql = "INSERT INTO produtos (nome, quantidade, preco) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdi', $nome, $quantidade, $preco);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar produto: ' . $conn->error]);
    }
    exit();
}

// Verificar se a solicitação é uma requisição AJAX para editar um produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $nome = $_POST['nome'];
    $quantidade = intval($_POST['quantidade']);
    $preco = floatval($_POST['preco']);

    $sql = "UPDATE produtos SET nome = ?, quantidade = ?, preco = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sidi', $nome, $quantidade, $preco, $id); // Corrigido: nome (s), quantidade (i), preco (d), id (i)

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar produto: ' . $conn->error]);
    }
    exit();
}

// Verificar se a solicitação é uma requisição AJAX para buscar um produto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto = $result->fetch_assoc();

    if ($produto) {
        echo json_encode($produto);
    } else {
        echo json_encode(null);
    }
    exit();
}

// Remover produto
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Produto excluído com sucesso!';
        header("Location: estoque.php");
        exit();
    } else {
        $_SESSION['message'] = 'Erro ao excluir produto: ' . htmlspecialchars($conn->error);
        header("Location: estoque.php");
        exit();
    }
}

// Obter lista de produtos
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/estoque.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Estilo para reduzir a largura da coluna de ações e ajustar os botões */
        .table .col-actions {
            width: 150px;
            /* Define uma largura fixa suficiente para os ícones e texto */
        }

        .table .btn {
            padding: 4px 8px;
            /* Reduz o padding dos botões */
            font-size: 0.85rem;
            /* Diminui o tamanho da fonte */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Exibir Mensagem Flash -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Controle de Estoque</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Conteúdo da página -->
    <div class="container mt-5">
        <h1>Controle de Estoque</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAdd" title="Cadastrar Novo Produto">
            <i class="fas fa-plus"></i> Adicionar Produto
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th class="col-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                        <td>R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                        <td class="col-actions">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-warning btn-sm button-custom-height mr-1" onclick="editProduct(<?php echo $row['id']; ?>)" title="Editar">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                                <a href="estoque.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm button-custom-height" title="Excluir" onclick="return confirm('Tem certeza que deseja remover este produto?')">
                                    <i class="fas fa-trash mr-1"></i> Excluir
                                </a>
                            </div>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Botão Voltar -->
        <a href="index.php" class="btn btn-secondary mt-4">
            <i class="fas fa-home"></i> Voltar ao Início
        </a>
    </div>

    <!-- Modal para Adicionar Produto -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Adicionar Novo Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdd">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidade">Quantidade:</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>
                        <div class="form
                        <div class=" form-group">
                            <label for="preco">Preço:</label>
                            <input type="text" class="form-control" id="preco" name="preco" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Produto -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Editar Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdit">
                        <input type="hidden" id="idEdit" name="id">
                        <div class="form-group">
                            <label for="nomeEdit">Nome:</label>
                            <input type="text" class="form-control" id="nomeEdit" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="quantidadeEdit">Quantidade:</label>
                            <input type="number" class="form-control" id="quantidadeEdit" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="precoEdit">Preço:</label>
                            <input type="text" class="form-control" id="precoEdit" name="preco" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto bg-dark text-white text-center py-3">
        &copy; 2024 Renan Ramos
    </footer>

    <!-- Scripts -->
    <script src="js/estoque.js"></script>
</body>

</html>