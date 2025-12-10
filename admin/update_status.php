<?php
// update_status.php
include 'admin_protect.php';  // Keep admins only
include '../connect.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Get and validate input
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$status  = isset($_POST['status']) ? strtolower(trim($_POST['status'])) : '';

if ($user_id <= 0 || !in_array($status, ['active', 'inactive'])) {
    http_response_code(400);
    exit('Invalid data');
}

// Prevent changing your own account to inactive (optional safety)
session_start();
if ($user_id == $_SESSION['user_id'] && $status === 'inactive') {
    http_response_code(403);
    exit('You cannot deactivate yourself');
}

// Update the database
$stmt = $conn->prepare("UPDATE `user` SET status = ? WHERE user_id = ?");
$stmt->bind_param("si", $status, $user_id);

if ($stmt->execute()) {
    // Success – return clean response for AJAX
    http_response_code(200);
    echo 'success';
} else {
    http_response_code(500);
    echo 'Database error';
}

$stmt->close();
$conn->close();
?>