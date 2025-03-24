<?php
require 'config.php';

try {
    // Lấy tất cả quảng cáo từ cơ sở dữ liệu
    $query = 'SELECT id, file_path, link FROM ads WHERE status = "active"';
    $stmt = $pdo->query($query);
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kiểm tra nếu không có quảng cáo nào
    if (empty($ads)) {
        $ads = [];
    }

    // Trả về danh sách quảng cáo dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($ads, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Trả về lỗi nếu có vấn đề
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>