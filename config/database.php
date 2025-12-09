<?php
/**
 * Konfigurasi Database & Fungsi-Fungsi Helper
 * File utama untuk koneksi database dan fungsi-fungsi pembantu
 * RetroLoved E-Commerce System
 */

// Konfigurasi database - sesuaikan dengan environment Anda
define('DB_HOST', 'localhost');     
define('DB_USER', 'root');          
define('DB_PASS', '');              
define('DB_NAME', 'retroloved');    

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

/**
 * Eksekusi SQL query
 * @param string $sql - Query SQL yang akan dijalankan
 * @return mysqli_result|bool - Result set atau boolean
 */
function query($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    return $result;
}

/**
 * Escape string untuk mencegah SQL injection
 * @param string $string - String yang akan di-escape
 * @return string - String yang sudah aman dari SQL injection
 */
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($string));
}

// ===== SISTEM PESAN (MESSAGE SYSTEM) =====
/**
 * Set flash message untuk ditampilkan di halaman berikutnya
 * Pesan disimpan di session dan akan dihapus setelah ditampilkan
 * @param string $type - Tipe pesan: success, error, warning, info
 * @param string $message - Pesan yang akan ditampilkan
 */
function set_message($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

/**
 * Tampilkan flash message sebagai notifikasi toast
 * Otomatis mengkonversi pesan dari session ke JavaScript toast
 */
function display_message() {
    if(isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        $type = $msg['type'];
        $text = htmlspecialchars($msg['text'], ENT_QUOTES, 'UTF-8');
        
        // Petakan tipe pesan ke fungsi toast yang sesuai
        $toast_function = 'toastInfo';
        if($type == 'success') $toast_function = 'toastSuccess';
        elseif($type == 'error') $toast_function = 'toastError';
        elseif($type == 'warning') $toast_function = 'toastWarning';
        
        // Output JavaScript untuk menampilkan toast
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    if(typeof window.{$toast_function} === 'function') {
                        window.{$toast_function}('{$text}');
                    } else {
                        console.error('Toast function {$toast_function} not available');
                    }
                });
              </script>";
        
        // Hapus pesan dari session setelah ditampilkan
        unset($_SESSION['message']);
    }
}

// ===== FUNGSI VALIDASI (VALIDATION FUNCTIONS) =====
/**
 * Validasi format email
 * @param string $email - Email yang akan divalidasi
 * @return bool - True jika email valid
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validasi password minimal 6 karakter
 * @param string $password - Password yang akan divalidasi
 * @return bool - True jika password valid
 */
function validate_password($password) {
    return strlen($password) >= 6;
}

/**
 * Validasi format nomor telepon Indonesia
 * Format yang diterima: 08xxxxxxxxxx atau 62xxxxxxxxxx (8-12 digit setelah prefix)
 * @param string $phone - Nomor telepon yang akan divalidasi
 * @return bool - True jika nomor telepon valid
 */
function validate_phone($phone) {
    return preg_match('/^(08|62)\d{8,12}$/', $phone);
}

// ===== FUNGSI PEMBANTU PAGINATION =====
/**
 * Fungsi pembantu untuk pagination
 * Menghitung offset, total halaman, dan informasi pagination lainnya
 * @param string $table - Nama tabel database
 * @param string $where - Kondisi WHERE (default: "1=1")
 * @param int $per_page - Jumlah item per halaman (default: 12)
 * @return array - Array berisi informasi pagination
 */
function paginate($table, $where = "1=1", $per_page = 12) {
    // Ambil nomor halaman dari URL, default = 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if($page < 1) $page = 1;
    
    // Hitung offset untuk query LIMIT
    $offset = ($page - 1) * $per_page;
    
    // Hitung total item
    $count_query = "SELECT COUNT(*) as total FROM $table WHERE $where";
    $count_result = query($count_query);
    $count = mysqli_fetch_assoc($count_result)['total'];
    
    // Hitung total halaman
    $total_pages = ceil($count / $per_page);
    
    return [
        'page' => $page,
        'offset' => $offset,
        'per_page' => $per_page,
        'total_pages' => $total_pages,
        'total_items' => $count
    ];
}

// ===== FUNGSI PEMBANTU NOTIFIKASI =====
/**
 * Membuat notifikasi baru untuk pengguna
 * Digunakan untuk notifikasi status pesanan, promo, dan lainnya
 * @param int $user_id - ID pengguna yang akan menerima notifikasi
 * @param int|null $order_id - ID pesanan terkait (opsional)
 * @param string $type - Tipe notifikasi (order, shipping, promo, dll)
 * @param string $title - Judul notifikasi
 * @param string $message - Isi pesan notifikasi
 * @return mysqli_result|bool - Hasil eksekusi query
 */
