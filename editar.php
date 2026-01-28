<?php
session_start();
require 'conexao.php';

// Verifica login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

// Verifica se tem ID na URL
if (!isset($_GET['id'])) {
    header("Location: painel.php");
    exit;
}

$id_agendamento = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];

// Busca os dados do agendamento (Só se pertencer a esse usuário)
$sql = "SELECT * FROM agendamentos WHERE id = :id AND usuario_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id_agendamento);
$stmt->bindValue(':uid', $id_usuario);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $agendamento = $stmt->fetch();
} else {
    // Se não achar ou não for dono
    header("Location: painel.php");
    exit;
}

// Se o usuário clicou em "Salvar Alterações"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_data = $_POST['data'];
    $nova_especialidade = $_POST['especialidade'];

    $sql_update = "UPDATE agendamentos SET data_consulta = :data, especialidade = :esp WHERE id = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindValue(':data', $nova_data);
    $stmt_update->bindValue(':esp', $nova_especialidade);
    $stmt_update->bindValue(':id', $id_agendamento);

    if ($stmt_update->execute()) {
        header("Location: painel.php");
        exit;
    } else {
        echo "Erro ao atualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Agendamento</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        /* CSS IGUAL AO DO AGENDAR.PHP (CENTRALIZADO) */
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f3f4f6; 
            display: flex; /* Centraliza Verticalmente e Horizontalmente */
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
            width: 400px; 
            text-align: center; 
        }
        
        h2 { color: #333; margin-top: 0; font-size: 24px; margin-bottom: 10px; }
        
        /* Título com ícone */
        .titulo-icon { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px; }

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

        /* BOTÃO AMARELO (Para indicar Edição) */
        button { 
            width: 100%; 
            padding: 14px; 
            background-color: #f59e0b; /* Amarelo/Laranja */
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: 600; 
            transition: 0.3s; 
        }
        
        button:hover { background-color: #d97706; }
        
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
        <div class="titulo-icon">
            <span style="font-size: 24px;">✏️</span>
            <h2>Remarcar/Editar</h2>
        </div>
        
        <form method="POST">
            <label>Data:</label>
            <input type="date" name="data" value="<?php echo $agendamento['data_consulta']; ?>" required>

            <label>Especialidade:</label>
            
            <select name="especialidade" id="select-especialidade" required>
                <option value="" disabled>Selecione...</option>
                
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

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="painel.php" class="btn-voltar">Cancelar</a>
    </div>

    <script>
        // Pega o valor que veio do banco de dados (PHP)
        var valorAtual = "<?php echo $agendamento['especialidade']; ?>";
        
        // Seleciona automaticamente na lista
        var select = document.getElementById('select-especialidade');
        select.value = valorAtual;
    </script>

</body>
</html>