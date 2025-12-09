<?php
/**
 * Halaman Privacy Policy
 * Menampilkan kebijakan privasi dan pengelolaan data pengguna
 * RetroLoved E-Commerce System
 */

session_start();
require_once 'config/database.php';
$base_url = '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - RetroLoved</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/toast.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="container">
            <h1 class="page-title">Privacy Policy</h1>
            <p class="page-subtitle">Kebijakan privasi dan perlindungan data pribadi Anda</p>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper legal-content">
                
                <div class="legal-intro">
                    <p>RetroLoved berkomitmen untuk melindungi privasi Anda. Privacy Policy ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda ketika menggunakan layanan kami.</p>
                </div>

                <div class="legal-block">
                    <h2>1. Information We Collect</h2>
                    
                    <h3>1.1 Informasi yang Anda Berikan</h3>
                    <p>Kami mengumpulkan informasi yang Anda berikan secara langsung, termasuk:</p>
                    <ul class="legal-list">
                        <li>Nama lengkap</li>
                        <li>Alamat email</li>
                        <li>Nomor telepon</li>
                        <li>Alamat pengiriman</li>
                        <li>Informasi pembayaran</li>
                        <li>Password akun (terenkripsi)</li>
                    </ul>
                    
                    <h3>1.2 Informasi yang Dikumpulkan Otomatis</h3>
                    <p>Ketika Anda menggunakan website kami, kami secara otomatis mengumpulkan:</p>
                    <ul class="legal-list">
                        <li>Alamat IP</li>
                        <li>Tipe browser dan perangkat</li>
                        <li>Halaman yang dikunjungi</li>
                        <li>Waktu dan durasi kunjungan</li>
                        <li>Cookies dan data tracking lainnya</li>
                    </ul>
                    
                    <h3>1.3 Informasi dari Pihak Ketiga</h3>
                    <p>Kami dapat menerima informasi dari payment gateway dan ekspedisi untuk memproses transaksi Anda.</p>
                </div>

                <div class="legal-block">
                    <h2>2. How We Use Your Information</h2>
                    <p>Kami menggunakan informasi Anda untuk:</p>
                    
                    <h3>2.1 Menyediakan Layanan</h3>
                    <ul class="legal-list">
                        <li>Memproses dan mengirimkan pesanan Anda</li>
                        <li>Mengelola akun Anda</li>
                        <li>Memberikan customer support</li>
                        <li>Mengirimkan konfirmasi dan update pesanan</li>
                    </ul>
                    
                    <h3>2.2 Meningkatkan Layanan</h3>
                    <ul class="legal-list">
                        <li>Menganalisis penggunaan website</li>
                        <li>Meningkatkan fitur dan fungsionalitas</li>
                        <li>Personalisasi pengalaman belanja Anda</li>
                    </ul>
                    
                    <h3>2.3 Marketing dan Komunikasi</h3>
                    <ul class="legal-list">
                        <li>Mengirimkan newsletter dan promosi (dengan persetujuan Anda)</li>
                        <li>Memberikan informasi tentang produk baru</li>
                        <li>Mengirimkan survei kepuasan pelanggan</li>
                    </ul>
                    
                    <h3>2.4 Keamanan dan Pencegahan Fraud</h3>
                    <ul class="legal-list">
                        <li>Memverifikasi identitas Anda</li>
                        <li>Mencegah dan mendeteksi penipuan</li>
                        <li>Mematuhi kewajiban hukum</li>
                    </ul>
                </div>

                <div class="legal-block">
                    <h2>3. Information Sharing</h2>
                    
                    <h3>3.1 Kapan Kami Membagikan Informasi</h3>
                    <p>Kami dapat membagikan informasi Anda dengan:</p>
                    <ul class="legal-list">
                        <li><strong>Service Providers:</strong> Payment gateway, ekspedisi, dan penyedia layanan IT</li>
                        <li><strong>Legal Requirements:</strong> Ketika diwajibkan oleh hukum atau proses hukum</li>
                        <li><strong>Business Transfers:</strong> Dalam hal merger, akuisisi, atau penjualan aset</li>
                    </ul>
                    
                    <h3>3.2 Perlindungan Data</h3>
                    <p>Semua pihak ketiga yang bekerja sama dengan kami diwajibkan untuk melindungi data Anda dan hanya menggunakannya sesuai instruksi kami.</p>
                </div>

                <div class="legal-block">
                    <h2>4. Data Security</h2>
                    
                    <h3>4.1 Langkah Keamanan</h3>
                    <p>Kami menerapkan berbagai langkah keamanan untuk melindungi informasi Anda:</p>
                    <ul class="legal-list">
                        <li>Enkripsi SSL untuk transmisi data sensitif</li>
                        <li>Password terenkripsi</li>
                        <li>Akses terbatas ke data pribadi</li>
                        <li>Monitoring keamanan secara berkala</li>
                        <li>Firewall dan sistem keamanan lainnya</li>
                    </ul>
                    
                    <h3>4.2 Tanggung Jawab Anda</h3>
                    <p>Anda bertanggung jawab untuk menjaga kerahasiaan password Anda dan tidak membagikannya kepada siapapun.</p>
                </div>

                <div class="legal-block">
                    <h2>5. Cookies and Tracking</h2>
                    
                    <h3>5.1 Apa itu Cookies</h3>
                    <p>Cookies adalah file kecil yang disimpan di perangkat Anda untuk meningkatkan pengalaman browsing.</p>
                    
                    <h3>5.2 Jenis Cookies yang Kami Gunakan</h3>
                    <ul class="legal-list">
                        <li><strong>Essential Cookies:</strong> Diperlukan untuk fungsi dasar website</li>
                        <li><strong>Performance Cookies:</strong> Membantu kami memahami bagaimana pengunjung menggunakan website</li>
                        <li><strong>Functional Cookies:</strong> Mengingat preferensi Anda</li>
                        <li><strong>Marketing Cookies:</strong> Melacak aktivitas untuk iklan yang relevan</li>
                    </ul>
                    
                    <h3>5.3 Mengelola Cookies</h3>
                    <p>Anda dapat mengatur browser Anda untuk menolak cookies, tetapi ini mungkin mempengaruhi fungsionalitas website.</p>
                </div>

                <div class="legal-block">
                    <h2>6. Your Rights</h2>
                    <p>Anda memiliki hak untuk:</p>
                    
                    <h3>6.1 Akses</h3>
                    <p>Meminta salinan data pribadi yang kami miliki tentang Anda.</p>
                    
                    <h3>6.2 Koreksi</h3>
                    <p>Memperbarui atau memperbaiki informasi yang tidak akurat.</p>
                    
                    <h3>6.3 Penghapusan</h3>
                    <p>Meminta penghapusan data pribadi Anda (dengan beberapa pengecualian legal).</p>
                    
                    <h3>6.4 Opt-out</h3>
                    <p>Berhenti berlangganan dari komunikasi marketing kami kapan saja.</p>
                    
                    <h3>6.5 Portabilitas Data</h3>
                    <p>Meminta data Anda dalam format yang dapat dibaca mesin.</p>
                </div>

                <div class="legal-block">
                    <h2>7. Data Retention</h2>
                    <p>Kami menyimpan informasi pribadi Anda selama:</p>
                    <ul class="legal-list">
                        <li>Akun Anda aktif</li>
                        <li>Diperlukan untuk menyediakan layanan</li>
                        <li>Diwajibkan oleh hukum (misalnya untuk keperluan pajak)</li>
                        <li>Diperlukan untuk menyelesaikan perselisihan</li>
                    </ul>
                </div>

                <div class="legal-block">
                    <h2>8. Children's Privacy</h2>
                    <p>Layanan kami tidak ditujukan untuk anak-anak di bawah 17 tahun. Kami tidak secara sengaja mengumpulkan informasi pribadi dari anak-anak. Jika Anda percaya bahwa kami telah mengumpulkan informasi dari anak di bawah umur, segera hubungi kami.</p>
                </div>

                <div class="legal-block">
                    <h2>9. Third-Party Links</h2>
                    <p>Website kami mungkin berisi link ke website pihak ketiga. Kami tidak bertanggung jawab atas praktek privasi website tersebut. Kami mendorong Anda untuk membaca privacy policy mereka.</p>
                </div>

                <div class="legal-block">
                    <h2>10. International Data Transfers</h2>
                    <p>Informasi Anda mungkin disimpan dan diproses di server yang berlokasi di Indonesia atau negara lain. Kami memastikan bahwa transfer data dilakukan dengan perlindungan yang memadai.</p>
                </div>

                <div class="legal-block">
                    <h2>11. Changes to Privacy Policy</h2>
                    <p>Kami dapat memperbarui Privacy Policy ini dari waktu ke waktu. Perubahan akan efektif segera setelah dipublikasikan di website. Kami akan memberitahu Anda tentang perubahan material melalui email atau notifikasi di website.</p>
                </div>

                <div class="legal-block">
                    <h2>12. Contact Us</h2>
                    <p>Jika Anda memiliki pertanyaan atau kekhawatiran tentang Privacy Policy ini atau ingin menggunakan hak-hak Anda, silakan hubungi kami:</p>
                    <ul class="contact-list">
                        <li><strong>Email:</strong> privacy@retroloved.com</li>
                        <li><strong>Phone:</strong> +62 xxx xxxx xxxx</li>
                        <li><strong>Address:</strong> Jakarta, Indonesia</li>
                    </ul>
                </div>

                <div class="legal-footer">
                    <p><strong>Last Updated:</strong> January 2025</p>
                    <p>Dengan menggunakan website RetroLoved, Anda mengakui bahwa Anda telah membaca dan memahami Privacy Policy ini dan menyetujui pengumpulan dan penggunaan informasi Anda sebagaimana dijelaskan di sini.</p>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
