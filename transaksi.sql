CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produk_id INT NOT NULL,
    jumlah INT NOT NULL,
    total_harga INT NOT NULL,
    metode_pembayaran VARCHAR(50),
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
);
