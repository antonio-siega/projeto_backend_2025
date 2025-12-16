<?php

session_start(); 
require 'Conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado! Por favor, faça login (rode o login_simulado.php).");
}

$userId = $_SESSION['usuario_id'];
$accessToken = 'APP_USR-6777044867566758-121512-1d636628dfb4647e618230ba2b040111-3067578484'; 

try {

    $conexao = new Conexao();
    $pdo = $conexao->getConexao();
    $sql = "SELECT * FROM pedido WHERE cliente_id = :uid AND status = 'pendente' ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $userId]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die("<h3>Nenhum pedido pendente encontrado para " . $_SESSION['usuario_nome'] . ".</h3>");
    }

    
    $dadosPagamento = [
        "items" => [
            [
                "title" => "Ingresso #{$pedido['id']} - Evento IF",
                "quantity" => 1,
                "currency_id" => "BRL",
                "unit_price" => (float)$pedido['total_liquido']
            ]
        ],
        "back_urls" => [
            "success" => "http://localhost/Projeto_Back_End/projeto_back_end_2025_copia/retorno_pagamento.php?pedido_id=" . $pedido['id'],
            "failure" => "http://localhost/Projeto_Back_End/projeto_back_end_2025_copia/retorno_pagamento.php?status=falha"
        ],
        "auto_return" => "approved",
        "external_reference" => (string)$pedido['id']
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.mercadopago.com/checkout/preferences",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($dadosPagamento),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    
    $mp = json_decode($response, true);
    $link = $mp['init_point'] ?? '#';

    // Exibição
    echo "<h2>Olá, {$_SESSION['usuario_nome']}!</h2>";
    echo "<p>Você tem um pedido pendente no valor de <b>R$ " . number_format($pedido['total_liquido'], 2, ',', '.') . "</b>.</p>";
    echo "<a href='$link' style='background:#009EE3; color:white; padding:15px; text-decoration:none; border-radius:5px;'>Pagar Agora com Mercado Pago</a>";

} catch (Exception $e) {
    echo "Erro no sistema: " . $e->getMessage();
}
?>

