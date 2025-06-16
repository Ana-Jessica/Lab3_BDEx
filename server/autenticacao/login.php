<?php
session_start();
include_once("../conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email inválido.'); window.location.href = '../../templates/pglogin.html';</script>";
        exit();
    }

    if (empty($senha)) {
        echo "<script>alert('Senha obrigatória.'); window.location.href = '../../templates/pglogin.html';</script>";
        exit();
    }

    // Primeiro tenta encontrar na tabela empresa
    $stmt = mysqli_prepare($conn, "SELECT id_empresa, senha_empresa, ativo FROM empresa WHERE email_empresa = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id_empresa, $senha_hash, $ativo);
        mysqli_stmt_fetch($stmt);

        if (password_verify($senha, $senha_hash)) {
            if ($ativo == 0) {
                // Conta desativada, gera token para reativação
                $token = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Atualiza token no banco de dados
                $update = mysqli_prepare($conn, "UPDATE empresa SET token_reativacao = ?, reativacao_expira = ? WHERE email_empresa = ?");
                $token_hash = password_hash($token, PASSWORD_BCRYPT);
                mysqli_stmt_bind_param($update, "sss", $token_hash, $expira, $email);
                mysqli_stmt_execute($update);
                mysqli_stmt_close($update);

                // Modal de reativação
                $modal_reativacao = '
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="background: white; padding: 20px; border-radius: 2vh; max-width: 400px; margin: 100px auto; text-align: center;">
                        <h3>Conta Desativada</h3>
                        <p>Sua conta está desativada. Deseja reativá-la agora?</p>
                        <form action="../usuarios/reativarConta.php" method="POST" id="formReativar">
                            <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
                            <input type="hidden" name="tipo" value="empresa">
                            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                            <button type="submit" style="background: green; color: white; padding: 10px; border: none; cursor: pointer;">Reativar</button>
                            <button type="button" onclick="window.location.href=\'../../templates/pglogin.html\'" style="background: red; color: white; padding: 10px; border: none; cursor: pointer;">Cancelar</button>
                        </form>
                    </div>
                </div>
                ';
                echo $modal_reativacao;
                mysqli_stmt_close($stmt);
                exit();
            }

            // Conta ativa, prossegue com o login
            $_SESSION['id'] = $id_empresa;
            $_SESSION['tipo'] = 'empresa';
            $_SESSION['email'] = $email;

            if (!empty($_POST['manter_logado'])) {
                setcookie('id', $id_empresa, time() + (30 * 24 * 60 * 60), "/");
                setcookie('tipo', 'empresa', time() + (30 * 24 * 60 * 60), "/");
            }
            $_SESSION['bem_vindoo'] = true;
            header("Location: ../../templates/dashboard_empresa.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = '../../templates/pglogin.html';</script>";
            exit();
        }
    } else {
        mysqli_stmt_close($stmt);

        // Agora tenta encontrar na tabela desenvolvedor
        $stmt = mysqli_prepare($conn, "SELECT id_desenvolvedor, senha_desenvolvedor, ativo FROM desenvolvedor WHERE email_desenvolvedor = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $id_desenvolvedor, $senha_hash, $ativo);
            mysqli_stmt_fetch($stmt);

            if (password_verify($senha, $senha_hash)) {
                if ($ativo == 0) {
                    // Conta desativada, gera token para reativação
                    $token = bin2hex(random_bytes(32));
                    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Atualiza token no banco de dados
                    $update = mysqli_prepare($conn, "UPDATE desenvolvedor SET token_reativacao = ?, reativacao_expira = ? WHERE email_desenvolvedor = ?");
                    $token_hash = password_hash($token, PASSWORD_BCRYPT);
                    mysqli_stmt_bind_param($update, "sss", $token_hash, $expira, $email);
                    mysqli_stmt_execute($update);
                    mysqli_stmt_close($update);

                    // Modal de reativação
                    $modal_reativacao = '
                    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                        <div style="background: white; padding: 20px; border-radius: 2vh; max-width: 400px; margin: 100px auto; text-align: center;">
                            <h3>Conta Desativada</h3>
                            <p>Sua conta está desativada. Deseja reativá-la agora?</p>
                            <form action="../usuarios/reativarConta.php" method="POST" id="formReativar">
                                <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
                                <input type="hidden" name="tipo" value="desenvolvedor">
                                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                                <button type="submit" style="background: green; color: white; padding: 10px; border: none; cursor: pointer;">Reativar</button>
                                <button type="button" onclick="window.location.href=\'../../templates/pglogin.html\'" style="background: red; color: white; padding: 10px; border: none; cursor: pointer;">Cancelar</button>
                            </form>
                        </div>
                    </div>
                    ';
                    echo $modal_reativacao;
                    mysqli_stmt_close($stmt);
                    exit();
                }

                // Conta ativa, prossegue com o login
                $_SESSION['id'] = $id_desenvolvedor;
                $_SESSION['tipo'] = 'desenvolvedor';
                $_SESSION['email'] = $email;

                if (!empty($_POST['manter_logado'])) {
                    setcookie('id', $id_desenvolvedor, time() + (30 * 24 * 60 * 60), "/");
                    setcookie('tipo', 'desenvolvedor', time() + (30 * 24 * 60 * 60), "/");
                }
                $_SESSION['bem_vindo'] = true;
                header("Location: ../../templates/dashboard_desenvolvedor.php");
                exit();
            } else {
                echo "<script>alert('Senha incorreta.'); window.location.href = '../../templates/pglogin.html';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Usuário não encontrado.'); window.location.href = '../../templates/pglogin.html';</script>";
            exit();
        }
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../../templates/pglogin.html");
    exit();
}
?>