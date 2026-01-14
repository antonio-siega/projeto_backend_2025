<?php
session_start();
include_once('conecta.php');

$codigo = $_POST['codigo'];
$total = 100.00; // mesmo valor da compra

$sql = "SELECT * FROM cupom
        WHERE codigo = ?
        AND NOW() BETWEEN periodo_ini AND periodo_fim";

$stmt = mysqli_prepare($bancodedados, $sql);

mysqli_stmt_bind_param($stmt, "s", $codigo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $cupom = mysqli_fetch_assoc($result);

    if ($cupom['tipo'] == 'percentual') {
        $desconto = $total * ($cupom['valor'] / 100);
    } else {
        $desconto = $cupom['valor'];
    }

    $_SESSION['desconto'] = $desconto;

} else {
    $_SESSION['desconto'] = 0;
}

header("Location: compra.php");
exit;
