<?php
session_start();
require_once "../conecta.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action == 'login') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            header("Location: auth.php?error=empty_fields");
            exit();
        }

        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = mysqli_prepare($bancodedados, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_perfil'] = $user['perfil'];
                
                header("Location: ../home.php"); // Redirect to home
                exit();
            } else {
                header("Location: auth.php?error=invalid_credentials");
                exit();
            }
        } else {
            header("Location: auth.php?error=invalid_credentials");
            exit();
        }
    } elseif ($action == 'register') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            header("Location: auth.php?error=empty_fields&form=register");
            exit();
        }

        if ($senha !== $confirmar_senha) {
            header("Location: auth.php?error=passwords_dont_match&form=register");
            exit();
        }

        // Check if email already exists
        $sql = "SELECT id FROM usuario WHERE email = ?";
        $stmt = mysqli_prepare($bancodedados, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: auth.php?error=email_exists&form=register");
            exit();
        }

        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (nome, email, senha, perfil, ativo) VALUES (?, ?, ?, 'cliente', 1)";
        $stmt = mysqli_prepare($bancodedados, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($bancodedados);
            $_SESSION['user_nome'] = $nome;
            $_SESSION['user_perfil'] = 'cliente';
            
            header("Location: ../home.php");
            exit();
        } else {
            header("Location: auth.php?error=registration_failed&form=register");
            exit();
        }
    }
} else {
    header("Location: auth.php");
    exit();
}
?>