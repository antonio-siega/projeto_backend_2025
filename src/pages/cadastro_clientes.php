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
      <div class="navbar"> 
      <a href="../index.html">Inicio</a> 
      <a href="novo_pedido.php">Novo Pedido</a> 
      <a href="todos_pedidos.php">Todos os Pedidos</a>
      <a href="clientes_cadastrados.php">Clientes Cadastrados</a>
    </div>

    <?php
      require_once(__DIR__ . '/../conecta.php');
      if(isset($_GET['opcao']))
      {
        if($_GET['opcao']=='e')
        {
            $id = $_GET['id'];
            $sql = "delete from cliente where id = $id";
            mysqli_query($bancodedados,$sql);
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

   <main class="main">
   <h1 class='title'>Cadastro de Cliente</h1>
        <div>
          <form class="form" action="cadastro_clientes.php" method="POST">
            <div>
              <label for="nome">Nome</label>
                <input class="input" required value="<?php if(isset($nome)) echo $nome;?>" type="text" id="nome" name="nome" placeholder="Informe o nome do cliente" size="60">
            </div>
            <div>
              <label for="documento">CPF</label>
                <input class="input" required value="<?php if(isset($documento)) echo $documento; ?>" type="text" id="documento" name="documento" placeholder="Somente numeros" size="60"> 
            </div>
            <div class="form-group">
              <label for="contato">Telefone</label>
                <input class="input" required value="<?php if(isset($contato)) echo $contato; ?>" type="text" id="contato" name="contato" placeholder="Somente numeros" size="60"> 
            </div>
            <div>
              <label for="consentimento_id">Aceita os termos de cadastratos?</label>
                <input class="check" type="checkbox" name="consentimento_id" id="consentimento_id" value="1"> Aceito

                <?php
                  if(isset($_GET['opcao']) && $_GET['opcao']=='a')
                  {
                 ?>
                <input type="hidden" name="id" value="<?php echo $id;?>"> 
                <?php
                  }
                ?>
            </div>
            <br>
            <input class="button" type="submit" value="Gravar">
          </form>
        </div>

        <div>
        <?php
          if(isset($_POST['nome']))
          {
            if(isset($_POST['id']))
            {
              $id = $_POST['id'];
              $nome = $_POST['nome'];
              $documento = $_POST['documento'];
              $contato = $_POST['contato'];
              $consentimento = isset($_POST['consentimento_id']) ? $_POST['consentimento_id'] : '0';
              $sql = "update cliente set nome='$nome', documento = '$documento', contato = '$contato', consentimento='$consentimento' where id = $id";
              if(mysqli_query($bancodedados,$sql))
              {
                header('Location: clientes_cadastrados.php');
                exit();
              }
            }
            else {
              $nome = $_POST['nome'];
              $documento = $_POST['documento'];
              $contato = $_POST['contato'];
              $consentimento = isset($_POST['consentimento_id']) ? $_POST['consentimento_id'] : '0';
              $sql = "insert into cliente (nome,documento,contato,consentimento) values ('$nome','$documento','$contato','$consentimento')";
              if(mysqli_query($bancodedados,$sql))
              {
                header('Location: clientes_cadastrados.php');
                exit();
              }
            }
          }
        ?>
        </div>