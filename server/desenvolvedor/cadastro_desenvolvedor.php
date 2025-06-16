<?php
include_once("../conexao.php");

// Ativar relatório detalhado de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Coleta dos dados do formulário
        $nome = filter_input(INPUT_POST, 'nome_desenvolvedor', FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, 'telefone_desenvolvedor', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email_desenvolvedor', FILTER_SANITIZE_EMAIL);
        $endereco = filter_input(INPUT_POST, 'endereco_desenvolvedor', FILTER_SANITIZE_STRING);
        $cpf = filter_input(INPUT_POST, 'cpf_desenvolvedor', FILTER_SANITIZE_STRING);
        $skills = filter_input(INPUT_POST, 'skills_desenvolvedor', FILTER_SANITIZE_STRING);
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
        $stmt = $conn->prepare("SELECT id_desenvolvedor FROM desenvolvedor WHERE email_desenvolvedor = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception('Este email já foi cadastrado!');
        }
        $stmt->close();

        // 2. Verifica na tabela Empresa
        $stmt = $conn->prepare("SELECT id_empresa FROM empresa WHERE email_empresa = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception('Este email já foi cadastrado!');
        }
        $stmt->close();

        // Verifica se CPF já existe (apenas na tabela Desenvolvedor)
        $stmt = $conn->prepare("SELECT id_desenvolvedor FROM desenvolvedor WHERE cpf_desenvolvedor = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception('Este CPF já foi cadastrado!');
        }
        $stmt->close();

        // CADASTRO DO DESENVOLVEDOR
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO desenvolvedor 
            (nome_desenvolvedor, telefone_desenvolvedor, email_desenvolvedor, endereco_desenvolvedor, cpf_desenvolvedor, skills_desenvolvedor, senha_desenvolvedor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss", $nome, $telefone, $email, $endereco, $cpf, $skills, $senha_hash);
        if ($stmt->execute()) {
            $sucesso = urlencode("Desenvolvedor cadastrado com êxito!");
            header("Location: ../../templates/pglogin.html?sucesso=$sucesso");
            exit();
        } else {
            throw new Exception('Erro ao cadastrar desenvolvedor!');
        }

    } catch (Exception $e) {
        $mensagem = urlencode($e->getMessage());
        header("Location: ../../templates/cadastro.html?erro=$mensagem");
        exit();

    } finally {
        if (isset($stmt))
            $stmt->close();
        $conn->close();
    }
} else {
    echo "<script>window.location.href = '../../templates/cadastro.html';</script>";
}
?>