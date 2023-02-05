-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 18, 2022 at 11:34 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `angkatan`
--

CREATE TABLE `angkatan` (
  `id_angkatan` bigint(20) UNSIGNED NOT NULL,
  `code_angkatan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_angkatan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ang : angkatan, kom : komando, sub: subkomando',
  `id_provinsi` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `angkatan`
--

INSERT INTO `angkatan` (`id_angkatan`, `code_angkatan`, `nama_angkatan`, `level`, `id_provinsi`, `parent`, `created_at`, `updated_at`) VALUES
(2, NULL, 'mamamax', 'sub', 14, 6, '2022-02-14 17:49:14', '2022-02-14 21:04:27'),
(3, 'z', 'd', 'ang', 13, NULL, '2022-02-14 17:57:13', '2022-02-18 02:58:50'),
(5, NULL, 'asd', 'kom', NULL, 3, '2022-02-14 20:45:23', '2022-02-14 20:45:23'),
(6, NULL, 'dfgs', 'kom', 15, 3, '2022-02-14 20:45:29', '2022-02-14 21:01:45'),
(7, NULL, 'er', 'sub', NULL, 6, '2022-02-14 20:46:17', '2022-02-14 20:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `bor`
--

CREATE TABLE `bor` (
  `id_bor` bigint(20) UNSIGNED NOT NULL,
  `id_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `all_tt` int(11) NOT NULL,
  `icu_slot` int(11) NOT NULL,
  `icu_isi` int(11) NOT NULL,
  `isolate_slot` int(11) NOT NULL,
  `isolate_isi` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bor`
--

INSERT INTO `bor` (`id_bor`, `id_rs`, `tanggal`, `all_tt`, `icu_slot`, `icu_isi`, `isolate_slot`, `isolate_isi`, `created_at`, `updated_at`) VALUES
(2, '6', '2022-02-17', 88, 4, 3, 90, 76, '2022-02-16 00:50:24', '2022-02-16 00:50:24'),
(3, '6', '2022-02-18', 55, 33, 9, 66, 44, '2022-02-16 00:53:14', '2022-02-16 00:53:14');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `nama`, `alamat`, `telepon`, `email`, `website`, `created_at`, `updated_at`) VALUES
(1, 'Satya Mandala', 'Gg. Sukabumi No. 529, Semarang 77189, Aceh', '0809 846 966', 'puspita.putri@gmail.co.id', 'http://budiman.biz/dolores-facere-aut-qui-ut-cumque', NULL, NULL),
(2, 'Irma Nurdiyanti', 'Jln. Banda No. 534, Sabang 83470, DKI', '(+62) 620 3753 2431', 'fnasyidah@gmail.com', 'http://www.hutasoit.biz/', NULL, NULL),
(3, 'Maria Hastuti', 'Jln. Ronggowarsito No. 820, Lhokseumawe 49907, Kalteng', '029 5983 0578', 'dabukke.asmadi@gmail.com', 'http://www.sudiati.org/sit-numquam-ea-sit-hic-adipisci-maiores-aut', NULL, NULL),
(4, 'Bambang Tamba', 'Dk. Ciwastra No. 172, Tomohon 17737, Kaltara', '(+62) 449 1556 1387', 'kayla.waskita@yahoo.co.id', 'http://nurdiyanti.in/', NULL, NULL),
(5, 'Hesti Cinthia Melani', 'Dk. Panjaitan No. 597, Sabang 49399, Sumut', '(+62) 945 8292 9773', 'suryono.kani@gmail.com', 'http://namaga.web.id/doloribus-provident-in-sed-fugit-ut-sapiente-architecto', NULL, NULL),
(6, 'Atmaja Suwarno', 'Ds. Jayawijaya No. 107, Pematangsiantar 65041, Babel', '0743 4765 7996', 'rina.novitasari@gmail.com', 'http://wijayanti.or.id/', NULL, NULL),
(7, 'Ayu Utami', 'Psr. Babadan No. 349, Administrasi Jakarta Utara 88961, DIY', '0833 7533 880', 'farah.uwais@gmail.com', 'http://www.lestari.com/necessitatibus-sit-adipisci-magnam-culpa-quam', NULL, NULL),
(8, 'Harimurti Wibowo', 'Kpg. Tentara Pelajar No. 201, Tual 78512, Aceh', '(+62) 29 9157 3618', 'mursita.maryadi@yahoo.co.id', 'http://ardianto.tv/', NULL, NULL),
(9, 'Yunita Puspa Novitasari', 'Ki. Abdul No. 31, Semarang 42411, Sulteng', '(+62) 341 7872 1770', 'santoso.dodo@yahoo.co.id', 'https://www.permata.go.id/ab-dolores-sit-accusantium-accusamus-et-quia-harum', NULL, NULL),
(10, 'Mahesa Okta Prabowo S.Sos', 'Jr. Imam No. 271, Probolinggo 16276, Sulsel', '(+62) 898 2666 660', 'prasetya.widya@yahoo.co.id', 'http://haryanto.co.id/', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nohp` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `nama`, `alamat`, `nohp`, `email`, `facebook`, `instagram`, `whatsapp`, `company`, `created_at`, `updated_at`) VALUES
(3, 'Hartana Gamblang Uwais', 'Psr. Bara Tambar No. 445, Pagar Alam 22267, Kalbar', '0659 9511 1004', 'fitria.uyainah@yahoo.com', 'saefullah.bakiadi@yahoo.com', 'irahayu', '0731 6385 1356', 'UD Haryanto (Persero) Tbk', NULL, NULL),
(4, 'Gandewa Halim', 'Dk. Wahid Hasyim No. 546, Ternate 83984, DIY', '(+62) 204 3924 4822', 'zulaikha75@yahoo.co.id', 'mandasari.vivi@gmail.com', 'cutami', '(+62) 903 9720 6612', 'PD Siregar Laksita (Persero) Tbk', NULL, NULL),
(5, 'Prabu Wijaya S.E.', 'Dk. Basket No. 401, Medan 14513, Babel', '(+62) 23 9330 249', 'raharja.zulkarnain@yahoo.co.id', 'prasasta.galiono@yahoo.com', 'anggriawan.melinda', '(+62) 297 2682 601', 'PT Pranowo Dabukke', NULL, NULL),
(6, 'Kalim Sihotang S.T.', 'Psr. Ters. Kiaracondong No. 560, Sukabumi 18154, Malut', '(+62) 944 6988 124', 'setiawan.ella@gmail.com', 'raisa.farida@yahoo.co.id', 'lala50', '0298 3246 220', 'Perum Prabowo', NULL, NULL),
(7, 'Gabriella Putri Fujiati', 'Psr. Bak Mandi No. 967, Banjarmasin 95558, Pabar', '0284 6314 336', 'ira.hartati@gmail.com', 'hfarida@yahoo.com', 'yunita.thamrin', '022 9595 5625', 'PD Melani Prasetya', NULL, NULL),
(8, 'Kani Zulfa Rahmawati', 'Gg. Setia Budi No. 70, Banjarbaru 62594, Papua', '023 1894 7923', 'prakosa.utami@yahoo.com', 'yuniar.ifa@yahoo.co.id', 'dwi95', '(+62) 934 1592 8899', 'CV Hidayanto Suryatmi (Persero) Tbk', NULL, NULL),
(9, 'Fali', 'Jln. Hang No. 719, Gorontalo 88229, Kalteng', '(+62) 313 6063 9238', 'yuniar.icha@gmail.com', 'firmansyah.lintang@gmail.co.id', 'janet.nasyiah', '0960 1304 053', 'CV Astuti Tbk', NULL, '2021-10-19 00:29:13'),
(11, 'Dono', 'pdg', '564564', 'admin@admin.com', 'adaw', 'awdawd', '465464', 'rtyryrtyr', '2021-10-19 00:32:43', '2021-10-19 00:32:43'),
(12, 'Dono', 'pdg', '564564', 'admin@admin.com', 'adaw', 'awdawd', '465464', 'rtyryrtyr', '2021-10-19 00:33:14', '2021-10-19 00:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `data_covid`
--

CREATE TABLE `data_covid` (
  `id_covid` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_pasien` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pasien` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` bigint(20) UNSIGNED NOT NULL,
  `nama_dokter` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(10) UNSIGNED NOT NULL,
  `start` date NOT NULL,
  `nama_event` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_event` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finish` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `start`, `nama_event`, `tempat_event`, `finish`, `created_at`, `updated_at`) VALUES
(1, '1985-12-24', 'Damar Arsipatra Wacana', 'Marvel', '2002-12-24', NULL, '2021-10-19 02:54:18'),
(2, '1984-09-18', 'Eja Latif Prayoga', 'Ville', '1999-07-04', NULL, NULL),
(3, '1975-09-15', 'Capa Waskita', 'Ville', '1992-09-09', NULL, NULL),
(4, '2005-10-14', 'Halima Victoria Farida', 'Ville', '2014-10-22', NULL, NULL),
(5, '2008-03-13', 'Lala Laksmiwati', 'Ville', '1971-01-29', NULL, NULL),
(6, '2002-07-13', 'Nurul Nasyidah', 'Ville', '1992-02-28', NULL, NULL),
(7, '1973-04-02', 'Johan Nababan', 'Ville', '2018-06-24', NULL, NULL),
(8, '1970-11-12', 'Jelita Mulyani', 'Ville', '2005-02-08', NULL, NULL),
(9, '1994-07-25', 'Purwa Wibisono', 'Ville', '2016-12-26', NULL, NULL),
(13, '2022-02-01', 'dsf', 'sdf', '2022-02-28', '2022-02-13 00:27:06', '2022-02-13 00:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pasien`
--

CREATE TABLE `jenis_pasien` (
  `id_jenis_pasien` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komando`
--

CREATE TABLE `komando` (
  `id_komando` bigint(20) UNSIGNED NOT NULL,
  `nama_komando` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_angkatan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `komando`
--

INSERT INTO `komando` (`id_komando`, `nama_komando`, `id_angkatan`, `created_at`, `updated_at`) VALUES
(2, 'delex', '3', '2022-02-13 20:14:19', '2022-02-13 20:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `kota_kab`
--

CREATE TABLE `kota_kab` (
  `id_kotakab` bigint(20) UNSIGNED NOT NULL,
  `nama_kotakab` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_provinsi` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kota_kab`
--

INSERT INTO `kota_kab` (`id_kotakab`, `nama_kotakab`, `id_provinsi`, `created_at`, `updated_at`, `jenis`) VALUES
(1101, 'ACEH SELATAN', 11, NULL, NULL, 'KAB.'),
(1102, 'ACEH TENGGARA', 11, NULL, NULL, 'KAB.'),
(1103, 'ACEH TIMUR', 11, NULL, NULL, 'KAB.'),
(1104, 'ACEH TENGAH', 11, NULL, NULL, 'KAB.'),
(1105, 'ACEH BARAT', 11, NULL, NULL, 'KAB.'),
(1106, 'ACEH BESAR', 11, NULL, NULL, 'KAB.'),
(1107, 'PIDIE', 11, NULL, NULL, 'KAB.'),
(1108, 'ACEH UTARA', 11, NULL, NULL, 'KAB.'),
(1109, 'SIMEULUE', 11, NULL, NULL, 'KAB.'),
(1110, 'ACEH SINGKIL', 11, NULL, NULL, 'KAB.'),
(1111, 'BIREUEN', 11, NULL, NULL, 'KAB.'),
(1112, 'ACEH BARAT DAYA', 11, NULL, NULL, 'KAB.'),
(1113, 'GAYO LUES', 11, NULL, NULL, 'KAB.'),
(1114, 'ACEH JAYA', 11, NULL, NULL, 'KAB.'),
(1115, 'NAGAN RAYA', 11, NULL, NULL, 'KAB.'),
(1116, 'ACEH TAMIANG', 11, NULL, NULL, 'KAB.'),
(1117, 'BENER MERIAH', 11, NULL, NULL, 'KAB.'),
(1118, 'PIDIE JAYA', 11, NULL, NULL, 'KAB.'),
(1171, 'BANDA ACEH', 11, NULL, NULL, 'KOTA'),
(1172, 'SABANG', 11, NULL, NULL, 'KOTA'),
(1173, 'LHOKSEUMAWE', 11, NULL, NULL, 'KOTA'),
(1174, 'LANGSA', 11, NULL, NULL, 'KOTA'),
(1175, 'SUBULUSSALAM', 11, NULL, NULL, 'KOTA'),
(1201, 'TAPANULI TENGAH', 12, NULL, NULL, 'KAB.'),
(1202, 'TAPANULI UTARA', 12, NULL, NULL, 'KAB.'),
(1203, 'TAPANULI SELATAN', 12, NULL, NULL, 'KAB.'),
(1204, 'NIAS', 12, NULL, NULL, 'KAB.'),
(1205, 'LANGKAT', 12, NULL, NULL, 'KAB.'),
(1206, 'KARO', 12, NULL, NULL, 'KAB.'),
(1207, 'DELI SERDANG', 12, NULL, NULL, 'KAB.'),
(1208, 'SIMALUNGUN', 12, NULL, NULL, 'KAB.'),
(1209, 'ASAHAN', 12, NULL, NULL, 'KAB.'),
(1210, 'LABUHANBATU', 12, NULL, NULL, 'KAB.'),
(1211, 'DAIRI', 12, NULL, NULL, 'KAB.'),
(1212, 'TOBA SAMOSIR', 12, NULL, NULL, 'KAB.'),
(1213, 'MANDAILING NATAL', 12, NULL, NULL, 'KAB.'),
(1214, 'NIAS SELATAN', 12, NULL, NULL, 'KAB.'),
(1215, 'PAKPAK BHARAT', 12, NULL, NULL, 'KAB.'),
(1216, 'HUMBANG HASUNDUTAN', 12, NULL, NULL, 'KAB.'),
(1217, 'SAMOSIR', 12, NULL, NULL, 'KAB.'),
(1218, 'SERDANG BEDAGAI', 12, NULL, NULL, 'KAB.'),
(1219, 'BATU BARA', 12, NULL, NULL, 'KAB.'),
(1220, 'PADANG LAWAS UTARA', 12, NULL, NULL, 'KAB.'),
(1221, 'PADANG LAWAS', 12, NULL, NULL, 'KAB.'),
(1222, 'LABUHANBATU SELATAN', 12, NULL, NULL, 'KAB.'),
(1223, 'LABUHANBATU UTARA', 12, NULL, NULL, 'KAB.'),
(1224, 'NIAS UTARA', 12, NULL, NULL, 'KAB.'),
(1225, 'NIAS BARAT', 12, NULL, NULL, 'KAB.'),
(1271, 'MEDAN', 12, NULL, NULL, 'KOTA'),
(1272, 'PEMATANG SIANTAR', 12, NULL, NULL, 'KOTA'),
(1273, 'SIBOLGA', 12, NULL, NULL, 'KOTA'),
(1274, 'TANJUNG BALAI', 12, NULL, NULL, 'KOTA'),
(1275, 'BINJAI', 12, NULL, NULL, 'KOTA'),
(1276, 'TEBING TINGGI', 12, NULL, NULL, 'KOTA'),
(1277, 'PADANGSIDIMPUAN', 12, NULL, NULL, 'KOTA'),
(1278, 'GUNUNGSITOLI', 12, NULL, NULL, 'KOTA'),
(1301, 'PESISIR SELATAN', 13, NULL, NULL, 'KAB.'),
(1302, 'SOLOK', 13, NULL, NULL, 'KAB.'),
(1303, 'SIJUNJUNG', 13, NULL, NULL, 'KAB.'),
(1304, 'TANAH DATAR', 13, NULL, NULL, 'KAB.'),
(1305, 'PADANG PARIAMAN', 13, NULL, NULL, 'KAB.'),
(1306, 'AGAM', 13, NULL, NULL, 'KAB.'),
(1307, 'LIMA PULUH KOTA', 13, NULL, NULL, 'KAB.'),
(1308, 'PASAMAN', 13, NULL, NULL, 'KAB.'),
(1309, 'KEPULAUAN MENTAWAI', 13, NULL, NULL, 'KAB.'),
(1310, 'DHARMASRAYA', 13, NULL, NULL, 'KAB.'),
(1311, 'SOLOK SELATAN', 13, NULL, NULL, 'KAB.'),
(1312, 'PASAMAN BARAT', 13, NULL, NULL, 'KAB.'),
(1371, 'PADANG', 13, NULL, NULL, 'KOTA'),
(1372, 'SOLOK', 13, NULL, NULL, 'KOTA'),
(1373, 'SAWAHLUNTO', 13, NULL, NULL, 'KOTA'),
(1374, 'PADANG PANJANG', 13, NULL, NULL, 'KOTA'),
(1375, 'BUKITTINGGI', 13, NULL, NULL, 'KOTA'),
(1376, 'PAYAKUMBUH', 13, NULL, NULL, 'KOTA'),
(1377, 'PARIAMAN', 13, NULL, NULL, 'KOTA'),
(1401, 'KAMPAR', 14, NULL, NULL, 'KAB.'),
(1402, 'INDRAGIRI HULU', 14, NULL, NULL, 'KAB.'),
(1403, 'BENGKALIS', 14, NULL, NULL, 'KAB.'),
(1404, 'INDRAGIRI HILIR', 14, NULL, NULL, 'KAB.'),
(1405, 'PELALAWAN', 14, NULL, NULL, 'KAB.'),
(1406, 'ROKAN HULU', 14, NULL, NULL, 'KAB.'),
(1407, 'ROKAN HILIR', 14, NULL, NULL, 'KAB.'),
(1408, 'SIAK', 14, NULL, NULL, 'KAB.'),
(1409, 'KUANTAN SINGINGI', 14, NULL, NULL, 'KAB.'),
(1410, 'KEPULAUAN MERANTI', 14, NULL, NULL, 'KAB.'),
(1471, 'PEKANBARU', 14, NULL, NULL, 'KOTA'),
(1472, 'DUMAI', 14, NULL, NULL, 'KOTA'),
(1501, 'KERINCI', 15, NULL, NULL, 'KAB.'),
(1502, 'MERANGIN', 15, NULL, NULL, 'KAB.'),
(1503, 'SAROLANGUN', 15, NULL, NULL, 'KAB.'),
(1504, 'BATANGHARI', 15, NULL, NULL, 'KAB.'),
(1505, 'MUARO JAMBI', 15, NULL, NULL, 'KAB.'),
(1506, 'TANJUNG JABUNG BARAT', 15, NULL, NULL, 'KAB.'),
(1507, 'TANJUNG JABUNG TIMUR', 15, NULL, NULL, 'KAB.'),
(1508, 'BUNGO', 15, NULL, NULL, 'KAB.'),
(1509, 'TEBO', 15, NULL, NULL, 'KAB.'),
(1571, 'JAMBI', 15, NULL, NULL, 'KOTA'),
(1572, 'SUNGAI PENUH', 15, NULL, NULL, 'KOTA'),
(1601, 'OGAN KOMERING ULU', 16, NULL, NULL, 'KAB.'),
(1602, 'OGAN KOMERING ILIR', 16, NULL, NULL, 'KAB.'),
(1603, 'MUARA ENIM', 16, NULL, NULL, 'KAB.'),
(1604, 'LAHAT', 16, NULL, NULL, 'KAB.'),
(1605, 'MUSI RAWAS', 16, NULL, NULL, 'KAB.'),
(1606, 'MUSI BANYUASIN', 16, NULL, NULL, 'KAB.'),
(1607, 'BANYUASIN', 16, NULL, NULL, 'KAB.'),
(1608, 'OGAN KOMERING ULU TIMUR', 16, NULL, NULL, 'KAB.'),
(1609, 'OGAN KOMERING ULU SELATAN', 16, NULL, NULL, 'KAB.'),
(1610, 'OGAN ILIR', 16, NULL, NULL, 'KAB.'),
(1611, 'EMPAT LAWANG', 16, NULL, NULL, 'KAB.'),
(1612, 'PENUKAL ABAB LEMATANG ILIR', 16, NULL, NULL, 'KAB.'),
(1613, 'MUSI RAWAS UTARA', 16, NULL, NULL, 'KAB.'),
(1671, 'PALEMBANG', 16, NULL, NULL, 'KOTA'),
(1672, 'PAGAR ALAM', 16, NULL, NULL, 'KOTA'),
(1673, 'LUBUK LINGGAU', 16, NULL, NULL, 'KOTA'),
(1674, 'PRABUMULIH', 16, NULL, NULL, 'KOTA'),
(1701, 'BENGKULU SELATAN', 17, NULL, NULL, 'KAB.'),
(1702, 'REJANG LEBONG', 17, NULL, NULL, 'KAB.'),
(1703, 'BENGKULU UTARA', 17, NULL, NULL, 'KAB.'),
(1704, 'KAUR', 17, NULL, NULL, 'KAB.'),
(1705, 'SELUMA', 17, NULL, NULL, 'KAB.'),
(1706, 'MUKO MUKO', 17, NULL, NULL, 'KAB.'),
(1707, 'LEBONG', 17, NULL, NULL, 'KAB.'),
(1708, 'KEPAHIANG', 17, NULL, NULL, 'KAB.'),
(1709, 'BENGKULU TENGAH', 17, NULL, NULL, 'KAB.'),
(1771, 'BENGKULU', 17, NULL, NULL, 'KOTA'),
(1801, 'LAMPUNG SELATAN', 18, NULL, NULL, 'KAB.'),
(1802, 'LAMPUNG TENGAH', 18, NULL, NULL, 'KAB.'),
(1803, 'LAMPUNG UTARA', 18, NULL, NULL, 'KAB.'),
(1804, 'LAMPUNG BARAT', 18, NULL, NULL, 'KAB.'),
(1805, 'TULANG BAWANG', 18, NULL, NULL, 'KAB.'),
(1806, 'TANGGAMUS', 18, NULL, NULL, 'KAB.'),
(1807, 'LAMPUNG TIMUR', 18, NULL, NULL, 'KAB.'),
(1808, 'WAY KANAN', 18, NULL, NULL, 'KAB.'),
(1809, 'PESAWARAN', 18, NULL, NULL, 'KAB.'),
(1810, 'PRINGSEWU', 18, NULL, NULL, 'KAB.'),
(1811, 'MESUJI', 18, NULL, NULL, 'KAB.'),
(1812, 'TULANG BAWANG BARAT', 18, NULL, NULL, 'KAB.'),
(1813, 'PESISIR BARAT', 18, NULL, NULL, 'KAB.'),
(1871, 'BANDAR LAMPUNG', 18, NULL, NULL, 'KOTA'),
(1872, 'METRO', 18, NULL, NULL, 'KOTA'),
(1901, 'BANGKA', 19, NULL, NULL, 'KAB.'),
(1902, 'BELITUNG', 19, NULL, NULL, 'KAB.'),
(1903, 'BANGKA SELATAN', 19, NULL, NULL, 'KAB.'),
(1904, 'BANGKA TENGAH', 19, NULL, NULL, 'KAB.'),
(1905, 'BANGKA BARAT', 19, NULL, NULL, 'KAB.'),
(1906, 'BELITUNG TIMUR', 19, NULL, NULL, 'KAB.'),
(1971, 'PANGKAL PINANG', 19, NULL, NULL, 'KOTA'),
(2101, 'BINTAN', 21, NULL, NULL, 'KAB.'),
(2102, 'KARIMUN', 21, NULL, NULL, 'KAB.'),
(2103, 'NATUNA', 21, NULL, NULL, 'KAB.'),
(2104, 'LINGGA', 21, NULL, NULL, 'KAB.'),
(2105, 'KEPULAUAN ANAMBAS', 21, NULL, NULL, 'KAB.'),
(2171, 'BATAM', 21, NULL, NULL, 'KOTA'),
(2172, 'TANJUNG PINANG', 21, NULL, NULL, 'KOTA'),
(3101, 'ADM. KEP. SERIBU', 31, NULL, NULL, 'KAB.'),
(3171, 'ADM. JAKARTA PUSAT', 31, NULL, NULL, 'KOTA'),
(3172, 'ADM. JAKARTA UTARA', 31, NULL, NULL, 'KOTA'),
(3173, 'ADM. JAKARTA BARAT', 31, NULL, NULL, 'KOTA'),
(3174, 'ADM. JAKARTA SELATAN', 31, NULL, NULL, 'KOTA'),
(3175, 'ADM. JAKARTA TIMUR', 31, NULL, NULL, 'KOTA'),
(3201, 'BOGOR', 32, NULL, NULL, 'KAB.'),
(3202, 'SUKABUMI', 32, NULL, NULL, 'KAB.'),
(3203, 'CIANJUR', 32, NULL, NULL, 'KAB.'),
(3204, 'BANDUNG', 32, NULL, NULL, 'KAB.'),
(3205, 'GARUT', 32, NULL, NULL, 'KAB.'),
(3206, 'TASIKMALAYA', 32, NULL, NULL, 'KAB.'),
(3207, 'CIAMIS', 32, NULL, NULL, 'KAB.'),
(3208, 'KUNINGAN', 32, NULL, NULL, 'KAB.'),
(3209, 'CIREBON', 32, NULL, NULL, 'KAB.'),
(3210, 'MAJALENGKA', 32, NULL, NULL, 'KAB.'),
(3211, 'SUMEDANG', 32, NULL, NULL, 'KAB.'),
(3212, 'INDRAMAYU', 32, NULL, NULL, 'KAB.'),
(3213, 'SUBANG', 32, NULL, NULL, 'KAB.'),
(3214, 'PURWAKARTA', 32, NULL, NULL, 'KAB.'),
(3215, 'KARAWANG', 32, NULL, NULL, 'KAB.'),
(3216, 'BEKASI', 32, NULL, NULL, 'KAB.'),
(3217, 'BANDUNG BARAT', 32, NULL, NULL, 'KAB.'),
(3218, 'PANGANDARAN', 32, NULL, NULL, 'KAB.'),
(3271, 'BOGOR', 32, NULL, NULL, 'KOTA'),
(3272, 'SUKABUMI', 32, NULL, NULL, 'KOTA'),
(3273, 'BANDUNG', 32, NULL, NULL, 'KOTA'),
(3274, 'CIREBON', 32, NULL, NULL, 'KOTA'),
(3275, 'BEKASI', 32, NULL, NULL, 'KOTA'),
(3276, 'DEPOK', 32, NULL, NULL, 'KOTA'),
(3277, 'CIMAHI', 32, NULL, NULL, 'KOTA'),
(3278, 'TASIKMALAYA', 32, NULL, NULL, 'KOTA'),
(3279, 'BANJAR', 32, NULL, NULL, 'KOTA'),
(3301, 'CILACAP', 33, NULL, NULL, 'KAB.'),
(3302, 'BANYUMAS', 33, NULL, NULL, 'KAB.'),
(3303, 'PURBALINGGA', 33, NULL, NULL, 'KAB.'),
(3304, 'BANJARNEGARA', 33, NULL, NULL, 'KAB.'),
(3305, 'KEBUMEN', 33, NULL, NULL, 'KAB.'),
(3306, 'PURWOREJO', 33, NULL, NULL, 'KAB.'),
(3307, 'WONOSOBO', 33, NULL, NULL, 'KAB.'),
(3308, 'MAGELANG', 33, NULL, NULL, 'KAB.'),
(3309, 'BOYOLALI', 33, NULL, NULL, 'KAB.'),
(3310, 'KLATEN', 33, NULL, NULL, 'KAB.'),
(3311, 'SUKOHARJO', 33, NULL, NULL, 'KAB.'),
(3312, 'WONOGIRI', 33, NULL, NULL, 'KAB.'),
(3313, 'KARANGANYAR', 33, NULL, NULL, 'KAB.'),
(3314, 'SRAGEN', 33, NULL, NULL, 'KAB.'),
(3315, 'GROBOGAN', 33, NULL, NULL, 'KAB.'),
(3316, 'BLORA', 33, NULL, NULL, 'KAB.'),
(3317, 'REMBANG', 33, NULL, NULL, 'KAB.'),
(3318, 'PATI', 33, NULL, NULL, 'KAB.'),
(3319, 'KUDUS', 33, NULL, NULL, 'KAB.'),
(3320, 'JEPARA', 33, NULL, NULL, 'KAB.'),
(3321, 'DEMAK', 33, NULL, NULL, 'KAB.'),
(3322, 'SEMARANG', 33, NULL, NULL, 'KAB.'),
(3323, 'TEMANGGUNG', 33, NULL, NULL, 'KAB.'),
(3324, 'KENDAL', 33, NULL, NULL, 'KAB.'),
(3325, 'BATANG', 33, NULL, NULL, 'KAB.'),
(3326, 'PEKALONGAN', 33, NULL, NULL, 'KAB.'),
(3327, 'PEMALANG', 33, NULL, NULL, 'KAB.'),
(3328, 'TEGAL', 33, NULL, NULL, 'KAB.'),
(3329, 'BREBES', 33, NULL, NULL, 'KAB.'),
(3371, 'MAGELANG', 33, NULL, NULL, 'KOTA'),
(3372, 'SURAKARTA', 33, NULL, NULL, 'KOTA'),
(3373, 'SALATIGA', 33, NULL, NULL, 'KOTA'),
(3374, 'SEMARANG', 33, NULL, NULL, 'KOTA'),
(3375, 'PEKALONGAN', 33, NULL, NULL, 'KOTA'),
(3376, 'TEGAL', 33, NULL, NULL, 'KOTA'),
(3401, 'KULON PROGO', 34, NULL, NULL, 'KAB.'),
(3402, 'BANTUL', 34, NULL, NULL, 'KAB.'),
(3403, 'GUNUNG KIDUL', 34, NULL, NULL, 'KAB.'),
(3404, 'SLEMAN', 34, NULL, NULL, 'KAB.'),
(3471, 'YOGYAKARTA', 34, NULL, NULL, 'KOTA'),
(3501, 'PACITAN', 35, NULL, NULL, 'KAB.'),
(3502, 'PONOROGO', 35, NULL, NULL, 'KAB.'),
(3503, 'TRENGGALEK', 35, NULL, NULL, 'KAB.'),
(3504, 'TULUNGAGUNG', 35, NULL, NULL, 'KAB.'),
(3505, 'BLITAR', 35, NULL, NULL, 'KAB.'),
(3506, 'KEDIRI', 35, NULL, NULL, 'KAB.'),
(3507, 'MALANG', 35, NULL, NULL, 'KAB.'),
(3508, 'LUMAJANG', 35, NULL, NULL, 'KAB.'),
(3509, 'JEMBER', 35, NULL, NULL, 'KAB.'),
(3510, 'BANYUWANGI', 35, NULL, NULL, 'KAB.'),
(3511, 'BONDOWOSO', 35, NULL, NULL, 'KAB.'),
(3512, 'SITUBONDO', 35, NULL, NULL, 'KAB.'),
(3513, 'PROBOLINGGO', 35, NULL, NULL, 'KAB.'),
(3514, 'PASURUAN', 35, NULL, NULL, 'KAB.'),
(3515, 'SIDOARJO', 35, NULL, NULL, 'KAB.'),
(3516, 'MOJOKERTO', 35, NULL, NULL, 'KAB.'),
(3517, 'JOMBANG', 35, NULL, NULL, 'KAB.'),
(3518, 'NGANJUK', 35, NULL, NULL, 'KAB.'),
(3519, 'MADIUN', 35, NULL, NULL, 'KAB.'),
(3520, 'MAGETAN', 35, NULL, NULL, 'KAB.'),
(3521, 'NGAWI', 35, NULL, NULL, 'KAB.'),
(3522, 'BOJONEGORO', 35, NULL, NULL, 'KAB.'),
(3523, 'TUBAN', 35, NULL, NULL, 'KAB.'),
(3524, 'LAMONGAN', 35, NULL, NULL, 'KAB.'),
(3525, 'GRESIK', 35, NULL, NULL, 'KAB.'),
(3526, 'BANGKALAN', 35, NULL, NULL, 'KAB.'),
(3527, 'SAMPANG', 35, NULL, NULL, 'KAB.'),
(3528, 'PAMEKASAN', 35, NULL, NULL, 'KAB.'),
(3529, 'SUMENEP', 35, NULL, NULL, 'KAB.'),
(3571, 'KEDIRI', 35, NULL, NULL, 'KOTA'),
(3572, 'BLITAR', 35, NULL, NULL, 'KOTA'),
(3573, 'MALANG', 35, NULL, NULL, 'KOTA'),
(3574, 'PROBOLINGGO', 35, NULL, NULL, 'KOTA'),
(3575, 'PASURUAN', 35, NULL, NULL, 'KOTA'),
(3576, 'MOJOKERTO', 35, NULL, NULL, 'KOTA'),
(3577, 'MADIUN', 35, NULL, NULL, 'KOTA'),
(3578, 'SURABAYA', 35, NULL, NULL, 'KOTA'),
(3579, 'BATU', 35, NULL, NULL, 'KOTA'),
(3601, 'PANDEGLANG', 36, NULL, NULL, 'KAB.'),
(3602, 'LEBAK', 36, NULL, NULL, 'KAB.'),
(3603, 'TANGERANG', 36, NULL, NULL, 'KAB.'),
(3604, 'SERANG', 36, NULL, NULL, 'KAB.'),
(3671, 'TANGERANG', 36, NULL, NULL, 'KOTA'),
(3672, 'CILEGON', 36, NULL, NULL, 'KOTA'),
(3673, 'SERANG', 36, NULL, NULL, 'KOTA'),
(3674, 'TANGERANG SELATAN', 36, NULL, NULL, 'KOTA'),
(5101, 'JEMBRANA', 51, NULL, NULL, 'KAB.'),
(5102, 'TABANAN', 51, NULL, NULL, 'KAB.'),
(5103, 'BADUNG', 51, NULL, NULL, 'KAB.'),
(5104, 'GIANYAR', 51, NULL, NULL, 'KAB.'),
(5105, 'KLUNGKUNG', 51, NULL, NULL, 'KAB.'),
(5106, 'BANGLI', 51, NULL, NULL, 'KAB.'),
(5107, 'KARANGASEM', 51, NULL, NULL, 'KAB.'),
(5108, 'BULELENG', 51, NULL, NULL, 'KAB.'),
(5171, 'DENPASAR', 51, NULL, NULL, 'KOTA'),
(5201, 'LOMBOK BARAT', 52, NULL, NULL, 'KAB.'),
(5202, 'LOMBOK TENGAH', 52, NULL, NULL, 'KAB.'),
(5203, 'LOMBOK TIMUR', 52, NULL, NULL, 'KAB.'),
(5204, 'SUMBAWA', 52, NULL, NULL, 'KAB.'),
(5205, 'DOMPU', 52, NULL, NULL, 'KAB.'),
(5206, 'BIMA', 52, NULL, NULL, 'KAB.'),
(5207, 'SUMBAWA BARAT', 52, NULL, NULL, 'KAB.'),
(5208, 'LOMBOK UTARA', 52, NULL, NULL, 'KAB.'),
(5271, 'MATARAM', 52, NULL, NULL, 'KOTA'),
(5272, 'BIMA', 52, NULL, NULL, 'KOTA'),
(5301, 'KUPANG', 53, NULL, NULL, 'KAB.'),
(5302, 'IMOR TENGAH SELATAN', 53, NULL, NULL, 'KAB '),
(5303, 'TIMOR TENGAH UTARA', 53, NULL, NULL, 'KAB.'),
(5304, 'BELU', 53, NULL, NULL, 'KAB.'),
(5305, 'ALOR', 53, NULL, NULL, 'KAB.'),
(5306, 'FLORES TIMUR', 53, NULL, NULL, 'KAB.'),
(5307, 'SIKKA', 53, NULL, NULL, 'KAB.'),
(5308, 'ENDE', 53, NULL, NULL, 'KAB.');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_10_18_060904_create_customer_table', 2),
(6, '2021_10_18_061535_create_event_table', 2),
(7, '2021_10_18_061926_create_company_table', 2),
(8, '2021_10_21_035225_create_permission_tables', 3),
(10, '2021_10_21_035353_create_permission_tables', 4),
(11, '2021_10_25_031902_add_updated_at_to_role_has', 5),
(13, '2021_10_28_035220_drop_column_roleid_table_user', 6),
(15, '2022_02_12_125531_create_komando', 7),
(16, '2022_02_12_125707_create_sub_komando', 7),
(17, '2022_02_12_130747_create_provinsi', 7),
(18, '2022_02_12_130805_create_kota_kab', 7),
(19, '2022_02_12_133327_create_dokter', 7),
(20, '2022_02_12_133340_create_sp_dokter', 7),
(21, '2022_02_12_133458_create_posisi_paramedis', 7),
(22, '2022_02_12_133510_create_paramedis', 7),
(23, '2022_02_12_134906_create_sp_subsp', 7),
(24, '2022_02_12_134924_create_sp_subsp_rs', 7),
(26, '2022_02_12_141122_create_jenis_pasien', 7),
(27, '2022_02_12_141221_create_status_pasien', 7),
(28, '2022_02_12_141510_create_data_covid', 7),
(29, '2022_02_12_141526_create_bor', 7),
(31, '2022_02_12_124809_create_angkatan', 8),
(32, '2022_02_12_134936_create_rs', 9);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(10, 'App\\Models\\User', 13),
(10, 'App\\Models\\User', 14),
(12, 'App\\Models\\User', 19),
(12, 'App\\Models\\User', 20),
(14, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 15);

-- --------------------------------------------------------

--
-- Table structure for table `paramedis`
--

CREATE TABLE `paramedis` (
  `id_paramedis` bigint(20) UNSIGNED NOT NULL,
  `nama_paramedis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_posisi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'customer_list', 'web', '2021-10-21 21:11:23', '2021-10-21 21:11:23'),
(2, 'customer_create', 'web', '2021-10-21 21:12:28', '2021-10-21 21:12:28'),
(3, 'customer_edit', 'web', '2021-10-24 23:31:06', '2021-10-24 23:31:06'),
(4, 'customer_delete', 'web', '2021-10-24 23:31:14', '2021-10-24 23:31:14'),
(5, 'company_list', 'web', '2021-10-24 23:31:29', '2021-10-24 23:31:29'),
(6, 'company_create', 'web', '2021-10-24 23:31:39', '2021-10-24 23:31:39'),
(7, 'company_edit', 'web', '2021-10-24 23:31:47', '2021-10-24 23:31:47'),
(8, 'company_delete', 'web', '2021-10-24 23:31:56', '2021-10-24 23:31:56'),
(9, 'event_list', 'web', '2021-10-24 23:36:59', '2021-10-24 23:36:59'),
(10, 'event_create', 'web', '2021-10-24 23:37:07', '2021-10-24 23:37:07'),
(11, 'event_edit', 'web', '2021-10-24 23:37:13', '2021-10-24 23:37:13'),
(12, 'event_delete', 'web', '2021-10-24 23:37:13', '2021-10-24 23:37:13'),
(13, 'user_list', 'web', '2021-10-27 19:36:47', '2021-10-27 19:36:47'),
(14, 'user_create', 'web', '2021-10-27 19:36:59', '2021-10-27 19:36:59'),
(15, 'user_delete', 'web', '2021-10-27 19:37:17', '2021-10-27 19:37:17'),
(16, 'user_edit', 'web', '2021-10-27 19:37:31', '2021-10-27 19:37:31'),
(17, 'role_list', 'web', '2021-10-27 19:37:46', '2021-10-27 19:37:46'),
(18, 'role_create', 'web', '2021-10-27 19:38:02', '2021-10-27 19:38:02'),
(19, 'role_delete', 'web', '2021-10-27 19:38:14', '2021-10-27 19:38:14'),
(20, 'role_edit', 'web', '2021-10-27 19:38:30', '2021-10-27 19:38:30'),
(21, 'permission_list', 'web', '2021-10-27 19:38:42', '2021-10-27 19:38:42'),
(22, 'permission_create', 'web', '2021-10-27 19:39:06', '2021-10-27 19:39:21'),
(23, 'permission_delete', 'web', '2021-10-27 19:39:35', '2021-10-27 19:39:35'),
(24, 'permission_edit', 'web', '2021-10-27 19:39:53', '2021-10-27 19:39:53'),
(25, 'contoh-doang', 'web', '2021-10-28 00:01:11', '2021-10-28 00:01:11'),
(26, 'contoh-lagi', 'web', '2021-10-28 00:01:59', '2021-10-28 00:01:59');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posisi_paramedis`
--

CREATE TABLE `posisi_paramedis` (
  `id_posisi` bigint(20) UNSIGNED NOT NULL,
  `nama_posisi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinsi`
--

CREATE TABLE `provinsi` (
  `id_provinsi` bigint(20) UNSIGNED NOT NULL,
  `nama_provinsi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinsi`
--

INSERT INTO `provinsi` (`id_provinsi`, `nama_provinsi`, `created_at`, `updated_at`) VALUES
(11, 'Aceh', NULL, NULL),
(12, 'Sumatera Utara', NULL, NULL),
(13, 'Sumatera Barat', NULL, NULL),
(14, 'Riau', NULL, NULL),
(15, 'Jambi', NULL, NULL),
(16, 'Sumatera Selatan', NULL, NULL),
(17, 'Bengkulu', NULL, NULL),
(18, 'Lampung', NULL, NULL),
(19, 'Kepulauan Bangka Belitung', NULL, NULL),
(21, 'Kepulauan Riau', NULL, NULL),
(31, 'DKI Jakarta', NULL, NULL),
(32, 'Jawa Barat', NULL, NULL),
(33, 'Jawa Tengah', NULL, NULL),
(34, 'DI Yogyakarta', NULL, NULL),
(35, 'Jawa Timur', NULL, NULL),
(36, 'Banten', NULL, NULL),
(51, 'Bali', NULL, NULL),
(52, 'Nusa Tenggara Barat', NULL, NULL),
(53, 'Nusa Tenggara Timur', NULL, NULL),
(61, 'Kalimantan Barat', NULL, NULL),
(62, 'Kalimantan Tengah', NULL, NULL),
(63, 'Kalimantan Selatan', NULL, NULL),
(64, 'Kalimantan Timur', NULL, NULL),
(65, 'Kalimantan Utara', NULL, NULL),
(71, 'Sulawesi Utara', NULL, NULL),
(72, 'Sulawesi Tengah', NULL, NULL),
(73, 'Sulawesi Selatan', NULL, NULL),
(74, 'Sulawesi Tenggara', NULL, NULL),
(75, 'Gorontalo', NULL, NULL),
(76, 'Sulawesi Barat', NULL, NULL),
(81, 'Maluku', NULL, NULL),
(82, 'Maluku Utara', NULL, NULL),
(91, 'Papua', NULL, NULL),
(92, 'Papua Barat', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(10, 'CEO', 'web', '2021-10-21 03:23:10', '2021-10-21 03:23:10'),
(12, 'Staff', 'web', '2021-10-24 21:34:50', '2021-10-24 21:34:50'),
(14, 'Admin', 'web', '2021-10-24 21:37:22', '2021-10-24 21:37:22'),
(15, 'Member', 'web', '2021-10-24 21:37:27', '2021-10-24 21:37:27'),
(16, 'asd', 'web', '2022-02-13 04:17:54', '2022-02-13 04:17:54'),
(17, 'gggg', 'web', '2022-02-13 04:19:17', '2022-02-13 04:19:17'),
(19, 'dfg', 'web', '2022-02-13 05:01:37', '2022-02-13 05:01:37'),
(20, 'anu', 'web', '2022-02-18 02:58:02', '2022-02-18 02:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(1, 15, '2021-10-29 00:15:06', '2021-10-29 00:15:06'),
(2, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(2, 15, '2021-10-29 00:18:34', '2021-10-29 00:18:34'),
(2, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(3, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(3, 15, '2021-10-29 00:26:11', '2021-10-29 00:26:11'),
(4, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(4, 15, '2021-10-29 00:15:06', '2021-10-29 00:15:06'),
(5, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(5, 15, '2021-10-29 00:18:34', '2021-10-29 00:18:34'),
(6, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(6, 15, '2021-10-29 00:26:11', '2021-10-29 00:26:11'),
(7, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(8, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(9, 14, '2021-10-24 23:42:47', '2021-10-24 23:42:47'),
(10, 14, '2021-10-25 23:45:33', '2021-10-25 23:45:33'),
(11, 14, '2021-10-25 23:45:03', '2021-10-25 23:45:03'),
(11, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(12, 14, '2021-10-25 23:45:03', '2021-10-25 23:45:03'),
(13, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(14, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(14, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(15, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(16, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(17, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(17, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(18, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(19, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(20, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(20, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(21, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(21, 15, '2021-10-29 00:38:15', '2021-10-29 00:38:15'),
(22, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(23, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(23, 20, '2022-02-18 02:58:22', '2022-02-18 02:58:22'),
(24, 14, '2021-10-27 19:41:14', '2021-10-27 19:41:14'),
(24, 15, '2021-10-29 00:38:15', '2021-10-29 00:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `rs`
--

CREATE TABLE `rs` (
  `id_rs` bigint(20) UNSIGNED NOT NULL,
  `id_angkatan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kotakab` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_dokter`
--

CREATE TABLE `sp_dokter` (
  `id_sp_dok` bigint(20) UNSIGNED NOT NULL,
  `id_sp_subsp_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dokter` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_subsp`
--

CREATE TABLE `sp_subsp` (
  `id_sp_subsp` bigint(20) UNSIGNED NOT NULL,
  `nama_sp_subsp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sp_subsp_rs`
--

CREATE TABLE `sp_subsp_rs` (
  `id_sp_subsp_rs` bigint(20) UNSIGNED NOT NULL,
  `id_sp_subsp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rs` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status_pasien`
--

CREATE TABLE `status_pasien` (
  `id_status_pasien` bigint(20) UNSIGNED NOT NULL,
  `nama_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_komando`
--

CREATE TABLE `sub_komando` (
  `id_subkomando` bigint(20) UNSIGNED NOT NULL,
  `nama_subkomando` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komando` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'dezha', 'admin@example.com', NULL, '$2y$10$E4EEhSD.iEH/Uok0u4Fgbed.GOr6HM8IYVvPlrPWh3UC7ejouJIIG', 'dy0ORjsO3BFeD6htAbfRMvnuQiQUw0ODBWjo0ZivqN761K9VWBWKK086vLiz', '2021-10-18 01:43:25', '2021-10-18 01:43:25'),
(9, 'ADNDINI', 'ADNI@DANIN.com', NULL, '$2y$10$KBq5fUU3P9kHIej42IoWxe8h7VxWKzF9FEQYyYFDVKux/M6qjbdBO', NULL, '2021-10-21 03:11:56', '2021-10-21 03:11:56'),
(10, 'bandu', 'bandu@gmnail.com', NULL, '$2y$10$AmL5A1/aWONveVKY4C/17eEWfPp/jeM3gxIO3S0oOw1LwAfcsyb2W', NULL, '2021-10-21 03:14:15', '2021-10-21 03:14:15'),
(11, 'adni', 'adni@adni.com', NULL, '$2y$10$iFmz94U2.BmHrbTf9vSRNuFx0RdGK75EsZWGFPDZBlKr1Bjy2r1.e', NULL, '2021-10-21 03:17:05', '2021-10-21 03:17:05'),
(12, 'adnu`', 'adnu@naduiad.com', NULL, '$2y$10$TyQNCeBu4AYLxIWl5TQi6ePkHPDSDJ9B.Y8IIMUN0skSuF5PCS2/2', NULL, '2021-10-21 03:18:57', '2021-10-21 03:18:57'),
(13, 'ichad', 'ichad@ichad.com', NULL, '$2y$10$5cLPpxi7YNf9wasSsfBLiOcXLn5YsYus6NUtcsipJP/3W060Z7HmK', NULL, '2021-10-21 03:24:17', '2021-10-21 03:24:17'),
(14, 'CEO', 'ceo@ceo.com', NULL, '$2y$10$NF.wxen47rsEYwUOCUGGg.bikyok.gU8nmlXMY20XDs4G0S3U7/XW', NULL, '2021-10-24 21:49:33', '2021-10-24 21:49:33'),
(15, 'dezha', 'dezha@dezha.com', NULL, '$2y$10$K/EDhiIkth0rq4tCu.wfKeU491TcgcexOiCHMtb81A/43ZUgX.Jd.', NULL, '2021-10-24 23:47:01', '2021-10-24 23:47:01'),
(16, 'susanto', 'susanto@su.com', NULL, '$2y$10$LgcIwWSW2vUqCm2TrBKO3.OrQoDtvYLjOf.bDOFfUfFfAZjVbmo.G', NULL, '2021-10-27 21:14:25', '2021-10-27 21:14:25'),
(17, 'fadli', 'fadli@fadli.com', NULL, '$2y$10$UJ/38oG52aYz0oTxPXVCt.oPn0hrsLeUibWviDeC/Gi1rx6GeoKay', NULL, '2021-10-27 22:57:46', '2021-10-27 22:57:46'),
(19, 'bikin nama lo', 'woy@gmail.com', NULL, '$2y$10$rT3mGO3S6zCxVqNSDwaDDOwU5/.2Dhy4B1nEeGZkhiPDGbqUfpiIm', NULL, '2021-10-27 23:08:11', '2021-10-27 23:08:11'),
(20, 'coba1', 'coba1@example.com', NULL, '$2y$10$nk7jQHYdjBQkBWelI7qgmeUMcsZukc6N2nJl0ubeJd1J1a981xA7e', NULL, '2021-10-28 21:40:46', '2021-10-28 21:40:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `angkatan`
--
ALTER TABLE `angkatan`
  ADD PRIMARY KEY (`id_angkatan`);

--
-- Indexes for table `bor`
--
ALTER TABLE `bor`
  ADD PRIMARY KEY (`id_bor`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_covid`
--
ALTER TABLE `data_covid`
  ADD PRIMARY KEY (`id_covid`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_pasien`
--
ALTER TABLE `jenis_pasien`
  ADD PRIMARY KEY (`id_jenis_pasien`);

--
-- Indexes for table `komando`
--
ALTER TABLE `komando`
  ADD PRIMARY KEY (`id_komando`);

--
-- Indexes for table `kota_kab`
--
ALTER TABLE `kota_kab`
  ADD PRIMARY KEY (`id_kotakab`),
  ADD KEY `kota_kab_id_provinsi_foreign` (`id_provinsi`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `paramedis`
--
ALTER TABLE `paramedis`
  ADD PRIMARY KEY (`id_paramedis`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posisi_paramedis`
--
ALTER TABLE `posisi_paramedis`
  ADD PRIMARY KEY (`id_posisi`);

--
-- Indexes for table `provinsi`
--
ALTER TABLE `provinsi`
  ADD PRIMARY KEY (`id_provinsi`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rs`
--
ALTER TABLE `rs`
  ADD PRIMARY KEY (`id_rs`);

--
-- Indexes for table `sp_dokter`
--
ALTER TABLE `sp_dokter`
  ADD PRIMARY KEY (`id_sp_dok`);

--
-- Indexes for table `sp_subsp`
--
ALTER TABLE `sp_subsp`
  ADD PRIMARY KEY (`id_sp_subsp`);

--
-- Indexes for table `sp_subsp_rs`
--
ALTER TABLE `sp_subsp_rs`
  ADD PRIMARY KEY (`id_sp_subsp_rs`);

--
-- Indexes for table `status_pasien`
--
ALTER TABLE `status_pasien`
  ADD PRIMARY KEY (`id_status_pasien`);

--
-- Indexes for table `sub_komando`
--
ALTER TABLE `sub_komando`
  ADD PRIMARY KEY (`id_subkomando`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `angkatan`
--
ALTER TABLE `angkatan`
  MODIFY `id_angkatan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bor`
--
ALTER TABLE `bor`
  MODIFY `id_bor` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `data_covid`
--
ALTER TABLE `data_covid`
  MODIFY `id_covid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pasien`
--
ALTER TABLE `jenis_pasien`
  MODIFY `id_jenis_pasien` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komando`
--
ALTER TABLE `komando`
  MODIFY `id_komando` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kota_kab`
--
ALTER TABLE `kota_kab`
  MODIFY `id_kotakab` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5309;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `paramedis`
--
ALTER TABLE `paramedis`
  MODIFY `id_paramedis` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posisi_paramedis`
--
ALTER TABLE `posisi_paramedis`
  MODIFY `id_posisi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinsi`
--
ALTER TABLE `provinsi`
  MODIFY `id_provinsi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rs`
--
ALTER TABLE `rs`
  MODIFY `id_rs` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sp_dokter`
--
ALTER TABLE `sp_dokter`
  MODIFY `id_sp_dok` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_subsp`
--
ALTER TABLE `sp_subsp`
  MODIFY `id_sp_subsp` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sp_subsp_rs`
--
ALTER TABLE `sp_subsp_rs`
  MODIFY `id_sp_subsp_rs` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_pasien`
--
ALTER TABLE `status_pasien`
  MODIFY `id_status_pasien` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_komando`
--
ALTER TABLE `sub_komando`
  MODIFY `id_subkomando` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kota_kab`
--
ALTER TABLE `kota_kab`
  ADD CONSTRAINT `kota_kab_id_provinsi_foreign` FOREIGN KEY (`id_provinsi`) REFERENCES `provinsi` (`id_provinsi`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
