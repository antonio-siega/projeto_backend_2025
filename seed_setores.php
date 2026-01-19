<?php
require_once "src/pages/conecta.php";

echo "--- Criando Setores Padrão ---
";

$resEventos = mysqli_query($bancodedados, "SELECT id FROM evento");
while ($evento = mysqli_fetch_assoc($resEventos)) {
    $id = $evento['id'];
    
    // Verificar se já tem setores
    $check = mysqli_query($bancodedados, "SELECT id FROM setor WHERE evento_id = $id");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($bancodedados, "INSERT INTO setor (evento_id, nome, capacidade) VALUES ($id, 'Pista', 1000)");
        mysqli_query($bancodedados, "INSERT INTO setor (evento_id, nome, capacidade) VALUES ($id, 'VIP', 200)");
        echo "Setores criados para evento ID $id
";
    }
}
?>
