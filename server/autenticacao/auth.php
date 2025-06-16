<?php
require_once __DIR__ . '/../../server/conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado pela sessão, verifica o status ativo
if (isset($_SESSION['id']) && isset($_SESSION['tipo']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $tipo = $_SESSION['tipo'];
    $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
    $campo_email = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';

    // Verifica se a conta está ativa
    $stmt = $conn->prepare("SELECT ativo FROM $tabela WHERE $campo_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($ativo);
    if ($stmt->fetch() && $ativo == 1) {
        $stmt->close();
        return; // Conta ativa, prossegue
    }
    $stmt->close();
}

// Caso contrário, tenta logar pelo cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['tipo'])) {
    $id = $_COOKIE['id'];
    $tipo = $_COOKIE['tipo'];
    $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
    $campo_email = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
    $campo_id = ($tipo === 'empresa') ? 'id_empresa' : 'id_desenvolvedor';

    $stmt = $conn->prepare("SELECT email_$tabela, ativo FROM $tabela WHERE $campo_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($email, $ativo);
    if ($stmt->fetch() && $ativo == 1) {
        $_SESSION['id'] = $id;
        $_SESSION['tipo'] = $tipo;
        $_SESSION['email'] = $email;
        $stmt->close();
        return;
    }
    $stmt->close();

    // Limpa cookies inválidos
    setcookie('id', '', time() - 3600, "/");
    setcookie('tipo', '', time() - 3600, "/");
}

// Se não tiver sessão nem cookie válido, ou a conta estiver desativada, redireciona para login
session_unset();
session_destroy();
header("Location: ../../templates/pglogin.html?erro=conta_desativada_ou_sessao_expirada");
exit();
?>