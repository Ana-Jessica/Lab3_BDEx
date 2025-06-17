<?php
session_start();
require_once '../conexao.php';

// Permite acesso se for o admin via login_historico.php OU um usuário logado normalmente
$acesso_historico = isset($_SESSION['acesso_historico']) && $_SESSION['acesso_historico'] === true;

if (!$acesso_historico && (!isset($_SESSION['email']) || !isset($_SESSION['id']) || !isset($_SESSION['tipo']))) {
    header("Location: login_historico.php");
    exit();
}

try {
    if ($acesso_historico) {
        // Se for o admin, mostra o histórico completo
        $sql = "SELECT * FROM historico_usuarios ORDER BY data_hora DESC";
        $stmt = $conn->prepare($sql);
    } else {
        // Se for usuário comum, mostra apenas o próprio histórico
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
    <link rel="stylesheet" href="../../static/estilo.css"> <!-- Mantendo o estilo do projeto -->
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        .historico-item { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .acao { font-weight: bold; color: #555; }
        .data { color: #888; font-size: 0.9em; }
        .ip { font-style: italic; font-size: 0.9em; }
    </style>
</head>
<body>
    <h2>Histórico de Ações da Conta</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="historico-item">
                <div class="acao">Ação: <?= htmlspecialchars($row['acao']) ?></div>
                <div class="data">Data/Hora: <?= htmlspecialchars($row['data_hora']) ?></div>
                <div class="ip">IP: <?= htmlspecialchars($row['ip']) ?></div>
                <?php if (!empty($row['motivo'])): ?>
                    <div>Motivo: <?= nl2br(htmlspecialchars($row['motivo'])) ?></div>
                <?php endif; ?>
                <?php if ($acesso_historico): ?>
                    <div>Tipo de Usuário: <?= htmlspecialchars($row['tipo_usuario']) ?></div>
                    <div>ID Empresa: <?= htmlspecialchars($row['id_empresa']) ?></div>
                    <div>ID Desenvolvedor: <?= htmlspecialchars($row['id_desenvolvedor']) ?></div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nenhum registro encontrado.</p>
    <?php endif; ?>

    <?php if (!$acesso_historico): ?>
        <a href="../../templates/dashboard_<?= $tipo_usuario ?>.php">← Voltar para o Dashboard</a>
    <?php endif; ?>
</body>
</html>
