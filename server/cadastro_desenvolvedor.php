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

    $errors = [];

    if (empty($nome)) $errors[] = "Nome é obrigatório.";
    if (empty($telefone)) $errors[] = "Telefone é obrigatório.";
    if (empty($email)) $errors[] = "E-mail é obrigatório.";
    if (empty($cpf)) $errors[] = "CPF é obrigatório.";
    if (empty($senha)) $errors[] = "Senha é obrigatória.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }

    if (strlen($senha) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Verifica se email já existe
    $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor FROM Desenvolvedor WHERE email_desenvolvedor = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Este email já está cadastrado.";
    }
    mysqli_stmt_close($stmt);

    // Verifica se CPF já existe
    $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor FROM Desenvolvedor WHERE cpf = ?");
    mysqli_stmt_bind_param($stmt, "s", $cpf);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Este CPF já está cadastrado.";
    }
    mysqli_stmt_close($stmt);

    if (empty($errors)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO Desenvolvedor 
            (nome_desenvolvedor, telefone_desenvolvedor, email_desenvolvedor, cpf, linguagens_de_programacao, tecnologias, senha_desenvolvedor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssss", $nome, $telefone, $email, $cpf, $linguagens, $tecnologias, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../templates/pglogin.html");
            exit();
        } else {
            $errors[] = "Erro ao cadastrar desenvolvedor: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }

    if (!empty($errors)) {
        $query = http_build_query([
            'error' => implode(" ", $errors),
            'nome_desenvolvedor' => $nome,
            'telefone_desenvolvedor' => $telefone,
            'email_desenvolvedor' => $email,
            'cpf' => $cpf,
            'linguagens_de_programacao' => $linguagens,
            'tecnologias' => $tecnologias
        ]);

        header("Location: ../public/cadastro_desenvolvedor.html?" . $query);
        exit();
    }
} else {
    header("Location: ../public/cadastro_desenvolvedor.html");
    exit();
}
?>
