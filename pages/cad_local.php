<?php
include "conecta.php";
/*comentario teste*/
/* Buscar organizações */
$orgs = mysqli_query($conn, "SELECT id, nome FROM organizacao");

/* Inserção do local */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $endereco = $_POST["endereco"];
    $capacidade = $_POST["capacidade"];
    $organizacao_id = $_POST["organizacao_id"];

    $sql = "INSERT INTO local_evento (nome, endereco, capacidade, organizacao_id)
            VALUES ('$nome', '$endereco', '$capacidade', '$organizacao_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<p><strong>Local cadastrado com sucesso!</strong></p>";
    } else {
        echo "<p>Erro ao cadastrar local.</p>";
    }
}
?>

<h2>Cadastro de Local</h2>

<form method="post">
    <label>Nome do Local:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Endereço:</label><br>
    <input type="text" name="endereco" required><br><br>

    <label>Capacidade:</label><br>
    <input type="number" name="capacidade" required><br><br>

    <label>Organização:</label><br>
    <select name="organizacao_id" required>
        <option value="">Selecione</option>
        <?php
        while ($org = mysqli_fetch_assoc($orgs)) {
            echo "<option value='".$org["id"]."'>".$org["nome"]."</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Cadastrar</button>
</form>

<br>
<a href="lista_local.php">Ver locais cadastrados</a>
