<?php
session_start();
include_once("conexao.php");
include_once("auth.php");

// Verifica se é empresa
if ($_SESSION['tipo'] !== 'empresa') {
    header("Location: ../templates/pglogin.html");
    exit();
}

$id_empresa = $_SESSION['id'];

// Ação padrão (listar vagas)
$acao = $_GET['acao'] ?? 'listar';
$id_vaga = $_GET['id'] ?? null;

switch ($acao) {
    case 'excluir':
        excluirVaga($conn, $id_vaga, $id_empresa);
        break;
        
    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            editarVaga($conn, $id_vaga, $id_empresa);
        }
        break;
        
        
    default:
        listarVagas($conn, $id_empresa);
}

function listarVagas($conn, $id_empresa) {
    $sql = "SELECT * FROM Vaga WHERE id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h2>Suas Vagas</h2>";
    echo "<ul>";
    while ($vaga = $result->fetch_assoc()) {
        echo "<li>
                {$vaga['titulo_vaga']} - 
                <a href='?acao=editar&id={$vaga['id_vaga']}'>Editar</a> | 
                <a href='?acao=excluir&id={$vaga['id_vaga']}' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
              </li>";
    }
    echo "</ul>";
}

function excluirVaga($conn, $id_vaga, $id_empresa) {
    // Verifica se a vaga pertence à empresa antes de excluir
    $sql = "DELETE FROM Vaga WHERE id_vaga = ? AND id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_vaga, $id_empresa);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Vaga excluída com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao excluir vaga!";
    }
    
    header("Location: ../templates/dashboard_empresa.php");
    exit();
}


function editarVaga($conn, $id_vaga, $id_empresa) {
    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $valor = isset($_POST['valor']) && $_POST['valor'] !== ''
        ? (float) str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor'])
        : null;

    // Validação básica
    if (empty($titulo) || empty($descricao)) {
        $_SESSION['erro'] = "Campos obrigatórios não preenchidos!";
        header("Location: ../templates/dashboard_empresa.php");
        exit();
    }

    $sql = "UPDATE Vaga SET 
            titulo_vaga = ?,
            descricao_vaga = ?,
            valor_oferta = ?
            WHERE id_vaga = ? AND id_empresa = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $titulo, $descricao, $valor, $id_vaga, $id_empresa);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Vaga atualizada com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar vaga!";
    }

    header("Location: ../templates/dashboard_empresa.php");
    exit();
}

?>