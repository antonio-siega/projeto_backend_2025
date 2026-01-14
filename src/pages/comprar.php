<?php

require_once(__DIR__ . '/../../conecta.php');

if (!isset($_GET['id'])) {
    die("Pedido não informado.");
}

$pedido_id = (int) $_GET['id'];

$sql = "
SELECT p.*, c.nome AS cliente_nome 
FROM pedido p 
LEFT JOIN cliente c ON p.cliente_id = c.id
WHERE p.id = $pedido_id
";

$resultado = mysqli_query($bancodedados, $sql);
$pedido = mysqli_fetch_assoc($resultado);

if (!$pedido) {
    die("Pedido não encontrado.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../styles/home.css">
</head>
<body>

<main class="main">

    <h1 class="title">
        Finalizar <span class="contrast">Compra</span>
    </h1>

    <form id="form-pagar" class="form">

        <p class="text"><b>Cliente:</b> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
        <p class="text"><b>Quantidade:</b> <?= $pedido['quantidade'] ?></p>
        <p class="text"><b>Valor total:</b> R$ <?= number_format($pedido['total_liquido'], 2, ',', '.') ?></p>

        <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">

        <button type="submit" class="button">
            Finalizar pagamento
        </button>
    </form>

    <div id="log" class="text">
        Aguardando ação...
    </div>

</main>

<script>
document.getElementById('form-pagar').addEventListener('submit', async function(e) {
    e.preventDefault();

    const logBox = document.getElementById('log');
    logBox.innerText = 'Gerando pagamento...';

    const pedidoIdValue = this.querySelector('input[name="pedido_id"]').value;


    const params = new URLSearchParams();
    params.append('pedido_id', pedidoIdValue);

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
        console.log("Resposta bruta:", text); 

        const result = JSON.parse(text);

        if (result.status === 'ok' && result.link_pagamento) {
            window.location.href = result.link_pagamento;
        } else {
            logBox.innerText = 'Erro: ' + (result.mensagem || 'Falha ao gerar');
        }

    } catch (e) {
        console.error(e);
        logBox.innerText = 'Erro de conexão ou resposta inválida.';
    }
});
</script>

</body>
</html>
