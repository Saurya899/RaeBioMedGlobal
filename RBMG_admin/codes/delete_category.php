<?php
session_start();
include("../config/db.php");

if (isset($_GET['id'])) {
    $cat_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // First, get the image name to delete the file
    $query = "SELECT image FROM categories WHERE id = '$cat_id'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $category = mysqli_fetch_assoc($result);
        $image_name = $category['image'];
        
        // Delete the category from database
        $delete_query = "DELETE FROM categories WHERE id = '$cat_id'";
        $delete_result = mysqli_query($conn, $delete_query);
        
        if ($delete_result) {
            // Delete the image file if it exists
            if (!empty($image_name)) {
                $image_path = "../assets/images/categories/" . $image_name;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $_SESSION['success'] = "Category deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting category: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Category not found!";
    }
} else {
    $_SESSION['error'] = "Invalid request!";
}

// Redirect back to categories page
header("Location: ../pages/categories.php");
exit();
?>