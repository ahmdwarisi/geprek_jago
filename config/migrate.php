<?php
// Auto-migration untuk menambahkan kolom yang diperlukan
// File ini di-include dari config/database.php

if (isset($conn)) {
    // Check apakah kolom 'deskripsi' sudah ada di tabel 'orders'
    $check_deskripsi = mysqli_query($conn, "SHOW COLUMNS FROM `orders` LIKE 'deskripsi'");
    
    if (mysqli_num_rows($check_deskripsi) == 0) {
        // Kolom belum ada, tambahkan
        $alter_query = "ALTER TABLE `orders` ADD COLUMN `deskripsi` TEXT DEFAULT NULL AFTER `alamat`";
        mysqli_query($conn, $alter_query);
    }
}
