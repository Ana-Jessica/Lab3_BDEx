<?php
include_once("../conexao.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitização
        $nome_empresa = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_STRING);
        $cnpj_empresa = filter_input(INPUT_POST, 'cnpj_empresa', FILTER_SANITIZE_STRING);
        $endereco_empresa = filter_input(INPUT_POST, 'endereco_empresa', FILTER_SANITIZE_STRING);
        $email_empresa = filter_input(INPUT_POST, 'email_empresa', FILTER_SANITIZE_EMAIL);
        $telefone_empresa = filter_input(INPUT_POST, 'telefone_empresa', FILTER_SANITIZE_STRING);
        $senha_empresa = $_POST['senha_empresa'];

        // Validação
        if (!$nome_empresa || !$cnpj_empresa || !$endereco_empresa || !$email_empresa || !$telefone_empresa || !$senha_empresa) {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("Preencha todos os campos obrigatórios!"));
            exit();
        }

        if (!filter_var($email_empresa, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("E-mail inválido!"));
            exit();
        }

        if (strlen($senha_empresa) < 6) {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("A senha deve ter pelo menos 6 caracteres!"));
            exit();
        }

        // Verificações de duplicidade
        $stmt = mysqli_prepare($conn, "
            SELECT email_empresa FROM empresa WHERE email_empresa = ?
            UNION
            SELECT email_desenvolvedor AS email_empresa FROM desenvolvedor WHERE email_desenvolvedor = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email_empresa, $email_empresa);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("Este email já foi cadastrado!"));
            exit();
        }
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, "SELECT id_empresa FROM empresa WHERE cnpj_empresa = ?");
        mysqli_stmt_bind_param($stmt, "s", $cnpj_empresa);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("Este CNPJ já foi cadastrado!"));
            exit();
        }
        mysqli_stmt_close($stmt);

        // Inserção
        $senha_hash = password_hash($senha_empresa, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO empresa (nome_empresa, cnpj_empresa, endereco_empresa, email_empresa, telefone_empresa, senha_empresa) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $nome_empresa, $cnpj_empresa, $endereco_empresa, $email_empresa, $telefone_empresa, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../../templates/pglogin.html?sucesso=" . urlencode("Empresa cadastrada com sucesso!"));
            exit();
        } else {
            header("Location: ../../templates/cadastro.html?erro=" . urlencode("Erro ao cadastrar a empresa!"));
            exit();
        }

    } catch (Exception $e) {
        header("Location: ../../templates/cadastro.html?erro=" . urlencode($e->getMessage()));
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }
} else {
    header("Location: ../../templates/cadastro.html");
    exit();
}
?>
