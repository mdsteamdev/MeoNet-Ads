<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $website_source = $_POST['website_source'];
    $location = $_POST['location'];
    $tags = $_POST['tags'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $link = $_POST['link'];

    $uploadDir = 'uploads/ads/';
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO ads (title, type, file_path, website_source, location, tags, category, status, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $type, $uploadFile, $website_source, $location, $tags, $category, $status, $link]);
            $success = 'Ad uploaded successfully!';
        } catch (PDOException $e) {
            $error = 'Failed to upload ad: ' . $e->getMessage();
        }
    } else {
        $error = 'Failed to upload file.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Ad</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>New Ad</h2>
    <?php if (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="new_ad.php" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        
        <label for="type">Type:</label>
        <input type="text" id="type" name="type" required>
        
        <label for="file">File:</label>
        <input type="file" id="file" name="file" required>
        
        <label for="website_source">Website Source:</label>
        <input type="text" id="website_source" name="website_source" required>
        
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>
        
        <label for="tags">Tags:</label>
        <input type="text" id="tags" name="tags" required>
        
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Giải trí">Giải trí</option>
            <option value="Du lịch">Du lịch</option>
            <option value="Giáo dục">Giáo dục</option>
            <option value="Vận tải">Vận tải</option>
            <option value="Điện tử">Điện tử</option>
            <option value="Công nghệ">Công nghệ</option>
            <option value="Game">Game</option>
        </select>
        
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <label for="link">Ad Link:</label>
        <input type="url" id="link" name="link" required>

        <button type="submit">Submit</button>
    </form>
</body>
</html>