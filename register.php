<?php
include 'inc/koneksi.php';

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi sederhana
    if (empty($username) || empty($password)) {
        $pesan = "Username dan Password wajib diisi!";
    } elseif ($password !== $konfirmasi) {
        $pesan = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username sudah digunakan
       $cek = $koneksi->prepare("SELECT id_user FROM akun WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $pesan = "Username sudah digunakan.";
        } else {
            // Simpan password apa adanya (TIDAK DIHASH)
            $level = "user"; // default user

            $stmt = $koneksi->prepare("INSERT INTO akun (username, password, level) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $level);

            if ($stmt->execute()) {
                header("Location: register.php?notif=success&redirect=login");
                exit;
            } else {
                $pesan = "Gagal mendaftar, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Toko Ardo</title>
    <link rel="stylesheet" href="css/login.css"> <!-- opsional -->
</head>
<body>
<div class="login-box">
    <h2>Register User</h2>
    <?php if (isset($_GET['notif']) && $_GET['notif'] == 'success' && isset($_GET['redirect']) && $_GET['redirect'] == 'login'): ?>
    <script>
        window.onload = function() {
            alert('Selamat, akun anda telah terdaftar!');
            window.location.href = 'login.php';
        }
    </script>
    <?php endif; ?>
    <?php if ($pesan) echo "<div class='error'>$pesan</div>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required><br>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>
