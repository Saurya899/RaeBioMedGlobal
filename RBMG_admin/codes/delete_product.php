<?php
session_start();
include("../config/db.php");

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Get image name
    $get_image = mysqli_query($conn, "SELECT image FROM products WHERE id = '$id'");
    $image_data = mysqli_fetch_assoc($get_image);
    
    if($image_data) {
        // Delete image file
        $image_path = "../assets/images/products/" . $image_data['image'];
        if(file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Delete from database
        $query = "DELETE FROM products WHERE id = '$id'";
        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Product deleted successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Product not found!";
    }
} else {
    $_SESSION['error'] = "Invalid request!";
}

header("Location: ../pages/products.php");
exit();
?>