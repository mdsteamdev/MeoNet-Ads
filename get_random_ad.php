<?php
require 'config.php';

$filter_type = $_GET['filter_type'];
$filter_value = $_GET['filter_value'];

try {
    if ($filter_type === 'tag') {
        $stmt = $pdo->prepare('SELECT * FROM ads WHERE tags LIKE ? AND status = "active"');
    } else {
        $stmt = $pdo->prepare('SELECT * FROM ads WHERE category = ? AND status = "active"');
    }
    $stmt->execute(["%$filter_value%"]);
    $ads = $stmt->fetchAll();

    if (!empty($ads)) {
        $ad = $ads[array_rand($ads)]; // Chọn ngẫu nhiên một quảng cáo
        echo json_encode([
            'file_path' => $ad['file_path'],
            'link' => $ad['link']
        ]);
    } else {
        echo json_encode(['error' => 'No ads found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>