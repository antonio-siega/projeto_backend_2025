<?php
include "conecta.php";

$sql = "SELECT l.id, l.nome, l.endereco, l.capacidade, o.nome AS organizacao
        FROM local_evento l
        JOIN organizacao o ON l.organizacao_id = o.id";

$result = mysqli_query($conn, $sql);
?>

<h2>Locais Cadastrados</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Endereço</th>
        <th>Capacidade</th>
        <th>Organização</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["nome"]."</td>";
            echo "<td>".$row["endereco"]."</td>";
            echo "<td>".$row["capacidade"]."</td>";
            echo "<td>".$row["organizacao"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum local cadastrado.</td></tr>";
    }
    ?>
</table>

<br>
<a href="cad_local.php">Cadastrar novo local</a>
