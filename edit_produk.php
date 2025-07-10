<?php
include 'inc/koneksi.php';
session_start();
if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit;
    
}

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id = $id";
$result = $koneksi->query($query);
$produk = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    // Cek apakah ada file baru yang diupload
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "img/produk/$gambar");
        $query = "UPDATE produk SET nama='$nama', harga='$harga', gambar='$gambar' WHERE id=$id";
    } else {
        $query = "UPDATE produk SET nama='$nama', harga='$harga' WHERE id=$id";
    }

    if ($koneksi->query($query)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Gagal update: " . $koneksi->error;
    }
}
?>

<h2>Edit Produk</h2>
<link rel="stylesheet" href="css/edit_produk.css">
<form method="post" enctype="multipart/form-data">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama" value="<?= $produk['nama'] ?>"><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" value="<?= $produk['harga'] ?>"><br><br>

    <label>Gambar Produk (Kosongkan jika tidak diubah):</label><br>
    <input type="file" name="gambar"><br><br>
    <img src="img/produk/<?= $produk['gambar'] ?>" width="100"><br><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="admin_dashboard.php" class="btn-kembali">
  &larr; Kembali ke Admin Dashboard
</a>
</form>
