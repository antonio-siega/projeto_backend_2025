<?php
require_once __DIR__ . '/../../includes/db.php'; 
session_start();

$pedido_id = $_GET['id'] ?? null; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Aprovado | IF Ticket</title>
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
            PAGAMENTO <span class="contrast">APROVADO</span>
        </h1>

        <div class="form" style="width: 40rem; text-align: center;">
            <p class="logo" style="font-size: 2rem; font-weight: 700;">
                PEDIDO <span class="contrast-logo">#<?= htmlspecialchars($pedido_id) ?></span>
            </p>
            
            <p style="color: var(--color-gray-200); font-size: 1.4rem; margin-bottom: 1rem;">
                Seu ingresso foi gerado com sucesso e já está disponível.
            </p>

            <a href="meus_ingressos.php?id=<?= $pedido_id ?>" class="button" style="text-decoration: none; width: 100%;">
                <i class="ph-fill ph-ticket" style="margin-right: 1rem;"></i> Ver Ingressos
            </a>
        </div>

        <br>
        
        <a href="../index.html" class="link" style="font-size: 1.4rem;">
            Voltar para o início
        </a>

    </main>

</body>
</html>