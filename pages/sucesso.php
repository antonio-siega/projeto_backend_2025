<?php
$pedido_id = $_GET['pedido_id'] ?? null;
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pagamento aprovado</title>
  <link rel="stylesheet" href="../styles/home.css">
</head>
<body>
  <main class="main">
    <h1 class="title">PAGAMENTO APROVADO</h1>
    <p>Pedido nº <?= htmlspecialchars($pedido_id) ?></p>
    <p>Seu ingresso foi gerado.</p>
    <a class="button" href="meus_ingressos.html">Ver ingresso</a>
  </main>
</body>
</html>
