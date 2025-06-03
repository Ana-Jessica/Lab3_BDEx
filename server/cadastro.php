<?php
// Incluir o arquivo de conexão
include_once("conexao.php");

try {
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitizar e validar os dados
        $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
        $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_STRING);
        $perfil = filter_var($_POST['perfil'], FILTER_SANITIZE_STRING);
        $cep = filter_var($_POST['cep'], FILTER_SANITIZE_STRING);
        $endereco = filter_var($_POST['endereco'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar a senha

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        // Validar CPF/CNPJ (exemplo simples, você pode adicionar validações mais robustas)
        if (empty($cpf)) {
            throw new Exception("CPF/CNPJ é obrigatório.");
        }

        // Preparar a query SQL para inserir os dados
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, cpf, perfil, cep, endereco, email, senha) 
                                VALUES (:nome, :cpf, :perfil, :cep, :endereco, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':perfil', $perfil);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);

        // Executar a query
        $stmt->execute();
        
        // Redirecionar para uma página de sucesso
        header("Location: ../pglogin.html?status=success");
        exit();
    }
} catch (PDOException $e) {
    // Tratar erros de banco de dados
    echo "Erro ao cadastrar: " . $e->getMessage();
} catch (Exception $e) {
    // Tratar outros erros
    echo "Erro: " . $e->getMessage();
} finally {
    // Fechar a conexão
    $conn = null;
}
?>