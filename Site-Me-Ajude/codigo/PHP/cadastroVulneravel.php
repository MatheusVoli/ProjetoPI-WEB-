<?php

include 'db_connection.php';

// Verificar se o formulário foi enviado
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    // Coletar os dados do formulário
    $nome = $conn->real_escape_string( $_POST[ 'nome' ] );
    $cpf = $conn->real_escape_string( $_POST[ 'cpf' ] );
    $dataNascimento = $conn->real_escape_string( $_POST[ 'dataNascimento' ] );
    $necessidades = $conn->real_escape_string( $_POST[ 'observacoes' ] );
    $programas = isset( $_POST[ 'programas' ] ) ? implode( ', ', $_POST[ 'programas' ] ) : '';

    // Tratamento do arquivo de foto
    if ( isset( $_FILES[ 'foto' ] ) && $_FILES[ 'foto' ][ 'error' ] == 0 ) {
        $foto = $_FILES[ 'foto' ][ 'name' ];
        $foto_tmp = $_FILES[ 'foto' ][ 'tmp_name' ];
        $destino = 'uploads/' . $foto;
        // Pasta onde a imagem será armazenada

        // Mover o arquivo para a pasta de destino
        if ( move_uploaded_file( $foto_tmp, $destino ) ) {
            echo 'Foto carregada com sucesso!';
        } else {
            echo 'Falha ao carregar a foto!';
        }
    } else {
        $destino = null;
        // Se não houver foto, deixe vazio
    }

    // Comando SQL para inserir os dados no banco
    $sql = "INSERT INTO vulneraveis (nome_completo, cpf, data_nascimento, programas_sociais, necessidades, foto) 
            VALUES ('$nome', '$cpf', '$dataNascimento', '$programas', '$necessidades', '$destino')";

    // Executar a query e verificar se foi bem-sucedida
    if ( $conn->query( $sql ) === TRUE ) {
        echo 'Novo registro inserido com sucesso!';
    } else {
        echo 'Erro: ' . $sql . '<br>' . $conn->error;
    }

    // Comando SQL para atualizar os dados no banco
    $sql = "UPDATE vulneraveis SET nome_completo='$nome', cpf='$cpf', data_nascimento='$dataNascimento', 
        programas_sociais='$programas', necessidades='$necessidades', foto='$destino' 
        WHERE id='$id'";

    // Executar a query e verificar se foi bem-sucedida
    if ( $conn->query( $sql ) === TRUE ) {
        echo 'Registro atualizado com sucesso!';
    } else {
        echo 'Erro ao atualizar o registro: ' . $conn->error;
    }

    // Comando SQL para deletar um registro no banco
    $sql = "DELETE FROM vulneraveis WHERE id='$id'";

    // Executar a query e verificar se foi bem-sucedida
    if ( $conn->query( $sql ) === TRUE ) {
        echo 'Registro deletado com sucesso!';
    } else {
        echo 'Erro ao deletar o registro: ' . $conn->error;
    }

}

// Fechar a conexão
$conn->close();
?>