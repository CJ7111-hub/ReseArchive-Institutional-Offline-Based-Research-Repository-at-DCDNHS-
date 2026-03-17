<?php
require_once 'config.php';
if (!isset($pdo) || !$pdo) {
    die('DB connection not established.');
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=manuscripts_' . date('Ymd_His') . '.csv');
$output = fopen('php://output', 'w');
// write header
fputcsv($output, ['ID','Title','Author','Section','Strand','Tags','Date Uploaded','Summary','File Path']);
foreach ($pdo->query('SELECT * FROM manuscripts ORDER BY id DESC') as $row) {
    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['author'],
        $row['section'],
        $row['strand'],
        $row['tags'],
        $row['date_uploaded'],
        $row['summary'],
        $row['file_path']
    ]);
}
fclose($output);
exit;