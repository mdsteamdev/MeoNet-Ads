<?php
require 'config.php';

try {
    // Lấy ID quảng cáo từ query string
    $adId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($adId > 0) {
        // Tăng số lượt nhấp chuột
        $query = 'UPDATE ads SET clicks = clicks + 1 WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $adId]);

        // Chuyển hướng đến liên kết quảng cáo
        $linkQuery = 'SELECT link FROM ads WHERE id = :id';
        $linkStmt = $pdo->prepare($linkQuery);
        $linkStmt->execute(['id' => $adId]);
        $ad = $linkStmt->fetch(PDO::FETCH_ASSOC);

        if ($ad && isset($ad['link'])) {
            header('Location: ' . $ad['link']);
            exit;
        }
    }

    // Nếu không tìm thấy quảng cáo, chuyển hướng về trang chủ
    header('Location: /');
    exit;
} catch (Exception $e) {
    // Trả về lỗi nếu có vấn đề
    echo 'Error: ' . $e->getMessage();
}
?>