<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$stmt = $pdo->query('SELECT * FROM ads');
$ads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Ads Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="tabs">
        <button class="tablinks" onclick="openTab(event, 'Dashboard')">Dashboard</button>
        <button class="tablinks" onclick="openTab(event, 'NewAd')">New Ad</button>
        <button class="tablinks" onclick="openTab(event, 'EmbedCode')">Embed Code</button>
        <button class="tablinks" onclick="openTab(event, 'Logout')">Logout</button>
    </div>

    <div id="Dashboard" class="tabcontent">
        <h2>User Dashboard</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>File</th>
                        <th>Clicks</th>
                        <th>Impressions</th>
                        <th>Publisher</th>
                        <th>Location</th>
                        <th>Tags</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ads as $ad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ad['title']); ?></td>
                        <td><?php echo htmlspecialchars($ad['type']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($ad['file_path']); ?>" alt="<?php echo htmlspecialchars($ad['title']); ?>" width="50"></td>
                        <td><?php echo htmlspecialchars($ad['clicks']); ?></td>
                        <td><?php echo htmlspecialchars($ad['impressions']); ?></td>
                        <td><?php echo htmlspecialchars($ad['website_source']); ?></td>
                        <td><?php echo htmlspecialchars($ad['location']); ?></td>
                        <td><?php echo htmlspecialchars($ad['tags']); ?></td>
                        <td><?php echo htmlspecialchars($ad['category']); ?></td>
                        <td><?php echo htmlspecialchars($ad['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="NewAd" class="tabcontent">
        <h2>New Ad</h2>
        <form method="POST" action="create_ad.php" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="image">Image</option>
                <option value="video">Video</option>
            </select>
            <label for="file">File:</label>
            <input type="file" id="file" name="file" required>
            <label for="website_source">Publisher:</label>
            <input type="text" id="website_source" name="website_source" required>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            <label for="tags">Tags:</label>
            <input type="text" id="tags" name="tags" required>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
                <option value="deleted">Deleted</option>
            </select>
            <button type="submit">Create Ad</button>
        </form>
    </div>

    <div id="Publishers" class="tabcontent">
        <h2>Publishers</h2>
        <form method="POST" action="create_publisher.php">
            <label for="publisher_name">Publisher Name:</label>
            <input type="text" id="publisher_name" name="publisher_name" required>
            <label for="publisher_email">Publisher Email:</label>
            <input type="email" id="publisher_email" name="publisher_email" required>
            <label for="publisher_website">Publisher Website:</label>
            <input type="url" id="publisher_website" name="publisher_website" required>
            <button type="submit">Create Publisher</button>
        </form>
    </div>

<div id="EmbedCode" class="tabcontent">
    <h2>Generate Embed Code</h2>
    <form method="POST" action="">
        <label for="display_type">Select Display Type:</label>
        <select id="display_type" name="display_type" required>
            <option value="banner" <?php echo (isset($_POST['display_type']) && $_POST['display_type'] === 'banner') ? 'selected' : ''; ?>>Banner</option>
            <option value="popup" <?php echo (isset($_POST['display_type']) && $_POST['display_type'] === 'popup') ? 'selected' : ''; ?>>Popup</option>
        </select>
        <button type="submit">Generate Code</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $displayType = $_POST['display_type'] ?? 'banner';
        $embedCode = generateEmbedCode($displayType);

        if ($embedCode): ?>
            <div class="embed-code-container">
                <h2>Your Embed Code:</h2>
                <textarea readonly><?php echo htmlspecialchars($embedCode); ?></textarea>
            </div>
        <?php else: ?>
            <p class="error">Invalid display type.</p>
        <?php endif;
    }
    ?>
</div>

    <div id="Logout" class="tabcontent">
        <h2>Logout</h2>
        <a href="logout.php">Logout</a>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Mở tab mặc định
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".tablinks").click();
        });
    </script>
</body>
</html>