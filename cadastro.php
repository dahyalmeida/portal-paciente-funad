<?php
require 'conexao.php';

$mensagem = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $tipo_deficiencia = $_POST['tipo_deficiencia']; // Novo Campo!

    // Verifica CPF
    $sql_verifica = "SELECT id FROM usuarios WHERE cpf = :cpf";
    $stmt_verifica = $pdo->prepare($sql_verifica);
    $stmt_verifica->bindValue(':cpf', $cpf);
    $stmt_verifica->execute();

    if ($stmt_verifica->rowCount() > 0) {
        $mensagem = "⚠️ Erro: Este CPF já está cadastrado!";
    } else {
        // Cadastra (Agora salvando a deficiência também)
        // ATENÇÃO: Verifique se no seu banco o nome da coluna é 'tipo_deficiencia' mesmo!
        $sql = "INSERT INTO usuarios (nome, cpf, senha, tipo_deficiencia) VALUES (:nome, :cpf, :senha, :deficiencia)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':deficiencia', $tipo_deficiencia);
        
        if ($stmt->execute()) {
            $sucesso = true;
        } else {
            $mensagem = "Erro ao cadastrar. Verifique os dados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Portal FUNAD</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #e9ecef; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; margin-top: 0; }
        
        input, select { width: 100%; padding: 12px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        
        /* BOTÃO VERDE */
        .btn-cadastrar { 
            width: 100%; padding: 12px; background-color: #28a745; color: white; 
            border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;
            text-align: center; display: block; box-sizing: border-box; text-decoration: none;
        }
        .btn-cadastrar:hover { background-color: #218838; }
        
        .link-login { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; font-size: 14px; }
        .link-login:hover { text-decoration: underline; }
        
        .msg-erro { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; border: 1px solid #f5c6cb; }
        .msg-sucesso { background: #d4edda; color: #155724; padding: 20px; border-radius: 4px; text-align: center; border: 1px solid #c3e6cb; }
        .msg-sucesso h3 { margin-top: 0; }
    </style>
    
    <script>
        function mascaraCPF(i){
            var v = i.value;
            if(isNaN(v[v.length-1])){ i.value = v.substring(0, v.length-1); return; }
            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        }
    </script>
</head>
<body>

    <div class="card">
        
        <?php if ($sucesso): ?>
            <div class="msg-sucesso">
                <h3>✅ Tudo Certo!</h3>
                <p>Cadastro realizado com sucesso.</p>
                <a href="index.php" class="btn-cadastrar" style="margin-top: 15px;">Ir para o Login</a>
            </div>
        <?php else: ?>
            <h2>Crie sua Conta</h2>
            
            <?php if (!empty($mensagem)): ?>
                <div class="msg-erro"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Nome Completo</label>
                <input type="text" name="nome" placeholder="Ex: Maria da Silva" required>

                <label>CPF</label>
                <input type="text" name="cpf" oninput="mascaraCPF(this)" placeholder="000.000.000-00" required>

                <label>Tipo de Deficiência</label>
                <select name="tipo_deficiencia" required>
                    <option value="" disabled selected>Selecione...</option>
                    <option value="Física">Física</option>
                    <option value="Auditiva">Auditiva</option>
                    <option value="Visual">Visual</option>
                    <option value="Intelectual">Intelectual</option>
                    <option value="Múltipla">Múltipla</option>
                    <option value="TEA">TEA (Autismo)</option>
                    <option value="Outra">Outra</option>
                    <option value="Nenhuma">Nenhuma (Responsável)</option>
                </select>

                <label>Senha</label>
                <input type="password" name="senha" placeholder="Crie uma senha" required>

                <button type="submit" class="btn-cadastrar">Cadastrar</button>
            </form>

            <a href="index.php" class="link-login">Já tenho conta? Fazer Login</a>
        <?php endif; ?>

    </div>

</body>
</html>