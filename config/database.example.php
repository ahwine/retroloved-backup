<?php
/**
 * Konfigurasi Database & Fungsi-Fungsi Helper
 * File utama untuk koneksi database dan fungsi-fungsi pembantu
 * RetroLoved E-Commerce System
 * 
 * INSTRUKSI: Copy file ini dan rename menjadi 'database.php', lalu isi dengan kredensial database Anda
 */

// Konfigurasi database - sesuaikan dengan environment Anda
define('DB_HOST', 'localhost');     // Host database (biasanya localhost)
define('DB_USER', 'root');          // Username database
define('DB_PASS', '');              // Password database (kosongkan jika tidak ada)
define('DB_NAME', 'retroloved');    // Nama database

// Buat koneksi ke database MySQL
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8 untuk mendukung karakter Indonesia
mysqli_set_charset($conn, "utf8mb4");

// Set timezone untuk Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// ... (sisanya sama seperti file database.php asli)
// Salin semua fungsi dari database.php mulai dari baris 27 hingga akhir
?>
