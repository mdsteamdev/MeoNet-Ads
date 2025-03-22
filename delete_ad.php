<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM ads WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: admin_dashboard.php');
    exit;
}
?>