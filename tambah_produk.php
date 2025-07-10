<?php
session_start();
include 'inc/koneksi.php';
// Ambil data kategori dari tabel kategori
$kategori_result = $koneksi->query("SELECT id_kategori, nama_kategori FROM kategori");
// Proses form hanya jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Pastikan folder img/produk/ ada
    $target_dir = __DIR__ . "/img/produk/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Upload file dan simpan ke DB
    $target_path = $target_dir . $gambar;

echo "<pre>";
echo "TMP File: $tmp\n";
echo "Target Path: $target_path\n";
echo "Is tmp readable? " . (is_uploaded_file($tmp) ? "Yes" : "No") . "\n";
echo "Target folder exists? " . (is_dir($target_dir) ? "Yes" : "No") . "\n";
echo "</pre>";

if (move_uploaded_file($tmp, $target_path)) {
    $id_kategori = $_POST['id_kategori'];
    $koneksi->query("INSERT INTO produk (nama, harga, gambar, id_kategori) VALUES ('$nama', '$harga', '$gambar', '$id_kategori')");
    echo "<script>
        alert('Selamat, produk telah ditambahkan');
        window.location.href = 'admin_dashboard.php';
    </script>";
    exit;
} else {
    echo "Upload gambar gagal!";
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk - Toko Ardo</title>
    <link rel="stylesheet" href="css/tambah_produk.css">
</head>

<body>
    <h1>Tambah Produk</h1>
    
    <form method="POST" enctype="multipart/form-data">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" required><br><br>

    <label>Kategori:</label><br>
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($kat = $kategori_result->fetch_assoc()): ?>
            <option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Gambar Produk:</label><br>
    <input type="file" name="gambar" accept="image/*" required><br><br>

    <button type="submit">Simpan</button>
    <a href="admin_dashboard.php">Kembali</a>
</form>
</body>
</html>
