<?php
// development error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_conn.php';
if (!isset($pdo) || !$pdo) {
    die('Database connection not established.');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid record ID.');
}

$stmt = $pdo->prepare("SELECT title FROM manuscripts WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    die('Record not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="container text-center">
        <div class="col-md-4 mx-auto card p-4 shadow border-0 rounded-4">
            <h4 class="text-danger">Delete Record?</h4>
            <p>Are you sure you want to delete <b>"<?= $row['title'] ?>"</b>?</p>
            <a href="process.php?delete_id=<?= $id ?>" class="btn btn-danger">Yes, Delete</a>
            <a href="index.php" class="btn btn-light mt-2">No, Cancel</a>
        </div>
    </div>
</body>
</html>