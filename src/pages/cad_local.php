<?php
include "../../conecta.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $endereco = $_POST["endereco"];
    $capacidade = $_POST["capacidade_total"];

    $sql = "INSERT INTO local (endereco, capacidade_total)
            VALUES ('$endereco', '$capacidade')";

    if (mysqli_query($bancodedados, $sql)) {
        $msg = "Local cadastrado com sucesso!";
    } else {
        $msg = "Erro ao cadastrar local."; 
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Local</title>

    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/index.css">

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />
</head>

<body>


<header class="header">
    <h1 class="title">Cadastro de Local</h1>
    <i class="ph ph-list" id="openModal"></i>
</header>


<div class="modal-wrapper" id="termsModal">
    <div class="modal main-modal">
        <header class="modal-header">
            <h2 class="modal-title">Menu</h2>
        </header>

        <div class="modal-content">
            <a href="../../index.html" class="button">Início</a>
            <a href="lista_local.php" class="button">Listar Locais</a>
            <a href="cad_usuario.php" class="button">Cadastrar Usuário</a>
            <a href="cad_organizacao.php" class="button">Cadastrar Organização</a>
            <a href="./auth/auth.html" class="link">Sair</a>

        </div>
    </div>
</div>


<main class="main">

    <?php if ($msg): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post" class="form">

        <input
            type="text"
            name="endereco"
            class="input"
            placeholder="Endereço do local"
            required
        >

        <input
            type="number"
            name="capacidade_total"
            class="input"
            placeholder="Capacidade total"
            required
        >

        <button type="submit" class="button">
            Cadastrar Local
        </button>

    </form>
</main>

<script src="../scripts/index.js"></script>
</body>
</html>
