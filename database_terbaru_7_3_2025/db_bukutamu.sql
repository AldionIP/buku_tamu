-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 06:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `master_tamu`
--

CREATE TABLE `master_tamu` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `rating` int(1) DEFAULT NULL,
  `waktu_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_tamu`
--

INSERT INTO `master_tamu` (`id`, `nama`, `email`, `no_hp`, `alamat`, `pekerjaan`, `rating`, `waktu_dibuat`) VALUES
(2147483647, 'Adinda Mumtazah Salsabila', 'Dndmmtzh@gmail.com', '+62 083-1778-95281', 'Jalan ikan mas gg mansur no 34 bandar lampung', 'Lainnya', 5, '2025-07-03 03:16:13'),
(2147483648, 'Catur Priyanto', 'cpriyanto205@gmail.com', '+62 085-6647-14137', 'Jln. Nangka 3 no 35 Korpri Jaya Kec. Sukarame', 'Buruh', 5, '2025-07-03 03:58:19'),
(2147483649, 'Leni mahanani', 'lenimahanani82@gmail.com', '+62 812-7937-9514', 'Jalan sunda kapal brown no.15', 'Lainnya', 5, '2025-07-03 04:06:42'),
(2147483651, 'Megi sandy saputra', 'megi.sandi@gmail.com', '+62 081-7830-5541', 'Jl. Dr Setia Budi No. 4, Sukarame 2, Teluk Betung Barat, Bandar Lampung', 'Lainnya', 5, '2025-07-03 14:29:11'),
(2147483652, 'Kartini', 'krtini1017@gmail.com', '+62 089-5217-55209', 'Jln.wa abdurahman gg musholah baitul makmur', 'Lainnya', 5, '2025-07-03 14:30:26'),
(2147483653, 'Heri Pahlani', 'Heripahlani@gmail.com', '+62 089-5613-20678', 'Jl.amd gang teratai kp sawah raya lk 2 rt 06 bakung teluk betung barat bandar lampung', 'Buruh', 5, '2025-07-03 14:34:52'),
(2147483654, 'Herlia Dwi Putri', 'herliadwiputri@gmail.com', '+62 853-6671-1698', 'Jl. Soekarno Hatta no 12 Sepang Jaya', 'Lainnya', 5, '2025-07-03 14:35:42'),
(2147483655, 'Muhammad Arief Irvan', 'arif.irvan55@gmail.com', '+62 896-1797-8198', 'Jl. Hi Abdullah VI No 3 RT 14 LK 3 Kel. Kedaton', 'Lainnya', 5, '2025-07-03 14:36:38'),
(2147483656, 'WINDA HARYANI', 'haryani.winda82@gmail.com', '+62 082-2890-23682', 'jl teluk Bone suka Banjar RT 04 lk 02', 'Lainnya', 5, '2025-07-03 14:37:15'),
(2147483657, 'SULAIMAN HENDRA', 'sulaimanhendra090802@gmail.com', '+62 085-8132-51276', 'jl kunci rt 04 lk 03 kel keteguhan kec teluk betung timur bandar lampung', 'Pelajar/Mahasiswa', 5, '2025-07-03 14:37:58'),
(2147483658, 'Arifin. S', 'arifinsanta66310@gmail.com', '+62 081-5387-73597', 'Jl. Ikan paus no.15 GG cendrawasih 3 RT.017 LK.I Kel. Pesawahan kec. Telukbetung Selatan B. Lampung', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:38:55'),
(2147483659, 'A.Rahman', 'rahmanbetung82@gmail.com', '+62 813-5116-9683', 'Jl. Wr. Supratman No 16 Rt 007 LK II', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:39:34'),
(2147483660, 'Sindy nan zola', 'sindy010696@gmail.com', '+62 083-8005-16519', 'Jl. Cv kota agung no 3 lk I', 'Lainnya', 5, '2025-07-03 14:40:16'),
(2147483661, 'Gunawan syah', 'agunsyah112@gmail.com', '+62 853-6763-8884', 'Jl. Slamet riyadi 4 no.31 rt 005 Lk.1 kelurahan bumi raya kecamatan bumi waras kota bandarlampung', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:41:41'),
(2147483662, 'Adam Reza', 'adamreza251@gmail.com', '+62 083-1686-51449', 'Jln. Ikan Bawal gg Wahid RT 015 Bumi Waras Bandar Lampung', 'Lainnya', 5, '2025-07-03 14:42:27'),
(2147483663, 'Angga Pratama', 'pratamaangga1986@gmail.com', '+62 081-3673-01801', 'Jl.Ikan Mas Gg Mansur RT.024 Lk.III Kelurahan Kangkung Kecamatan Bumi Waras', 'Lainnya', 5, '2025-07-03 14:43:11'),
(2147483664, 'Rizke Hulan Djano', 'rizkedj@gmail.com', '+62 896-6831-7461', 'Jl. Yos Sudarso Gg. Ikan Selar No.16 Rt.09 Lk.01 Kel. Sukaraja Kec. Bumi Waras', 'Lainnya', 5, '2025-07-03 14:43:57'),
(2147483665, 'Ahmad Burlian', 'aqfel.magribi@gmail.com', '+62 085-3810-65085', 'Jalan Ogan Kampung Sukabaru Rt 06 Lingkungan 3 No 29 Panjang utara', 'Lainnya', 5, '2025-07-03 14:44:36'),
(2147483666, 'ARIEF ARPHAN', 'ariefajja228@gmail.com', '+62 895-6219-20914', 'Jl.teluk Ambon GG garuda', 'Wiraswasta', 5, '2025-07-03 14:45:30'),
(2147483667, 'Faidah utami', 'faidahutami2022@gmail.com', '+62 085-2696-69391', 'Kp. Karang Maritim RT.04 Lk 2 Kel. Karang maritim kec. Panjang', 'Lainnya', 5, '2025-07-03 14:46:09'),
(2147483668, 'AGUS IRAWAN', 'gusirdusuqiyah08@gmail.com', '+62 831-7849-4568', 'KP. SINAR GUNUNG LK III RT. 010 KELURAHAN PANJANG SELATAN KECAMATAN PANJANG KOTA BANDAR LAMPUNG PROVINSI LAMPUNG', 'Lainnya', 5, '2025-07-03 14:46:54'),
(2147483669, 'MULYANTO', 'Mulyanto.aboen@gmail.com', '+62 812-7265-4841', '\"Jalan Teuku Umar Gg.Putra No.54 RT.003\r\n Lk. 1 Kel. Sawah Brebes Kec. Tanjung Karang Timur Bandar Lampung.\"', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:47:35'),
(2147483670, 'Zul Tanu Reza', 'zultanureza79@gmail.com', '+62 819-5746-1802', 'Jalan Putri Balau No 60 Lk I', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:48:49'),
(2147483671, 'Khojin Taliban', 'khojintaliban@gmail.com', '+62 813-6784-5086', 'JL. H. SYARIF, no.23, Kalibalau Kencana, Kedamaian, Bandar Lampung.', 'Lainnya', 5, '2025-07-03 14:49:27'),
(2147483672, 'Aji Prayoga Wibowo', 'Ajiprayogaw@gmail.com', '+62 896-4882-5126', 'Jl. Wolter Monginsidi gang Permai III NO.20, Kelurahan Talang, Kecamatan Teluk Betung Selatan', 'Wiraswasta', 5, '2025-07-03 14:50:17'),
(2147483673, 'Nurjaman', 'putrieaja01@gmail.com', '+62 895-6097-09088', 'Jl jati gg. Markisa no.10 Tanjung raya', 'Lainnya', 5, '2025-07-03 14:51:06'),
(2147483674, 'Marlina', 'linamar00341@gmail.com', '+62 812-7152-6727', 'Jl.Cut mutia gg. Sriwijaya I RT 18 LK I Kelurahan Gulak Galik Kecamatan TBU', 'Lainnya', 5, '2025-07-03 14:51:41'),
(2147483675, 'Marni junita,SE', 'dede.marni90@gmail.com', '+62 852-6872-1901', 'Jln, dr Harun 1Gg.H wirna no 126 kota baru', 'Mengurus Rumah Tangga', 5, '2025-07-03 14:52:23'),
(2147483676, 'FAZRIE MULIA', 'fazri.henny@gmail.com', '+62 812-9725-5347', 'Jl. Durian II No.10 Rt.03 Lk.1 Durian Payung, Tanjung Karang Pusat, Kota Bandar Lampung', 'Lainnya', 5, '2025-07-03 14:53:01'),
(2147483677, 'Hendri Leo', 'leohendri9@gmail.com', '+62 896-3066-2364', 'Jl. H. Komarudin Gg. By Pass Raya 8 No. 15, RT. 005, Rajabasa Raya, Rajabasa, Bandar Lampung, Lampung', 'Lainnya', 5, '2025-07-03 14:53:37');

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
(5, 14, 'Al D ion', 'suryaaditya1400@gmail.com', 'Keamanan', 'contoh', 'dgsgsfgs', 'dgsdg', '2025-05-06 21:41:00', '2025-05-06 14:41:29', 'Selesai', NULL, ''),
(6, NULL, 'ewfqwfwqfqwwadaa', 'adwdwdq', 'Kebersihan', 'singkat', 'KKB', 'Papua', NULL, '2025-06-30 07:19:37', 'Ditolak', NULL, 'mantap'),
(7, NULL, 'sfnakfnka', 'sajdakdbnka', 'Fasilitas', 'sajksnka', 'fawkfhkaw', '', NULL, '2025-07-02 12:40:43', 'Selesai', 'uploads/pengaduan/lampiran_686528cbef44a1751460043.png', 'tolong tolong'),
(8, NULL, 'testing dion', 'dion@gmail.com', 'Layanan', 'Kebersihan', 'masuk', '', NULL, '2025-07-03 06:11:59', 'Selesai', 'uploads/pengaduan/lampiran_68661f2fe0afb1751523119.png', 'mantap'),
(9, NULL, 'Aldion', 'aldion2392@gmail.com', 'Saran', 'Saran', 'testing', '', NULL, '2025-07-03 15:34:19', 'Baru', 'uploads/pengaduan/lampiran_6866a2fb3247e1751556859.png', NULL),
(10, NULL, 'scsdc', 'dss@gmail.com', 'Layanan', 'dvsd', 'ds ds', '', NULL, '2025-07-03 15:47:12', 'Baru', 'uploads/pengaduan/lampiran_6866a600d82611751557632.png', NULL);

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
  `master_id_sumber` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL COMMENT 'Alamat tamu',
  `keperluan` text DEFAULT NULL COMMENT 'Keperluan kunjungan tamu',
  `pekerjaan` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL COMMENT 'Nomor telepon tamu',
  `pesan` text DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL COMMENT 'Rating kepuasan 1-5',
  `status_kehadiran` varchar(20) DEFAULT NULL COMMENT 'Status (e.g., Hadir)',
  `waktu_scan_masuk` datetime DEFAULT NULL COMMENT 'Waktu saat QR di-scan',
  `no_antrian_hari_ini` int(11) DEFAULT NULL,
  `tanggal_antrian` date DEFAULT NULL,
  `waktu_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`id`, `master_id_sumber`, `nama`, `email`, `alamat`, `keperluan`, `pekerjaan`, `no_telp`, `pesan`, `rating`, `status_kehadiran`, `waktu_scan_masuk`, `no_antrian_hari_ini`, `tanggal_antrian`, `waktu_input`) VALUES
(49, NULL, 'Aldion Ihza Pratama', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Pelajar/Mahasiswa', '', NULL, NULL, 'Hadir', NULL, 4, '2025-07-03', '2025-07-02 21:00:45'),
(50, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Wiraswasta', '', NULL, NULL, 'Hadir', NULL, 5, '2025-07-03', '2025-07-02 21:01:07'),
(51, NULL, 'Aldion ihza pratama 3', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Pegawai/Guru', '', NULL, NULL, 'Hadir', NULL, 6, '2025-07-03', '2025-07-02 21:01:14'),
(52, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Pelayanan Statistik Terpadu', 'Aparat Desa/Kelurahan', '', NULL, NULL, 'Hadir', NULL, 7, '2025-07-03', '2025-07-02 21:01:45'),
(55, 2147483647, 'Leni mahanani', 'lenimahanani82@gmail.com', 'Jalan sunda kapal brown no.15', 'Penawaran Kerja Sama', 'Lainnya', '+62 812-7937-9514', NULL, 4, 'Hadir', '2025-07-03 06:07:42', 10, '2025-07-03', '2025-07-02 23:07:42'),
(56, 2147483647, 'Catur Priyanto', 'cpriyanto205@gmail.com', 'Jln. Nangka 3 no 35 Korpri Jaya Kec. Sukarame', 'Diskusi/Koordinasi Kegiatan Statistik', 'Lainnya', '+62 085-6647-14137', NULL, 5, 'Hadir', '2025-07-03 06:16:53', 11, '2025-07-03', '2025-07-02 23:16:53'),
(57, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Wiraswasta', '', NULL, 5, 'Hadir', '2025-07-03 22:12:04', 12, '2025-07-03', '2025-07-03 15:12:04'),
(58, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Aparat Desa/Kelurahan', '', NULL, 1, 'Hadir', '2025-07-03 22:13:36', 13, '2025-07-03', '2025-07-03 15:13:36'),
(59, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Diskusi/Koordinasi Kegiatan Statistik', 'Aparat Desa/Kelurahan', '', NULL, 2, 'Hadir', '2025-07-03 22:13:53', 14, '2025-07-03', '2025-07-03 15:13:53'),
(60, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Aparat Desa/Kelurahan', '', NULL, 3, 'Hadir', '2025-07-03 22:14:24', 15, '2025-07-03', '2025-07-03 15:14:24'),
(61, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Aparat Desa/Kelurahan', '', NULL, 3, 'Hadir', '2025-07-03 22:14:43', 16, '2025-07-03', '2025-07-03 15:14:43'),
(62, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Diskusi/Koordinasi Kegiatan Statistik', 'Aparat Desa/Kelurahan', '', NULL, 3, 'Hadir', '2025-07-03 22:14:57', 17, '2025-07-03', '2025-07-03 15:14:57'),
(63, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Aparat Desa/Kelurahan', '', NULL, 2, 'Hadir', '2025-07-03 22:15:14', 18, '2025-07-03', '2025-07-03 15:15:14'),
(64, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Pelayanan Statistik Terpadu', 'Aparat Desa/Kelurahan', '', NULL, 4, 'Hadir', '2025-07-03 22:15:32', 19, '2025-07-03', '2025-07-03 15:15:32'),
(65, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Aparat Desa/Kelurahan', '', NULL, 4, 'Hadir', '2025-07-03 22:15:46', 20, '2025-07-03', '2025-07-03 15:15:46'),
(66, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Aparat Desa/Kelurahan', '', NULL, 4, 'Hadir', '2025-07-03 22:16:48', 21, '2025-07-03', '2025-07-03 15:16:48'),
(67, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Aparat Desa/Kelurahan', '', NULL, 5, 'Hadir', '2025-07-03 22:17:16', 22, '2025-07-03', '2025-07-03 15:17:16'),
(68, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Aparat Desa/Kelurahan', '', NULL, 5, 'Hadir', '2025-07-03 22:17:36', 23, '2025-07-03', '2025-07-03 15:17:36'),
(69, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Pelayanan Statistik Terpadu', 'Aparat Desa/Kelurahan', '', NULL, 5, 'Hadir', '2025-07-03 22:17:53', 24, '2025-07-03', '2025-07-03 15:17:53'),
(70, NULL, 'dionksak', 'acs@gmail.comd', 'efefwev', 'Rapat/Pertemuan', 'Wiraswasta', '0823981238912', NULL, NULL, 'Hadir', NULL, 25, '2025-07-03', '2025-07-03 16:21:04'),
(71, NULL, 'dionksak', 'acs@gmail.comd', 'efefwev', 'Rapat/Pertemuan', 'Wiraswasta', '0823981238912', NULL, NULL, 'Hadir', NULL, 26, '2025-07-03', '2025-07-03 16:21:59'),
(72, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Wiraswasta', '', NULL, 5, 'Hadir', '2025-07-03 23:23:14', 27, '2025-07-03', '2025-07-03 16:23:14'),
(73, NULL, 'sddvsdd', 'dvsdvsv@gmail.com', 'dvsvsd', 'Penawaran Kerja Sama', 'Mitra BPS', '32193812938912', NULL, NULL, 'Hadir', NULL, 28, '2025-07-03', '2025-07-03 16:26:41'),
(74, NULL, 'sfada', 'ajdk@gmail.com', 'asdada', 'Penawaran Kerja Sama', 'Mitra BPS', '0801380138`', NULL, NULL, 'Hadir', NULL, 29, '2025-07-03', '2025-07-03 16:46:08'),
(75, NULL, 'sfada', 'ajdk@gmail.com', 'asdada', 'Penawaran Kerja Sama', 'Mitra BPS', '0801380138`', NULL, NULL, 'Hadir', NULL, 30, '2025-07-03', '2025-07-03 16:48:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_tamu`
--
ALTER TABLE `master_tamu`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `master_tamu`
--
ALTER TABLE `master_tamu`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483678;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

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
