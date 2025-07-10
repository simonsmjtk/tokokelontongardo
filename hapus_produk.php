<?php
session_start();
include 'inc/koneksi.php';

// Cek login admin
if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil nama gambar
    $result = $koneksi->query("SELECT gambar FROM produk WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $produk = $result->fetch_assoc();
        $file_gambar = __DIR__ . "/img/produk/" . $produk['gambar'];

        // Hapus file gambar jika ada
        if (file_exists($file_gambar)) {
            unlink($file_gambar);
        }

        // Hapus data produk
        $koneksi->query("DELETE FROM produk WHERE id = $id");
    }
}

header("Location: admin_dashboard.php");
exit;
