<?php
include 'inc/koneksi.php';

if (isset($_GET['id_transaksi'])) {
    $id = intval($_GET['id_transaksi']); // amankan input
    $sql = "SELECT transaksi.*, produk.nama, produk.harga 
        FROM transaksi 
        JOIN produk ON transaksi.produk_id = produk.id 
        WHERE transaksi.transaksi_id = $id";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Transaksi tidak ditemukan.");
    }
} else {
    die("ID transaksi tidak diberikan.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <style>
        body { font-family: Arial, sans-serif; width: 300px; margin: 0 auto; }
        h2, p { text-align: center; }
        .struk { border: 1px dashed #000; padding: 15px; margin-top: 20px; }
        .cetak { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="struk">
        <h2>Toko Ardo</h2>
        <hr>
        <p>No. Transaksi: <?= htmlspecialchars($data['transaksi_id']) ?></p>
        <p>Tanggal: <?= htmlspecialchars($data['tanggal']) ?></p>
        <p>Produk: <?= htmlspecialchars($data['nama']) ?></p>
        <p>Harga Satuan: Rp <?= number_format($data['harga'], 0, ',', '.') ?></p>
        <p>Jumlah: <?= htmlspecialchars($data['jumlah']) ?></p>
        <p><strong>Total Bayar: Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></strong></p>
        <hr>
        <p>Terima kasih telah berbelanja!</p>
    </div>
    <div class="cetak">
        <button onclick="window.print()">Cetak Struk</button>
    </div>
</body>
</html>
