<?php
// db_connect.php

$host = 'localhost';
$username = 'root'; 
$password = '';     
$database = 'reys_vegetables_db'; // Pastikan database ini sudah dibuat

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>