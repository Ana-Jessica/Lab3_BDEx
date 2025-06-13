<?php
include_once("../conexao.php");
// Inicia a sessão para pegar o ID da empresa
session_start();
$id_empresa_session = $_SESSION['id_empresa'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando e sanitizando os dados do formulário
    $id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_VALIDATE_INT);
    $titulo = filter_input(INPUT_POST, 'titulo_vaga', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao_vaga', FILTER_SANITIZE_STRING);
    $valor_oferta = isset($_POST['valor_oferta']) && $_POST['valor_oferta'] !== ''
        ? filter_input(INPUT_POST, 'valor_oferta', FILTER_VALIDATE_FLOAT)
        : null;
    $data_publicacao = date('Y-m-d');

    // Validação dos campos obrigatórios
    if (!$id_empresa || !$titulo || !$descricao) {
        echo "<script>alert('Preencha todos os campos obrigatórios!'); window.history.back();</script>";
        exit;
    }

    // Preparando a query
    $sql = "INSERT INTO Vaga (id_empresa, titulo_vaga, data_publicacao, descricao_vaga, valor_oferta) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isssd", $id_empresa, $titulo, $data_publicacao, $descricao, $valor_oferta);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Vaga criada com sucesso!'); window.location.href = '../../templates/dashboard_empresa.php';</script>";
        } else {
            echo "<script>alert('Erro ao executar a inserção: " . mysqli_error($conn) . "'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erro ao preparar a query: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }

    mysqli_close($conn);
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.modaleditarvaga');
            form.action = '" . $_SERVER['PHP_SELF'] . "';
            
            // Adiciona names aos inputs
            form.querySelector('.box-input:nth-child(2) input').name = 'titulo_vaga';
            form.querySelector('.box-input:nth-child(3) input').name = 'descricao_vaga';
            form.querySelector('.box-input:nth-child(4) input').name = 'valor_oferta';
            
            // Adiciona o ID da empresa (vindo do PHP)
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id_empresa';
            idInput.value = '" . $id_empresa_session . "';
            form.insertBefore(idInput, form.firstChild);
            
            // Atualiza o label da empresa
            const empresaLabel = form.querySelector('.box-input:nth-child(5) label');
            if(empresaLabel) {
                empresaLabel.textContent += ' (ID: " . $id_empresa_session . ")';
            }
        });
    </script>";
}
?>