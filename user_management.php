<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'config.php';

// Fetch users
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <form id="createUserForm" action="create_user.php" method="POST">
            <h2>Create New User</h2>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Create User</button>
        </form>

        <div class="table-container">
            <h2>Manage Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="actions">
                                <button onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                                <button onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editUser(userId) {
            window.location.href = 'edit_user.php?id=' + userId;
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'delete_user.php?id=' + userId;
            }
        }
    </script>
</body>
</html>