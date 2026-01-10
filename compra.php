<?php
include_once 'conecta.php';

$cliente_id = 1; // cliente simulado
$valor_ingresso = 100.00;
$valor_final = $valor_ingresso;

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['cupom'])) {
        $mensagem = "Digite um cupom para aplicar.";
        $tipo_mensagem = 'warning';

    } else {
        $codigo = strtoupper(trim($_POST['cupom']));
        $agora = date('Y-m-d H:i:s');

        // Busca cupom válido
        $sql = "SELECT * FROM cupom
                WHERE codigo = ?
                  AND periodo_ini <= ?
                  AND periodo_fim >= ?
                  AND limite_total > 0";

        $stmt = mysqli_prepare($bancodedados, $sql);
        
        mysqli_stmt_bind_param($stmt, "sss", $codigo, $agora, $agora);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) === 0) {
            $mensagem = "Cupom inválido, expirado ou esgotado.";
            $tipo_mensagem = 'danger';

        } else {
            $cupom = mysqli_fetch_assoc($resultado);

            // Verifica limite por cliente
            $sqlUso = "SELECT COUNT(*) total
                       FROM cupom_uso
                       WHERE cupom_id = ? AND cliente_id = ?";
            $stmtUso = mysqli_prepare($bancodedados, $sqlUso);
            mysqli_stmt_bind_param($stmtUso, "ii", $cupom['id'], $cliente_id);
            mysqli_stmt_execute($stmtUso);
            $resUso = mysqli_stmt_get_result($stmtUso);
            $uso = mysqli_fetch_assoc($resUso);
            mysqli_stmt_close($stmtUso);

            if ($uso['total'] >= $cupom['limite_cliente']) {
                $mensagem = "Você já atingiu o limite de uso deste cupom.";
                $tipo_mensagem = 'danger';

            } else {

                // Calcula desconto
                if ($cupom['tipo'] === 'percentual') {
                    $desconto = ($valor_ingresso * $cupom['valor']) / 100;
                } else {
                    $desconto = $cupom['valor'];
                }

                $valor_final = max(0, $valor_ingresso - $desconto);

                // Debita limite total
                $sqlUpdate = "UPDATE cupom
                              SET limite_total = limite_total - 1
                              WHERE id = ?";
                $stmtUpdate = mysqli_prepare($bancodedados, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "i", $cupom['id']);
                mysqli_stmt_execute($stmtUpdate);
                mysqli_stmt_close($stmtUpdate);

                // Registra uso
                $sqlInsert = "INSERT INTO cupom_uso (cupom_id, cliente_id)
                              VALUES (?, ?)";
                $stmtInsert = mysqli_prepare($bancodedados, $sqlInsert);
                mysqli_stmt_bind_param($stmtInsert, "ii", $cupom['id'], $cliente_id);
                mysqli_stmt_execute($stmtInsert);
                mysqli_stmt_close($stmtInsert);

                $mensagem = "Cupom aplicado com sucesso!";
                $tipo_mensagem = 'success';
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Compra</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/auth_new.css" rel="stylesheet">
</head>
<body>

<main class="main">
  <div class="container">
    <br>
    <h1 class="title">Finalizar compra</h1>

    <p><strong>Valor do ingresso:</strong>
      R$ <?= number_format($valor_ingresso, 2, ',', '.') ?>
    </p>

    <p><strong>Total a pagar:</strong>
      R$ <?= number_format($valor_final, 2, ',', '.') ?>
    </p>

    <?php if (!empty($mensagem)): ?>
      <div class="alert alert-<?= $tipo_mensagem ?>">
        <?= $mensagem ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="form mt-3">
      <div class="form-group w-100 mb-3">
        <label>Usar cupom</label>
        <input type="text" name="cupom" class="form-control" placeholder="Digite o cupom">
      </div>

      <input type="submit" class="button" value="Aplicar cupom">
    </form>

  </div>
</main>

</body>
</html>
