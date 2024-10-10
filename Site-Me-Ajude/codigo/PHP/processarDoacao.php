<?php
include 'db_connection.php'; // Inclua sua conexão com o banco de dados
include 'doacao.php'; // Inclua a classe Doacao

// Recebendo os dados via POST
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$valor = isset($_POST['valor']) ? $_POST['valor'] : '';
$dataDoacao = date('Y-m-d H:i:s'); // Definindo a data da doação como a data atual
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';


// Verificando se o e-mail e CPF existem no banco de dados
$query = "SELECT id FROM usuarios WHERE email = :email AND cpf = :cpf"; // Alterado para 'id'
$stmt = $conn->prepare($query);

// Usando bindValue para associar valores
$stmt->bindValue(':email', $email);
$stmt->bindValue(':cpf', $cpf);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificando se o usuário foi encontrado
if ($result) {
    $idUsuario = $result['id']; // Obtendo o ID do usuário

    // Criando um objeto da classe Doacao
    $doacao = new Doacao($idUsuario, $valor, $dataDoacao, $descricao);

    // Chamando o método para salvar a doação no banco
    if ($doacao->salvar($conn)) {
        echo "<script>
                alert('Doação registrada com sucesso!');
                window.location.href = '../HTML/inicioSessao.html'; // Redireciona para a página de doações
              </script>";
    } else {
        echo "<script>
                alert('Erro ao registrar doação.');
                window.location.href = '../HTML/inicioSessao.html'; // Redireciona para o formulário de doação
              </script>";
    }
} else {
    echo "<script>
            alert('Usuário não encontrado. Verifique seu CPF e e-mail.');
            window.location.href = '../HTML/Doacao.html'; // Redireciona para o formulário de doação
          </script>";
}

$conn = null; // Fecha a conexão com o banco de dados
?>
