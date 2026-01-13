<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mercado_pago.php';
require_once __DIR__ . '/../GeradorDeIngressos.php';

use MercadoPago\Client\Payment\PaymentClient;

$conexao = new Conexao();
$db = $conexao->getConexao();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['type']) || $data['type'] !== 'payment') {
    http_response_code(200);
    exit("Tipo de notificação inválido.");
}

$resource_id = $data['data']['id'];

try {

    
    $paymentClient = new PaymentClient();
    $payment = $paymentClient->get($resource_id);

    if (!$payment) {
        throw new Exception('Pagamento não encontrado no Mercado Pago');
    }

    $mp_status = $payment->status;
    $pedido_id = (int) $payment->external_reference;
    $transacao_gateway = $payment->id;

    $status_sistema = mapMPStatusToSystemStatus($mp_status);

    $db->beginTransaction();

    $stmt_pagamento = $db->prepare("
        UPDATE pagamento 
        SET status = ?, transacao_gateway = ?, data_aprovacao = ?, updated_at = NOW() 
        WHERE pedido_id = ?
    ");
    $stmt_pagamento->execute([
        $status_sistema,
        $transacao_gateway,
        ($mp_status === 'approved' ? date('Y-m-d H:i:s') : null),
        $pedido_id
    ]);

    $stmt_pedido = $db->prepare("
        UPDATE pedido 
        SET status = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    $stmt_pedido->execute([$status_sistema, $pedido_id]);

    if ($mp_status === 'approved') {
        GeradorDeIngressos::emitirIngressos($db, $pedido_id);
    }

    $db->commit();

    http_response_code(200);
    echo "OK";

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Erro Webhook MP (Pedido ID: {$pedido_id}): " . $e->getMessage());
    http_response_code(500);
}

function mapMPStatusToSystemStatus($mp_status) {
    switch ($mp_status) {
        case 'approved': return 'aprovado';
        case 'rejected': return 'recusado';
        case 'pending': return 'pendente';
        case 'refunded':
        case 'charged_back': return 'estornado';
        default: return 'pendente';
    }
}
?>
