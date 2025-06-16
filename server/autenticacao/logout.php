<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Destroi a sessão
session_destroy();

// Remove os cookies, se existirem
if (isset($_COOKIE['id'])) {
    setcookie('id', '', time() - 3600, "/");
}
if (isset($_COOKIE['tipo'])) {
    setcookie('tipo', '', time() - 3600, "/");
}

// Redireciona pro login
header("Location: ../../templates/pglogin.html");
exit();

?>