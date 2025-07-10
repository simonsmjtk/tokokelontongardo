<?php
session_start();
include 'inc/koneksi.php';

// Proses tambah ke keranjang via GET
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + 1;
  header("Location: keranjang.php");
  exit;
}
// Proses tambah/kurang produk via POST
if (isset($_POST['plus'])) {
  $id = $_POST['plus'];
  $_SESSION['keranjang'][$id]++;
  header("Location: keranjang.php");
  exit;
}
if (isset($_POST['min'])) {
  $id = $_POST['min'];
  $_SESSION['keranjang'][$id]--;
  if ($_SESSION['keranjang'][$id] <= 0) {
    unset($_SESSION['keranjang'][$id]);
  }
  header("Location: keranjang.php");
  exit;
}
// Update jumlah produk
if (isset($_POST['update_jumlah'])) {
  foreach ($_POST['jumlah'] as $id => $jumlah) {
    if ($jumlah <= 0) {
      unset($_SESSION['keranjang'][$id]);
    } else {
      $_SESSION['keranjang'][$id] = $jumlah;
    }
  }
  header("Location: keranjang.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="css/keranjang.css">
</head>
<body>
<?php

echo "<h1>Keranjang Belanja</h1>";
echo "<a href='produk.php' style='display:inline-block;margin-bottom:18px;padding:8px 18px;background:#eee;color:#333;border-radius:8px;text-decoration:none;font-weight:bold;'>← Kembali ke Produk</a>";

if (empty($_SESSION['keranjang'])) {
  echo "<div class='keranjang-kosong'>Keranjang kosong.</div>";
} else {
  echo "<form method='post'>";
  $grand_total = 0;
  foreach ($_SESSION['keranjang'] as $id => $jumlah) {
  $result = $koneksi->query("SELECT * FROM produk WHERE id=$id");
  $row = $result->fetch_assoc();
  $total_harga_produk = $row['harga'] * $jumlah;
  $grand_total += $total_harga_produk;
  echo "<p style='display:flex;align-items:center;gap:10px;'>
    <span style='min-width:120px;display:inline-block'>{$row['nama']}</span>
    <button type='submit' name='min' value='$id' class='btn-qty'>➖</button>
    <input type='number' name='jumlah[$id]' value='$jumlah' min='1' style='width:50px;text-align:center;' readonly>
    <button type='submit' name='plus' value='$id' class='btn-qty'>➕</button>
    <span style='margin-left:10px;'>x Rp " . number_format($row['harga']) . "</span>
    <span style='margin-left:10px;'>= <b>Rp " . number_format($total_harga_produk) . "</b></span>
  </p>";
}
  echo "<hr style='max-width:400px;'>";
  echo "<p style='font-size:18px;'><b>Total Belanja: Rp " . number_format($grand_total) . "</b></p>";
  echo "</form>";
  // Tombol checkout pop up
  echo "<button id='btnShowCheckout' class='btn-checkout' style='margin-top:18px;'>Checkout</button>";

  // Popup checkout (hanya jika keranjang tidak kosong)
  $ids = array_keys($_SESSION['keranjang']);
  $id_list = implode(',', $ids);
  $result = $koneksi->query("SELECT * FROM produk WHERE id IN ($id_list)");

  ob_start();
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
  echo "<div id='popupCheckout' class='popup-checkout' style='display:none;'>
      <div class='popup-content'>
        <span class='close-popup' id='closePopup' style='cursor:pointer;font-size:22px;float:right;'>&times;</span>
        $detail_pembelian
        <form method='post' action='pembayaran.php' style='max-width:400px;margin:24px auto;'>
            <label for='metode'><b>Pilih Metode Pembayaran :</b></label><br>
            <select name='metode' id='metode' required>
                <option value=''>-- Pilih --</option>
                <optgroup label='E-Wallet'>
                    <option value='OVO'>OVO</option>
                    <option value='DANA'>DANA</option>
                    <option value='GoPay'>GoPay</option>
                    <option value='ShopeePay'>ShopeePay</option>
                </optgroup>
                <optgroup label='Bank Transfer'>
                  <option value='BRI'>Bank BRI</option>
                    <option value='BCA'>Bank BCA</option>
                    <option value='Mandiri'>Bank Mandiri</option>
                </optgroup>
                <option value='COD'>Bayar di Tempat (COD)</option>
            </select><br>
            <button type='submit' class='btn-checkout'>Konfirmasi Pesanan</button>
        </form>
      </div>
    </div>";
  // Tambahkan style popup agar tampil seperti modal
  echo "<style>
  .popup-checkout {
    display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.25); align-items: center; justify-content: center;
  }
  .popup-checkout .popup-content {
    background: #fffbe7; border-radius: 14px; box-shadow: 0 2px 16px #bbb; padding: 32px 28px 24px 28px; min-width: 320px; max-width: 95vw; position: relative;
  }
  .popup-checkout table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
  .popup-checkout th, .popup-checkout td { padding: 7px 4px; border-bottom: 1px solid #eee; text-align: center; }
  .popup-checkout th { background: #f7f7f7; }
  .btn-checkout { background: #27ae60; color: #fff; border: none; border-radius: 6px; padding: 10px 24px; font-size: 1em; cursor: pointer; margin-top: 10px; }
  .close-popup { position: absolute; top: 12px; right: 18px; font-size: 22px; color: #888; cursor: pointer; }
  @media (max-width: 600px) { .popup-checkout .popup-content { padding: 12px 4vw; min-width: unset; } }
  </style>\n";
  echo "<script>
    document.getElementById('btnShowCheckout').onclick = function() {
      document.getElementById('popupCheckout').style.display = 'flex';
    };
    document.getElementById('closePopup').onclick = function() {
      document.getElementById('popupCheckout').style.display = 'none';
    };
    window.onclick = function(event) {
      var popup = document.getElementById('popupCheckout');
      if (event.target == popup) {
        popup.style.display = 'none';
      }
    }
    </script>";
}
?>
</body>
</html>
