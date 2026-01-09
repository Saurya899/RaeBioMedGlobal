<?php
include("../config/db.php");
include("../includes/session.php");

if (isset($_GET['id'])) {
    $carousel_id = intval($_GET['id']);
    $query = "SELECT * FROM carousel WHERE id = $carousel_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $carousel = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'carousel' => $carousel]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Carousel item not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
}
?>