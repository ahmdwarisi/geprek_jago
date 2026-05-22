<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main>
    <!-- Bagian Header Kontak -->
    <section class="section section-bg-primary">
        <div class="container">
            <div class="section-header center" style="margin-bottom: 0;">
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem;">Hubungi Kami</h1>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
                    Ada saran, kritik, atau ingin pesan dalam jumlah banyak? Tim Geprek Jago siap membantu kamu.
                </p>
            </div>
        </div>
    </section>

    <!-- Bagian Info & Form Kontak -->
    <section class="section container">
        <div class="grid-2 contact-wrapper" style="align-items: start;">
            <div>
            <div class="contact-info-list">
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <p class="contact-info-label">Alamat Outlet</p>
                        <p class="contact-info-value">Perumahan Telang Indah, Kamal, Bangkalan</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">call</span>
                    </div>
                    <div>
                        <p class="contact-info-label">WhatsApp</p>
                        <p class="contact-info-value">+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <div>
                        <p class="contact-info-label">Email</p>
                        <p class="contact-info-value">halo@geprekjago.com</p>
                    </div>
                </div>
            </div>
        </div>
        <div>
        <div class="contact-form-card">
            <form action="#">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" placeholder="Masukkan nama kamu">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" class="form-input" placeholder="halo@geprekjago.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Pesan</label>
                    <textarea rows="4" class="form-input" placeholder="Apa yang bisa kami bantu?"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem;">Kirim Pesan</button>
            </form>
        </div>
    </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>