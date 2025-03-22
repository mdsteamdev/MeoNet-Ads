<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$stmt = $pdo->query('SELECT * FROM publishers');
$publishers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Publishers</title>
        <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h2>Publishers</h2>
    <a href="new_publisher.php">New Publisher</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Websites</th>
                <th>Locations</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($publishers as $publisher): ?>
            <tr>
                <td><?php echo htmlspecialchars($publisher['name']); ?></td>
                <td><?php echo htmlspecialchars($publisher['company']); ?></td>
                <td><?php echo htmlspecialchars($publisher['websites']); ?></td>
                <td><?php echo htmlspecialchars($publisher['locations']); ?></td>
                <td><?php echo htmlspecialchars($publisher['email']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>