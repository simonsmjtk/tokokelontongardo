<?php
session_start();
include 'inc/koneksi.php';
// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
  $_SESSION['keranjang'] = [];
}

// Tambah ke keranjang
if (isset($_GET['add'])) {
  $id = $_GET['add'];
  if (isset($_SESSION['keranjang'][$id])) {
    $_SESSION['keranjang'][$id]++;
  } else {
    $_SESSION['keranjang'][$id] = 1;
  }
  $redirect = 'produk.php';
  if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
  }
  header("Location: $redirect");
  exit;
}

// Kurangi dari keranjang
if (isset($_GET['min'])) {
  $id = $_GET['min'];
  if (isset($_SESSION['keranjang'][$id])) {
    $_SESSION['keranjang'][$id]--;
    if ($_SESSION['keranjang'][$id] <= 0) {
      unset($_SESSION['keranjang'][$id]);
    }
  }
  $redirect = 'produk.php';
  if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
  }
  header("Location: $redirect");
  exit;
}

// Beli langsung
if (isset($_GET['buylangsung'])) {
    $id = $_GET['buylangsung'];
    // Jika produk sudah ada di keranjang, biarkan jumlahnya
    if (isset($_SESSION['keranjang'][$id])) {
        // Tidak diubah, biarkan jumlahnya
    } else {
        $_SESSION['keranjang'] = [$id => 1];
    }
    // Hanya produk yang dibeli langsung yang ada di keranjang
    $_SESSION['keranjang'] = [$id => $_SESSION['keranjang'][$id] ?? 1];
    // Tidak redirect ke checkout.php, tapi munculkan popup dengan JS
    echo "<script>window.onload = function() { document.getElementById('btnShowCheckout').click(); };</script>";
}

// Hitung total item di keranjang
$total_keranjang = array_sum($_SESSION['keranjang']);
$total_harga_keranjang = 0;
if (!empty($_SESSION['keranjang'])) {
  $ids = implode(',', array_keys($_SESSION['keranjang']));
  $result = $koneksi->query("SELECT id, harga FROM produk WHERE id IN ($ids)");
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $qty = $_SESSION['keranjang'][$id] ?? 0;
      $total_harga_keranjang += $row['harga'] * $qty;
    }
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Produk - Toko Ardo</title>
  <link rel="stylesheet" href="css/produk.css">
  <style>
  .cart-icon {
    position: fixed;
    top: 20px;
    right: 30px;
    font-size: 28px;
    color: #3498db;
    text-decoration: none;
  }
  .cart-count {
    position: absolute;
    top: -8px;
    right: -10px;
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 7px;
    font-size: 14px;
  }
  .produk {
    border: 1px solid #eee;
    padding: 15px;
    margin-bottom: 20px;
    width: 220px;
    display: inline-block;
    vertical-align: top;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0 2px 8px #eee;
  }
  .produk .qty-btn {
    padding: 2px 8px;
    font-size: 16px;
    margin: 0 3px;
    background: #eee;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  .produk .action-btn {
    margin: 5px 2px;
    padding: 5px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    color: #fff;
  }
  .produk .keranjang-btn {
    background: #3498db;
  }
  .produk .beli-btn {
    background: #27ae60;
  }
  </style>
</head >
<body>
  <!-- Background icon sembako -->
  <span class="bg-icon bg-icon1">🍚</span>
  <span class="bg-icon bg-icon3">🥛</span>
  <span class="bg-icon bg-icon4">🧼</span>
  <span class="bg-icon bg-icon5">☕</span>
  <span class="bg-icon bg-icon6">🍞</span>
  <span class="bg-icon bg-icon7">🧂</span>
  <span class="bg-icon bg-icon8">🥚</span>
  <span class="bg-icon bg-icon9">🍬</span>
  <span class="bg-icon bg-icon10">🛢️</span>

  <a href="index.php" style="display: inline-block; margin-bottom: 15px; padding: 10px 20px; background-color: #3498db; color: white; text-decoration: none; border-radius: 5px;">⬅ Kembali ke Dashboard</a>
   <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Icon keranjang -->
<a href="keranjang.php" class="cart-icon">
  🛒
  <?php if ($total_keranjang > 0): ?>
    <span class="cart-count"><?php echo $total_keranjang; ?></span>
  <?php endif; ?>
</a>
<?php if ($total_keranjang > 0): ?>
  <button id="btnShowCheckout" class="btn-checkout" style="position:fixed;top:20px;right:250px;z-index:1000;padding:8px 18px;font-size:16px;">Checkout</button>
