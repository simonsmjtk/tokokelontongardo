<?php
session_start();

$id = $_GET['id'];
$_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + 1;

header("Location: keranjang.php");

$nama = $_POST['nama'];
$harga = $_POST['harga'];

$item = [
    'id' => $id,
    'nama' => $nama,
    'harga' => $harga,
    'jumlah' => 1
];

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$found = false;
foreach ($_SESSION['keranjang'] as &$produk) {
    if ($produk['id'] == $id) {
        $produk['jumlah'] += 1;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['keranjang'][] = $item;
}

header("Location: keranjang.php");
exit;
?>
