<?php
include 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido.";
        exit();
    }

    // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Preparar a consulta
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    if ($stmt === false) {
        error_log("Erro na preparação da consulta: " . $conn->error);
        die("Erro interno. Tente novamente mais tarde.");
    }

    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o usuário.";
        error_log("Erro ao cadastrar: " . $stmt->error);
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
    <title>Cadastro de Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; }
        input { width: 100%; margin-bottom: 10px; padding: 10px; }
    </style>
</head>
<body>
    <form action="cadastrar.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
