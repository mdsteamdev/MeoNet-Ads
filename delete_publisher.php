<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $publisher_id = $_POST['id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM publishers WHERE id = ?');
        $stmt->execute([$publisher_id]);
        echo 'Publisher deleted successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>