<?php
session_start();
require 'conexao.php';

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado.");
}

// 2. Verifica se o ID da consulta foi enviado
if (!isset($_GET['id'])) {
    die("Erro: Nenhuma consulta selecionada.");
}

$id_agendamento = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// 3. Busca os dados no banco
// (A segurança garante que você só veja SEUS próprios comprovantes)
$sql = "SELECT * FROM agendamentos WHERE id = :id AND usuario_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id_agendamento);
$stmt->bindValue(':user_id', $id_usuario);
$stmt->execute();
$dados = $stmt->fetch();

// Se não achar nada (ou se o usuário tentar ver consulta de outro)
if (!$dados) {
    die("Comprovante não encontrado ou acesso negado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprovante - FUNAD</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background-color: #555; display: flex; justify-content: center; padding: 50px; margin: 0; }
        
        .papel { 
            background: #fffef0; /* Cor de papel amarelado */
            width: 400px; 
            padding: 40px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5); 
            border: 1px solid #ccc;
        }
        
        .cabecalho { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-weight: bold; font-size: 18px; }
        .subtitulo { font-size: 12px; margin-top: 5px; }
        
        .campo { margin-bottom: 15px; }
        .label { font-size: 14px; color: #666; font-weight: bold; }
        .valor { font-size: 18px; color: #000; display: block; margin-top: 5px; }
        
        .rodape { margin-top: 40px; border-top: 2px dashed #000; padding-top: 20px; text-align: center; font-size: 12px; color: #666; }
        
        button { display: block; width: 100%; padding: 15px; background: #333; color: white; border: none; margin-top: 20px; cursor: pointer; font-size: 16px; }
        button:hover { background: #000; }
        
        /* Esconde o botão preto na hora de imprimir no papel */
        @media print { 
            body { background: white; padding: 0; }
            button { display: none; } 
            .papel { box-shadow: none; border: none; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="papel">
        <div class="cabecalho">
            <div class="logo">GOVERNO DA PARAÍBA</div>
            <div class="logo">FUNAD - PB</div>
            <div class="subtitulo">Comprovante de Agendamento Eletrônico</div>
        </div>

        <div class="campo">
            <span class="label">PACIENTE:</span>
            <span class="valor"><?php echo $_SESSION['nome_usuario']; ?></span>
        </div>

        <div class="campo">
            <span class="label">ESPECIALIDADE/SETOR:</span>
            <span class="valor"><?php echo $dados['especialidade']; ?></span>
        </div>

        <div class="campo">
            <span class="label">DATA DO ATENDIMENTO:</span>
            <span class="valor"><?php echo date('d/m/Y', strtotime($dados['data_consulta'])); ?></span>
        </div>

        <div class="campo">
            <span class="label">STATUS:</span>
            <span class="valor" style="background: #eee; display: inline-block; padding: 2px 5px;"><?php echo $dados['status']; ?></span>
        </div>

        <div class="rodape">
            Este documento deve ser apresentado na recepção.<br>
            Gerado em: <?php echo date('d/m/Y H:i'); ?>
        </div>

        <button onclick="window.print()">🖨️ IMPRIMIR COMPROVANTE</button>
    </div>

</body>
</html>