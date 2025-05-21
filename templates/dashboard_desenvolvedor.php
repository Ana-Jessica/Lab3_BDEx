<?php
include_once("../server/auth.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do desenvolvedor</title>
</head>
<body>
    <div class="dashboard">
        <header>
            <h1>Bem-vindo ao Dashboard do Desenvolvedor</h1>
        </header>
        <section class="about-me">
            <h2>Sobre Mim</h2>
            <p>Olá! Meu nome é [Seu Nome], sou desenvolvedor com experiência em diversas tecnologias e apaixonado por criar soluções inovadoras. Adoro aprender coisas novas e compartilhar conhecimento com a comunidade.</p>
        </section>
        <section class="projects">
            <h2>Meus Projetos</h2>
            <ul>
                <li>Projeto 1 - Descrição breve do projeto.</li>
                <li>Projeto 2 - Descrição breve do projeto.</li>
                <li>Projeto 3 - Descrição breve do projeto.</li>
            </ul>
        </section>
        <a href="../server/logout.php">Sair</a>

        <footer>
            <p>&copy; 2023 Desenvolvedor. Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>