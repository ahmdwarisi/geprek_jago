<?php
// Mendeteksi halaman aktif untuk memberikan styling berbeda pada link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="fixed left-0 top-0 h-screen w-64 border-r border-emerald-100 bg-white dark:bg-gray-950 shadow-[2px_0_8px_rgba(13,68,41,0.04)] z-50 flex flex-col p-4">
    <div class="px-4 py-8 mb-6">
        <h1 class="text-2xl font-black text-primary dark:text-emerald-400 tracking-tight">Geprek Jago</h1>
        <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold">Admin Panel</p>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="dashboard_admin.php" 
           class="<?= ($current_page == 'dashboard_admin.php') ? 'sidebar-link-active' : 'sidebar-link' ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="kelola_menu.php" 
           class="<?= ($current_page == 'kelola_menu.php') ? 'sidebar-link-active' : 'sidebar-link' ?>">
            <span class="material-symbols-outlined">restaurant_menu</span>
            <span class="text-sm">Kelola Menu</span>
        </a>

        <a href="kelola_pesanan.php" 
           class="<?= ($current_page == 'kelola_pesanan.php') ? 'sidebar-link-active' : 'sidebar-link' ?>">
            <span class="material-symbols-outlined">receipt_long</span>
            <span class="text-sm">Pesanan</span>
        </a>
    </nav>

    <div class="pt-4 mt-auto border-t border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3 px-4 py-3 mb-2">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-primary font-bold text-xs">
                <?= substr($_SESSION['admin_name'] ?? 'A', 0, 1); ?>
            </div>
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-800"><?= $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                <span class="text-[10px] text-gray-400">Super Admin</span>
            </div>
        </div>
        
        <a href="../process/auth.php?logout=true" 
           class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg transition-all text-sm font-bold">
            <span class="material-symbols-outlined">logout</span>
            <span>Keluar</span>
        </a>
    </div>
</aside>