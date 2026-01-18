<?php
require_once(__DIR__ . '/../../conecta.php');
session_start();

$db = (new Conexao())->getConexao();

if (!isset($_GET['id'])) {
    die("Pedido não informado.");
}

$pedido_id = (int) $_GET['id'];

$sql = "
SELECT p.*, c.nome AS cliente_nome 
FROM pedido p 
LEFT JOIN cliente c ON p.cliente_id = c.id
WHERE p.id = :id
";

$stmt = $db->prepare($sql);
$stmt->execute(['id' => $pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/pages/index.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .contrast { color: var(--color-green); }
        .contrast-logo { color: var(--color-green-100); font-weight: 800; }
    </style>
</head>
<body>

    <main class="main" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
        
        <h1 class="title">
            FINALIZAR <span class="contrast">COMPRA</span>
        </h1>

        <div class="form" style="width: 40rem; text-align: center;">
            <p class="logo" style="font-size: 2rem; font-weight: 700;">
                PEDIDO <span class="contrast-logo">#<?= $pedido_id ?></span>
            </p>
            
            <div style="text-align: left; width: 100%; margin: 1.5rem 0; font-size: 1.4rem; color: var(--color-gray-200);">
                <p style="margin-bottom: 0.5rem;"><b>Cliente:</b> <span style="color: var(--black);"><?= htmlspecialchars($pedido['cliente_nome']) ?></span></p>
                <p style="margin-bottom: 0.5rem;"><b>Quantidade:</b> <span style="color: var(--black);"><?= $pedido['quantidade'] ?></span></p>
                <p><b>Valor total:</b> <span style="color: var(--black);">R$ <?= number_format($pedido['total_liquido'], 2, ',', '.') ?></span></p>
            </div>

            <form id="form-pagar">
                <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
                <button type="submit" class="button" style="text-decoration: none; width: 100%; border: none; cursor: pointer;">
                   <i class="ph-fill ph-credit-card" style="margin-right: 1rem;"></i> Finalizar pagamento
                </button>
            </form>
        </div>

        <br>
        
        <div id="log" class="link" style="font-size: 1.4rem;">
            Aguardando ação...
        </div>

    </main>

<script>
document.getElementById('form-pagar').addEventListener('submit', async function(e) {
    e.preventDefault();
    const logBox = document.getElementById('log');
    logBox.innerText = 'Gerando pagamento...';

    const pedidoIdValue = this.querySelector('input[name="pedido_id"]').value;

    try {
        const response = await fetch('/projeto_backend_2025/api/criar_pagamento.php', {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache'
            },
            body: 'pedido_id=' + encodeURIComponent(pedidoIdValue)
        });

        const text = await response.text();
        const result = JSON.parse(text);

        if (result.status === 'ok' && result.link_pagamento) {
            window.location.href = result.link_pagamento;
        } else {
            window.location.href = 'pagamento_erro.php?pedido_id=' + pedidoIdValue;
        }
    } catch (e) {
        window.location.href = 'pagamento_erro.php?pedido_id=' + pedidoIdValue;
    }
});
</script>
</body>
</html>