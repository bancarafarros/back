-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table sajadahku.donasi_histories
CREATE TABLE IF NOT EXISTS `donasi_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donasi_id` int(11) DEFAULT NULL,
  `ref_bank` int(11) DEFAULT NULL,
  `nomor_invoice` varchar(50) DEFAULT NULL,
  `kode_pembayaran` varchar(50) DEFAULT NULL,
  `nominal` decimal(20,2) DEFAULT NULL,
  `nama_pengirim` varchar(255) DEFAULT NULL,
  `tanggal_donasi` date DEFAULT NULL,
  `keterangan` text,
  `is_valid` enum('0','1') DEFAULT '0',
  `is_anonim` enum('0','1') DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sajadahku.donasi_histories: ~7 rows (approximately)
/*!40000 ALTER TABLE `donasi_histories` DISABLE KEYS */;
REPLACE INTO `donasi_histories` (`id`, `donasi_id`, `ref_bank`, `nomor_invoice`, `kode_pembayaran`, `nominal`, `nama_pengirim`, `tanggal_donasi`, `keterangan`, `is_valid`, `is_anonim`, `created_by`, `created_at`, `last_modified`) VALUES
	(1, 1, 1, NULL, NULL, 2000000.00, NULL, '2022-06-15', 'donasi anak yatim', '1', '1', 11, '2022-06-15 12:56:38', '2022-06-15 12:57:19'),
	(2, 1, 1, NULL, NULL, 3000000.00, NULL, '2022-06-15', 'donasi anak yatim', '1', '1', 11, '2022-06-15 12:56:38', '2022-06-15 12:57:19'),
	(3, 1, 1, NULL, NULL, 1000000.00, NULL, '2022-06-16', 'Ingin donasi aja sih', '0', '1', 11, '2022-06-16 08:53:39', NULL),
	(4, 1, 1, NULL, NULL, 1000000.00, NULL, '2022-06-16', 'Ingin donasi aja sih', '0', '1', 11, '2022-06-16 08:54:44', NULL),
	(5, 1, 1, NULL, NULL, 500000.00, NULL, '2022-06-16', 'Ingin donasi aja sih', '0', '1', 11, '2022-06-16 08:56:56', NULL),
	(6, 1, 1, NULL, NULL, 500000.00, NULL, '2022-06-16', 'Ingin donasi aja sih', '0', '1', 11, '2022-06-16 09:52:06', NULL),
	(7, 1, 1, NULL, NULL, 500000.00, NULL, '2022-06-16', 'Ingin donasi aja sih', '0', '1', 11, '2022-06-16 11:03:10', NULL);
/*!40000 ALTER TABLE `donasi_histories` ENABLE KEYS */;

-- Dumping structure for table sajadahku.jamaah_tabungan_pembayaran
CREATE TABLE IF NOT EXISTS `jamaah_tabungan_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabungan_id` char(36) NOT NULL DEFAULT '0',
  `ref_bank` int(11) NOT NULL DEFAULT '0',
  `nomer_invoice` varchar(50) DEFAULT NULL,
  `kode_pembayaran` varchar(50) NOT NULL,
  `nama_pengirim` varchar(255) DEFAULT NULL,
  `nominal` decimal(20,2) DEFAULT NULL,
  `is_valid` enum('1','0') DEFAULT '0',
  `expired_time` timestamp NULL DEFAULT NULL,
  `url_bukti` text,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sajadahku.jamaah_tabungan_pembayaran: ~3 rows (approximately)
/*!40000 ALTER TABLE `jamaah_tabungan_pembayaran` DISABLE KEYS */;
REPLACE INTO `jamaah_tabungan_pembayaran` (`id`, `tabungan_id`, `ref_bank`, `nomer_invoice`, `kode_pembayaran`, `nama_pengirim`, `nominal`, `is_valid`, `expired_time`, `url_bukti`, `created_by`, `modified_by`, `created_at`, `last_modified`) VALUES
	(31, '1', 37, 'INV-03215100001', '313592091979855', 'bagus', 600000.00, '1', '2022-06-21 03:21:51', NULL, 11, NULL, '2022-06-20 10:21:52', '2022-06-20 10:22:34'),
	(32, '1', 37, 'INV-04381300001', '327709174682076', 'bagus', 600000.00, '0', '2022-06-22 04:38:13', NULL, 11, NULL, '2022-06-21 11:38:14', NULL),
	(33, '1', 37, 'INV-05405700001', '786883116737988', 'bagus', 600000.00, '0', '2022-06-22 05:40:57', NULL, 11, NULL, '2022-06-21 12:40:57', NULL);
/*!40000 ALTER TABLE `jamaah_tabungan_pembayaran` ENABLE KEYS */;

