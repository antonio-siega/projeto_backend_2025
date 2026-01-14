<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

$pedido_id = (int)($_REQUEST['pedido_id'] ?? 0);

if ($pedido_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => 'ID do pedido invalido']);
    exit;
}

try {
    MercadoPagoConfig::setAccessToken('APP_USR-3871915922687609-121614-8d2abda2b85d71e57eb8ed2d56aa5aec-3067578484');

    $db = (new Conexao())->getConexao();

    $stmt = $db->prepare("SELECT total_liquido FROM pedido WHERE id = ?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        throw new Exception('Pedido não encontrado');
    }

    $total = (float)$pedido['total_liquido'];

    $client = new PreferenceClient();

    $preference = $client->create([
        "items" => [
            [
                "title" => "Ingresso IF - Pedido {$pedido_id}",
                "quantity" => 1,
                "unit_price" => $total,
                "currency_id" => "BRL"
            ]
        ],
        "external_reference" => (string)$pedido_id,
        "back_urls" => [
            "success" => "http://localhost/projeto_backend_2025/pages/sucesso.php",
            "failure" => "http://localhost/projeto_backend_2025/pages/erro.php",
        ]
    ]);

    if (empty($preference->init_point)) {
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Mercado Pago não retornou link de pagamento'
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
        'msg' => $e->getMessage()
    ]);
}