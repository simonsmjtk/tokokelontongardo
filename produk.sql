CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    gambar VARCHAR(255) NOT NULL
);

INSERT INTO produk (nama, harga, gambar) VALUES
('Produk A', 50000, 'produk_a.jpg'),
('Produk B', 100000, 'produk_b.jpg'),
('Produk C', 75000, 'produk_c.jpg');
