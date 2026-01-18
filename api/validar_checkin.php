<?php
session_start();
require_once __DIR__ . '/../conecta.php';

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

    // 1. Verifica dispositivo
    $stmt_disp = $db->prepare("SELECT id FROM dispositivo_checkin WHERE uuid = ?");
    $stmt_disp->execute([$dispositivo_uuid]);
    $disp_data = $stmt_disp->fetch(PDO::FETCH_ASSOC);

    $dispositivo_db_id = $disp_data ? $disp_data['id'] : null;
    if (!$dispositivo_db_id) {
        $stmt_ins_disp = $db->prepare("INSERT INTO dispositivo_checkin (uuid) VALUES (?)");
        $stmt_ins_disp->execute([$dispositivo_uuid]);
        $dispositivo_db_id = $db->lastInsertId();
    }

    // 2. Busca ingresso
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

    // 3. Lógica de Status (Correção aqui: removido o UPDATE na tabela ingresso)
    if ($ingresso['status'] === 'utilizado') {
        $tentativa_duplicada = true;
        $mensagem = 'ATENÇÃO: Ingresso já utilizado anteriormente!';
        $status_resposta = 'utilizado'; 

    } elseif ($ingresso['status'] === 'emitido' || $ingresso['status'] === 'transferido') { 
        $stmt_update = $db->prepare("UPDATE ingresso SET status = 'utilizado', data_uso = NOW() WHERE id = ?");
        $stmt_update->execute([$ingresso_id]);

        $mensagem = 'CHECK-IN REALIZADO COM SUCESSO.';
        $status_resposta = 'sucesso';
    } else {
        $mensagem = "Ingresso inválido (Status: {$ingresso['status']}).";
        $status_resposta = 'invalido';
    }

    // 4. Grava Log na tabela checkin (Onde a coluna tentativa_duplicada realmente existe)
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
            'evento' => $ingresso['evento_nome'],
            'status_ingresso' => $ingresso['status'],
            'horario_validacao' => date('d/m/Y H:i:s')
        ]
    ]);
    
} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    // Exibe o erro real no card para facilitar o seu ajuste no banco
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no Banco: ' . $e->getMessage()]);
}
?>