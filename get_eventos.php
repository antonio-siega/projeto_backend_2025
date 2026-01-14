<?php
header('Content-Type: application/json');

// Simulação de dados (em um caso real, você faria uma consulta ao banco de dados MySQL)
$setor_evento = "select setor.id from setor inner join setor.id = evento.id;"

$evento_id = isset($_GET['evento_id']) ? (int)$_GET['evento_id'] : 0;

if ($evento_id > 0 && isset($setor_evento[$evento_id])) {
    echo json_encode($setor_evento[$evento_id]);
} else {
    echo json_encode([]);
}
?>
