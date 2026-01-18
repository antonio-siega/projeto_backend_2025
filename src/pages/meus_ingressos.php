<?php

require_once __DIR__ . '/../../includes/db.php'; 
session_start();

$pedido_id = $_GET['id'] ?? 1; 

$db = (new Conexao())->getConexao();

$stmt = $db->prepare(" 
    SELECT 
        i.*, 
        e.nome AS evento_nome, 
        e.data_inicio, 
        l.endereco AS evento_local
    FROM ingresso i
    JOIN pedido p ON i.pedido_id = p.id
    JOIN setor s ON p.setor_id = s.id
    JOIN evento e ON s.evento_id = e.id
    JOIN local l ON e.local_id = l.id
    WHERE i.pedido_id = ?
");
$stmt->execute([$pedido_id]);
$ingressos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Ingressos | IF Ticket</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/pages/index.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>

        .ticket-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            padding: 2rem;
            justify-content: center;
        }

        .ticket-card {
            background: var(--white); 
            border-left: 0.8rem solid var(--color-green); 
            border-radius: 1.6rem;
            box-shadow: var(--shadow); 
            width: 35rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ticket-header {
            background: var(--color-green-100);
            padding: 1.5rem;
            text-align: center;
        }

        .event-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--color-green-200);
            text-transform: uppercase;
        }

        .ticket-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .qr-wrapper {
            background: #fff;
            padding: 1rem;
            border: 1px solid var(--color-gray-100);
            border-radius: 1rem;
        }

        .info-grid {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            font-size: 1.3rem;
            border-top: 1px dashed var(--color-gray-100);
            padding-top: 1.5rem;
        }

        .info-item label {
            display: block;
            color: var(--color-gray-200);
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        .info-item span {
            color: var(--black); /* */
            font-weight: 700;
        }

        .ticket-footer {
            background: var(--color-gray-50);
            padding: 1rem;
            text-align: center;
            font-family: monospace;
            font-size: 1.1rem;
            color: var(--color-gray-200);
        }

        .status-badge {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 0.4rem 1rem;
            border-radius: 5rem;
            font-size: 1.1rem;
            font-weight: 700;
        }
        .status-emitido { background: #d4edda; color: #155724; }
        .status-utilizado { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <main class="main">
        <h1 class="section-title" style="text-align: center; margin-top: 2rem;">Seus Ingressos</h1>
        
        <div class="ticket-container">
            <?php foreach ($ingressos as $ing): ?>
                <div class="ticket-card">
                    <div class="ticket-header">
                        <div class="event-name"><?= htmlspecialchars($ing['evento_nome']) ?></div>
                    </div>

                    <div class="ticket-body">
                        <span class="status-badge status-<?= strtolower($ing['status']) ?>">
                            <?= strtoupper($ing['status']) ?>
                        </span>

                        <div class="qr-wrapper">
                            <img src="<?= $ing['qrcode'] ?>" alt="QR Code" width="160">
                        </div>

                        <div class="info-grid">
                            <div class="info-item" style="grid-column: span 2;">
                                <label>Titular</label>
                                <span><?= htmlspecialchars($ing['titular_nome']) ?></span>
                            </div>
                            <div class="info-item">
                                <label>Data e Hora</label>
                                <span><?= date('d/m/Y H:i', strtotime($ing['data_inicio'])) ?></span>
                            </div>
                            <div class="info-item">
                                <label>Local</label>
                                <span><?= htmlspecialchars($ing['evento_local']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-footer">
                        ID: <?= $ing['identificador_unico'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>