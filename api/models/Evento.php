<?php
class Evento {
    private $conn;
    private $table_name = "evento";

    public $id;
    public $nome;
    public $descricao;
    public $imagem_url;
    public $data_inicio;
    public $data_fim;
    public $local_id;
    public $organizacao_id;
    public $status;
    public $politica_cancelamento;


    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT e.id, e.nome, e.descricao, e.imagem_url, e.data_inicio, e.data_fim, e.local_id, e.politica_cancelamento, l.endereco, e.organizacao_id, e.status
                  FROM " . $this->table_name . " e
                  LEFT JOIN local l ON e.local_id = l.id
                  ORDER BY e.data_inicio DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, imagem_url, data_inicio, data_fim, organizacao_id, local_id, status, politica_cancelamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->imagem_url = htmlspecialchars(strip_tags($this->imagem_url));
        $this->data_inicio = htmlspecialchars(strip_tags($this->data_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($this->data_fim));
        $this->organizacao_id = htmlspecialchars(strip_tags($this->organizacao_id));
        $this->local_id = htmlspecialchars(strip_tags($this->local_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->politica_cancelamento = htmlspecialchars(strip_tags($this->politica_cancelamento));

        $stmt->bind_param("sssssiiss", $this->nome, $this->descricao, $this->imagem_url, $this->data_inicio, $this->data_fim, $this->organizacao_id, $this->local_id, $this->status, $this->politica_cancelamento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET nome = ?, descricao = ?, data_inicio = ?, data_fim = ?, local_id = ?, politica_cancelamento = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->data_inicio = htmlspecialchars(strip_tags($this->data_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($this->data_fim));
        $this->local_id = htmlspecialchars(strip_tags($this->local_id));
        $this->politica_cancelamento = htmlspecialchars(strip_tags($this->politica_cancelamento));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("ssssisi", $this->nome, $this->descricao, $this->data_inicio, $this->data_fim, $this->local_id, $this->politica_cancelamento, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>
