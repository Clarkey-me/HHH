<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['cart_message'] = "Please log in first.";
    header("Location: cart.php");
    exit();
}

$cart_id = (int)($_POST['cart_id'] ?? 0);
$field   = $_POST['field'] ?? '';
$value   = trim($_POST['value'] ?? '');

if (!$cart_id || !in_array($field, ['size', 'color']) || $value === '') {
    $_SESSION['cart_message'] = "Invalid request.";
    header("Location: cart.php");
    exit();
}

// First, make sure cart table has the columns
$conn->query("ALTER TABLE cart ADD COLUMN IF NOT EXISTS size VARCHAR(10) NULL");
$conn->query("ALTER TABLE cart ADD COLUMN IF NOT EXISTS color VARCHAR(50) NULL");

$stmt = $conn->prepare("UPDATE cart SET $field = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("sii", $value, $cart_id, $_SESSION['user']['user_id']);
$stmt->execute();
$stmt->close();

$_SESSION['cart_message'] = "Variant updated!";
header("Location: cart.php");
exit();