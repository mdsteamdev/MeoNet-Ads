<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$publisher_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM publishers WHERE id = ?');
$stmt->execute([$publisher_id]);
$publisher = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $publisher_name = $_POST['publisher_name'];
    $publisher_email = $_POST['publisher_email'];
    $publisher_website = $_POST['publisher_website'];
    $publisher_location = $_POST['publisher_location'];

    try {
        $stmt = $pdo->prepare('UPDATE publishers SET name = ?, email = ?, website = ?, location = ? WHERE id = ?');
        $stmt->execute([$publisher_name, $publisher_email, $publisher_website, $publisher_location, $publisher_id]);
        echo 'Publisher updated successfully!';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Publisher</title>
</head>
<body>
    <h2>Edit Publisher</h2>
    <form method="POST" action="edit_publisher.php?id=<?php echo $publisher_id; ?>">
        <label for="publisher_name">Publisher Name:</label>
        <input type="text" id="publisher_name" name="publisher_name" value="<?php echo htmlspecialchars($publisher['name']); ?>" required>
        <label for="publisher_email">Publisher Email:</label>
        <input type="email" id="publisher_email" name="publisher_email" value="<?php echo htmlspecialchars($publisher['email']); ?>" required>
        <label for="publisher_website">Publisher Website:</label>
        <input type="url" id="publisher_website" name="publisher_website" value="<?php echo htmlspecialchars($publisher['website']); ?>" required>
        <label for="publisher_location">Publisher Location:</label>
        <select id="publisher_location" name="publisher_location" required>
            <!-- Thêm danh sách các quốc gia và 64 tỉnh thành ở Việt Nam, bao gồm "Toàn quốc" -->
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
                $selected = $publisher['location'] == $location ? 'selected' : '';
                echo "<option value=\"$location\" $selected>$location</option>";
            }
            ?>
        </select>
        <button type="submit">Update Publisher</button>
    </form>
</body>
</html>