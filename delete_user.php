<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'config.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    if ($stmt->execute([$userId])) {
        header('Location: user_management.php');
        exit;
    } else {
        echo 'Failed to delete user.';
    }
} else {
    header('Location: user_management.php');
    exit;
}
?>