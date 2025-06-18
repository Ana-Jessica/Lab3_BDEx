<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($usuario === 'admin' && $senha === 'admin') {
        $_SESSION['acesso_historico'] = true;
        header("Location: historico.php");
        exit();
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo - Histórico</title>
    <style>

        body {
    background-image: url('../../static/imgs/fundo_completo.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

        {
            background-color: #f0faff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-box h2 {
            color: #0e5db3;
            margin-bottom: 20px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-box input[type="submit"] {
            background-color: #0e5db3;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .login-box input[type="submit"]:hover {
            background-color: #084d99;
        }

        .erro {
            color: #d93025;
            margin-bottom: 10px;
        }

        .logo-bdex {
            width: 150px;
            margin-bottom: 15px;
        }



    </style>
</head>
<body>
    <div class="login-box">
        <img src="../../static/imgs/LOGO_BDEx.svg" alt="BDEx logo featuring abstract blue and green shapes with the text BDEx Banco de Dados Experimental in a modern font, conveying a professional and innovative atmosphere" class="logo-bdex">

        <h2>Login Administrativo</h2>

        <?php if (isset($erro)): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="submit" value="Entrar">
        </form>
    </div>
</body>
</html>
