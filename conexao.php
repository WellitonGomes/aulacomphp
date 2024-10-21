<?php
$servername = "localhost";
$username = "root";
$password = "escola";
$dbname = "loja_nova";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
