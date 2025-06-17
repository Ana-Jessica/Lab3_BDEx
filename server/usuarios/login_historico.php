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
    <title>Login Histórico</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; padding-top: 100px; }
        form { background-color: #fff; padding: 20px; border-radius: 8px; display: inline-block; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { margin: 5px; padding: 10px; width: 200px; }
        button { padding: 10px 20px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h2>Login de Acesso ao Histórico</h2>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuário"><br>
        <input type="password" name="senha" placeholder="Senha"><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