function create_notification($user_id, $order_id, $type, $title, $message) {
    $user_id = escape($user_id);
    $order_id = $order_id ? escape($order_id) : 'NULL';
    $type = escape($type);
    $title = escape($title);
    $message = escape($message);
    
    $query = "INSERT INTO notifications (user_id, order_id, type, title, message) 
              VALUES ('$user_id', $order_id, '$type', '$title', '$message')";
    
    return query($query);
}

/**
 * Hitung jumlah notifikasi yang belum dibaca
 * Digunakan untuk menampilkan badge notifikasi di header
 * @param int $user_id - ID pengguna
 * @return int - Jumlah notifikasi yang belum dibaca
 */
function get_unread_notifications_count($user_id) {
    $user_id = escape($user_id);
    $query = "SELECT COUNT(*) as count FROM notifications WHERE user_id = '$user_id' AND is_read = 0";
    $result = query($query);
    return mysqli_fetch_assoc($result)['count'];
}

// ===== NOTIFIKASI STATUS PESANAN =====
/**
 * Kirim notifikasi otomatis saat status pesanan berubah
 * Memberikan update realtime kepada pelanggan tentang status pesanan mereka
 * @param int $order_id - ID pesanan
 * @param string $new_status - Status baru (Pending, Processing, Shipped, Delivered, Cancelled)
 * @return bool - True jika berhasil mengirim notifikasi
 */
function send_order_status_notification($order_id, $new_status) {
    // Ambil detail pesanan
    $order_id = escape($order_id);
    $order_query = query("SELECT user_id, order_id FROM orders WHERE order_id = '$order_id'");
    $order = mysqli_fetch_assoc($order_query);
    
    if(!$order) return false;
    
    $user_id = $order['user_id'];
    
    // Template pesan notifikasi untuk setiap status
    // IMPORTANT: 'type' harus match dengan filter tabs di notifications.php
    $status_messages = [
        'Pending' => [
            'title' => 'Pesanan Menunggu Konfirmasi',
            'message' => 'Pesanan #' . $order_id . ' sedang menunggu konfirmasi pembayaran. Silakan upload bukti pembayaran jika belum.',
            'type' => 'order_pending'
        ],
        'Processing' => [
            'title' => 'Pesanan Dikonfirmasi',
            'message' => 'Pesanan #' . $order_id . ' telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.',
            'type' => 'order_confirmed'
        ],
        'Shipped' => [
            'title' => 'Pesanan Telah Dikirim',
            'message' => 'Pesanan #' . $order_id . ' telah dikirim! Silakan tunggu paket Anda tiba.',
            'type' => 'order_shipped'
        ],
        'Delivered' => [
            'title' => 'Pesanan Telah Sampai',
            'message' => 'Pesanan #' . $order_id . ' telah sampai! Terima kasih telah berbelanja di RetroLoved.',
            'type' => 'order_delivered'
        ],
        'Completed' => [
            'title' => 'Pesanan Selesai',
            'message' => 'Pesanan #' . $order_id . ' telah selesai. Terima kasih telah berbelanja di RetroLoved!',
            'type' => 'order_completed'
        ],
        'Cancelled' => [
            'title' => 'Pesanan Dibatalkan',
            'message' => 'Pesanan #' . $order_id . ' telah dibatalkan. Jika ada pertanyaan, silakan hubungi admin.',
            'type' => 'order_cancelled'
        ]
    ];
    
    // Cek apakah status ada dalam template pesan
    if(isset($status_messages[$new_status])) {
        $notif = $status_messages[$new_status];
        return create_notification(
            $user_id,
            $order_id,
            $notif['type'],
            $notif['title'],
            $notif['message']
        );
    }
    
    return false;
}

// ===== FUNGSI PEMBANTU WAKTU RELATIF =====
/**
 * Konversi timestamp ke format "waktu yang lalu" (contoh: "2 jam yang lalu")
 * Untuk menampilkan waktu relatif yang lebih mudah dipahami pengguna
 * @param string $datetime - String datetime dari database
 * @return string - String waktu relatif yang terformat
 */
function time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    // Definisi periode waktu dalam detik
    $periods = array(
        'tahun' => 31536000,
        'bulan' => 2592000,
        'minggu' => 604800,
        'hari' => 86400,
        'jam' => 3600,
        'menit' => 60,
        'detik' => 1
    );
    
    // Loop untuk menemukan periode yang paling sesuai
    foreach($periods as $key => $value) {
        if($difference >= $value) {
            $time = floor($difference / $value);
            return $time . ' ' . $key . ' yang lalu';
        }
    }
    
    return 'Baru saja';
}

