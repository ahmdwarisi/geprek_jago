-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 11:19 AM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geprek_jago`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `email`, `password`) VALUES
(1, 'halo@geprekjago.com', '$2y$10$Vf.Bp74kT9hVNGRxtTDlEOA4Z9hnD/COK6enyIN4ULN5BM.dZnMQu');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `session_id`, `id_menu`, `jumlah`, `created_at`) VALUES
(1, 'session_abc123', 1, 2, '2026-05-21 03:00:00'),
(2, 'session_abc123', 4, 1, '2026-05-21 03:00:00'),
(3, 'session_abc123', 11, 3, '2026-05-21 03:00:00'),
(4, 'session_def456', 2, 1, '2026-05-21 04:30:00'),
(5, 'session_def456', 8, 2, '2026-05-21 04:30:00'),
(6, 'session_ghi789', 3, 1, '2026-05-21 05:15:00'),
(7, 'session_ghi789', 5, 1, '2026-05-21 05:15:00'),
(8, 'session_ghi789', 7, 1, '2026-05-21 05:15:00'),
(9, 'session_jkl012', 9, 2, '2026-05-21 06:45:00'),
(10, 'session_jkl012', 15, 1, '2026-05-21 06:45:00'),
(11, 'session_mno345', 1, 3, '2026-05-21 07:20:00'),
(12, 'session_mno345', 12, 2, '2026-05-21 07:20:00'),
(13, 'session_pqr678', 6, 1, '2026-05-21 08:00:00'),
(14, 'session_pqr678', 10, 1, '2026-05-21 08:00:00'),
(15, 'session_stu901', 13, 4, '2026-05-21 09:30:00'),
(16, 'session_vwx234', 2, 2, '2026-05-21 10:00:00'),
(17, 'session_vwx234', 3, 1, '2026-05-21 10:00:00'),
(18, 'session_yza567', 16, 2, '2026-05-21 11:15:00'),
(19, 'session_yza567', 17, 1, '2026-05-21 11:15:00'),
(20, 'session_bcd890', 18, 3, '2026-05-21 12:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `gambar`, `kategori`, `stok`, `created_at`) VALUES
(1, 'Paket Ayam Jago', '(Nasi + Ayam Geprek)', 12000, NULL, 'Paket Super Jago', 100, '2026-04-28 15:46:00'),
(2, 'Paket Hot Lava', '(Nasi + Ayam Hot Lava)', 12000, NULL, 'Paket Super Jago', 50, '2026-04-28 15:46:00'),
(3, 'Paket Black Mamba', '(Nasi + Ayam Black Mamba)', 12000, NULL, 'Paket Super Jago', 50, '2026-04-28 15:46:00'),
(4, 'Paket Komplit', '(Nasi + Ayam Geprek + Minum)', 14000, NULL, 'Paket Hemat Jago', 50, '2026-04-28 15:46:00'),
(5, 'Paket Nugget Jago', '(Isi 6 pcs)', 10000, NULL, 'Paket Hemat Jago', 30, '2026-04-28 15:46:00'),
(6, 'Paket Sosis Jago', '(Isi 6 pcs)', 10000, NULL, 'Paket Hemat Jago', 30, '2026-04-28 15:46:00'),
(7, 'Paket Jamur Enoki', '(Nasi + Jamur Enoki Crispy)', 8000, NULL, 'Paket Hemat Jago', 30, '2026-04-28 15:46:00'),
(8, 'Mie Combo', '(Mie + Ayam Geprek)', 13000, NULL, 'Paket Mie Jago', 40, '2026-04-28 15:46:00'),
(9, 'Mie Super', '(Mie + Ayam Geprek + Nasi)', 15000, NULL, 'Paket Mie Jago', 40, '2026-04-28 15:46:00'),
(10, 'Mie Hemat', '(Mie + Nugget/Sosis)', 12000, NULL, 'Paket Mie Jago', 40, '2026-04-28 15:46:00'),
(11, 'Es Teh', 'Minuman segar', 3000, NULL, 'Minuman', 100, '2026-04-28 15:46:00'),
(12, 'Ayam Geprek Saja', 'Ayam geprek tanpa nasi', 9000, NULL, 'Menu Lainnya', 50, '2026-04-28 15:48:18'),
(13, 'Nugget (6pcs)', 'Hanya nugget', 8000, NULL, 'Menu Lainnya', 30, '2026-04-28 15:48:18'),
(14, 'Sosis (6pcs)', 'Hanya sosis', 8000, NULL, 'Menu Lainnya', 30, '2026-04-28 15:48:18'),
(15, 'Nasi Putih', '1 Porsi Nasi', 8000, NULL, 'Menu Lainnya', 100, '2026-04-28 15:48:18'),
(16, 'Jamur Enoki', 'Hanya jamur enoki', 5000, NULL, 'Menu Lainnya', 30, '2026-04-28 15:48:18'),
(17, 'Mie Jago', 'Hanya mie jago', 4000, NULL, 'Menu Lainnya', 40, '2026-04-28 15:48:18'),
(18, 'Es Teh', 'Minuman segar', 3000, NULL, 'Minuman', 100, '2026-04-28 15:48:18'),
(19, 'lechy tea', 'Varian teh khusus', 4000, NULL, 'Minuman', 50, '2026-04-28 15:48:18'),
(20, 'Air Mineral', 'Air botol', 3000, NULL, 'Minuman', 100, '2026-04-28 15:48:18'),
(21, 'Tambah Sambal', 'Extra sambal', 2000, NULL, 'Extra', 100, '2026-04-28 15:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `metode_pengiriman` enum('makan_di_tempat','delivery') DEFAULT NULL,
  `metode_pembayaran` enum('qris','ewallet','transfer','cash') DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `status` enum('pending','diproses','selesai') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `nama_pelanggan`, `no_hp`, `alamat`, `deskripsi`, `metode_pengiriman`, `metode_pembayaran`, `total_harga`, `status`, `created_at`) VALUES
(1, 'Siska', '08123456701', 'Telang Indah', NULL, 'makan_di_tempat', 'cash', 12000, 'selesai', '2026-04-25 20:00:00'),
(2, 'adi', '08123456702', 'Gg. 1 Kamal', NULL, 'delivery', 'cash', 24000, 'selesai', '2026-04-25 21:15:00'),
(3, 'laura', '08123456703', 'Fakultas Teknik UTM', NULL, 'delivery', 'cash', 15000, 'selesai', '2026-04-25 22:30:00'),
(4, 'chelsy', '08123456704', 'Dormitory UTM', NULL, 'makan_di_tempat', 'cash', 14000, 'selesai', '2026-04-25 23:00:00'),
(5, 'Maya Sari', '08123456705', 'Jl. Raya Telang', NULL, 'makan_di_tempat', 'cash', 12000, 'selesai', '2026-04-26 20:00:00'),
(6, 'Dedi Kurnia', '08123456706', 'Perum Grahakamal', NULL, 'delivery', 'cash', 24000, 'selesai', '2026-04-26 21:45:00'),
(7, 'warisi', '08123456707', 'Fisib UTM', NULL, 'delivery', 'cash', 13000, 'selesai', '2026-04-26 22:15:00'),
(8, 'Rizky Pratama', '08123456708', 'Telang Indah Barat', NULL, 'makan_di_tempat', 'cash', 25000, 'selesai', '2026-04-26 23:30:00'),
(9, 'Laila Husna', '08123456709', 'Gg. Sabar Kamal', NULL, 'delivery', 'cash', 10000, 'selesai', '2026-04-27 04:30:00'),
(10, 'Fajar Siddiq', '08123456710', 'Depan UTM', NULL, 'makan_di_tempat', 'cash', 20000, 'selesai', '2026-04-27 20:30:00'),
(11, 'dyah ayu', '08123456711', 'Fakultas Ekonomi', NULL, 'delivery', 'cash', 15000, 'selesai', '2026-04-27 21:00:00'),
(12, 'Hendra', '08123456712', 'Jl. Kusuma Bangsa', NULL, 'makan_di_tempat', 'cash', 20000, 'selesai', '2026-04-27 22:00:00'),
(13, 'Yuni Artanti', '08123456713', 'Gg. Melati', NULL, 'delivery', 'cash', 13000, 'selesai', '2026-04-27 22:45:00'),
(14, 'Taufik', '08123456714', 'Lab. Informatika', NULL, 'delivery', 'cash', 28000, 'selesai', '2026-04-27 23:15:00'),
(15, 'Nihaa', '08123456715', 'Telang Timur', NULL, 'makan_di_tempat', 'cash', 12000, 'selesai', '2026-04-28 00:00:00'),
(16, 'Adit', '08123456716', 'Kos Hijau', NULL, 'makan_di_tempat', 'cash', 14000, 'selesai', '2026-04-28 04:00:00'),
(17, 'Indah Lestari', '08123456717', 'Grahakamal Blok A', NULL, 'delivery', 'cash', 22000, 'selesai', '2026-04-28 05:00:00'),
(18, 'Bambang', '08123456718', 'Jl. Kamal Raya', NULL, 'makan_di_tempat', 'cash', 8000, 'selesai', '2026-04-28 05:30:00'),
(19, 'Zulfa', '08123456719', 'Faperta UTM', NULL, 'delivery', 'cash', 13000, 'selesai', '2026-04-28 06:00:00'),
(20, 'fadil', '08123456720', 'Telang Indah Gg 2', NULL, 'makan_di_tempat', 'cash', 25000, 'selesai', '2026-04-28 06:30:00'),
(21, 'Mega', '08123456721', 'Belakang UTM', NULL, 'delivery', 'cash', 15000, 'selesai', '2026-04-28 07:00:00'),
(22, 'Guntur', '08123456722', 'Perumahan Telang', NULL, 'makan_di_tempat', 'cash', 12000, 'selesai', '2026-04-28 07:15:00'),
(23, 'Nadia', '08123456723', 'Samping Kampus', NULL, 'delivery', 'cash', 14000, 'selesai', '2026-04-28 07:30:00'),
(24, 'Fika', '08123456724', 'Jl. Raya Kamal', NULL, 'makan_di_tempat', 'cash', 20000, 'selesai', '2026-04-28 07:45:00'),
(25, 'Ratna', '08123456725', 'Fisib Gedung C', NULL, 'delivery', 'cash', 12000, 'selesai', '2026-04-28 08:00:00'),
(26, 'Dika', '08123456726', 'Kos Oranye', NULL, 'makan_di_tempat', 'cash', 8000, 'selesai', '2026-04-28 08:15:00'),
(27, 'Hani', '08123456727', 'Fakultas Hukum', NULL, 'delivery', 'cash', 13000, 'selesai', '2026-04-28 08:30:00'),
(28, 'Yoga', '08123456728', 'Telang Indah Gg 5', NULL, 'makan_di_tempat', 'cash', 24000, 'selesai', '2026-04-28 08:45:00'),
(29, 'Rara', '08123456729', 'Jl. Telang Jaya', NULL, 'delivery', 'cash', 15000, 'selesai', '2026-04-28 09:00:00'),
(30, 'Irfan', '08123456730', 'Depan Gerbang UTM', NULL, 'makan_di_tempat', 'cash', 12000, 'selesai', '2026-04-28 09:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_detail` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id_detail`, `id_order`, `id_menu`, `jumlah`, `harga`, `subtotal`) VALUES
(1, 1, 1, 1, 10000, 10000),
(2, 1, 2, 2, 5000, 10000),
(3, 2, 1, 2, 10000, 20000),
(4, 2, 2, 2, 5000, 10000),
(5, 3, 1, 1, 10000, 10000),
(6, 4, 1, 3, 10000, 30000),
(7, 5, 1, 1, 10000, 10000),
(8, 5, 2, 1, 5000, 5000),
(9, 6, 1, 1, 10000, 10000),
(10, 6, 2, 1, 5000, 5000),
(11, 7, 1, 1, 10000, 10000),
(12, 7, 2, 1, 5000, 5000),
(13, 8, 1, 2, 10000, 20000),
(14, 8, 2, 2, 5000, 10000),
(15, 9, 2, 20, 5000, 100000),
(16, 10, 2, 6, 5000, 30000),
(17, 11, 2, 3, 5000, 15000),
(18, 12, 2, 2, 5000, 10000),
(19, 12, 1, 1, 10000, 10000),
(20, 13, 3, 3, 12000, 36000);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id_review`, `id_menu`, `nama`, `rating`, `komentar`, `created_at`) VALUES
(1, 1, 'Siska', 5, 'Ayamnya krispi banget, sambalnya nampol!', '2026-04-28 02:00:00'),
(2, 2, 'adii', 4, 'Hot lavanya enak, tapi kurang pedas dikit buat saya.', '2026-04-28 02:30:00'),
(3, 3, 'Rina', 5, 'Black mamba unik banget rasanya, wajib coba guys!', '2026-04-28 20:15:00'),
(4, 4, 'Andi', 5, 'Paket komplit paling worth it buat kantong mahasiswa.', '2026-04-28 21:00:00'),
(5, 8, 'Maya', 4, 'Mie combonya porsinya banyak, kenyang pol.', '2026-04-28 22:45:00'),
(6, 9, 'Dedi', 5, 'Mie supernyaa nyuperrr.', '2026-04-29 19:20:00'),
(7, 11, 'Santi', 3, 'Es tehnya standar sih, tapi seger.', '2026-04-29 23:10:00'),
(8, 7, 'Rizky', 5, 'Jamur enokinya krispi parah, nagih!', '2026-04-30 00:00:00'),
(9, 19, 'Laila', 4, 'Lechy teanya enak, kerasa banget lecinya.', '2026-04-30 18:30:00'),
(10, 12, 'Fajar', 4, 'Ayam gepreknya gede, bumbunya meresap.', '2026-04-30 19:00:00'),
(11, 1, 'Dewi', 5, 'Sering beli di sini, kualitasnya konsisten.', '2026-04-30 19:45:00'),
(12, 10, 'Hendra', 4, 'Mie hemat emang beneran hemat buat akhir bulan haha.', '2026-04-30 20:20:00'),
(13, 21, 'Yuni', 5, 'Sambalnya beneran extra, mantap!', '2026-04-30 20:50:00'),
(14, 4, 'Taufik', 5, 'Pelayanan cepet, makanan dateng masih anget.', '2026-04-30 21:15:00'),
(15, 3, 'Putri', 2, 'Enak sih tapi nunggu antriannya agak lama.', '2026-04-30 21:45:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_keranjang_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_order_detail_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_detail_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
