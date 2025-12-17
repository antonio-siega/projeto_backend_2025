<?php
$conn = mysqli_connect("localhost", "root", "", "eventos_if");

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
?>
