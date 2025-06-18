<?php
require_once '../conexao.php'; // Ajuste o caminho se necessário

function registrarHistorico($conn, $tipo_usuario, $id_usuario, $acao, $motivo = null) {
    $ip = $_SERVER['REMOTE_ADDR'];

    if ($tipo_usuario === 'empresa') {
        $sql = "INSERT INTO historico_usuarios (id_empresa, tipo_usuario, acao, motivo, ip) VALUES (?, 'empresa', ?, ?, ?)";
    } elseif ($tipo_usuario === 'desenvolvedor') {
        $sql = "INSERT INTO historico_usuarios (id_desenvolvedor, tipo_usuario, acao, motivo, ip) VALUES (?, 'desenvolvedor', ?, ?, ?)";
    } else {
        return false;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_usuario, $acao, $motivo, $ip);
    return $stmt->execute();
}
?>
