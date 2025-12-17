<?php
include "conecta.php";

$sql = "SELECT id, nome, contato, created_at FROM organizacao";
$result = mysqli_query($conn, $sql);
?>

<h2>Organizações Cadastradas</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Contato</th>
        <th>Data de Cadastro</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["nome"]."</td>";
            echo "<td>".$row["contato"]."</td>";
            echo "<td>".$row["created_at"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Nenhuma organização cadastrada.</td></tr>";
    }
    ?>
</table>

<br>
<a href="cad_organizacao.php">Cadastrar nova organização</a>
