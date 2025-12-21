<?php
// painel_checkin.php
session_start();
$_SESSION['usuario_id'] = 1; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Controle - Check-in</title>
</head>
<body>

    <h1>Sistema de Check-in IF</h1>
    
    <div class="scanner-box">
        <h3>Validar Ingresso</h3>
        <form id="checkinForm">
            <input type="text" name="token_ingresso" id="token_input" placeholder="Cole o código do ingresso aqui..." required autofocus>
            <input type="hidden" name="dispositivo_uuid" value="TOTEM_01_SALA">
            <button type="submit">EFETUAR ENTRADA</button>
        </form>

        <div id="feedback"></div>
    </div>

    <script src="script_checkin.js"></script>
</body>
</html>