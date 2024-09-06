$(document).ready(function() {
    // Submeter o formulário de adicionar
    $('#formAdd').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'estoque.php',
            type: 'POST',
            data: $(this).serialize() + '&action=add',
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    location.reload(); // Recarregar a página para mostrar o novo produto
                } else {
                    alert('Erro: ' + result.message);
                }
            }
        });
    });

    // Submeter o formulário de editar
    $('#formEdit').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'estoque.php',
            type: 'POST',
            data: $(this).serialize() + '&action=edit',
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    location.reload(); // Recarregar a página para mostrar as mudanças
                } else {
                    alert('Erro: ' + result.message);
                }
            }
        });
    });
});

function editProduct(id) {
    $.ajax({
        url: 'estoque.php',
        type: 'GET',
        data: { action: 'fetch', id: id },
        success: function(response) {
            let product = JSON.parse(response);
            if (product) {
                $('#idEdit').val(product.id);
                $('#nomeEdit').val(product.nome);
                $('#quantidadeEdit').val(product.quantidade);
                $('#precoEdit').val(product.preco);
                $('#modalEdit').modal('show');
            } else {
                alert('Produto não encontrado.');
            }
        }
    });
}
