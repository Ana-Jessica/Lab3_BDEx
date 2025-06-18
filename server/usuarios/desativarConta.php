<?php
session_start();

require_once __DIR__ . '/../../server/conexao.php';
require_once 'registrar_historico.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    header("Location: ../../templates/pglogin.html?erro=sessao_expirada");
    exit();
}

$email = $_SESSION['email'];
$tipo = $_SESSION['tipo'];

try {
    // Determina a tabela e os campos com base no tipo de usuário
    $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
    $campo_email = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
    $campo_id = ($tipo === 'empresa') ? 'id_empresa' : 'id_desenvolvedor';

    // Verifica se a conta existe e está ativa
    $stmt = $conn->prepare("SELECT $campo_id FROM $tabela WHERE $campo_email = ? AND ativo = 1");
    if (!$stmt) {
        throw new Exception("Erro na preparação da query de verificação: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Conta não encontrada ou já desativada.");
    }
    $row = $result->fetch_assoc();
    $id_usuario = $row[$campo_id];
    $stmt->close();

    // Desativa a conta
    $stmt = $conn->prepare("UPDATE $tabela SET ativo = 0 WHERE $campo_email = ?");
    if (!$stmt) {
        throw new Exception("Erro na preparação da query de desativação: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Erro ao executar a query de desativação: " . $stmt->error);
    }
    $stmt->close();

    // Registro no histórico (RF06.4)
    $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : null;
    registrarHistorico($conn, $tipo, $id_usuario, 'desativacao', $motivo);

    // Limpa cookies, se existirem
    if (isset($_COOKIE['id']) || isset($_COOKIE['tipo'])) {
        setcookie('id', '', time() - 3600, "/");
        setcookie('tipo', '', time() - 3600, "/");
    }

    // Limpa a sessão
    session_unset();
    session_destroy();

    // Redireciona com mensagem de sucesso
    header("Location: ../../templates/pglogin.html?conta_desativada=1");
    exit();
} catch (Exception $e) {
    error_log("Erro ao desativar conta: " . $e->getMessage());
    header("Location: ../../templates/dashboard_$tipo.php?erro=desativacao_falhou");
    exit();
}
?>
