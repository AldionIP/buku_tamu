-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 05:16 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bukutamu`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id_pengaduan` int(11) NOT NULL,
  `id_petugas_pencatat` int(11) DEFAULT NULL,
  `nama_pelapor` varchar(150) NOT NULL,
  `kontak_pelapor` varchar(100) DEFAULT NULL,
  `kategori_pengaduan` varchar(100) NOT NULL,
  `judul_pengaduan` varchar(255) NOT NULL,
  `detail_pengaduan` text NOT NULL,
  `lokasi_kejadian` varchar(255) DEFAULT NULL,
  `waktu_kejadian` datetime DEFAULT NULL,
  `waktu_lapor` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pengaduan` enum('Baru','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Baru',
  `lampiran` varchar(255) DEFAULT NULL,
  `catatan_tindaklanjut` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id_pengaduan`, `id_petugas_pencatat`, `nama_pelapor`, `kontak_pelapor`, `kategori_pengaduan`, `judul_pengaduan`, `detail_pengaduan`, `lokasi_kejadian`, `waktu_kejadian`, `waktu_lapor`, `status_pengaduan`, `lampiran`, `catatan_tindaklanjut`) VALUES
(1, 16, 'Al D ion', 'suryaaditya1400@gmail.com', 'Fasilitas', 'Ac mati', 'Ac mati di ruang tunggu', 'ruangan tunggu lantai 1', '2025-04-23 10:07:00', '2025-04-23 03:07:28', 'Selesai', 'uploads/pengaduan/lampiran_6808597076798_1745377648.jpg', 'p'),
(2, 14, 'testing', '08976467868', 'Keamanan', 'keamanan', 'keamanan kurang', 'parkiran', '2025-04-23 10:50:00', '2025-04-23 03:50:48', 'Ditolak', 'uploads/pengaduan/lampiran_680863982e228_1745380248.png', ''),
(3, 14, 'dion', 'anddnjdndj@example.com', 'Saran', 'contoh', 'contoh', 'tkp natar', '2025-04-23 11:02:00', '2025-04-23 04:02:09', 'Diproses', NULL, 'mantap'),
(4, 14, 'sutimen', 'adnajaba@gmail.com', 'Kebersihan', 'contoh', 'contoh', 'tkp balam', '2025-04-23 11:02:00', '2025-04-23 04:03:12', 'Baru', 'uploads/pengaduan/lampiran_6808668067f89_1745380992.png', NULL),
(5, 14, 'Al D ion', 'suryaaditya1400@gmail.com', 'Keamanan', 'contoh', 'dgsgsfgs', 'dgsdg', '2025-05-06 21:41:00', '2025-05-06 14:41:29', 'Selesai', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$6XbSX7hj.Dz7wafO9q0E9.hr0zGKuPkynevDAyzfEcVuGegEJ4VcO', 'Nama Admin Utama'),
(14, 'dion', '$2y$10$ISyqy1oqB8lRf0MXQv1BneOkI5V/arCEp/z/.hAivjzpbkI5Ls/1a', 'Al D yon'),
(15, 'arkan', '$2y$10$ki6z3yJFqIwVAC1gh6bzreoHoxmPe/5ed380h1ar7K064rk72kRtG', 'arkan d piton'),
(16, 'Dian', '$2y$10$kZ/fHOcWEqMb8kNyCWkdKOQK3t3Q0gcPocPYeZROvtgsgqSmgJxhm', 'Dian d frediksen');

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL COMMENT 'Alamat tamu',
  `keperluan` text DEFAULT NULL COMMENT 'Keperluan kunjungan tamu',
  `pekerjaan` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL COMMENT 'Nomor telepon tamu',
  `pesan` text NOT NULL,
  `rating` tinyint(4) NOT NULL COMMENT 'Rating kepuasan 1-5',
  `status_kehadiran` varchar(20) DEFAULT NULL COMMENT 'Status (e.g., Hadir)',
  `waktu_scan_masuk` datetime DEFAULT NULL COMMENT 'Waktu saat QR di-scan',
  `no_antrian_hari_ini` int(11) DEFAULT NULL,
  `tanggal_antrian` date DEFAULT NULL,
  `waktu_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`id`, `nama`, `alamat`, `keperluan`, `pekerjaan`, `no_telp`, `pesan`, `rating`, `status_kehadiran`, `waktu_scan_masuk`, `no_antrian_hari_ini`, `tanggal_antrian`, `waktu_input`) VALUES
(1, 'Brody', 'Land Of Dawn', 'Rasa sakit adalah bukti bahwa aku masih hidup', NULL, '09089776', 'gg wp', 5, 'Hadir', '2025-04-22 09:27:59', NULL, NULL, '2025-04-22 03:34:21'),
(2, 'Hilda', 'Land Of Dawn', 'Bertarung Untuk memperoleh jawabannya', NULL, '0918279736297', 'ejffbj', 2, 'Hadir', '2025-04-22 09:30:44', NULL, NULL, '2025-04-22 03:34:40'),
(12, 'aldion ihza p', 'mars', 'makan', NULL, '0808949384239', '', 5, NULL, NULL, NULL, NULL, '2025-04-22 01:45:07'),
(13, 'layla', 'Land Of Dawn', 'Siap Maju !', NULL, '0899923444', '', 1, NULL, NULL, NULL, NULL, '2025-04-22 01:53:27'),
(14, 'Fanny', 'Land Of Dawn', 'Tuan Apa Perintahmu!', NULL, '090909090', '', 2, NULL, NULL, NULL, NULL, '2025-04-22 01:53:51'),
(15, 'tigreal', 'land Of Dawn', 'Tugasku untuk melindungi kalian', NULL, '89898989', '', 4, NULL, NULL, NULL, NULL, '2025-04-22 01:54:30'),
(17, 'Valir', 'Land Of Dawn', 'Dengan api akan kuhanguskan dunia ini!', NULL, '31331241', '', 2, NULL, NULL, NULL, NULL, '2025-04-22 01:55:16'),
(18, 'roger', 'Land Of Dawn', 'Darah Suci!', NULL, '21325437568', '', 5, NULL, NULL, NULL, NULL, '2025-04-22 01:56:22'),
(19, 'vfsdghgf', 'fhgjfj', 'fnfgbf245236', NULL, '36557687', '', 5, 'Hadir', '2025-04-22 09:31:01', NULL, NULL, '2025-04-22 01:56:38'),
(20, 'p', 'test', 'testing', NULL, '0000', '', 4, NULL, NULL, NULL, NULL, '2025-04-22 22:49:18'),
(21, 'tigrealefefs', 'vdsdvsdvsd', 'Lainnya', NULL, '463645765867', '', 5, NULL, NULL, 1, '2025-05-06', '2025-05-06 09:28:05'),
(22, 'aldion ihza pratana', 'dgdfg', 'Mitra', NULL, '0895345060988', '', 5, NULL, NULL, 2, '2025-05-06', '2025-05-06 09:29:35'),
(23, 'aldion ihza pratana', 'dgdfg', 'Mitra', NULL, '0895345060988', '', 5, NULL, NULL, 3, '2025-05-06', '2025-05-06 09:32:54'),
(24, 'aldion ihza pratana', 'dgdfg', 'Mitra', NULL, '0895345060988', '', 5, NULL, NULL, 4, '2025-05-06', '2025-05-06 09:33:00'),
(25, 'aldion ihza pratana', 'rgfdfg', 'Mitra', NULL, '0895345060988', '', 5, NULL, NULL, 5, '2025-05-06', '2025-05-06 09:33:15'),
(26, 'aldion ihza pratana', 'fsgsge', 'Mitra', NULL, '0895345060988', '', 4, NULL, NULL, 6, '2025-05-06', '2025-05-06 09:43:34'),
(27, 'dion', 'gegegse', 'Mitra', NULL, '8658585', '', 0, NULL, NULL, 7, '2025-05-06', '2025-05-06 10:25:55'),
(28, 'dion', 'gfsdgdgs', 'Mitra', NULL, '8658585', '', 0, NULL, NULL, 8, '2025-05-06', '2025-05-06 10:34:40'),
(29, 'diongsgdghdfhdfhfdhfd', 'gdgsdgsg', 'Kolaborasi', NULL, '8658585', '', 0, NULL, NULL, 9, '2025-05-06', '2025-05-06 10:36:12'),
(30, 'testinggggggggggggggg', 'gdgsdgsg', 'Belajar', NULL, '8658585', '', 0, NULL, NULL, 10, '2025-05-06', '2025-05-06 10:49:14'),
(31, 'testinggggggggggggggg', 'gdgsdgsg', 'Belajar', NULL, '8658585', '', 0, NULL, NULL, 11, '2025-05-06', '2025-05-06 11:42:26'),
(32, 'testinggggggggggggggg', 'jhgjhgjhv', 'Belajar', NULL, '8658585', '', 0, NULL, NULL, 12, '2025-05-06', '2025-05-06 11:50:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_petugas_pencatat` (`id_petugas_pencatat`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal_antrian` (`tanggal_antrian`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ibfk_1` FOREIGN KEY (`id_petugas_pencatat`) REFERENCES `petugas` (`id_petugas`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
