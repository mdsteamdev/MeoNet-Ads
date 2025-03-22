<?php
$host = 'localhost';
$db = 'ad_manager';
$user = 'root';  // Đảm bảo rằng tên người dùng này đúng
$pass = '123456';      // Đảm bảo rằng mật khẩu này đúng

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

?>