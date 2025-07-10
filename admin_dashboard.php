<?php
session_start();
include 'inc/koneksi.php';

// Debug untuk cek session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah user login dan levelnya admin
if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <title>Admin Dashboard - Toko Ardo</title>
    <link rel="stylesheet" href="css/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="navbar-admin">
  <div class="navbar-left">
    <img src="img/logo.png" alt="Logo" class="navbar-logo" />
    <span class="navbar-title">Toko Kelontong Ardo</span>
  </div>
  <div class="navbar-right">
  <div class="navbar-user-dropdown">
    <span class="navbar-user" id="userDropdownBtn">
      <i class="fa fa-user"></i>
      Selamat Datang, <b><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></b>
      <i class="fa fa-caret-down" style="margin-left:8px;"></i>
    </span>
    <div class="dropdown-menu" id="userDropdownMenu">
      <a href="logout.php" class="dropdown-item">Logout</a>
    </div>
  </div>
</div>
</div>

<!-- ...navbar dan admin-bar... -->

<!-- Form pencarian tetap pakai GET -->
<form class="admin-search" method="get" action="">
    <input type="text" id="searchInput" placeholder="Cari nama produk..." />
</form>

<?php
$where = "";
if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $cari = $koneksi->real_escape_string($_GET['cari']);
    $where = "WHERE nama LIKE '%$cari%'";
}
$result = $koneksi->query("SELECT * FROM produk $where ORDER BY id DESC");

// Proses best seller
if (isset($_POST['tambah_best_seller']) && !empty($_POST['best_seller_ids'])) {
    $ids = array_map('intval', $_POST['best_seller_ids']);
    $id_list = implode(',', $ids);
    $koneksi->query("UPDATE produk SET best_seller = 1 WHERE id IN ($id_list)");
    echo "<script>alert('Produk best seller berhasil ditambahkan!');window.location='admin_dashboard.php';</script>";
}
if (isset($_POST['simpan_best_seller'])) {
    $koneksi->query("UPDATE produk SET best_seller = 0");
    if (!empty($_POST['best_seller_ids'])) {
        $ids = array_map('intval', $_POST['best_seller_ids']);
        $id_list = implode(',', $ids);
        $koneksi->query("UPDATE produk SET best_seller = 1 WHERE id IN ($id_list)");
    }
    echo "<script>alert('Status best seller berhasil diperbarui!');window.location='admin_dashboard.php';</script>";
}
?>

<form id="form-best-seller" method="post">
  <div class="admin-actions">
      <a href="tambah_produk.php" class="btn btn-biru">Tambah Produk</a>
      <a href="history_transaksi.php" class="btn btn-biru">Lihat Riwayat Transaksi</a>
      <button type="submit" name="simpan_best_seller">Simpan Best Seller</button>
  </div>
  <table border="1">
    <tr>
        <th>ID</th>
        <th>Pilih Best Seller</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
    <tbody>
    <?php while ($produk = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $produk['id'] ?></td>
        <td style="text-align:center;">
           <input type="checkbox" name="best_seller_ids[]" value="<?= $produk['id'] ?>" <?= $produk['best_seller'] ? 'checked' : '' ?>>
        </td>
        <td><?= htmlspecialchars($produk['nama']) ?></td>
        <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
        <td><img src="img/produk/<?= htmlspecialchars($produk['gambar']) ?>" width="80"></td>
        <td>
            <a href="edit_produk.php?id=<?= $produk['id'] ?>">Edit</a>
            <a href="hapus_produk.php?id=<?= $produk['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  <br>
</form>
<?php
?>
<?php
if (isset($_POST['simpan_best_seller'])) {
    // Reset semua best_seller ke 0
    $koneksi->query("UPDATE produk SET best_seller = 0");
    // Jika ada yang dicentang, set best_seller = 1
    if (!empty($_POST['best_seller_ids'])) {
        $ids = array_map('intval', $_POST['best_seller_ids']);
        $id_list = implode(',', $ids);
        $koneksi->query("UPDATE produk SET best_seller = 1 WHERE id IN ($id_list)");
    }
    echo "<script>alert('Status best seller berhasil diperbarui!');window.location='admin_dashboard.php';</script>";
}
?>
<script>
const btn = document.getElementById('userDropdownBtn');
const menu = document.getElementById('userDropdownMenu');
document.addEventListener('click', function(e) {
  if (btn.contains(e.target)) {
    menu.classList.toggle('show');
  } else {
    menu.classList.remove('show');
  }
});
</script>
<script>
</script>
</body>
</html>
