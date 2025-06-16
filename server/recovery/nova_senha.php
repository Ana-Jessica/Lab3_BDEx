<?php
// nova_senha.php - Página para definir uma nova senha após clicar no link de redefinição

// Incluir arquivos necessários
require_once 'configTokenEmail.php'; // Configuração do banco de dados e email
require_once 'funcoesTokenEmail.php'; // Funções auxiliares

// Inicializar variáveis de controle
$erro = ''; // Mensagem de erro
$sucesso = ''; // Flag de sucesso
$tokenValido = false; // Flag de validade do token
$tokenData = null; // Dados do token

// Generate CSRF token early to ensure it's available
$csrfToken = generateCsrfToken();

// Verificar se token e tipo estão fornecidos na URL
if (!isset($_GET['token']) || !isset($_GET['tipo']) || empty($_GET['token']) || empty($_GET['tipo'])) {
    $erro = 'Link inválido! Solicite uma nova recuperação de senha.';
} else {
    $token = $_GET['token'];
    $tipo = $_GET['tipo'];
    
    // Validar tipo de usuário
    if (!in_array($tipo, ['empresa', 'desenvolvedor'])) {
        $erro = 'Tipo de usuário inválido!';
    } else {
        // Validar token
        $tokenData = validarToken($token, $tipo, $conn);
        if (!$tokenData) {
            $erro = 'Token inválido ou expirado! O link de recuperação só é válido por 1 hora.';
        } else {
            $tokenValido = true;
        }
    }
}

