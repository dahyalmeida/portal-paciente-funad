<?php
// conexao.php
$host = 'localhost';
$dbname = 'funad_db';
$username = 'root';
$password = ''; // Senha vazia no XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>