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
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/auth_new.css" rel="stylesheet">
</head>
<body>
<main class="main">
  <div class="container mt-5">
    <h1 class="title">Cadastrar Cupom</h1>

    <?php if ($mensagem): ?>
      <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="POST" class="form">

      <div class="form-group w-100 mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" required>
      </div>

      <div class="form-group w-100 mb-3">
        <label>Tipo</label>
        <select name="tipo" class="form-control" required>
          <option value="percentual">Percentual (%)</option>
          <option value="valor">Valor fixo (R$)</option>
        </select>
      </div>

      <div class="form-group w-100 mb-3">
        <label>Valor</label>
        <input type="number" step="0.01" name="valor" class="form-control" required>
      </div>

      <div class="row">
        <div class="w-100 mb-3">
          <label>Período inicial</label>
          <input type="date" name="periodo_ini" class="form-control" required>
        </div>
        <div class="w-100 mb-3">
          <label>Período final</label>
          <input type="date" name="periodo_fim" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col mb-3">
          <label>Limite total</label>
          <input type="number" name="limite_total" class="form-control" required>
        </div>
        <div class="col mb-3">
          <label>Limite por cliente</label>
          <input type="number" name="limite_cliente" class="form-control" required>
        </div>
      </div>

      <div class="form-group w-100 mb-3">
        <label>Canal restrito</label>
        <select name="canal_restrito" class="form-control" required>
          <option value="ecommerce">E-commerce</option>
          <option value="comissario">Comissário</option>
          <option value="bilheteria">Bilheteria</option>
        </select>
      </div>

      <div class="form-group w-100 mb-3" >
        <label>Comissário (opcional)</label>
        <input type="number" name="comissario_id" class="form-control">
      </div>

      <button class="button">Salvar cupom</button>

    </form>
  </div>
</main>
</body>
</html>
