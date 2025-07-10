<?php
session_start();
include 'inc/koneksi.php';

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $query = $koneksi->prepare("SELECT * FROM akun WHERE username = ? AND password = ?");
    $query->bind_param("ss", $user, $pass);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $_SESSION['login'] = true;
        $_SESSION['level'] = $data['level'];
        $_SESSION['id_user'] = $data['id_user']; // <-- Tambahkan baris ini

        // Redirect berdasarkan level
        if ($data['level'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $pesan = "Login gagal. Cek kembali username dan password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Toko Ardo</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-box">
           <marquee style="background:#3498db;color:#fff;font-size:1.4em;padding:15px 0;margin-bottom:23px;border-radius:15px;">
        Selamat Datang di Toko Kelontong Ardo
    </marquee>
        <h2>Login</h2>
        <?php if ($pesan) echo "<p class='error'>$pesan</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </form>
    </div>
</body>
</html>
