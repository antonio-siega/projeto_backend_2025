<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$token_ingresso = $_POST['token_ingresso'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null; 
$dispositivo_uuid = $_POST['dispositivo_uuid'] ?? 'NAO_INFORMADO'; 

if (!$token_ingresso || !$usuario_id) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso negado.']);
    exit;
}

$conexao = new Conexao();
$db = $conexao->getConexao();

try {
    $db->beginTransaction();

    $stmt_disp = $db->prepare("SELECT id FROM dispositivo_checkin WHERE uuid = ?");
    $stmt_disp->execute([$dispositivo_uuid]);
    $disp_data = $stmt_disp->fetch(PDO::FETCH_ASSOC);

    if (!$disp_data) {
        $stmt_ins_disp = $db->prepare("INSERT INTO dispositivo_checkin (uuid) VALUES (?)");
        $stmt_ins_disp->execute([$dispositivo_uuid]);
        $dispositivo_db_id = $db->lastInsertId();
    } else {
        $dispositivo_db_id = $disp_data['id'];
    }

    
    $stmt = $db->prepare("
        SELECT i.id as ingresso_id, i.status, i.titular_nome, i.titular_documento, e.nome AS evento_nome
        FROM ingresso i
        JOIN pedido p ON i.pedido_id = p.id
        JOIN setor s ON p.setor_id = s.id
        JOIN evento e ON s.evento_id = e.id
        WHERE i.identificador_unico = ?
        AND e.status = 'publicado'
        FOR UPDATE
    ");
    $stmt->execute([$token_ingresso]); 
    $ingresso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ingresso) {
        if ($db->inTransaction()) $db->rollBack();
        echo json_encode(['status' => 'invalido', 'mensagem' => 'Ingresso não encontrado ou inválido.']);
        exit;
    }
    
    $ingresso_id = $ingresso['ingresso_id'];
    $tentativa_duplicada = false;
    $status_resposta = '';
    $mensagem = '';


    if ($ingresso['status'] === 'utilizado') {
        $tentativa_duplicada = true;
        $mensagem = 'ATENÇÃO: Ingresso já utilizado!';
        $status_resposta = 'utilizado';

        $stmt_duplicado = $db->prepare("UPDATE ingresso SET tentativas_duplicadas = tentativas_duplicadas + 1 WHERE id = ?");
        $stmt_duplicado->execute([$ingresso_id]);

    } elseif ($ingresso['status'] === 'emitido' || $ingresso['status'] === 'transferido') { 
  
        $stmt_update = $db->prepare("UPDATE ingresso SET status = 'utilizado', data_uso = NOW() WHERE id = ?");
        $stmt_update->execute([$ingresso_id]);

        $mensagem = 'CHECK-IN REALIZADO COM SUCESSO.';
        $status_resposta = 'sucesso';
    } else {
        $mensagem = "Ingresso em status '{$ingresso['status']}' não pode ser utilizado.";
        $status_resposta = 'invalido';
    }


    if ($status_resposta === 'sucesso' || $status_resposta === 'utilizado') {
        $stmt_checkin = $db->prepare("
            INSERT INTO checkin (ingresso_id, dispositivo_id, data_hora, tentativa_duplicada)
            VALUES (?, ?, NOW(), ?)
        ");
        $stmt_checkin->execute([
            $ingresso_id, 
            $dispositivo_db_id, 
            $tentativa_duplicada ? 1 : 0
        ]);
    }
    
    $db->commit();

    echo json_encode([
        'status' => $status_resposta,
        'mensagem' => $mensagem,
        'detalhes' => [
            'nome' => $ingresso['titular_nome'],
            'documento' => $ingresso['titular_documento'],
            'evento' => $ingresso['evento_nome']
        ]
    ]);
    
} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    error_log("Erro no check-in: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro interno do servidor.']);
}
?>