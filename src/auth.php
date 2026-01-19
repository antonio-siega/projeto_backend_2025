<?php
session_start();
$error = $_GET['error'] ?? '';
$form_type = $_GET['form'] ?? 'login';
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bem vindo</title>
    <link rel="stylesheet" href="../../styles/global.css" />
    <link rel="stylesheet" href="./auth.css" />
    <style>
      .error-message {
        color: #ff4d4d;
        background: #ffe6e6;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        text-align: center;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <form id="loginForm" class="form" action="process_auth.php" method="POST" <?php echo $form_type === 'login' ? '' : 'style="display: none;"'; ?>>
      <input type="hidden" name="action" value="login">
      <img src="../../assets/images/logo.png" alt="logo" class="logo" />
      <hr />

      <h2 class="title">LOGIN</h2>

      <?php if ($error && $form_type === 'login'): ?>
        <div class="error-message">
          <?php
            switch($error) {
              case 'invalid_credentials': echo "Email ou senha inválidos."; break;
              case 'empty_fields': echo "Preencha todos os campos."; break;
              default: echo "Ocorreu um erro. Tente novamente.";
            }
          ?>
        </div>
      <?php endif; ?>

      <div class="input-wrapper">
        <span class="input-label">Email</span>
        <input type="email" name="email" placeholder="Insira seu email" required />
      </div>

      <div class="input-wrapper">
        <span class="input-label">Senha</span>
        <input type="password" name="senha" placeholder="Insira sua senha" required />
      </div>

      <button type="submit" class="button">Entrar</button>
      <a href="#" id="showRegister" class="link">Não tenho conta</a>
    </form>

    <form id="registerForm" class="form" action="process_auth.php" method="POST" <?php echo $form_type === 'register' ? '' : 'style="display: none;"'; ?>>
      <input type="hidden" name="action" value="register">
      <img src="../../assets/images/logo.png" alt="logo" class="logo" />
      <hr />

      <h2 class="title">CADASTRO</h2>

      <?php if ($error && $form_type === 'register'): ?>
        <div class="error-message">
          <?php
            switch($error) {
              case 'email_exists': echo "Este email já está cadastrado."; break;
              case 'passwords_dont_match': echo "As senhas não coincidem."; break;
              case 'registration_failed': echo "Erro ao realizar cadastro. Tente novamente."; break;
              case 'empty_fields': echo "Preencha todos os campos."; break;
              default: echo "Ocorreu um erro. Tente novamente.";
            }
          ?>
        </div>
      <?php endif; ?>

      <div class="input-wrapper">
        <span class="input-label">Nome</span>
        <input type="text" name="nome" placeholder="Insira seu nome" required />
      </div>

      <div class="input-wrapper">
        <span class="input-label">Email</span>
        <input type="email" name="email" placeholder="Insira seu email" required />
      </div>

      <div class="input-wrapper">
        <span class="input-label">Senha</span>
        <input
          type="password"
          name="senha"
          id="registerPassword"
          placeholder="Insira sua senha"
          required
        />
      </div>

      <div class="input-wrapper">
        <span class="input-label">Confirmar</span>
        <input
          type="password"
          name="confirmar_senha"
          id="registerConfirmPassword"
          placeholder="Confirme sua senha"
          required
        />
      </div>

      <div class="checkbox-container">
        <input type="checkbox" id="termsCheckbox" />
        <label id="openTerms" for="termsCheckbox">Aceito os termos</label>
      </div>

      <button type="submit" class="button" id="submitButton" disabled>
        Criar
      </button>
      <a href="#" id="showLogin" class="link">Já tenho conta</a>
    </form>

    <div class="modal-wrapper" id="termsModal">
      <div
        class="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
      >
        <header class="modal-header">
          <h2 class="modal-title">Título do Modal</h2>
        </header>

        <div class="modal-content">
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laboriosam
            error deserunt excepturi veritatis autem inventore cum, iste
            pariatur! Dolor, numquam aut commodi nemo dolore molestiae
            distinctio beatae harum ab cumque.
          </p>
        </div>

        <footer class="modal-footer">
          <button type="button" class="button button-secundary" id="closeModal">
            Cancelar
          </button>
          <button type="button" class="button" id="confirmTerms">
            Confirmar
          </button>
        </footer>
      </div>
    </div>

    <script src="./auth.js"></script>
  </body>
</html>