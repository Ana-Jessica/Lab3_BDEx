<?php
session_start();
include_once ("conexao.php"); // Inclui o arquivo de conexão com o banco
include_once ("auth.php"); // Verifica autenticação

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php?erro=not_logged_in");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_desenvolvedor = $_SESSION['id'];
    $nome_desenvolvedor = $_POST['nome_desenvolvedor'] ?? '';
    $telefone_desenvolvedor = $_POST['telefone_desenvolvedor'] ?? '';
    $email_desenvolvedor = $_POST['email_desenvolvedor'] ?? '';
    $cpf_desenvolvedor = $_POST['cpf_desenvolvedor'] ?? '';
    $skills_desenvolvedor = $_POST['skills_desenvolvedor'] ?? ''; 
   


    // Validações básicas
    if (empty($nome_desenvolvedor) || empty($telefone_desenvolvedor) || empty($email_desenvolvedor) || empty($cpf_desenvolvedor) || empty($skills_desenvolvedor)) {
        header("Location: dash_desenvolvedor.php?erro=campos_vazios");
        exit();
    }

    // Verifica se a senha está correta antes de atualizar
    $stmt = $conn->prepare("SELECT senha_desenvolvedor FROM desenvolvedor WHERE id_desenvolvedor = ?");
    $stmt->bind_param("i", $id_desenvolvedor);
    $stmt->execute();
    $stmt->bind_result($senha_atual);
    $stmt->fetch();
    $stmt->close();


    // Prepara a query de atualização
    $sql = "UPDATE `banco_bdex`.`desenvolvedor` SET 
            `nome_desenvolvedor` = ?, 
            `telefone_desenvolvedor` = ?, 
            `email_desenvolvedor` = ?, 
            `cpf_desenvolvedor` = ?, 
            `skills_desenvolvedor` = ? 
            WHERE `id_desenvolvedor` = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssi", 
            $nome_desenvolvedor, 
            $telefone_desenvolvedor, 
            $email_desenvolvedor, 
            $cpf_desenvolvedor, 
            $skills_desenvolvedor, 
            $id_desenvolvedor
        );

        if ($stmt->execute()) {
            $_SESSION['editado_sucesso'] = true;
            header("Location: ../templates/dashboard_desenvolvedor.php");
            exit();


        } else {
            error_log("Erro ao atualizar dados: " . $stmt->error);
            header("Location: dash_desenvolvedor.php?erro=atualizacao_falhou");
        }
        $stmt->close();
    } else {
        error_log("Erro na preparação da query: " . $conn->error);
        header("Location: dashboard_desenvolvedor.php?erro=query_falhou");
    }
} else {
    header("Location: dashboard_desenvolvedor.php?erro=metodo_invalido");
}

$conn->close();
?>