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

// Mengambil data total pendapatan HARI INI (pesanan dengan status selesai)
$query_pendapatan = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM orders WHERE status = 'selesai' AND DATE(created_at) = CURDATE()");
$total_pendapatan = $query_pendapatan ? mysqli_fetch_assoc($query_pendapatan)['total'] : 0;

// Mengambil data total pesanan HARI INI
$query_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
$total_pesanan = $query_pesanan ? mysqli_fetch_assoc($query_pesanan)['total'] : 0;

// Mengambil 5 pesanan terbaru
$query_recent_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");

// Logika Grafik Penjualan
$chart_filter = $_GET['chart_filter'] ?? 'mingguan';
$chart_labels = [];
$chart_data = [];

if ($chart_filter === 'bulanan') {
    // Ambil rekap total pendapatan per bulan untuk tahun berjalan
    $bulan_indo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    $data_per_bulan = array_fill(1, 12, 0); // Default isi angka 0 untuk ke 12 bulan
    $query_chart = mysqli_query($conn, "SELECT MONTH(created_at) as bulan, SUM(total_harga) as total FROM orders WHERE status = 'selesai' AND YEAR(created_at) = YEAR(CURDATE()) GROUP BY MONTH(created_at)");
    while ($row = mysqli_fetch_assoc($query_chart)) {
        $data_per_bulan[$row['bulan']] = $row['total'];
    }
    foreach ($data_per_bulan as $bulan_num => $total) {
        $chart_labels[] = $bulan_indo[$bulan_num - 1];
        $chart_data[] = $total;
    }
} else {
    // Ambil rekap total pendapatan per hari untuk 7 hari terakhir
    $data_per_hari = [];
    for ($i = 6; $i >= 0; $i--) {
        $date_str = date('Y-m-d', strtotime("-$i days"));
        $data_per_hari[$date_str] = 0; // Default isi angka 0 untuk 7 hari terakhir
    }
    $query_chart = mysqli_query($conn, "SELECT DATE(created_at) as tanggal, SUM(total_harga) as total FROM orders WHERE status = 'selesai' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)");
    while ($row = mysqli_fetch_assoc($query_chart)) {
        $data_per_hari[$row['tanggal']] = $row['total'];
    }
    $nama_hari = [1 => 'SEN', 2 => 'SEL', 3 => 'RAB', 4 => 'KAM', 5 => 'JUM', 6 => 'SAB', 7 => 'MIN'];
    foreach ($data_per_hari as $tgl => $total) {
        $chart_labels[] = $nama_hari[date('N', strtotime($tgl))];
        $chart_data[] = $total;
    }
}

// Logika Best Seller Dinamis Mengikuti Filter Grafik
$bestseller_nama = 'Belum Ada Data';
$bestseller_terjual = 0;
$bestseller_desc = 'Belum ada data penjualan pada periode ini.';
$trend_icon_html = '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>'; // Default Naik

if ($chart_filter === 'bulanan') {
    $curr_where = "WHERE o.status = 'selesai' AND MONTH(o.created_at) = MONTH(CURDATE()) AND YEAR(o.created_at) = YEAR(CURDATE())";
    $prev_where = "WHERE o.status = 'selesai' AND MONTH(o.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(o.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
    $label_waktu = "bulan lalu";
} else {
    $curr_where = "WHERE o.status = 'selesai' AND DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $prev_where = "WHERE o.status = 'selesai' AND DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND DATE(o.created_at) < DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $label_waktu = "minggu lalu";
}

$query_bestseller = mysqli_query($conn, "SELECT m.id_menu, m.nama_menu, SUM(od.jumlah) as total_terjual FROM order_detail od JOIN orders o ON od.id_order = o.id_order JOIN menu m ON od.id_menu = m.id_menu $curr_where GROUP BY od.id_menu ORDER BY total_terjual DESC LIMIT 1");

