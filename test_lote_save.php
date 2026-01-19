<?php
require_once "src/pages/conecta.php";

echo "--- Teste de Gravação de Lote ---
";

// Pegar o primeiro setor disponível
$res = mysqli_query($bancodedados, "SELECT id FROM setor LIMIT 1");
$setor = mysqli_fetch_assoc($res);

if ($setor) {
    $setor_id = $setor['id'];
    $preco = 50.00;
    $ini = date('Y-m-d') . " 00:00:00";
    $fim = date('Y-m-d', strtotime('+30 days')) . " 23:59:59";
    $limite = 100;
    
    $sql = "INSERT INTO lote (setor_id, preco, periodo_vigencia_ini, periodo_vigencia_fim, limite, status) 
            VALUES ($setor_id, $preco, '$ini', '$fim', $limite, 'ativo')";
    
    if (mysqli_query($bancodedados, $sql)) {
        echo "SUCESSO: O lote foi gravado no banco de dados!
";
        // Limpar teste
        mysqli_query($bancodedados, "DELETE FROM lote WHERE preco = 50.00 AND limite = 100");
    } else {
        echo "ERRO ao gravar lote: " . mysqli_error($bancodedados) . "
";
    }
} else {
    echo "Erro: Nenhum setor encontrado para o teste.
";
}
?>
