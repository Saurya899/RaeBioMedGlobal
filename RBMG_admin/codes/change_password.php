<?php
session_start();
include('../config/db.php'); // Database connection file include karo

header('Content-Type: application/json');

// Check if user logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$admin_id = $_SESSION['admin_id'];
$current = $_POST['current'] ?? '';
$new = $_POST['new'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Check if current password match karta hai
$sql = "SELECT password FROM admin WHERE id = '$admin_id'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

if ($row['password'] !== $current) {
    echo json_encode(["status" => "error", "message" => "Old password is incorrect"]);
    exit;
}

// Confirm password check
if ($new !== $confirm) {
    echo json_encode(["status" => "error", "message" => "New and Confirm passwords do not match"]);
    exit;
}

$update = "UPDATE admin SET password = '$new' WHERE id = '$admin_id'";
if (mysqli_query($conn, $update)) {
     // Redirect to index page (login)
    echo json_encode([
        "status" => "success",
        "message" => "Password updated successfully! Redirecting to login...",
        "redirect" => "../index.php"
    ]);
    
  // Password update success hone ke baad session destroy
   
    session_unset();
    session_destroy();
     exit;
} else {
    echo json_encode(["status" => "error", "message" => "Database error! Try again."]);
}
?>
