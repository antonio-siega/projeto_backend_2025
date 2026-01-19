<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Nova venda - comissário">
    <meta name="author" content="Luis Felipe">
    <link rel="icon" href="favicon.ico">

    <title>Nova Venda de Comissário</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="styles/auth.css" rel="stylesheet">
	
	<style>
	
      select {
        position: relative;
        padding: 1rem;
        width: 100%;
        border: 0.125rem solid var(--white-100);
        color: var(--green-100);
      }

      select:focus {
        border-color: var(--green-300);
      }

      select:focus:hover {
        border-color: var(--green-300);
      }

      select:hover {
        border-color: var(--green-300);
        color: black;
      }

      label {
        width: 100%;
        padding-left: 1rem;
		padding-top: 0.5rem;
		color: var(--grey-300);
		font-size: 20px;
      }

      .button {
		font-size: 20px;
      }

      .main {
		height: 100%;
		width: 100%;
		margin: 1rem 0rem;
	  }
	  
	  main {
		display: grid;
		place-items: center;
		margin-top: 1rem;
	  }
	  
	  .form {
		width: 90vw !important;
		max-width: 600px;
	  }
	  
	  .terms-button {
		align-items: center;
		padding: 1rem;
		border-radius: 0.5rem;
		background-color: var(--green-100);
		margin-top: 1rem;
	  }
	  
	  .terms-button:hover {
		color: var(--white);
	  }

    </style>
	
  </head>
  
  <body class="main">
    <main>
      <form method="POST" class="form" id="cadastro_venda" target="_self">

        <h1 class="title">Nova Venda (Comissão)</h1>

        <label for="evento">Evento: </label>
        <select id="evento" name="evento" required>
          <option class="terms-text" value="" selected hidden>Selecione</option>
          <option value="evento1">Evento 1</option>
          <option value="evento2">Evento 2</option>
          <option value="evento3">Evento 3</option>
          <option value="evento4">Evento 4</option>
		</select>

        <label for="setor">Setor: </label>
        <select id="setor" name="setor" required>
          <option class="terms-text" value="" selected hidden>Selecione</option>
          <option value="setor1">Setor 1</option>
          <option value="setor2">Setor 2</option>
          <option value="setor3">Setor 3</option>
          <option value="setor4">Setor 4</option>
		</select>

        <label for="lote">Lote: </label>
        <select id="lote" name="lote" required>
          <option class="terms-text" value="" selected hidden>Selecione</option>
          <option value="lote1">Lote 1</option>
          <option value="lote2">Lote 2</option>
          <option value="lote3">Lote 3</option>
          <option value="lote4">Lote 4</option>
		</select>

        <input type="submit" class="button" id="cadastrarVenda" value="Cadastrar Venda">

      </form>
	  
	  <button class="terms-button" id="btnLink">Compartilhar link de venda (com comissão)</button>
	  
	  <a class="terms-button" href="link_comissario.php">Teste do link</a>

    </main>
  </body>
</html>