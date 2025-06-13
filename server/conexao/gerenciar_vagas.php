<?php
session_start();
include_once("../conexao.php");
include_once("auth.php");

// Verifica se é empresa
if ($_SESSION['tipo'] !== 'empresa') {
    header("Location: ../../templates/pglogin.html");
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

function listarVagas($conn, $id_empresa)
{
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

function excluirVaga($conn, $id_vaga, $id_empresa)
{
    // Verifica se a vaga pertence à empresa antes de excluir
    $sql = "DELETE FROM Vaga WHERE id_vaga = ? AND id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_vaga, $id_empresa);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Vaga excluída com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao excluir vaga!";
    }

    header("Location: ../../templates/dashboard_empresa.php");
    exit();
}


function editarVaga($conn, $id_vaga, $id_empresa)
{
    // Verifica se os dados foram enviados
    if (!isset($_POST['titulo'], $_POST['descricao'], $_POST['status_vaga'])) {
        $_SESSION['erro'] = "Campos obrigatórios não foram preenchidos.";
        header("Location: ../../templates/dashboard_empresa.php");
        exit();
    }

    // Captura e trata os dados do formulário
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $valor_raw = $_POST['valor'] ?? '';
    $status_vaga = trim($_POST['status_vaga']); // ✅ agora está sendo capturado

    // Validação do status
    $status_permitidos = ['ativa', 'fechada', 'conectada'];
    if (!in_array($status_vaga, $status_permitidos)) {
        $_SESSION['erro'] = "Status da vaga inválido.";
        header("Location: ../../templates/dashboard_empresa.php");
        exit();
    }

    // Converte valor para float seguro
    $valor = null;
    if (!empty($valor_raw)) {
        // Remove R$, ponto de milhar, e converte vírgula para ponto
        $valor = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $valor_raw));
    }

    // Verifica se a vaga pertence à empresa
    $check_sql = "SELECT 1 FROM vaga WHERE id_vaga = ? AND id_empresa = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_vaga, $id_empresa);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        $_SESSION['erro'] = "Vaga não encontrada ou não pertence à sua empresa.";
        header("Location: ../../templates/dashboard_empresa.php");
        exit();
    }

    // Atualiza a vaga com status
    $sql = "UPDATE vaga SET 
                titulo_vaga = ?, 
                descricao_vaga = ?, 
                valor_oferta = ?, 
                status_vaga = ?
            WHERE id_vaga = ? AND id_empresa = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['erro'] = "Erro na preparação da query: " . $conn->error;
        header("Location: ../../templates/dashboard_empresa.php");
        exit();
    }

    $stmt->bind_param("ssdssi", $titulo, $descricao, $valor, $status_vaga, $id_vaga, $id_empresa);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Vaga atualizada com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar vaga: " . $stmt->error;
    }

    header("Location: ../../templates/dashboard_empresa.php");
    exit();
}


?>