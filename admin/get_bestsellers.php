<?php
require_once 'auth_required.php';
require_once '../config/database.php';

header('Content-Type: application/json');

$filter = $_GET['filter'] ?? '';
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;

if ($filter === '' || $index === -1) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak valid']);
    exit;
}

$where_clause = "";
$title = "";

if ($filter === 'bulanan') {
    $month = $index + 1; // index 0 = Januari
    $year = date('Y');
    $where_clause = "MONTH(o.created_at) = $month AND YEAR(o.created_at) = $year";
    
    $bulan_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $title = "Menu Terlaris - " . $bulan_indo[$index] . " $year";
} elseif ($filter === 'mingguan') {
    // index 0 = MINGGU, 1 = SENIN, dst.
    $days_since_sunday = date('N') % 7;
    $start_of_week = date('Y-m-d', strtotime("-$days_since_sunday days"));
    
    $target_date = date('Y-m-d', strtotime($start_of_week . " +$index days"));
    $where_clause = "DATE(o.created_at) = '$target_date'";
    
    $hari_indo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $title = "Menu Terlaris - " . $hari_indo[$index] . " (" . date('d/m/Y', strtotime($target_date)) . ")";
} else {
    echo json_encode(['status' => 'error', 'message' => 'Filter tidak valid']);
    exit;
}

$query = "SELECT m.nama_menu, m.gambar, SUM(od.jumlah) as total_terjual 
          FROM order_detail od 
          JOIN orders o ON od.id_order = o.id_order 
          JOIN menu m ON od.id_menu = m.id_menu 
          WHERE o.status = 'selesai' AND $where_clause 
          GROUP BY od.id_menu 
          ORDER BY total_terjual DESC 
          LIMIT 5";

$result = mysqli_query($conn, $query);
$data = [];
if ($result) { while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; } }

echo json_encode(['status' => 'success', 'title' => $title, 'data' => $data]);