<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'inc/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Belanja | Toko Ardo</title>
    <link rel="stylesheet" href="css/pembayaran.css">
</head>
<body>
<?php
if (empty($_SESSION['keranjang'])) {
    echo "<p>Keranjang kosong.</p>";
    exit;
}

$metode = isset($_POST['metode']) ? $_POST['metode'] : '';
if (!$metode) {
    echo "<p>Metode pembayaran belum dipilih.</p>";
    exit;
}

$ids = array_keys($_SESSION['keranjang']);
$id_list = implode(',', $ids);
$result = $koneksi->query("SELECT * FROM produk WHERE id IN ($id_list)");

echo "<h2>Struk Belanja</h2>";
echo "<div id='struk-area' style='max-width:500px;margin:0 auto;background:#fffbe7;border-radius:12px;box-shadow:0 2px 12px #eee;padding:32px 28px 24px 28px;position:relative;overflow:hidden;'>";
echo "<div style='text-align:center;margin-bottom:18px;'>
        <img src='img/logo.png' alt='Logo Toko Ardo' style='height:60px;margin-bottom:8px;'><br>
        <span style='font-size:1.6em;font-weight:bold;color:#3498db;letter-spacing:1px;'>Toko kelontong  Ardo</span><br>
        <span style='font-size:1em;color:#888;'>Jl. Serma Muda Parsuratan</span><br>
        <span style='font-size:0.95em;color:#aaa;'>Tanggal: <b>".date('d-m-Y H:i')."</b></span>
      </div>";
echo "<hr style='border:0;border-top:1px dashed #bbb;margin:18px 0;'>";
echo "<table style='width:100%;font-size:1em;margin-bottom:12px;'>";
echo "<tr style='background:#f7f7f7;font-weight:bold;'>
        <th style='padding:6px 0;'>Nama Produk</th>
        <th style='padding:6px 0;'>Jumlah</th>
        <th style='padding:6px 0;'>Harga</th>
        <th style='padding:6px 0;'>Total</th>
      </tr>";

$total_semua = 0;
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $nama = htmlspecialchars($row['nama']);
    $qty = $_SESSION['keranjang'][$id];
    $harga = $row['harga'];
    $total = $qty * $harga;
    $total_semua += $total;
    echo "<tr style='text-align:center;'>
            <td style='padding:4px 0;'>$nama</td>
            <td>$qty</td>
            <td>Rp " . number_format($harga, 0, ',', '.') . "</td>
            <td>Rp " . number_format($total, 0, ',', '.') . "</td>
          </tr>";
}
echo "<tr style='font-weight:bold;background:#f7f7f7;'>
        <td colspan='3' align='right' style='padding:6px 0;'>Total Bayar</td>
        <td style='padding:6px 0;'>Rp " . number_format($total_semua, 0, ',', '.') . "</td>
      </tr>";
echo "</table>";
echo "<div style='margin:10px 0 18px 0;font-size:1.1em;'><b>Metode Pembayaran:</b> $metode</div>";

$rekening = [
     'BCA'     => ['Bank BCA', '1234567890', 'Imelda Rinwati Manurung'],
    'Mandiri' => ['Bank Mandiri', '1070018287138', 'Imelda Rinwati Manurung'],
    'BRI'     => ['Bank BRI', '479401003520531', 'Imelda Rinwati Manurung'],
];

$ewallet = [
    'OVO'        => ['OVO', '082275842290', 'Toko Ardo'],
    'DANA'       => ['DANA', '082275842290', 'Toko Ardo'],
    'GoPay'      => ['GoPay', '082275842290', 'Toko Ardo'],
    'ShopeePay'  => ['ShopeePay', '082275842290', 'Toko Ardo'],
];
$metode_valid = array_merge(array_keys($rekening), array_keys($ewallet), ['COD']);
if (!in_array($metode, $metode_valid)) {
    echo "<p>Metode pembayaran tidak valid.</p>";
    exit;
}
if (isset($rekening[$metode])) {
    $data = $rekening[$metode];
    echo "<div class='info-box' style='background:#e3f6ff;padding:10px 16px;border-radius:8px;margin-bottom:10px;'>
            <b>Transfer ke:</b> {$data[0]}<br>
            <span style='font-size:1.1em;'><b>{$data[1]}</b> a.n. <b>{$data[2]}</b></span>
          </div>";
} elseif (isset($ewallet[$metode])) {
    $data = $ewallet[$metode];
    echo "<div class='info-box' style='background:#e3f6ff;padding:10px 16px;border-radius:8px;margin-bottom:10px;'>
            <b>Transfer ke {$data[0]}:</b><br>
            <span style='font-size:1.1em;'><b>{$data[1]}</b> a.n. <b>{$data[2]}</b></span>
          </div>";
} elseif (strtolower($metode) == 'cod') {
    echo "<div class='info-box' style='background:#e3f6ff;padding:10px 16px;border-radius:8px;margin-bottom:10px;'>
            <b>Pembayaran di tempat (COD).</b>
          </div>";
}
echo "<div style='margin:18px 0 0 0;text-align:center;font-size:1.1em;color:#27ae60;font-weight:bold;'>Pembayaran berhasil!<br>Terima kasih telah berbelanja di <span style='color:#3498db;'>Toko Ardo</span>!</div>";
echo "<hr style='border:0;border-top:1px dashed #bbb;margin:18px 0 10px 0;'>";
echo "<div style='text-align:center;font-size:0.95em;color:#888;'>Struk ini sah tanpa tanda tangan.<br>Jika ada kendala, hubungi admin Toko Ardo.</div>";
echo "</div>";
// Tombol cetak struk
echo "<div style='text-align:center;margin:24px;'>
        <button onclick=\"printStruk()\">Cetak Struk</button>
        <a href='index.php' class='btn-beranda'>Kembali ke Beranda</a>
      </div>";
?>
<script>
function printStruk() {
    var strukContent = document.getElementById('struk-area').innerHTML;
    var win = window.open('', '', 'width=700,height=600');
    win.document.write('<html><head><title>Cetak Struk</title>');
    win.document.write('<link rel="stylesheet" href="css/struk.css">');
    win.document.write('</head><body>');
    win.document.write(strukContent);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>
<?php
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;

// Ambil username dari tabel akun sesuai id_user
$userResult = $koneksi->query("SELECT username FROM akun WHERE id_user='$id_user' LIMIT 1");
$username = '';
if ($userRow = $userResult->fetch_assoc()) {
    $username = $userRow['username'];
}
$tanggal = date('Y-m-d H:i:s');

foreach ($_SESSION['keranjang'] as $id_produk => $qty) {
    $produk = $koneksi->query("SELECT nama, harga FROM produk WHERE id='$id_produk'")->fetch_assoc();
    $nama_produk = $produk['nama'];
    $harga = $produk['harga'];
    $total = $harga * $qty;

    // Insert lengkap dengan username yang benar
    $koneksi->query("INSERT INTO history_transaksi (id_user, produk, jumlah, total_harga, tanggal, username, metode)
        VALUES ('$id_user', '$nama_produk', '$qty', '$total', '$tanggal', '$username', '$metode')");
}
unset($_SESSION['keranjang']);
?>
</body>
</html>