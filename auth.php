<?php
session_start();
require 'conexao.php'; // Chama a conexão que já fizemos

// Recebe os dados do formulário
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];

// Verifica no banco de dados
$sql = "SELECT * FROM usuarios WHERE cpf = :cpf AND senha = :senha";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cpf', $cpf);
$stmt->bindValue(':senha', $senha);
$stmt->execute();

// Se encontrou alguém (linha > 0)
if ($stmt->rowCount() > 0) {
    $dado = $stmt->fetch();
    
    // Salva na sessão
    $_SESSION['id_usuario'] = $dado['id'];
    $_SESSION['nome_usuario'] = $dado['nome'];

    // Manda para o Painel
    header("Location: painel.php");
} else {
    // Se errou a senha
    echo "<script>
            alert('CPF ou Senha incorretos!');
            window.location.href = 'index.php';
          </script>";
}
?>