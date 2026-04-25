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

// Logika Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
$where_clause = "";
if ($filter === 'pending') $where_clause = "WHERE status = 'pending'";
elseif ($filter === 'diproses') $where_clause = "WHERE status = 'diproses'";
elseif ($filter === 'selesai') $where_clause = "WHERE status = 'selesai'";

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
                    <form action="cetak_laporan.php" method="GET" target="_blank" style="display: flex; gap: 0.5rem; align-items: center; margin: 0;">
                        <select name="periode" id="periodeSelect" class="form-input" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: var(--surface); border: 1px solid var(--surface-border); font-family: inherit; font-size: 0.875rem; width: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.08); cursor: pointer; transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" required>
                            <option value="" disabled selected>Pilih Periode...</option>
                            <option value="harian">Harian (Hari Ini)</option>
                            <option value="mingguan">Mingguan (7 Hari Terakhir)</option>
                            <option value="bulanan">Bulanan (Bulan Ini)</option>
                            <option value="kustom">Kustom (Pilih Tanggal)</option>
                            <option value="semua">Semua Waktu</option>
                        </select>
                        <div id="kustomTanggal" style="display: none; gap: 0.5rem; align-items: center;">
                            <input type="date" name="tgl_mulai" class="form-input" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid var(--surface-border); font-size: 0.875rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <span style="font-size: 0.875rem; color: var(--text-muted); font-weight: bold;">s.d.</span>
                            <input type="date" name="tgl_selesai" class="form-input" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid var(--surface-border); font-size: 0.875rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        </div>
                        <button type="submit" class="btn-primary">
                            <span class="material-symbols-outlined">print</span> Cetak Laporan
                        </button>
                    </form>
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
                        <a href="?filter=semua" class="<?= $filter == 'semua' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.5rem 1rem; <?= $filter != 'semua' ? 'color: var(--text-muted); border-color: var(--surface-border);' : '' ?>">Semua</a>
                        <a href="?filter=pending" class="<?= $filter == 'pending' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.5rem 1rem; <?= $filter != 'pending' ? 'color: var(--text-muted); border-color: var(--surface-border);' : '' ?>">Menunggu</a>
                        <a href="?filter=diproses" class="<?= $filter == 'diproses' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.5rem 1rem; <?= $filter != 'diproses' ? 'color: var(--text-muted); border-color: var(--surface-border);' : '' ?>">Diproses</a>
                        <a href="?filter=selesai" class="<?= $filter == 'selesai' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.5rem 1rem; <?= $filter != 'selesai' ? 'color: var(--text-muted); border-color: var(--surface-border);' : '' ?>">Selesai</a>
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
                                $query_orders = mysqli_query($conn, "SELECT * FROM orders $where_clause ORDER BY created_at DESC");
                                $modals_html = ''; // Untuk menyimpan elemen pop-up detail pesanan
                                if($query_orders && mysqli_num_rows($query_orders) > 0) {
                                    while($order = mysqli_fetch_assoc($query_orders)) {
                                        // Terjemahkan Status
                                        $status_text = 'Menunggu';
                                        $badge_style = 'background: #fffbeb; color: #d97706;'; // Kuning
                                        if($order['status'] == 'diproses') {
                                            $status_text = 'Diproses';
                                            $badge_style = 'background: #dbeafe; color: #2563eb;'; // Biru
                                        } elseif($order['status'] == 'selesai') {
                                            $status_text = 'Selesai';
                                            $badge_style = 'background: #d1fae5; color: #059669;'; // Hijau
                                        }

                                        // --- AMBIL DETAIL PESANAN ---
                                        $id_ord = $order['id_order'];
                                        $q_detail = mysqli_query($conn, "SELECT od.*, m.nama_menu FROM order_detail od JOIN menu m ON od.id_menu = m.id_menu WHERE od.id_order = $id_ord");
                                        $detail_items = '';
                                        while($d = mysqli_fetch_assoc($q_detail)){
                                            $detail_items .= '
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                                                <div><span style="font-weight: 700;">'.$d['jumlah'].'x</span> '.htmlspecialchars($d['nama_menu']).'</div>
                                                <div style="font-weight: 600;">Rp '.number_format($d['subtotal'], 0, ',', '.').'</div>
                                            </div>';
                                        }

                                        $metode_pengiriman = $order['metode_pengiriman'] == 'delivery' ? 'Delivery' : 'Makan di Tempat';
                                        $metode_pembayaran = strtoupper($order['metode_pembayaran']);

                                        // Generate Modal HTML Dinamis
                                        $modals_html .= '
                                        <div id="modalDetail'.$id_ord.'" class="modal-overlay">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3>Detail Pesanan #GJ-'.str_pad($id_ord, 4, '0', STR_PAD_LEFT).'</h3>
                                                    <button class="btn-close" onclick="closeModal(\'modalDetail'.$id_ord.'\')"><span class="material-symbols-outlined">close</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="margin-bottom: 1.5rem;">
                                                        <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Informasi Pelanggan</p>
                                                        <div style="font-weight: 600;">'.htmlspecialchars($order['nama_pelanggan']).'</div>
                                                        <div style="font-size: 0.875rem; color: var(--text-muted);">'.($order['no_hp'] ? htmlspecialchars($order['no_hp']) : '-').'</div>
                                                        <div style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">'.htmlspecialchars($order['alamat']).'</div>
                                                    </div>
                                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                                        <div>
                                                            <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Metode Makan</p>
                                                            <div style="font-weight: 600; font-size: 0.875rem;">'.$metode_pengiriman.'</div>
                                                        </div>
                                                        <div>
                                                            <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Pembayaran</p>
                                                            <div style="font-weight: 600; font-size: 0.875rem;">'.$metode_pembayaran.'</div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Daftar Pesanan</p>
                                                        '.$detail_items.'
                                                        <div style="border-top: 1px dashed var(--surface-border); margin-top: 1rem; padding-top: 1rem; display: flex; justify-content: space-between; font-weight: 800; color: var(--primary); font-size: 1.125rem;">
                                                            <span>Total Harga</span>
                                                            <span>Rp '.number_format($order['total_harga'], 0, ',', '.').'</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">#GJ-<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td><div style="font-weight: 600;"><?= htmlspecialchars($order['nama_pelanggan']) ?></div></td>
                                    <td style="color: var(--text-muted); font-size: 0.75rem;"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td style="text-align: center;">
                                        <form action="../process/order_action.php" method="POST" style="margin: 0;">
                                            <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
                                            <select name="status" onchange="this.form.submit()" style="<?= $badge_style ?> border: none; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; cursor: pointer; outline: none; text-align: center;">
                                                <option value="pending" style="background: white; color: black;" <?= $order['status'] == 'pending' ? 'selected' : '' ?> <?= $order['status'] != 'pending' ? 'disabled' : '' ?>>Menunggu</option>
                                                <option value="diproses" style="background: white; color: black;" <?= $order['status'] == 'diproses' ? 'selected' : '' ?> <?= $order['status'] == 'selesai' ? 'disabled' : '' ?>>Diproses</option>
                                                <option value="selesai" style="background: white; color: black;" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td style="text-align: right; font-weight: 700;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                    <td style="text-align: center;">
                                        <button onclick="openModal('modalDetail<?= $order['id_order'] ?>')" style="color: var(--primary); background: transparent; border: none; font-weight: 600; font-size: 0.875rem; cursor: pointer; text-decoration: underline;">Detail</button>
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

<!-- Render semua pop-up Modal Detail yang sudah dikumpulkan di dalam loop -->
<?= isset($modals_html) ? $modals_html : '' ?>

<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    document.getElementById('periodeSelect').addEventListener('change', function() {
        const kustomTanggal = document.getElementById('kustomTanggal');
        if (this.value === 'kustom') {
            kustomTanggal.style.display = 'flex';
            document.querySelector('input[name="tgl_mulai"]').required = true;
            document.querySelector('input[name="tgl_selesai"]').required = true;
        } else {
            kustomTanggal.style.display = 'none';
            document.querySelector('input[name="tgl_mulai"]').required = false;
            document.querySelector('input[name="tgl_selesai"]').required = false;
        }
    });
</script>
</body>
</html>