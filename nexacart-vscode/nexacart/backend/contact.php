<?php
// ============================================================
//  NexaCart Backend — contact.php
//  Contact form handler with MySQL INSERT
//  Course: 23CSE404 | Capstone Project
// ============================================================

header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Honeypot spam check
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true]); // silently discard
    exit;
}

$name    = trim(htmlspecialchars($_POST['name'] ?? ''));
$email   = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));

if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || strlen($message) < 20) {
    echo json_encode(['success' => false, 'message' => 'Please fill all fields correctly.']);
    exit;
}

$conn = getDBConnection();
$stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    // PHP mail() — replace with PHPMailer for production
    $to      = 'hello@nexacart.in';
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8";
    mail($to, "NexaCart Contact: $subject", "From: $name\nEmail: $email\n\n$message", $headers);
    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit message.']);
}

$stmt->close();
$conn->close();
?>

<?php
// ============================================================
//  NexaCart Backend — products.php
//  Product listing API — READ operation from MySQL
//  Course: 23CSE404 | Capstone Project
// ============================================================
/*
header('Content-Type: application/json');
require_once 'config.php';

$conn     = getDBConnection();
$category = $_GET['category'] ?? '';
$search   = '%' . ($_ GET['search'] ?? '') . '%';
$sort     = $_GET['sort'] ?? 'id';

$allowed_sorts = ['id', 'price ASC', 'price DESC', 'name ASC'];
if (!in_array($sort, $allowed_sorts)) $sort = 'id';

if ($category) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND name LIKE ? ORDER BY $sort");
    $stmt->bind_param("ss", $category, $search);
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY $sort");
    $stmt->bind_param("s", $search);
}

$stmt->execute();
$result   = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(['success' => true, 'count' => count($products), 'products' => $products]);
$stmt->close();
$conn->close();
*/
?>
