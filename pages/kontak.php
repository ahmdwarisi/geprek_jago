<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main class="container section">
    <div class="grid-2 contact-wrapper">
        <div>
            <h1 class="contact-title">Hubungi Kami</h1>
            <p class="contact-desc">
                Ada saran, kritik, atau ingin pesan dalam jumlah banyak? Tim Geprek Jago siap membantu kamu.
            </p>
            
            <div class="contact-info-list">
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

        <div class="contact-form-card">
            <form action="#">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" placeholder="Masukkan nama kamu">
                </div>
                <div class="form-group">
                    <label class="form-label">Pesan</label>
                    <textarea rows="4" class="form-input" placeholder="Apa yang bisa kami bantu?"></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem;">Kirim Pesan</button>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>