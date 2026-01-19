<?php
	//conexão com o servidor mysql
	//Dados para acesso
	$servidor = "localhost";
	$usuario = "root";
	$senha = "";
	$nomedobanco = "eventos_if";

	//Conexão com o servidor (SGBD)
	$bancodedados = mysqli_connect($servidor,$usuario,$senha);
	
	//seleção do banco de dados
	mysqli_select_db($bancodedados,$nomedobanco);

?>