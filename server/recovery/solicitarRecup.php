<?php
// solicitarRecup.php - Page to request a password reset email

// Include required files
require_once 'configTokenEmail.php'; // Database and email config
require_once 'funcoesTokenEmail.php'; // Helper functions

// Initialize message variables
$mensagem = ''; // Message content
$tipoMensagem = ''; // Message type (success or error)

// Generate CSRF token
$csrfToken = generateCsrfToken();

// Implement basic rate limiting
if (!isset($_SESSION['last_reset_request'])) {
    $_SESSION['last_reset_request'] = 0; // Initialize last request time
}
// Check if less than 60 seconds since last request
if (time() - $_SESSION['last_reset_request'] < 60) {
    $mensagem = 'Por favor, aguarde 1 minuto antes de solicitar outra recuperação.';
    $tipoMensagem = 'error';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Log POST data for debugging
    error_log("POST data in solicitarRecup.php: " . print_r($_POST, true));
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $mensagem = 'Erro de segurança! Token CSRF inválido. Por favor, recarregue a página e tente novamente.';
        $tipoMensagem = 'error';
    } else {
        $email = trim($_POST['email']); // Get email
        $tipo = $_POST['tipo']; // Get user type (empresa or desenvolvedor)
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem = 'Por favor, insira um email válido!';
            $tipoMensagem = 'error';
        } elseif (!in_array($tipo, ['empresa', 'desenvolvedor'])) {
            // Validate user type
            $mensagem = 'Tipo de usuário inválido!';
            $tipoMensagem = 'error';
        } else {
            // Check if email exists in the specified table
            $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
            $emailColumn = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
            $checkSql = "SELECT $emailColumn FROM $tabela WHERE $emailColumn = ?";
            $checkStmt = mysqli_prepare($conn, $checkSql);
            if (!$checkStmt) {
                error_log("Erro na preparação da query (check email): " . mysqli_error($conn) . " | SQL: $checkSql");
                $mensagem = 'Erro interno no banco de dados. Tente novamente mais tarde.';
                $tipoMensagem = 'error';
            } else {
                mysqli_stmt_bind_param($checkStmt, 's', $email);
                mysqli_stmt_execute($checkStmt);
                $result = mysqli_stmt_get_result($checkStmt);
                if (mysqli_num_rows($result) === 0) {
                    $mensagem = 'Email não encontrado para o tipo de usuário selecionado.';
                    $tipoMensagem = 'error';
                } else {
                    // Generate and store token
                    $token = gerarToken();
                    $createdAt = date('Y-m-d H:i:s');
                    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    // Insert token into tokens_reset_senha
                    $insertSql = "INSERT INTO tokens_reset_senha (email_envio, token_email, created_at, expires_at) VALUES (?, ?, ?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertSql);
                    if (!$insertStmt) {
                        error_log("Erro na preparação da query (INSERT token): " . mysqli_error($conn) . " | SQL: $insertSql");
                        $mensagem = 'Erro interno no banco de dados. Tente novamente mais tarde.';
                        $tipoMensagem = 'error';
                    } else {
                        mysqli_stmt_bind_param($insertStmt, 'ssss', $email, $token, $createdAt, $expiresAt);
                        if (mysqli_stmt_execute($insertStmt)) {
                            // Send recovery email
                            if (enviarEmailRecuperacao($email, $token, $tipo)) {
                                $mensagem = 'Email de recuperação enviado com sucesso! Verifique sua caixa de entrada e a pasta de spam/lixo.';
                                $tipoMensagem = 'success';
                                $_SESSION['last_reset_request'] = time();
                            } else {
                                $mensagem = 'Erro ao enviar email. Tente novamente em alguns minutos.';
                                $tipoMensagem = 'error';
                            }
                        } else {
                            error_log("Erro na execução da query (INSERT token): " . mysqli_error($conn));
                            $mensagem = 'Erro interno. Tente novamente mais tarde.';
                            $tipoMensagem = 'error';
                        }
                        mysqli_stmt_close($insertStmt);
                    }
                }
                mysqli_stmt_close($checkStmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - BDEx</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
            border: 1px solid #e5e5e5;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .logo { 
            text-align: center; 
            margin-bottom: 25px; 
        }
        
        .logo h1 { 
            color: #2c3e50; 
            font-size: 22px; 
            margin-bottom: 5px; 
            font-weight: 700;
        }
        
        .logo p { 
            color: #7f8c8d; 
            font-size: 14px; 
        }
        
        .form-group { 
            margin-bottom: 18px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: 600; 
            color: #34495e;
            font-size: 14px;
        }
        
        input[type="email"], select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }
        
        input[type="email"]:focus, select:focus { 
            outline: none; 
            border-color: #1e3a8a;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e3a8a 0%, #16a085 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.3);
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .message {
            padding: 12px 15px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .message.success { 
            background-color: #d1fae5; 
            color: #065f46; 
            border: 1px solid #a7f3d0; 
        }
        
        .message.error { 
            background-color: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #fca5a5; 
        }
        
        .links { 
            text-align: center; 
            margin-top: 20px; 
        }
        
        .links a { 
            color: #1e3a8a; 
            text-decoration: none; 
            font-size: 14px;
            font-weight: 500;
        }
        
        .links a:hover { 
            text-decoration: underline; 
            color: #1d4ed8;
        }
        
        .info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
            color: #1e40af;
            line-height: 1.5;
        }
        
        .info strong {
            display: block;
            margin-bottom: 8px;
            color: #1e3a8a;
        }
        
        .info ul { 
            margin: 8px 0 0 18px; 
        }
        
        .info li { 
            margin-bottom: 4px; 
        }
        
        /* Responsividade */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
                margin: 0;
                max-width: 90vw;
                width: 90vw;
            }
            
            .logo h1 {
                font-size: 20px;
            }
        }
        
        /* Garantir que não tenha scroll */
        @media (max-height: 700px) {
            .container {
                padding: 20px;
                max-height: 85vh;
            }
            
            .info {
                margin-top: 15px;
                padding: 12px;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
            
            .logo {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🔐 Recuperar Senha</h1>
            <p>Digite seu email para receber instruções</p>
        </div>
        
        <?php if ($mensagem): ?>
            <div class="message <?php echo $tipoMensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validateForm()">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="seu@email.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="tipo">👤 Tipo de usuário:</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecione o tipo...</option>
                    <option value="empresa" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'empresa') ? 'selected' : ''; ?>>🏢 Empresa</option>
                    <option value="desenvolvedor" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'desenvolvedor') ? 'selected' : ''; ?>>💻 Desenvolvedor</option>
                </select>
            </div>
            <button type="submit" class="btn">📤 Enviar Email de Recuperação</button>
        </form>
        
        <div class="info">
            <strong>ℹ️ Como funciona:</strong>
            <ul>
                <li>Digite seu email cadastrado</li>
                <li>Escolha se você é empresa ou desenvolvedor</li>
                <li>Você receberá um email com link para nova senha</li>
                <li>O link expira em 1 hora</li>
            </ul>
        </div>
        
        <div class="links">
            <a href="../../templates/pglogin.html">← Voltar ao Login</a>
        </div>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const tipo = document.getElementById('tipo').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                alert('Por favor, insira um email válido!');
                return false;
            }
            if (!tipo) {
                alert('Por favor, selecione o tipo de usuário!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
?>