if ($query_bestseller && mysqli_num_rows($query_bestseller) > 0) {
    $bestseller = mysqli_fetch_assoc($query_bestseller);
    $id_menu_bestseller = $bestseller['id_menu'];
    $bestseller_nama = $bestseller['nama_menu'];
    $bestseller_terjual = $bestseller['total_terjual'];

    // Ambil total terjual menu ini pada periode sebelumnya untuk membandingkan persentase
    $query_prev = mysqli_query($conn, "SELECT SUM(od.jumlah) as total_prev FROM order_detail od JOIN orders o ON od.id_order = o.id_order $prev_where AND od.id_menu = $id_menu_bestseller");
    $prev_data = mysqli_fetch_assoc($query_prev);
    $prev_terjual = $prev_data['total_prev'] ? $prev_data['total_prev'] : 0;

    $persentase = $prev_terjual > 0 ? round(abs((($bestseller_terjual - $prev_terjual) / $prev_terjual) * 100)) : 100;

    if ($bestseller_terjual > $prev_terjual) {
        $bestseller_desc = "Penjualan meningkat $persentase% dari $label_waktu!";
        $trend_icon_html = '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>';
    } elseif ($bestseller_terjual < $prev_terjual) {
        $bestseller_desc = "Penjualan menurun $persentase% dari $label_waktu.";
        $trend_icon_html = '<polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline>'; // Ikon panah ke bawah
    } else {
        $bestseller_desc = "Penjualan stabil sama dengan $label_waktu.";
        $trend_icon_html = '<line x1="2" y1="12" x2="22" y2="12"></line><polyline points="16 6 22 12 16 18"></polyline>'; // Ikon panah datar/stabil
    }
}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h1>Halo, <?= isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin Jago' ?> 👋</h1>
                    <p>Berikut adalah update terbaru untuk warung Geprek Jago hari ini.</p>
                </div>

                <div class="grid-3" style="margin-bottom: 2rem;">
                    <!-- Card 1 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon emerald material-symbols-outlined">payments</span>
                        </div>
                        <div>
                            <p class="stat-title">Pendapatan Hari Ini</p>
                            <div class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="admin-stat-card">
                        <div class="stat-icon-wrap">
                            <span class="stat-icon blue material-symbols-outlined">receipt_long</span>
                        </div>
                        <div>
                            <p class="stat-title">Pesanan Hari Ini</p>
                            <div class="stat-value"><?= number_format($total_pesanan, 0, ',', '.') ?></div>
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

                <!-- Container Layout untuk Chart & Best Seller -->
                <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem; align-items: stretch;">
                    <!-- Chart Penjualan -->
                    <div class="admin-table-card" style="flex: 1; min-width: 300px; margin-bottom: 0; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-radius: 20px; border: none;">
                        <div class="admin-table-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: none; padding: 30px 30px 0 30px;">
                            <h3 style="font-size: 1.25rem; color: var(--primary); font-weight: 800; margin: 0;">Statistik Penjualan</h3>
                            <div class="segmented-control">
                                <a href="?chart_filter=mingguan" class="segment-btn <?= $chart_filter == 'mingguan' ? 'active' : '' ?>">Minggu Ini</a>
                                <a href="?chart_filter=bulanan" class="segment-btn <?= $chart_filter == 'bulanan' ? 'active' : '' ?>">Bulan Ini</a>
                            </div>
                        </div>
                        <div style="position: relative; height: 350px; width: 100%; padding: 1.5rem 30px 30px 30px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Best Seller Card -->
                    <div class="card-best-seller" style="flex-shrink: 0; flex-grow: 1; max-width: 350px;">
                        <div>
                            <div class="badge-best-seller">BEST SELLER</div>
                            <h2 class="product-title"><?= htmlspecialchars($bestseller_nama) ?></h2>
                            <p class="product-description"><?= $bestseller_desc ?></p>
                        </div>
                        <div class="footer-card">
                            <div class="stats-sold">
                                <span class="label-terjual">TERJUAL</span>
                                <span class="count-terjual"><?= number_format($bestseller_terjual, 0, ',', '.') ?> Porsi</span>
                            </div>
                            <div class="icon-trend">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <?= $trend_icon_html ?>
                                </svg>
                            </div>
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
                                <?php
                                if ($query_recent_orders && mysqli_num_rows($query_recent_orders) > 0) {
                                    while ($order = mysqli_fetch_assoc($query_recent_orders)) {
                                        $badge_style = 'background: #fffbeb; color: #d97706;';
                                        $status_text = 'Menunggu';
                                        if ($order['status'] == 'diproses') {
                                            $badge_style = 'background: #dbeafe; color: #2563eb;';
                                            $status_text = 'Diproses';
                                        } elseif ($order['status'] == 'selesai') {
                                            $badge_style = 'background: #d1fae5; color: #059669;';
                                            $status_text = 'Selesai';
                                        }
                                ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#GJ-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td><div style="font-weight: 600;"><?= htmlspecialchars($order['nama_pelanggan']) ?></div></td>
                                    <td style="text-align: center;"><span class="badge-warning" style="<?= $badge_style ?>"><?= $status_text ?></span></td>
                                    <td style="text-align: right; font-weight: 700;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada pesanan terbaru.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Statistik Penjualan',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: '#114227', 
                    borderRadius: 8, 
                    borderSkipped: false,
                    barThickness: 45 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false 
                    },
                    tooltip: { callbacks: { label: function(context) { return 'Rp ' + context.parsed.y.toLocaleString('id-ID'); } } }
                },
                scales: {
                    y: {
                        display: false, 
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false, 
                            drawBorder: false
                        },
                        ticks: {
                            color: '#A0A0A0', 
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
</body>
</html>