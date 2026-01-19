<?php
class Local {
    private $conn;
    private $table_name = "local";

    public $id;
    public $endereco;
    public $capacidade_total;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, endereco, capacidade_total FROM " . $this->table_name . " ORDER BY endereco ASC";
        return $this->conn->query($query);
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " (endereco, capacidade_total) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $this->endereco = htmlspecialchars(strip_tags($this->endereco));
        $this->capacidade_total = htmlspecialchars(strip_tags($this->capacidade_total));
        $stmt->bind_param("si", $this->endereco, $this->capacidade_total);
        return $stmt->execute();
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET endereco = ?, capacidade_total = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->endereco = htmlspecialchars(strip_tags($this->endereco));
        $this->capacidade_total = htmlspecialchars(strip_tags($this->capacidade_total));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bind_param("sii", $this->endereco, $this->capacidade_total, $this->id);
        return $stmt->execute();
    }

    function delete() {
        // Verificar se existem eventos associados
        $check_query = "SELECT COUNT(*) as total FROM evento WHERE local_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bind_param("i", $this->id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['total'] > 0) {
            return false; // Não pode excluir pois há eventos
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>