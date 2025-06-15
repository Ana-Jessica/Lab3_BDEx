<?php
session_start();
include_once("../conexao.php");

if ($_SESSION['tipo'] !== 'empresa') {
    header("Location: ../templates/pglogin.html");
    exit();
}

$acao = $_GET['acao'] ?? '';
$id_solicitacao = intval($_GET['id']);

if (!in_array($acao, ['aceitar', 'recusar'])) {
    die("Ação inválida.");
}

$id_empresa = $_SESSION['id'];

if ($acao === 'aceitar') {
    // Iniciar transação para garantir consistência
    $conn->begin_transaction();

    try {
        // 1. Recuperar os dados da solicitação e da vaga
        $stmt = $conn->prepare("
            SELECT s.id_desenvolvedor, s.id_vaga, v.status_vaga
            FROM solicitacao s
            INNER JOIN Vaga v ON s.id_vaga = v.id_vaga
            WHERE s.id_solicitacao = ? AND v.id_empresa = ?
        ");
        $stmt->bind_param("ii", $id_solicitacao, $id_empresa);
        $stmt->execute();
        $stmt->bind_result($id_desenvolvedor, $id_vaga, $status_vaga);

        if ($stmt->fetch()) {
            $stmt->close();

            // Verificar se a vaga já está conectada
            if ($status_vaga === 'conectada') {
                $_SESSION['erro'] = "Esta vaga já está conectada com outro desenvolvedor.";
                $conn->rollback();
                header("Location: ../templates/dashboard_empresa.php#solicitacoes");
                exit();
            }

            // 2. Atualizar o status da solicitação para 'aceita'
            $stmt2 = $conn->prepare("
                UPDATE solicitacao 
                SET status_solicitacao = 'aceita' 
                WHERE id_solicitacao = ?
            ");
            $stmt2->bind_param("i", $id_solicitacao);
            $stmt2->execute();
            $stmt2->close();

            // 3. Verificar se a conexão já existe
            $check = $conn->prepare("
                SELECT id_conexao 
                FROM Conexao 
                WHERE id_empresa = ? AND id_desenvolvedor = ? AND id_vaga = ?
            ");
            $check->bind_param("iii", $id_empresa, $id_desenvolvedor, $id_vaga);
            $check->execute();
            
            if ($check->fetch()) {
                // Conexão já existe
                $_SESSION['conexao_jaexiste'] = true;
            } else {
                // 4. Inserir a nova conexão
                $stmt3 = $conn->prepare("
                    INSERT INTO Conexao (id_empresa, id_desenvolvedor, id_vaga, data_conexao, status_conexao)
                    VALUES (?, ?, ?, NOW(), 'ativa')
                ");
                $stmt3->bind_param("iii", $id_empresa, $id_desenvolvedor, $id_vaga);
                $stmt3->execute();
                $stmt3->close();
                
                $_SESSION['conexao_criada'] = true;
            }
            $check->close();

            // 5. Atualizar o status da vaga para 'conectada'
            $stmt4 = $conn->prepare("
                UPDATE Vaga 
                SET status_vaga = 'conectada' 
                WHERE id_vaga = ?
            ");
            $stmt4->bind_param("i", $id_vaga);
            $stmt4->execute();
            $stmt4->close();

            $conn->commit();
            $_SESSION['mensagem'] = "Solicitação aceita e conexão estabelecida com sucesso. Vaga marcada como conectada.";
        } else {
            $conn->rollback();
            $_SESSION['erro'] = "Solicitação inválida ou não pertence à sua empresa.";
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erro'] = "Erro ao processar a solicitação: " . $e->getMessage();
    }
}

if ($acao === 'recusar') {
    // Atualiza o status da solicitação para 'rejeitada'
    $stmt = $conn->prepare("
        UPDATE solicitacao 
        SET status_solicitacao = 'rejeitada' 
        WHERE id_solicitacao = ?
    ");
    $stmt->bind_param("i", $id_solicitacao);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Solicitação recusada com sucesso.";
    } else {
        $_SESSION['erro'] = "Erro ao recusar a solicitação.";
    }

    $stmt->close();
}

header("Location: ../../templates/dashboard_empresa.php#solicitacoes");
exit();
?>