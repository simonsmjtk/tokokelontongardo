<?php
$host = 'localhost'; // atau '127.0.0.1'
$username = 'root'; // Username default XAMPP
$pass = ''; // Password default XAMPP (kosong jika belum diatur)
$db = 'toko_ardo'; // Nama database Anda

$koneksi= new mysqli($host, $username, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
