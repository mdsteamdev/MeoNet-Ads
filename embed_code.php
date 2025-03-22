<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$tags = $pdo->query('SELECT DISTINCT tags FROM ads')->fetchAll();
$categories = $pdo->query('SELECT DISTINCT category FROM ads')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Embed Code</title>
    <link rel="stylesheet" href="http://localhost:8888/css/style.css">
</head>
<body>
    <h2>Generate Embed Code</h2>
    <form method="POST" action="generate_embed_code.php">
        <label for="display_type">Display Type:</label>
        <select id="display_type" name="display_type" required>
            <option value="popup">Popup</option>
            <option value="fixed">Fixed</option>
        </select>
        <label for="filter_type">Filter By:</label>
        <select id="filter_type" name="filter_type" required>
            <option value="tag">Tag</option>
            <option value="category">Category</option>
        </select>
        <label for="filter_value">Filter Value:</label>
        <select id="filter_value" name="filter_value" required>
            <?php foreach ($tags as $tag): ?>
                <option value="<?php echo htmlspecialchars($tag['tags']); ?>"><?php echo htmlspecialchars($tag['tags']); ?></option>
            <?php endforeach; ?>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['category']); ?>"><?php echo htmlspecialchars($category['category']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Generate</button>
    </form>
</body>
</html>