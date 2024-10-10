<?php
class Doacao {
    private $id_usuario;
    private $valor;
    private $data_doacao;
    private $descricao;

    public function __construct($id_usuario, $valor, $data_doacao, $descricao) {
        $this->id_usuario = $id_usuario;
        $this->valor = $valor;
        $this->data_doacao = $data_doacao;
        $this->descricao = $descricao;
    }

    public function salvar($conn) {
        $query = "INSERT INTO doacoes (id_usuario, valor, data_doacao, descricao) VALUES (:id_usuario, :valor, :data_doacao, :descricao)";
        $stmt = $conn->prepare($query);

        // Usando bindValue para associar valores
        $stmt->bindValue(':id_usuario', $this->id_usuario);
        $stmt->bindValue(':valor', $this->valor);
        $stmt->bindValue(':data_doacao', $this->data_doacao);
        $stmt->bindValue(':descricao', $this->descricao);

        return $stmt->execute();
    }
}

?>
