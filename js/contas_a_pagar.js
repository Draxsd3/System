// Função para abrir o modal de edição com os dados da conta selecionada
function editAccount(id) {
    $.ajax({
        url: 'fetch_conta.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            const conta = JSON.parse(response);
            $('#idEdit').val(conta.id);
            $('#descricaoEdit').val(conta.descricao);
            $('#valorEdit').val(conta.valor);
            $('#data_vencimentoEdit').val(conta.data_vencimento);
            $('#editAccountModal').modal('show');
        },
        error: function() {
            alert('Erro ao buscar os dados da conta.');
        }
    });
}

// Função para excluir uma conta
function deleteAccount(id) {
    if (confirm('Tem certeza que deseja excluir esta conta?')) {
        $.ajax({
            url: 'contas_a_pagar.php',
            type: 'POST',
            data: { action: 'delete', id: id },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#conta-' + id).remove(); // Remove a linha da tabela
                    alert('Conta excluída com sucesso!');
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('Erro ao excluir a conta.');
            }
        });
    }
}
