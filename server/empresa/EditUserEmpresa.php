<?php
session_start();
include_once("../conexao.php"); // Conexão com banco
include_once("../autenticacao/auth.php"); // Verifica autenticação

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../templates/pglogin.html?erro=nao_autenticado");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_empresa = $_SESSION['id'];

    $nome_empresa = $_POST['nome_empresa'] ?? '';
    $endereco_empresa = $_POST['endereco_empresa'] ?? '';
    $email_empresa = $_POST['email_empresa'] ?? '';
    $telefone_empresa = $_POST['telefone_empresa'] ?? '';
    // $senha_atual_informada = $_POST['senha_empresa_atual'] ?? '';

    // Validação básica
    if (empty($nome_empresa) || empty($endereco_empresa) || empty($email_empresa) || empty($telefone_empresa)) {
        header("Location: dashboard_empresa.php?erro=campos_vazios");
        exit();
    }


    // (Opcional) Verificar se o e-mail já está em uso — futuro
    /*
    $sql_verifica = "SELECT id_empresa FROM empresa WHERE email_empresa = ? AND id_empresa != ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("si", $email_empresa, $id_empresa);
    $stmt_verifica->execute();
    $stmt_verifica->store_result();
    if ($stmt_verifica->num_rows > 0) {
        $stmt_verifica->close();
        header("Location: dashboard_empresa.php?erro=email_em_uso");
        exit();
    }
    $stmt_verifica->close();
    */

    // Atualizar dados
    $sql = "UPDATE empresa SET 
                nome_empresa = ?, 
                endereco_empresa = ?, 
                email_empresa = ?, 
                telefone_empresa = ?
            WHERE id_empresa = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssi", 
            $nome_empresa, 
            $endereco_empresa, 
            $email_empresa, 
            $telefone_empresa, 
            $id_empresa
        );

        if ($stmt->execute()) {
            $_SESSION['editado_sucesso'] = true;
            header("Location: ../../templates/dashboard_empresa.php");
            exit();
        } else {
            error_log("Erro ao atualizar dados da empresa: " . $stmt->error);
            header("Location: dashboard_empresa.php?erro=atualizacao_falhou");
        }
        $stmt->close();
    } else {
        error_log("Erro na preparação da query de atualização: " . $conn->error);
        header("Location: dashboard_empresa.php?erro=query_falhou");
    }
} else {
    header("Location: dashboard_empresa.php?erro=metodo_invalido");
}

$conn->close();
?>
