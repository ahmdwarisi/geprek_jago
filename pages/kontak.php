<?php 
require_once '../config/database.php';
include '../includes/header.php'; 
?>

<main>
    <!-- Bagian Header Kontak -->
    <section class="section section-bg-primary">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="section-header center" style="margin-bottom: 0;">
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem;">Hubungi Kami</h1>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
                    Ada saran, kritik, atau ingin pesan dalam jumlah banyak? Tim Geprek Jago siap membantu kamu.
                </p>
            </div>
        </div>
    </section>

    <!-- Bagian Info & Form Kontak -->
    <section class="section container mx-auto px-3 sm:px-4">
        <div class="grid-2 contact-wrapper" style="align-items: start;">
            <div>
            <div class="contact-info-list">
                <a href="https://maps.app.goo.gl/etHVMspTRZVCf8aJA" target="_blank" class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <p class="contact-info-label">Alamat Outlet</p>
                        <p class="contact-info-value">Perumahan Telang Indah, Kamal, Bangkalan</p>
                    </div>
                </a>
                <a href="https://wa.me/6282332325294" target="_blank" class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">call</span>
                    </div>
                    <div>
                        <p class="contact-info-label">WhatsApp</p>
                        <p class="contact-info-value">+62 823-3232-5294</p>
                    </div>
                </a>
                <a href="mailto:halo@geprekjago.com" class="contact-info-item">
                    <div class="contact-icon">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <div>
                        <p class="contact-info-label">Email</p>
                        <p class="contact-info-value">halo@geprekjago.com</p>
                    </div>
                </a>
            </div>
        </div>
        <div>
        <div class="contact-form-card">
            <form id="contactForm" action="#">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="namaPengirim" class="form-input" placeholder="Masukkan nama kamu" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" id="emailPengirim" class="form-input" placeholder="halo@geprekjago.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pesan</label>
                    <textarea id="isiPesan" rows="4" class="form-input" placeholder="Apa yang bisa kami bantu?" required></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding-top: 1rem; padding-bottom: 1rem;">Kirim Pesan</button>
            </form>
        </div>
    </div>
    </div>
</main>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form memuat ulang halaman
        
        const nama = document.getElementById('namaPengirim').value;
        const email = document.getElementById('emailPengirim').value;
        const pesan = document.getElementById('isiPesan').value;
        
        // Format teks untuk dikirim ke WhatsApp
        const textWa = `Halo Geprek Jago! 👋\n\nNama: *${nama}*\nEmail: *${email}*\n\nPesan:\n_${pesan}_`;
        
        // Nomor WhatsApp tujuan (sama seperti yang ada di tombol info kontak)
        const noWa = '6282332325294';
        
        // Buka WhatsApp di tab baru dengan pesan yang sudah diisi
        window.open(`https://wa.me/${noWa}?text=${encodeURIComponent(textWa)}`, '_blank');
    });
</script>

<?php include '../includes/footer.php'; ?>