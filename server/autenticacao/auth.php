<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Se já estiver logado pela sessão, tá tudo certo
if (isset($_SESSION['id']) && isset($_SESSION['tipo'])) {
    return; // continua o carregamento da página
}

// Caso contrário, tenta logar pelo cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['tipo'])) {
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['tipo'] = $_COOKIE['tipo'];
    return;
}

// Se não tiver sessão nem cookie, manda pro login
header("Location: ../templates/pglogin.html");
exit();
