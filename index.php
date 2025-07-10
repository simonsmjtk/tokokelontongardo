<?php
session_start();
include 'inc/koneksi.php';

// Ambil username jika sudah login
$username = '';
if (isset($_SESSION['id_user'])) {
    $uid = $_SESSION['id_user'];
    $q = $koneksi->query("SELECT username FROM akun WHERE id_user = $uid");
    if ($q && $row = $q->fetch_assoc()) {
        $username = $row['username'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Toko Kelontong Ardo</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .marquee {
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      animation: marquee 15s linear infinite;
    }

    @keyframes marquee {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }
  </style>
</head>
<body>
  <span class="bg-icon bg-icon1">🍚</span>
  <span class="bg-icon bg-icon2">🍜</span>
  <span class="bg-icon bg-icon3">🥛</span>
  <span class="bg-icon bg-icon4">🧼</span>
  <span class="bg-icon bg-icon5">☕</span>
  <span class="bg-icon bg-icon6">🍞</span>
  <span class="bg-icon bg-icon7">🧂</span>
  <span class="bg-icon bg-icon8">🥚</span>
  <span class="bg-icon bg-icon9">🍬</span>
  <span class="bg-icon bg-icon10">🛢️</span>
 <header class="header">
 <div class="logo-container">
    <img src="img/logo3.png" alt="Logo Toko Ardo" class="logo-img">
    <h1 class="judul-toko">Toko Kelontong Ardo</h1>
  </div>
  <!-- Tambahkan sebelum <nav> -->
<button class="menu-toggle" id="menuToggle" aria-label="Menu">&#9776;</button>
  <nav>
    <button onclick="location.href='index.php'">Home</button>
    <button onclick="location.href='about.html'">About</button>
    <button onclick="location.href='contact.html'">Contact</button>
    <button onclick="location.href='produk.php'">Produk</button>
    <button onclick="location.href='testimoni.html'">Testimoni</button>
    <?php if (isset($_SESSION['login'])): ?>
    <?php else: ?>
      <button onclick="location.href='login.php'">Login</button>
    <?php endif; ?>
  </nav>
  <?php if ($username): ?>
  <div class="login-info" style="position:relative;">
    <span id="profileName" style="cursor:pointer;">
      <span style="color:#512da8; margin-right:6px;">👤</span>
      <strong>
        <?php
          // Ucapan waktu untuk user login
          date_default_timezone_set('Asia/Jakarta');
          $hour = (int)date('H');
          if ($hour >= 5 && $hour < 12) {
              echo "Selamat Pagi, " . htmlspecialchars($username);
          } elseif ($hour >= 12 && $hour < 18) {
              echo "Selamat Siang, " . htmlspecialchars($username);
          } else {
              echo "Selamat Malam, " . htmlspecialchars($username);
          }
        ?>
      </strong>
    </span>
    <form action="logout.php" method="post" class="logout-form" id="logoutForm" style="display:none; position:absolute; top:110%; right:0; z-index:10;">
      <button type="submit">Logout</button>
    </form>
  </div>
<?php endif; ?>
</header>
  <div class="marquee">
    <p>Selamat datang di Toko Kelontong Ardo! Temukan produk terbaik untuk kebutuhan sehari-hari Anda!</p>
  </div>
  <!-- Galeri/Tampilan Toko Full Width -->
   <div class="toko-gallery" style="text-align:center; margin:36px 0 18px 0;">
  <h2 style="margin-bottom:16px; color:#007bff;">Tampilan Toko Kami</h2>
<div class="toko-gallery-full">
  <img src="img/ardo2.jpg" alt="Toko Ardo" class="toko-full-img" />
</div>
   <section class="best-seller">
  <h2>Produk Best Seller</h2>
<div class="produk-best-seller">
<?php
$result = $koneksi->query("SELECT * FROM produk WHERE best_seller = 1 ORDER BY id DESC");
while ($produk = $result->fetch_assoc()) {
?>
    <div class="produk-item" style="display:inline-block; margin:10px; border:1px solid #ccc; padding:10px;">
        <img src="img/produk/<?= htmlspecialchars($produk['gambar']) ?>" width="100"><br>
        <b><?= htmlspecialchars($produk['nama']) ?></b><br>
        <span>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></span><br>
        <span style="color:orange;font-weight:bold;">Best Seller</span>
    </div>
<?php } ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var profile = document.getElementById('profileName');
  var logoutForm = document.getElementById('logoutForm');
  if (profile && logoutForm) {
    document.addEventListener('click', function(e) {
      if (profile.contains(e.target)) {
        logoutForm.style.display = (logoutForm.style.display === 'none' || logoutForm.style.display === '') ? 'inline-block' : 'none';
      } else {
        logoutForm.style.display = 'none';
      }
    });
  }
})
</script>
<script>
  // Script untuk menu hamburger/titik tiga
  var menuToggle = document.getElementById('menuToggle');
  var nav = document.querySelector('nav');
  if (menuToggle && nav) {
    menuToggle.addEventListener('click', function(e) {
      nav.classList.toggle('show');
      e.stopPropagation();
    });
    document.addEventListener('click', function(e) {
      if (!nav.contains(e.target) && !menuToggle.contains(e.target)) {
        nav.classList.remove('show');
      }
    });
  }

</script>
<script>
document.querySelectorAll('.produk-item').forEach(function(item) {
  item.style.position = 'relative';
  item.addEventListener('mouseenter', function() {
    // Cek jika sudah ada border glowing
    if (!item.querySelector('.produk-border-glow')) {
      var glow = document.createElement('div');
      glow.className = 'produk-border-glow';
      glow.style.position = 'absolute';
      glow.style.top = '-7px';
      glow.style.left = '-7px';
      glow.style.right = '-7px';
      glow.style.bottom = '-7px';
      glow.style.borderRadius = '18px';
      glow.style.border = '2.5px solid #2196f3';
      glow.style.boxShadow = '0 0 24px 6px rgba(33,150,243,0.28), 0 0 0 2px #90caf9';
      glow.style.pointerEvents = 'none';
      glow.style.opacity = '0';
      glow.style.transition = 'opacity 0.35s, box-shadow 0.7s';
      glow.style.zIndex = '2';
      item.appendChild(glow);
      setTimeout(function() {
        glow.style.opacity = '1';
        glow.animate([
          { boxShadow: '0 0 24px 6px rgba(33,150,243,0.28), 0 0 0 2px #90caf9' },
          { boxShadow: '0 0 40px 12px rgba(33,150,243,0.45), 0 0 0 4px #42a5f5' },
          { boxShadow: '0 0 24px 6px rgba(33,150,243,0.28), 0 0 0 2px #90caf9' }
        ], {
          duration: 1200,
          iterations: Infinity
        });
      }, 10);
    }
    item.style.transform = 'translateY(-14px) scale(1.045) rotate(-1.5deg)';
    item.style.transition = 'transform 0.35s cubic-bezier(.4,2,.6,1)';
  });
  item.addEventListener('mouseleave', function() {
    var glow = item.querySelector('.produk-border-glow');
    if (glow) {
      glow.style.opacity = '0';
      setTimeout(function() {
        if (glow && glow.parentNode) glow.parentNode.removeChild(glow);
      }, 350);
    }
    item.style.transform = '';
  });
});
</script>
<!-- Tambahkan sebelum </body> -->
<button id="scrollTopBtn" style="display:none;position:fixed;bottom:24px;right:24px;z-index:99;padding:10px 14px;border-radius:50%;background:#3498db;color:#fff;border:none;font-size:20px;box-shadow:0 2px 8px #aaa;cursor:pointer;">↑</button>
<script>
window.addEventListener('scroll', function() {
  document.getElementById('scrollTopBtn').style.display = (window.scrollY > 200) ? 'block' : 'none';
});
document.getElementById('scrollTopBtn').onclick = function() {
  window.scrollTo({top:0, behavior:'smooth'});
};
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.produk-item').forEach(function(item, i) {
    item.style.opacity = 0;
    item.style.transform = 'translateY(40px) scale(0.96)';
    item.style.transition = 'opacity 0.6s cubic-bezier(.4,2,.6,1), transform 0.6s cubic-bezier(.4,2,.6,1)';
    setTimeout(function() {
      item.style.opacity = 1;
      item.style.transform = 'translateY(0) scale(1)';
    }, 200 + i * 120);
  });
});
</script>
</body>
</html>

