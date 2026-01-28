<?php
session_start();
require 'conexao.php';

// 1. Segurança: Só deixa quem está logado entrar
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

// 2. Verifica se veio um ID para apagar
if (isset($_GET['id'])) {
    $id_agendamento = $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    // 3. O PULO DO GATO (Segurança Máxima)
    // O comando diz: "Delete o agendamento X, MAS SÓ SE ele pertencer a este usuário"
    // Isso impede que um usuário malandro mude o número na URL e apague a consulta de outra pessoa.
    $sql = "DELETE FROM agendamentos WHERE id = :id AND usuario_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id_agendamento);
    $stmt->bindValue(':user_id', $id_usuario);
    $stmt->execute();
}

// 4. Volta para o painel
header("Location: painel.php");
exit;
?>