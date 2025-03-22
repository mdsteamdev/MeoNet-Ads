<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$stmt = $pdo->query('SELECT * FROM ads');
$ads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    

    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="new_ad.php">New Ad</a>
    <a href="publishers.php">Publishers</a>
    <a href="embed_code.php">Embed Code</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>File</th>
                <th>Clicks</th>
                <th>Impressions</th>
                <th>Website Source</th>
                <th>Location</th>
                <th>Tags</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ads as $ad): ?>
            <tr>
                <td><?php echo htmlspecialchars($ad['title']); ?></td>
                <td><?php echo htmlspecialchars($ad['type']); ?></td>
                <td><img src="<?php echo htmlspecialchars($ad['file_path']); ?>" alt="<?php echo htmlspecialchars($ad['title']); ?>" width="50"></td>
                <td><?php echo htmlspecialchars($ad['clicks']); ?></td>
                <td><?php echo htmlspecialchars($ad['impressions']); ?></td>
                <td><?php echo htmlspecialchars($ad['website_source']); ?></td>
                <td><?php echo htmlspecialchars($ad['location']); ?></td>
                <td><?php echo htmlspecialchars($ad['tags']); ?></td>
                <td><?php echo htmlspecialchars($ad['category']); ?></td>
                <td><?php echo htmlspecialchars($ad['status']); ?></td>
                <td>
                    <a href="edit_ad.php?id=<?php echo $ad['id']; ?>">Edit</a>
                    <form method="POST" action="delete_ad.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>