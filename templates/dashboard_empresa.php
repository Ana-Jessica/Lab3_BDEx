<?php
include_once("../server/auth.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina da empresa</title>
</head>
<body>
    <link rel="stylesheet" href="../static/styles/dashboard_empresa.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../static/imgs/logo/simbolo.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="../static/imgs/logo/simbolo.svg">
    <link rel="icon" type="image/png" sizes="16x16" href="../static/imgs/logo/simbolo.svg">
    <link rel="manifest" href="../ICONS/site.webmanifest">
    <link rel="mask-icon" href="../ICONS/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <div class="container">
        <header>
            <h1>Bem-vindo à sua página de empresa</h1>
        </header>
        <nav>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Configurações</a></li>
                <li><a href="../server/logout.php">Sair</a></li>
            </ul>
        </nav>
        <main>
            <!-- Conteúdo principal da página -->
            <h2>Conteúdo da Empresa</h2>
            <p>Aqui você pode gerenciar suas informações e configurações.</p>
        </main>
    </div>
</body>
</html>