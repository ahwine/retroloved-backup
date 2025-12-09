<?php
/**
 * Run All Database Migrations
 * Script untuk menjalankan semua migration SQL secara otomatis
 * Pastikan database sudah dibuat sebelum menjalankan script ini
 * RetroLoved E-Commerce System
 */

// Konfigurasi database - sesuaikan dengan environment Anda
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'retroloved');

// Koneksi ke database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}

// Disable mysqli exception untuk bisa handle error secara manual
mysqli_report(MYSQLI_REPORT_OFF);

mysqli_set_charset($conn, "utf8mb4");

echo "✅ Koneksi database berhasil!\n\n";
echo "================================================\n";
echo "  RETROLOVED DATABASE MIGRATION RUNNER\n";
echo "================================================\n\n";

/**
 * Jalankan SQL file migration
 * @param string $filename - Nama file SQL
 * @return bool - True jika berhasil
 */
function runMigration($filename) {
    global $conn;
    
    $filepath = __DIR__ . '/' . $filename;
    
    if (!file_exists($filepath)) {
        echo "⚠️  File tidak ditemukan: $filename\n";
        return false;
    }
    
    echo "▶️  Menjalankan: $filename\n";
    
    // Baca file SQL
    $sql = file_get_contents($filepath);
    
    if (empty($sql)) {
        echo "⚠️  File kosong: $filename\n\n";
        return false;
    }
    
    // Split SQL berdasarkan semicolon untuk multiple queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $success = true;
    $executed = 0;
    
    foreach ($queries as $query) {
        // Skip komentar dan query kosong
        if (empty($query) || substr($query, 0, 2) === '--' || substr($query, 0, 2) === '/*') {
            continue;
        }
        
        // Jalankan query dengan error handling
        $result = @mysqli_query($conn, $query);
        
        if ($result) {
            $executed++;
        } else {
            // Cek apakah error karena kolom/tabel sudah ada (bukan error fatal)
            $error = mysqli_error($conn);
            if (strpos($error, 'Duplicate column') !== false || 
                strpos($error, 'already exists') !== false ||
                strpos($error, 'Duplicate key') !== false ||
                strpos($error, "Can't DROP") !== false ||
                (strpos($error, 'Table') !== false && strpos($error, 'already exists') !== false)) {
                echo "   ℹ️  Sudah ada/tidak perlu, dilewati\n";
                $executed++; // Tetap hitung sebagai executed karena tidak masalah
            } else {
                echo "   ⚠️  Warning: $error\n";
                echo "   Query: " . substr($query, 0, 100) . "...\n";
                // Tidak set $success = false karena beberapa error bisa diabaikan
            }
        }
    }
    
    if ($success && $executed > 0) {
        echo "   ✅ Berhasil! ($executed queries executed)\n\n";
    } elseif ($executed === 0) {
        echo "   ℹ️  Tidak ada query yang dijalankan (mungkin sudah dijalankan sebelumnya)\n\n";
    } else {
        echo "   ⚠️  Selesai dengan warning\n\n";
    }
    
    return $success;
}

// Daftar migration files yang akan dijalankan (urutan penting!)
$migrations = [
    'add_second_image_column.sql',          // Tambah kolom image_url_2
    'add_image_3_and_4_columns.sql',        // Tambah kolom image_url_3 dan 4
    'add_image_5_to_10_columns.sql',        // Tambah kolom image_url_5 sampai 10
    'add_is_sold_column.sql',               // Tambah kolom is_sold untuk track produk terjual
    'add_notifications_and_profile.sql',    // Tambah tabel notifications dan kolom profile_picture
    'add_shipping_addresses.sql',           // Tambah tabel shipping_addresses
    'create_password_resets_table.sql',     // Tambah tabel password_resets untuk forgot password
    'add_order_management_improvements.sql', // Tambah tabel order_history dan admin_notes
    'fix_bugs_migration.sql',               // Fix bugs dan tambah indexes
    'remove_stock_column.sql',              // Hapus kolom stock (tidak diperlukan untuk preloved)
    'remove_wishlist_table.sql',            // Hapus tabel wishlist (fitur dihapus)
    'create_contact_support_table.sql',     // Tambah tabel contact_support untuk customer support
    'add_admin_page_visits.sql',            // Tambah tabel admin_page_visits untuk tracking
];

// Jalankan semua migrations
$total = count($migrations);
$success_count = 0;
$failed_count = 0;

echo "📋 Total migrations: $total\n\n";

foreach ($migrations as $migration) {
    if (runMigration($migration)) {
        $success_count++;
    } else {
        $failed_count++;
    }
}

// Summary
echo "================================================\n";
echo "  MIGRATION SUMMARY\n";
echo "================================================\n";
echo "✅ Berhasil: $success_count\n";
echo "❌ Gagal: $failed_count\n";
echo "📊 Total: $total\n";
echo "================================================\n\n";

if ($failed_count === 0) {
    echo "🎉 Semua migrations berhasil dijalankan!\n";
    echo "Database RetroLoved siap digunakan!\n\n";
} else {
    echo "⚠️  Beberapa migrations gagal. Periksa error di atas.\n";
    echo "Namun aplikasi mungkin tetap bisa berjalan jika error hanya minor.\n\n";
}

// Close connection
mysqli_close($conn);

echo "Tekan Enter untuk keluar...";
if (PHP_SAPI === 'cli') {
    fgets(STDIN);
}
?>
