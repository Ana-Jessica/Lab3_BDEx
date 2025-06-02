<?php
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone_empresa', FILTER_SANITIZE_STRING);
    $senha = $_POST['senha'];

    // Verificações básicas
    if (!$nome_empresa || !$cnpj || !$endereco || !$email || !$telefone || !$senha) {
        echo "<script>alert('Preencha todos os campos obrigatórios!'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('E-mail inválido!'); window.history.back();</script>";
        exit();
    }

    if (strlen($senha) < 6) {
        echo "<script>alert('A senha deve ter pelo menos 6 caracteres!'); window.history.back();</script>";
        exit();
    }

    // Verifica se o email já existe
    $stmt = mysqli_prepare($conn, "SELECT id_empresa FROM empresa WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Este email já foi cadastrado!'); window.history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Verifica se o CNPJ já existe
    $stmt = mysqli_prepare($conn, "SELECT id_empresa FROM empresa WHERE cnpj = ?");
    mysqli_stmt_bind_param($stmt, "s", $cnpj);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Este CNPJ já foi cadastrado!'); window.history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Cadastro
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO empresa (nome_empresa, cnpj, endereco, email, telefone_empresa, senha_empresa) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $nome_empresa, $cnpj, $endereco, $email, $telefone, $senha_hash);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Empresa cadastrada com sucesso!'); window.location.href = '../templates/pglogin.html';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao cadastrar a empresa!'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>window.location.href = '../templates/cadastro_empresa.html';</script>";
    exit();
}
?>
