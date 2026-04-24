<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "toko_buku";

$conn = mysqli_connect($host, $user, $password, $database);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>