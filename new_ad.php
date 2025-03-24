<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

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

    // Xử lý tải lên tệp
    $file_path = 'uploads/' . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $file_path);

    try {
        $stmt = $pdo->prepare('INSERT INTO ads (title, type, file_path, link, website_source, location, tags, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$title, $type, $file_path, $link, $website_source, $location, $tags, $category, $status]);
        echo 'Ad created successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Ad</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
    <script>
        function previewImage() {
            const file = document.getElementById('file').files[0];
            const preview = document.getElementById('imagePreview');
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    </script>
</head>
<body>
    <h2>Create New Ad</h2>
    <form method="POST" action="new_ad.php" enctype="multipart/form-data">
        <table>
            <tr>
                <th><label for="title">Title:</label></th>
                <td><input type="text" id="title" name="title" required></td>
            </tr>
            <tr>
                <th><label for="type">Type:</label></th>
                <td>
                    <select id="type" name="type" required>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="file">File:</label></th>
                <td>
                    <input type="file" id="file" name="file" accept="image/*" onchange="previewImage()" required>
                    <img id="imagePreview" src="" alt="Image preview" style="width: 200px; display: block; margin-top: 10px;">
                </td>
            </tr>
            <tr>
                <th><label for="link">Link:</label></th>
                <td><input type="url" id="link" name="link" required></td>
            </tr>
            <tr>
                <th><label for="website_source">Publisher:</label></th>
                <td>
                    <select id="website_source" name="website_source" required>
                        <?php foreach ($publishers as $publisher): ?>
                            <option value="<?php echo htmlspecialchars($publisher['name']); ?>"><?php echo htmlspecialchars($publisher['name']); ?></option>
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
                            echo "<option value=\"$location\">$location</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="tags">Tags:</label></th>
                <td><input type="text" id="tags" name="tags" required></td>
            </tr>
            <tr>
                <th><label for="category">Category:</label></th>
                <td><input type="text" id="category" name="category" required></td>
            </tr>
            <tr>
                <th><label for="status">Status:</label></th>
                <td>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">Create Ad</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>