<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit();
}
$user_nome = $_SESSION['user_nome'] ?? 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Principal</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/home.css" />

    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />

    <script src="../scripts/home.js" defer></script>
  </head>
  <body>
    <header class="header">
      <h1 class="title">OLÁ, <i class="contrast"><?php echo htmlspecialchars(strtoupper($user_nome)); ?></i>!</h1>
      <button class="settings-button" id="settings-button">
        <i class="ph-fill ph-gear"></i>
      </button>

      <div class="dropdown-menu" id="dropdown">
        <h1 class="title">CONFIGURAÇÃO</h1>
        <button class="button" data-action="edit">Editar Conta</button>
        <button class="button" data-action="organization">Cadastrar Organização</button>
        <button class="button" data-action="local">Gerenciar Locais</button>
        <button class="button" data-action="setores">Gerenciar Setores</button>
        <button class="button" data-action="lotes">Gerenciar Lotes</button>
        <button class="button" data-action="create">Gerenciar Eventos</button>
        <button class="button" data-action="join">Entrar em um evento</button>
        <button class="button button-secundary" onclick="document.getElementById('dropdown').classList.remove('active')">Voltar para Home</button>
        <a href="auth/logout.php" class="button" style="text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; background: #666;">Sair</a>
      </div>
    </header>
    <main class="main home-main" id="eventos-list"></main>

    <!-- Modal Elements for Event Creation/Edit -->
    <div class="modal-wrapper" id="termsModal">
      <div class="modal main-modal">
         <!-- Content will be injected by index.js -->
      </div>

      <div class="modal" id="secondaryModal">
        <header class="modal-header">
          <h2 class="modal-title" id="secondaryTitle">Título do Modal</h2>
          <button type="button" class="button button-small" onclick="document.getElementById('termsModal').classList.remove('active')" style="background: #999; width: auto; padding: 0.5rem 1rem; border: none; color: white;">
            Voltar para Home
          </button>
        </header>
        <div class="modal-content" id="secondaryContent">
          <!-- Form will be injected here -->
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/index.js"></script>
  </body>
</html>