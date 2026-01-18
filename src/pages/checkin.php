<?php
require_once(__DIR__ . '/../../conecta.php'); 
session_start();

// simulação de usuário para o sistema permitir o teste
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = 1; 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Check-in</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/index.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .contrast { color: var(--color-green); }
        .contrast-logo { color: var(--color-green-100); font-weight: 800; }
        
        
        .status-msg { font-size: 1.6rem; font-weight: 700; margin-bottom: 1rem; display: block; }
        .sucesso-text { color: var(--color-green); }
        .erro-text { color: #dc3545; }
        .alerta-text { color: #ffc107; }
        
        .detalhes-ingresso {
            text-align: left;
            background: var(--color-gray-50);
            padding: 1.5rem;
            border-radius: 0.8rem;
            margin-top: 1.5rem;
            font-size: 1.3rem;
            border: 1px solid var(--color-gray-100);
        }
    </style>
</head>
<body>

    <main class="main" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
        
        <h1 class="title">
            VALIDAR <span class="contrast">CHECK-IN</span>
        </h1>

        <div class="form" id="card-checkin" style="width: 40rem; text-align: center;">
            <div id="conteudo-formulario">
                <p class="logo" style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">
                    INSIRA O CÓDIGO DO <span class="contrast-logo">INGRESSO</span>
                </p>

                <form id="form-executar-checkin">
                    <div class="input-wrapper" style="width: 100%; margin-bottom: 1.5rem;">
                        <span class="input-label">TOKEN</span>
                        <input type="text" name="token_ingresso" placeholder="Cole o código do ingresso" required autofocus>
                    </div>
                    
                    <input type="hidden" name="dispositivo_uuid" value="WEB-STATION-01">

                    <button type="submit" class="button" style="border: none; cursor: pointer; width: 100%;">
                        <i class="ph-fill ph-qr-code" style="margin-right: 1rem;"></i> Realizar Check-in
                    </button>
                </form>
            </div>

            <div id="resultado-checkin" style="display: none;">
                <span id="txt-status" class="status-msg"></span>
                <p id="txt-mensagem" style="font-size: 1.4rem; color: var(--color-gray-200);"></p>
                
                <div id="info-detalhes" class="detalhes-ingresso"></div>

                <button onclick="resetarFormulario()" class="button button-secundary" style="margin-top: 1.5rem; width: 100%; border: none; cursor: pointer;">
                    <i class="ph-fill ph-arrow-left" style="margin-right: 1rem;"></i> Voltar para o início
                </button>
            </div>
        </div>

        <br>

        <a href="../../index.html" class="link" style="font-size: 1.4rem;">
            Voltar para o início
        </a>

    </main>

    <script>
        const formArea = document.getElementById('conteudo-formulario');
        const resultArea = document.getElementById('resultado-checkin');

        document.getElementById('form-executar-checkin').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('../../api/validar_checkin.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                formArea.style.display = 'none';
                resultArea.style.display = 'block';

                const txtStatus = document.getElementById('txt-status');
                const txtMsg = document.getElementById('txt-mensagem');
                const infoDet = document.getElementById('info-detalhes');

                
                if (data.status === 'sucesso') {
                    txtStatus.innerText = "CHECK-IN REALIZADO";
                    txtStatus.className = "status-msg sucesso-text";
                } else if (data.status === 'utilizado') {
                    txtStatus.innerText = "ALERTA: DUPLICADO";
                    txtStatus.className = "status-msg alerta-text";
                } else {
                    txtStatus.innerText = "INGRESSO INVÁLIDO";
                    txtStatus.className = "status-msg erro-text";
                }

                txtMsg.innerText = data.mensagem;

                if (data.detalhes) {
                    infoDet.style.display = 'block';
                    infoDet.innerHTML = `
                        <b>Evento:</b> ${data.detalhes.evento}<br>
                        <b>Titular:</b> ${data.detalhes.nome}<br>
                        <b>Documento:</b> ${data.detalhes.documento}
                    `;
                } else {
                    infoDet.style.display = 'none';
                }

            } catch (error) {
                alert("Erro ao processar check-in. Verifique a API.");
            }
        });

        function resetarFormulario() {
            resultArea.style.display = 'none';
            formArea.style.display = 'block';
            document.getElementById('form-executar-checkin').reset();
        }
    </script>
</body>
</html>