<?php
// Bật hiển thị lỗi để kiểm tra (chỉ dùng trong môi trường phát triển)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Lấy loại hiển thị từ form (Banner Tĩnh hoặc Popup)
$displayType = $_POST['display_type'] ?? 'fixed';

// Tạo mã nhúng dựa trên loại hiển thị
if ($displayType === 'fixed') {
    // Mã nhúng cho Banner Tĩnh
    $embedCode = <<<HTML
<script src="static_ad.js" data-type="fixed"></script>
HTML;
} elseif ($displayType === 'popup') {
    // Mã nhúng cho Popup
    $embedCode = <<<HTML
<script src="static_ad.js" data-type="popup"></script>
HTML;
} else {
    // Trả về lỗi nếu loại hiển thị không hợp lệ
    echo 'Invalid display type.';
    exit;
}

// Trả về mã nhúng
echo $embedCode;
?>