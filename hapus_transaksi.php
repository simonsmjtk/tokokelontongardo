<?php
session_start();
include 'inc/koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $koneksi->query("DELETE FROM history_transaksi WHERE id_transaksi = $id");
}

header("Location: history_transaksi.php");
exit;
?>