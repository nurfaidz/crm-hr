-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2022 at 11:24 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workspace`
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

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(10) UNSIGNED NOT NULL,
  `branch_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `created_at`, `updated_at`, `company_id`) VALUES
(1, 'UD Pertiwi', NULL, NULL, 1),
(2, 'CV Nasyiah Hassanah (Persero) Tbk', NULL, NULL, 1),
(3, 'UD Nashiruddin', NULL, NULL, 1),
(4, 'UD Iswahyudi Maryati', NULL, NULL, 1),
(5, 'Perum Pratiwi Maheswara', NULL, NULL, 1),
(6, 'PD Damanik', NULL, NULL, 1),
(7, 'UD Riyanti Salahudin (Persero) Tbk', NULL, NULL, 1),
(8, 'Perum Haryanti Siregar (Persero) Tbk', NULL, NULL, 1),
(9, 'UD Yulianti', NULL, NULL, 1),
(10, 'Perum Purwanti Riyanti', NULL, NULL, 1),
(11, 'UD Hidayat Tbk', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_register` date NOT NULL,
  `company_expired` date NOT NULL,
  `status` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `company_email`, `company_address`, `company_phone`, `company_register`, `company_expired`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Putri Purwanti', 'mulyani.tantri@yahoo.com', 'Kpg. Pasirkoja No. 825, Administrasi Jakarta Barat 99823, Jateng', '028 4685 6818', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(2, 'Martana Hidayanto S.E.', 'icha.nasyidah@yahoo.com', 'Ki. Sumpah Pemuda No. 297, Tebing Tinggi 57331, NTT', '(+62) 809 660 213', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(3, 'Paramita Fujiati', 'qnovitasari@hutasoit.desa.id', 'Dk. Baabur Royan No. 412, Sorong 15943, Malut', '0828 6765 779', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(4, 'Unggul Prasetyo', 'ggunawan@nurdiyanti.org', 'Ds. Jaksa No. 681, Sorong 71389, Kalbar', '(+62) 268 4655 886', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(5, 'Edward Sihombing', 'caket49@manullang.biz', 'Ki. Jagakarsa No. 13, Pekalongan 57298, DKI', '(+62) 517 3943 0559', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(6, 'Irwan Januar', 'castuti@yahoo.com', 'Dk. Achmad No. 793, Parepare 15988, Bali', '0396 9778 078', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(7, 'Koko Balangga Januar S.Pd', 'gara.utama@yahoo.com', 'Psr. Ters. Pasir Koja No. 448, Langsa 29658, NTB', '(+62) 419 9264 643', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(8, 'Victoria Lestari', 'lwacana@rahayu.asia', 'Gg. Bara Tambar No. 711, Sibolga 18468, Sumsel', '0972 3200 071', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(9, 'Sakti Sirait', 'hagustina@yahoo.co.id', 'Jln. Casablanca No. 488, Batam 79743, Jambi', '0228 1711 412', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(10, 'Puji Lidya Agustina M.TI.', 'mitra89@gmail.com', 'Jr. Rumah Sakit No. 227, Kediri 14086, Kaltim', '0410 7028 2770', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(11, 'Siti Puspita', 'faizah.mardhiyah@safitri.ac.id', 'Ki. Abdul. Muis No. 923, Jayapura 64453, NTB', '(+62) 22 2142 9286', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(12, 'Xanana Budiman', 'garda79@andriani.id', 'Kpg. Yohanes No. 926, Gunungsitoli 48683, Kepri', '(+62) 25 5786 254', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(13, 'Anom Nugroho', 'kyolanda@gmail.co.id', 'Dk. Baha No. 497, Sungai Penuh 96181, Lampung', '(+62) 644 3418 624', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(14, 'Uli Purwanti', 'gara.sudiati@haryanti.co', 'Dk. Yosodipuro No. 122, Tebing Tinggi 68200, Jateng', '0579 7488 558', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(15, 'Bagiya Rajata', 'cahya.riyanti@yahoo.co.id', 'Dk. Jambu No. 494, Bogor 11134, Sulbar', '(+62) 915 9112 080', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(16, 'Eli Novitasari M.Ak', 'victoria.hariyah@hidayat.mil.id', 'Psr. Surapati No. 135, Sabang 69336, DKI', '(+62) 247 3627 2484', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(17, 'Kunthara Mandala', 'wisnu.yuliarti@mulyani.com', 'Ds. Uluwatu No. 245, Dumai 51193, Bali', '(+62) 519 6436 6010', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(18, 'Zulfa Purnawati', 'oliva.mandala@hutasoit.tv', 'Ki. Reksoninten No. 867, Palembang 89579, Kaltara', '0730 6661 221', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(19, 'Malika Purnawati', 'dwi.lailasari@latupono.in', 'Jr. Yohanes No. 315, Magelang 72360, Sulut', '(+62) 854 4435 633', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(20, 'Darijan Damanik', 'xsantoso@prasetya.biz.id', 'Ds. Bank Dagang Negara No. 718, Samarinda 40463, Malut', '023 6008 525', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(21, 'Gangsa Lukita Nainggolan', 'dyolanda@halim.id', 'Gg. Bak Mandi No. 590, Batam 80494, Kalteng', '(+62) 550 8881 7469', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(22, 'Nabila Wijayanti S.Psi', 'gandewa10@fujiati.go.id', 'Gg. Dahlia No. 348, Palu 16335, Kaltara', '0787 8096 636', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(23, 'Septi Clara Agustina M.Ak', 'hutagalung.darmaji@winarsih.org', 'Kpg. Agus Salim No. 870, Pagar Alam 63690, Malut', '(+62) 380 6866 415', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(24, 'Maya Yulianti M.Pd', 'usada.purwadi@winarsih.net', 'Jr. Bah Jaya No. 900, Kediri 45678, Sulteng', '0450 4290 6414', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(25, 'Melinda Agustina', 'laksmiwati.suci@yahoo.co.id', 'Kpg. Siliwangi No. 994, Padangsidempuan 82559, Kalsel', '(+62) 308 3841 3695', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(26, 'Fathonah Ifa Novitasari M.Farm', 'habibi.pangeran@yahoo.co.id', 'Kpg. Cokroaminoto No. 975, Cilegon 43214, Jambi', '0506 5954 308', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(27, 'Jagaraga Tamba', 'raharja.manullang@habibi.my.id', 'Kpg. Gegerkalong Hilir No. 587, Tidore Kepulauan 50314, Kalsel', '0917 5046 752', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(28, 'Talia Bella Puspasari S.Pt', 'balidin53@gmail.co.id', 'Gg. Sukajadi No. 685, Tidore Kepulauan 48585, Kepri', '0978 0288 7888', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(29, 'Karimah Elma Pudjiastuti S.Psi', 'mahendra.aditya@andriani.web.id', 'Jr. Lembong No. 674, Bogor 63569, Bengkulu', '(+62) 22 5947 551', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL),
(30, 'Titi Malika Melani S.E.I', 'prasetyo96@megantara.biz', 'Jln. Sudiarto No. 35, Banjarbaru 65893, Sumut', '0740 2168 753', '2022-03-14', '2022-04-14', 'aaa', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_status`
--

CREATE TABLE `company_status` (
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `status_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_short` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_long` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_status`
--

INSERT INTO `company_status` (`status_id`, `status_code`, `status_short`, `status_long`, `created_at`, `updated_at`) VALUES
(1, 'unv', 'unverified', 'Belum Verifikasi Email', NULL, NULL),
(2, 'ver', 'verify', 'Sudah Verifikasi Email', NULL, NULL),
(3, 'act', 'active', 'Aktif', NULL, NULL),
(4, 'exp', 'expired', 'Akun Sudah Kadaluarsa', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nohp` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_branch_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `code`, `department_name`, `department_branch_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Komisaris', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(2, NULL, 'Direktur', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(3, NULL, 'Sekretariat', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(4, NULL, 'Akunting', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(5, NULL, 'Keagenan', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(6, NULL, 'Keuangan', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(7, NULL, 'Logistik Kapal', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(8, NULL, 'Pemasaran dan Operasi', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(9, NULL, 'SMR-SPI', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(10, NULL, 'Kepala Cabang Surabaya', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(11, NULL, 'Kepala Sekretariat', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(12, NULL, 'Operasional', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(13, NULL, 'Accounting', 1, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(14, NULL, 'Kepala Cabang', 2, '2022-03-14 13:28:17', '2022-03-14 13:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `designation_id` int(10) UNSIGNED NOT NULL,
  `designation_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation_department_id` int(11) NOT NULL,
  `user_level` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status_tunjangan_jabatan` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`designation_id`, `designation_name`, `designation_department_id`, `user_level`, `status_tunjangan_jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Dimas Utama', 1, '1', 0, NULL, NULL),
(2, 'Chelsea Hartati S.Farm', 2, '1', 0, NULL, NULL),
(3, 'Maria Lestari', 1, '1', 0, NULL, NULL),
(4, 'Zelda Mandasari', 1, '1', 0, NULL, NULL),
(5, 'Sadina Wijayanti', 1, '1', 0, NULL, NULL),
(6, 'Rini Yuliarti', 1, '1', 0, NULL, NULL),
(7, 'Gantar Sakti Sihotang M.Ak', 1, '1', 0, NULL, NULL),
(8, 'Febi Maya Novitasari', 1, '1', 0, NULL, NULL),
(9, 'Sakura Wastuti', 1, '1', 0, NULL, NULL),
(10, 'Purwanto Kusumo', 1, '1', 0, NULL, NULL),
(11, 'Cawisono Arta Permadi S.Pd', 1, '1', 0, NULL, NULL);

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
  `nama_event` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_event` char(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finish` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `kota_kab`
--

CREATE TABLE `kota_kab` (
  `id_kotakab` bigint(20) UNSIGNED NOT NULL,
  `nama_kotakab` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_provinsi` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `level_mealtrans`
--

CREATE TABLE `level_mealtrans` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL,
  `meal_transport` double(10,2) DEFAULT NULL,
  `tunjangan_jabatan` int(11) NOT NULL DEFAULT 0,
  `tunjangan_kemahalan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `level_mealtrans`
--

INSERT INTO `level_mealtrans` (`id`, `name`, `level`, `meal_transport`, `tunjangan_jabatan`, `tunjangan_kemahalan`, `created_at`, `updated_at`) VALUES
(1, 'Komisaris', 1, 2000000.00, 0, 0, NULL, NULL),
(2, 'Direktur', 2, 2000000.00, 0, 0, NULL, NULL),
(3, 'Manajer', 3, 2000000.00, 0, 0, NULL, NULL),
(4, 'Kasi', 4, 2000000.00, 0, 0, NULL, NULL),
(5, 'Pelaksana', 5, 2000000.00, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(5, '2021_10_18_060904_create_customer_table', 1),
(6, '2021_10_18_061535_create_event_table', 1),
(7, '2021_10_18_061926_create_company_table', 1),
(8, '2021_10_21_035353_create_permission_tables', 1),
(9, '2021_10_25_031902_add_updated_at_to_role_has', 1),
(10, '2021_10_28_035220_drop_column_roleid_table_user', 1),
(11, '2022_02_12_124809_create_angkatan', 1),
(12, '2022_02_12_130747_create_provinsi', 1),
(13, '2022_02_12_130805_create_kota_kab', 1),
(14, '2022_02_12_133327_create_dokter', 1),
(15, '2022_02_12_133340_create_sp_dokter', 1),
(16, '2022_02_12_133458_create_posisi_paramedis', 1),
(17, '2022_02_12_133510_create_paramedis', 1),
(18, '2022_02_12_134906_create_sp_subsp', 1),
(19, '2022_02_12_134924_create_sp_subsp_rs', 1),
(20, '2022_02_12_134936_create_rs', 1),
(21, '2022_02_12_141122_create_jenis_pasien', 1),
(22, '2022_02_12_141221_create_status_pasien', 1),
(23, '2022_02_12_141510_create_data_covid', 1),
(24, '2022_02_12_141526_create_bor', 1),
(25, '2022_02_18_180642_create_companies_table', 1),
(26, '2022_02_19_134331_create_company_status', 1),
(27, '2022_02_21_152015_create_branches_table', 1),
(28, '2022_03_02_143431_create_departments_table', 1),
(29, '2022_03_02_150104_create_designation_table', 1),
(30, '2022_03_06_151138_create_level_mealtrans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

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
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'master_company.list', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(2, 'master_company.create', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(3, 'master_company.edit', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(4, 'master_company.delete', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(5, 'branch_company.list', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(6, 'branch_company.create', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(7, 'branch_company.edit', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(8, 'branch_company.delete', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(9, 'department_company.list', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(10, 'department_company.create', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(11, 'department_company.edit', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(12, 'department_company.delete', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(2, 'worker', 'web', '2022-03-14 13:28:17', '2022-03-14 13:28:17');

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
(1, 1, NULL, NULL),
(2, 1, NULL, NULL),
(3, 1, NULL, NULL),
(4, 1, NULL, NULL),
(5, 1, NULL, NULL),
(6, 1, NULL, NULL),
(7, 1, NULL, NULL),
(8, 1, NULL, NULL),
(9, 1, NULL, NULL),
(10, 1, NULL, NULL),
(11, 1, NULL, NULL),
(12, 1, NULL, NULL);

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@gmail.com', NULL, '$2y$10$1M5ZyEBCbAVNRXe6I2A1quizT1AJfPorlSuhiCkRF.7uRRvo2701K', NULL, '2022-03-14 13:28:17', '2022-03-14 13:28:17'),
(2, 'Example worker user', 'worker@gmail.com', '2022-03-14 13:28:17', '$2y$10$1Q/amH0TXL0SuGZSd6GuYO1l3j43MS79kuMx9mbn9CUAvoV90jaPy', 'WjWVi16EG9', '2022-03-14 13:28:17', '2022-03-14 13:28:17');

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
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`),
  ADD UNIQUE KEY `branches_branch_name_unique` (`branch_name`),
  ADD KEY `branches_company_id_foreign` (`company_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `companies_company_name_unique` (`company_name`),
  ADD UNIQUE KEY `companies_company_email_unique` (`company_email`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_status`
--
ALTER TABLE `company_status`
  ADD PRIMARY KEY (`status_id`);

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
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_department_name_unique` (`department_name`),
  ADD UNIQUE KEY `department_code_unique` (`code`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`designation_id`),
  ADD UNIQUE KEY `designation_designation_name_unique` (`designation_name`);

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
-- Indexes for table `kota_kab`
--
ALTER TABLE `kota_kab`
  ADD PRIMARY KEY (`id_kotakab`),
  ADD KEY `kota_kab_id_provinsi_foreign` (`id_provinsi`);

--
-- Indexes for table `level_mealtrans`
--
ALTER TABLE `level_mealtrans`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_angkatan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bor`
--
ALTER TABLE `bor`
  MODIFY `id_bor` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_status`
--
ALTER TABLE `company_status`
  MODIFY `status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_covid`
--
ALTER TABLE `data_covid`
  MODIFY `id_covid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `designation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `kota_kab`
--
ALTER TABLE `kota_kab`
  MODIFY `id_kotakab` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `level_mealtrans`
--
ALTER TABLE `level_mealtrans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `paramedis`
--
ALTER TABLE `paramedis`
  MODIFY `id_paramedis` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id_provinsi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rs`
--
ALTER TABLE `rs`
  MODIFY `id_rs` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
