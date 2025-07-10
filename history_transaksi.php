<?php
session_start();
include 'inc/koneksi.php';
date_default_timezone_set('Asia/Jakarta');
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

// Ambil username dari tabel akun
$username = '';
$userResult = $koneksi->query("SELECT username FROM akun WHERE id_user='$id_user' LIMIT 1");
if ($userRow = $userResult->fetch_assoc()) {
    $username = $userRow['username'];
}

$sql = "SELECT * FROM history_transaksi ORDER BY tanggal DESC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Transaksi</title>
    <link rel="stylesheet" href="css/checkout.css">
    <style>
        .btn-hapus {color:#fff;background:#e53935;padding:6px 14px;border-radius:6px;text-decoration:none;}
        .btn-hapus:hover {background:#b71c1c;}
        .aksi {text-align:center;}
    </style>
</head>
<body>
<h2>History Transaksi</h2>
<a href="admin_dashboard.php" style="display:inline-block;margin-bottom:16px;padding:8px 18px;background:#1976d2;color:#fff;text-decoration:none;border-radius:6px;">Kembali ke Dashboard</a>
<table border="1" cellpadding="8" style="width:98%;margin:auto;">
    <tr>
    <th>ID Transaksi</th>
    <th>Produk</th>
    <th>Jumlah</th>
    <th>Total Harga</th>
    <th>Tanggal</th>
    <th>Username</th> <!-- Ganti dari nama_pembeli -->
    <th>Metode Pembayaran</th>
    <th>Aksi</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id_transaksi'] ?></td>
    <td><?= htmlspecialchars($row['produk']) ?></td>
    <td><?= $row['jumlah'] ?></td>
    <td>Rp <?= number_format($row['total_harga'],0,',','.') ?></td>
    <td><?=date('Y-m-d H:i:s'); ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td> <!-- Ambil dari kolom username -->
    <td><?= htmlspecialchars($row['metode']) ?></td>
    <td class="aksi">
       <a href="hapus_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn-hapus" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>