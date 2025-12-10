<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Relatórios ">
    <meta name="author" content="Maria Luiza">
    <link rel="icon" href="favicon.ico">

    <title>Relatórios</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="styles/auth_new.css" rel="stylesheet">
  </head>

  <body>
    <?php
      include_once('conecta.php');
      

    $organizacao_id = isset($_POST['organizacao_id']) ? $_POST['organizacao_id'] : null;
    $evento_id = isset($_POST['evento_id']) ? $_POST['evento_id'] : null;
    $relatorios_id = isset($_POST['relatorios_id']) ? $_POST['relatorios_id'] : null;
    $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
    $data_final = isset($_POST['data_final']) ? $_POST['data_final'] : null;
      
    ?>
    <main role="main">
      <div class="main">
        <div class="container-fluid px-5">
          <br>
          <h1 class="title">Relatórios</h1>

            <form action="pesquisa.php" method="POST" class="form">
              <div class="form-group w-100 mb-3">
                <label for="relatorios_id">Qual relatório você quer exportar hoje?</label>
                <select class="form-control" name="relatorios_id" id="relatorios_id">
                <option value="financeiro">Vendas/Finanças</option>  
                <option value="ocupacao">Ingressos vendidos</option>  
                <option value="checkin">Qtd de check-in</option>      
                <option value="comissao">Vendas por comissão</option>
                </select>
              </div>

              <div class="row g-3 ">
            
              <div class="col-12 col-md-6 col-lg-3  w-100 mb-3">
                <label for="data_inicio">Data Inicial</label>
                <input  value="data_inicio" type="date" class="form-control" id="data_inicio" name="data_inicio" placeholder="Informe a data inicial" >  
              </div>

              <div class="col-12 col-md-6 col-lg-3 w-100 mb-3">
                <label for="data_final">Data Final</label>
                <input  value="data_final" type="date" class="form-control" id="data_final" name="data_final" placeholder="Informe a data final." >  
              </div>

              <div class="col-12 col-md-6 col-lg-3">
                <label for="organizacao_id">Qual organização?</label>
                <select class="form-control" name="organizacao_id" id="organizacao_id">
                <option value="" <?php echo ($organizacao_id === null || $organizacao_id === '') ? 'selected' : ''; ?>>-- Todas --</option>
                <?php
                  $sql = "select id, nome from organizacao order by nome";
                  $resultado = mysqli_query($bancodedados,$sql);
                  while($linha = mysqli_fetch_array($resultado))
                  {
                    if(isset($organizacao_id) && $organizacao_id == $linha['id'] )
                      echo "<option selected value='".$linha['id']."'>".$linha['nome']."</option>";
                    else
                      echo "<option value='".$linha['id']."'>".$linha['nome']."</option>"; }
                ?>           
                </select>
              </div>

              <div class="col-12 col-md-6 col-lg-3">
                <label for="evento_id">Qual evento?</label>
                <select class="form-control" name="evento_id" id="evento_id">
                <option value="" <?php echo ($evento_id === null || $evento_id === '') ? 'selected' : ''; ?>>-- Todos --</option>
                <?php
                  $sql = "select id, nome from evento order by nome";
                  $resultado = mysqli_query($bancodedados, $sql);
                  while($linha = mysqli_fetch_array($resultado))
                  {
                    if(isset($evento_id) && $evento_id == $linha['id'] )
                      echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";
                      
                    else
                    echo "<option value='".$linha['id']."'>".$linha['nome']."</option>";  
                  }
                ?>           
                </select>
              </div>

                <br>

              <div class="col-12 mt-3">
                <input type="submit" class="button" value="Pesquisar">
              </div>

            </div>
          </form>
          <h2 class="title mt-5">Relatório</h2>
          <?php 
      
          if (isset($resultados_relatorio) && !empty($resultados_relatorio)) { 
          ?>
            <div class="table-responsive mt-4">
              <table class="table table-striped table-bordered">
                
                <thead class="bg-dark text-white">
                  <tr>
                    <?php 
                    foreach ($campos_tabela as $campo) {
                      echo "<th>" . htmlspecialchars($campo) . "</th>";
                    }
                    ?>
                  </tr>
                </thead>

                <tbody>
                  <?php 
                  foreach ($resultados_relatorio as $linha) {
                    echo "<tr>";
                    foreach ($linha as $valor) {
                      echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    echo "</tr>";
                  }
                    ?>
                    <?php 
                      } else {
                        
                        echo "<p class='text-muted'>Selecione um filtro e clique em Pesquisar para gerar o relatório, ou a pesquisa não retornou resultados.</p>";
                      }
                    ?>
                  
                </tbody>
              </table>
            </div>
        </div>
      </div>
   
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>