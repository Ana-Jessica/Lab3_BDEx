<?php
include_once("conexao.php");

// Ativar relatório detalhado de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Coleta dos dados do formulário
        $nome = filter_input(INPUT_POST, 'nome_desenvolvedor', FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, 'telefone_desenvolvedor', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email_desenvolvedor', FILTER_SANITIZE_EMAIL);
        $endereco = filter_input(INPUT_POST, 'endereco_desenvolvedor', FILTER_SANITIZE_STRING);
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
        $skills = filter_input(INPUT_POST, 'skills', FILTER_SANITIZE_STRING);
        $senha = $_POST['senha_desenvolvedor'];

        // Validações básicas
        if (!$nome || !$telefone || !$email || !$endereco || !$cpf || !$senha) {
            throw new Exception('Preencha todos os campos obrigatórios!');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('E-mail inválido!');
        }

        if (strlen($senha) < 6) {
            throw new Exception('A senha deve ter pelo menos 6 caracteres!');
        }

        // VERIFICAÇÃO DO EMAIL EM AMBAS AS TABELAS
        // 1. Verifica na tabela Desenvolvedor
        $stmt = $conn->prepare("SELECT id_desenvolvedor FROM Desenvolvedor WHERE email_desenvolvedor = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            throw new Exception('Este email já foi cadastrado!');
        }
        $stmt->close();

        // 2. Verifica na tabela Empresa
        $stmt = $conn->prepare("SELECT id_empresa FROM Empresa WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            throw new Exception('Este email já foi cadastrado!');
        }
        $stmt->close();

        // Verifica se CPF já existe (apenas na tabela Desenvolvedor)
        $stmt = $conn->prepare("SELECT id_desenvolvedor FROM Desenvolvedor WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            throw new Exception('Este CPF já foi cadastrado!');
        }
        $stmt->close();

        // CADASTRO DO DESENVOLVEDOR
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Desenvolvedor 
            (nome_desenvolvedor, telefone_desenvolvedor, email_desenvolvedor, endereco_desenvolvedor, cpf, skills, senha_desenvolvedor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssss", $nome, $telefone, $email, $endereco, $cpf, $skills, $senha_hash);

        if ($stmt->execute()) {
            echo "<script>alert('DESENVOLVEDOR CADASTRADO COM ÊXITO!'); window.location.href = '../templates/pglogin.html';</script>";
        } else {
            throw new Exception('Erro ao cadastrar desenvolvedor!');
        }

    } catch (Exception $e) {
        echo "<script>alert('".addslashes($e->getMessage())."'); window.history.back();</script>";
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }
} else {
    echo "<script>window.location.href = '../public/cadastro_desenvolvedor.html';</script>";
}
?>