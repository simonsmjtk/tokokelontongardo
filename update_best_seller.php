<?php
include 'koneksi.php';
$id = intval($_POST['id']);
$best_seller = intval($_POST['best_seller']);
$koneksi->query("UPDATE produk SET best_seller = $best_seller WHERE id = $id");
?>