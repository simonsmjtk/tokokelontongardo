CREATE TABLE akun (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  level ENUM('admin', 'user') NOT NULL
) ENGINE=InnoDB;

INSERT INTO akun (username, password, level) VALUES
('admin', 'admin123', 'admin'),
('user', 'user123', 'user');
