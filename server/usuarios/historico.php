<?php
session_start();
require_once '../conexao.php';

// Permite acesso se for admin ou usuário comum
$acesso_historico = isset($_SESSION['acesso_historico']) && $_SESSION['acesso_historico'] === true;

if (!$acesso_historico && (!isset($_SESSION['email']) || !isset($_SESSION['id']) || !isset($_SESSION['tipo']))) {
    header("Location: login_historico.php");
    exit();
}

try {
    if ($acesso_historico) {
        $sql = "SELECT * FROM historico_usuarios ORDER BY data_hora DESC";
        $stmt = $conn->prepare($sql);
    } else {
        $tipo_usuario = $_SESSION['tipo'];
        $id_usuario = $_SESSION['id'];

        if ($tipo_usuario === 'empresa') {
            $sql = "SELECT * FROM historico_usuarios WHERE id_empresa = ? ORDER BY data_hora DESC";
        } elseif ($tipo_usuario === 'desenvolvedor') {
            $sql = "SELECT * FROM historico_usuarios WHERE id_desenvolvedor = ? ORDER BY data_hora DESC";
        } else {
            throw new Exception("Tipo de usuário inválido.");
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    die("Erro ao buscar histórico: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Ações</title>
    <style>
         body {
    background-image: url('../../static/imgs/fundo_completo.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    margin: 0;
    padding: 20px;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: start;
}

    

        {
            background-color: #f0faff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #0e5db3;
            margin-bottom: 20px;
        }

        .historico-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .historico-item:last-child {
            border-bottom: none;
        }

        .acao {
            font-weight: bold;
            color: #333;
        }

        .data {
            color: #666;
            font-size: 0.9em;
        }

        .ip, .tipo, .ids {
            font-size: 0.9em;
            color: #444;
        }

        .motivo {
            margin-top: 5px;
            font-style: italic;
        }

        .voltar-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #ffffff;
            background-color: #0e5db3;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .voltar-link:hover {
            background-color: #084d99;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Histórico de Ações da Conta</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="historico-item">
                    <div class="acao">Ação: <?= htmlspecialchars($row['acao']) ?></div>
                    <div class="data">Data/Hora: <?= htmlspecialchars($row['data_hora']) ?></div>
                    <div class="ip">IP: <?= htmlspecialchars($row['ip']) ?></div>

                    <?php if (!empty($row['motivo'])): ?>
                        <div class="motivo">Motivo: <?= nl2br(htmlspecialchars($row['motivo'])) ?></div>
                    <?php endif; ?>

                    <?php if ($acesso_historico): ?>
                        <div class="tipo">Tipo de Usuário: <?= htmlspecialchars($row['tipo_usuario']) ?></div>
                        <div class="ids">ID Empresa: <?= htmlspecialchars($row['id_empresa']) ?> | ID Desenvolvedor: <?= htmlspecialchars($row['id_desenvolvedor']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Nenhum registro encontrado.</p>
        <?php endif; ?>

        <?php if (!$acesso_historico): ?>
            <a href="../../templates/dashboard_<?= $tipo_usuario ?>.php" class="voltar-link">← Voltar para o Dashboard</a>
        <?php else: ?>
            <a href="login_historico.php" class="voltar-link">← Voltar para o Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
