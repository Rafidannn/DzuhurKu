DROP DATABASE IF EXISTS dzuhurku;
CREATE DATABASE dzuhurku;
USE dzuhurku;

-- Tabel Admin
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_admin VARCHAR(100) NOT NULL
);

-- Admin dengan password plain text (tanpa hash)
INSERT INTO admin (username, password, nama_admin) VALUES 
('admin', 'admin123', 'Administrator Utama');

-- Tabel Kelas
CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL
);

INSERT INTO kelas (nama_kelas) VALUES 
('10-A'), ('10-B'), ('11-A'), ('12-A');

-- Tabel Guru
CREATE TABLE guru (
    id_guru INT AUTO_INCREMENT PRIMARY KEY,
    nama_guru VARCHAR(100) NOT NULL
);

INSERT INTO guru (nama_guru) VALUES 
('Bapak Budi'), ('Ibu Siti'), ('Bapak Andi');

-- Tabel Murid
CREATE TABLE murid (
    id_murid INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) NOT NULL UNIQUE,
    nama_murid VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_kelas INT NOT NULL,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE
);

-- Murid dengan password plain text (123456)
INSERT INTO murid (nisn, nama_murid, password, id_kelas) VALUES 
('10001', 'Ahmad', '123456', 1),
('10002', 'Budi', '123456', 1),
('10003', 'Citra', '123456', 1),
('10004', 'Dewi', '123456', 2),
('10005', 'Eko', '123456', 2),
('10006', 'Fajar', '123456', 3),
('10007', 'Gita', '123456', 3);

-- Tabel Presensi
CREATE TABLE presensi (
    id_presensi INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    id_murid INT NOT NULL,
    id_guru INT NOT NULL,
    status ENUM('Hadir', 'Tidak Hadir') NOT NULL,
    FOREIGN KEY (id_murid) REFERENCES murid(id_murid) ON DELETE CASCADE,
    FOREIGN KEY (id_guru) REFERENCES guru(id_guru) ON DELETE CASCADE
);
