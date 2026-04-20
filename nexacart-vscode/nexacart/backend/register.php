<?php
// ============================================================
//  NexaCart Backend — register.php
//  User registration with MySQL CREATE operation
//  Course: 23CSE404 | Capstone Project
// ============================================================

session_start();
header('Content-Type: application/json');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$first    = trim(htmlspecialchars($_POST['first_name'] ?? ''));
$last     = trim(htmlspecialchars($_POST['last_name'] ?? ''));
$email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$phone    = trim(preg_replace('/\D/', '', $_POST['phone'] ?? ''));
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm_password'] ?? '');

// Validate
$errors = [];
if (empty($first)) $errors[] = 'First name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (strlen($phone) < 10) $errors[] = 'Invalid phone number.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($password !== $confirm) $errors[] = 'Passwords do not match.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$conn = getDBConnection();

// Check email uniqueness
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
    $check->close(); $conn->close();
    exit;
}
$check->close();

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);
$name   = $first . ' ' . $last;

// INSERT user
$stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $hashed);

if ($stmt->execute()) {
    $userId = $conn->insert_id;
    $_SESSION['user_id']   = $userId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email']= $email;
    $_SESSION['logged_in'] = true;
    setcookie('nexacart_user', $email, time() + (7 * 24 * 60 * 60), '/');
    echo json_encode(['success' => true, 'message' => 'Account created successfully!', 'user' => ['name' => $name, 'email' => $email]]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
}

$stmt->close();
$conn->close();
?>
