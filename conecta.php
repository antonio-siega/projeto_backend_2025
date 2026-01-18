<?php

class Conexao {
	public $host = 'localhost';
    public $db   = 'eventos_if';
    public $user = 'root';
    public $pass = '';

    
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

	$objConexao = new Conexao();

	//conexão com o servidor mysql
	//Dados para acesso
	$servidor    = $objConexao->host;
	$usuario     = $objConexao->user;
	$senha       = $objConexao->pass;
	$nomedobanco = $objConexao->db;

	//Conexão com o servidor (SGBD)
	$bancodedados = mysqli_connect($servidor,$usuario,$senha);
	
	//seleção do banco de dados
	mysqli_select_db($bancodedados,$nomedobanco);


?>