<?php endif; ?>
<?php if ($total_keranjang > 0): ?>
  <span style="position:fixed;top:55px;right:30px;font-size:15px;color:#222;background:#fffbe7;padding:4px 12px;border-radius:8px;box-shadow:0 2px 8px #eee;">
    Total: <b>Rp <?php echo number_format($total_harga_keranjang); ?></b>
  </span>
<?php endif; ?>
  <h1>Daftar Produk</h1>
  <form method="get" style="margin-bottom:20px; text-align:center;">
  <input type="text" name="cari" placeholder="Cari produk..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>" style="padding:7px 14px; border-radius:6px; border:1px solid #ccc; width:220px;">
  <button type="submit" style="padding:7px 18px; border-radius:6px; background:#3498db; color:#fff; border:none;">Cari</button>
</form>
<?php
// Tambahkan ini sebelum $produk = [];
if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $cari = $koneksi->real_escape_string($_GET['cari']);
    $result = $koneksi->query("SELECT * FROM produk WHERE nama LIKE '%$cari%'");
} else {
    $result = $koneksi->query("SELECT * FROM produk");
}

// ... kode session & keranjang ...

// Ambil kategori dari GET

$kategori_result = $koneksi->query("SELECT * FROM kategori");
$id_kategori_aktif = isset($_GET['id_kategori']) ? intval($_GET['id_kategori']) : 0;
?>
<div class="kategori-filter">
    <a href="produk.php" class="btn-kategori <?= $id_kategori_aktif == 0 ? 'active' : '' ?>">Semua</a>
    <?php while($kat = $kategori_result->fetch_assoc()): ?>
        <a href="produk.php?id_kategori=<?= $kat['id_kategori'] ?>" class="btn-kategori <?= $id_kategori_aktif == $kat['id_kategori'] ? 'active' : '' ?>">
            <?= htmlspecialchars($kat['nama_kategori']) ?>
        </a>
    <?php endwhile; ?>
</div>
<?php
// QUERY PRODUK BERDASARKAN KATEGORI
if ($id_kategori_aktif > 0) {
    $result = $koneksi->query("SELECT produk.*, kategori.nama_kategori FROM produk 
        LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori 
        WHERE produk.id_kategori = $id_kategori_aktif");
} else {
    $result = $koneksi->query("SELECT produk.*, kategori.nama_kategori FROM produk 
        LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori");
}
$kategori_result = $koneksi->query("SELECT * FROM kategori");
$id_kategori_aktif = isset($_GET['id_kategori']) ? intval($_GET['id_kategori']) : 0;
$cari = isset($_GET['cari']) ? $koneksi->real_escape_string($_GET['cari']) : '';

$sql = "SELECT produk.*, kategori.nama_kategori FROM produk 
        LEFT JOIN kategori ON produk.id_kategori = kategori.id_kategori";

$where = [];
if ($id_kategori_aktif > 0) {
    $where[] = "produk.id_kategori = $id_kategori_aktif";
}
if ($cari != '') {
    $where[] = "produk.nama LIKE '%$cari%'";
}
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$result = $koneksi->query($sql);
$produk = [];
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}
$total = count($produk);
$per_slide = 2;
$slide_count = ceil($total / $per_slide);

echo '<div class="produk-container">';
foreach ($produk as $row) {
    $id = $row['id'];
    $gambar = !empty($row['gambar']) && file_exists("img/{$row['gambar']}") ? $row['gambar'] : 'default.png';
    $qty = $_SESSION['keranjang'][$id] ?? 0;
    echo "<div class='produk'>
        <img src='img/{$gambar}' width='100'><br>
        <strong>{$row['nama']}</strong><br>
        Rp " . number_format($row['harga']) . "<br>
        <div style='margin:8px 0;'>
          <a href='produk.php?min={$id}' class='qty-btn'>−</a>
          <span style='min-width:24px;display:inline-block;'>{$qty}</span>
          <a href='produk.php?add={$id}' class='qty-btn'>+</a>
        </div>
        <a href='produk.php?buylangsung={$id}' class='action-btn beli-btn'>Beli Langsung</a>
        <a href='produk.php?add={$id}' class='action-btn keranjang-btn'>Keranjang</a>
    </div>";
}
echo '</div>';

// Bagian checkout popup hanya tampil jika keranjang tidak kosong
if (!empty($_SESSION['keranjang'])) {
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
    window.onclick = function(event) {
      var popup = document.getElementById('popupCheckout');
      if (event.target == popup) {
        popup.style.display = "none";
      }
    }
    </script>
    <?php
}
?>
<script>
document.querySelectorAll('.btn-kategori').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-kategori').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        var kategori = this.getAttribute('data-kategori');
        document.querySelectorAll('.produk-item').forEach(function(item) {
            if (kategori === 'Semua' || item.getAttribute('data-kategori') === kategori) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
