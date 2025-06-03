<?php
// server/cadastro_desenvolvedor.php

// Inclui o arquivo de conexão
include_once("conexao.php");

// Processar o formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar e sanitizar dados
    $nome = filter_input(INPUT_POST, 'nome_desenvolvedor', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone_desenvolvedor', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email_desenvolvedor', FILTER_SANITIZE_EMAIL);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $linguagens = filter_input(INPUT_POST, 'linguagens_de_programacao', FILTER_SANITIZE_STRING);
    $tecnologias = filter_input(INPUT_POST, 'tecnologias', FILTER_SANITIZE_STRING);
    $senha = $_POST['senha']; // Não sanitizamos a senha pois será hasheada
    
    // Validações
    $errors = [];
    
    // Validar campos obrigatórios
    if (empty($nome)) $errors[] = "Nome é obrigatório.";
    if (empty($telefone)) $errors[] = "Telefone é obrigatório.";
    if (empty($email)) $errors[] = "E-mail é obrigatório.";
    if (empty($cpf)) $errors[] = "CPF é obrigatório.";
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
        $stmt = $pdo->prepare("SELECT id_desenvolvedor FROM Desenvolvedor WHERE email_desenvolvedor = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Este email já está cadastrado.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erro ao verificar email: " . $e->getMessage();
    }
    
    // Verificar se CPF já está cadastrado
    try {
        $stmt = $pdo->prepare("SELECT id_desenvolvedor FROM Desenvolvedor WHERE cpf = ?");
        $stmt->execute([$cpf]);
        if ($stmt->fetch()) {
            $errors[] = "Este CPF já está cadastrado.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erro ao verificar CPF: " . $e->getMessage();
    }
    
    // Se não houver erros, prosseguir com o cadastro
    if (empty($errors)) {
        try {
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Inserir no banco de dados
            $stmt = $pdo->prepare("INSERT INTO Desenvolvedor 
                                  (nome_desenvolvedor, telefone_desenvolvedor, email_desenvolvedor, cpf, linguagens_de_programacao, tecnologias, senha_desenvolvedor) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $telefone, $email, $cpf, $linguagens, $tecnologias, $senha_hash]);
            
            // Redirecionar para página de sucesso
            header("Location: ../public/cadastro_desenvolvedor.html?success=Desenvolvedor cadastrado com sucesso!");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erro ao cadastrar desenvolvedor: " . $e->getMessage();
        }
    }
    
    // Se houver erros, redirecionar com os dados e mensagens
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
    // Se alguém tentar acessar diretamente sem enviar o formulário
    header("Location: ../public/cadastro_desenvolvedor.html");
    exit();
}
?>