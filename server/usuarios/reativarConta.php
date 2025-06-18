<?php
session_start();
require_once __DIR__ . '/../../server/conexao.php';
require_once 'registrar_historico.php'; // Adicionado para o histórico

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../templates/pglogin.html");
    exit();
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$token = $_POST['token'] ?? '';
$tipo = $_POST['tipo'] ?? '';

if (empty($email) || empty($token) || empty($tipo) || !in_array($tipo, ['empresa', 'desenvolvedor'])) {
    header("Location: ../../templates/pglogin.html?erro=dados_ausentes");
    exit();
}

$tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
$campo_email = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
$campo_id = ($tipo === 'empresa') ? 'id_empresa' : 'id_desenvolvedor';

try {
    $stmt = $conn->prepare("SELECT $campo_id, token_reativacao, reativacao_expira FROM $tabela WHERE $campo_email = ?");
    if (!$stmt) {
        throw new Exception("Erro na preparação da query: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $id_usuario = $user[$campo_id];

        if (!empty($user['token_reativacao']) &&
            !empty($user['reativacao_expira']) &&
            strtotime($user['reativacao_expira']) > time() &&
            password_verify($token, $user['token_reativacao'])) {

            $update = $conn->prepare("UPDATE $tabela SET ativo = 1, token_reativacao = NULL, reativacao_expira = NULL WHERE $campo_email = ?");
            if (!$update) {
                throw new Exception("Erro na preparação do UPDATE: " . $conn->error);
            }
            $update->bind_param("s", $email);
            $update->execute();
            if ($update->affected_rows === 0) {
                throw new Exception("Nenhuma conta foi atualizada.");
            }
            $update->close();

            // Registro no histórico (RF06.4)
            registrarHistorico($conn, $tipo, $id_usuario, 'reativacao');

            header("Location: ../../templates/pglogin.html?conta_reativada=1");
            exit();
        }
    }
    throw new Exception("Token inválido ou expirado.");
} catch (Exception $e) {
    error_log("Erro ao reativar conta: " . $e->getMessage());
    header("Location: ../../templates/pglogin.html?erro=token_invalido");
    exit();
}

// Redirecionamento final por segurança (não deve chegar aqui)
header("Location: ../../templates/pglogin.html?reativada=1");
exit();
?>
