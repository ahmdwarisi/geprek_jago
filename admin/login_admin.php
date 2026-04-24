<?php
session_start();
// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html class="light" lang="id">
  <head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login Admin - Geprek Jago</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>

    <style type="text/tailwindcss">
        @layer components {
            .admin-stat-card {
                @apply p-6 bg-white dark:bg-slate-900 border border-emerald-50 dark:border-emerald-900 rounded-2xl shadow-sm hover:shadow-md transition-all;
            }
            .btn-primary {
                @apply bg-[#0D4429] text-white px-6 py-2.5 rounded-full font-semibold hover:bg-[#155a38] transition-all active:scale-95 flex items-center gap-2;
            }
        }
    </style>

    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: '#0D4429', // Warna identitas Geprek Jago
              secondary: '#356850',
              background: '#f9f9fc',
            },
            fontFamily: {
              sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            }
          },
        },
      };
    </script>
  </head>
  <body class="flex flex-col items-center justify-between min-h-screen antialiased bg-background">
    
    <div class="flex items-center justify-center w-full px-4 py-8 flex-grow">
      <div class="flex flex-col w-full max-w-[400px] gap-6 p-8 bg-white border border-zinc-100 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        
        <div class="flex flex-col items-center gap-2">
          <div class="flex items-center justify-center w-16 h-16 mb-1 rounded-2xl bg-emerald-50 text-primary">
            <span class="text-3xl material-symbols-outlined">restaurant</span>
          </div>
          <h1 class="text-2xl font-bold text-primary">Geprek Jago</h1>
          <p class="text-sm text-slate-500 font-medium">Admin Dashboard Access</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
          <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-bold flex items-center gap-2 border border-red-100">
            <span class="material-symbols-outlined text-sm">error</span>
            <span>Email atau Password yang Anda masukkan salah.</span>
          </div>
        <?php endif; ?>

        <form action="../process/auth.php" method="POST" class="flex flex-col gap-5">
          
          <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="email">Email</label>
            <div class="relative group">
              <span class="absolute text-[20px] transition-colors -translate-y-1/2 material-symbols-outlined left-4 top-1/2 text-slate-400 group-focus-within:text-primary">mail</span>
              <input name="email" type="email" id="email" required 
                     class="w-full py-3.5 pl-12 pr-4 transition-all border border-slate-100 rounded-xl outline-none bg-slate-50 focus:ring-2 focus:ring-primary/20 focus:bg-white placeholder:text-slate-400 text-sm" 
                     placeholder="Masukkan email Anda"/>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="password">Kata Sandi</label>
            <div class="relative group">
              <span class="absolute text-[20px] transition-colors -translate-y-1/2 material-symbols-outlined left-4 top-1/2 text-slate-400 group-focus-within:text-primary">lock</span>
              <input name="password" type="password" id="password" required 
                     class="w-full py-3.5 pl-12 pr-4 transition-all border border-slate-100 rounded-xl outline-none bg-slate-50 focus:ring-2 focus:ring-primary/20 focus:bg-white placeholder:text-slate-400 text-sm" 
                     placeholder="Masukkan kata sandi"/>
            </div>
          </div>

          <div class="flex justify-end">
            <a class="text-xs font-bold transition-colors cursor-pointer text-primary hover:underline" href="#">Lupa Kata Sandi?</a>
          </div>

          <button type="submit" name="login" class="btn-primary w-full justify-center py-4 mt-2 shadow-lg shadow-emerald-900/10">
            <span class="font-bold">Masuk Sekarang</span>
            <span class="text-[18px] material-symbols-outlined">login</span>
          </button>
        </form>

        <div class="flex flex-col items-center gap-1 pt-3 border-t border-slate-50">
          <p class="text-xs text-slate-400 font-medium">Bermasalah saat masuk?</p>
          <a class="text-xs font-bold text-primary hover:underline" href="https://wa.me/628123456789">Hubungi Tim IT</a>
        </div>
      </div>
    </div>
    
    <footer class="w-full py-8 text-center">
      <p class="text-xs text-slate-400 font-medium">
        © 2024 Geprek Jago. Selera Nusantara, Kualitas Juara.
      </p>
    </footer>

  </body>
</html>