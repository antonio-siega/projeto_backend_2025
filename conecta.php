<?php


/*
Este arquivo está estruturado dessa forma porque parte do projeto (os arquivos envolvendo pagamentos, ingressos e check-in) 
foi desenvolvida utilizando PDO. Se alterarmos a forma principal de conexão ou as referências dos arquivos, grande parte dos 
códigos deixaria de funcionar, o que exigiria refatorar vários arquivos do sistema.

Por isso, a classe Conexao foi mantida para atender o uso de PDO, e a partir dela reaproveitamos
os mesmos dados (host, banco, usuário e senha) para criar também a conexão via MySQLi.
Assim, garantimos compatibilidade com as partes do sistema que usam PDO e com as que ainda usam MySQLi,
sem precisar alterar o restante do projeto.

*/


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