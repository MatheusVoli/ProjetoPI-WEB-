<?php
include 'db_connection.php'; // Inclua sua conexão com o banco de dados
include 'usuario.php'; // Inclua a classe Usuario

// Supondo que você tenha recebido os dados do formulário via POST
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$dataNascimento = $_POST['dataNascimento'];
$cidade = $_POST['cidade'];
$bairro = $_POST['bairro'];
$endereco = $_POST['endereco'];
$cep = $_POST['cep'];
$estado = $_POST['estado'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha']; // Senha em texto normal

// Verifica se o CPF tem 11 dígitos
if (strlen($cpf) !== 11 || !is_numeric($cpf)) {
    echo "<script>
            alert('CPF deve conter exatamente 11 dígitos numéricos.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
    exit;
}

// Validação do telefone
if (strlen($telefone) !== 11 || !is_numeric($telefone)) {
    echo "<script>
            alert('Telefone deve conter exatamente 11 dígitos numéricos.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
    exit;
}

// Validação do CEP
if (strlen($cep) !== 8 || !is_numeric($cep)) {
    echo "<script>
            alert('CEP deve conter exatamente 8 dígitos numéricos.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
    exit;
}

// Verifica a data de nascimento
$dataAtual = new DateTime();
$dataNascimentoObj = new DateTime($dataNascimento);
$anoNascimento = $dataNascimentoObj->format('Y');

if ($anoNascimento < 1908 || $dataAtual->diff($dataNascimentoObj)->y < 18) {
    echo "<script>
            alert('A data de nascimento deve ser válida e a pessoa deve ter mais de 18 anos.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
    exit;
}

// Verifica se o CPF ou e-mail já existem
$sqlVerifica = "SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf OR email = :email";
$stmtVerifica = $conn->prepare($sqlVerifica);
$stmtVerifica->bindParam(':cpf', $cpf);
$stmtVerifica->bindParam(':email', $email);
$stmtVerifica->execute();
$contador = $stmtVerifica->fetchColumn();

if ($contador > 0) {
    echo "<script>
            alert('CPF ou e-mail já estão cadastrados.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
    exit;
}

// Prepare a SQL statement
$sql = "INSERT INTO usuarios (nome, cpf, data_nascimento, cidade, bairro, endereco, cep, estado, telefone, email, senha) 
        VALUES (:nome, :cpf, :data_nascimento, :cidade, :bairro, :endereco, :cep, :estado, :telefone, :email, :senha)";

$stmt = $conn->prepare($sql);

// Bind os parâmetros
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':data_nascimento', $dataNascimento);
$stmt->bindParam(':cidade', $cidade);
$stmt->bindParam(':bairro', $bairro);
$stmt->bindParam(':endereco', $endereco);
$stmt->bindParam(':cep', $cep);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':telefone', $telefone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);

// Execute a consulta
if ($stmt->execute()) {
    echo "<script>
            alert('Cadastro realizado com sucesso!');
            window.location.href = '../HTML/entrar.html'; // Redireciona para a página de entrar
          </script>";
} else {
    echo "<script>
            alert('Erro ao cadastrar.');
            window.location.href = '../HTML/cadastro.html'; // Redireciona para a página de cadastro
          </script>";
}
?>
