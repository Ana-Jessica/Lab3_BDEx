<?php
// funcoesTokenEmail.php - Helper functions for token generation, email sending, and token validation

// Include Composer's autoloader for PHPMailer
require_once '../vendor/autoload.php';
// Include configuration file
require_once 'configTokenEmail.php';

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Generate secure token for password reset
function gerarToken() {
    // Generate 32 random bytes and convert to 64-character hexadecimal
    return bin2hex(random_bytes(32));
}

// Send password recovery email
function enviarEmailRecuperacao($email, $token, $tipo) {
    $mail = new PHPMailer(true);
    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Host = EMAIL_HOST;
        $mail->Port = EMAIL_PORT;

        // Email settings
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Generate dynamic reset link
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        $basePath = dirname(dirname($_SERVER['SCRIPT_NAME']));
        $linkRecuperacao = $protocol . $domain . $basePath . '/recovery/nova_senha.php?token=' . urlencode($token) . '&tipo=' . urlencode($tipo);

        // Email content
        $mail->Subject = 'Recuperação de Senha - ' . EMAIL_FROM_NAME;
        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { background-color: white; padding: 30px; margin: 20px 0; }
                .button { background-color: #007bff; color: white; padding: 12px 30px; 
                         text-decoration: none; border-radius: 5px; display: inline-block; 
                         margin: 20px 0; }
                .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Recuperação de Senha</h1>
                </div>
                <div class='content'>
                    <h2>Olá!</h2>
                    <p>Você solicitou a recuperação de sua senha.</p>
                    <p>Para criar uma nova senha, clique no botão abaixo:</p>
                    <a href='$linkRecuperacao' class='button'>Redefinir Minha Senha</a>
                    <p><strong>Importante:</strong></p>
                    <ul>
                        <li>Este link expira em <strong>1 hora</strong></li>
                        <li>Se você não solicitou esta recuperação, ignore este email</li>
                        <li>Sua senha atual continua válida até você criar uma nova</li>
                    </ul>
                </div>
                <div class='footer'>
                    <p>Este é um email automático, não responda.</p>
                    <p>© 2025 " . EMAIL_FROM_NAME . " - Todos os direitos reservados</p>
                </div>
            </div>
        </body>
        </html>";
        $mail->AltBody = "Recuperação de Senha\n\nVocê solicitou a recuperação de sua senha.\n\nAcesse o link abaixo para criar uma nova senha:\n$linkRecuperacao\n\nEste link expira em 1 hora.\nSe você não solicitou esta recuperação, ignore este email.";
        
        // Send email
        $mail->send();
        error_log("Email de recuperação enviado para $email com token $token");
        return true;
    } catch (Exception $e) {
        // Log email sending errors
        error_log("Erro ao enviar email de recuperação para $email: " . $mail->ErrorInfo);
        return false;
    }
}

// Validate password reset token
function validarToken($token, $tipo, $conn) {
    // Validate table name to prevent SQL injection
    if (!in_array($tipo, ['empresa', 'desenvolvedor'])) {
        error_log("Tipo de usuário inválido na validação de token: $tipo");
        return false;
    }
    // Determine table and email column
    $tabela = ($tipo === 'empresa') ? 'empresa' : 'desenvolvedor';
    $emailColumn = ($tipo === 'empresa') ? 'email_empresa' : 'email_desenvolvedor';
    
    // Query tokens_reset_senha and join with user table to verify email
    $sql = "SELECT t.email_envio, t.created_at, t.expires_at 
            FROM tokens_reset_senha t
            JOIN $tabela u ON t.email_envio = u.$emailColumn
            WHERE t.token_email = ? 
            AND t.expires_at > NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Erro na preparação da query (validarToken): " . mysqli_error($conn) . " | SQL: $sql");
        return false;
    }
    mysqli_stmt_bind_param($stmt, 's', $token);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Erro na execução da query (validarToken): " . mysqli_error($conn));
        mysqli_stmt_close($stmt);
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    $tokenData = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($tokenData) {
        error_log("Token válido encontrado para email: " . $tokenData['email_envio']);
    } else {
        error_log("Token inválido ou expirado: $token");
    }
    return $tokenData ? $tokenData : false;
}
?>