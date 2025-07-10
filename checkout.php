<?php
session_start();
include 'inc/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Toko Ardo</title>
    <link rel="stylesheet" href="css/checkout.css">
    <style>
    .popup-checkout {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
    }
    .popup-content {
        background: #fff;
        padding: 32px 24px 18px 24px;
        border-radius: 14px;
        min-width: 340px;
        max-width: 95vw;
        position: relative;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .close-popup {
        position: absolute;
        right: 18px; top: 12px;
        font-size: 24px;
        cursor: pointer;
        color: #a259e9;
    }
    .btn-checkout {
        background: linear-gradient(90deg, #a259e9 0%, #7c3aed 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 28px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin: 24px auto 0 auto;
        display: block;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    th, td {
        padding: 8px 6px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    th { background: #f8f8ff; }
    </style>
</head>
<body>
<?php
if (empty($_SESSION['keranjang'])) {
    echo "<p>Keranjang kosong.</p>";
    exit;
}

$ids = array_keys($_SESSION['keranjang']);
$id_list = implode(',', $ids);
$result = $koneksi->query("SELECT * FROM produk WHERE id IN ($id_list)");

ob_start(); // Start output buffering for popup content
echo "<h2>Detail Pembelian</h2>";
echo "<table>";
echo "<tr>
        <th>Gambar</th>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga Satuan</th>
        <th>Total Harga</th>
      </tr>";

$total_semua = 0;
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $nama = htmlspecialchars($row['nama']);
    $qty = $_SESSION['keranjang'][$id];
    $harga = $row['harga'];
    $total = $qty * $harga;
    $total_semua += $total;
    $gambar = !empty($row['gambar']) && file_exists("img/{$row['gambar']}") ? $row['gambar'] : 'default.png';
    echo "<tr>
            <td><img src='img/{$gambar}' width='70' style='border-radius:8px;box-shadow:0 1px 6px #eaeaea;'></td>
            <td>$nama</td>
            <td>$qty</td>
            <td>Rp " . number_format($harga, 0, ',', '.') . "</td>
            <td>Rp " . number_format($total, 0, ',', '.') . "</td>
          </tr>";
}
echo "<tr>
        <td colspan='4' align='right'><b>Total Bayar</b></td>
        <td><b>Rp " . number_format($total_semua, 0, ',', '.') . "</b></td>
      </tr>";
echo "</table>";
$detail_pembelian = ob_get_clean();
?>

<!-- Tombol untuk membuka popup -->
<button id="btnShowCheckout" class="btn-checkout">Checkout</button>

<!-- Popup Checkout -->
<div id="popupCheckout" class="popup-checkout">
  <div class="popup-content">
    <span class="close-popup" id="closePopup">&times;</span>
    <?= $detail_pembelian ?>
    <form method="post" action="pembayaran.php" style="max-width:400px;margin:24px auto;">
        <label for="metode"><b>Pilih Metode Pembayaran :</b></label><br>
        <select name="metode" id="metode" required>
            <option value="">-- Pilih --</option>
            <optgroup label="E-Wallet">
                <option value="OVO">OVO</option>
                <option value="DANA">DANA</option>
                <option value="GoPay">GoPay</option>
                <option value="ShopeePay">ShopeePay</option>
            </optgroup>
            <optgroup label="Bank Transfer">
             <option value="BCA">Bank BCA</option>
              <option value="Mandiri">Bank Mandiri</option>
             <option value="BRI">Bank BRI</option>
            </optgroup>
            <option value="COD">Bayar di Tempat (COD)</option>
        </select><br>
        <button type="submit" class="btn-checkout">Konfirmasi Pesanan</button>
    </form>
  </div>
</div>

<script>
document.getElementById('btnShowCheckout').onclick = function() {
  document.getElementById('popupCheckout').style.display = 'flex';
};
document.getElementById('closePopup').onclick = function() {
  document.getElementById('popupCheckout').style.display = 'none';
};
// Tutup popup jika klik di luar konten
window.onclick = function(event) {
  var popup = document.getElementById('popupCheckout');
  if (event.target == popup) {
    popup.style.display = "none";
  }
}
</script>
</body>
</html>