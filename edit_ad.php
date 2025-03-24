<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$ad_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM ads WHERE id = ?');
$stmt->execute([$ad_id]);
$ad = $stmt->fetch();

// Lấy danh sách publishers từ cơ sở dữ liệu
$publishersStmt = $pdo->query('SELECT * FROM publishers');
$publishers = $publishersStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $file = $_FILES['file'];
    $link = $_POST['link'];
    $website_source = $_POST['website_source'];
    $location = $_POST['location'];
    $tags = $_POST['tags'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    if ($file['name']) {
        // Xử lý tải lên tệp mới
        $file_path = 'uploads/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $file_path);
    } else {
        $file_path = $ad['file_path'];
    }

    try {
        $stmt = $pdo->prepare('UPDATE ads SET title = ?, type = ?, file_path = ?, link = ?, website_source = ?, location = ?, tags = ?, category = ?, status = ? WHERE id = ?');
        $stmt->execute([$title, $type, $file_path, $link, $website_source, $location, $tags, $category, $status, $ad_id]);
        echo 'Ad updated successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ad</title>
</head>
<body>
    <h2>Edit Ad</h2>
    <form id="editAdForm" enctype="multipart/form-data">
        <table>
            <tr>
                <th><label for="title">Title:</label></th>
                <td><input type="text" id="title" name="title" value="<?php echo htmlspecialchars($ad['title']); ?>" required></td>
            </tr>
            <tr>
                <th><label for="type">Type:</label></th>
                <td>
                    <select id="type" name="type" required>
                        <option value="image" <?php echo $ad['type'] == 'image' ? 'selected' : ''; ?>>Image</option>
                        <option value="video" <?php echo $ad['type'] == 'video' ? 'selected' : ''; ?>>Video</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="file">File:</label></th>
                <td>
                    <input type="file" id="file" name="file" accept="image/*" onchange="previewImage()">
                    <img id="imagePreview" src="<?php echo htmlspecialchars($ad['file_path']); ?>" alt="Image preview" style="width: 200px; display: block; margin-top: 10px;">
                </td>
            </tr>
            <tr>
                <th><label for="link">Link:</label></th>
                <td><input type="url" id="link" name="link" value="<?php echo htmlspecialchars($ad['link']); ?>" required></td>
            </tr>
            <tr>
                <th><label for="website_source">Publisher:</label></th>
                <td>
                    <select id="website_source" name="website_source" required>
                        <?php foreach ($publishers as $publisher): ?>
                            <option value="<?php echo htmlspecialchars($publisher['name']); ?>" <?php echo $ad['website_source'] == $publisher['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($publisher['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="location">Location:</label></th>
                <td>
                    <select id="location" name="location" required>
                        <?php
                        $locations = [
                            'Toàn quốc', 'Hà Nội', 'TP Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
                            'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu', 'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước', 'Bình Thuận',
                            'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang', 'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang',
                            'Hòa Bình', 'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu', 'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định', 'Nghệ An', 'Ninh Bình',
                            'Ninh Thuận', 'Phú Thọ', 'Phú Yên', 'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị', 'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
                            'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang', 'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
                        ];
                        foreach ($locations as $location) {
                            echo "<option value=\"$location\" " . ($ad['location'] == $location ? 'selected' : '') . ">$location</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="tags">Tags:</label></th>
                <td><input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($ad['tags']); ?>" required></td>
            </tr>
            <tr>
                <th><label for="category">Category:</label></th>
                <td><input type="text" id="category" name="category" value="<?php echo htmlspecialchars($ad['category']); ?>" required></td>
            </tr>
            <tr>
                <th><label for="status">Status:</label></th>
                <td>
                    <select id="status" name="status" required>
                        <option value="active" <?php echo $ad['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="paused" <?php echo $ad['status'] == 'paused' ? 'selected' : ''; ?>>Paused</option>
                        <option value="deleted" <?php echo $ad['status'] == 'deleted' ? 'selected' : ''; ?>>Deleted</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="button" onclick="updateAd(<?php echo $ad_id; ?>)">Update Ad</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>