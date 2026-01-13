<?php

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;


$lote_id    = (int)($_POST['lote_id'] ?? 0);
$quantidade = (int)($_POST['quantidade'] ?? 0);


if ($lote_id <= 0 || $quantidade <= 0) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Dados inválidos'
    ]);
    exit;
}

try {
   
    MercadoPagoConfig::setAccessToken(
        'APP_USR-3871915922687609-121614-8d2abda2b85d71e57eb8ed2d56aa5aec-3067578484'
    );

   
    $db = (new Conexao())->getConexao();

   
    $stmt = $db->prepare("SELECT preco FROM lote WHERE id = ?");
    $stmt->execute([$lote_id]);
    $lote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lote) {
        throw new Exception('Lote não encontrado');
    }

    $preco_unitario = (float)$lote['preco'];
    $total = $preco_unitario * $quantidade;

   
    $client = new PreferenceClient();

   

    $preference = $client->create([
        "items" => [
            [
                "title" => "Ingresso IF - Lote {$lote_id}",
                "quantity" => $quantidade,
                "unit_price" => $preco_unitario
            ]
        ],
        "external_reference" => uniqid('pedido_'),

        "back_urls" => [
            "success" => "http://localhost/projeto_backend_2025/pages/sucesso.php",
            "failure" => "http://localhost/projeto_backend_2025/pages/erro.php",
        ],
        "auto_return" => "approved"
    ]);



    if (empty($preference->init_point)) {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Mercado Pago não retornou link de pagamento',
            'debug' => $preference
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'ok',
        'link_pagamento' => $preference->init_point
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'erro',
        'msg' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
