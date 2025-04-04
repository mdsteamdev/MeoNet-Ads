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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="tabs">
        <button class="tablinks" onclick="openTab(event, 'Dashboard')">Dashboard</button>
        <button class="tablinks" onclick="openTab(event, 'NewAd')">New Ad</button>
        <button class="tablinks" onclick="openTab(event, 'Publishers')">Publishers</button>
        <button class="tablinks" onclick="openTab(event, 'EmbedCode')">Embed Code</button>
        <button class="tablinks" onclick="openTab(event, 'Logout')">Logout</button>
    </div>

    <div id="Dashboard" class="tabcontent">
        <h2>Admin Dashboard</h2>
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
                        <td>
                            <a href="edit_ad.php?id=<?php echo $ad['id']; ?>">Edit</a>
                            <form method="POST" action="delete_ad.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
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
    <form id="generateEmbedCodeForm">
        <label for="display_type">Display Type:</label>
        <select id="display_type" name="display_type" required>
            <option value="popup">Popup</option>
            <option value="fixed">Fixed</option>
        </select>
        <label for="filter_type">Filter By:</label>
        <select id="filter_type" name="filter_type" required>
            <option value="tag">Tag</option>
            <option value="category">Category</option>
        </select>
        <label for="filter_value">Filter Value:</label>
        <select id="filter_value" name="filter_value" required>
            <?php foreach ($tags as $tag): ?>
                <option value="<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></option>
            <?php endforeach; ?>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" onclick="generateEmbedCode()">Generate Embed Code</button>
    </form>

    <!-- Ô hiển thị kết quả mã nhúng -->
    <div style="margin-top: 20px;">
        <label for="embed_code_result">Embed Code Result:</label>
        <textarea id="embed_code_result" rows="6" style="width: 100%;" readonly></textarea>
    </div>
</div>

<script>
  async function generateEmbedCode() {
    const form = document.getElementById('generateEmbedCodeForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('generate_embed_code.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Failed to generate embed code');
        }

        const result = await response.text();

        // Hiển thị kết quả trong ô textarea (mã hóa HTML)
        const embedCodeResult = document.getElementById('embed_code_result');
        embedCodeResult.value = result;
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while generating the embed code.');
    }
}
</script>

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