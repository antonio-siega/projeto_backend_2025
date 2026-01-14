<?php
// Start the session
session_start();


?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="tela_comssario">
    <link rel="icon" href="src/img/favicon.ico">

    <title>Tela comissário</title>
    <link rel="stylesheet" href="../styles/global.css" />
	<link rel="stylesheet" href="../styles/lista.css"/>
	<link rel="stylesheet" href="../styles/index.css"/>
	<link rel="stylesheet" href="../styles/navbar.css"/>
	
	
	
    <script src="../scripts/home.js" defer></script>
	
	<style>
		dialog {
			margin: auto;
			position: fixed;
			top: 0%;
			left: 0%;
			border-radius: 0.8rem;
		}

		dialog::backdrop {
			background-color: rgba(0, 0, 0, 0.5);
		}
	</style>
  </head>
  <!--<body>
            
        <header class="header">
      <h1 class="title">OLÁ, <i class="contrast"><?php $_SESSION["login"]?> </i>!</h1>
      <button class="settings-button" id="settings-button">
        <i class="ph-fill ph-gear"></i>
      </button>

      <div class="dropdown-menu" id="dropdown">
        <h1 class="title">CONFIGURAÇÃO</h1>
        <button class="button">Alterar</button>
        <button class="button">Criar evento</button>
        <button class="button">Entrar em um evento</button>
      </div>
    </header>-->
    <?php
    include_once('conecta.php');
    ?>
    <body>
      <div class="main">
        <div class="container">
           
    <?php
    
    
         /* if(isset($_POST['nome']))
          {
            if(isset($_POST['id']))
            {
              $id = $_POST['id'];
              $organizacao_id = $_POST['organizacao_id'];
              $nome = $_POST['nome'];
              $dados = $_POST['dados'];
              $regra_comissao=$_POST['regra_comissao'];
              $sql = "UPDATE comissario SET organizacao_id = $organizacao_id, 
              nome = '$nome', dados = '$dados', regra_comissao='$regra_comissao' where id = $id";
              mysqli_query($bancodedados,$sql);

            }
            else {
              $id = $_POST['id'];
              $organizacao_id = $_POST['organizacao_id'];
              $nome = $_POST['nome'];
              $dados = $_POST['dados'];
              $regra_comissao=$_POST['regra_comissao'];
              $sql = "INSERT INTO comissario(organizacao_id,nome,dados,regra_comissao)
              values('$organizacao_id','$nome','$dados', '$regra_comissao')";
              mysqli_query($bancodedados,$sql);
                
            }
           

          }*/
          
        ?>
        <br>
		
			<header class="header">
				<h1 class="title">Dados do Comissário</h1>
				<i><button class="button title" onclick="modalLink.showModal()">Links (Comissão)</button></i>
			</header>
          
        </div>
        
        <div class="table-responsive">
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
					echo "<p>Bem-vindo Nome,</p>";//pegar sessao_start em auth.php
					$sql="SELECT comissario.*, organizacao.nome AS organiza, repasse_comissao.valor AS repasse FROM comissario, organizacao, repasse_comissao
					WHERE comissario.organizacao_id = organizacao.id AND repasse_comissao.comissario_id = comissario.id AND comissario.id = 1";//pegar comissario.id por uma varialve php
					$resultado=mysqli_query($bancodedados, $sql);
				  if ($linha = mysqli_fetch_array($resultado))
				  {
					echo "<tr><td>".$linha['organiza']."</td>";
					echo "<td>".$linha['dados']."</td>";
					echo "<td>".$linha['repasse']."</td>";
					echo "<td>".$linha['regra_comissao']."</td></tr>";  
				  }
				   /* $sql="SELECT comissario.*, organizacao.nome AS organiza
					FROM comissario, organizacao WHERE comissario.organizacao_id = organizacao.id";
				   
					$resultado = mysqli_query($bancodedados,$sql);
				   while($linha = mysqli_fetch_array($resultado))
				  {
				  
					echo "<tr><td>".$linha['nome']."</td>";
					echo "<td>".$linha['organiza']."</td>";
					echo "<td>".$linha['dados']."</td>";
					echo "<td>".$linha['regra_comissao']."</td></tr>";

					}
					*/
				  ?>
				</tbody>  
			</table>
        </div>
		  
		  <!-- Código LUIS -->
				
      <dialog id="modalLink">
        
          <section class="form modal">
          <form method="POST" class="form" id="cadastro_venda" target="_self">
			<header class="modal-header">
				<h2 class="modal-title">Nova Venda (Comissão)</h2>
			</header>
			
			<div class="dropdown-wrapper">
				<span class="dropdown-label">Evento</span>
				<select id="evento" name="evento" required>
				  <?php
					  $sql = "select * from evento";
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
					  $sql = "select * from setor";
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
					  $sql = "select * from lote";
					  $resultado = mysqli_query($bancodedados,$sql);
					  while($linha = mysqli_fetch_array($resultado))
					  {
						  echo "<option selected value='' hidden>Selecione</option>";  
						  echo "<option value='".$linha['id']."'>"."R$ ".$linha['preco']."</option>";
					  }
				  ?>  
				</select>
			</div>

            <input type="submit" class="button" id="cadastrarVenda" value="Cadastrar/Compartilhar Venda">

          </form>
		  
		  <!--<script src="auto_form.js"></script>-->

            <?php

              if(isset($_POST["evento"]))
              {
                $evento = $_POST["evento"];
                $setor = $_POST["setor"];
                $lote = $_POST["lote"];

                $eventoLink = "select id from evento where id = $evento";
                $setorLink = "select id from setor where id = $setor";
                $loteLink = "select id from lote where id = $lote";
				
				$sql = "select evento.id, setor.id, lote.id from evento, setor, lote";

                $link = "novo_pedido.php?eventoId=$eventoLink&setorId=$setorLink&loteId=$loteLink";
				
              }

            ?>
            
            <button class="button" id="btnLink">Compartilhar Link de Comissário</button>
			
			<footer class="modal-footer">
				<button type="button" class="button button-secundary" onclick="modalLink.close()">Cancelar</button>
			</footer>
            
          </section>
        
      </dialog>

		  <!-- FIM -->
		  
      </div>
        
  </body>
</html>