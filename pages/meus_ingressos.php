<?php
// meus_ingressos.php
require_once __DIR__ . '/../includes/db.php'; 
session_start();

$pedido_id = $_GET['pedido_id'] ?? 1; 

$db = (new Conexao())->getConexao();
$stmt = $db->prepare("SELECT * FROM ingresso WHERE pedido_id = ?");
$stmt->execute([$pedido_id]);
$ingressos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Ingressos</title>
</head>
<body>

    <?php foreach ($ingressos as $ing): ?>
        <div class="ticket">
            <div class="ticket-header">INGRESSO DIGITAL</div>
            <div class="qr-area">
                <img src="<?= $ing['qrcode'] ?>" alt="QR Code" width="180">
            </div>
            <div class="token-text">
                <?= $ing['identificador_unico'] ?>
            </div>
            <div class="footer">
                <strong>Titular:</strong> <?= $ing['titular_nome'] ?><br>
                <strong>Status:</strong> <?= strtoupper($ing['status']) ?>
            </div>
        </div>
    <?php endforeach; ?>

</body>
</html>