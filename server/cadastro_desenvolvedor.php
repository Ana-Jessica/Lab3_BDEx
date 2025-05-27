<?php
// server/cadastro_desenvolvedor.php

include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome_desenvolvedor', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone_desenvolvedor', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email_desenvolvedor', FILTER_SANITIZE_EMAIL);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $linguagens = filter_input(INPUT_POST, 'linguagens_de_programacao', FILTER_SANITIZE_STRING);
    $tecnologias = filter_input(INPUT_POST, 'tecnologias', FILTER_SANITIZE_STRING);
    $senha = $_POST['senha'];

    // Validacoes
    if (!$nome || !$telefone || !$email || !$cpf || !$senha) {
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

    // Verifica se email já existe
    $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor FROM Desenvolvedor WHERE email_desenvolvedor = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Este email já foi cadastrado!'); window.history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Verifica se CPF já existe
    $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor FROM Desenvolvedor WHERE cpf = ?");
    mysqli_stmt_bind_param($stmt, "s", $cpf);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Este CPF já foi cadastrado!'); window.history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Cadastro
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO Desenvolvedor 
        (nome_desenvolvedor, telefone_desenvolvedor, email_desenvolvedor, cpf, linguagens_de_programacao, tecnologias, senha_desenvolvedor) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssss", $nome, $telefone, $email, $cpf, $linguagens, $tecnologias, $senha_hash);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('DESENVOLVEDOR CADASTRADO COM ÊXITO!'); window.location.href = '../templates/pglogin.html';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao cadastrar desenvolvedor!'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>window.location.href = '../public/cadastro_desenvolvedor.html';</script>";
    exit();
}
?>
