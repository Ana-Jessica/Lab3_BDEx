<?php
session_start();
include_once("../conexao.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../../templates/pglogin.html");
    exit();
}

$acao = $_POST['acao'] ?? '';
$id_conexao = $_POST['id_conexao'] ?? 0;

if (!in_array($acao, ['encerrar', 'concluir'])) {
    $_SESSION['erro'] = "Ação inválida";
    header("Location: ../../templates/dashboard_empresa.php#conexoes");
    exit();
}

// Verifica se a conexão pertence à empresa
$stmt = $conn->prepare("SELECT id_conexao FROM conexao WHERE id_conexao = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_conexao, $_SESSION['id']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['erro'] = "Conexão não encontrada ou não pertence à sua empresa";
    header("Location: ../../templates/dashboard_empresa.php#conexoes");
    exit();
}
$stmt->close();

if ($acao === 'encerrar') {
    $justificativa = trim($_POST['justificativa'] ?? '');
    
    if (empty($justificativa)) {
        $_SESSION['erro'] = "Justificativa é obrigatória";
        header("Location: ../../templates/dashboard_empresa.php#conexoes");
        exit();
    }

    $stmt = $conn->prepare("UPDATE conexao SET status_conexao = 'encerrada', justificativa = ? WHERE id_conexao = ?");
    $stmt->bind_param("si", $justificativa, $id_conexao);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Conexão encerrada com sucesso";
    } else {
        $_SESSION['erro'] = "Erro ao encerrar conexão";
    }
    $stmt->close();
}

if ($acao === 'concluir') {
    $stmt = $conn->prepare("UPDATE conexao SET status_conexao = 'concluida' WHERE id_conexao = ?");
    $stmt->bind_param("i", $id_conexao);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Conexão concluída com sucesso";
    } else {
        $_SESSION['erro'] = "Erro ao concluir conexão";
    }
    $stmt->close();
}

header("Location: ../../templates/dashboard_empresa.php#conexoes");
exit();
?>