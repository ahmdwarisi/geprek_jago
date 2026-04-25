
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `geprek_jago`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `email`, `password`) VALUES
(1, 'halo@geprekjago.com', '$2y$10$Vf.Bp74kT9hVNGRxtTDlEOA4Z9hnD/COK6enyIN4ULN5BM.dZnMQu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
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
-- Struktur dari tabel `menu`
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
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `gambar`, `kategori`, `stok`, `created_at`) VALUES
(1, 'Paket Geprek Jumbo', 'ini lagi coba aja ya', 10000, '1777012180_69eb0dd46900c.avif', 'Makanan', 47, '2026-04-24 06:29:40'),
(2, 'Es Teh', 'esss', 5000, '1777028636_69eb4e1cbab0b.jpg', 'Minuman', 0, '2026-04-24 11:03:56'),
(3, 'Paket Ayam Jago', '(Nasi + Ayam Geprek)', 12000, '1777102923_69ec704b0c5f8.jpg', 'Paket Super Jago', 0, '2026-04-25 07:42:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
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
-- Dumping data untuk tabel `orders`
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
(13, 'uiui', '6567', 'juiuiu', 'delivery', 'transfer', 42000, 'selesai', '2026-04-25 07:44:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_detail`
--

CREATE TABLE `order_detail` (
  `id_detail` int NOT NULL,
  `id_order` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `order_detail`
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
-- Struktur dari tabel `review`
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
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indeks untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;
COMMIT;

