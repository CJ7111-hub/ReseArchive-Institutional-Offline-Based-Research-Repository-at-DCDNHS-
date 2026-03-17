<?php
// show errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_conn.php';
if (!isset($pdo) || !$pdo) {
    die('Database connection not established.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Manuscript</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        <div class="col-md-6 mx-auto card shadow p-4 border-0 rounded-4">
            <h3 class="mb-4 fw-bold text-success">Upload New Manuscript</h3>
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3"><label>Author</label><input type="text" name="author" class="form-control" required></div>
                <div class="mb-3"><label>File</label><input type="file" name="manuscript_file" class="form-control" accept=".pdf,.doc,.docx,.txt" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Section</label><select name="section" class="form-select"><option>TAUSUG</option><option>YAKAN</option><option>SUBANEN</option><option>T'BOLI</option><option>MANSAKA</option><option>MATIGSALUG</option><option>MARANAO</option><option>MANOBO</option><option>IRANUN</option></select></div>
                    <div class="col-md-6 mb-3"><label>Strand</label><select name="strand" class="form-select"><option>STEM</option><option>TVL-ICT</option><option>HUMSS</option><option>ABM</option></select></div>
                </div>
                <div class="mb-3"><label>Tags <small class="text-muted">comma-separated</small></label><input type="text" name="tags" class="form-control" placeholder="e.g. research,math,2025"></div>
                <div class="mb-3"><label>Summary</label><textarea name="summary" class="form-control" rows="3" placeholder="Short summary or abstract"></textarea>
                    <button type="button" id="generateSummary" class="btn btn-sm btn-outline-secondary mt-2">Auto-generate</button>
                </div>
                <div class="mb-3"><label>Date</label><input type="date" name="date_uploaded" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                <button type="submit" name="add_btn" class="btn btn-success w-100">SAVE</button>
                <a href="index.php" class="btn btn-link w-100 text-muted mt-2">Cancel</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
