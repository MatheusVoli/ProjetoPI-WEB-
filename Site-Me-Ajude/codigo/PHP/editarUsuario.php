<?php
// Inclua o arquivo de conexão com o banco de dados
include_once('db_connection.php');

// Verifique se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados do formulário
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $endereco = $_POST['endereco'];
    $estado = $_POST['estado'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Aqui você pode adicionar a lógica para verificar a senha atual se necessário

    // Atualizar os dados no banco de dados
    $sql = "UPDATE usuarios SET cep=?, cidade=?, bairro=?, endereco=?, estado=?, telefone=?, email=?, senha=? WHERE id_usuario=?";
    
    // Preparar o comando para execução
    if ($stmt = $conn->prepare($sql)) {
        // Vincular os parâmetros
        $stmt->bind_param("ssssssssi", $cep, $cidade, $bairro, $endereco, $estado, $telefone, $email, password_hash($senha, PASSWORD_DEFAULT), $id_usuario);
        
        // ID do usuário a ser editado (pode ser passado via GET ou SESSION)
        $id_usuario = $_SESSION['id_usuario']; // ou use GET como $_GET['id_usuario']
        
        // Executar o comando
        if ($stmt->execute()) {
            echo "Usuário atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar usuário: " . $conn->error;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        echo "Erro ao preparar comando: " . $conn->error;
    }

    // Fechar a conexão
    $conn->close();
}
?>
