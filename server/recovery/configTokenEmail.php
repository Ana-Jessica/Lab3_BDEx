<?php
// configTokenEmail.php - Configuration file for database and email settings

require_once '../conexao.php'; // Database connection

// Define email configuration constants for PHPMailer
define('EMAIL_HOST', 'smtp.gmail.com'); // SMTP server host
define('EMAIL_PORT', 587); // SMTP port (587 for TLS)
define('EMAIL_USERNAME', 'bancobdex@gmail.com'); // Gmail address
define('EMAIL_PASSWORD', 'uvhu etet wlyn tvbf'); // Gmail App Password
define('EMAIL_FROM', 'bancobdex@gmail.com'); // Sender email
define('EMAIL_FROM_NAME', 'BDEx'); // Sender name

// Start session for CSRF protection and rate limiting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session ID to ensure persistence
error_log("Session ID in configTokenEmail.php: " . session_id());

// Verify database connection
if (!$conn || mysqli_connect_error()) {
    error_log("Database connection invalid in configTokenEmail.php: " . mysqli_connect_error());
    die("Erro de conexão com o banco de dados.");
}

// Generate CSRF token for form security
function generateCsrfToken() {
    // Generate token only if it doesn't exist
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 64-character secure token
        error_log("Generated new CSRF token: " . $_SESSION['csrf_token']);
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validateCsrfToken($token) {
    // Log validation attempt
    error_log("Validating CSRF token: Submitted=$token, Session=" . ($_SESSION['csrf_token'] ?? 'none'));
    // Check if token exists and matches session token
    $isValid = isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    if (!$isValid) {
        error_log("CSRF token validation failed: Submitted=$token, Session=" . ($_SESSION['csrf_token'] ?? 'none'));
    }
    // Regenerate token after validation to prevent reuse
    unset($_SESSION['csrf_token']);
    generateCsrfToken(); // Generate new token for next form
    return $isValid;
}
?>