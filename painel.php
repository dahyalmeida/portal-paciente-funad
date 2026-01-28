<?php
// --- BLOCO DE SEGURANÇA E DEBUG ---
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario'])) { header("Location: index.php"); exit; }

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM agendamentos WHERE usuario_id = :id ORDER BY data_consulta DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id_usuario);
$stmt->execute();
$agendamentos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Consultas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f3f4f6; 
            margin: 0; 
            color: #334155;
        }
        
        /* NAVBAR */
        .navbar { 
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            padding: 15px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            color: white;
        }
        
        .navbar h2 { margin: 0; font-size: 18px; font-weight: 600; }
        .navbar h2 span { color: #cbd5e1; font-weight: 400; }

        /* Botão Sair */
        .btn-sair { 
            background-color: #ffffff; 
            color: #dc2626; 
            text-decoration: none; 
            padding: 8px 20px; 
            border-radius: 6px; 
            font-size: 14px; 
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        .btn-sair:hover { background-color: #f8fafc; transform: translateY(-1px); }

        /* CONTAINER */
        .container { 
            max-width: 950px; 
            margin: 40px auto; 
            padding: 40px; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        h3 { font-size: 24px; margin-top: 0; color: #0f172a; font-weight: 700; }

        /* --- MUDANÇA AQUI: BOTÃO NOVO AGORA É VERDE --- */
        .btn-novo { 
            display: inline-block; 
            background-color: #28a745; /* Verde Sucesso */
            color: white; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
            transition: 0.2s; 
        }
        .btn-novo:hover { 
            background-color: #218838; /* Verde Escuro */
            transform: translateY(-1px); 
        }
        
        /* Link do Relatório */
        .btn-relatorio {
            margin-right: 15px; 
            text-decoration: none; 
            color: #64748b; 
            font-weight: 600; 
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }
        .btn-relatorio:hover { color: #1e293b; text-decoration: underline; }

        /* TABELA */
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: 20px; }
        
        th { 
            text-align: left; 
            padding: 15px 20px; 
            color: #64748b; 
            font-size: 12px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        td { 
            padding: 16px 20px; 
            background: #fff; 
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle; 
            color: #334155;
            font-size: 14px;
        }
        
        tbody tr:hover td { background-color: #f8fafc; }
        td.acoes { white-space: nowrap; width: 150px; text-align: center; }

        /* ÍCONES */
        .btn-icone {
            display: inline-flex; justify-content: center; align-items: center;
            width: 36px; height: 36px;
            text-decoration: none; border-radius: 8px; font-size: 16px; margin: 0 4px;
            transition: all 0.2s;
        }
        .azul { background-color: #f0f9ff; color: #0284c7; } .azul:hover { background-color: #0284c7; color: white; }
        .amarelo { background-color: #fffbeb; color: #d97706; } .amarelo:hover { background-color: #f59e0b; color: white; }
        .vermelho { background-color: #fef2f2; color: #ef4444; } .vermelho:hover { background-color: #ef4444; color: white; }

        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2><span>Olá,</span> <?php echo $_SESSION['nome_usuario']; ?></h2>
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap;">
            <h3>Meus Agendamentos</h3>
            <div style="display: flex; align-items: center;">
                <a href="relatorio_geral.php" target="_blank" class="btn-relatorio">
                    📄 Imprimir Histórico
                </a>

                <a href="agendar.php" class="btn-novo">+ Nova Consulta</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Especialidade</th>
                    <th>Situação</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($agendamentos) > 0): ?>
                    <?php foreach($agendamentos as $item): ?>
                    
                    <?php
                        $status = $item['status'];
                        $cor_fundo = '#fff7ed'; $cor_texto = '#c2410c'; // Pendente
                        if($status == 'Confirmado') { $cor_fundo = '#ecfdf5'; $cor_texto = '#047857'; } // Confirmado
                    ?>

                    <tr>
                        <td style="font-weight: 600; color: #1e293b;">
                            <?php echo date('d/m/Y', strtotime($item['data_consulta'])); ?>
                        </td>
                        <td><?php echo $item['especialidade']; ?></td>
                        <td>
                            <span class="status-badge" style="background-color: <?php echo $cor_fundo; ?>; color: <?php echo $cor_texto; ?>;">
                                <?php echo $status; ?>
                            </span>
                        </td>
                        
                        <td class="acoes">
                            <a href="comprovante.php?id=<?php echo $item['id']; ?>" target="_blank" class="btn-icone azul" title="Imprimir">🖨️</a>
                            <a href="editar.php?id=<?php echo $item['id']; ?>" class="btn-icone amarelo" title="Editar">✏️</a>
                            <a href="excluir.php?id=<?php echo $item['id']; ?>" class="btn-icone vermelho" onclick="return confirm('Cancelar consulta?');" title="Cancelar">✖</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; padding:50px; color: #64748b;">Nenhum agendamento encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>