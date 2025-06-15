<?php
include_once("../conexao.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_conexao']);
    $acao = $_POST['acao'];

    if ($acao === 'encerrar') {
        $justificativa = trim($_POST['justificativa']);
        $stmt = $conn->prepare("UPDATE conexao SET status_conexao = 'encerrada', justificativa = ? WHERE id_conexao = ?");
        $stmt->bind_param("si", $justificativa, $id);
        $stmt->execute();
        $_SESSION['mensagem'] = "Conexão encerrada com sucesso!";
    }

    if ($acao === 'concluir') {
        $stmt = $conn->prepare("UPDATE conexao SET status_conexao = 'concluida' WHERE id_conexao = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['mensagem'] = "Conexão concluída com sucesso!";
    }
}

header("Location: ../../templates/dashboard_empresa.php#conexoes");
exit();
