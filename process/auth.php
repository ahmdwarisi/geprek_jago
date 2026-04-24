<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Mencari admin berdasarkan email
    $query = "SELECT * FROM admin WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password dengan hash BCRYPT
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id_admin'];
            $_SESSION['admin_name'] = $row['nama'];
            
            header("Location: ../admin/dashboard_admin.php");
            exit();
        } else {
            // Password salah
            header("Location: ../admin/login_admin.php?error=wrong_password");
            exit();
        }
    } else {
        // Email tidak terdaftar
        header("Location: ../admin/login_admin.php?error=not_found");
        exit();
    }
}

// Log out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../admin/login_admin.php");
    exit();
}