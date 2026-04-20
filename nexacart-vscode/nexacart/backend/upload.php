<?php
// ============================================================
//  NexaCart Backend — upload.php
//  File Upload Handler (profile photo / product image)
//  Demonstrates: PHP file upload + session management
//  Course: 23CSE404 | Capstone Project
// ============================================================

session_start();
header('Content-Type: application/json');

require_once 'config.php';

// Require login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Please log in to upload files.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file received.']);
    exit;
}

$file     = $_FILES['file'];
$maxSize  = 5 * 1024 * 1024; // 5 MB
$allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

// Validate size
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File too large. Max 5MB allowed.']);
    exit;
}

// Validate MIME type using finfo (more secure than $_FILES['type'])
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
if (!in_array($mimeType, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, WEBP, GIF allowed.']);
    exit;
}

// Generate unique filename
$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('upload_', true) . '.' . strtolower($ext);
$uploadDir= __DIR__ . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$destination = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    // Store upload record in DB
    $conn     = getDBConnection();
    $userId   = $_SESSION['user_id'];
    $url      = '/backend/uploads/' . $filename;
    $stmt     = $conn->prepare("INSERT INTO uploads (user_id, filename, url) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $filename, $url);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'url' => $url, 'filename' => $filename]);
} else {
    echo json_encode(['success' => false, 'message' => 'Upload failed. Please try again.']);
}
?>
