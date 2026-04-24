<?php
require_once 'config/database.php';

$nama = 'Adminjago';
$email = 'halo@geprekjago.com'; 
$password_baru = 'admin123';
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT); // Mengacak password

// Cek apakah email sudah terdaftar
$cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");

if(mysqli_num_rows($cek) > 0) {
    // Jika akun sudah ada, perbarui passwordnya saja
    mysqli_query($conn, "UPDATE admin SET password = '$password_hash' WHERE email = '$email'");
    echo "<h1>Berhasil!</h1><p>Password untuk akun <b>$email</b> berhasil diperbarui ke format enkripsi yang benar.</p>";
} else {
    // Jika belum ada, buat akun baru
    mysqli_query($conn, "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$password_hash')");
    echo "<h1>Berhasil!</h1><p>Akun admin baru dibuat dengan aman.</p>";
}
echo "<p>Gunakan Email: <b>$email</b></p>";
echo "<p>Gunakan Password: <b>$password_baru</b></p>";
echo "<br><a href='admin/login_admin.php'>Klik di sini untuk mencoba Login</a>";