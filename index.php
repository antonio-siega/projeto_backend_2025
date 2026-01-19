<?php
session_start();
/*
if (isset($_SESSION['user_id'])) {
    header("Location: src/pages/home.php");
    exit();
}
*/
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Início</title>
    <link rel="stylesheet" href="./src/styles/global.css" />
    <link rel="stylesheet" href="./src/styles/index.css" />
    <script src="./src/scripts/index.js" defer></script>
  </head>
  <body>
    <header class="header">
      <img src="./src/assets/images/logo.png" alt="logo" class="logo" />
      <div class="header-content">
        <a href="./src/pages/auth/auth.php" class="link">Sair do sistema</a>
      </div>
    </header>

    <main class="main">
      <h1 class="title">Seja bem vindo ao IF TICKET</h1>
      <p class="description">
        O sistema de ingressos do IF. Compre seus ingressos para os eventos do
        campus.
      </p>
      <a href="./src/pages/auth/auth.php" class="link">Entrar no sistema</a>
    </main>
  </body>
</html>