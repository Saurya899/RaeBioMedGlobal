<?php
session_start();
include("../config/db.php");

if (isset($_POST['update_seller'])) {
    $seller_id = intval($_POST['seller_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $about_us = mysqli_real_escape_string($conn, $_POST['about_us']);
    $about_product = mysqli_real_escape_string($conn, $_POST['about_product']);
    
    // Handle image upload
    $image = $_POST['old_image'];
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];
        
        // Check if image is valid
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $image_type = mime_content_type($image_tmp);
        
        if (in_array($image_type, $allowed_types)) {
            if ($image_error === 0) {
                if ($image_size <= 5000000) { // 5MB max
                    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
                    $image = uniqid() . '.' . $image_extension;
                    $upload_path = "../assets/images/sellers/" . $image;
                    
                    if (move_uploaded_file($image_tmp, $upload_path)) {
                        // Delete old image if it exists and is not the default
                        if ($_POST['old_image'] && $_POST['old_image'] != 'default.jpg' && file_exists("../assets/images/sellers/" . $_POST['old_image'])) {
                            unlink("../assets/images/sellers/" . $_POST['old_image']);
                        }
                    } else {
                        $_SESSION['error'] = "Failed to upload image";
                        header("Location: ../admin/sellers.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Image size too large. Maximum 5MB allowed";
                    header("Location: ../admin/sellers.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Error uploading image";
                header("Location: ../admin/sellers.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image format. Allowed: JPG, JPEG, PNG, GIF, WEBP";
            header("Location: ../admin/sellers.php");
            exit();
        }
    }
    
    // Update seller in database
    $query = "UPDATE sellers SET 
              first_name = '$first_name',
              last_name = '$last_name',
              email = '$email',
              phone = '$phone',
              address = '$address',
              city = '$city',
              state = '$state',
              pincode = '$pincode',
              about_us = '$about_us',
              about_product = '$about_product',
              image = '$image',
              updated_at = NOW()
              WHERE id = $seller_id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Seller updated successfully";
    } else {
        $_SESSION['error'] = "Error updating seller: " . mysqli_error($conn);
    }
    
    header("Location: ../pages/sellers.php");
    exit();
}
?>