<?php
session_start();
include("../config/db.php");

if (isset($_POST['cat_update'])) {
    $cat_id = mysqli_real_escape_string($conn, $_POST['cat_id']);
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $description = mysqli_real_escape_string($conn, $_POST['cat_des']);
    $old_image = mysqli_real_escape_string($conn, $_POST['old_image']);

    // Initialize variables
    $imageName = $old_image;
    $hasError = false;
    $errorMessage = "";

    // Validate required fields
    if (empty($name) || empty($description)) {
        $hasError = true;
        $errorMessage = "Please fill in all required fields.";
    }

    // Handle image upload if new image is provided
    if (!$hasError && isset($_FILES['cat_image']) && $_FILES['cat_image']['error'] == 0) {
        $targetDir = "../assets/images/categories/";

        $file_tmpname = $_FILES['cat_image']['tmp_name'];
        $file_name = $_FILES['cat_image']['name'];
        $file_size = $_FILES['cat_image']['size'];
        $file_type = $_FILES['cat_image']['type'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Check file extension
        if (!in_array($file_ext, $allowed_ext)) {
            $hasError = true;
            $errorMessage = "Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP images are allowed.";
        }
        
        // Check file size (5MB max)
        if ($file_size > 5000000) {
            $hasError = true;
            $errorMessage = "File size too large. Maximum 5MB allowed.";
        }
        
        // Check if file is actually an image
        $check_image = getimagesize($file_tmpname);
        if ($check_image === false) {
            $hasError = true;
            $errorMessage = "File is not a valid image.";
        }

        if (!$hasError) {
            // Generate unique filename
            $fileName = time() . "_" . $name . "." . $file_ext;
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($file_tmpname, $targetFilePath)) {
                $imageName = $fileName;
                
                // Delete old image if it exists and is not the default
                if (!empty($old_image) && file_exists($targetDir . $old_image)) {
                    unlink($targetDir . $old_image);
                }
            } else {
                $hasError = true;
                $errorMessage = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update database if no errors
    if (!$hasError) {
        $query = "UPDATE categories SET 
                  cat_name = '$name', 
                  description = '$description', 
                  image = '$imageName',
                  updated_at = NOW()
                  WHERE id = '$cat_id'";
        
        $run = mysqli_query($conn, $query);

        if ($run) {
            $_SESSION['success'] = "Category updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating category: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = $errorMessage;
    }

    // Redirect back to categories page
    header("Location: ../pages/categories.php");
    exit();

} else {
    // If form wasn't submitted properly, redirect with error
    $_SESSION['error'] = "Invalid request!";
    header("Location: ../pages/categories.php");
    exit();
}
?>