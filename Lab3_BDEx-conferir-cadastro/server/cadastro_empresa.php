<?php
// server/cadastro_empresa.php

include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone_empresa', FILTER_SANITIZE_STRING);
    $senha = $_POST['senha'];

    $errors = [];

    if (empty($nome_empresa)) $errors[] = "Nome da empresa é obrigatório.";
    if (empty($cnpj)) $errors[] = "CNPJ é obrigatório.";
    if (empty($endereco)) $errors[] = "Endereço é obrigatório.";
    if (empty($email)) $errors[] = "E-mail é obrigatório.";
    if (empty($telefone)) $errors[] = "Telefone é obrigatório.";
    if (empty($senha)) $errors[] = "Senha é obrigatória.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }

    if (strlen($senha) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Verifica se email já existe
    $stmt = mysqli_prepare($conn, "SELECT id_empresa FROM empresa WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Este email já está cadastrado.";
    }
    mysqli_stmt_close($stmt);

    // Verifica se CNPJ já existe
    $stmt = mysqli_prepare($conn, "SELECT id_empresa FROM empresa WHERE cnpj = ?");
    mysqli_stmt_bind_param($stmt, "s", $cnpj);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Este CNPJ já está cadastrado.";
    }
    mysqli_stmt_close($stmt);

    if (empty($errors)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO empresa 
            (nome_empresa, cnpj, endereco, email, telefone_empresa, senha_empresa) 
            VALUES (?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($stmt, "ssssss", $nome_empresa, $cnpj, $endereco, $email, $telefone, $senha_hash);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../templates/pglogin.html");

            exit();
        } else {
            $errors[] = "Erro ao cadastrar empresa: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }

    if (!empty($errors)) {
        $query = http_build_query([
            'error' => implode(" ", $errors),
            'nome_empresa' => $nome_empresa,
            'cnpj' => $cnpj,
            'endereco' => $endereco,
            'email' => $email,
            'telefone_empresa' => $telefone
        ]);

        header("Location: ../public/cadastro_empresa.html?" . $query);
        exit();
    }
} else {
    header("Location: ../public/cadastro_empresa.html");
    exit();
}
