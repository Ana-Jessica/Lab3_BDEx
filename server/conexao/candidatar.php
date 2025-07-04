<?php
session_start();
include_once("../conexao.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'desenvolvedor') {
    die("Você precisa estar logado como desenvolvedor.");
}

$id_vaga = intval($_GET['id']);
$id_desenvolvedor = $_SESSION['id'];

// Verificar se já existe solicitação
$stmt = $conn->prepare("SELECT * FROM solicitacao WHERE id_desenvolvedor = ? AND id_vaga = ?");
$stmt->bind_param("ii", $id_desenvolvedor, $id_vaga);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['solicitado_aviso'] = true;
    header("Location: ../../templates/dashboard_desenvolvedor.php");
    exit();
}

// Inserir nova solicitação
$stmt = $conn->prepare("INSERT INTO solicitacao (id_desenvolvedor, id_vaga) VALUES (?, ?)");
$stmt->bind_param("ii", $id_desenvolvedor, $id_vaga);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Solicitação enviada com sucesso!";
    $_SESSION['solicitado_sucesso'] = true;
} else {
    $_SESSION['erro'] = "Erro ao enviar solicitação.";
    $_SESSION['solicitado_sucesso'] = false;
}

header("Location: ../../templates/dashboard_desenvolvedor.php");
exit();
