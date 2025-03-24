<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password) || empty($email)) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } else {
        try {
            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Thêm User vào cơ sở dữ liệu
            $query = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'email' => $email
            ]);

            $success = 'Tạo tài khoản thành công!';
        } catch (PDOException $e) {
            $error = 'Lỗi: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký User</title>
</head>
<body>
    <h1>Đăng ký User</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Đăng ký</button>
    </form>
</body>
</html>