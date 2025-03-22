<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>