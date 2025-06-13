<?php
session_start();
include_once("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id']) && $_SESSION['tipo'] === 'empresa') {
    $id_empresa = $_SESSION['id'];
    $id_vaga = $_POST['id_vaga'];
    $id_desenvolvedor = $_POST['id_desenvolvedor'];

    $stmt = $conn->prepare("INSERT INTO conexao (id_empresa, id_desenvolvedor, id_vaga) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iii", $id_empresa, $id_desenvolvedor, $id_vaga);
        if ($stmt->execute()) {
            echo "Conexão criada com sucesso!";
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
