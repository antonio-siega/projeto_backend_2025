<?php
// /api/webhook_mercadopago.php
// Este script não usa sessão

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mercado_pago.php';

// Necessário para a lógica de emissão
require_once __DIR__ . '/../IngressoService.php'; // Vamos criar essa classe auxiliar

$conexao = new Conexao();
$db = $conexao->getConexao();

// 1. Obter e Decodificar a notificação
$data = json_decode(file_get_contents('php://input'), true);

// A notificação deve ser do tipo 'payment'
if (!isset($data['type']) || $data['type'] !== 'payment') {
    http_response_code(400); 
    exit("Tipo de notificação inválido.");
}

$resource_id = $data['data']['id']; // ID do pagamento no Mercado Pago

try {
    // 2. Buscar o objeto Payment (para ter certeza do status)
    $payment = MercadoPago\Payment::find_by_id($resource_id);

    $mp_status = $payment->status;
    $pedido_id = (int)$payment->external_reference; 
    $transacao_gateway = $payment->id;
    
    $status_sistema = mapMPStatusToSystemStatus($mp_status);

    $db->beginTransaction();

    // 3. Atualizar a tabela 'pagamento'
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

    // 4. Atualizar a tabela 'pedido'
    $stmt_pedido = $db->prepare("
        UPDATE pedido 
        SET status = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    $stmt_pedido->execute([$status_sistema, $pedido_id]);

    // 5. EMISSÃO DO INGRESSO (Ação crítica)
    if ($mp_status === 'approved') {
        // Ingresso só é emitido mediante pagamento aprovado [cite: 70]
        IngressoService::emitirIngressos($db, $pedido_id);
    }
    // Se for 'estornado', você também precisa cancelar os ingressos. (Lógica a ser implementada em IngressoService)

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