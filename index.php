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

$categories = ['Popup', 'Banner'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
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
        .embed-code-container {
            margin-top: 20px;
        }
        .embed-code {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .copy-button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .copy-button:hover {
            background-color: #0056b3;
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


 async function approveAd(adId, approve) {
        try {
            const response = await fetch('approve_ad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    id: adId,
                    approve: approve
                })
            });

            const result = await response.text();
            
            // Kiểm tra xem phản hồi có chứa "Session expired" hoặc "Unauthorized access"
            if (result.includes('Session expired') || result.includes('Unauthorized access') || result.includes('<html>')) {
                console.error('Unexpected HTML response:', result);
                alert(result); // Hiển thị thông báo kết quả
                window.location.href = 'login.php'; // Chuyển hướng tới trang đăng nhập
            } else {
                alert(result); // Hiển thị thông báo kết quả
                location.reload(); // Tải lại trang để cập nhật danh sách quảng cáo
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while approving the ad.');
        }
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
                body: new URLSearchParams({ id: publisherId })
            });
            const result = await response.text();
            alert(result); // Hiển thị thông báo kết quả
            location.reload(); // Tải lại trang để cập nhật danh sách nhà xuất bản
        }

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

        function copyEmbedCode() {
            const embedCodeResult = document.getElementById('embed_code_result');
            embedCodeResult.select();
            document.execCommand('copy');
            alert('Embed code copied to clipboard!');
        }
    </script>
</head>
<body>
<div class="tabs">
    <button class="tablinks" onclick="openTab(event, 'Dashboard')">Dashboard</button>
    <button class="tablinks" onclick="openTab(event, 'NewAd')">New Ad</button>
    <button class="tablinks" onclick="openTab(event, 'Publishers')">Publishers</button>
    <button class="tablinks" onclick="openTab(event, 'EmbedCode')">Embed Code</button>
    <button class="tablinks" onclick="openTab(event, 'UserManagement')">User Management</button> <!-- Thêm tab User Management -->
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
        <th>Approve</th> <!-- Thêm cột Approve -->
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($ads as $ad) : ?>
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
                <?php if ($ad['approved']): ?>
                    Approved
                <?php else: ?>
                    <button onclick="approveAd(<?php echo $ad['id']; ?>, true)">Approve</button>
                    <button onclick="approveAd(<?php echo $ad['id']; ?>, false)">Reject</button>
                <?php endif; ?>
            </td>
            <td>
                <button onclick="editAd(<?php echo $ad['id']; ?>)">Edit</button>
                <button onclick="deleteAd(<?php echo $ad['id']; ?>)">Delete</button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
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
                    <input type="file" id="file" name="file" accept="image/*,video/mp4,video/mov" onchange="previewImage()" required>
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
                            <option value="MeoLinks">MeoLinks</option>
                            <option value="MeoNet">MeoNet</option>
                            <option value="MeoTicket">MeoTicket</option>
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
                    <td>
                        <select id="category" name="category" required>
                            <option value="Popup">Popup</option>
                            <option value="Banner">Banner</option>
                        </select>
                    </td>
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
                <option value="banner">Banner</option>
            </select>
            <button type="button" onclick="generateEmbedCode()">Generate Embed Code</button>
        </form>

        <!-- Ô hiển thị kết quả mã nhúng -->
        <div style="margin-top: 20px;">
            <label for="embed_code_result">Embed Code Result:</label>
            <textarea id="embed_code_result" rows="6" style="width: 100%;" readonly></textarea>
        </div>
        <button id="copy_code_button" type="button" onclick="copyEmbedCode()" style="display: block;">Copy Embed Code</button>
    </div>
    
    <div id="UserManagement" class="tabcontent">
    <h2>User Management</h2>
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
                <?php
                // Kết nối đến cơ sở dữ liệu và lấy danh sách người dùng
                require 'config.php';
                $stmt = $pdo->query('SELECT * FROM users');
                $users = $stmt->fetchAll();

                foreach ($users as $user): ?>
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

    

    <div id="Logout" class="tabcontent">
        <h2>Logout</h2>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
