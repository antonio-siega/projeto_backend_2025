<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestão de Eventos">
    <meta name="author" content="Quarto Periodo SI">

    <title>Cadastro de Clientes</title>
     <link rel="stylesheet" href="../styles/root.css">
     <link rel="stylesheet" href="../styles/index.css">
     <link rel="stylesheet" href="../styles/navbar.css">
      <link rel="stylesheet" href="../styles/lista.css">

  </head>
  <body>
    <?php
      require_once(__DIR__ . '/../conecta.php');
      $mensagem_erro = '';
      if(isset($_GET['opcao']))
      {
        if($_GET['opcao']=='e')
        {
            $id = $_GET['id'];
            // Verificar se há pedidos associados a este cliente
            $sql_verifica = "select count(*) as total from pedido where cliente_id = $id";
            $resultado_verifica = mysqli_query($bancodedados,$sql_verifica);
            $linha_verifica = mysqli_fetch_array($resultado_verifica);
            
            if($linha_verifica['total'] > 0)
            {
              $mensagem_erro = "Não é possível excluir este cliente pois existem pedidos associados. Exclua os pedidos primeiro.";
            }
            else
            {
              $sql = "delete from cliente where id = $id";
              mysqli_query($bancodedados,$sql);
              header('Location: clientes_cadastrados.php');
              exit();
            }
        }
        if($_GET['opcao']=='a')
        {
          $id = $_GET['id'];
          $sql = "select * from cliente where id = $id";
          $resultado = mysqli_query($bancodedados,$sql);
          if ($linha = mysqli_fetch_array($resultado))
          {
            $nome = $linha['nome'];
            $documento = $linha['documento'];
            $contato = $linha['contato'];
            $consentimento = $linha['consentimento'];
          }
        }
      }
    ?>
    <div class="navbar"> 
      <a href="../index.html">Inicio</a> 
      <a href="novo_pedido.php">Novo Pedido</a> 
      <a href="todos_pedidos.php">Todos os Pedidos</a>
      <a href="cadastro_clientes.php">Cadastro de Cliente</a>
      
    </div>

    <?php if($mensagem_erro != ''): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
      <strong>Erro:</strong> <?php echo $mensagem_erro; ?>
    </div>
    <?php endif; ?>

    <main class="main">
     <div>
          <h1 class='title'>Clientes Cadastrados</h1>
      </div>
        <div class="lista">
          <table >
            <tr>
              <th>Nome</th>
              <th>CPF</th>
              <th>Contato</th>
              <th>Consentimento</th>
              <th class="tdmenor">Excluir</th>
      		    <th class="tdmenor">Editar</th>
            </tr>

          <?php
            $sql = "select id,nome,documento,contato,consentimento from cliente;";
            $resultado = mysqli_query($bancodedados,$sql);
            while($linha = mysqli_fetch_array($resultado))
            {
              echo "<tr><td>".$linha['nome']."</td>";
              echo "<td>".$linha['documento']."</td>";
              echo "<td>".$linha['contato']."</td>";
              echo "<td>".$linha['consentimento']."</td>";
              echo "<td><a href='clientes_cadastrados.php?opcao=e&id=".$linha['id']."'>Excluir</a></td>";
              echo "<td><a href='cadastro_clientes.php?opcao=a&id=".$linha['id']."'>Editar</a></td></tr>";
            }
          ?>

          </table>
        </div>        
  </main>
  </div>
  </body>
</html>