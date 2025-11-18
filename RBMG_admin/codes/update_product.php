<?php
session_start();
include("../config/db.php");

if(isset($_POST['update_product'])) {
    $prod_id = mysqli_real_escape_string($conn, $_POST['prod_id']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $specification = mysqli_real_escape_string($conn, $_POST['specification']);
    $old_image = mysqli_real_escape_string($conn, $_POST['old_image']);
    
    // Check if new image uploaded
    if($_FILES['image']['name'] != '') {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if(in_array($image_ext, $allowed_ext)) {
            if($image_size <= 5000000) {
                $new_image_name = time() . '_'. $name ."." . $image_ext;
                $image_path = "../assets/images/products/" . $new_image_name;
                
                if(move_uploaded_file($image_tmp, $image_path)) {
                    // Delete old image
                    if(file_exists("../assets/images/products/" . $old_image)) {
                        unlink("../assets/images/products/" . $old_image);
                    }
                    $final_image = $new_image_name;
                } else {
                    $_SESSION['error'] = "Failed to upload new image!";
                    header("Location: ../pages/products.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Image size too large!";
                header("Location: ../pages/products.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image format!";
            header("Location: ../pages/products.php");
            exit();
        }
    } else {
        $final_image = $old_image;
    }
    
    $query = "UPDATE products SET 
              category_id = '$category_id',
              name = '$name',
              description = '$description',
              price = '$price',
              specification = '$specification',
              image = '$final_image',
              updated_at = NOW()
              WHERE id = '$prod_id'";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    
    header("Location: ../pages/products.php");
    exit();
}
?>