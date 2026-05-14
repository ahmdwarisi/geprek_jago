-- Menambahkan kolom 'catatan' ke tabel 'order_detail' jika belum ada
ALTER TABLE `order_detail` 
ADD COLUMN `catatan` TEXT DEFAULT NULL AFTER `subtotal`;
