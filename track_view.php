<?php
require 'config.php';

// Bật hiển thị lỗi để kiểm tra
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Lấy ID quảng cáo từ query string
    $adId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($adId > 0) {
        // Tăng số lượt hiển thị cho quảng cáo
        $query = 'UPDATE ads SET impressions = impressions + 1 WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $adId]);

        // Trả về phản hồi thành công
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    // Nếu không có ID hợp lệ, trả về lỗi
    header('Content-Type: application/json', true, 400);
    echo json_encode(['error' => 'Invalid ad ID']);
} catch (PDOException $e) {
    // Trả về lỗi nếu có vấn đề với cơ sở dữ liệu
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Trả về lỗi nếu có vấn đề khác
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>