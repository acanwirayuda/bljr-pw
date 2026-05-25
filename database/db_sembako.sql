CREATE DATABASE IF NOT EXISTS db_sembako
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_sembako;

DROP TABLE IF EXISTS penjualan_detail;
DROP TABLE IF EXISTS penjualan;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(30) NOT NULL UNIQUE,
  nama_barang VARCHAR(150) NOT NULL,
  kategori VARCHAR(100) NOT NULL,
  satuan VARCHAR(20) NOT NULL DEFAULT 'pcs',
  harga_beli DECIMAL(12,2) NOT NULL DEFAULT 0,
  harga_jual DECIMAL(12,2) NOT NULL DEFAULT 0,
  stok INT NOT NULL DEFAULT 0,
  stok_minimum INT NOT NULL DEFAULT 5,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE penjualan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_transaksi VARCHAR(40) NOT NULL UNIQUE,
  admin_id INT NULL,
  nama_pelanggan VARCHAR(120) DEFAULT 'Umum',
  total DECIMAL(12,2) NOT NULL DEFAULT 0,
  bayar DECIMAL(12,2) NOT NULL DEFAULT 0,
  kembalian DECIMAL(12,2) NOT NULL DEFAULT 0,
  tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_penjualan_admin
    FOREIGN KEY (admin_id) REFERENCES admins(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE penjualan_detail (
  id INT AUTO_INCREMENT PRIMARY KEY,
  penjualan_id INT NOT NULL,
  barang_id INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  jumlah INT NOT NULL DEFAULT 1,
  subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_detail_penjualan
    FOREIGN KEY (penjualan_id) REFERENCES penjualan(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_detail_barang
    FOREIGN KEY (barang_id) REFERENCES barang(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Akun admin default
-- Email    : admin@sembako.test
-- Password : admin123
INSERT INTO admins (nama, email, password) VALUES
('Administrator', 'admin@sembako.test', '$2y$12$UvQgSPXd2Fphqp.1ikEYnuZskrd7r9mxvWu12iUvLobsmU/kiIjeu');

-- Data awal barang sembako
INSERT INTO barang (kode_barang, nama_barang, kategori, satuan, harga_beli, harga_jual, stok, stok_minimum) VALUES
('BRG001', 'Beras Premium 5 Kg', 'Beras', 'pack', 61000, 68000, 20, 5),
('BRG002', 'Minyak Goreng 1 Liter', 'Minyak', 'liter', 14500, 17000, 35, 8),
('BRG003', 'Gula Pasir 1 Kg', 'Gula', 'kg', 15000, 18000, 25, 6),
('BRG004', 'Telur Ayam 1 Kg', 'Telur', 'kg', 27000, 31000, 18, 5),
('BRG005', 'Mie Instan Goreng', 'Mie Instan', 'pcs', 2800, 3500, 80, 15),
('BRG006', 'Kopi Sachet', 'Minuman', 'pcs', 1200, 2000, 100, 20),
('BRG007', 'Tepung Terigu 1 Kg', 'Tepung', 'kg', 10500, 13000, 22, 5),
('BRG008', 'Susu Kental Manis Kaleng', 'Susu', 'kaleng', 11500, 14500, 16, 5);
