<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $file = $_FILES['file'];
    $link = $_POST['link'];
    $website_source = $_POST['website_source'];
    $location = $_POST['location'];
    $tags = $_POST['tags'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    // Xử lý tải lên tệp
    $file_path = 'uploads/' . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $file_path);

    try {
        $stmt = $pdo->prepare('INSERT INTO ads (title, type, file_path, link, website_source, location, tags, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$title, $type, $file_path, $link, $website_source, $location, $tags, $category, $status]);
        echo 'Ad created successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>