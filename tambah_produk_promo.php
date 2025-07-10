<?php
session_start();
include 'inc/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $promo = isset($_POST['promo']) ? $_POST['promo'] : '';
    $diskon = $_POST['diskon'] ?? 0;
    $best_seller = isset($_POST['best_seller']) ? 1 : 0;

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "img/produk/" . $gambar);

    $sql = "INSERT INTO produk (nama, harga, gambar, promo, diskon, best_seller) VALUES 
    ('$nama', '$harga', '$gambar', '$promo', '$diskon', '$best_seller')";
    $koneksi->query($sql);
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk Promo</title>
    <link rel="stylesheet" href="css/tambah_produk_promo.css">
</head>
<body>
    <h2>Tambah Produk Promo</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Nama Produk:</label><br>
        <input type="text" name="nama" required><br>
        <label>Harga:</label><br>
        <input type="number" name="harga" required><br>
        <label>Diskon (%):</label><br>
        <input type="number" name="diskon" min="0" max="100"><br>
        <label>Label Promo:</label><br>
        <select name="promo">
            <option value="">--Tidak Ada--</option>
            <option value="Diskon">Diskon</option>
        </select><br>
        <label>Best Seller:</label>
        <input type="checkbox" name="best_seller" value="1"> Ya<br>
        <label>Gambar:</label><br>
        <input type="file" name="gambar" required><br><br>
        <button type="submit">Simpan</button>
      <a href="admin_dashboard.php" class="btn-kembali">
  &larr; Kembali ke Admin Dashboard
</a>
    </form>
</body>
</html>