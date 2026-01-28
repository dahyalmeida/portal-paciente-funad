<?php
session_start();
require 'conexao.php';

// 1. Segurança: Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];

// 2. Busca o histórico COMPLETO (confirmados e pendentes)
$sql = "SELECT * FROM agendamentos WHERE usuario_id = :id ORDER BY data_consulta DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id_usuario);
$stmt->execute();
$lista = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico do Paciente - FUNAD</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            padding: 40px; 
            color: #000;
        }
        
        /* Cabeçalho Oficial */
        .cabecalho { 
            text-align: center; 
            margin-bottom: 40px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 20px; 
        }
        .cabecalho h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .cabecalho h2 { margin: 5px 0; font-size: 16px; font-weight: normal; }
        .cabecalho p { margin: 5px 0 0 0; font-size: 12px; color: #333; }

        /* Dados do Paciente */
        .dados-paciente {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Tabela */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        
        th, td { 
            border: 1px solid #000; 
            padding: 10px; 
            text-align: left; 
            font-size: 13px; 
        }
        
        th { background-color: #e0e0e0; font-weight: bold; text-transform: uppercase; }

        /* Rodapé do Relatório */
        .resumo { margin-top: 20px; font-size: 12px; text-align: right; font-weight: bold; }

        /* Botão de Imprimir (Some na impressão) */
        .btn-print { 
            display: block; 
            width: 200px; 
            margin: 0 auto 40px auto; 
            padding: 12px; 
            background: #333; 
            color: white; 
            text-align: center; 
            text-decoration: none; 
            border-radius: 6px; 
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
        }
        .btn-print:hover { background: #000; }

        /* Regra para Impressora: Esconde o botão */
        @media print { 
            .btn-print { display: none; } 
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <a href="#" onclick="window.print(); return false;" class="btn-print">🖨️ Imprimir Relatório</a>

    <div class="cabecalho">
        <h1>Governo do Estado da Paraíba</h1>
        <h2>FUNAD - Fundação Centro Integrado de Apoio ao Portador de Deficiência</h2>
        <p>Sistema de Agendamento Eletrônico - Relatório Geral</p>
    </div>

    <div class="dados-paciente">
        <strong>Paciente:</strong> <?php echo $nome_usuario; ?><br>
        <strong>Emissão do Documento:</strong> <?php echo date('d/m/Y \à\s H:i'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Data</th>
                <th style="width: 50%;">Especialidade / Serviço</th>
                <th style="width: 30%;">Situação</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($lista) > 0): ?>
                <?php foreach($lista as $item): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($item['data_consulta'])); ?></td>
                    <td><?php echo $item['especialidade']; ?></td>
                    <td><?php echo $item['status']; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align:center; padding: 20px;">
                        Nenhum registro encontrado no histórico deste paciente.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="resumo">
        Total de Registros Encontrados: <?php echo count($lista); ?>
    </div>

</body>
</html>