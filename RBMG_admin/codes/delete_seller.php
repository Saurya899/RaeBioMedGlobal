<?php
include('../config/db.php');
 // Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $seller_id = intval($_GET['id']);
    
    if ($_GET['action'] == 'delete') {
        // Get seller image before deleting
        $image_query = "SELECT image FROM sellers WHERE id = $seller_id";
        $image_result = mysqli_query($conn, $image_query);
        $seller_data = mysqli_fetch_assoc($image_result);
        
        // Delete seller
        $delete_query = "DELETE FROM sellers WHERE id = $seller_id";
        if (mysqli_query($conn, $delete_query)) {
            // Delete image file if exists
            if ($seller_data['image'] && file_exists("../assets/images/sellers/" . $seller_data['image'])) {
                unlink("../assets/images/sellers/" . $seller_data['image']);
            }
            $_SESSION['success'] = "Seller deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting seller: " . mysqli_error($conn);
        }
        header("Location: ../pages/sellers.php");
        exit();
    }
}
?>