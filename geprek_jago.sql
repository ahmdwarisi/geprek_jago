-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2026 at 05:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `id_admin` int NOT NULL,
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
  `id_keranjang` int NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `nama_menu` varchar(150) DEFAULT NULL,
  `deskripsi` text,
  `harga` int DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `stok` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
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
  `id_order` int NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `metode_pengiriman` enum('makan_di_tempat','delivery') DEFAULT NULL,
  `metode_pembayaran` enum('qris','ewallet','transfer','cash') DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `status` enum('pending','diproses','selesai') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `nama_pelanggan`, `no_hp`, `alamat`, `metode_pengiriman`, `metode_pembayaran`, `total_harga`, `status`, `created_at`) VALUES
(1, 'wsqs', '', 'axsa', 'makan_di_tempat', 'qris', 20000, 'selesai', '2026-04-24 11:40:57'),
(2, 'hsjahsaj', '', 'asjiidisi', 'makan_di_tempat', 'ewallet', 30000, 'selesai', '2026-04-24 11:45:25'),
(3, 'iuhdisu', '', 'iuwi', 'makan_di_tempat', 'qris', 10000, 'selesai', '2026-04-24 11:47:58'),
(4, 'sjahishi', '', 'iuwhiuqw', 'makan_di_tempat', 'cash', 30000, 'selesai', '2026-04-24 12:00:08'),
(5, 'iwiqeh', '', 'iwhiehi', 'makan_di_tempat', 'transfer', 15000, 'selesai', '2026-04-24 12:03:56'),
(6, 'ijooi', '5656565656665', 'uhihuii', 'delivery', 'qris', 21000, 'selesai', '2026-04-24 14:43:20'),
(7, 'jajaj', '877887878', 'jhahajjjhzvvzCzFV', 'delivery', 'cash', 36000, 'selesai', '2026-04-24 15:46:22'),
(8, 'iuhiui', '4568', 'hhhjjjfffggf', 'delivery', 'qris', 33600, 'selesai', '2026-04-25 01:40:17'),
(9, 'hhhhhh', '', 'hhhhhh', 'makan_di_tempat', 'ewallet', 100000, 'selesai', '2026-04-25 01:41:53'),
(10, 'nbnn', '', 'kkjj', 'makan_di_tempat', 'transfer', 30000, 'selesai', '2026-04-25 01:43:35'),
(11, 'uyuy', '', 'yyy', 'makan_di_tempat', 'qris', 15000, 'selesai', '2026-04-25 01:52:40'),
(12, 'ww', '', 'www', 'makan_di_tempat', 'qris', 20000, 'selesai', '2026-04-25 02:00:47'),
(13, 'uiui', '6567', 'juiuiu', 'delivery', 'transfer', 42000, 'selesai', '2026-04-25 07:44:25'),
(14, 'fadil', '', 'meja 1', 'makan_di_tempat', 'qris', 20000, 'pending', '2026-04-29 13:58:39'),
(15, 'juu', '', 'hgjg', 'makan_di_tempat', 'qris', 15000, 'selesai', '2026-05-13 05:25:40');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_detail` int NOT NULL,
  `id_order` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int NOT NULL,
  `id_menu` int DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
