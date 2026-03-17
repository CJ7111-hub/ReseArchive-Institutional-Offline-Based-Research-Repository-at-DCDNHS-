<?php
// basic configuration
$host = 'localhost';
$db   = 'research_db';
$user = 'root';
$pass = '';

// display errors for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// make sure PDO MySQL driver is available
if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL extension not installed or enabled.');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // ensure the expected table exists (creates if missing)
    $pdo->exec("CREATE TABLE IF NOT EXISTS manuscripts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        section VARCHAR(100) DEFAULT NULL,
        strand VARCHAR(100) DEFAULT NULL,
        date_uploaded DATE DEFAULT NULL,
        file_path VARCHAR(255) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    // add file_path column if database/table already existed but column missing
    $pdo->exec("ALTER TABLE manuscripts ADD COLUMN IF NOT EXISTS file_path VARCHAR(255) DEFAULT NULL;");
} catch (PDOException $e) {
    // if database does not exist, give user instructions
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        die('Database "' . htmlspecialchars($db) . '" not found. Create it and try again.');
    }
    die("Connection failed: " . $e->getMessage());
}
?>