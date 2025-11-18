<?php
session_start();
include("../config/db.php");

if (isset($_POST['cat_save'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $description = mysqli_real_escape_string($conn, $_POST['cat_des']);

    // Initialize variables
    $imageName = "";
    $hasError = false;
    $errorMessage = "";

    // Validate required fields
    if (empty($name) || empty($description)) {
        $hasError = true;
        $errorMessage = "Please fill in all required fields.";
    }

    // Handle image upload
    if (!$hasError && isset($_FILES['cat_image']) && $_FILES['cat_image']['error'] == 0) {
        $targetDir = "../assets/images/categories/";

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

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
            } else {
                $hasError = true;
                $errorMessage = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif (!$hasError && (!isset($_FILES['cat_image']) || $_FILES['cat_image']['error'] == 4)) {
        // No image uploaded
        $hasError = true;
        $errorMessage = "Please select a category image.";
    }

    // Insert into database if no errors
    if (!$hasError) {
        $query = "INSERT INTO categories (cat_name, description, image, created_at) 
                  VALUES ('$name', '$description', '$imageName', NOW())";
        $run = mysqli_query($conn, $query);

        if ($run) {
            $_SESSION['success'] = "Category added successfully!";
        } else {
            $_SESSION['error'] = "Error adding category: " . mysqli_error($conn);
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
    header("Location: ../admin/categories.php");
    exit();
}
