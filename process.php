<?php
// development error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_conn.php';
if (!isset($pdo) || !$pdo) {
    die('Database connection not established.');
}

try {
    if (isset($_POST['add_btn'])) {
        $title  = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $section = $_POST['section'] ?? null;
        $strand  = $_POST['strand'] ?? null;
        $date    = $_POST['date_uploaded'] ?? null;
        $tags    = trim($_POST['tags'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        if ($title === '' || $author === '') {
            throw new Exception('Title and author are required.');
        }
        // file upload
        $filePath = null;
        if (!empty($_FILES['manuscript_file']['name'])) {
            if ($_FILES['manuscript_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error.');
            }
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmp = $_FILES['manuscript_file']['tmp_name'];
            $origName = basename($_FILES['manuscript_file']['name']);
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $allowed = ['pdf','doc','docx','txt'];
            if (!in_array(strtolower($ext), $allowed)) {
                throw new Exception('Invalid file type.');
            }
            $newName = uniqid('file_', true) . '.' . $ext;
            $dest = $uploadDir . '/' . $newName;
            if (!move_uploaded_file($tmp, $dest)) {
                throw new Exception('Unable to move uploaded file.');
            }
            $filePath = 'uploads/' . $newName;
        }
        $stmt = $pdo->prepare("INSERT INTO manuscripts (title, author, section, strand, date_uploaded, file_path, tags, summary) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $author, $section, $strand, $date, $filePath, $tags ?: null, $summary ?: null]);
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['update_btn'])) {
        $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title  = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        if (!$id || $title === '' || $author === '') {
            throw new Exception('ID, title and author are required for update.');
        }
        // fetch existing path
        $stmt = $pdo->prepare("SELECT file_path FROM manuscripts WHERE id=?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        $filePath = $existing['file_path'] ?? null;
        if (!empty($_FILES['manuscript_file']['name'])) {
            if ($_FILES['manuscript_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error.');
            }
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmp = $_FILES['manuscript_file']['tmp_name'];
            $origName = basename($_FILES['manuscript_file']['name']);
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $allowed = ['pdf','doc','docx','txt'];
            if (!in_array(strtolower($ext), $allowed)) {
                throw new Exception('Invalid file type.');
            }
            $newName = uniqid('file_', true) . '.' . $ext;
            $dest = $uploadDir . '/' . $newName;
            if (!move_uploaded_file($tmp, $dest)) {
                throw new Exception('Unable to move uploaded file.');
            }
            if ($filePath && file_exists(__DIR__ . '/' . $filePath)) {
                @unlink(__DIR__ . '/' . $filePath);
            }
            $filePath = 'uploads/' . $newName;
        }
        $stmt = $pdo->prepare("UPDATE manuscripts SET title=?, author=?, file_path=?, tags=?, summary=? WHERE id=?");
        $stmt->execute([$title, $author, $filePath, $tags ?: null, $summary ?: null, $id]);
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['delete_id'])) {

    $id = filter_input(INPUT_GET, 'delete_id', FILTER_VALIDATE_INT);

    if (!$id) {
        throw new Exception('Invalid ID for deletion.');
    }

    // SOFT DELETE → change status to Archived
    $stmt = $pdo->prepare("UPDATE manuscripts SET status='Archived' WHERE id=?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}
} catch (Exception $e) {
    // display message and stop (or could redirect with error param)
    die('Error: ' . htmlspecialchars($e->getMessage()));
}
?>