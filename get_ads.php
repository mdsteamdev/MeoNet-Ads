<?php
require 'config.php';

$filterType = $_GET['filter_type'];
$filterValue = $_GET['filter_value'];

try {
    if ($filterType === 'tag') {
        $stmt = $pdo->prepare('SELECT file_path, link FROM ads WHERE tags = ?');
    } else {
        $stmt = $pdo->prepare('SELECT file_path, link FROM ads WHERE category = ?');
    }
    $stmt->execute([$filterValue]);
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ads);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>