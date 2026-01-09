<?php
session_start();
include('../config/db.php'); 
header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$admin_id = $_SESSION['admin_id'];
$current = $_POST['current'] ?? '';
$new = $_POST['new'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Check if current email matches
$sql = "SELECT email FROM admin WHERE id = '$admin_id'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

if ($row['email'] !== $current) {
    echo json_encode(["status" => "error", "message" => "Current email is incorrect"]);
    exit;
}

// Check if new & confirm match
if ($new !== $confirm) {
    echo json_encode(["status" => "error", "message" => "New and Confirm emails do not match"]);
    exit;
}

// Update email
$update = "UPDATE admin SET email = '$new' WHERE id = '$admin_id'";
if (mysqli_query($conn, $update)) {
    echo json_encode([
        "status" => "success",
        "message" => "Email updated successfully! Redirecting to Login...",
        "redirect" => "../index.php"
    ]);


    session_unset();
    session_destroy();
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Database error! Try again."]);
}
?>
