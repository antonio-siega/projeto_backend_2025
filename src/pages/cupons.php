<?php
include_once 'conecta.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo         = strtoupper(trim($_POST['codigo']));
    $tipo           = $_POST['tipo']; // percentual | valor
    $valor          = $_POST['valor'];
    $periodo_ini    = $_POST['periodo_ini'] . " 00:00:00";
    $periodo_fim    = $_POST['periodo_fim'] . " 23:59:59";
    $limite_total   = $_POST['limite_total'];
    $limite_cliente = $_POST['limite_cliente'];
    $canal_restrito = $_POST['canal_restrito'];
    $comissario_id  = !empty($_POST['comissario_id']) ? $_POST['comissario_id'] : null;

    $sql = "INSERT INTO cupom
            (codigo, tipo, valor, periodo_ini, periodo_fim,
             limite_total, limite_cliente, canal_restrito, comissario_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($bancodedados, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssdssiiis",
        $codigo,
        $tipo,
        $valor,
        $periodo_ini,
        $periodo_fim,
        $limite_total,
        $limite_cliente,
        $canal_restrito,
        $comissario_id
    );

    if (mysqli_stmt_execute($stmt)) {
        $mensagem = "Cupom cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar cupom.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cupons</title>
   <link rel="stylesheet" href="../styles/global.css" />
	<link rel="stylesheet" href="../styles/lista.css"/>
	<link rel="stylesheet" href="../styles/index.css"/>
	<link rel="stylesheet" href="../styles/navbar.css"/>
  <link rel="stylesheet" href="..\styles\texto.css" />
</head>
<body>
<main class="main">
  <div class="container ">

    <form method="POST" class="form">
    <h1 class="title">Cadastrar Cupom</h1>

      <div class="input-wrapper">
        <span class="input-label">Código</span>
        <input type="text" name="codigo" required>
      </div>

      <div class="dropdown-wrapper">
       <span class="dropdown-label">Tipo</span>
        <select name="tipo" required>
          <option value="percentual" disabled selected>Percentual (%)</option>
          <option value="valor">Valor fixo (R$)</option>
        </select>
      </div>

      <div class="input-wrapper">
          <span class="input-label" >Valor</span>
        <input type="number" step="0.01" name="valor" class="form-control" required>
      </div>

      <div class="row">
        <div class="input-wrapper">
            <span class="input-label" >Período inicial</span>
          <input type="date" name="periodo_ini" class="form-control" required>
        </div>
        <div class="input-wrapper">
          <span class="input-label">Período final</span>
          <input type="date" name="periodo_fim" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="input-wrapper">
          <span class="input-label">Limite total</span>
          <input type="number" name="limite_total" class="form-control" required>
        </div>
        <div class="input-wrapper">
          <span class="input-label">Limite por cliente</span>
          <input type="number" name="limite_cliente" class="form-control" required>
        </div>
      </div>

      <div class="dropdown-wrapper">
       <span class="dropdown-label">Canal restrito</span>
        <select name="canal_restrito" class="form-control" required>
          <option value="ecommerce">E-commerce</option>
          <option value="comissario">Comissário</option>
          <option value="bilheteria">Bilheteria</option>
        </select>
      </div>

      <div class="input-wrapper">
          <span class="input-label">Comissário (opcional)</span>
        <input type="number" name="comissario_id" class="form-control">
      </div>
      
    <?php if ($mensagem): ?>
      <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

      <button class="button">Salvar cupom</button>

    </form>
  </div>
</main>
</body>
</html>
