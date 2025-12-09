<?php
/**
 * Email Configuration & Helper Class
 * Supports both PHPMailer (SMTP) and PHP mail() function
 * RetroLoved E-Commerce System
 * 
 * INSTRUKSI: Copy file ini dan rename menjadi 'email.php', lalu isi dengan kredensial email Anda
 */

// ===== EMAIL CONFIGURATION =====
// Ganti dengan kredensial Gmail Anda atau SMTP lainnya

define('EMAIL_METHOD', 'SMTP'); // 'SMTP' atau 'MAIL'

// SMTP Configuration (untuk Gmail atau SMTP lainnya)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // 'tls' atau 'ssl'
define('SMTP_USERNAME', 'your-email@gmail.com'); // Email untuk kirim
define('SMTP_PASSWORD', 'your-app-password');    // App Password (16 digit tanpa spasi)

// Email Default Settings
define('EMAIL_FROM', 'your-email@gmail.com');
define('EMAIL_FROM_NAME', 'RetroLoved');
define('EMAIL_REPLY_TO', 'your-email@gmail.com');

// Admin Email untuk notifikasi
define('ADMIN_EMAIL', 'admin@retroloved.com');

// Support Email untuk reply customer
define('SUPPORT_EMAIL', 'your-email@gmail.com');

// ... (sisanya sama seperti file email.php asli)
// Salin class EmailHelper dari email.php mulai dari baris 30 hingga akhir
?>
