<?php
// ==============================
// BASIC DATABASE CONFIGURATION
// ==============================
$host = 'localhost';
$db   = 'research_db';
$user = 'root';
$pass = '';

// Show errors (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if PDO MySQL is enabled
if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL extension not installed or enabled.');
}

try {

    // Connect to MySQL server first (without selecting DB)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    $pdo->exec("USE $db");

    // =========================
    // SECTIONS TABLE
    // =========================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            section_name VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;
    ");

    // Insert default sections if empty
    $pdo->exec("
        INSERT IGNORE INTO sections (id, section_name) VALUES
        (1,'TAUSUG'),
        (2,'YAKAN'),
        (3,'SUBANEN'),
        (4,'T''BOLI'),
        (5,'MARANAO'),
        (6,'MANSAKA'),
        (7,'TAGAKAOLO'),
        (8,'TAGABAWA');
    ");

    // =========================
    // STRANDS TABLE
    // =========================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS strands (
            id INT AUTO_INCREMENT PRIMARY KEY,
            strand_name VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;
    ");

    $pdo->exec("
        INSERT IGNORE INTO strands (id, strand_name) VALUES
        (1,'TVL ICT'),
        (2,'TVL EIM'),
        (3,'HUMSS'),
        (4,'STEM');
    ");

    // =========================
    // MANUSCRIPTS TABLE
    // =========================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS manuscripts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            abstract TEXT,
            keywords TEXT,
            publication_year YEAR,
            adviser VARCHAR(255),
            section_id INT,
            strand_id INT,
            file_path VARCHAR(500),
            upload_date DATE,
            status ENUM('Approved','Pending','Archived') DEFAULT 'Pending',

            FOREIGN KEY (section_id) REFERENCES sections(id)
                ON DELETE SET NULL ON UPDATE CASCADE,

            FOREIGN KEY (strand_id) REFERENCES strands(id)
                ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB;
    ");

    // =========================
    // DOWNLOADS TABLE
    // =========================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS downloads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            manuscript_id INT NOT NULL,
            download_date DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id)
                ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB;
    ");

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>