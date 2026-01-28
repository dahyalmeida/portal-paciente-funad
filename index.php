<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar - Portal FUNAD</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e9ecef; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 320px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; transition: 0.3s; }
        button:hover { background-color: #0056b3; }
        .logo { font-size: 24px; font-weight: bold; color: #333; margin-bottom: 20px; }
        
        /* Link novo de cadastro */
        .link-cadastro { margin-top: 20px; display: block; font-size: 14px; color: #666; text-decoration: none; }
        .link-cadastro b { color: #007bff; }
        .link-cadastro:hover b { text-decoration: underline; }
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
    <div class="login-card">
        <div class="logo">Portal do Paciente</div>
        
        <form action="auth.php" method="POST">
            <input type="text" name="cpf" oninput="mascaraCPF(this)" placeholder="Seu CPF" required>
            <input type="password" name="senha" placeholder="Sua Senha" required>
            <button type="submit">Acessar Sistema</button>
        </form>

        <a href="cadastro.php" class="link-cadastro">
            Não tem conta? <b>Cadastre-se aqui</b>
        </a>
        
    </div>
</body>
</html>