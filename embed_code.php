<?php
require 'config.php';

if (isset($_GET['id'])) {
    $adId = $_GET['id'];

    // Fetch ad details
    $stmt = $pdo->prepare('SELECT * FROM ads WHERE id = ?');
    $stmt->execute([$adId]);
    $ad = $stmt->fetch();

    if ($ad) {
        // Generate embed code based on ad type
        $embedCode = '';
        if ($ad['type'] == 'image') {
            $embedCode = '<img src="' . htmlspecialchars($ad['file_path']) . '" alt="' . htmlspecialchars($ad['title']) . '">';
        } elseif ($ad['type'] == 'video') {
            $embedCode = '<video src="' . htmlspecialchars($ad['file_path']) . '" controls></video>';
        }

        // Output the embed code
        echo $embedCode;
    } else {
        echo 'Ad not found.';
    }
} else {
    echo 'Invalid request.';
}
?>