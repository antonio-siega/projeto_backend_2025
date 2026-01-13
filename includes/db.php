<?php

class Conexao {
    private $host = 'localhost';
    private $db   = 'eventos_if';
    private $user = 'root';
    private $pass = '';
    
    public function getConexao() {
        try {
            $pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
         
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
        
            die("Erro crítico de conexão: " . $e->getMessage());
        }
    }
}
?>
