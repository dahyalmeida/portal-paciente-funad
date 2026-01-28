<?php
session_start();
require 'conexao.php';

// Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$mensagem = "";

// Se enviou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $especialidade = $_POST['especialidade'];
    $usuario_id = $_SESSION['id_usuario'];
    $status = "Pendente"; // Status padrão

    $sql = "INSERT INTO agendamentos (usuario_id, data_consulta, especialidade, status) VALUES (:uid, :data, :esp, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uid', $usuario_id);
    $stmt->bindValue(':data', $data);
    $stmt->bindValue(':esp', $especialidade);
    $stmt->bindValue(':status', $status);

    if ($stmt->execute()) {
        header("Location: painel.php");
        exit;
    } else {
        $mensagem = "Erro ao agendar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Agendamento</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
            width: 400px; 
            text-align: center; 
        }
        
        h2 { color: #333; margin-top: 0; font-size: 24px; margin-bottom: 10px; }
        p { color: #666; font-size: 14px; margin-bottom: 30px; }
        
        label { display: block; text-align: left; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 14px; }
        
        input, select { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 20px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        /* --- BOTÃO VERDE --- */
        button { 
            width: 100%; 
            padding: 14px; 
            background-color: #28a745; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 600; 
            transition: 0.3s; 
        }
        
        button:hover { background-color: #218838; }
        
        .btn-voltar { 
            display: block; 
            margin-top: 20px; 
            color: #666; 
            text-decoration: none; 
            font-size: 14px; 
        }
        .btn-voltar:hover { text-decoration: underline; color: #333; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Agendar Atendimento</h2>
        <p>Selecione o serviço desejado para iniciar seu tratamento.</p>
        
        <?php if(!empty($mensagem)) echo "<p style='color:red'>$mensagem</p>"; ?>

        <form method="POST">
            <label>Data Preferencial:</label>
            <input type="date" name="data" required>

            <label>Especialidade / Serviço:</label>
            
            <select name="especialidade" required>
                <option value="" disabled selected>Selecione o serviço...</option>
                
                <optgroup label="Entrada e Avaliação">
                    <option value="Triagem (CAD)">Triagem (Primeiro Acesso)</option>
                    <option value="Diagnóstico (CORDI)">Diagnóstico Multiprofissional</option>
                    <option value="Avaliação Passe Livre">Avaliação para Passe Livre</option>
                </optgroup>

                <optgroup label="Especialidades Médicas">
                    <option value="Neurologia">Neurologia</option>
                    <option value="Psiquiatria">Psiquiatria</option>
                    <option value="Clínica Médica">Clínica Médica</option>
                    <option value="Oftalmologia">Oftalmologia</option>
                    <option value="Otorrinolaringologia">Otorrinolaringologia</option>
                </optgroup>

                <optgroup label="Reabilitação e Terapias">
                    <option value="Fisioterapia">Fisioterapia</option>
                    <option value="Fisioterapia Aquática">Hidroterapia</option>
                    <option value="Fonoaudiologia">Fonoaudiologia</option>
                    <option value="Terapia Ocupacional">Terapia Ocupacional</option>
                    <option value="Psicologia">Psicologia</option>
                    <option value="Serviço Social">Serviço Social</option>
                </optgroup>
                
                <optgroup label="Programas Especiais">
                    <option value="Estimulação Precoce">Estimulação Precoce (Bebês)</option>
                    <option value="TEA">Atendimento TEA (Autismo)</option>
                </optgroup>
            </select>

            <button type="submit">Confirmar Agendamento</button>
        </form>

        <a href="painel.php" class="btn-voltar">Cancelar e Voltar</a>
    </div>

</body>
</html>