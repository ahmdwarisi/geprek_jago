<?php
session_start();
require_once '../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

// Mengambil data statistik dari database (seperti sebelumnya)
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM menu");
$total_menu = $query_total ? mysqli_fetch_assoc($query_total)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Geprek Jago</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Admin -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                Geprek Jago
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Admin Panel</div>
            </div>
            <nav class="admin-nav">
                <a href="dashboard_admin.php" class="admin-nav-link active">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="kelola_menu.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">restaurant_menu</span> Kelola Menu
                </a>
                <a href="kelola_pesanan.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">receipt_long</span> Pesanan
                </a>
                <a href="../process/auth.php?logout=true" class="admin-nav-link" style="margin-top: auto; color: var(--red-badge);">
                    <span class="material-symbols-outlined">logout</span> Keluar
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-content">
            <!-- Topbar -->
            <header class="admin-topbar">
                <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">Ringkasan Bisnis</h2>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span class="material-symbols-outlined" style="color: var(--text-muted); cursor: pointer;">notifications</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.875rem;">
                        <span class="material-symbols-outlined" style="background: var(--surface-border); padding: 0.5rem; border-radius: 50%; color: var(--primary);">person</span>
                        <?= isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin' ?>
                    </div>
                </div>
            </header>

            <!-- Content Canvas -->
            <main class="admin-main">
                <div class="admin-header">
                    <h1>Halo, <?= isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin' ?> 👋</h1>
                    <p>Berikut adalah update terbaru untuk warung Geprek Jago hari ini.</p>
                </div>

                <div class="grid-3" style="margin-bottom: 2rem;">
                    <!-- Card 1 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon emerald material-symbols-outlined">payments</span>
                            <span class="stat-badge">+12.5%</span>
                        </div>
                        <div>
                            <p class="stat-title">Pendapatan</p>
                            <div class="stat-value">Rp 45.200k</div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon blue material-symbols-outlined">receipt_long</span>
                        </div>
                        <div>
                            <p class="stat-title">Total Pesanan</p>
                            <div class="stat-value">1,284</div>
                        </div>
                    </div>

                    <!-- Card 3 (Data dari DB) -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon orange material-symbols-outlined">restaurant_menu</span>
                        </div>
                        <div>
                            <p class="stat-title">Total Menu Tersedia</p>
                            <div class="stat-value"><?= $total_menu ?></div>
                        </div>
                    </div>
                </div>

                <div class="admin-table-card">
                    <div class="admin-table-header">
                        <h3>Pesanan Terbaru</h3>
                        <a href="kelola_pesanan.php">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#GJ-9821</td>
                                    <td><div style="font-weight: 600;">Rina Susanti</div></td>
                                    <td style="text-align: center;"><span class="badge-warning">Proses</span></td>
                                    <td style="text-align: right; font-weight: 700;">Rp 64.000</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#GJ-9820</td>
                                    <td><div style="font-weight: 600;">Aditya Nugraha</div></td>
                                    <td style="text-align: center;"><span class="badge-warning" style="background: #d1fae5; color: #059669;">Selesai</span></td>
                                    <td style="text-align: right; font-weight: 700;">Rp 28.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>