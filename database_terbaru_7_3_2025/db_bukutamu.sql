-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 07:52 AM
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
  `id` bigint(200) NOT NULL,
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
(2147483666, 'ARIEF ARPHAN', 'ariefajja228@gmail.com', '+62 895-6219-20914', 'Jl.teluk Ambon GG garuda', 'Wirausaha', 5, '2025-07-03 14:45:30'),
(2147483667, 'Faidah utami', 'faidahutami2022@gmail.com', '+62 085-2696-69391', 'Kp. Karang Maritim RT.04 Lk 2 Kel. Karang maritim kec. Panjang', 'Lainnya', 5, '2025-07-03 14:46:09'),
(2147483668, 'AGUS IRAWAN', 'gusirdusuqiyah08@gmail.com', '+62 831-7849-4568', 'KP. SINAR GUNUNG LK III RT. 010 KELURAHAN PANJANG SELATAN KECAMATAN PANJANG KOTA BANDAR LAMPUNG PROVINSI LAMPUNG', 'Lainnya', 5, '2025-07-03 14:46:54'),
(2147483669, 'MULYANTO', 'Mulyanto.aboen@gmail.com', '+62 812-7265-4841', '\"Jalan Teuku Umar Gg.Putra No.54 RT.003\r\n Lk. 1 Kel. Sawah Brebes Kec. Tanjung Karang Timur Bandar Lampung.\"', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:47:35'),
(2147483670, 'Zul Tanu Reza', 'zultanureza79@gmail.com', '+62 819-5746-1802', 'Jalan Putri Balau No 60 Lk I', 'Aparat Desa/Kelurahan', 5, '2025-07-03 14:48:49'),
(2147483671, 'Khojin Taliban', 'khojintaliban@gmail.com', '+62 813-6784-5086', 'JL. H. SYARIF, no.23, Kalibalau Kencana, Kedamaian, Bandar Lampung.', 'Lainnya', 5, '2025-07-03 14:49:27'),
(2147483672, 'Aji Prayoga Wibowo', 'Ajiprayogaw@gmail.com', '+62 896-4882-5126', 'Jl. Wolter Monginsidi gang Permai III NO.20, Kelurahan Talang, Kecamatan Teluk Betung Selatan', 'Wirausaha', 5, '2025-07-03 14:50:17'),
(2147483673, 'Nurjaman', 'putrieaja01@gmail.com', '+62 895-6097-09088', 'Jl jati gg. Markisa no.10 Tanjung raya', 'Lainnya', 5, '2025-07-03 14:51:06'),
(2147483674, 'Marlina', 'linamar00341@gmail.com', '+62 812-7152-6727', 'Jl.Cut mutia gg. Sriwijaya I RT 18 LK I Kelurahan Gulak Galik Kecamatan TBU', 'Lainnya', 5, '2025-07-03 14:51:41'),
(2147483675, 'Marni junita,SE', 'dede.marni90@gmail.com', '+62 852-6872-1901', 'Jln, dr Harun 1Gg.H wirna no 126 kota baru', 'Mengurus Rumah Tangga', 5, '2025-07-03 14:52:23'),
(2147483676, 'FAZRIE MULIA', 'fazri.henny@gmail.com', '+62 812-9725-5347', 'Jl. Durian II No.10 Rt.03 Lk.1 Durian Payung, Tanjung Karang Pusat, Kota Bandar Lampung', 'Lainnya', 5, '2025-07-03 14:53:01'),
(2147483677, 'Hendri Leo', 'leohendri9@gmail.com', '+62 896-3066-2364', 'Jl. H. Komarudin Gg. By Pass Raya 8 No. 15, RT. 005, Rajabasa Raya, Rajabasa, Bandar Lampung, Lampung', 'Lainnya', 5, '2025-07-03 14:53:37'),
(2147483678, 'ADE ANDRIANSYAH', 'Adheandrian91@gmail.com', '+62 852-1210-4128', 'Perum Karunia Indah Blok K no 7 Sukabumi Indah Bandar Lampung', 'Wiraswasta', 5, '2025-07-06 02:20:45'),
(2147483679, 'nana supriyatna', 'nana.supriyatna54@gmail.com', '+62 083-7183-0333', 'Jl. Kenari No.41 RT.007 LK.I Kelurahan Pelita Kecamatan Enggal', 'Honorer', 5, '2025-07-06 02:21:46'),
(2147483680, 'Yudi Effendi', 'yudi.yd68@gmail.com', '+62 852-6684-4988', 'Jl S Parman Gg Sumur Pucung II No.45', 'Wiraswasta', 5, '2025-07-06 02:22:45'),
(2147483681, 'Rini septiawati', 'Riniseptiawati@gmail.com', '+62 822-7995-7593', 'Jl pajajaran no 152 rt  01 lk 3 gunung sulah', 'Lainnya', 5, '2025-07-06 02:26:53'),
(2147483682, 'LISMAWATI', 'Lismaa270@gmail.com', '+62 081-3694-33218', 'Jl.amd.Temenggung gg.turunan II lk II RT 004 Kel. Gunung Sulah Kec.Way Halim', 'Lainnya', 5, '2025-07-06 02:27:34'),
(2147483683, 'Ferdiyanto', 'Ferdiotnay@gmail.com', '+62 822-8173-4472', '\"Perum Griya Abdi Negara Blok A.14 no 3\r\nKelurahan Sukabumi Kecamatan Sukabumi \r\nKota Bandar Lampung\"', 'Lainnya', 5, '2025-07-06 02:28:19'),
(2147483684, 'Salwa Adyti Oktaviani', 'salwaadyti25@gmail.com', '+62 953-6363-0464', 'Jl.Pulau Bangka Prum karunia indah blok K No.9 Sukabumi, Bandar Lampung', 'Pelajar/Mahasiswa', 5, '2025-07-06 02:28:57'),
(2147483685, 'Mayang sari', 'mayang12359@gmail.com', '+62 085-8385-16841', 'Jl. Terusan P bawean 2 sukarame LK 1', 'Lainnya', 5, '2025-07-06 02:29:48'),
(2147483686, 'Taufikri Mauluddin', 'taufikrianang4080@gmail.com', '+62 821-8149-1651', 'Perum Korpri Blok D8 No 16 RT 06 LK 1', 'Lainnya', 5, '2025-07-06 02:30:30'),
(2147483687, 'Zuliana Syani', 'zuliana.syani@gmail.com', '+62 853-7795-9955', 'Jl. Pulau Damar Gg. Sapta Marga No.18 Blok.c16 Kel. Way Dadi Baru Kec. Sukarame Kota Bandar Lampung', 'Lainnya', 5, '2025-07-06 02:31:10'),
(2147483688, 'MUHAMMAD BUSTANUL ARIFIN', 'bustanula138@gmail.com', '+62 895-3209-13515', 'Jl Mangga Raya No 33 LK I RT 001 Way Dadi Baru', 'Wiraswasta', 5, '2025-07-06 02:31:56'),
(2147483689, 'Syarifuddin', 'syarifuddin6996@gmail.com', '+62 085-2677-14245', 'Jl. Pembangunan no. 68. rt 04/01 korpri jaya kec. Sukarame', 'Lainnya', 5, '2025-07-06 02:32:35'),
(2147483690, 'Istiqomah', 'isti0032@gmail.com', '+62 089-7407-0262', 'JL P DAMAR GG ARRAHMAN BLOK A.49 RT 11 LK 2 WAY DADI BARU', 'Lainnya', 5, '2025-07-06 02:33:18'),
(2147483691, 'Muhammad Ulummudin', 'm.ulumuddin96@gmail.com', '+62 896-6683-3338', 'Jl mangga no 3/28 b lk I waydadi baru sukarame bandar lampung', 'Lainnya', 5, '2025-07-06 02:33:53'),
(2147483692, 'Bambang Dimas Ermanto', 'Bambangdimas90@gmail.com', '+62 896-9911-4723', 'Jalan Bumi Manti 2 Gg.Hi.Zakaria 1 No. 38B RT.004', 'Lainnya', 5, '2025-07-06 02:34:53'),
(2147483693, 'Rezlya Fitri Siregar', 'rezlyafitri1999@gmail.com', '+62 081-3663-58707', 'Jl musyawarah no 106 labuhan ratu raya', 'Lainnya', 5, '2025-07-06 02:36:06'),
(2147483694, 'Refli sudrajat', 'refli.sudrajat1@gmail.com', '+62 877-9385-8602', 'Jalan flamboyan 5 kel labuhan dalam kec tanjung senang bandarlampung', 'Lainnya', 5, '2025-07-06 02:36:48'),
(2147483695, 'Rofidatul hasnia', 'rofidatul44@gmail.com', '+62 081-2937-80536', 'Jln. Ra basyit gang serumpun 1 sinar Semendo, labuhan dalam kecamatan Tanjung senang, bandar lampung', 'Lainnya', 5, '2025-07-06 02:37:48'),
(2147483696, 'Ariansyah', 'syah.arian22@yahoo.co.id', '+62 822-8152-0889', 'Jl.Bunga Lili I Blok H7 No.08 Kel.Perumnas Way Kandis Kec.Tanjung Senang Kota Bandar Lampung', 'Lainnya', 5, '2025-07-06 02:38:21'),
(2147483697, 'Elga chantika sabila', 'elgachantikaaa@gmail.com', '+62 857-6665-4909', 'Jalan Pulau Sari V Nomor 89', 'Lainnya', 5, '2025-07-06 02:38:56'),
(2147483698, 'Dezha Yansyah Putra', 'dezhaputra18102000@gmail.com', '+62 823-7592-1939', 'Jalan Jambu Ujung RT 06 LK 1 Kelurahan Gedong Meneng Baru, Kecamatan Rajabasa, Bandar Lampung', 'Pelajar/Mahasiswa', 5, '2025-07-06 02:39:32'),
(2147483699, 'RIFQI PRADANAPUTRA HANAN', 'rifqiph@gmail.com', '+62 081-2725-84738', 'JALAN DIPANGGA SATYA NO.61 RAJABASA PEMUKA, RAJABASA', 'Lainnya', 5, '2025-07-06 02:40:14'),
(2147483700, 'Sariyantika Putri', 'Sariyantikaputri@gmail.com', '+62 838-3719-5040', 'Jl. Kapten abdul haq Gg. Masjid Nurussalam no. 32A', 'Lainnya', 5, '2025-07-06 02:41:03'),
(2147483701, 'Dian Ramadhan', 'ramadhan.dian13@gmail.com', '+62 812-7114-4774', 'Jl. ST. Badarudin Gg Al Hidayah 30 RT. 03 LK 2, Kelurahan Gunung, Kecamatan Langkapura, Bandar Lampung', 'Lainnya', 5, '2025-07-06 02:41:38'),
(2147483702, 'Muhamad kholdi', 'Muhamadkholdi@gmail.com', '+62 081-1091-2001', 'Way gubak', 'Lainnya', 5, '2025-07-06 02:42:17'),
(2147483703, 'Wahyu Maradona. SE', 'wahyu.maradona05@gmail.com', '+62 821-8006-5033', 'Jl. Pagar Alam gg. Aries', 'Lainnya', 5, '2025-07-06 02:43:01'),
(2147483704, 'Indra Putra', 'Indraputra999@gmail.com', '+62 852-7932-0425', 'Jl. Pendidikan Gang Budaya 3 No.33 RT 008 LK 1', 'Lainnya', 5, '2025-07-06 02:43:38'),
(2147483705, 'M Udhiek Masudi', 'doddiajjalah@gmail.com', '+62 812-7346-430', 'Jl Imam Bonjol Gg Bayur RT 06 LK 1 Kel Sumberrejo Kec Kemiling Kota Bandar Lampung', 'Lainnya', 5, '2025-07-06 02:44:10'),
(2147483706, 'Maya dianti', 'mayaindriansyah1@gmail.com', '+62 821-8105-2296', 'Jl.pancasila sakti gg forum Rt.15Lk.1 kel.sumberrejo kec.kemiling', 'Lainnya', 5, '2025-07-06 02:44:43'),
(2147483707, 'Iwan Sumardi', 'iwan25926@gmail.com', '+62 085-2674-52152', 'Jl. Nurul Islam Rt 005 Lk 2 Kel. Pinang Jaya Kec. Kemiling', 'Lainnya', 5, '2025-07-06 02:45:26'),
(2147483708, 'Mochamad Jamarudin', 'jamarudinmochamad@gmail.com', '+62 895-0460-0550', 'Jl. Bambu no. 19 RT. 001 LK. 1', 'Lainnya', 5, '2025-07-06 02:45:58'),
(2147483709, 'RAHMAN ERIC MAS\'UD', 'rahman.eric.msd@gmail.com', '+62 895-3520-5081', 'JL SAMRATULANGI GG BUNGSU 5 NO 19 PENENGAHAN RAYA KEDATON', 'Freelance', 5, '2025-07-06 02:46:33'),
(2147483710, 'Dewi Anggraini Puspita sari', 'dewianggraini2325@gmail.com', '+62 852-7918-2223', 'Jalan kiwi no 67/19 kelurahan sidodadi kecamatan kedaton Bandar lampung', 'Lainnya', 5, '2025-07-06 02:47:35'),
(2147483711, 'Rio Ronaldoa', 'riora1309@gmail.com', '+62 857-8350-1425', 'Jl. Kangguru no 27/9 sidodadi kedaton bandar lampung', 'Lainnya', NULL, '2025-07-06 02:48:21'),
(2147483712, 'Asrof Firmansyah', 'Asrof.firmansyah23@gmail.com', '+62 857-0974-6104', 'Jl. MS. Batu Bara, Gg.Kamboja No.45 Kec.TBU Kel.Kupang Teba, Bandar Lampung', 'Wiraswasta', 5, '2025-07-06 02:49:02'),
(2147483713, 'Uning Nurhayati', 'uningnurhayati04@gmail.com', '+62 857-6400-7354', 'Perum bilabong blok E5 no 12', 'Lainnya', 5, '2025-07-06 02:49:36'),
(2147483714, 'Supartini, A.md', 'Tinie.mss@gmail.com', '+62 823-5708-9333', 'JL. Nusantara no. 3 RT 03 LK 1', 'Lainnya', NULL, '2025-07-06 02:50:06'),
(2147483715, 'Isnawati', 'isnawatilampung18@gmail.com', '+62 813-7988-8051', 'Jln Singa no 52', 'Lainnya', 5, '2025-07-06 02:50:40'),
(2147483716, 'ayu gistiavena', 'gistiavena@gmail.com', '+62 853-7977-2424', 'jl. ms. batu bara gg. sedap malam no. 29', 'Lainnya', 5, '2025-07-06 02:51:10'),
(2147483717, 'Cammendea Audrey Emeraldine', 'ecaudrey@gmail.com', '+62 895-3232-06818', 'Jl pembangunan c no 70-120 LK 1', 'Lainnya', 5, '2025-07-06 02:52:56'),
(2147483718, 'Andre Okta Febrian Nurdin', 'aofnurdin@gmail.com', '+62 813-7959-8777', 'Jl sultan agung gang murai RT 002 LK I', 'Lainnya', 5, '2025-07-06 02:54:09'),
(2147483719, 'Ratih Subchiani', 'ratih.subchiani@gmail.com', '+62 853-6001-8079', 'Jl. Putri balau Gang pelopor RT.005 RW.000 No. 136', 'Lainnya', 5, '2025-07-06 02:54:55'),
(2147483720, 'Agil Selviya', 'agilselviya28@gmail.com', '+62 821-8350-9412', 'Jl H Agus Salim Gg Boy No 21 Lk ll', 'Lainnya', 5, '2025-07-06 02:55:38'),
(2147483721, 'Febiyola yolanda', 'yolandafebiyola@gmail.com', '+62 852-7331-3684', 'Jl m ali 1 rt 02 lk 01 no. 49', 'Lainnya', 5, '2025-07-06 02:56:18'),
(2147483722, 'Meli yusra', 'padang040454@gmail.com', '+62 822-8203-9554', 'Jln.wr.mongonsidi gg.arsad no.1', 'Lainnya', 5, '2025-07-06 02:57:03'),
(2147483723, 'Anita gustiana', 'gustianaanita4@gmail.com', '+62 895-4135-94563', 'Jl.kapten Abdul Haq GG.masjid nurussalam', 'Lainnya', 5, '2025-07-06 02:58:01'),
(2147483724, 'FIKAYANTI', 'galeridinda21@gmail.com', '+62 812-7846-8792', 'Jl.ir Soekarno Hatta kp.suka indah III RT 014 LK II Kel.Way Laga kec.Suka Bumi', 'Lainnya', 5, '2025-07-06 02:58:34'),
(2147483725, 'GUSTIAWATI', 'tifanorisky568@gmail.com', '+62 853-8358-4705', 'Jl.Purnawirawan Gg.Mushola RT 02', 'Lainnya', 5, '2025-07-06 02:59:10'),
(2147483726, 'Suyanti', 'suyanti.lpg@gmail.com', '+62 853-8096-9754', 'Jl kelurahan lk II RT 02', 'Lainnya', 5, '2025-07-06 02:59:53'),
(2147483727, 'Ahmad Erwin Alviansah', 'erwinalviansah@gmail.com', '+62 812-7209-9907', 'Jl Ms Batubara Gg Kamboja No 45 RT 008 LK I', 'Lainnya', 5, '2025-07-06 03:00:36'),
(2147483728, 'Suryani suci maryati', 'Kyaniekirani@gmail.com', '+62 821-2456-7802', 'Jln imam bonjol gg satria no 44 langkapura gunung agung', 'Lainnya', 5, '2025-07-06 03:01:13'),
(2147483729, 'DEKA HERLINA', 'linakia1730@gmail.com', '+62 237-3424-140', 'JL WR Supratman GG BERINGIN II LK II', 'Lainnya', 5, '2025-07-06 03:01:53'),
(2147483730, 'Mugiarto', 'mugiatomugimugi@gmail.com', '+62 812-8714-5509', 'Jl. Pembangunan no 33 RT 05 LK I', 'Buruh', 5, '2025-07-06 03:02:24'),
(2147483731, 'Yunida Fitri', 'yunidafitrilpg@gmail.com', '+62 822-8044-7927', 'Jl. M. Ali Gg. Jambu RT 002 LK 1', 'Lainnya', 5, '2025-07-06 03:03:11'),
(2147483732, 'Zulkarnain SH', 'izuldoang21@gmail.com', '+62 081-3796-54864', 'Jl. Gajah Mada GG. Purnawirawan no  16 kota baru Tanjung karang timur bandar Lampung', 'Lainnya', 5, '2025-07-06 03:04:02'),
(2147483733, 'Andi winarno', 'andigbrn1708@gmail.com', '+62 899-5974-111', 'Jl. Pramuka no. 15 RT 01 LK 1', 'Lainnya', 5, '2025-07-06 03:04:40'),
(2147483734, 'Aulia wahyudi', 'puspita21051986@gmail.com', '+62 083-8463-83918', 'Jl.ikan bawal GG.wahid RT 16 Lk III Kel.kangkung kec bumi waras Bandar lampung', 'Lainnya', 5, '2025-07-06 03:05:23'),
(2147483735, 'Nadya Amalia', 'nadyaamalia293@gmail.com', '+62 857-6820-5030', 'Jalan KH Agus Anang no 30 RT 01 kp Ketapang', 'Lainnya', NULL, '2025-07-06 03:06:05'),
(2147483736, 'Yunila Rumaningsih', 'yunilarumaningsih@gmail.com', '+62 831-7497-2985', 'Jl. Dosomuko gg. Pelita muda no 47 rt 07 lk 2 sawah brebes', 'Lainnya', 5, '2025-07-06 03:06:44'),
(2147483737, 'Ria desma yanti', 'ariariadesmayanti@gmail.com', '+62 812-7347-0431', 'Jl cirebon gg inpres no 57 sukarame II', 'Lainnya', 5, '2025-07-06 03:07:24'),
(2147483738, 'SRI INDRAWATI', 'indrawatis476@gmail.com', '+62 089-6060-02038', 'Jalan Ir Sutami Kampung Baru', 'Lainnya', 5, '2025-07-06 03:07:56'),
(2147483739, 'Dhiya Salsabila Putri', 'dbilaputri@gmail.com', '+62 895-6216-08349', 'Perumahan Bukit Mas Permai Blok B2 No.3', 'Lainnya', 5, '2025-07-06 03:08:28'),
(2147483740, 'ELLA PUSPITA', 'ellamisyal@gmail.com', '+62 899-3018-135', 'Jl.Bung Tomo no 36', 'Lainnya', 5, '2025-07-06 03:09:09'),
(2147483741, 'Arief Wibisono', 'ariefwibi055@gmail.com', '+62 823-8070-0303', 'Jl.Singa no.52 RT.008 LK III', 'Lainnya', 5, '2025-07-06 03:09:44'),
(2147483742, 'DIAN NARULITA SARI', 'narulitasaridian0@gmail.com', '+62 821-7849-6904', 'Kp.baru 3 Panjang utara', 'Lainnya', 5, '2025-07-06 03:10:21'),
(2147483743, 'Sulastri handayani', 'lastricantik289@gmail.com', '+62 895-3481-14124', 'Jl Dr Cipto Mangunkusumo gg.melati 1 no 01 Kel sumur batu kec teluk Betung utara', 'Lainnya', 5, '2025-07-06 03:10:58'),
(2147483744, 'Syarafina Husna', 'Syarafinahusna7@gmail.com', '+62 821-7689-8741', 'Perum Karunia Indah Blok K7', 'Lainnya', 5, '2025-07-06 03:11:40'),
(2147483745, 'Reza Aditya', 'rezauaditya@gmail.com', '+62 895-2087-8847', 'Jln. Pulau Singkep Gg. Jangkar', 'Lainnya', 5, '2025-07-06 03:12:12'),
(2147483746, 'Ika Rini', 'ikariniriwan25@gmail.com', '+62 821-7609-4466', 'Jl. Selat Malaka V, LK II Rt 010, Kampung Teluk Jaya', 'Lainnya', 5, '2025-07-06 03:12:49'),
(2147483747, 'ELLIS NOFIANTI', 'ellisaswani@gmail.com', '+62 897-5784-123', 'Jl. M. Ali RT. 001 LK. I', 'Lainnya', 5, '2025-07-06 03:13:20'),
(2147483748, 'Piga Anugerah Putra', 'pigaadri@gmail.com', '+62 858-4033-9803', 'Jl. Pulau Kelagian No 7', 'Lainnya', 5, '2025-07-06 04:00:34'),
(2147483749, 'Sri Marwati', 'mawarsri750@gmail.com', '+62 813-6978-9430', 'Jl. Cut Mutia GG Jepara no 13 RT/RW 020/-, Kel. Gulak-galik, T.Betung B. Lampung', 'Lainnya', 5, '2025-07-06 04:01:15'),
(2147483750, 'Othman Mahmud', 'othman.mahmud@gmail.com', '+62 853-6947-8640', 'Jalan Lada VII No. 36 RT 001 RW 01', 'Buruh', 5, '2025-07-06 04:01:43'),
(2147483751, 'Afni Maisaroh', 'afnimei2903@gmail.com', '+62 899-4276-966', 'Jl. Purnawirawan no 126/65 Gunung Terang, Langkapura,B Lampung', 'Lainnya', 5, '2025-07-06 04:02:13'),
(2147483752, 'Rio Arlian Ronie', 'rioarlian78@gmail.com', '+62 821-8418-2264', 'Perumahan karunia indah blok L no. 23', 'Lainnya', 5, '2025-07-06 04:03:11'),
(2147483753, 'IDA WATI', 'idaidaaja2610@gmail.com', '+62 813-6803-9960', 'Jl. Pajajaran Gg. Kemuning No.20 Kel. Jagabaya II Kec. Way Halim Bandar Lampung', 'Lainnya', 5, '2025-07-06 04:03:38'),
(2147483754, 'Rahma yanti', 'agusvanbasten90@gmail.com', '+62 896-0276-9298', 'Jalan.basuki Rahmat no.12 teluk Betung Selatan , Bandra lampung', 'Lainnya', 5, '2025-07-06 04:04:07'),
(2147483755, 'Dede vitriah', 'vitriahasep18@gmail.com', '+62 858-3324-2787', 'Jalan ikan mas gang mansur RT 023 Lingkungan 3', 'Lainnya', 5, '2025-07-06 04:04:40'),
(2147483756, 'Dewi Darmayanti', 'ddarmayanti10@gmail.com', '+62 813-6906-4300', 'Jl Gajahmada GgPurnawirawan No.16  Bandarlampung', 'Aparat Desa/Kelurahan', 5, '2025-07-06 04:05:16'),
(2147483757, 'Susi lestari', 'lestaris784@gmail.com', '+62 812-7229-0739', 'Jalan pekon ampai no 67 rt.2 lk.1', 'Lainnya', 5, '2025-07-06 04:05:47'),
(2147483758, 'NOVA MAULINA', 'novamaulina1986@gmail.com', '+62 851-5666-8381', 'Jl Ms Batubara Gg kamboja No 45 RT 008 LK I', 'Lainnya', 5, '2025-07-06 04:06:14'),
(2147483759, 'Adelia Putri Ramadani', 'adeliaputriramadani03@gmail.com', '+62 896-2868-2540', 'Jalan Alimuddin Umar, Gg. Rawan', 'Freelance', 5, '2025-07-06 04:06:42'),
(2147483760, 'Haryadi', 'yhar2439@gmail.com', '+62 813-6904-3397', 'Jl. Raja Ratu Gg. Sejahtera III no 65 RT. 012 LK.I', 'Lainnya', NULL, '2025-07-06 04:07:16'),
(2147483761, 'Seira Agitdini Setioningrum', 'agitseira@gmail.com', '+62 895-7040-05408', 'Perum Nusantara Permai Blok D2 No 7', 'Lainnya', 5, '2025-07-06 04:07:47'),
(2147483762, 'Dwi meliarni putri', 'dwimeliarniputri@gmail.com', '+62 813-7900-4026', 'Jl h agus salim gg langgeng no 8', 'Lainnya', 5, '2025-07-06 04:08:16'),
(2147483763, 'Kartikasari Indah Izzati', 'kartikasariindah83@gmail.com', '+62 813-6658-4439', 'Jl. Sukardi Hamdani Palapa Vc No 48B', 'Lainnya', 5, '2025-07-06 04:08:50'),
(2147483764, 'Fitri Nilasari', 'fittri.nilasari@gmail.com', '+62 821-7589-7063', 'Jl. Mangga Gg Famili no.22, Waydadi Baru, Sukarame', 'Lainnya', 5, '2025-07-06 04:09:17'),
(2147483765, 'AJI FAJAR NUGRAHA', 'ajifajar23@gmail.com', '+62 851-5540-7657', 'Jl. Dr Sutomo no 18A RT/RW 002/000', 'Freelance', 5, '2025-07-06 04:09:54'),
(2147483766, 'Halima', 'halimabimantaka@gmail.com', '+62 089-8290-8492', 'Jalan Nusa jaya gg Nusa 2 Rt 006 Lk 1', 'Lainnya', 5, '2025-07-06 04:10:49'),
(2147483767, 'Wahyu Marifia Ningsih', 'marifianingsih1979@gmail.com', '+62 896-9835-6262', 'Jl. Jati GG. Markisa No 10, Kel. Tanjung Raya, Kec. Kedamaian Kota Bandar Lampung', 'Lainnya', 5, '2025-07-06 04:11:22'),
(2147483768, 'DIANA DIAN SAPUTRI', 'dian80594@gmail.com', '+62 853-8260-1568', 'Jl P Singkep P Dwi Karya Indah Blok B 12', 'Lainnya', 5, '2025-07-06 04:11:51'),
(2147483769, 'Farida', 'ummuiqra07@gmail.com', '+62 085-8399-83800', 'Kp.Karang jaya RT 12 Lk 1', 'Lainnya', 5, '2025-07-06 04:12:26'),
(2147483770, 'Danial fahrozi', 'Danial90.fahrozi@gmail.com', '+62 812-7276-5940', 'Jl kemuning no 44 sinar semendo', 'Lainnya', 5, '2025-07-06 04:12:55'),
(2147483771, 'NIKMAH BAQIYYATUS SHALIHAH', 'nikmahsoleha89@gmail.com', '+62 896-6233-8457', 'Jl. Pulau Damar Gg Arrahman no. 8 Blok A. 22', 'Lainnya', 5, '2025-07-06 04:13:36'),
(2147483772, 'SUMIATI  KM', 'Umiqai08@gmail.com', '+62 853-8373-1617', 'Jl.Bakau Gg herbras No.12', 'Lainnya', 5, '2025-07-06 04:14:07'),
(2147483773, 'ANI MALIYAH', 'animaliyah952@gmail.com', '+62 089-5047-33171', 'Jalan teluk Ambon GG garuda no 39 RT 02 LK 02', 'Lainnya', 5, '2025-07-06 04:14:37'),
(2147483774, 'Tressa Syafitri', 'tressasyafitri07@gmail.com', '+62 858-0950-4221', 'Jl S Parman Gg Sumur Pucung II No.45', 'Lainnya', 5, '2025-07-06 04:15:04'),
(2147483775, 'Romi Ramadhani', 'romiramadhani88@gmail.com', '+62 853-6766-1520', 'Jln.  Sukarno hatta kp.  Suka indah 3 rt 014', 'Buruh', 5, '2025-07-06 04:15:32'),
(2147483776, 'Kowinarni', 'kowinarni3@gmail.com', '+62 895-6405-60184', 'Kp.teluk jaya no.45 lk.II rt.006', 'Lainnya', 5, '2025-07-06 04:16:04'),
(2147483777, 'Rahayu Meria Sari', 'Sari.lpg123@gmail.com', '+62 082-2824-90584', 'Jl.Raden Intan Gg. Kenari no.24', 'Lainnya', 5, '2025-07-06 04:16:37'),
(2147483778, 'Like Yuanita Dewi', 'likeyuanitadewi@gmail.com', '+62 822-8110-5519', 'Jl. Kimaja No 26', 'Lainnya', 5, '2025-07-06 04:17:10'),
(2147483779, 'ANIES WIDYASTUTI', 'anieswidyastuti@gmail.com', '+62 857-6683-4044', 'Jl. Terusan ragom gawi 3 perum sakura residence blok e23', 'Lainnya', 5, '2025-07-06 04:17:43'),
(2147483780, 'WIWIK FERAWATI', 'wiwikfw25.wf@gmail.com', '+62 812-7845-9104', 'Jl. Selat Gasfar II No. 21 Kp. Baru 1', 'Lainnya', 5, '2025-07-06 04:18:09'),
(2147483781, 'NUR KAMISA', 'nur.kamisa02@gmail.com', '+62 896-7176-3590', 'Jl. Ikan Sebelah No.19 Rt.034 LK.III', 'Lainnya', 5, '2025-07-06 04:18:36'),
(2147483782, 'ULFA NURINI', 'ulfanurini46@gmail.com', '+62 821-7689-1899', 'KP. KARANG JAYA LK I RT 013', 'Lainnya', 5, '2025-07-06 04:19:10'),
(2147483783, 'Yuyun Diana', 'dianayuyun31@gmail.com', '+62 813-5114-0949', 'Jl. Benda GG. HJ. Aminfa LK II RT. 002 RW. 000', 'Lainnya', 5, '2025-07-06 04:19:37'),
(2147483784, 'Umi syamsiah', 'umisya1209@gmail.com', 'umisya1209@gmail.com', 'Jl bakau nomor 35 RT 12 LK I', 'Lainnya', 5, '2025-07-06 04:20:05'),
(2147483785, 'MARPUAH', 'marfuah0603@gmail.com', '+62 853-8096-4839', 'JL.BAKAU GG.WARU', 'Lainnya', 5, '2025-07-06 04:20:54'),
(2147483786, 'Nanik Sawitri', 'naniksawitri1@gmail.com', '+62 896-3420-8105', 'Jl Soekarno Hatta kp Bayur atas no27', 'Lainnya', 5, '2025-07-06 04:21:21'),
(2147483787, 'JURIANI', 'juriani082@gmail.com', '+62 089-6306-00755', 'Jl jati 1 no 4 Rt 17', 'Lainnya', 5, '2025-07-06 04:21:47'),
(2147483788, 'Cindy Anjani Putri', 'cindianjani01@gmail.com', '+62 895-3181-5740', 'Jl. Kiwi Gg Kiwi V No 35', 'Lainnya', 5, '2025-07-06 04:22:19'),
(2147483789, 'Elwinda Purnama Sari', 'elwindaanjas@gmail.com', '+62 813-6820-6852', 'Jl ikan layur no 18 lk 2 blok c', 'Lainnya', 5, '2025-07-06 04:22:48'),
(2147483790, 'GIARTI', 'giarti290584@gmail.com', '+62 082-3718-50223', 'Jalan.teluk Lampung GG.Garuda No 100', 'Lainnya', 5, '2025-07-06 04:23:19'),
(2147483791, 'Indra Purnama', 'Purnamaindra2108@gmail.com', '+62 812-8544-4232', 'Jalan Pramuka Gang Alpukat', 'Lainnya', 5, '2025-07-06 04:23:47'),
(2147483792, 'GALUH DWI KARTIKA WICAKSONO', 'galuhdwi1305@gmail.com', '+62 896-3683-0155', 'Jl. Asoka Blok A4 no 3 Permata Biru LK I', 'Freelance', 5, '2025-07-06 04:24:13'),
(2147483793, 'SOFA INDAH MAWARTI', 'sofaindah09mei@gmail.com', '+62 812-6258-0069', 'JL. RE MARTADINATA LK I RT 004', 'Lainnya', 5, '2025-07-06 04:24:51'),
(2147483794, 'Astri Kumala Putri', 'putriachie3@gmail.com', '+62 089-8290-7170', 'Perum Kuripan Permai Blok A No. 15', 'Freelance', 5, '2025-07-06 04:25:26'),
(2147483795, 'EKA NOVITA SARI', 'ekabundaarsya@gmail.com', '+62 813-7939-3330', 'JL.PRAMUKA GG SIRSAK LK 1 RT 07', 'Lainnya', 5, '2025-07-06 04:25:56'),
(2147483796, 'Lilis Junaini', 'lilisjunaini0683@gmail.com', '+62 853-6668-3323_', 'Jl. Kartini gg duane no 5 Rt 009 LK 2 kelurahan palapa', 'Mengurus Rumah Tangga', 5, '2025-07-06 04:26:39'),
(2147483797, 'Neni kurniati', 'Nenik0210@gmail.com', '+62 895-6111-91110', 'Jl Soekarno-Hatta kp suka indah III Lk II RT 014/000 Kel Way Laga Kec SukabumiKel', 'Mengurus Rumah Tangga', 5, '2025-07-06 04:27:10'),
(2147483798, 'FACHRURROZI', 'fahruryanti12@gmail.com', '+62 857-8950-2064', 'Kp karang maritim Rt 01 Lk 2', 'Lainnya', 5, '2025-07-06 04:27:40'),
(2147483799, 'Ariansyah', 'ayie.foto17@gmail.com', '+62 813-6964-3212', 'Jl. Mangga no. 17', 'Lainnya', 5, '2025-07-06 04:28:06'),
(2147483800, 'Icha Ananda Putri', 'ichaananda2@gmail.com', '+62 821-7602-2646', 'Jalan Mekar Sari, Kedamaian Gg.Sadar 1 No.4', 'Lainnya', 5, '2025-07-06 04:28:39'),
(2147483801, 'Muhamad Afandi', 'afandi6660@gmail.com', '+62 896-7178-5016', 'Jl Wr Supratman no 46 gunung mas teluk betung selatan bandar lampung', 'Lainnya', 5, '2025-07-06 04:29:11'),
(2147483802, 'Lilis Kusuma Sari', 'Liliskusuma00@gmail.com', '+62 526-9282-993', 'Jl. Ikan julung Skip Rahayu LK 1 Rt 020', 'Lainnya', 5, '2025-07-06 04:29:57'),
(2147483803, 'Rukiah', 'rukiahkiarukiah@gmail.com', '+62 822-8194-6731', 'JLN WAY SEMANGKA NO 7 LK1', 'Lainnya', 5, '2025-07-06 04:30:29'),
(2147483804, 'Syanindhita Husna', 'syanindhitahusna30@gmail.com', '+62 853-8152-0695', 'Perumahan karunia indah blok k7', 'Lainnya', 5, '2025-07-06 04:30:57'),
(2147483805, 'PIPUK SRI MUMPUNI', 'pipuksrimumpuni@gmail.com', '+62 813-9390-5100', 'Jl. Selat gasfar II Kp. Baru 3 Rt.01/1 Panjang Utara', 'Lainnya', 5, '2025-07-06 04:31:27'),
(2147483806, 'Frans Bramasta', 'fransbramasta1005@gmail.com', '+62 857-8373-2472', 'Jl WR Supratman no. 16 RT 004', 'Lainnya', 5, '2025-07-06 04:32:00'),
(2147483807, 'Davina Ramadhanti Pratama P. H', 'ramadhantidavina@gmail.com', '+62 821-7511-2447', 'Jl.Ikan Bawal Gg.Kadar 2 No. 25 LK III RT 017', 'Lainnya', 5, '2025-07-06 04:32:24'),
(2147483808, 'Sella indah juwita', 'sellaindahjuwita@gmail.com', '+62 895-3274-00308', 'Jl. P seribu no 153 lk1 rt/rw 001 waydadi, kec. Sukarame bandar lampung', 'Buruh', 5, '2025-07-06 04:32:55'),
(2147483809, 'Denny Yuza Saputra', 'denyyuza100@gmail.com', '+62 812-7843-7557', 'Jl.P antasari gg hi thobari no 20 rt 01 rw II', 'Lainnya', 5, '2025-07-06 04:33:28'),
(2147483810, 'WIWIK NOFIYANTI', 'wiwiknofiyanti383@gmail.com', '+62 882-7676-2035', 'Jl. Ikan Julung Gg. Kamboja RT 017 LK I', 'Lainnya', 5, '2025-07-06 04:33:55'),
(2147483811, 'Liza Agustina', 'lizaagustina60@gmail.com', '+62 812-7942-9818', 'Jl.Mangga Gg.Wijaya No.20 RT.006/LK.II', 'Lainnya', 5, '2025-07-06 04:34:25'),
(2147483812, 'HALIMAH TUSAKDIAH', 'zakkymah@gmail.com', '+62 895-4127-42200', 'Jl sukma GG Damai no 6', 'Lainnya', 5, '2025-07-06 04:34:58'),
(2147483813, 'Nurhasan', 'animadonghua@gmail.com', '+62 857-0972-1517', 'Jl.RE.Martadinata No.14 LK II RT.019', 'Lainnya', 5, '2025-07-06 04:35:53'),
(2147483814, 'Novi Yanti', 'noviabid89@gmail.com', '+62 082-1788-76155', 'Sinar laut jl teluk Bone II lk 1', 'Lainnya', 5, '2025-07-06 04:36:35'),
(2147483815, 'MURSAN', 'mursanlpg@gmail.com', '+62 812-7216-6629', 'H safri lk1 Rt08 Srengsem', 'Buruh', 5, '2025-07-06 04:37:01'),
(2147483816, 'Ardiyantina,A.ma', 'ardiyantina1981@gmail.com', '+62 812-7957-9680', 'JL. Pulau buru no.67', 'Lainnya', 5, '2025-07-06 04:37:30'),
(2147483817, 'Dita yuni antika sari', 'Dyuniansa@gmail.com', '+62 857-6835-9072', 'Jl. Haji komarudin gang haji ismail 4 nomor 98 rt 05 rajabasaraya', 'Lainnya', 5, '2025-07-06 04:37:56'),
(2147483818, 'Lesi Aprianti', 'echie.imout@gmail.com', '+62 895-0522-8399', 'Jl.cengkeh tengah 4 no.249', 'Lainnya', 5, '2025-07-06 04:38:22'),
(2147483819, 'Bella Srikiswati', 'bellasrikiswati@gmail.com', '+62 081-2724-80139', 'Jl. HOS. Cokroaminoto gg.kamboja no. 39 RT. 01 LK. I Enggal', 'Lainnya', 5, '2025-07-06 04:39:28'),
(2147483820, 'Endang Yumiati', 'endangyumiati@gmail.com', '+62 858-4118-8304', 'Kp. Baruna Jaya RT 007 LK III', 'Lainnya', 5, '2025-07-06 04:39:58'),
(2147483821, 'Rukiti', 'rukitieffendi12@gmail.com', '+62 895-0890-5727', 'Perum tjg raya permai blok 14 no 3 Rt12 lk2', 'Lainnya', 5, '2025-07-06 04:40:25'),
(2147483822, 'Rezki Perwita Arum', 'perwitaarum2@gmail.com', '+62 896-3400-4104', 'Jalan pulau sari 6 no 137 perumnas way kandis tanjung senang bandar lampung', 'Lainnya', 5, '2025-07-06 04:40:52'),
(2147483823, 'Ayu Nurlisa', 'ayunurlisa130@gmail.com', '+62 882-8704-6422', 'Jalan Hoscokroaminoto Gg Kamboja III no.25, RT/RW 001/001', 'Lainnya', 5, '2025-07-06 04:41:36'),
(2147483824, 'PUTRA AULIA', 'putraaulia181@gmail.com', '+62 822-8271-7432', 'Jl.gunung Dempo no.200 perumnas way halim', 'Lainnya', 5, '2025-07-06 04:42:29'),
(2147483825, 'Leni munawari', 'Lenimunawari016@gmail.com', '+62 578-8523-499', 'Jln jendral suprapto gg langgar no14 rt 04', 'Lainnya', 5, '2025-07-06 04:43:02'),
(2147483826, 'Yulian alamsyah', 'bps.lampung26@gmail.com', '+62 897-8170-377', 'Kp.karang jaya.Rt.012 Lk1', 'Lainnya', 5, '2025-07-06 04:43:29'),
(2147483827, 'WAHYU KRISEETYANINGRUM', 'Titintitin909090@gmail.com', '+62 822-8249-0482', 'Jl. Pulau buru no 36', 'Lainnya', 5, '2025-07-06 04:43:57'),
(2147483828, 'Aukhe Elmiransyah', 'Aukheelmiransyah10@gmail.com', '+62 898-1516-149', 'Jalan Nawawi Gelar Dalom Sukajaya', 'Lainnya', 5, '2025-07-06 04:44:26'),
(2147483829, 'APRIZAL', 'reyshaalifa0@gmail.com', '+62 831-5486-2790', 'Jl.laks re Martadinata no.50', 'Lainnya', 5, '2025-07-06 04:44:58'),
(2147483830, 'DIAN ANDRIYANI', 'dianandriyani969@gmail.com', '+62 813-7970-066', 'Jl. LadaVII no 19 perumnas way halim bandar lampung', 'Lainnya', 5, '2025-07-06 04:45:27'),
(2147483831, 'Hendra Gunawan', 'hendra.311264@gmail.com', '+62 831-7001-3704', 'Jl. Bumi manti 2 GG SKC VI NO 50 LK 2', 'Lainnya', 5, '2025-07-06 04:45:59'),
(2147483832, 'Titus kriswantoro', 'titustoro@gmail.com', '+62 823-7109-6461', 'Jl. Bumi Arta GG. Bumi Arta 4, way Kandis, tanjung seneng, bandar lampung', 'Lainnya', 5, '2025-07-06 04:46:35'),
(2147483833, 'SEPTIAN NADIRSYAH PUTRA', 'bondoel27@gmail.com', '+62 896-1694-7771', 'JL.TEUKU CIK DITIRO NO.1 RT.14 LK.I', 'Lainnya', 5, '2025-07-06 04:47:30'),
(2147483834, 'Lidia Soraya Apriani', 'lidia.sorayaapr@gmail.com', '+62 853-6943-4445', 'Jl. Pulau Sebesi Gg. Surya Darma No. 50B Rt. 006', 'Lainnya', 5, '2025-07-06 04:48:09'),
(2147483835, 'Nikodemus Prajnadibya Kurniawan', 'nikodemusprajna@gmail.com', '+62 813-7777-5510', 'Jl. Flamboyan IV no V Labuhan Dalam Tanjung Senang Bandar Lampung', 'Lainnya', 5, '2025-07-06 04:49:31'),
(2147483836, 'Roki andi saputra', 'rokiandi97@gmail.com', '+62 081-2730-14790', 'Jl. Indra Bangsawan Gg. Batin tihang pemuka Pramuka', 'Lainnya', 5, '2025-07-06 04:50:05'),
(2147483837, 'Aprili gledia', 'apriligledia@gmail.com', '+62 818-0888-4147', 'Jl pulau sari v no 89 perumnas way kandis', 'Lainnya', 5, '2025-07-06 04:50:49'),
(2147483838, 'SITI MUWAFIQOH FITRI', 'muwafiqoh234@gmail.com', '+62 857-8377-1300', 'Jl. Mangga no 3/28b Lk 1 RT 003', 'Lainnya', 5, '2025-07-06 04:51:22'),
(2147483839, 'Ithfa Harum Eka Pratiwi', 'ithfapratiwi94@gmail.com', '+62 895-3858-35805', 'Jln Slamet Riyadi 4 RT 001 LK 01', 'Lainnya', 5, '2025-07-06 04:51:59'),
(2147483840, 'Masliyah', 'liamasliyah058@gmail.com', '+62 838-9701-3567', 'Jl.jati gg bungur1 no.20 Rt.01 Lk 2', 'Lainnya', 5, '2025-07-06 04:52:34'),
(2147483841, 'MOHAMAD RIDHO RINALFI', 'ridhorinalvi@gmail.com', '+62 813-6904-4619', 'PERUM GRIYA CEMPAKA PERMAI 2 BLOK D NO 3', 'Wiraswasta', 5, '2025-07-06 04:53:13'),
(2147483842, 'HERMANSYAH', 'hermansyahromli91@gmail.com', '+62 821-7553-6049', 'Jl Nawawi Glr Dalom No 25 Sukajaya, LK 1, RT 04, Rajabasa Jaya, Rajabasa, Bandar Lampung', 'Lainnya', 5, '2025-07-06 04:53:54'),
(2147483843, 'Nadia Agustina', 'nadia.agustina90@gmail.com', '+62 897-6086-099', 'Jl.dr Susilo Gg.Pusri 2 No.26 RT. 018 LK II', 'Lainnya', 5, '2025-07-06 04:54:28'),
(2147483844, 'Merta Alyana', 'alyanamerta@gmail.com', '+62 895-3611-33024', 'Jl. Pramuka GG.Hi.Maherat Hamid No.62 RT 03 Lk 01', 'Lainnya', 5, '2025-07-06 04:55:04'),
(2147483845, 'DIANA SARI', 'Dianaasari31@gmail.com', '+62 822-8155-6040', 'JL CEMPAKA BLOK TD I NO.5 LK III', 'Lainnya', 5, '2025-07-06 04:55:51'),
(2147483846, 'Gustin Amelia Pratiwi', 'amelgstn.88@gmail.com', '+62 813-6712-7849', 'Jl. Danau Toba Lk III', 'Lainnya', 5, '2025-07-06 04:56:23'),
(2147483847, 'Siti Aisah', 'sitiaisyah7929@gmail.com', '+62 877-7713-5071', 'Jl. Rasuna Said Gg. Cendrawasih No. 24', 'Lainnya', 5, '2025-07-06 04:56:55'),
(2147483848, 'Eka sari asih', 'ekasari2379@gmail.com', '+62 853-7992-2990', 'Jalan Dr Cipto Mangunkusumo', 'Lainnya', 5, '2025-07-06 04:57:31'),
(2147483849, 'Yuanita Utari', 'yuanitautari150@gmail.com', '+62 089-6603-20721', 'Jln Purnawirawan GG impres langkapura', 'Lainnya', 5, '2025-07-06 04:58:03'),
(2147483850, 'Ektri Jumeltha', 'melthajumawan@gmail.com', '+62 853-6660-6430', 'Jl. Kopi Utara 3 no 114', 'Lainnya', 5, '2025-07-06 04:58:38'),
(2147483851, 'Lucia Aprianti', 'luciaaprianti99@gmail.com', '+62 882-7627-7358', 'Jl. Kiwi GG Kiwi V No 35', 'Lainnya', 5, '2025-07-06 04:59:09'),
(2147483852, 'DEPI DESWATI', 'depid128@gmail.com', '+62 813-6816-2848', 'jl.imam bonjol no.14 rt.02 lk.01', 'Lainnya', 5, '2025-07-06 04:59:44'),
(2147483853, 'Diva Hening Amartya', 'selamatdiva@gmail.com', '+62 857-8046-0487', 'Perum bukit mas permai blok F no 2 Sukabumi Bandarlampung', 'Lainnya', 5, '2025-07-06 05:01:01'),
(2147483854, 'Ria agustina', 'ryaalljasmine1319@gmail.com', '+62 896-3768-4434', 'Jalan sisingamanga raja gg.nuri no.49 rt 012 lk2', 'Lainnya', 5, '2025-07-06 05:03:01'),
(2147483855, 'Pesona Sophista Mulya', 'Psnashstamlya27@gmail.com', '+62 813-7793-0755', 'Jl. Sultan Agung Gg. M. Bangsawan No. 128', 'Freelance', 5, '2025-07-06 05:04:08'),
(2147483856, 'Muhammad Vahri Syahnur', 'muhammadvahrisyahnur@gmail.com', '+62 831-6944-8847', 'Jl. Imam Bonjol Gg. Prona RT 006 RW 001', 'Lainnya', 5, '2025-07-06 05:04:37'),
(2147483857, 'Fitriah', 'fitriahfifit11@gmail.com', '+62 852-6938-0171', 'Jl. Dr. Harun 1 no. 53', 'Lainnya', 5, '2025-07-06 05:07:42'),
(2147483858, 'Fance Yudha Affandi', 'yudha.affandii@gmail.com', '+62 896-5054-3361', 'Jl. Pulau Legundi Gg. Kenanga No.65', 'Lainnya', 5, '2025-07-06 05:10:06'),
(2147483859, 'NEVI HERIANI OKTAVIA, S.Pd', 'neviheriani96@gmail.com', '+62 899-9676-863', 'JL.HI.SAID PERUMAHAN SERVITIA BLOK A 18 RT 06 LK II', 'Wirausaha', 5, '2025-07-06 05:10:52'),
(2147483860, 'Agustia', 'agustia0616@gmail.com', '+62 899-6467-818', 'Jl.P.Kemerdekaan GG.Madlias II/14', 'Lainnya', 5, '2025-07-06 05:16:59'),
(2147483861, 'Nur Istiqomah', 'istiqomahnur711@gmail.com', '+62 878-4678-9743', 'Jl. MS Kramayudha RT 08 Lk I, Kel. Bakung, Kec. Telukbetung Barat Kota Bandar Lampung', 'Lainnya', NULL, '2025-07-06 05:17:33'),
(2147483862, 'NINA YULIANTI SUSANTI', 'yuliantinina34@gmail.com', '+62 822-7882-5299', 'Jl Gajah Mada Gg H Jamaludin', 'Lainnya', 5, '2025-07-06 05:18:03'),
(2147483863, 'Sarah Edma Putri', 'edmaputri0301@gmail.com', '+62 822-7924-1945', 'Jl. Pangeran Emir M. Noer Gg. Karya Muda 1 No. 36 RT. 004', 'Lainnya', 5, '2025-07-06 05:18:58'),
(2147483864, 'Dian Erna Mega sari', 'ernamegasaridian@gmail.com', '+62 083-1110-64202', 'jL.Dr.Harun 1no25 lk 01 RT 02', 'Lainnya', 5, '2025-07-06 05:21:42'),
(2147483865, 'Henamia tarigan', 'henamiatarigan@gmail.com', '+62 081-2822-11462', 'jl.panglima polem gg.mawar putih 1 no.64 rt.002/000 segala mider tanjung karang barat', 'Lainnya', 5, '2025-07-06 05:22:06'),
(2147483866, 'NURJANAH', 'casyadi040519@gmail.com', '+62 882-7411-5303', 'Jl.Teluk Bone lk.II RT.006 kotakarangraya', 'Lainnya', 5, '2025-07-06 05:22:41'),
(2147483867, 'FARHANAH KARTIKA OKTARIZKY', 'Ktika7791@gmail.com', '+62 896-8516-2512', 'Jl.WA.Rahman No.10 RT.06 LK.01 Kel. Batu Putuk Kec.TBB', 'Lainnya', 5, '2025-07-06 05:23:09'),
(2147483868, 'RONAN FASHA ANANTA', 'fasharonan@gmail.com', '+62 821-8335-2114', 'JL.RATU DIBALAU GG.CIPTO NO.38', 'Lainnya', 5, '2025-07-06 05:23:38'),
(2147483869, 'Ditya Riyadi', 'riyadiditya@gmail.com', '+62 821-7915-7460', 'jalan Mayor Salim Batubara Gang Sedap Malam No. 38', 'Lainnya', 5, '2025-07-06 05:24:07'),
(2147483870, 'fanni kurniawan', 'bina.ever99@gmail.com', '+62 882-7755-2095', 'perum bkp blow w no 133', 'Lainnya', NULL, '2025-07-06 05:24:40'),
(2147483871, 'evi harmini', 'eviharmini3383@gmail.com', '+62 821-8288-8511', 'Jl. Perumda 1 no. 2281 rt. 28 rw.08', 'Lainnya', 5, '2025-07-06 05:25:05'),
(2147483872, 'YOLANDA RIZKI AMALIA', 'yolandariski0@gmail.com', '+62 895-3211-13270', 'Jl. raya Prokimal, kel. Kali Cinta RT001/RW001, kec. Kotabumi Utara, kab. Lampung Utara, Lampung, 34511', 'Lainnya', 5, '2025-07-06 05:25:33'),
(2147483873, 'AZZAHRA SOFIA MURSIDAH', 'azzahra.sofia77@gmail.com', '+62 895-1707-4204', 'Jl. Pramuka Perum Bumi Puspa Kencana 3 Blok J No.23 Rajabasa, Bandar Lampung, Lampung', 'Lainnya', 5, '2025-07-06 05:26:00'),
(2147483874, 'Rizky Pinkkan Saputra', 'rizkyps2022@gmail.com', '+62 082-1787-95697', 'Jl P Singkep P Dwi Karya Indah Blok B no 12, RT 08 LK II', 'Lainnya', 5, '2025-07-06 05:26:28'),
(2147483875, 'Tiara Sari', 'tiarasarii1503@gmail.com', '+62 896-3218-6825', 'JL.RE MARTADINATA GG.HARNAS.LKI', 'Lainnya', NULL, '2025-07-06 05:27:11'),
(2147483876, 'Yuliana Intan Nurmala', 'ami.intanyuliana@gmail.com', '+62 813-7936-8877', 'Perum Bukit Mas Permai Blok H3 Sukabumi', 'Lainnya', 5, '2025-07-06 05:27:38'),
(2147483877, 'Nadya Herdina Putri', 'nadyaherdinaputri28@gmail.com', '+62 821-7538-7915', 'Jalan Bung Tomo Komplek RRI LK II', 'Lainnya', 5, '2025-07-06 05:28:06'),
(2147483878, 'Dian Miranti', 'Mirantidian49@gmail.com', '+62 857-8317-3108', 'Jalan.Ir.H.Juanda, Teba, Kotaagung Timur.', 'Lainnya', 5, '2025-07-06 05:28:36'),
(2147483879, 'Husna Jatsiyah', 'husnajatsiyah19@gmail.com', '+62 822-8233-5313', 'Jalan Pramuka Gang Jambu 1', 'Lainnya', 5, '2025-07-06 05:29:05'),
(2147483880, 'Retno Ayu Miranti', 'Retnomiranti313@gmail.com', '+62 082-2825-85076', 'Jl. Pulau Raya 1, No. 72 Perumnas Way Kandis Tanjung Senang, Bandar Lampung', 'Lainnya', 5, '2025-07-06 05:29:30'),
(2147483881, 'Endang Herawati', 'endangherawati790@gmail.com', '+62 812-7249-6393', 'JL. Gatot Subroto GG. Wongso No. 11 Kel. Tanjung Gading', 'Lainnya', 5, '2025-07-06 05:30:00'),
(2147483882, 'Rahayu Purwani', 'rahayuprwn@gmail.com', '+62 896-3006-9382', 'Jalan K. H. Mas Mansyur No. 28', 'Lainnya', 5, '2025-07-06 05:30:38'),
(2147483883, 'Della Amanda s pandia', 'Jae.kyu9@gmail.com', '+62 822-8092-6932', 'Jalan sutoyo no. 5 RT. 04 Lk. 2', 'Wirausaha', 5, '2025-07-06 05:31:21'),
(2147483884, 'Sri Bulan Roma Intan', 'bulanintan92@gmail.com', '+62 821-8688-9997', 'Jalan Pulau Singkep', 'Lainnya', 5, '2025-07-06 05:31:51'),
(2147483885, 'Reza Oktavia', 'rezaoktavia321@gmail.com', '+62 897-9510-285', 'Jl. Teluk Ambon Gg. Rajawali RT.002', 'Lainnya', 5, '2025-07-06 05:32:24'),
(2147483886, 'Muhamad Akrom Hasani', 'makromhsn@gmail.com', '+62 831-7024-6329', 'Jl Chairil Anwar No 21 LK II', 'Lainnya', 5, '2025-07-06 05:32:49'),
(2147483887, 'SHERLY ALFIRO NURSHAFA\'AT', 'sherlyalfiro18@gmail.com', '+62 823-7364-2736', 'Jl. Angkasa II, No.75', 'Lainnya', 5, '2025-07-06 05:33:36'),
(2147483888, 'ALFINUR', 'alfinur.npm2051010230@gmail.com', '+62 859-4662-7134', 'JL. DRS WARSITO GG. DEMPO NO.10', 'Lainnya', NULL, '2025-07-06 05:34:03'),
(2147483889, 'NUR FADILAH', 'fnur06950@gmail.com', '+62 898-4136-176', 'Jl.H.Agus Salim Gg. Bengkel No.53', 'Lainnya', 5, '2025-07-06 05:34:34'),
(2147483890, 'ADI SHAMBONO, S.Sos', 'adishambono88@gmail.com', '+62 831-7893-6597', 'JL. Teluk Ambon LK. II RT. 009', 'Lainnya', 5, '2025-07-06 05:35:15'),
(2147483891, 'Niranda Audine', 'nirandaaudine@gmail.com', '+62 852-7311-9846', 'Perumahan Karunia Indah Blok E1 No 19 RT 12 LK lll', 'Lainnya', 5, '2025-07-06 05:35:42'),
(2147483892, 'Annis Sholikha Putri', 'annissholikha999@gmail.com', '+62 895-2913-1976', 'Jl. Ratu dibalau gg teratai No 64 RT 04 LK II Way kandis', 'Lainnya', 5, '2025-07-06 05:36:19'),
(2147483893, 'Wahyu Mauludah', 'yayukwahyu2002@gmail.com', '+62 821-8415-5327', 'Jl PB MARGA RT 03 LK I', 'Lainnya', 5, '2025-07-06 05:36:53'),
(2147483894, 'Faizal Azhar', 'faizalazgg@gmail.com', '+62 812-7478-9039', 'Jalan Basuki Rahmat Gang. Kaswari No.30', 'Lainnya', 5, '2025-07-06 05:37:40'),
(2147483895, 'Rahman Setiawan', 'rahmansetiawan.2904@gmail.com', '+62 878-9978-1735', 'JALAN WR. MONGINSIDI GANG ARSYAD NO. 28 RT. 010 LK. II', 'Lainnya', 5, '2025-07-06 05:38:08'),
(2147483896, 'Septiana Maisaroh', 'yanasepti598@gmail.com', '+62 895-3673-01493', 'Jl Soekarno Hatta Ketapang atas', 'Lainnya', 5, '2025-07-06 05:38:33'),
(2147483897, 'Nisrina Khairiyah Saputri', 'nisrina621@gmail.com', '+62 895-6124-50008', 'Jl. Merpati, RT 002 RW 007, Dusun Wonokriyo', 'Lainnya', 5, '2025-07-06 05:39:07'),
(2147483898, 'Doni Dwi Cahyo', 'sersandoni17@gmail.com', '+62 882-6839-9861', 'Jl teluk Lampung lk 1 RT 008 Kel pidada kec panjang bandar Lampung', 'Lainnya', 5, '2025-07-06 05:39:32'),
(2147483899, 'SELI ANGGRAYNA', 'Sellyanggrayna@gmail.com', '+62 853-6634-0099', 'Jalan kelapa sawit VIII No. 126  prumnas wayhalim', 'Lainnya', 5, '2025-07-06 05:40:06'),
(2147483900, 'Tari Damayanti', 'barazaynabdar@gmail.com', '+62 896-3666-4999', 'Jl sisingamangaraja gg arrahman no 27', 'Lainnya', 5, '2025-07-06 05:40:33'),
(2147483901, 'NOVALIA', 'novalia.amril@gmail.com', '+62 822-8255-7775', 'Jl abdurrahman gg saki no 31', 'Lainnya', 5, '2025-07-06 05:41:02'),
(2147483902, 'Risna Kemala', 'risnakemala05@gmail.com', '+62 895-3528-08474', 'Jl. P. Senopati, Gg. Sadewo, Korpri Jaya, Sukarame, B.Lampung', 'Wiraswasta', 5, '2025-07-06 05:41:37'),
(2147483903, 'Tri Desyani SP', 'Tri.desyani76@gmail.com', '+62 851-0232-8089', 'Perum Taman Palapa Indah blok melati no 1', 'Lainnya', 5, '2025-07-06 05:42:12'),
(2147483904, 'Siti fatimah', 'siti.adm291016@gmail.com', '+62 896-8902-2610', 'JL.KH.A.DAHLAN GG WAKAF NO 33', 'Lainnya', 5, '2025-07-06 05:42:42'),
(2147483905, 'Dewi Utari handayani', 'bundaadeeva01@gmail.com', '+62 895-6304-50002', 'Jl. Padat Karya Lk.II Kubang Dalam RT. 04', 'Lainnya', 5, '2025-07-06 05:43:11'),
(2147483906, 'Dewi Maryana Sukma', 'dewimaryanasukma14@gmail.com', '+62 882-6909-2650', 'Jl. Hi. Said Gg. Sungkai 1 LK. III', 'Lainnya', 5, '2025-07-06 05:43:54'),
(2147483907, 'Amanda Tarisa Salsabila', 'salsabilaamanda546@gmail.com', '+62 895-3682-28717', 'Jl. Purnawirawan Gang swadaya 7 no. 39 gunung terang, Bandar lampung', 'Lainnya', 5, '2025-07-06 05:44:23'),
(2147483908, 'Hidayatullah', 'hidayahdayat833@gmail.com', '+62 812-1946-2439', 'Jl. Hi. Said Gg. Sungkai 1 LK. III', 'Lainnya', 5, '2025-07-06 05:44:58'),
(2147483909, 'Sundari', 'sundariris123@gmail.com', '+62 895-0305-9466', 'Jalan pulau singkep Gang. Asem No. 61 RT 05 LK 2', 'Lainnya', 5, '2025-07-06 05:45:29'),
(2147483910, 'Masteguh Ronalldy', 'mronalldy0509@gmail.com', '+62 899-6403-864', 'Kp. Sukamina LK II PPI', 'Lainnya', 5, '2025-07-06 05:46:00'),
(2147483911, 'Nanang, S.Pd', 'Ariesta1987@gmail.com', '+62 853-6944-1429', 'Jl. Harapan Gg. Bhayangkara No.18/52 RT 004', 'Lainnya', 5, '2025-07-06 05:46:36'),
(2147483912, 'HENDI PRASENDO', 'hprasendo@gmail.com', '+62 822-8287-6436', 'Jalan Yulius Usman No 03', 'Lainnya', 5, '2025-07-06 05:47:08'),
(2147483913, 'Ejat Sudrajat', 'ejatsudra@gmail.com', '+62 812-7211-5431', 'Jl. Letnan Kolonel Jl. Endro Suratmin, Harapan Jaya, Kec. Sukarame, Kota Bandar Lampung, Lampung 35131', 'Lainnya', 5, '2025-07-06 05:47:36'),
(2147483914, 'Masruroh', 'vazza.florist@gmail.com', '+62 859-3467-2618', 'Jalan flamboyan 5 no 110 kelurahan labuhan dalam. Kec tanjung senang', 'Lainnya', 5, '2025-07-06 05:48:02'),
(2147483915, 'Evita Amalia', 'evita.amalia1989@gmail.com', '+62 823-7999-9838', 'Perumahan Srimulyo Permai Blok C No. 20', 'Lainnya', 5, '2025-07-06 05:48:33'),
(2147483917, 'Siti Rahmawati', 'Airanadjwa@gmail.com', '+62 895-0839-5894', 'Jalan jati no.1', 'Lainnya', 5, '2025-07-06 05:49:24'),
(2147483918, 'Okta saputra', 'sokta942@gmail.com', '+62 897-2220-422', 'Jln.alamsyah ratu prawira negara. Kp.karang jaya RT 014 Lk 1', 'Lainnya', 5, '2025-07-06 05:49:50'),
(2147483919, 'Shakila Puspita', 'Shakilapuspita2000@gmail.com', '+62 831-6064-7626', 'Jl endrisuratmin gg merdeka 1 nomor 100', 'Lainnya', 5, '2025-07-06 05:50:22'),
(2147483920, 'Marda Faathir Al Hadiid', 'aditmarda09@gmail.com', '+62 895-7052-35561', 'KP KARANG ANYAR GG JUPITER RT 13 LK 1, KELURAHAN PANJANG UTARA KECAMATAN PANJANG', 'Lainnya', 5, '2025-07-06 05:50:46'),
(2147483921, 'Aini Nurwala', 'aininurwala08@gmail.com', '+62 823-7768-5567', 'jl. Durian 2 Gg. Pondok No. 62', 'Lainnya', 5, '2025-07-06 05:51:45');

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
(50, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Wirausaha', '', NULL, NULL, 'Hadir', NULL, 5, '2025-07-03', '2025-07-02 21:01:07'),
(51, NULL, 'Aldion ihza pratama 3', 'aldion30892@gmail.com', 'merak batin city', 'Koordinasi AntarInstansi', 'Pegawai/Guru', '', NULL, NULL, 'Hadir', NULL, 6, '2025-07-03', '2025-07-02 21:01:14'),
(52, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Pelayanan Statistik Terpadu', 'Aparat Desa/Kelurahan', '', NULL, NULL, 'Hadir', NULL, 7, '2025-07-03', '2025-07-02 21:01:45'),
(55, 2147483647, 'Leni mahanani', 'lenimahanani82@gmail.com', 'Jalan sunda kapal brown no.15', 'Penawaran Kerja Sama', 'Lainnya', '+62 812-7937-9514', NULL, 4, 'Hadir', '2025-07-03 06:07:42', 10, '2025-07-03', '2025-07-02 23:07:42'),
(56, 2147483647, 'Catur Priyanto', 'cpriyanto205@gmail.com', 'Jln. Nangka 3 no 35 Korpri Jaya Kec. Sukarame', 'Diskusi/Koordinasi Kegiatan Statistik', 'Lainnya', '+62 085-6647-14137', NULL, 5, 'Hadir', '2025-07-03 06:16:53', 11, '2025-07-03', '2025-07-02 23:16:53'),
(57, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Rapat/Pertemuan', 'Wirausaha', '', NULL, 5, 'Hadir', '2025-07-03 22:12:04', 12, '2025-07-03', '2025-07-03 15:12:04'),
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
(70, NULL, 'dionksak', 'acs@gmail.comd', 'efefwev', 'Rapat/Pertemuan', 'Wirausaha', '0823981238912', NULL, NULL, 'Hadir', NULL, 25, '2025-07-03', '2025-07-03 16:21:04'),
(71, NULL, 'dionksak', 'acs@gmail.comd', 'efefwev', 'Rapat/Pertemuan', 'Wirausaha', '0823981238912', NULL, NULL, 'Hadir', NULL, 26, '2025-07-03', '2025-07-03 16:21:59'),
(72, NULL, 'Aldion ihza 4', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Wirausaha', '', NULL, 5, 'Hadir', '2025-07-03 23:23:14', 27, '2025-07-03', '2025-07-03 16:23:14'),
(73, NULL, 'sddvsdd', 'dvsdvsv@gmail.com', 'dvsvsd', 'Penawaran Kerja Sama', 'Mitra BPS', '32193812938912', NULL, NULL, 'Hadir', NULL, 28, '2025-07-03', '2025-07-03 16:26:41'),
(74, NULL, 'sfada', 'ajdk@gmail.com', 'asdada', 'Penawaran Kerja Sama', 'Mitra BPS', '0801380138`', NULL, NULL, 'Hadir', NULL, 29, '2025-07-03', '2025-07-03 16:46:08'),
(75, NULL, 'sfada', 'ajdk@gmail.com', 'asdada', 'Penawaran Kerja Sama', 'Mitra BPS', '0801380138`', NULL, NULL, 'Hadir', NULL, 30, '2025-07-03', '2025-07-03 16:48:03'),
(76, NULL, 'sfasf', 'sdfjaba@gmail.com', 'afjafakw', 'Pelayanan Statistik Terpadu', 'Wiraswasta', '0892839`28`91', NULL, NULL, 'Hadir', NULL, 1, '2025-07-05', '2025-07-05 13:41:26'),
(77, NULL, 'testing', 'kskskdnkd@gmail.com', 'adnakwdnkadn', 'Penawaran Kerja Sama', 'Mengurus Rumah Tangga', '083193892138291', NULL, NULL, 'Hadir', NULL, 2, '2025-07-05', '2025-07-05 13:50:17'),
(78, NULL, 'Aldion ihza pratama 2', 'aldion30892@gmail.com', 'merak batin city', 'Penawaran Kerja Sama', 'Aparat Desa/Kelurahan', '', NULL, 3, 'Hadir', '2025-07-05 20:56:30', 3, '2025-07-05', '2025-07-05 13:56:30'),
(79, NULL, 'apkahberhasil', 'aldjksjakks@gmail.com', 'asdnkadkadka', 'Diskusi/Koordinasi Kegiatan Statistik', 'Wiraswasta', '083912389123891', NULL, NULL, 'Hadir', NULL, 4, '2025-07-05', '2025-07-05 14:02:56'),
(80, NULL, 'bismillah jadi', 'jasdbaJDBja@gmail.com', 'ajdbjadbjadja', 'Pelayanan Statistik Terpadu', 'Honorer', '0183183013810', NULL, 5, 'Hadir', NULL, 5, '2025-07-05', '2025-07-05 14:15:41');

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
  MODIFY `id` bigint(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483922;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
