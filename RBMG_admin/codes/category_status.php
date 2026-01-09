<?php
session_start();
include('../config/db.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Update status
    $update = "UPDATE categories SET status = '$status' WHERE id = '$id'";
    
    if (mysqli_query($conn, $update)) {
        $action = $status == 1 ? "enabled" : "disabled";
        echo json_encode([
            "status" => "success", 
            "message" => "Category {$action} successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to update category status!"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method!"
    ]);
}
?>