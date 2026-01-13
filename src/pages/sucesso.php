<?php
    session_start();
    // Captura o ID do pedido via URL para exibição
    $pedido_id = $_GET['pedido_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Aprovado</title>
    <link rel="stylesheet" href="../styles/home.css">
</head>
<body>

    <main class="main">
        
        <h1 class="title">
            PAGAMENTO <span class="contrast">APROVADO</span>
        </h1>

        <div class="form">
            <p class="logo">
                PEDIDO <span class="contrast-logo">#<?= $pedido_id ?></span>
            </p>
            
            <p style="color: var(--green-100); margin-bottom: 1rem;">
                Seu ingresso foi gerado com sucesso!
            </p>

            <a href="meus_ingressos.php" class="button" style="text-decoration: none; width: 100%;">
                <i class="ph-fill ph-ticket"></i> Ver Ingressos
            </a>
        </div>

        <br>
        
        <a href="../index.html" class="link">
            Voltar para o início
        </a>

    </main>

</body>
</html>