// ===== FUNGSI ORDER HISTORY =====
/**
 * Catat perubahan status order ke history
 * @param int $order_id - ID pesanan
 * @param string $status - Status baru
 * @param string|null $tracking_number - Nomor resi (opsional)
 * @param string|null $notes - Catatan perubahan (opsional)
 * @param int|null $changed_by - ID admin yang mengubah (opsional)
 * @return mysqli_result|bool
 */
function log_order_history($order_id, $status, $tracking_number = null, $notes = null, $changed_by = null) {
    $order_id = escape($order_id);
    $status = escape($status);
    $tracking_number = $tracking_number ? "'" . escape($tracking_number) . "'" : 'NULL';
    $notes = $notes ? "'" . escape($notes) . "'" : 'NULL';
    $changed_by = $changed_by ? escape($changed_by) : 'NULL';
    
    $query = "INSERT INTO order_history (order_id, status, tracking_number, notes, changed_by) 
              VALUES ('$order_id', '$status', $tracking_number, $notes, $changed_by)";
    
    return query($query);
}

/**
 * Ambil history perubahan order
 * @param int $order_id - ID pesanan
 * @return mysqli_result
 */
function get_order_history($order_id) {
    $order_id = escape($order_id);
    $query = "SELECT oh.*, u.full_name as admin_name 
              FROM order_history oh 
              LEFT JOIN users u ON oh.changed_by = u.user_id 
              WHERE oh.order_id = '$order_id' 
              ORDER BY oh.created_at ASC";
    return query($query);
}

/**
 * Update status order dengan tracking dan history
 * @param int $order_id - ID pesanan
 * @param string $new_status - Status baru
 * @param string|null $tracking_number - Nomor resi
 * @param string|null $admin_notes - Catatan admin
 * @param int|null $admin_id - ID admin yang mengubah
 * @return bool
 */
function update_order_status($order_id, $new_status, $tracking_number = null, $admin_notes = null, $admin_id = null) {
    $order_id = escape($order_id);
    $new_status = escape($new_status);
    
    // Build update query
    $update_parts = ["status = '$new_status'"];
    
    // Map status to current_status_detail untuk consistency
    $status_detail_map = [
        'Pending' => 'order_placed',
        'Processing' => 'processing',
        'Shipped' => 'in_transit',
        'Delivered' => 'delivered',
        'Completed' => 'completed',
        'Cancelled' => 'cancelled'
    ];
    
    // Update current_status_detail based on status
    if (isset($status_detail_map[$new_status])) {
        $update_parts[] = "current_status_detail = '" . $status_detail_map[$new_status] . "'";
    }
    
    if ($tracking_number !== null) {
        $tracking_number = escape($tracking_number);
        $update_parts[] = "tracking_number = '$tracking_number'";
    }
    
    if ($admin_notes !== null) {
        $admin_notes = escape($admin_notes);
        $update_parts[] = "admin_notes = '$admin_notes'";
    }
    
    // Set delivered_at untuk status Delivered atau Completed
    if ($new_status == 'Delivered' || $new_status == 'Completed') {
        $update_parts[] = "delivered_at = NOW()";
    }
    
    // Set shipped_at untuk status Shipped
    if ($new_status == 'Shipped') {
        $update_parts[] = "shipped_at = NOW()";
    }
    
    $update_sql = implode(', ', $update_parts);
    
    // Update order
    $result = query("UPDATE orders SET $update_sql WHERE order_id = '$order_id'");
    
    if ($result) {
        // AUTO-ASSIGN COURIER saat status diubah ke Shipped
        if ($new_status == 'Shipped') {
            require_once 'shipping.php';
            auto_assign_courier($order_id);
        }
        
        // Log to history dengan status_detail
        $status_detail = isset($status_detail_map[$new_status]) ? $status_detail_map[$new_status] : null;
        log_order_history_with_detail($order_id, $new_status, $status_detail, $tracking_number, $admin_notes, $admin_id);
        
        // Send notification
        send_order_status_notification($order_id, $new_status);
        
        return true;
    }
    
    return false;
}

/**
 * Log order history dengan status_detail
 */
function log_order_history_with_detail($order_id, $status, $status_detail = null, $tracking_number = null, $notes = null, $changed_by = null) {
    $order_id = escape($order_id);
    $status = escape($status);
    $status_detail = $status_detail ? "'" . escape($status_detail) . "'" : 'NULL';
    $tracking_number = $tracking_number ? "'" . escape($tracking_number) . "'" : 'NULL';
    $notes = $notes ? "'" . escape($notes) . "'" : 'NULL';
    $changed_by = $changed_by ? escape($changed_by) : 'NULL';
    
    $query = "INSERT INTO order_history (order_id, status, status_detail, tracking_number, notes, changed_by) 
              VALUES ('$order_id', '$status', $status_detail, $tracking_number, $notes, $changed_by)";
    
    return query($query);
}
?>