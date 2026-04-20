<?php
// ============================================================
//  NexaCart Backend — config.php
//  Database connection configuration
//  Course: 23CSE404 | Capstone Project
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Change for production
define('DB_PASS', '');              // Change for production
define('DB_NAME', 'nexacart_db');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
