<?php
require 'config.php';

$username = 'admin';
$password = '123456';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->execute([$username, $hashed_password]);
    echo 'User created successfully!';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>