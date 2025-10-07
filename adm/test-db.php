<?php
// Simple database connection test
require_once 'config/security.php';

echo "<h2>Database Connection Test</h2>";
echo "<p><strong>Environment:</strong> " . (DEBUG_MODE ? 'Development' : 'Production') . "</p>";
echo "<p><strong>Database Host:</strong> " . DB_HOST . "</p>";
echo "<p><strong>Database Name:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Database User:</strong> " . DB_USER . "</p>";

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'><strong>✅ Database connection successful!</strong></p>";
    
    // Test if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Users table exists</p>";
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<p>👥 Total users in database: " . $result['count'] . "</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Users table does not exist</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ Database connection failed:</strong></p>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "<p style='color: blue;'><strong>💡 Solution:</strong> Create the database '" . DB_NAME . "' in your MySQL server.</p>";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<p style='color: blue;'><strong>💡 Solution:</strong> Check your database username and password.</p>";
    } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<p style='color: blue;'><strong>💡 Solution:</strong> Make sure MySQL server is running.</p>";
    }
}
?>