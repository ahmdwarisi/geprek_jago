<?php
// Aktifkan pesan error untuk keperluan debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

if ($conn) {
    echo "<h1 style='color: green;'>Koneksi Berhasil!</h1>";
    echo "<p>Database berhasil terhubung dengan sistem.</p>";
} else {
    echo "<h1 style='color: red;'>Koneksi Gagal!</h1>";
    echo "<p>Pesan Error: " . mysqli_connect_error() . "</p>";
}
?>