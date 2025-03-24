<?php
require 'config.php';

// Lấy kích thước từ query string (nếu có)
$width = isset($_GET['width']) ? (int)$_GET['width'] : null;
$height = isset($_GET['height']) ? (int)$_GET['height'] : null;

// Lọc quảng cáo dựa trên kích thước (nếu có)
$query = 'SELECT file_path, link, width, height FROM ads WHERE status = "active"';
if ($width && $height) {
    $query .= ' AND width = :width AND height = :height';
}

$stmt = $pdo->prepare($query);
if ($width && $height) {
    $stmt->execute(['width' => $width, 'height' => $height]);
} else {
    $stmt->execute();
}

$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nếu không có quảng cáo nào, hiển thị thông báo
if (empty($ads)) {
    echo '<p>No ads available.</p>';
    exit;
}

// Chọn ngẫu nhiên một quảng cáo
$ad = $ads[array_rand($ads)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Display</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background: transparent;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <a href="<?php echo htmlspecialchars($ad['link']); ?>" target="_blank">
        <img src="<?php echo htmlspecialchars($ad['file_path']); ?>" alt="Ad">
    </a>
</body>
</html>