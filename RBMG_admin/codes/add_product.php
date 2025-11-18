<?php
session_start();
include("../config/db.php");

if (isset($_POST['add_product'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $specification = mysqli_real_escape_string($conn, $_POST['specification']);

    // Image Upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');

    if (in_array($image_ext, $allowed_ext)) {
        if ($image_size <= 5000000) { // 5MB
            $new_image_name = time() . '_' . $name . "." . $image_ext;
            $image_path = "../assets/images/products/" . $new_image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $query = "INSERT INTO products (category_id, name, description, price, specification, image) 
                         VALUES ('$category_id', '$name', '$description', '$price', '$specification', '$new_image_name')";

                if (mysqli_query($conn, $query)) {
                    $_SESSION['success'] = "Product added successfully!";
                    header("Location: ../pages/products.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Error: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = "Failed to upload image!";
            }
        } else {
            $_SESSION['error'] = "Image size too large! Max 5MB allowed.";
        }
    } else {
        $_SESSION['error'] = "Invalid image format! Only JPG, JPEG, PNG, GIF, WEBP allowed.";
    }

    header("Location: ../pages/products.php");
    exit();
}
