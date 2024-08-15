<?php

$servername = "your server name";
$username = "your username";
$password = "your password";
$dbname = "restaurantdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>