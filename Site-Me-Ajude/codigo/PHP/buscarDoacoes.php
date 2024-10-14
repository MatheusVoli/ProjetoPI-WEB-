<?php
// Inclui o arquivo de conexão com o banco de dados
include 'db_connection.php';

// Recebe o CPF do usuário via POST e remove qualquer formatação (como pontos ou traços)
$cpfUsuario = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';

// Verifica se o CPF foi fornecido
if (empty($cpfUsuario)) {
    // Se o CPF não foi fornecido ou é inválido, retorna uma mensagem de erro
    echo "<div class='alert alert-danger'>CPF não fornecido ou inválido.</div>";
    exit(); // Interrompe a execução do script
}

// Consulta SQL para buscar doações associadas ao CPF
$queryDoacoes = "
    SELECT d.* 
    FROM doacoes d
    JOIN usuarios u ON d.id_usuario = u.id
    WHERE u.cpf = :cpf"; // Faz a junção entre a tabela de doações e usuários usando o CPF

try {
    // Prepara a consulta SQL
    $stmtDoacoes = $conn->prepare($queryDoacoes);
    // Vincula o CPF à consulta
    $stmtDoacoes->bindValue(':cpf', $cpfUsuario);
    // Executa a consulta
    $stmtDoacoes->execute();
    // Busca todos os resultados
    $doacoes = $stmtDoacoes->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se o usuário possui doações registradas
    if ($doacoes && count($doacoes) > 0) {
        // Se houver doações, cria a tabela de doações
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Valor</th>
                        <th>Data da Doação</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>";

        // Percorre cada doação e exibe os detalhes
        foreach ($doacoes as $doacao) {
            echo "<tr>
                    <td>{$doacao['id']}</td>
                    <td>{$doacao['valor']}</td>
                    <td>{$doacao['data_doacao']}</td>
                    <td>{$doacao['descricao']}</td>
                  </tr>";
        }

        // Fecha a tabela
        echo "</tbody></table>";
    } else {
        // Se não houver doações, exibe uma mensagem informativa
        echo "<div class='alert alert-warning'>Nenhuma doação encontrada para este CPF.</div>";
    }

} catch (PDOException $e) {
    // Exibe mensagem de erro caso ocorra uma exceção
    echo "<div class='alert alert-danger'>Erro ao buscar doações: " . $e->getMessage() . "</div>";
}

// Fecha a conexão com o banco de dados
$conn = null;
?>
