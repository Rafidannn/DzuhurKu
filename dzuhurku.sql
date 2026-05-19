-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Bulan Mei 2026 pada 03.44
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dzuhurku`
--
CREATE DATABASE IF NOT EXISTS `dzuhurku` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dzuhurku`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`) VALUES
(1, 'admin', 'admin123', 'Administrator Utama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`) VALUES
(1, 'Bapak Budi'),
(2, 'Ibu Siti'),
(3, 'Bapak Andi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`) VALUES
(1, 'X (sepuluh) Kuliner 3'),
(2, 'XI (sebelas ) PH 2'),
(3, 'XI PPL 1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `murid`
--

CREATE TABLE `murid` (
  `id_murid` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_murid` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `murid`
--

INSERT INTO `murid` (`id_murid`, `nisn`, `nama_murid`, `password`, `id_kelas`) VALUES
(1, '0084383683', 'Alsheira Salsabilla', '123456', 1),
(2, '0107617176', 'Anandita Rasty Annuur', '123456', 1),
(3, '0098620531', 'Aulia Kania Putri', '123456', 1),
(4, '0099312201', 'Aulia Mukharohmah', '123456', 1),
(5, '0109771076', 'Avara Ratu Maulida', '123456', 1),
(6, '0084517268', 'Ayudya Zahrah', '123456', 1),
(7, '0094052227', 'Daisy Vita Dwi Rahayu', '123456', 1),
(8, '0091673112', 'Devina Syafa Ayudya', '123456', 1),
(9, '0086853381', 'Dewi Anggun Kartawijaya', '123456', 1),
(10, 'NIS-10624', 'Dwi Nurya Ramadhani', '123456', 1),
(11, '0091954161', 'Dzakirah Bashid Rizq', '123456', 1),
(12, '0091596306', 'Fitri Rahayu', '123456', 1),
(13, '0099731832', 'Imelda Lestari', '123456', 1),
(14, '0086405311', 'Kayla Silviyani', '123456', 1),
(15, '2532450116', 'Kezia Regita Oktaviani', '123456', 1),
(16, '0094614775', 'Khafara Nursyahrani Yusuf', '123456', 1),
(17, '3109563584', 'Leviana Zahra Airella Nugroho', '123456', 1),
(18, '0099281995', 'Ma\'a Suci Ismawati', '123456', 1),
(19, '0097066166', 'Meilana Anggraini', '123456', 1),
(20, '0099945063', 'Nadila Aprilliani', '123456', 1),
(21, '0091847136', 'Nailah Saskia', '123456', 1),
(22, '0104671399', 'Nayla Izzati Khairunnisa', '123456', 1),
(23, '0098471261', 'Nur Arifah Awaliyah', '123456', 1),
(24, '0087034286', 'Nur Choiril Razkia Rahmadhania', '123456', 1),
(25, '0097553707', 'Nur Muhammad Izzan Hadi Atmodjo', '123456', 1),
(26, '0097997891', 'Rizqia Annisa Saputra', '123456', 1),
(27, '0109198628', 'Robii Nafis Ugua', '123456', 1),
(28, '0098254162', 'Sasmitha Allea Nanditya', '123456', 1),
(29, '0098369831', 'Shafa Satya Pradani', '123456', 1),
(30, '0091944225', 'Shafa Sugi Khaerani', '123456', 1),
(31, '0099706084', 'Siti Hilmiyah', '123456', 1),
(32, '0084215174', 'Siti Lizma Wati Oktavia', '123456', 1),
(33, '0102605321', 'Tegar Ahza Abdillah', '123456', 1),
(34, '0098143773', 'Tsabitah There Zhahirah', '123456', 1),
(35, '0089041810', 'Zahra Rizky Ramadhanie', '123456', 1),
(36, '3089640088', 'Zahrotul Fikriyah', '123456', 1),
(37, '0088755542', 'Albi Triyansyah', '123456', 2),
(38, '0076809409', 'Ali Zainal Abidin', '123456', 2),
(39, '0086942350', 'Andika Hermawan', '123456', 2),
(40, '0089210816', 'Ayu Nuraeni', '123456', 2),
(41, '0081414839', 'Chevin Juliano Prastian', '123456', 2),
(42, '0087103848', 'Daniel Anggiat Siagian', '123456', 2),
(43, '0086738544', 'Farrin Quinsha Izzati Andrian', '123456', 2),
(44, '0078956789', 'Gadis Roro Safitri', '123456', 2),
(45, '0092154507', 'Genevado Alfiansyah Kelana', '123456', 2),
(46, '0086305946', 'Habibi Yusuf', '123456', 2),
(47, '0086671154', 'Layla Sari', '123456', 2),
(48, '0084279757', 'Laysa Faradiba Almira', '123456', 2),
(49, '0083372097', 'Marco Dezan Dwi Atmaja', '123456', 2),
(50, '0087777142', 'Muhammad Rizky Ramadhan', '123456', 2),
(51, '0084016330', 'Mutiara Nabila', '123456', 2),
(52, '0066030433', 'Nabila Putri', '123456', 2),
(53, '0082922551', 'Nadhira Insani Arsyafira', '123456', 2),
(54, '0082200493', 'Nadia Wulan Dari', '123456', 2),
(55, '0082713993', 'Nadya Shafwah Ramadhani', '123456', 2),
(56, '0087769083', 'Patin Hamamah', '123456', 2),
(57, '0083089796', 'Puspa Dewi Widia Ningrum', '123456', 2),
(58, '0089168488', 'Rayya Pitta Aline', '123456', 2),
(59, '0082683313', 'Rena Trisna Lestari', '123456', 2),
(60, '0075306493', 'Ridwansyah Novaldy', '123456', 2),
(61, '0085105042', 'Sabrina Aulia Putri', '123456', 2),
(62, '0087599109', 'Salsabilah Wijaya', '123456', 2),
(63, '0086653200', 'Sausan Fatiah', '123456', 2),
(64, '0082429853', 'Shakila Chandhani Zaenal', '123456', 2),
(65, '0088854765', 'Steven Fondisonzo Sianturi', '123456', 2),
(66, '0082102186', 'Syafira Mehdia Syafitri', '123456', 2),
(67, '0085680487', 'Syalwa Diysa Respati Putri', '123456', 2),
(68, '0087788912', 'Tianlie Laily Badriyah', '123456', 2),
(69, '0083694004', 'Yezkiel Parulian Manalu', '123456', 2),
(70, '0087224713', 'Zaskia Alinska', '123456', 2),
(71, '0082172103', 'Achmad Fachri Hidayat', '123456', 3),
(72, '0083125263', 'Adya Syifa Ainiah', '123456', 3),
(73, '0081459257', 'Afifah Ayuningtias', '123456', 3),
(74, '0091457670', 'Ahmad Ihsan Muzakki', '123456', 3),
(75, '0094046768', 'Ahmad Kamal Angkasa', '123456', 3),
(76, '0085964065', 'Amalia Utami Widayanti', '123456', 3),
(77, '0086013314', 'Amsal Michael', '123456', 3),
(78, '0079331991', 'Asy - Syifa Nur\'aini', '123456', 3),
(79, '0084841804', 'Carysa Syarla Musabih', '123456', 3),
(80, '0087198489', 'Daffaa Wahyu Antakesuma', '123456', 3),
(81, '0095045024', 'Febrian Ilham Abdullah', '123456', 3),
(82, '0085361594', 'Ghatan Adya Pratama', '123456', 3),
(83, '0082891646', 'Hadi Nenchi Verlina', '123456', 3),
(84, '0081378998', 'Humairah Hud Alham', '123456', 3),
(85, '0084596877', 'Julio Raffael Rahma Yudin', '123456', 3),
(86, '0087245775', 'Kezia Putri Wijaya', '123456', 3),
(87, '0095841601', 'Maulana Mufti Yahya', '123456', 3),
(88, '0088427700', 'Miralti', '123456', 3),
(89, '0081913156', 'Mona Verlitta Putri', '123456', 3),
(90, '0086294795', 'Muhamad Fahri', '123456', 3),
(91, '0091234075', 'Muhammad Alif Ambia', '123456', 3),
(92, '3085463259', 'Muhammad Faqih Hibatullah', '123456', 3),
(93, '0087562243', 'Muhammad Ramadian Ramadhan', '123456', 3),
(94, '0088860611', 'Muhammad Rizky Fahreza', '123456', 3),
(95, '0081119238', 'Naufal Murtadho', '123456', 3),
(96, '0082567190', 'Naura Aeprillya Effendi', '123456', 3),
(97, '3097797354', 'Nayla Zara', '123456', 3),
(98, '0084480192', 'Octavian Bangkit Sanjaya', '123456', 3),
(99, '3088703679', 'Raditya Zahran Aulia Nugroho', '123456', 3),
(100, '0082780260', 'Rafidan Athari', '123456', 3),
(101, '2436310023', 'Rafka Duan Keano', '123456', 3),
(102, '0098236640', 'Rafka Raditya Putra Maulana', '123456', 3),
(103, '0087256385', 'Rakha Fawwaz Janitra', '123456', 3),
(104, '0081231602', 'Silfina Fithriya Rayya Affandi', '123456', 3),
(105, '0085189036', 'Yuke Fara Nurul Aini', '123456', 3),
(106, '0083291642', 'Zaina Zahrotushofa', '123456', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_murid` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `status` enum('Hadir','Tidak Hadir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `murid`
--
ALTER TABLE `murid`
  ADD PRIMARY KEY (`id_murid`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_murid` (`id_murid`),
  ADD KEY `id_guru` (`id_guru`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `murid`
--
ALTER TABLE `murid`
  MODIFY `id_murid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `murid`
--
ALTER TABLE `murid`
  ADD CONSTRAINT `murid_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_murid`) REFERENCES `murid` (`id_murid`) ON DELETE CASCADE,
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
