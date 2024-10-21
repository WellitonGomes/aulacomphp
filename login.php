<?php
session_start(); // Inicia uma nova sessão
include 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido.";
        exit();
    }

    // Prepara a consulta SQL para buscar o usuário pelo e-mail
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
    if ($stmt === false) {
        error_log("Erro na preparação da consulta: " . $conn->error);
        die("Erro interno. Tente novamente mais tarde.");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o e-mail foi encontrado
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifica se a senha inserida corresponde à senha armazenada
        if (password_verify($senha, $hashed_password)) {
            $_SESSION['email'] = $email; // Armazena o e-mail na sessão
            echo "Login bem-sucedido!";
            // Redireciona para uma página protegida, se necessário
            // header("Location: dashboard.php");
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "E-mail não encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; }
        input { width: 100%; margin-bottom: 10px; padding: 10px; }
    </style>
</head>
<body>
    <form action="login.php" method="post">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <input type="submit" value="Login">
    </form>
</body>
</html>
