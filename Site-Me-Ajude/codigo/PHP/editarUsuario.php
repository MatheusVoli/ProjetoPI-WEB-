<?php
header('Content-Type: application/json'); // Define o cabeçalho da resposta como JSON
include 'db_connection.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o parâmetro 'cpf' foi fornecido na URL
if (isset($_GET['cpf'])) {
    $cpf = $_GET['cpf']; // Obtém o CPF da URL

    // Valida o CPF
    if (strlen($cpf) !== 11 || !is_numeric($cpf)) {
        echo json_encode(['erro' => 'CPF inválido']); // Retorna erro se o CPF for inválido
        exit;
    }

    // Consulta no banco de dados
    $sql = "SELECT nome, telefone, email, cidade, bairro, endereco, cep, estado 
            FROM usuarios 
            WHERE cpf = :cpf"; // Prepara a consulta para buscar o usuário pelo CPF
    $stmt = $conn->prepare($sql); // Prepara a instrução SQL
    $stmt->bindParam(':cpf', $cpf); // Faz o binding do parâmetro CPF
    $stmt->execute(); // Executa a consulta

    // Verifica se algum usuário foi encontrado
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do usuário
        echo json_encode($usuario); // Retorna os dados do usuário como JSON
    } else {
        echo json_encode(['erro' => 'Cadastro não encontrado']); // Retorna erro se não encontrar o usuário
    }
} elseif ((isset($_POST['excluirConta']) || isset($_POST['editarUsuario'])) && isset($_POST['cpf']) && isset($_POST['senha'])) {
    $cpf = $_POST['cpf']; // Obtém o CPF do formulário
    $senha = $_POST['senha']; // Obtém a senha do formulário

    // Valida o CPF
    if (strlen($cpf) !== 11 || !is_numeric($cpf)) {
        echo json_encode(['erro' => 'CPF inválido']); // Retorna erro se o CPF for inválido
        exit;
    }

    // Verifica se o usuário existe e obtém sua senha
    $sqlVerifica = "SELECT id, senha FROM usuarios WHERE cpf = :cpf"; // Inclui a senha na consulta
    $stmtVerifica = $conn->prepare($sqlVerifica);
    $stmtVerifica->bindParam(':cpf', $cpf);
    $stmtVerifica->execute();
    $usuario = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['erro' => 'Nenhum usuário encontrado com este CPF.']); // Retorna erro se não encontrar o usuário
        exit;
    }

    // Verifica se a senha está correta
    if ($senha !== $usuario['senha']) { // Compara a senha diretamente
        echo json_encode(['erro' => 'Senha incorreta.']); // Retorna erro se a senha estiver incorreta
        exit;
    }

    $idUsuario = $usuario['id'];

    // Excluir conta
    if (isset($_POST['excluirConta'])) {
        // Prepara a SQL statement para excluir as doações do usuário
        $sqlDeleteDoacoes = "DELETE FROM doacoes WHERE id_usuario = :id_usuario"; // Corrigido para 'id_usuario'
        $stmtDeleteDoacoes = $conn->prepare($sqlDeleteDoacoes);
        $stmtDeleteDoacoes->bindParam(':id_usuario', $idUsuario);

        // Executa a exclusão das doações
        if (!$stmtDeleteDoacoes->execute()) {
            echo json_encode(['erro' => 'Erro ao excluir as doações do usuário.']); // Retorna erro se falhar na exclusão das doações
            exit;
        }

        // Prepara a SQL statement para excluir o usuário
        $sqlDeleteUsuario = "DELETE FROM usuarios WHERE id = :id_usuario"; // Corrigido para 'id'
        $stmtDeleteUsuario = $conn->prepare($sqlDeleteUsuario);
        $stmtDeleteUsuario->bindParam(':id_usuario', $idUsuario);

        // Executa a exclusão do usuário
        if ($stmtDeleteUsuario->execute()) {
            echo json_encode(['sucesso' => 'Conta excluída com sucesso!']); // Retorna sucesso na exclusão da conta
        } else {
            echo json_encode(['erro' => 'Erro ao excluir a conta.']); // Retorna erro se falhar na exclusão da conta
        }
    }

    // Editar usuário
    if (isset($_POST['editarUsuario'])) {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $endereco = $_POST['endereco'];
        $cep = $_POST['cep'];
        $estado = $_POST['estado'];

        // Prepara a SQL statement para editar o usuário
        $sqlEditaUsuario = "UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email, cidade = :cidade, 
                        bairro = :bairro, endereco = :endereco, cep = :cep, estado = :estado 
                        WHERE id = :id"; // Corrigido para usar 'id'
        $stmtEditaUsuario = $conn->prepare($sqlEditaUsuario);
        $stmtEditaUsuario->bindParam(':nome', $nome);
        $stmtEditaUsuario->bindParam(':telefone', $telefone);
        $stmtEditaUsuario->bindParam(':email', $email);
        $stmtEditaUsuario->bindParam(':cidade', $cidade);
        $stmtEditaUsuario->bindParam(':bairro', $bairro);
        $stmtEditaUsuario->bindParam(':endereco', $endereco);
        $stmtEditaUsuario->bindParam(':cep', $cep);
        $stmtEditaUsuario->bindParam(':estado', $estado);
        $stmtEditaUsuario->bindParam(':id', $idUsuario);

        // Executa a atualização do usuário
        if ($stmtEditaUsuario->execute()) {
            echo json_encode(['sucesso' => 'Dados do usuário atualizados com sucesso!']); // Retorna sucesso na atualização
        } else {
            echo json_encode(['erro' => 'Erro ao atualizar os dados do usuário.']); // Retorna erro se falhar na atualização
        }
    }
} else {
    echo json_encode(['erro' => 'CPF ou senha não fornecidos']); // Retorna erro se o CPF ou senha não foram passados
}
?>
