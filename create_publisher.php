<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $publisher_name = $_POST['publisher_name'];
    $publisher_email = $_POST['publisher_email'];
    $publisher_website = $_POST['publisher_website'];
    $publisher_location = $_POST['publisher_location'];

    try {
        $stmt = $pdo->prepare('INSERT INTO publishers (name, email, website, location) VALUES (?, ?, ?, ?)');
        $stmt->execute([$publisher_name, $publisher_email, $publisher_website, $publisher_location]);
        echo 'Publisher created successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>