<?php
<<<<<<< HEAD
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
=======
// server/cadastro_empresa.php

// Inclui o arquivo de conexão
include_once("conexao.php");

// Processar o formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar e sanitizar dados
>>>>>>> origin/conferir-cadastro
    $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
    $cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone_empresa', FILTER_SANITIZE_STRING);
<<<<<<< HEAD
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
=======
    $senha = $_POST['senha']; // Não sanitizamos a senha pois será hasheada
    
    // Validações
    $errors = [];
    
    // Validar campos obrigatórios
    if (empty($nome_empresa)) $errors[] = "Nome da empresa é obrigatório.";
    if (empty($cnpj)) $errors[] = "CNPJ é obrigatório.";
    if (empty($endereco)) $errors[] = "Endereço é obrigatório.";
    if (empty($email)) $errors[] = "E-mail é obrigatório.";
    if (empty($telefone)) $errors[] = "Telefone é obrigatório.";
    if (empty($senha)) $errors[] = "Senha é obrigatória.";
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido.";
    }
    
    // Validar senha (mínimo 6 caracteres)
    if (strlen($senha) < 6) {
        $errors[] = "A senha deve ter pelo menos 6 caracteres.";
    }
    
    // Verificar se email já está cadastrado
    try {
        $stmt = $pdo->prepare("SELECT id_empresa FROM Empresa WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Este email já está cadastrado.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erro ao verificar email: " . $e->getMessage();
    }
    
    // Verificar se CNPJ já está cadastrado
    try {
        $stmt = $pdo->prepare("SELECT id_empresa FROM Empresa WHERE cnpj = ?");
        $stmt->execute([$cnpj]);
        if ($stmt->fetch()) {
            $errors[] = "Este CNPJ já está cadastrado.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erro ao verificar CNPJ: " . $e->getMessage();
    }
    
    // Se não houver erros, prosseguir com o cadastro
    if (empty($errors)) {
        try {
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Inserir no banco de dados
            $stmt = $pdo->prepare("INSERT INTO Empresa 
                                  (nome_empresa, cnpj, endereco, email, telefone_empresa, senha_empresa) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome_empresa, $cnpj, $endereco, $email, $telefone, $senha_hash]);
            
            // Redirecionar para página de sucesso
            header("Location: ../public/cadastro_empresa.html?success=Empresa cadastrada com sucesso!");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erro ao cadastrar empresa: " . $e->getMessage();
        }
    }
    
    // Se houver erros, redirecionar com os dados e mensagens
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
    // Se alguém tentar acessar diretamente sem enviar o formulário
    header("Location: ../public/cadastro_empresa.html");
    exit();
}
?>
>>>>>>> origin/conferir-cadastro
