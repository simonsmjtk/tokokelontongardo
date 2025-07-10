CREATE DATABASE toko_ardo;
USE toko_ardo;

CREATE TABLE produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  harga INT,
  gambar VARCHAR(100)
);
INSERT INTO produk (nama, harga, gambar) VALUES
('Minyak Goreng 1L', 18000, 'minyak.jpg'),
('Gula Pasir 1kg', 14000, 'gula.jpg'),
('Beras 5kg', 55000, 'beras.jpg');
