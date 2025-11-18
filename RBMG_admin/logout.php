<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
  die("Logout Failed!");
}
session_unset();
session_destroy();
header("location:index.php");
