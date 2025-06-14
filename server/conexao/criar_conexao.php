<?php
session_start();
include_once("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['id']) && $_SESSION['tipo'] === 'empresa') {
    $id_empresa = $_SESSION['id'];
    $id_vaga = intval($_POST['id_vaga']);
    $id_desenvolvedor = intval($_POST['id_desenvolvedor']);

    // Verifica se a conexão já existe
    $check = $conn->prepare("SELECT 1 FROM conexao WHERE id_vaga = ? AND id_desenvolvedor = ?");
    $check->bind_param("ii", $id_vaga, $id_desenvolvedor);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
         $_SESSION['conexao_jaexiste'] = true;
        exit();
    }

    // Cria a nova conexão
    $stmt = $conn->prepare("INSERT INTO conexao (id_empresa, id_desenvolvedor, id_vaga) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iii", $id_empresa, $id_desenvolvedor, $id_vaga);
        if ($stmt->execute()) {
            // Atualiza o status da solicitação para 'aceita'
            $update_solicitacao = $conn->prepare("
                UPDATE solicitacao 
                SET status_solicitacao = 'aceita' 
                WHERE id_vaga = ? AND id_desenvolvedor = ?
            ");
            if ($update_solicitacao) {
                $update_solicitacao->bind_param("ii", $id_vaga, $id_desenvolvedor);
                $update_solicitacao->execute();
                $update_solicitacao->close();
            }

            // (Opcional) Atualiza o status da vaga também
            $conn->query("UPDATE vaga SET status_vaga = 'conectada' WHERE id_vaga = $id_vaga");

            $_SESSION['conexao_criada'] = true;
        } else {
            http_response_code(500);
            echo "Erro ao inserir: " . $stmt->error;
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
