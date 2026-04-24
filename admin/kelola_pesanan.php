<?php
session_start();
require_once '../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

// Mengambil data statistik pesanan dari database
$query_today = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
$total_today = $query_today ? mysqli_fetch_assoc($query_today)['total'] : 0;

$query_proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'diproses'");
$total_proses = $query_proses ? mysqli_fetch_assoc($query_proses)['total'] : 0;

$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'selesai'");
$total_selesai = $query_selesai ? mysqli_fetch_assoc($query_selesai)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Geprek Jago</title>
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
                <a href="dashboard_admin.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="kelola_menu.php" class="admin-nav-link">
                    <span class="material-symbols-outlined">restaurant_menu</span> Kelola Menu
                </a>
                <a href="kelola_pesanan.php" class="admin-nav-link active">
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
                <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">Geprek Jago</h2>
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
                <div class="admin-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h1>Kelola Pesanan</h1>
                        <p>Pantau dan kelola pesanan masuk hari ini.</p>
                    </div>
                    <button class="btn-primary">
                        <span class="material-symbols-outlined">download</span> Cetak Laporan
                    </button>
                </div>

                <div class="grid-3" style="margin-bottom: 2rem;">
                    <!-- Card 1 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon emerald material-symbols-outlined">shopping_cart</span>
                        </div>
                        <div>
                            <p class="stat-title">Total Pesanan Hari Ini</p>
                            <div class="stat-value"><?= $total_today ?></div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon orange material-symbols-outlined">pending_actions</span>
                        </div>
                        <div>
                            <p class="stat-title">Pesanan Diproses</p>
                            <div class="stat-value"><?= $total_proses ?></div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon blue material-symbols-outlined">check_circle</span>
                        </div>
                        <div>
                            <p class="stat-title">Pesanan Selesai</p>
                            <div class="stat-value"><?= $total_selesai ?></div>
                        </div>
                    </div>
                </div>

                <div class="admin-table-card">
                    <div class="admin-table-header" style="justify-content: flex-start; gap: 1rem;">
                        <button class="btn-primary" style="padding: 0.5rem 1rem;">Semua</button>
                        <button class="btn-outline" style="padding: 0.5rem 1rem; color: var(--text-muted); border-color: var(--surface-border);">Menunggu</button>
                        <button class="btn-outline" style="padding: 0.5rem 1rem; color: var(--text-muted); border-color: var(--surface-border);">Diproses</button>
                        <button class="btn-outline" style="padding: 0.5rem 1rem; color: var(--text-muted); border-color: var(--surface-border);">Selesai</button>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Waktu</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right;">Total Harga</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Mengambil data pesanan asli dari database
                                $query_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
                                if($query_orders && mysqli_num_rows($query_orders) > 0) {
                                    while($order = mysqli_fetch_assoc($query_orders)) {
                                        $status_text = ucfirst($order['status']);
                                ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#GJ-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td><div style="font-weight: 600;"><?= htmlspecialchars($order['nama_pelanggan']) ?></div></td>
                                    <td style="color: var(--text-muted); font-size: 0.75rem;"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td style="text-align: center;">
                                        <span class="badge-warning" <?php if($order['status']=='selesai') echo 'style="background: #d1fae5; color: #059669;"'; ?>><?= $status_text ?></span>
                                    </td>
                                    <td style="text-align: right; font-weight: 700;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                    <td style="text-align: center;">
                                        <a href="#" style="color: var(--primary); font-weight: 600; font-size: 0.875rem;">Detail</a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Belum ada pesanan masuk.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>