<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Relatórios ">
    <meta name="author" content="Maria Luiza">
    <link rel="icon" href="favicon.ico">

    <title>Relatórios</title>

     <link rel="stylesheet" href="..\styles\global.css" />
     <link rel="stylesheet" href="..\styles\components\input.css" />
     <link rel="stylesheet" href="..\styles\components\select.css" />
     <link rel="stylesheet" href="..\styles\lista_new.css" />
     <link rel="stylesheet" href="..\styles\texto.css" />

     
  </head>

  <body>
    <?php
      include_once('conecta.php');
      
    // Recupera os valores enviados pelo formulário, se existirem
    $organizacao_id = isset($_POST['organizacao_id']) ? $_POST['organizacao_id'] : null;
    $evento_id = isset($_POST['evento_id']) ? $_POST['evento_id'] : null;
    $relatorios_id = isset($_POST['relatorios_id']) ? $_POST['relatorios_id'] : null;
    $data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
    $data_final = isset($_POST['data_final']) ? $_POST['data_final'] : null;
      
    ?>
    <main role="main">
      <div class="main">
        <div class="container">
          <br>
          <!-- Formulário para seleção de filtros e tipo de relatório -->
            <form action="pesquisa.php" method="POST" class="form">
              <h1 class="title">Exporte relatórios</h1>
              <div class="dropdown-wrapper" >
                <span class="dropdown-label">Relatório a exportar</span>
                <select name="relatorios_id" id="relatorios_id">
                <option value="financeiro" disabled selected >Vendas/Finanças</option>  
                <option value="ocupacao">Ingressos vendidos</option>  
                <option value="checkin">Qtd de check-in</option>      
                <option value="comissao">Vendas por comissão</option>
                <option value="vendas_detalhe">Detalhes de venda</option>
                </select>
              </div>

              <div class="input-wrapper">
                <span class="input-label">Data Inicial</span>
                <input value="data_inicio" type="date"  id="data_inicio" name="data_inicio" placeholder="Informe a data inicial" >  
              </div>

            
              <div class="input-wrapper">
                <span class="input-label" >Data Final</span>
                <input  value="data_final" type="date"  id="data_final" name="data_final" placeholder="Informe a data final." >  
              </div>

               <!-- Filtros de organização e evento -->
              <div class="input-wrapper">
                <span class="input-label" >Organização</span>
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

              <div class="input-wrapper">
                <span class="input-label" >Evento</span>
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
          <h2 class="lista-titulo">Relatório</h2>
          <?php 
          // Exibe os resultados do relatório, se houver
          if (isset($resultados_relatorio) && !empty($resultados_relatorio)) { 
          ?>
            <div>
              <table class="table">
                <!-- Cabeçalho da tabela -->
                <thead class="th">
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
                  // Exibe as linhas do relatório
                  foreach ($resultados_relatorio as $linha) {
                    echo "<tr>";
                    foreach ($linha as $valor) {
                      echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    echo "</tr>";
                  }
                    ?>
                    <?php 
                    // Caso não haja resultados, exibe uma mensagem
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