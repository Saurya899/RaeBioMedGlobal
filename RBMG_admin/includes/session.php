<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    // Redirect to login page if not logged in
    header("Location:../index.php");
    exit;
}
$admin_email = $_SESSION['admin_email'];