// Processar envio do formulário de redefinição de senha
if ($tokenValido && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Registrar dados POST para depuração
    error_log("POST data in nova_senha.php: " . print_r($_POST, true));
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $erro = 'Erro de segurança! Token CSRF inválido. Por favor, tente novamente.';
    } else {
        $novaSenha = $_POST['nova_senha'];
        $confirmarSenha = $_POST['confirmar_senha'];
        
        // Validações do formulário
        if (empty($novaSenha) || empty($confirmarSenha)) {
            $erro = 'Todos os campos são obrigatórios!';
        } elseif (strlen($novaSenha) < 8) {
            $erro = 'A senha deve ter pelo menos 8 caracteres!';
        } elseif (strlen($novaSenha) > 50) {
            $erro = 'A senha não pode ter mais que 50 caracteres!';
        } elseif ($novaSenha !== $confirmarSenha) {
            $erro = 'As senhas não coincidem! Digite novamente.';
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/', $novaSenha)) {
            $erro = 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial (!@#$%^&*).';
        } else {
            // Determine table and columns
            $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
            $senhaColumn = ($tipo === 'empresa') ? 'senha_empresa' : 'senha_desenvolvedor';
            $emailColumn = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
            
            // Fazer hash da nova senha
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            // Atualizar senha
            $updateSql = "UPDATE $tabela SET $senhaColumn = ? WHERE $emailColumn = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            if (!$updateStmt) {
                error_log("Erro na preparação da consulta do banco (UPDATE senha): " . mysqli_error($conn) . " | SQL: $updateSql");
                $erro = 'Erro interno no banco de dados. Tente novamente mais tarde.';
            } else {
                mysqli_stmt_bind_param($updateStmt, 'ss', $senhaHash, $tokenData['email_envio']);
                if (mysqli_stmt_execute($updateStmt)) {
                    if (mysqli_stmt_affected_rows($updateStmt) > 0) {
                        // Deletar token usado
                        $deleteSql = "DELETE FROM tokens_reset_senha WHERE token_email = ?";
                        $deleteStmt = mysqli_prepare($conn, $deleteSql);
                        if (!$deleteStmt) {
                            error_log("Erro na preparação da consulta do banco (DELETE token): " . mysqli_error($conn) . " | SQL: $deleteSql");
                        } else {
                            mysqli_stmt_bind_param($deleteStmt, 's', $token);
                            mysqli_stmt_execute($deleteStmt);
                            mysqli_stmt_close($deleteStmt);
                        }
                        $sucesso = true;
                        error_log("Senha alterada com sucesso para email: " . $tokenData['email_envio'] . " ($tipo)");
                    } else {
                        error_log("Nenhuma linha afetada ao atualizar senha para email: " . $tokenData['email_envio'] . " ($tipo)");
                        $erro = 'Erro ao alterar senha: Email não encontrado. Tente novamente.';
                    }
                } else {
                    error_log("Erro na execução da consulta do banco (UPDATE senha): " . mysqli_error($conn) . " | SQL: $updateSql");
                    $erro = 'Erro ao alterar senha. Tente novamente.';
                }
                mysqli_stmt_close($updateStmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha - BDEx</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            margin: auto;
            position: relative;
        }
        
        h2 { 
            text-align: center; 
            margin-bottom: 30px; 
            color: #333; 
            font-size: 24px;
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            color: #333; 
            font-weight: bold; 
            text-align: left;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
            display: block;
            margin: 0 auto;
        }
        
        input[type="password"]:focus { 
            outline: none; 
            border-color: #007bff; 
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25); 
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto 0;
        }
        
        .btn:hover { 
            background-color: #0056b3; 
        }
        
        .alert { 
            padding: 12px; 
            margin-bottom: 20px; 
            border-radius: 4px; 
            text-align: center;
        }
        
        .alert-error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        
        .alert-success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        
        .password-requirements { 
            font-size: 12px; 
            color: #666; 
            margin-top: 5px; 
            text-align: left;
            line-height: 1.4;
        }
        
        .success-message { 
            text-align: center; 
        }
        
        .success-message a { 
            color: #007bff; 
            text-decoration: none; 
            font-weight: bold;
        }
        
        .success-message a:hover { 
            text-decoration: underline; 
        }
        
        .link-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .link-container a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .link-container a:hover {
            text-decoration: underline;
        }
        
        form {
            width: 100%;
            margin: 0 auto;
        }
        
        /* Responsividade aprimorada */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
                max-width: 100%;
            }
            
            h2 {
                font-size: 20px;
                margin-bottom: 25px;
            }
            
            input[type="password"] {
                padding: 10px 12px;
                font-size: 14px;
            }
            
            .btn {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
    <?php if ($sucesso): ?>
        <meta http-equiv="refresh" content="2;url=../../templates/pglogin.html">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <h2>Definir Nova Senha</h2>
        
        <?php if (!empty($erro)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <div class="success-message">
                    <strong>Senha alterada com sucesso!</strong><br><br>
                    Sua senha foi atualizada. Você será redirecionado para o login em 2 segundos.<br><br>
                    <a href="../../templates/pglogin.html">Fazer Login Agora</a>
                </div>
            </div>
        <?php elseif ($tokenValido): ?>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input 
                        type="password" 
                        id="nova_senha" 
                        name="nova_senha" 
                        required 
                        minlength="8"
                        maxlength="50"
                        placeholder="Digite sua nova senha"
                    >
                    <div class="password-requirements">
                        Mínimo de 8 caracteres, incluindo 1 letra maiúscula, 1 número e 1 caractere especial (!@#$%^&*).
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha:</label>
                    <input 
                        type="password" 
                        id="confirmar_senha" 
                        name="confirmar_senha" 
                        required 
                        minlength="8"
                        maxlength="50"
                        placeholder="Confirme sua nova senha"
                    >
                </div>
                
                <button type="submit" class="btn">Alterar Senha</button>
            </form>
        <?php elseif (!empty($erro)): ?>
            <div class="link-container">
                <a href="solicitarRecup.php">Solicitar nova recuperação</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Validação do formulário no envio
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const novaSenha = document.getElementById('nova_senha')?.value;
            const confirmarSenha = document.getElementById('confirmar_senha')?.value;
            if (novaSenha && confirmarSenha) {
                // Verificar se as senhas coincidem
                if (novaSenha !== confirmarSenha) {
                    e.preventDefault();
                    alert('As senhas não coincidem!');
                    return false;
                }
                // Verificar comprimento mínimo
                if (novaSenha.length < 8) {
                    e.preventDefault();
                    alert('A senha deve ter pelo menos 8 caracteres!');
                    return false;
                }
                // Verificar complexidade da senha
                if (!/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/.test(novaSenha)) {
                    e.preventDefault();
                    alert('A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial (!@#$%^&*).');
                    return false;
                }
            }
        });
    </script>
</body>
</html>