<?php
include "../../conecta.php";

$msg = "";

// Busca as organizações
$orgs = mysqli_query($bancodedados, "SELECT id, nome FROM organizacao");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $perfil = $_POST["perfil"];
    $ativo = $_POST["ativo"];
    $organizacao_id = $_POST["organizacao_id"];

    $sql = "INSERT INTO usuario (nome, perfil, ativo, organizacao_id)
            VALUES ('$nome', '$perfil', '$ativo', '$organizacao_id')";

    if (mysqli_query($bancodedados, $sql)) {
        $msg = "Usuário cadastrado com sucesso!";
    } else {
        $msg = "Erro ao cadastrar usuário: " . mysqli_error($bancodedados);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>

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
    <h1 class="title">Cadastro de Usuário</h1>
    <i class="ph ph-list" id="openModal"></i>
</header>

<div class="modal-wrapper" id="termsModal">
    <div class="modal main-modal">
        <header class="modal-header">
            <h2 class="modal-title">Menu</h2>
        </header>

        <div class="modal-content">
            <a href="../../index.html" class="button">Início</a>
            <a href="lista_usuario.php" class="button">Listar Usuários</a>
            <a href="cad_organizacao.php" class="button">Cadastrar Organização</a>
            <a href="cad_local.php" class="button">Cadastrar Local</a>
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
            name="nome"
            class="input"
            placeholder="Nome do usuário"
            required
        >

        <div class="dropdown-wrapper">
            <span class="dropdown-label">Perfil</span>
            <select name="perfil" required>
                <option value="organizador">Organizador</option>
                <option value="bilheteria">Bilheteria</option>
                <option value="financeiro">Financeiro</option>
                <option value="portaria">Portaria</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="dropdown-wrapper">
            <span class="dropdown-label">Status</span>
            <select name="ativo" required>
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
        </div>

        <div class="dropdown-wrapper">
            <span class="dropdown-label">Organização</span>
            <select name="organizacao_id" required>
                <option value="">Organização</option>
                <?php while ($org = mysqli_fetch_assoc($orgs)): ?>
                    <option value="<?= $org['id'] ?>">
                        <?= $org['nome'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="button">
            Cadastrar Usuário
        </button>

    </form>
</main>

<script src="../scripts/index.js"></script>

</body>
</html>
