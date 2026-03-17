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

$stmt = $pdo->prepare("SELECT * FROM manuscripts WHERE id = ?");
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
    <title>Edit Manuscript</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="col-md-6 mx-auto card shadow p-4 border-0 rounded-4">
            <h3 class="mb-4 fw-bold text-primary">Update Manuscript</h3>
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>"></div>
                <div class="mb-3"><label>Author</label><input type="text" name="author" class="form-control" value="<?= htmlspecialchars($row['author']) ?>"></div>
                <div class="col-md-3">
                    <select id="filterSection" class="form-select">
                        <option value="">All Sections</option>
                        <option>TAUSUG</option><option>YAKAN</option><option>SUBANEN</option><option>T'BOLI</option><option>MANSAKA</option><option>MARANAO</option><option>MANOBO</option><option>MATIGSALUG</option><option>IRANUN</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterStrand" class="form-select">
                        <option value="">All Strands</option>
                        <option>STEM</option><option>TVL-ICT</option><option>HUMSS</option><option>ABM</option>
                    </select>
                </div>
                <?php if (!empty($row['file_path'])): ?>
                <div class="mb-3">
                    <label>Current file</label>
                    <p><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank"><?= basename($row['file_path']) ?></a></p>
                </div>
                <?php endif; ?>
                <div class="mb-3"><label>Replace file (optional)</label><input type="file" name="manuscript_file" class="form-control" accept=".pdf,.doc,.docx,.txt"></div>
                <button type="submit" name="update_btn" class="btn btn-primary w-100">UPDATE</button>
            </form>
        </div>
    </div>
</body>
</html>