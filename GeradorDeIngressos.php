<?php


class GeradorDeIngressos {

    public static function emitirIngressos(PDO $db, $pedidoId) {
        // 1. Obter dados do Pedido e Cliente (titular)
        $stmt = $db->prepare("
            SELECT p.quantidade, c.nome AS titular_nome, c.documento AS titular_documento
            FROM pedido p
            JOIN cliente c ON p.cliente_id = c.id
            WHERE p.id = ? AND p.status = 'aprovado'
        ");
        $stmt->execute([$pedidoId]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            error_log("Tentativa de emissão para pedido não aprovado ou inexistente: ID {$pedidoId}");
            return false;
        }

        $quantidade = $pedido['quantidade'];
        $titularNome = $pedido['titular_nome'];
        $titularDocumento = $pedido['titular_documento'];

        $stmt_insert = $db->prepare("
            INSERT INTO ingresso 
            (pedido_id, identificador_unico, qrcode, status, titular_nome, titular_documento, data_emissao) 
            VALUES (?, ?, ?, 'emitido', ?, ?, NOW())
        ");
        
        for ($i = 0; $i < $quantidade; $i++) {
          
            $token = md5(uniqid(rand(), true));
            $qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $token;
            $stmt_insert->execute([
                $pedidoId, 
                $token, 
                $qrcode_url, 
                $titularNome, 
                $titularDocumento
            ]);
        }
        return true;
    }
}

?>