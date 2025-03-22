<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM ads WHERE id = ?');
$stmt->execute([$id]);
$ad = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $file_path = $ad['file_path'];
    if (!empty($_FILES['file']['name'])) {
        $file_path = 'uploads/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    }
    $website_source = $_POST['website_source'];
    $location = $_POST['location'];
    $tags = $_POST['tags'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare('UPDATE ads SET title = ?, type = ?, file_path = ?, website_source = ?, location = ?, tags = ?, category = ?, status = ? WHERE id = ?');
    $stmt->execute([$title, $type, $file_path, $website_source, $location, $tags, $category, $status, $id]);
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ad</title>
        <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h2>Edit Ad</h2>
    <form method="POST" action="edit_ad.php?id=<?php echo $ad['id']; ?>" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($ad['title']); ?>" required>
        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="image" <?php if ($ad['type'] == 'image') echo 'selected'; ?>>Image</option>
            <option value="video" <?php if ($ad['type'] == 'video') echo 'selected'; ?>>Video</option>
        </select>
        <label for="file">File:</label>
        <input type="file" id="file" name="file">
        <label for="website_source">Website Source:</label>
        <input type="text" id="website_source" name="website_source" value="<?php echo htmlspecialchars($ad['website_source']); ?>" required>
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($ad['location']); ?>" required>
        <label for="tags">Tags:</label>
        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($ad['tags']); ?>" required>
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($ad['category']); ?>" required>
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active" <?php if ($ad['status'] == 'active') echo 'selected'; ?>>Active</option>
            <option value="paused" <?php if ($ad['status'] == 'paused') echo 'selected'; ?>>Paused</option>
            <option value="deleted" <?php if ($ad['status'] == 'deleted') echo 'selected'; ?>>Deleted</option>
        </select>
        <button type="submit">Update Ad</button>
    </form>
</body>
</html>