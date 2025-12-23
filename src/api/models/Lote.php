<?php
class Lote {
    private $conn;
    private $table_name = "lote";

    public $id;
    public $setor_id;
    public $preco;
    public $periodo_vigencia_ini;
    public $periodo_vigencia_fim;
    public $limite;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT
                    l.id, l.setor_id, l.preco, l.periodo_vigencia_ini, l.periodo_vigencia_fim, l.limite, l.status
                FROM
                    " . $this->table_name . " l
                ORDER BY
                    l.periodo_vigencia_ini";

        $result = $this->conn->query($query);

        return $result;
    }

    function readBySetor() {
        $query = "SELECT
                    l.id, l.setor_id, l.preco, l.periodo_vigencia_ini, l.periodo_vigencia_fim, l.limite, l.status
                FROM
                    " . $this->table_name . " l
                WHERE
                    l.setor_id = ?
                ORDER BY
                    l.periodo_vigencia_ini";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->setor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function create() {
        $query = "INSERT INTO
                    " . $this->table_name . "
                (setor_id, preco, periodo_vigencia_ini, periodo_vigencia_fim, limite, status)
                VALUES
                    (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->setor_id = htmlspecialchars(strip_tags($this->setor_id));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->periodo_vigencia_ini = htmlspecialchars(strip_tags($this->periodo_vigencia_ini));
        $this->periodo_vigencia_fim = htmlspecialchars(strip_tags($this->periodo_vigencia_fim));
        $this->limite = htmlspecialchars(strip_tags($this->limite));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bind_param("idssis", $this->setor_id, $this->preco, $this->periodo_vigencia_ini, $this->periodo_vigencia_fim, $this->limite, $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>