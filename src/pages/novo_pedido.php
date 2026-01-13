<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestão de Eventos">
    <meta name="author" content="Quarto Periodo SI">

    <title>Cadastro de Pedido</title>
     <link rel="stylesheet" href="../styles/root.css">
     <link rel="stylesheet" href="../styles/index.css">
      <link rel="stylesheet" href="../styles/navbar.css">
  </head>
  <body>
       <div class="navbar"> 
      <a href="../index.html">Inicio</a> 
      <a href="novo_pedido.php">Novo Pedido</a> 
      <a href="todos_pedidos.php">Todos os Pedidos</a>
      <a href="cadastro_clientes.php">Cadastro de Cliente</a>
      <a href="clientes_cadastrados.php">Clientes Cadastrados</a>
    </div>
    <?php
      require_once(__DIR__ . '/../conecta.php');
        if(isset($_GET['opcao']) && $_GET['opcao']=='a')
        {
          $id = $_GET['id'];
          $sql = "select * from pedido where id = $id";
          $resultado = mysqli_query($bancodedados,$sql);
          if ($linha = mysqli_fetch_array($resultado))
          {
            $cliente_id = $linha['cliente_id'];
            $canal_venda = $linha['canal_venda'];
            $setor_id = $linha['setor_id'];
            $lote_id = $linha['lote_id'];
            $quantidade = $linha['quantidade'];
            $valor_bruto = $linha['valor_bruto'];
            $taxa = $linha['taxa'];
            $desconto = $linha['desconto'];
            $total_liquido = $linha['total_liquido'];
            $status = $linha['status'];
            $prazo_expiracao = $linha['prazo_expiracao'];
          }
        }
    ?>
  <main class="main">
   <h1 class='title'>Cadastrar Pedido</h1>
      <div>
        <form class="form" action="novo_pedido.php" method="POST">
         <div>
          <label for="cliente">Cliente</label>
            <select class="input" name="cliente" id="cliente" required>
              <option value="">-- Selecione um Cliente --</option>
              <?php
               $sql = "select id, nome from cliente order by nome";
                $resultado = mysqli_query($bancodedados,$sql);
                if($resultado) {
                  while($linha = mysqli_fetch_array($resultado))
                  {
                   if(isset($cliente_id) && $cliente_id == $linha['id'])
                     echo "<option selected value='".$linha['id']."'>".$linha['nome']."</option>";  
                    else
                     echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                  }
                }
              ?>              
            </select>
          <div>
            <label for="canal_venda">Canal de Venda</label>
              <select class="input" name="canal_venda" id="canal_venda" required>
                <option value="">-- Selecione --</option>
                <?php
                  $opcoes = array('ecommerce' => 'Ecommerce','comissario' => 'Comissário','bilheteria' => 'Bilheteria');
                  foreach($opcoes as $val => $op) {
                    $sel = (isset($canal_venda) && $canal_venda == $val) ? ' selected' : '';
                    echo "<option value='$val'$sel>$op</option>";
                  }
                ?>
            </select>
          <div>
            <label for="setor">Setor</label>
              <select class="input" name="setor_id" id="setor" required>
                <option value="">-- Selecione um Setor --</option>
                <?php
                  $sql = "select id, nome from setor order by nome";
                  $resultado = mysqli_query($bancodedados,$sql);
                  if($resultado && mysqli_num_rows($resultado) > 0) {
                    while($linha = mysqli_fetch_array($resultado))
                    {
                      if(isset($setor_id) && $setor_id == $linha['id'])
                        echo "<option selected value='".$linha['id']."'>".$linha['nome']."</option>";  
                      else
                        echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                    }
                  } else {
                    echo "<option>Nenhum setor disponível</option>";
                  }
                ?>      
              </select>
            <div>
              <label for="lote">Lote</label>
                <select class="input" name="lote_id" id="lote" required>
                  <option value="">-- Selecione um Lote --</option>
                  <?php
                    $sql = "select l.id, l.preco, s.nome as setor_nome from lote l inner join setor s on l.setor_id = s.id where l.status = 'ativo' order by s.nome, l.preco";
                    $resultado = mysqli_query($bancodedados,$sql);
                    if($resultado && mysqli_num_rows($resultado) > 0) {
                      while($linha = mysqli_fetch_array($resultado))
                      {
                        if(isset($lote_id) && $lote_id == $linha['id'])
                          echo "<option selected value='".$linha['id']."'>".$linha['setor_nome']." - R$ ".$linha['preco']."</option>";  
                        else
                          echo "<option value='".$linha['id']."'>".$linha['setor_nome']." - R$ ".$linha['preco']."</option>";
                      }
                    } else {
                      echo "<option>Nenhum lote disponível</option>";
                    }
                  ?>     
                </select>
            </div>
            <div>
              <label for="quantidade">Quantidade</label>
                <input class="input" required value="<?php if(isset($quantidade)) echo $quantidade;?>" type="text" id="quantidade" name="quantidade" placeholder="Somente numeros" size="60">
            </div>
            <div>
              <label  for="valor_bruto">Valor Bruto</label>
                <input class="input" required value="<?php if(isset($valor_bruto)) echo $valor_bruto; ?>" type="text" id="valor_bruto" name="valor_bruto" size="60" placeholder="Preenchido automaticamente"> 
            </div>
            <div>
              <label for="taxa">Taxa</label>
              <input class="input" required value="<?php if(isset($taxa)) echo $taxa; ?>" type="text" id="taxa" name="taxa" size="60" placeholder="Preenchido automaticamente"> 
            </div>
            <div>
              <label for="desconto">Desconto</label>
                <input class="input" required value="<?php if(isset($desconto)) echo $desconto; ?>" type="text" id="desconto" name="desconto" size="60" placeholder="Preenchido automaticamente"> 
            </div>
            <div>
              <label for="total_liquido">Total Liquido</label>
                <input class="input" req uired value="<?php if(isset($total_liquido)) echo $total_liquido; ?>" type="text" id="total_liquido" name="total_liquido" size="60" placeholder="Preenchido automaticamente"> 
            </div>
            <?php
              if(isset($_GET['opcao']) && $_GET['opcao']=='a')
              {
            ?>
              <input type="hidden" name="id" value="<?php echo $id;?>"> 
            <?php
              }
            ?>
            <div>
            <br>
            <input class="button" type="submit" value="Gravar">
            </div>

      </form>
    </div>
    <div>
      <?php
        if(isset($_POST['cliente']))
        {
          if(isset($_POST['id']))
          {
            $id = $_POST['id'];
            $cliente = $_POST['cliente'];
            $canal_venda = $_POST['canal_venda'];
            $setor_id = $_POST['setor_id'];
            $lote_id = $_POST['lote_id'];
            $quantidade = $_POST['quantidade'];
            $valor_bruto = $_POST['valor_bruto'];
            $taxa = $_POST['taxa'];
            $desconto = $_POST['desconto'];
            $total_liquido = $_POST['total_liquido'];
            $sql = "update pedido set cliente_id='$cliente', canal_venda = '$canal_venda', setor_id = '$setor_id', lote_id = '$lote_id', quantidade = '$quantidade', valor_bruto = '$valor_bruto', taxa = '$taxa', desconto = '$desconto', total_liquido = '$total_liquido' where id = $id";
            if(mysqli_query($bancodedados,$sql))
            {
              header('Location: todos_pedidos.php');
              exit();
            }

          }
          else {
            $cliente = $_POST['cliente'];
            $canal_venda = $_POST['canal_venda'];
            $setor_id = $_POST['setor_id'];
            $lote_id = $_POST['lote_id'];
            $quantidade = $_POST['quantidade'];
            $valor_bruto = $_POST['valor_bruto'];
            $taxa = $_POST['taxa'];
            $desconto = $_POST['desconto'];
            $total_liquido = $_POST['total_liquido'];
            $data_criacao = date('Y-m-d H:i:s');
            $sql = "insert into pedido (cliente_id,canal_venda,setor_id,lote_id,quantidade,valor_bruto,taxa,desconto,total_liquido,data_criacao) values ('$cliente','$canal_venda','$setor_id','$lote_id','$quantidade','$valor_bruto','$taxa','$desconto','$total_liquido','$data_criacao')";
            if(mysqli_query($bancodedados,$sql))
            {
              header('Location: todos_pedidos.php');
              exit();
            }  
          }

        }
      ?>
    </div>
    <script src="../scripts/pedido_calc.js"></script>
  </body>
</html>

