<?php
	// Start the session
	session_start();
	/*
	if(!isset($_SESSION["comissario_id"]))
	{
		header("location: auth/auth.html");
		exit();
	}
	*/
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="tela_comssario">
	<link rel="icon" href="src/img/favicon.ico">
	
	<title>Tela do Comissário</title>
	<link rel="stylesheet" href="../styles/global.css" />
	<link rel="stylesheet" href="../styles/index.css"/>
	<link rel="stylesheet" href="../styles/lista_new.css"/>

	<style>
		dialog {
			margin: auto;
			position: fixed;
			top: 0%;
			left: 0%;
			border-width: 1px;
			border-radius: 0.8rem;
		}
	
		dialog::backdrop {
			background-color: rgba(0, 0, 0, 0.5);
		}
		
		.button:hover {
			cursor: pointer;
		}
		
		form.form {
			box-shadow: 0 0 0.4rem 0.2rem rgba(0, 0, 0, 0.1);
		}
	</style>
</head>

<?php
	include_once('../../conecta.php');
?>

<body>
	<div class="main">
		
		<div class="container">
			<header class="header" style="box-shadow: 0 0 0.6rem 0.4rem rgba(0, 0, 0, 0.1);">
				<h1 class="title">Dados do Comissário</h1>
				<i><button class="button title" onclick="modalLink.showModal()">Links (Comissão)</button></i>
			</header>
		</div>

		<div class="table-responsive">
			<p>Bem-vindo, Nome</p> <!-- pegar sessao_start() em auth.php -->
			<table>
				<thead>
				<tr>
					<th class="lista-titulo">Organização</th>
					<th class="lista-titulo">Dados</th>
					<th class="lista-titulo">Valor a receber</th><!--ver valor na tabela repasse_comissao--> 
					<th class="lista-titulo">Regra de comissão</th>
				</tr>
				</thead>
			<tbody>
				<?php
					$sql="SELECT comissario.*, organizacao.nome AS organiza, repasse_comissao.valor AS repasse FROM comissario, organizacao, repasse_comissao
					WHERE comissario.organizacao_id = organizacao.id AND repasse_comissao.comissario_id = comissario.id AND comissario.id = 1";
					//pegar $_SESSION['comissario_id'] em session_start()
					$resultado = mysqli_query($bancodedados, $sql);
					if ($linha = mysqli_fetch_array($resultado))
					{
						echo "<tr><td>".$linha['organiza']."</td>";
						echo "<td>".$linha['dados']."</td>";
						echo "<td>".$linha['repasse']."</td>";
						echo "<td>".$linha['regra_comissao']."</td></tr>";  
					}
				?>
			</tbody>  
			</table>
		</div>

		<dialog id="modalLink">
			<section class="form modal">
				<form method="POST" class="form" onsubmit="return redirecionar(event)" target="_self">
					<header class="modal-header">
						<h2 class="modal-title">Nova Venda (Comissão)</h2>
					</header>
					
					<div class="dropdown-wrapper">
						<span class="dropdown-label">Evento</span>
						<select id="evento" name="evento" required>
							<?php
								$sql = "SELECT * FROM evento";
								$resultado = mysqli_query($bancodedados,$sql);
								while($linha = mysqli_fetch_array($resultado))
								{
									echo "<option selected value='' hidden>Selecione</option>";  
									echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
								}
							?>  
						</select>
					</div>
				
					<div class="dropdown-wrapper">
						<span class="dropdown-label">Setor</span>
						<select id="setor" name="setor" required>
							<?php
								$sql = "SELECT * FROM setor";
								$resultado = mysqli_query($bancodedados,$sql);
								while($linha = mysqli_fetch_array($resultado))
								{
									echo "<option selected value='' hidden>Selecione</option>";  
									echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
								}
							?>  
						</select>
					</div>
				
					<div class="dropdown-wrapper">
						<span class="dropdown-label">Lote</span>
						<select id="lote" name="lote" required>
							<?php
								$sql = "SELECT * FROM lote";
								$resultado = mysqli_query($bancodedados,$sql);
								while($linha = mysqli_fetch_array($resultado))
								{
									echo "<option selected value='' hidden>Selecione</option>";  
									echo "<option value='".$linha['id']."'>"."R$ ".$linha['preco']."</option>";
								}
							?>  
						</select>
					</div>
				
					<input type="submit" class="button" value="Compartilhar Venda">
				</form>
				
				<a class="button" href="novo_pedido.php?comissarioId=">Compartilhar Link de Comissário<a>
				
				<footer class="modal-footer">
					<button type="button" class="button button-secundary" onclick="modalLink.close()">Cancelar</button>
				</footer>
			</section>
		</dialog>

	</div>

	<script>
		function redirecionar(event) {
			var Evento = document.getElementById('evento').value;
			var Setor = document.getElementById('setor').value;
			var Lote = document.getElementById('lote').value;
			
			event.preventDefault();
			
			var url = 'novo_pedido.php?eventoId=' + encodeURIComponent(Evento) 
			+ '&setorId=' + encodeURIComponent(Setor) + '&loteId=' + encodeURIComponent(Lote);
			
			window.location.href = url;
		}
	</script>
</body>
</html>
