<?php
session_start();
include_once("conexao.php");

if ($_SESSION['tipo'] !== 'empresa') {
    header("Location: ../templates/pglogin.html");
    exit();
}

$acao = $_GET['acao'] ?? '';
$id_solicitacao = intval($_GET['id']);

if (!in_array($acao, ['aceitar', 'recusar'])) {
    die("Ação inválida.");
}

$id_empresa = $_SESSION['id'];

// Se for aceitar, primeiro cria a conexão
if ($acao === 'aceitar') {
    // 1. Recuperar os dados da solicitação
    $stmt = $conn->prepare("
        SELECT s.id_desenvolvedor, s.id_vaga
        FROM Solicitacao s
        INNER JOIN Vaga v ON s.id_vaga = v.id_vaga
        WHERE s.id_solicitacao = ? AND v.id_empresa = ?
    ");
    $stmt->bind_param("ii", $id_solicitacao, $id_empresa);
    $stmt->execute();
    $stmt->bind_result($id_desenvolvedor, $id_vaga);

    if ($stmt->fetch()) {
        $stmt->close();

        // 2. Inserir a conexão
        $stmt2 = $conn->prepare("
            INSERT INTO Conexao (id_empresa, id_desenvolvedor, id_vaga, data_conexao)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt2->bind_param("iii", $id_empresa, $id_desenvolvedor, $id_vaga);
        $stmt2->execute();
        $stmt2->close();
    } else {
        $stmt->close();
        $_SESSION['erro'] = "Solicitação inválida ou não pertence à sua empresa.";
        header("Location: ../templates/dashboard_empresa.php#solicitacoes");
        exit();
    }
}

// Agora, deletamos a solicitação (tanto ao aceitar quanto recusar)
$stmt = $conn->prepare("DELETE FROM Solicitacao WHERE id_solicitacao = ?");
$stmt->bind_param("i", $id_solicitacao);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Solicitação " . ($acao === 'aceitar' ? "aceita" : "recusada") . " com sucesso.";
} else {
    $_SESSION['erro'] = "Erro ao processar a solicitação.";
}

header("Location: ../templates/dashboard_empresa.php#solicitacoes");
exit();
?>
