<?php
class Setor {
    private $conn;
    private $table_name = "setor";

    public $id;
    public $evento_id;
    public $nome;
    public $capacidade;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT
                    s.id, s.evento_id, s.nome, s.capacidade
                FROM
                    " . $this->table_name . " s
                ORDER BY
                    s.nome";

        $result = $this->conn->query($query);

        return $result;
    }

    function readByEvento() {
        $query = "SELECT
                    s.id, s.evento_id, s.nome, s.capacidade
                FROM
                    " . $this->table_name . " s
                WHERE
                    s.evento_id = ?
                ORDER BY
                    s.nome";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->evento_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function create() {
        $query = "INSERT INTO
                    " . $this->table_name . "
                (evento_id, nome, capacidade)
                VALUES
                    (?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->evento_id = htmlspecialchars(strip_tags($this->evento_id));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->capacidade = htmlspecialchars(strip_tags($this->capacidade));

        $stmt->bind_param("isi", $this->evento_id, $this->nome, $this->capacidade);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>