<?php
include "conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $contato = $_POST["contato"];

    $sql = "INSERT INTO organizacao (nome, contato)
            VALUES ('$nome', '$contato')";

    if (mysqli_query($conn, $sql)) {
        echo "<p><strong>Organização cadastrada com sucesso!</strong></p>";
    } else {
        echo "<p>Erro ao cadastrar organização.</p>";
    }
}
?>

<h2>Cadastro de Organização</h2>

<form method="post">
    <label>Nome da Organização:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Contato:</label><br>
    <input type="text" name="contato"><br><br>

    <button type="submit">Cadastrar</button>
</form>
