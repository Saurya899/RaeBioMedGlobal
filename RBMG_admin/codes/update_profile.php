<?php
include('../includes/session.php');
include("../config/db.php");  // database connection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $imageName = "";

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "../assets/images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Update with image
        $query = "UPDATE admin SET name='$name',  mobile='$mobile', image='$imageName' WHERE email='$admin_email'";
    } else {
        // Update without image
        $query = "UPDATE admin SET name='$name',  mobile='$mobile' WHERE email='$admin_email'";
    }

    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
