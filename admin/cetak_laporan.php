<?php
session_start();
require_once '../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit();
}

$periode = $_GET['periode'] ?? 'semua';
$where_clause = "";
$judul_periode = "Semua Waktu";

// Proses Logika Filter Waktu Laporan
if ($periode === 'harian') {
    $where_clause = "WHERE DATE(created_at) = CURDATE()";
    $judul_periode = "Hari Ini (" . date('d F Y') . ")";
} elseif ($periode === 'mingguan') {
    $where_clause = "WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $judul_periode = "7 Hari Terakhir";
} elseif ($periode === 'bulanan') {
    $where_clause = "WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
    $judul_periode = "Bulan Ini (" . date('F Y') . ")";
} elseif ($periode === 'kustom') {
    $tgl_mulai = mysqli_real_escape_string($conn, $_GET['tgl_mulai'] ?? '');
    $tgl_selesai = mysqli_real_escape_string($conn, $_GET['tgl_selesai'] ?? '');
    if ($tgl_mulai && $tgl_selesai) {
        $where_clause = "WHERE DATE(created_at) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
        $judul_periode = date('d F Y', strtotime($tgl_mulai)) . " s.d. " . date('d F Y', strtotime($tgl_selesai));
    }
}

// Urutkan dari pesanan yang paling awal pada periode tersebut
$query = "SELECT * FROM orders $where_clause ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

$total_pendapatan = 0;
$total_pesanan = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Geprek Jago</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0D4429; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { display: flex; justify-content: space-between; margin-top: 30px; font-size: 14px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Geprek Jago</h1>
        <p>Laporan Penjualan</p>
        <p><strong>Periode:</strong> <?= $judul_periode ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th class="text-center">Status</th>
                <th class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($total_pesanan > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    // Total pendapatan hanya dari pesanan yang sudah 'selesai'
                    if ($row['status'] === 'selesai') {
                        $total_pendapatan += $row['total_harga'];
                    }
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td>#GJ-<?= str_pad($row['id_order'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                <td class="text-center"><?= ucfirst($row['status']) ?></td>
                <td class="text-right">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">Tidak ada data pesanan pada periode ini.</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pendapatan Bersih (Pesanan Selesai)</td>
                <td class="text-right">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div>
            <p><strong>Total Transaksi (Semua Status):</strong> <?= $total_pesanan ?> transaksi</p>
        </div>
        <div style="text-align: center; width: 200px;">
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>Adminjago</strong></p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #0D4429; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">Cetak Lagi</button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #666; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; margin-left: 10px;">Tutup</button>
    </div>
</body>
</html>