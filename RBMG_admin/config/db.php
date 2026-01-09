
<?php
 $hostname="localhost:3307";
 $username="root";
 $password="";
 $db_name="rbmg_admin";
 $conn = mysqli_connect($hostname,$username,$password,$db_name);
  if(!$conn)
  {
     die(" Database Connection Failed: " . mysqli_connect_error());
  }

?>