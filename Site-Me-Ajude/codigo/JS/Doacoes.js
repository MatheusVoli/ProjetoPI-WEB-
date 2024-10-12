// Função para exibir mensagem de erro
function exibirMensagemErro(mensagem) {
    const divErro = document.getElementById('mensagemErro');
    // Atualiza a div com a mensagem de erro
    divErro.innerHTML = `<div class="alert alert-danger">${mensagem}</div>`;
    
    // Remove a mensagem após 5 segundos
    setTimeout(() => {
        divErro.innerHTML = '';
    }, 5000);
}

// Função para enviar requisição AJAX ao buscar doações
document.getElementById('formBuscarDoacoes').addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o envio padrão do formulário

    const cpf = document.getElementById('cpf').value; // Obtém o CPF do campo de entrada

    // Exibe um alerta se o CPF estiver vazio
    if (!cpf) {
        exibirMensagemErro('Por favor, insira um CPF.');
        return; // Interrompe o envio se o CPF não foi fornecido
    }

    // Requisição AJAX para buscar doações
    const xhr = new XMLHttpRequest(); // Cria um novo objeto XMLHttpRequest
    xhr.open('POST', '../PHP/buscarDoacoes.php', true); // Define a URL do arquivo PHP
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); // Define o tipo de conteúdo

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Atualiza a div com a tabela de doações retornada pelo PHP
            document.getElementById('historicoDoacoes').innerHTML = xhr.responseText;
        } else {
            exibirMensagemErro('Erro ao buscar doações. Tente novamente mais tarde.');
        }
    };

    // Envia o CPF como dado da requisição
    xhr.send('cpf=' + encodeURIComponent(cpf)); // Codifica o CPF para evitar problemas com caracteres especiais
});