-- Dumping structure for table sajadahku.konfirmasi_pembayaran
CREATE TABLE IF NOT EXISTS `konfirmasi_pembayaran` (
  `id_konfirmasi` char(36) NOT NULL,
  `jenis_pembayaran` enum('tabungan','infaq','sedekah','zakat') NOT NULL COMMENT '01: Setoran Awal Tabungan Umrah\r\n02:Tabungan Umrah\r\n03: DP Pendaftaran Umrah\r\n04: Umrah\r\n05: Pendaftaran Haji\r\n06: Tabungan Pelunasan Haji\r\n07: Pendaftaran Wisata Halal\r\n08: Wisata Halal',
  `nomor_invoice` varchar(30) NOT NULL,
  `tanggal_transfer` date NOT NULL COMMENT 'Default hari ini',
  `ref_bank_tujuan` int(11) NOT NULL DEFAULT '0' COMMENT 'Tolong nanti no rek Bank diisikan disini',
  `jumlah_dana` decimal(15,2) NOT NULL,
  `nama_pengirim` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telpon` varchar(15) DEFAULT NULL,
  `url_bukti_pembayaran` varchar(255) NOT NULL,
  `status` enum('01','02','03','04') DEFAULT NULL COMMENT '01: Baru\r\n02: Valid\r\n03: Tidak Valid',
  `id_user_validator` int(11) DEFAULT NULL,
  `waktu_validasi` datetime DEFAULT NULL,
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_konfirmasi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sajadahku.konfirmasi_pembayaran: ~1 rows (approximately)
/*!40000 ALTER TABLE `konfirmasi_pembayaran` DISABLE KEYS */;
REPLACE INTO `konfirmasi_pembayaran` (`id_konfirmasi`, `jenis_pembayaran`, `nomor_invoice`, `tanggal_transfer`, `ref_bank_tujuan`, `jumlah_dana`, `nama_pengirim`, `email`, `no_telpon`, `url_bukti_pembayaran`, `status`, `id_user_validator`, `waktu_validasi`, `created_time`) VALUES
	('3a60e3c4-f048-11ec-a150-d8c4975e5ef9', 'tabungan', 'INV-03215100001', '2022-06-20', 37, 600000.00, '', NULL, NULL, '', '02', NULL, NULL, '2022-06-20 10:22:34');
/*!40000 ALTER TABLE `konfirmasi_pembayaran` ENABLE KEYS */;

-- Dumping structure for table sajadahku.ref_bank
CREATE TABLE IF NOT EXISTS `ref_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `fee` int(11) NOT NULL DEFAULT '0',
  `group` enum('Virtual Account','Convenience Store','E-Wallet') DEFAULT NULL,
  `icon_url` varchar(255) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `is_active` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table sajadahku.ref_bank: ~24 rows (approximately)
/*!40000 ALTER TABLE `ref_bank` DISABLE KEYS */;
REPLACE INTO `ref_bank` (`id`, `nama`, `fee`, `group`, `icon_url`, `kode`, `is_active`) VALUES
	(34, 'Maybank Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/ZT91lrOEad1582929126.png', 'MYBVA', '1'),
	(35, 'Permata Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/szezRhAALB1583408731.png', 'PERMATAVA', '1'),
	(36, 'BNI Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/n22Qsh8jMa1583433577.png', 'BNIVA', '1'),
	(37, 'BRI Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/8WQ3APST5s1579461828.png', 'BRIVA', '1'),
	(38, 'Mandiri Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/T9Z012UE331583531536.png', 'MANDIRIVA', '1'),
	(39, 'Sinarmas Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/KHcqcmqVFQ1607091889.png', 'SMSVA', '1'),
	(40, 'BRI Virtual Account (Open Payment)', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/OnIv6Aawbp1607429662.png', 'BRIVAOP', '1'),
	(41, 'CIMB Niaga Virtual Account (Open Payment)', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/kU6OjhucMD1607538212.png', 'CIMBVAOP', '1'),
	(42, 'Danamon Virtual Account (Open Payment)', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/nVVFmyjGzq1608100282.png', 'DANAMONOP', '1'),
	(43, 'Muamalat Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/GGwwcgdYaG1611929720.png', 'MUAMALATVA', '1'),
	(44, 'CIMB Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/WtEJwfuphn1614003973.png', 'CIMBVA', '1'),
	(45, 'Sahabat Sampoerna Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/YAN25leid81631608321.png', 'SAMPOERNAV', '1'),
	(46, 'Hana Bank Virtual Account (Open Payment)', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/ml13c9N7hk1631608749.png', 'HANAVAOP', '1'),
	(47, 'BSI Virtual Account', 4250, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/tEclz5Assb1643375216.png', 'BSIVA', '1'),
	(48, 'BSI Virtual Account (Open Payment)', 3333, 'Virtual Account', 'https://tripay.co.id/images/payment-channel/zsJZthjjYR1644239274.png', 'BSIVAOP', '1'),
	(49, 'Alfamart', 6000, 'Convenience Store', 'https://tripay.co.id/images/payment-channel/jiGZMKp2RD1583433506.png', 'ALFAMART', '1'),
	(50, 'Indomaret', 3500, 'Convenience Store', 'https://tripay.co.id/images/payment-channel/zNzuO5AuLw1583513974.png', 'INDOMARET', '1'),
	(51, 'Alfamidi', 6000, 'Convenience Store', 'https://tripay.co.id/images/payment-channel/aQTdaUC2GO1593660384.png', 'ALFAMIDI', '1'),
	(52, 'OVO', 0, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/fH6Y7wDT171586199243.png', 'OVO', '1'),
	(53, 'QRIS (ShopeePay)', 750, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/BpE4BPVyIw1605597490.png', 'QRIS', '1'),
	(54, 'QRIS (Open Payment)', 750, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/Iakuzalc3N1614953076.png', 'QRISOP', '1'),
	(55, 'QRIS (Customable)', 750, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/m9FtFwaBCg1623157494.png', 'QRISC', '1'),
	(56, 'QRIS (Open Payment - Customable)', 750, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/Cs6kKwxslt1623329615.png', 'QRISCOP', '1'),
	(57, 'QRIS (DANA)', 750, 'E-Wallet', 'https://tripay.co.id/images/payment-channel/BhzaL4jdm11637547059.png', 'QRISD', '1');
/*!40000 ALTER TABLE `ref_bank` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
