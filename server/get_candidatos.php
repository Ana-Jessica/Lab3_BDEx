<?php
include_once("../server/conexao.php");

if (isset($_GET['id_solicitacao'])) {
    $id = intval($_GET['id_solicitacao']);
    
    $sql = "
        SELECT d.nome_desenvolvedor, d.email_desenvolvedor, d.skills_desenvolvedor
        FROM solicitacao s
        INNER JOIN desenvolvedor d ON s.id_desenvolvedor = d.id_desenvolvedor
        WHERE s.id_solicitacao = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $candidatos = [];
    while ($row = $result->fetch_assoc()) {
        $candidatos[] = $row;
    }

    echo json_encode($candidatos);
}
?>
