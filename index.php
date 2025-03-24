<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

// Fetch ads
$stmt = $pdo->query('SELECT * FROM ads');
$ads = $stmt->fetchAll();

// Fetch publishers
$publishersStmt = $pdo->query('SELECT * FROM publishers');
$publishers = $publishersStmt->fetchAll();

// Fetch tags and categories
$tagsStmt = $pdo->query('SELECT DISTINCT tags FROM ads WHERE tags IS NOT NULL');
$tags = $tagsStmt->fetchAll(PDO::FETCH_COLUMN);

$categoriesStmt = $pdo->query('SELECT DISTINCT category FROM ads WHERE category IS NOT NULL');
$categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Thêm một số kiểu dáng bổ sung cho Dashboard */
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-gap: 1rem;
        }

        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        /* Style cho popup */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
        }

        .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".tablinks").click();
        });

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

        async function createAd() {
            const form = document.getElementById('newAdForm');
            const formData = new FormData(form);
            const response = await fetch('create_ad.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách quảng cáo
        }

        async function editAd(adId) {
            const response = await fetch('edit_ad.php?id=' + adId);
            const result = await response.text();
            document.getElementById('NewAd').innerHTML = result;
            openTab(event, 'NewAd');
        }

        async function updateAd(adId) {
            const form = document.getElementById('editAdForm');
            const formData = new FormData(form);
            const response = await fetch('edit_ad.php?id=' + adId, {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách quảng cáo
        }

        async function deleteAd(adId) {
            const response = await fetch('delete_ad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    id: adId
                })
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách quảng cáo
        }

        async function createPublisher() {
            const form = document.getElementById('newPublisherForm');
            const formData = new FormData(form);
            const response = await fetch('create_publisher.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách nhà xuất bản
        }

        async function editPublisher(publisherId) {
            const response = await fetch('edit_publisher.php?id=' + publisherId);
            const result = await response.text();
            document.getElementById('Publishers').innerHTML = result;
            openTab(event, 'Publishers');
        }

        async function deletePublisher(publisherId) {
            const response = await fetch('delete_publisher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    id: publisherId
                })
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách nhà xuất bản
        }

          // Hàm mở tab
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
    document.getElementsByClassName("tablinks")[0].click();

        // Hàm tạo mã nhúng
async function generateEmbedCode() {
    const form = document.getElementById('generateEmbedCodeForm');
    const formData = new FormData(form);
    const response = await fetch('generate_embed_code.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.text();

    // Ensure the DOM is ready before accessing elements
    document.addEventListener('DOMContentLoaded', function() {
        const embedCodeResult = document.getElementById('embed_code_result');
        const copyCodeButton = document.getElementById('copy_code_button');

        if (embedCodeResult && copyCodeButton) { // Check if elements exist
            embedCodeResult.textContent = result;
            copyCodeButton.style.display = 'block';
        } else {
            console.error('Error: embed_code_result or copy_code_button not found.');
        }
    });
}

function copyEmbedCode() {
    const embedCodeResult = document.getElementById('embed_code_result');
    if (!embedCodeResult) {
        console.error('Error: embed_code_result not found.');
        return;
    }
    const codeToCopy = embedCodeResult.textContent;

    navigator.clipboard.writeText(codeToCopy)
        .then(() => {
            alert('Embed code copied to clipboard!');
        })
        .catch(err => {
            console.error('Failed to copy code: ', err);
            alert('Failed to copy code. Please copy manually.');
        });
}
    </script>
</head>

<body>
    <div class="container">
        <div class="tabs">
            <button class="tablinks" onclick="openTab(event, 'Dashboard')">Dashboard</button>
            <button class="tablinks" onclick="openTab(event, 'NewAd')">New Ad</button>
            <button class="tablinks" onclick="openTab(event, 'Publishers')">Publishers</button>
            <button class="tablinks" onclick="openTab(event, 'EmbedCode')">Embed Code</button> <!-- Thêm tab Embed Code -->
            <button class="tablinks" onclick="openTab(event, 'Logout')">Logout</button>
        </div>

        <div id="Dashboard" class="tabcontent">
            <h1>Admin Dashboard</h1>
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
                        <?php foreach ($ads as $ad) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ad['title']); ?></td>
                                <td><?php echo htmlspecialchars($ad['type']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($ad['file_path']); ?>" alt="<?php echo htmlspecialchars($ad['title']); ?>" width="50">
                                </td>
                                <td><?php echo htmlspecialchars($ad['clicks']); ?></td>
                                <td><?php echo htmlspecialchars($ad['impressions']); ?></td>
                                <td><?php echo htmlspecialchars($ad['website_source']); ?></td>
                                <td><?php echo htmlspecialchars($ad['location']); ?></td>
                                <td><?php echo htmlspecialchars($ad['tags']); ?></td>
                                <td><?php echo htmlspecialchars($ad['category']); ?></td>
                                <td><?php echo htmlspecialchars($ad['status']); ?></td>
                                <td>
                                    <button onclick="editAd(<?php echo $ad['id']; ?>)">Edit</button>
                                    <button onclick="deleteAd(<?php echo $ad['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div id="NewAd" class="tabcontent">
            <h2>Create New Ad</h2>
            <form id="newAdForm" enctype="multipart/form-data">
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
                                <?php foreach ($publishers as $publisher) : ?>
                                    <option value="<?php echo htmlspecialchars($publisher['name']); ?>">
                                        <?php echo htmlspecialchars($publisher['name']); ?>
                                    </option>
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
                                    'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu', 'Bắc Ninh', 'Bến Tre',
                                    'Bình Định', 'Bình Dương', 'Bình Phước', 'Bình Thuận',
                                    'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai',
                                    'Hà Giang', 'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang',
                                    'Hòa Bình', 'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu', 'Lâm Đồng', 'Lạng Sơn',
                                    'Lào Cai', 'Long An', 'Nam Định', 'Nghệ An', 'Ninh Bình',
                                    'Ninh Thuận', 'Phú Thọ', 'Phú Yên', 'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
                                    'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
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
                            <button type="button" onclick="createAd()">Create Ad</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div id="Publishers" class="tabcontent">
            <h2>Publishers</h2>
            <form id="newPublisherForm">
                <label for="publisher_name">Publisher Name:</label>
                <input type="text" id="publisher_name" name="publisher_name" required>
                <label for="publisher_email">Publisher Email:</label>
                <input type="email" id="publisher_email" name="publisher_email" required>
                <label for="publisher_website">Publisher Website:</label>
                <input type="url" id="publisher_website" name="publisher_website" required>
                <label for="publisher_location">Publisher Location:</label>
                <select id="publisher_location" name="publisher_location" required>
                    <?php
                    $locations = [
                        'Toàn quốc', 'Hà Nội', 'TP Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
                        'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu', 'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước', 'Bình Thuận',
                    'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai',
                    'Hà Giang', 'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang',
                    'Hòa Bình', 'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu', 'Lâm Đồng', 'Lạng Sơn',
                    'Lào Cai', 'Long An', 'Nam Định', 'Nghệ An', 'Ninh Bình',
                    'Ninh Thuận', 'Phú Thọ', 'Phú Yên', 'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
                    'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
                    'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang', 'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
                ];
                foreach ($locations as $location) {
                    echo "<option value=\"$location\">$location</option>";
                }
                ?>
            </select>
            <button type="button" onclick="createPublisher()">Create Publisher</button>
        </form>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publishers as $publisher): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($publisher['name']); ?></td>
                        <td><?php echo htmlspecialchars($publisher['email']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($publisher['website']); ?>" target="_blank"><?php echo htmlspecialchars($publisher['website']); ?></a></td>
                        <td><?php echo htmlspecialchars($publisher['location']); ?></td>
                        <td>
                            <button onclick="editPublisher(<?php echo $publisher['id']; ?>)">Edit</button>
                            <button onclick="deletePublisher(<?php echo $publisher['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
</body>
</html>