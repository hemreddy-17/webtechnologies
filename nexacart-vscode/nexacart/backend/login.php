<?php
// ============================================================
//  NexaCart Backend — login.php
//  Handles user login with session management
//  Course: 23CSE404 | Capstone Project
// ============================================================

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = trim($_POST['password'] ?? '');

// Basic validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password too short.']);
    exit;
}

$conn = getDBConnection();

// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No account found with this email.']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    $stmt->close();
    $conn->close();
    exit;
}

// Set session
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email']= $user['email'];
$_SESSION['logged_in'] = true;

// Set login cookie (remember for 7 days)
setcookie('nexacart_user', $user['email'], time() + (7 * 24 * 60 * 60), '/');

echo json_encode([
    'success' => true,
    'message' => 'Login successful!',
    'user'    => ['name' => $user['name'], 'email' => $user['email']]
]);

$stmt->close();
$conn->close();
?>
