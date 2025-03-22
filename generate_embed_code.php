<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $display_type = $_POST['display_type'];
    $filter_type = $_POST['filter_type'];
    $filter_value = $_POST['filter_value'];

    $embed_code = '<script src="http://localhost:8888/embed_script.js" data-display-type="' . htmlspecialchars($display_type) . '" data-filter-type="' . htmlspecialchars($filter_type) . '" data-filter-value="' . htmlspecialchars($filter_value) . '"></script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Embed Code</title>
    <link rel="stylesheet" href="http://localhost:8888/css/style.css">
</head>
<body>
    <h2>Generated Embed Code</h2>
    <?php if (isset($embed_code)): ?>
        <textarea readonly style="width: 100%; height: 100px;"><?php echo $embed_code; ?></textarea>
    <?php else: ?>
        <p>Error generating embed code.</p>
    <?php endif; ?>
    <a href="embed_code.php" class="btn">Back</a>
</body>
</html>