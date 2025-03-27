<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?');
        $params = [$username, $email, $role, $passwordHash, $userId];
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?');
        $params = [$username, $email, $role, $userId];
    }

    if ($stmt->execute($params)) {
        header('Location: user_management.php');
        exit;
    } else {
        echo 'Failed to update user.';
    }
} else {
    $userId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        echo 'User not found.';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form id="editUserForm" action="edit_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <label for="password">Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password">
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>