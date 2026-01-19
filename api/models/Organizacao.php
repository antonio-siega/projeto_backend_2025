<?php
class Organizacao {
    private $conn;
    private $table_name = "organizacao";

    public $id;
    public $nome;
    public $contato;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, nome, contato FROM " . $this->table_name . " ORDER BY nome ASC";
        return $this->conn->query($query);
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " (nome, contato) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->contato = htmlspecialchars(strip_tags($this->contato));
        $stmt->bind_param("ss", $this->nome, $this->contato);
        return $stmt->execute();
    }
}
?>