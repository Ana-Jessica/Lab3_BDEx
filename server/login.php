<?php
session_start();
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email inválido.'); window.location.href = '../templates/pglogin.html';</script>";
        exit();
    }

    if (empty($senha)) {
        echo "<script>alert('Senha obrigatória.'); window.location.href = '../templates/pglogin.html';</script>";
        exit();
    }

    // Primeiro tenta encontrar na tabela empresa
    $stmt = mysqli_prepare($conn, "SELECT id_empresa, senha_empresa FROM empresa WHERE email_empresa = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id_empresa, $senha_hash);
        mysqli_stmt_fetch($stmt);

        if (password_verify($senha, $senha_hash)) {
            $_SESSION['id'] = $id_empresa;
            $_SESSION['tipo'] = 'empresa';

            if (!empty($_POST['manter_logado'])) {
                setcookie('id', $id_empresa, time() + (30 * 24 * 60 * 60), "/");
                setcookie('tipo', 'empresa', time() + (30 * 24 * 60 * 60), "/");
            }

            header("Location: ../templates/dashboard_empresa.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = '../templates/pglogin.html';</script>";
            exit();
        }
    } else {
        mysqli_stmt_close($stmt);

        // Agora tenta na tabela desenvolvedor
        $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor, senha_desenvolvedor FROM desenvolvedor WHERE email_desenvolvedor = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $id_desenvolvedor, $senha_hash);
            mysqli_stmt_fetch($stmt);

            if (password_verify($senha, $senha_hash)) {
                $_SESSION['id'] = $id_desenvolvedor;
                $_SESSION['tipo'] = 'desenvolvedor';

                if (!empty($_POST['manter_logado'])) {
                    setcookie('id', $id_desenvolvedor, time() + (30 * 24 * 60 * 60), "/");
                    setcookie('tipo', 'desenvolvedor', time() + (30 * 24 * 60 * 60), "/");
                }

                header("Location: ../templates/dashboard_desenvolvedor.php");
                exit();
            } else {
                echo "<script>alert('Senha incorreta.'); window.location.href = '../templates/pglogin.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Usuário não encontrado.'); window.location.href = '../templates/pglogin.html';</script>";
            exit();
        }
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../templates/pglogin.html");
    exit();
}
?>
