<?php
session_start();
include_once("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['id']) && $_SESSION['tipo'] === 'empresa') {
    $id_vaga = intval($_POST['id_vaga']);
    $id_desenvolvedor = intval($_POST['id_desenvolvedor']);

    // Atualiza status para 'rejeitada' (ou pode deletar a solicitação)
    $stmt = $conn->prepare("UPDATE solicitacao SET status_solicitacao = 'rejeitada' WHERE id_vaga = ? AND id_desenvolvedor = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $id_vaga, $id_desenvolvedor);
        if ($stmt->execute()) {
            echo "Solicitação rejeitada com sucesso!";
        } else {
            http_response_code(500);
            echo "Erro ao rejeitar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo "Erro ao preparar: " . $conn->error;
    }
} else {
    http_response_code(403);
    echo "Acesso não autorizado.";
}
?>
