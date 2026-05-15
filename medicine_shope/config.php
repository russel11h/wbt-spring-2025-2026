<?php
// This file creates the procedural mysqli database connection.
// Change database name, username, password or port here when your local XAMPP setup is different.
$conn = mysqli_connect('localhost', 'root', '', 'medicine_shop_db');
if (!$conn) { die('Database connection failed: ' . mysqli_connect_error()); }
mysqli_set_charset($conn, 'utf8mb4');
?>
