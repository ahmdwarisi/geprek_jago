<?php
session_start();
require_once '../config/database.php';

// Proteksi: Hanya admin yang bisa akses proses ini
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/login_admin.php");
    exit();
}

// PROSES TAMBAH MENU
if (isset($_POST['add_menu'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga = intval($_POST['harga']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok = intval($_POST['stok']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
    $nama_file_baru = time() . "_" . uniqid() . "." . $ekstensi;

    if (move_uploaded_file($tmp_name, '../assets/img/' . $nama_file_baru)) {
        $sql = "INSERT INTO menu (nama_menu, harga, kategori, stok, deskripsi, gambar) 
                VALUES ('$nama', '$harga', '$kategori', '$stok', '$deskripsi', '$nama_file_baru')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: ../admin/kelola_menu.php?msg=success");
        } else {
            header("Location: ../admin/kelola_menu.php?msg=error_db");
        }
    } else {
        header("Location: ../admin/kelola_menu.php?msg=error_upload");
    }
}

// PROSES EDIT MENU
if (isset($_POST['edit_menu'])) {
    $id_menu = intval($_POST['id_menu']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga = intval($_POST['harga']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok = intval($_POST['stok']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah ada gambar baru diupload
    if ($_FILES['gambar']['error'] === 4) { // Tidak ada file gambar baru
        $sql = "UPDATE menu SET nama_menu='$nama', harga='$harga', kategori='$kategori', stok='$stok', deskripsi='$deskripsi' WHERE id_menu=$id_menu";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../admin/kelola_menu.php?msg=updated");
        } else {
            header("Location: ../admin/kelola_menu.php?msg=error_db");
        }
    } else { // Ada gambar baru diupload
        $gambar = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
        $nama_file_baru = time() . "_" . uniqid() . "." . $ekstensi;

        if (move_uploaded_file($tmp_name, '../assets/img/' . $nama_file_baru)) {
            if (file_exists('../assets/img/' . $gambar_lama) && $gambar_lama != '') { unlink('../assets/img/' . $gambar_lama); }
            $sql = "UPDATE menu SET nama_menu='$nama', harga='$harga', kategori='$kategori', stok='$stok', deskripsi='$deskripsi', gambar='$nama_file_baru' WHERE id_menu=$id_menu";
            if (mysqli_query($conn, $sql)) { header("Location: ../admin/kelola_menu.php?msg=updated"); } else { header("Location: ../admin/kelola_menu.php?msg=error_db"); }
        } else {
            header("Location: ../admin/kelola_menu.php?msg=error_upload");
        }
    }
}

// PROSES HAPUS MENU
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Ambil nama file gambar agar bisa dihapus dari folder assets
    $res = mysqli_query($conn, "SELECT gambar FROM menu WHERE id_menu = $id");
    $data = mysqli_fetch_assoc($res);
    
    if ($data) {
        unlink('../assets/img/' . $data['gambar']); // Hapus file fisik
        mysqli_query($conn, "DELETE FROM menu WHERE id_menu = $id");
        header("Location: ../admin/kelola_menu.php?msg=deleted");
    }
}