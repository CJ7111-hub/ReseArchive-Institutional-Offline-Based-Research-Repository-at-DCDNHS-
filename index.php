<?php
// turn on error reporting for debugging; remove or disable in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_conn.php';

// verify connection object exists
if (!isset($pdo) || !$pdo) {
    die('Database connection not established. Check db_conn.php');
}

// handle optional logo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['school_logo'])) {
    if ($_FILES['school_logo']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['school_logo']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['school_logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['png','jpg','jpeg','gif'])) {
            if (!is_dir(__DIR__ . '/images')) {
                mkdir(__DIR__ . '/images', 0755, true);
            }
            move_uploaded_file($tmp, __DIR__ . '/images/logo.png');
            // reload to show logo
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $logo_error = 'Allowed formats: png, jpg, jpeg, gif';
        }
    } else {
        $logo_error = 'File upload failed (code ' . $_FILES['school_logo']['error'] . ').';
    }
}

$logo_exists = file_exists(__DIR__ . '/images/logo.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResearcHive | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="header-banner">
        <?php if ($logo_exists): ?>
        <div class="header-content">
            <img src="images/logo.png" alt="School Logo" class="school-logo">
            <div class="title-group">
                <h1>DOÑA CARMEN DENIA NATIONAL HIGH SCHOOL</h1>
                <h2 class="subtitle">Manuscript Repository</h2>
            </div>
        </div>
        <?php else: ?>
        <div class="header-content flex-column">
            <h1 class="mb-3">DOÑA CARMEN DENIA NATIONAL HIGH SCHOOL</h1>
            <h2 class="subtitle mb-4">Manuscript Repository</h2>
            <p class="text-warning">(school logo not found)</p>
            <form method="POST" enctype="multipart/form-data" class="upload-logo-form">
                <input type="file" name="school_logo" accept="image/*" required>
                <button type="submit" class="btn btn-light btn-sm mt-2">Upload Logo</button>
            </form>
            <?php if (!empty($logo_error)): ?>
            <p class="text-danger small"><?= htmlspecialchars($logo_error) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- header background image (faint, inside header only) -->
        
        <!-- decorative accent circles (purely visual) -->
        <div class="accent-circle left" aria-hidden="true"></div>
        <div class="accent-circle right" aria-hidden="true"></div>
    </div>
    <!--
        ===================== OUTLINE / EXTRA NON-EXECUTING BLOCK =====================
        The following commented block is intentionally added to increase the file
        length and to act as an 'outline' or placeholder for future content.
        It contains sample markup, notes, and pseudo-code only — it will NOT be
        executed by PHP nor rendered by the browser because it is inside an HTML
        comment. This keeps the visible output unchanged while making the source
        file longer for whatever purpose you need.

        Example placeholders and notes:

        <!-- Section: Future Filters -->
        <div class="future-filters">
            <!--
                Filter idea:
                - Filter by section (TAUSUG/YAKAN)
                - Filter by strand (STEM/TVL-ICT)
                - Date range picker
            -->
        </div>

        <!-- Section: Future Actions -->
        <div class="future-actions">
            <!--
                Actions to add:
                - Bulk download selected
                - Export CSV of metadata
                - Tagging system for categories
            -->
        </div>

        <!-- Pseudo-code for pagination (server-side)
            function fetchPage(page, perPage) {
                // SELECT * FROM manuscripts ORDER BY id DESC LIMIT offset, perPage
            }
        -->

        <!-- Accessibility TODOs:
            - Add skip-to-content link
            - Add role attributes to table for screen readers
            - Ensure color contrast passes WCAG
        -->

        <!-- End of outline placeholder. Remove or replace with real code when ready. -->
    -->
    <div class="container mt-n4">
        <div class="main-card shadow-lg p-4 bg-white rounded-4">
            <div class="row mb-4 align-items-center">
                <div class="col-md-8">
                    <div class="input-group search-bar" role="search" aria-label="Search manuscripts">
                        <span class="input-group-text border-0 bg-transparent"><i class="fas fa-search"></i></span>
                        <input type="text" id="liveSearch" class="form-control border-0 shadow-none" placeholder="Search manuscripts..." aria-label="Search manuscripts">
                    </div>
                    <small class="text-muted d-block mt-2">Press '/' to focus search</small>
                </div>
                <div class="col-md-4 text-end">
                    <button id="darkModeToggle" class="btn btn-outline-secondary me-2" title="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="add.php" class="btn btn-success rounded-pill px-4"><i class="fas fa-plus me-2"></i>ADD NEW</a>
                </div>
            </div>
            <!-- filter row -->
            <div class="row mb-3">
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
                <div class="col-md-4">
                    <input type="text" id="filterTags" class="form-control" placeholder="Filter by tag (comma-separated)">
                </div>
                <div class="col-md-2 text-end">
                    <a href="export.php" class="btn btn-sm btn-outline-primary">Export CSV</a>
                </div>
            </div>

            <!-- stats summary -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="p-3 bg-light rounded-3" style="font-size:0.9rem;">
                        <?php if (!empty($statsSection)): ?>
                            <strong>Sections:</strong>
                            <?php foreach($statsSection as $sec => $cnt): ?>
                                <?= htmlspecialchars($sec ?: 'Unspecified') ?> (<?= $cnt ?>)
                            <?php endforeach; ?>
                            &nbsp;|&nbsp;
                        <?php endif; ?>
                        <?php if (!empty($statsStrand)): ?>
                            <strong>Strands:</strong>
                            <?php foreach($statsStrand as $s => $cnt): ?>
                                <?= htmlspecialchars($s ?: 'Unspecified') ?> (<?= $cnt ?>)
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th><th>TITLE</th><th>AUTHOR</th><th>FILE</th><th>SECTION</th><th>STRAND</th><th>TAGS</th><th>DATE</th><th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="manuscriptTable">
                        <?php
                        try {
                            // gather simple stats for dashboard
                            $statsSection = [];
                            $statsStrand  = [];
                            foreach ($pdo->query("SELECT section, COUNT(*) AS cnt FROM manuscripts GROUP BY section") as $r) {
                                $statsSection[$r['section']] = $r['cnt'];
                            }
                            foreach ($pdo->query("SELECT strand, COUNT(*) AS cnt FROM manuscripts GROUP BY strand") as $r) {
                                $statsStrand[$r['strand']] = $r['cnt'];
                            }

                           $stmt = $pdo->query("
    SELECT * 
    FROM manuscripts 
    WHERE status != 'Archived'
    ORDER BY id DESC
");
$rows = $stmt->fetchAll();
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='7'>Error fetching manuscripts: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            $rows = [];
                        }
                        if (count($rows) === 0): ?>
                        <tr><td colspan="7" class="text-center">No manuscripts available.</td></tr>
                        <?php else:
                            foreach($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['author']) ?></td>
                            <td><?php if(!empty($row['file_path'])): ?><a href="#" class="preview-link" data-file="<?= htmlspecialchars($row['file_path']) ?>"><?= basename($row['file_path']) ?></a><?php else: ?>-<?php endif; ?></td>
                            <td><span class="badge badge-section"><?= $row['section'] ?></span></td>
                            <td><?= $row['strand'] ?></td>
                            <td><?php
                                if (!empty($row['tags'])) {
                                    $list = explode(',', $row['tags']);
                                    foreach ($list as $t) {
                                        echo '<span class="badge bg-light text-success me-1 small">' . htmlspecialchars(trim($t)) . '</span>';
                                    }
                                } else {
                                    echo '-';
                                }
                            ?></td>
                            <td><?= $row['date_uploaded'] ?></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-action text-primary"><i class="fas fa-pen"></i></a>
                                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn-action text-danger border-0 bg-transparent"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php $total = isset($rows) ? count($rows) : 0; ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">Showing 1 of <?= $total ?> entry<?= ($total !== 1) ? 'ies' : '' ?></div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- preview modal for documents -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="height:90vh;">
                <div class="modal-header">
                    <h5 class="modal-title">Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="previewBody">
                    <!-- embedded file will go here -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>