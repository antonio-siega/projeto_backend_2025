<?php
include "conecta.php";

$sql = "SELECT u.id, u.nome, u.perfil, u.ativo, o.nome AS organizacao
        FROM usuario u
        JOIN organizacao o ON u.organizacao_id = o.id";

$result = mysqli_query($conn, $sql);
?>

<h2>Usuários Cadastrados</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Perfil</th>
        <th>Ativo</th>
        <th>Organização</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["nome"]."</td>";
            echo "<td>".$row["perfil"]."</td>";
            echo "<td>".($row["ativo"] ? "Sim" : "Não")."</td>";
            echo "<td>".$row["organizacao"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum usuário cadastrado.</td></tr>";
    }
    ?>
</table>

<br>
<a href="cad_usuario.php">Cadastrar novo usuário</a>
