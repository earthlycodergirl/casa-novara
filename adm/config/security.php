<?php
/**
 * Security Configuration for Casa Novara Admin
 * Set these constants based on your environment
 */

// Environment detection - check if we're in development
$is_dev = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false || 
           strpos($_SERVER['HTTP_HOST'], '::1') !== false ||
           strpos($_SERVER['SERVER_NAME'], 'localhost') !== false);

// Debug mode - enabled in development, disabled in production
define('DEBUG_MODE', $is_dev);

// Database configuration - different for dev and production
if ($is_dev) {
    // Development database settings
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'casa_novara');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // Production database settings
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'cnghost_properties');
    define('DB_USER', 'cnghost_prod');
    define('DB_PASS', 'UnBD@eQ0pd$G');
}

// Security settings
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 300); // 5 minutes

// File upload settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'csv']);

// Error logging
define('LOG_DIRECTORY', __DIR__ . '/logs/');
define('ENABLE_ERROR_LOGGING', true);

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Input sanitization helper function
 */
function sanitize_input($input, $type = 'string') {
    switch($type) {
        case 'email':
            return filter_var(trim($input), FILTER_VALIDATE_EMAIL);
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT);
        case 'float':
            return filter_var($input, FILTER_VALIDATE_FLOAT);
        case 'url':
            return filter_var(trim($input), FILTER_VALIDATE_URL);
        case 'string':
        default:
            return filter_var(trim($input), FILTER_SANITIZE_STRING);
    }
}

/**
 * CSRF token validation
 */
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate CSRF token field for forms
 */
function csrf_token_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Secure file upload validation
 */
function validate_file_upload($file) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload error.';
        return $errors;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'File too large. Maximum size is ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB.';
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_FILE_TYPES)) {
        $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', ALLOWED_FILE_TYPES);
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowed_mimes = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf', 'text/csv',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    if (!in_array($mime_type, $allowed_mimes)) {
        $errors[] = 'Invalid file type detected.';
    }
    
    return $errors;
}

/**
 * Log security events
 */
function log_security_event($event, $details = '') {
    if (!ENABLE_ERROR_LOGGING) return;
    
    $log_entry = date('Y-m-d H:i:s') . " - SECURITY: $event";
    if ($details) {
        $log_entry .= " - Details: $details";
    }
    $log_entry .= " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
    
    $log_file = LOG_DIRECTORY . 'security.log';
    if (!is_dir(LOG_DIRECTORY)) {
        mkdir(LOG_DIRECTORY, 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>