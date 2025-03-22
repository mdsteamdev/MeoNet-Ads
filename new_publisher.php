<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $company = $_POST['company'];
    $websites = $_POST['websites'];
    $locations = $_POST['locations'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare('INSERT INTO publishers (name, company, websites, locations, email) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $company, $websites, $locations, $email]);
    header('Location: publishers.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Publisher</title>
        <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h2>New Publisher</h2>
    <form method="POST" action="new_publisher.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="company">Company:</label>
        <input type="text" id="company" name="company" required>
        <label for="websites">Websites:</label>
        <input type="text" id="websites" name="websites" required>
        <label for="locations">Locations:</label>
        <input type="text" id="locations" name="locations" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Create Publisher</button>
    </form>
</body>
</html>