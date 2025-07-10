CREATE TABLE history_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    produk VARCHAR(255),
    jumlah INT,
    total_harga INT,
    tanggal DATETIME,
    nama_pembeli VARCHAR(100),
    metode VARCHAR(50),
    FOREIGN KEY (id_user) REFERENCES akun(id)
) ENGINE=InnoDB;