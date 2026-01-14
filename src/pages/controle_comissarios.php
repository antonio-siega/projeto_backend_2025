<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Controle ">
    <meta name="author" content="Maria Luiza">
    <link rel="icon" href="favicon.ico">

    <title>Controle de Comissários</title>

   <link rel="stylesheet" href="..\styles\global.css" />
     <link rel="stylesheet" href="..\styles\components\input.css" />
     <link rel="stylesheet" href="..\styles\components\select.css" />
     <link rel="stylesheet" href="..\styles\lista_new.css" />
     <link rel="stylesheet" href="..\styles\texto.css" />
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=edit" />
  </head>

  <body>
    <?php
      include_once('conecta.php');

      if(isset($_GET['opcao']))
      {
        if($_GET['opcao']=='a')
        {
          $id = $_GET['id'];
          $sql = "select * from comissario where id = $id";
          $resultado = mysqli_query($bancodedados,$sql);
          if ($linha = mysqli_fetch_array($resultado))
          {
            $nome = $linha['nome'];
            $regra_comissao = $linha['regra_comissao'];
            $dados = $linha['dados'];
            
          }
        }
      }
    ?>
  <main role="main">
    <div class="main">
        <div class="container">
          
          <br>
          
            <form action="controle_comissarios.php" method="POST" class="form">
                <h1 class="title">Controle de Comissários</h1>
                <div class="input-wrapper">
                    <span class="input-label">Nome</span>
                    <input required value="<?php if(isset($nome)) echo $nome; ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Informe o nome do comissário" size="120">  
                </div>

                <div class="input-wrapper">
                    <span class="input-label">Regra de comissão</span>
                    <input required value="<?php if(isset($regra_comissao)) echo $regra_comissao; ?>" type="text" class="form-control" id="regra_comissao" name="regra_comissao" placeholder="Informe a regra de comissão" size="120">  
                </div>

                <div class="input-wrapper">
                    <span class="input-label">Contato</span>
                    <input required value="<?php if(isset($dados)) echo $dados; ?>" type="text" class="form-control" name="dados" id="dados" maxlength="30"  placeholder="Informe os dados de contato">  
                </div>
                

                <div >
                    <input type="submit" class="button" value="Cadastrar">
                </div>   

                <?php
                  if(isset($_GET['opcao'])&& $_GET['opcao'] == 'a')
                    {
                    ?>
                    <input type = "hidden" name = "id" value = "<?php echo $id;?>" >
                    <?php
                  }
                ?>
            </form>
            <?php
                    if(isset($_POST['nome']))
                {
                if(isset($_POST['id'])){
                    $id = $_POST['id'];
                    $nome = $_POST['nome'];
                    $regra_comissao = $_POST['regra_comissao'];
                    $dados = $_POST['dados'];
                    $sql = "update comissario set nome = '$nome',regra_comissao = '$regra_comissao', dados = '$dados' where id = $id";
                    mysqli_query($bancodedados,$sql);
                }
                else{
                    $id = $_POST['id'];
                    $nome = $_POST['nome'];
                    $regra_comissao = $_POST['regra_comissao'];
                    $dados = $_POST['dados'];
                    $sql = "insert into comissario(nome,regra_comissao,dados) values('$nome', '$regra_comissao','$dados')";
                    mysqli_query($bancodedados,$sql);
                
                }
                }
              ?>
              <br>
          <div class="row" style="margin-top:20px;">
            <h2 class="lista-titulo">Comissários cadastrados </h2>
          </div>
          <div class="row">
            <table class="table">
              <tr>
                <th>Nome comissário</th>
                <th>Regra Comissão</th>
                <th>Dados</th>
                <th class="material-symbols-outlined">edit</th>
              </tr>
            <?php
              $sql =  "select id, nome, regra_comissao, dados from comissario";
              $resultado = mysqli_query($bancodedados,$sql);
              while($linha =  mysqli_fetch_array($resultado))
              {
                  echo "<tr><td>".$linha['nome']."</td>";
                  echo "<td>".$linha['regra_comissao']."</td>";
                  echo "<td>".$linha['dados']."</td>";
    

              echo "<td><a href='controle_comissarios.php?opcao=a&id=".$linha['id']."'>Editar</a></td></tr>";
              }

              ?>
            </table>
          
        </div>
      </div>
      
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>