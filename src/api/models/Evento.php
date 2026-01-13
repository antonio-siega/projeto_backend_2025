<?php
class Evento {
    private $conn;
    private $table_name = "evento";

    public $id;
    public $organizacao_id;
    public $local_id;
    public $nome;
    public $descricao;
    public $status;
    public $politica_cancelamento;
    public $data_inicio;
    public $data_fim;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT
                    e.id, e.organizacao_id, e.local_id, e.nome, e.descricao, e.status, e.politica_cancelamento, e.data_inicio, e.data_fim
                FROM
                    " . $this->table_name . " e
                ORDER BY
                    e.data_inicio DESC";

        $result = $this->conn->query($query);

        return $result;
    }

    function create() {
        $query = "INSERT INTO
                    " . $this->table_name . "
                (nome, organizacao_id, local_id, descricao, status, politica_cancelamento, data_inicio, data_fim)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->organizacao_id = htmlspecialchars(strip_tags($this->organizacao_id));
        $this->local_id = htmlspecialchars(strip_tags($this->local_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->politica_cancelamento = htmlspecialchars(strip_tags($this->politica_cancelamento));
        $this->data_inicio = htmlspecialchars(strip_tags($this->data_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($this->data_fim));

        $stmt->bind_param("siisssss", $this->nome, $this->organizacao_id, $this->local_id, $this->descricao, $this->status, $this->politica_cancelamento, $this->data_inicio, $this->data_fim);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>