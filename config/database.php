<?php
  // Mencegah error mentah tampil di layar user (Sesuai setelan kamu)
  ini_set('display_errors', 0);
  error_reporting(0);

  $host     = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'geprek_jago';

  $conn = mysqli_connect($host, $username, $password, $database);

  if (!$conn) {
      // Catat error ke log internal, tapi tampilkan pesan ramah ke user
      error_log("Koneksi Database Gagal: " . mysqli_connect_error());
      die("Mohon maaf, server sedang dalam pemeliharaan.");
  }

  mysqli_set_charset($conn, 'utf8');
?>