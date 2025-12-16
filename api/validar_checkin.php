<?php
// /api/validar_checkin.php
session_start(); // Necessário para pegar o ID do operador

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// 1. **AUTENTICAÇÃO E DADOS**
$token_ingresso = $_POST['token_ingresso'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null; // ID do operador de portaria
$dispositivo_uuid = $_POST['dispositivo_uuid'] ?? 'NAO_INFORMADO'; 

if (!$token_ingresso || !$usuario_id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso negado ou Token ausente.']);
    exit;
}

$conexao = new Conexao();
$db = $conexao->getConexao();

try {
    $db->beginTransaction();

    // 2. Buscar Ingresso e Dados do Cliente/Evento
    $stmt = $db->prepare("
        SELECT i.id, i.status, i.titular_nome, i.titular_documento, e.nome AS evento_nome, d.id as dispositivo_db_id
        FROM ingresso i
        JOIN pedido p ON i.pedido_id = p.id
        JOIN setor s ON p.setor_id = s.id
        JOIN evento e ON s.evento_id = e.id
        LEFT JOIN dispositivo_checkin d ON d.uuid = ?
        WHERE i.identificador_unico = ?
    ");
    $stmt->execute([$dispositivo_uuid, $token_ingresso]);
    $ingresso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ingresso) {
        echo json_encode(['status' => 'invalido', 'mensagem' => 'Ingresso não encontrado ou inválido.']);
        $db->commit();
        exit;
    }
    
    $ingresso_id = $ingresso['id'];
    $dispositivo_db_id = $ingresso['dispositivo_db_id'];
    $tentativa_duplicada = false;
    $status_resposta = '';
    $mensagem = '';
    $detalhes = [
        'nome' => $ingresso['titular_nome'],
        'documento' => $ingresso['titular_documento'],
        'evento' => $ingresso['evento_nome']
    ];

    // 3. Validação do Status
    if ($ingresso['status'] === 'utilizado') {
        // Regra de Negócio: Um ingresso é de uso único; após check-in é indisponível para novo acesso[cite: 22].
        $tentativa_duplicada = true;
        $mensagem = 'ATENÇÃO: Ingresso já utilizado! Tentativa duplicada registrada.';
        $status_resposta = 'utilizado';

    } elseif ($ingresso['status'] === 'emitido' || $ingresso['status'] === 'transferido') { 
        // 4. Check-in Válido: Atualizar status do Ingresso
        $stmt_update = $db->prepare("
            UPDATE ingresso SET status = 'utilizado', data_uso = NOW() WHERE id = ?
        ");
        $stmt_update->execute([$ingresso_id]);

        $mensagem = 'CHECK-IN REALIZADO COM SUCESSO.';
        $status_resposta = 'sucesso';
        
    } else {
        // 'cancelado' ou outro status não permitido para uso
        $mensagem = "Ingresso em status '{$ingresso['status']}' não pode ser utilizado.";
        $status_resposta = 'invalido';
    }

    // 5. Registrar a Tentativa/Check-in na tabela 'checkin' [cite: 12]
    $stmt_checkin = $db->prepare("
        INSERT INTO checkin (ingresso_id, dispositivo_id, data_hora, tentativa_duplicada)
        VALUES (?, ?, NOW(), ?)
    ");
    $stmt_checkin->execute([
        $ingresso_id, 
        $dispositivo_db_id, // Pode ser NULL se o dispositivo_checkin não foi cadastrado
        $tentativa_duplicada
    ]);
    
    $db->commit();

    // 6. Retorno final para o Frontend
    echo json_encode([
        'status' => $status_resposta,
        'mensagem' => $mensagem,
        'detalhes' => $detalhes
    ]);
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Erro no processamento de check-in: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno do servidor.']);
}
?>