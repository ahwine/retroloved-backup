<?php
/**
 * Halaman Pesanan Customer (My Orders)
 * Menampilkan daftar pesanan, upload bukti pembayaran, dan cancel order
 * RetroLoved E-Commerce System
 */

// Mulai session
session_start();

// Set PHP upload limits using ini_set() as fallback if .htaccess doesn't work
// This is necessary when server uses PHP-FPM or CGI mode instead of mod_php
@ini_set('upload_max_filesize', '10M');
@ini_set('post_max_size', '12M');
@ini_set('max_execution_time', '300');
@ini_set('max_input_time', '300');

// Log current PHP settings for debugging
error_log("PHP Upload Settings - upload_max_filesize: " . ini_get('upload_max_filesize'));
error_log("PHP Upload Settings - post_max_size: " . ini_get('post_max_size'));

// Validasi: Hanya customer yang bisa akses halaman ini
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: ../index.php');
    exit();
}

// Include koneksi database
require_once '../config/database.php';
require_once '../config/shipping.php';
$base_url = '../';

// Ambil ID user yang sedang login
$user_id = $_SESSION['user_id'];

// Otomatis tandai notifikasi sebagai sudah dibaca jika datang dari link notifikasi
if(isset($_GET['notification_id']) && is_numeric($_GET['notification_id'])) {
    $notification_id = escape($_GET['notification_id']);
    // Update status notifikasi menjadi sudah dibaca
    query("UPDATE notifications SET is_read = 1 WHERE notification_id = '$notification_id' AND user_id = '$user_id'");
    
    // Redirect ke URL bersih (hapus parameter notification_id)
    $clean_url = 'orders.php';
    if(isset($_GET['filter'])) {
        $clean_url .= '?filter=' . urlencode($_GET['filter']);
    }
    header('Location: ' . $clean_url);
    exit();
}

// HANDLE UPLOAD PAYMENT PROOF
if(isset($_POST['upload_payment'])) {
    error_log("=== UPLOAD PAYMENT PROOF TRIGGERED ===");
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));
    
    $order_id = escape($_POST['order_id']);
    error_log("Order ID: " . $order_id);
    
    // Verify order belongs to user
    $check_order = mysqli_fetch_assoc(query("SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'"));
    
    if(!$check_order) {
        error_log("ERROR: Order not found for user");
        set_message('error', 'Order tidak ditemukan!');
    } else {
        error_log("Order found, checking file upload...");
        
        // Check if POST data was lost due to post_max_size exceeded
        if(empty($_POST) && empty($_FILES)) {
            error_log("ERROR: POST and FILES are empty - likely post_max_size exceeded");
            set_message('error', 'File terlalu besar! Ukuran maksimal adalah 5MB. Silakan kompres file Anda terlebih dahulu.');
        }
        // Handle file upload
        else if(isset($_FILES['payment_proof'])) {
            $upload_error = $_FILES['payment_proof']['error'];
            error_log("File upload error code: " . $upload_error);
            
            // Check for upload errors
            if($upload_error !== UPLOAD_ERR_OK) {
                switch($upload_error) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        error_log("ERROR: File exceeds upload_max_filesize or MAX_FILE_SIZE");
                        set_message('error', 'Ukuran file terlalu besar! Maksimal 5MB.');
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        error_log("ERROR: File was only partially uploaded");
                        set_message('error', 'Upload file gagal! File hanya terupload sebagian. Silakan coba lagi.');
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        error_log("ERROR: No file was uploaded");
                        set_message('error', 'Silakan pilih file terlebih dahulu!');
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        error_log("ERROR: Missing temporary folder");
                        set_message('error', 'Terjadi kesalahan server! Silakan hubungi administrator.');
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        error_log("ERROR: Failed to write file to disk");
                        set_message('error', 'Gagal menyimpan file! Silakan coba lagi.');
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        error_log("ERROR: File upload stopped by extension");
                        set_message('error', 'Upload dihentikan oleh ekstensi server!');
                        break;
                    default:
                        error_log("ERROR: Unknown upload error: " . $upload_error);
                        set_message('error', 'Terjadi kesalahan saat upload! Silakan coba lagi.');
                }
            } else {
                error_log("File upload detected, processing...");
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['payment_proof']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                
                if(!in_array(strtolower($filetype), $allowed)) {
                    set_message('error', 'Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan!');
                } else {
                    // Validate file size (max 5MB)
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    if($_FILES['payment_proof']['size'] > $maxSize) {
                        set_message('error', 'Ukuran file maksimal 5MB!');
                    } else {
                        // Generate unique filename
                        $new_filename = 'payment_' . $order_id . '_' . time() . '.' . $filetype;
                        $upload_path = '../assets/images/payments/' . $new_filename;
                        
                        // Create folder if not exists
                        if(!file_exists('../assets/images/payments/')) {
                            mkdir('../assets/images/payments/', 0777, true);
                        }
                        
                        if(move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_path)) {
                            error_log("File uploaded successfully: " . $upload_path);
                            
                            // Verify file actually exists after upload
                            if(file_exists($upload_path)) {
                                // Update database only if file successfully uploaded
                                // Update order dengan payment proof, status jadi Pending (15%)
                                $update = query("UPDATE orders SET payment_proof = '$new_filename', status = 'Pending' WHERE order_id = '$order_id'");
                                
                                // Log ke order_history
                                if($update) {
                                    require_once '../config/database.php';
                                    $user_id = $_SESSION['user_id'];
                                    $notes = 'Customer uploaded payment proof. Waiting for admin confirmation.';
                                    query("INSERT INTO order_history (order_id, status, status_detail, notes, changed_by, created_at) 
                                           VALUES ('$order_id', 'Pending', NULL, '$notes', '$user_id', NOW())");
                                }
                                
                                if($update) {
                                    error_log("Database updated successfully");
                                    set_message('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
                                } else {
                                    error_log("ERROR: Database update failed");
                                    // Delete uploaded file if database update fails
                                    if(file_exists($upload_path)) {
                                        unlink($upload_path);
                                    }
                                    set_message('error', 'Gagal mengupdate database!');
                                }
                            } else {
                                error_log("ERROR: File upload verification failed - file doesn't exist");
                                set_message('error', 'Gagal memverifikasi file! Silakan coba lagi.');
                            }
                        } else {
                            error_log("ERROR: move_uploaded_file failed");
                            set_message('error', 'Gagal mengupload file! Silakan coba lagi.');
                        }
                    }
                }
            }
        } else {
            error_log("ERROR: payment_proof not set in FILES array");
            set_message('error', 'Silakan pilih file terlebih dahulu!');
        }
    }
    error_log("=== REDIRECTING TO orders.php ===");
    header('Location: orders.php');
    exit();
} else {
    error_log("upload_payment POST not set");
}

// CANCEL ORDER (hanya untuk order Pending)
if(isset($_GET['cancel'])) {
    $order_id = escape($_GET['cancel']);
    
    // Cek apakah order milik user ini dan masih pending
    $order_check = mysqli_fetch_assoc(query("SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id' AND status = 'Pending'"));
    
    if($order_check) {
        // Update status jadi Cancelled
        query("UPDATE orders SET status = 'Cancelled' WHERE order_id = '$order_id'");
        
        // Return products ke available (set is_sold = 0)
        $order_items = query("SELECT product_id FROM order_items WHERE order_id = '$order_id'");
        while($item = mysqli_fetch_assoc($order_items)) {
            query("UPDATE products SET is_sold = 0 WHERE product_id = '{$item['product_id']}'");
        }
        
        set_message('success', 'Order berhasil dibatalkan. Produk telah dikembalikan ke katalog.');
    } else {
        set_message('error', 'Order tidak dapat dibatalkan atau tidak ditemukan.');
    }
    header('Location: orders.php');
    exit();
}

// Ambil semua order user
$orders = query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - RetroLoved</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/performance.css">
    <link rel="stylesheet" href="../assets/css/toast.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <!-- BREADCRUMB -->
    <div class="breadcrumb-container">
        <div class="container">
            <nav class="breadcrumb">
                <a href="../index.php" class="breadcrumb-item">Home</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-item active">My Orders</span>
            </nav>
        </div>
    </div>

    <!-- ORDERS SECTION -->
    <section class="orders-section">
        <div class="container">
            <div class="orders-header">
                <div class="orders-title-wrapper">
                    <h1 class="orders-title">My Orders</h1>
                    <p class="orders-subtitle">Riwayat pesanan Anda</p>
                </div>
            </div>
            
            <div class="orders-list">
        
        <?php if(mysqli_num_rows($orders) > 0): ?>
            
            <?php while($order = mysqli_fetch_assoc($orders)): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-header-left">
                            <div class="order-number">Order #<?php echo $order['order_id']; ?></div>
                            <div class="order-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?php echo date('d M Y', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    // Ambil item order dengan gambar produk, kategori, dan kondisi
                    $order_items = query("SELECT oi.*, p.product_name, p.image_url, p.category, p.condition_item 
                                         FROM order_items oi 
                                         JOIN products p ON oi.product_id = p.product_id 
                                         WHERE oi.order_id = '{$order['order_id']}'");
                    ?>
                    
                    <div class="order-items-list">
                        <?php while($item = mysqli_fetch_assoc($order_items)): ?>
                            <div class="order-item-card">
                                <div class="order-item-left">
                                    <img src="../assets/images/products/<?php echo $item['image_url']; ?>" 
                                         class="order-product-image"
                                         alt="<?php echo $item['product_name']; ?>"
                                         onerror="this.src='../assets/images/products/placeholder.jpg'">
                                    <div class="order-item-info">
                                        <h3 class="order-product-name"><?php echo $item['product_name']; ?></h3>
                                        <div class="order-product-meta">
                                            <span class="meta-item"><?php echo $item['category']; ?></span>
                                            <span class="meta-item"><?php echo $item['condition_item']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-item-right">
                                    <div class="order-product-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="order-total-section">
                        <div class="order-total-label">Total Belanja</div>
                        <div class="order-total-price">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></div>
                    </div>
                    
                    <?php 
                    // Trim status untuk menghindari masalah whitespace
                    $order_status = trim($order['status']);
                    ?>
                    
                    <?php if($order_status == 'Pending' && empty($order['payment_proof'])): ?>
                        <div class="order-actions order-actions-flex">
                            <button type="button" onclick="openUploadModal(<?php echo $order['order_id']; ?>, <?php echo $order['total_amount']; ?>)" class="btn btn-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="order-upload-icon">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Upload Payment Proof
                            </button>
                            <a href="javascript:void(0)" 
                               onclick="confirmModal('Apakah Anda yakin ingin membatalkan order ini? Produk akan dikembalikan ke katalog.', function() { window.location.href='orders.php?cancel=<?php echo $order['order_id']; ?>'; })"
                               class="btn order-cancel-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="order-cancel-icon">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                Cancel Order
                            </a>
                        </div>
                    <?php elseif(!empty($order['payment_proof']) && $order_status == 'Pending'): ?>
                        <div class="order-actions">
                            <span style="color: #F59E0B; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Waiting for admin confirmation...
                            </span>
                        </div>
                    <?php elseif($order_status == 'Processing' || $order_status == 'Shipped' || $order_status == 'Delivered'): ?>
                        <!-- PROCESSING, SHIPPED, DELIVERED - Show "View Tracking" -->
                        <div class="order-actions order-actions-flex" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                            <span style="color: <?php 
                                echo $order_status == 'Delivered' ? '#10B981' : '#3B82F6'; 
                            ?>; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="<?php echo $order_status == 'Delivered' ? '#10B981' : '#3B82F6'; ?>" stroke-width="2">
                                    <?php if($order_status == 'Delivered'): ?>
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    <?php elseif($order_status == 'Shipped'): ?>
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    <?php else: ?>
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="9 11 12 14 16 10"></polyline>
                                    <?php endif; ?>
                                </svg>
                                <?php 
                                    if($order_status == 'Processing') echo 'Sedang diproses';
                                    elseif($order_status == 'Shipped') echo 'Sedang dikirim';
                                    elseif($order_status == 'Delivered') echo 'Paket sudah sampai';
                                ?>
                            </span>
                            <a href="order-tracking.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                View Tracking
                            </a>
                        </div>
                    <?php elseif($order_status == 'Completed'): ?>
                        <!-- COMPLETED ORDER - Show "View Details" -->
                        <div class="order-actions order-actions-flex" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                            <span class="order-status-completed" style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #10B981;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Pesanan selesai
                            </span>
                            <a href="order-tracking.php?id=<?php echo $order['order_id']; ?>" class="btn btn-secondary" style="background: #6B7280; border-color: #6B7280;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="8"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                View Details
                            </a>
                        </div>
                    <?php elseif($order_status == 'Cancelled'): ?>
                        <!-- CANCELLED ORDER -->
                        <div class="order-actions">
                            <span class="order-status-cancelled">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                Order dibatalkan
                            </span>
                        </div>
                    <?php else: ?>
                        <!-- FALLBACK - jika tidak ada kondisi yang match -->
                        <div class="order-actions">
                            <span style="color: #6B7280; font-weight: 600;">
                                Status: <?php echo htmlspecialchars($order_status); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-orders">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <h3>Belum Ada Pesanan</h3>
                        <p>Anda belum memiliki riwayat pesanan. Mulai belanja sekarang!</p>
                        <a href="../shop.php" class="btn btn-primary">Lihat Katalog Produk</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="../assets/js/toast.js"></script>
    <script src="../assets/js/modal.js"></script>
    <script src="../assets/js/loading.js"></script>
    <script src="../assets/js/script.js"></script>
    
    <script>
        // Add loading state to page
        document.addEventListener('DOMContentLoaded', function() {
            // Pastikan modal tertutup saat halaman dimuat
            const modal = document.getElementById('uploadPaymentModal');
            if (modal) {
                modal.classList.remove('active');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }
            
            // Hide loading overlay if exists
            const loadingOverlay = document.querySelector('.loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
            
            // Add loading to cancel order
            const cancelLinks = document.querySelectorAll('[href*="cancel="]');
            cancelLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const originalHref = this.getAttribute('href');
                    e.preventDefault();
                    
                    confirmModal('Apakah Anda yakin ingin membatalkan order ini? Produk akan dikembalikan ke katalog.', function() {
                        showLoadingOverlay();
                        window.location.href = originalHref;
                    });
                });
            });
        });
    </script>

    <!-- Upload Payment Modal -->
    <div id="uploadPaymentModal" class="upload-modal">
        <div class="upload-modal-content">
            <div class="upload-modal-header">
                <h3>Upload Payment Proof</h3>
                <button type="button" class="modal-close" onclick="closeUploadModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <div class="upload-modal-body">
                <!-- Order Info -->
                <div class="modal-order-info">
                    <div class="modal-order-item">
                        <span class="modal-order-label">Order ID:</span>
                        <span class="modal-order-value" id="modalOrderId"></span>
                    </div>
                    <div class="modal-order-item">
                        <span class="modal-order-label">Total Amount:</span>
                        <span class="modal-order-value" id="modalOrderTotal"></span>
                    </div>
                </div>
                
                <!-- Payment Guide -->
                <div class="payment-guide-compact">
                    <div class="payment-guide-header-compact">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <strong>Panduan Upload Bukti Pembayaran</strong>
                    </div>
                    <ul class="payment-guide-list-compact">
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Detail transaksi yang jelas
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Jumlah harus sesuai dengan total order
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Tanggal dan waktu terlihat
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            JPG, PNG, GIF (max 5MB)
                        </li>
                    </ul>
                </div>
                
                <!-- Upload Form -->
                <form action="orders.php" method="POST" enctype="multipart/form-data" id="uploadPaymentForm" onsubmit="handleUploadSubmit(event); return false;">
                    <input type="hidden" name="order_id" id="uploadOrderId" value="">
                    
                    <div class="file-upload-wrapper-modal">
                        <div class="file-upload-content-modal" id="uploadContentModal" onclick="document.getElementById('paymentProofInput').click()">
                            <svg class="file-upload-icon-modal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <div class="file-upload-text-modal">Klik untuk upload bukti pembayaran</div>
                            <div class="file-upload-hint-modal">JPG, PNG atau GIF (max 5MB)</div>
                        </div>
                        <input type="file" id="paymentProofInput" name="payment_proof" accept="image/*" onchange="previewPaymentImage(this)" class="payment-proof-input-hidden">
                        <div class="image-preview-modal" id="imagePreviewModal">
                            <img id="previewImage" src="" alt="Preview">
                            <button type="button" class="btn-change-photo-modal" onclick="document.getElementById('paymentProofInput').click()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Ganti Foto
                            </button>
                        </div>
                    </div>
                    
                    <div class="upload-modal-actions">
                        <button type="button" class="btn-modal-cancel" onclick="closeUploadModal()">Batal</button>
                        <button type="submit" name="upload_payment" class="btn-modal-upload" id="uploadSubmitBtn" disabled>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Upload Modal Styles */
        .upload-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(3px);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-y: auto;
        }
        
        .upload-modal.active {
            display: flex;
        }
        
        .upload-modal-content {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .upload-modal-header {
            padding: 24px 24px 16px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .upload-modal-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }
        
        .modal-close {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #6B7280;
            transition: all 0.2s;
            border-radius: 6px;
        }
        
        .modal-close:hover {
            background: #F3F4F6;
            color: #1a1a1a;
        }
        
        .upload-modal-body {
            padding: 24px;
        }
        
        .modal-order-info {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .modal-order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .modal-order-item:last-child {
            margin-bottom: 0;
        }
        
        .modal-order-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
        }
        
        .modal-order-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 700;
        }
        
        .payment-guide-compact {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 2px solid #F59E0B;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .payment-guide-header-compact {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: #92400E;
        }
        
        .payment-guide-header-compact strong {
            font-size: 14px;
            font-weight: 700;
        }
        
        .payment-guide-list-compact {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .payment-guide-list-compact li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            color: #92400E;
        }
        
        .payment-guide-list-compact li:last-child {
            margin-bottom: 0;
        }
        
        .payment-guide-list-compact li svg {
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .file-upload-wrapper-modal {
            border: 3px dashed #D1D5DB;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s;
            cursor: pointer;
            background: #F9FAFB;
            position: relative;
        }
        
        .file-upload-wrapper-modal:hover {
            border-color: #D97706;
            background: #FEF3C7;
        }
        
        .file-upload-content-modal {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .file-upload-icon-modal {
            width: 48px;
            height: 48px;
            color: #D97706;
            margin-bottom: 12px;
        }
        
        .file-upload-text-modal {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .file-upload-hint-modal {
            font-size: 13px;
            color: #6B7280;
        }
        
        .payment-proof-input-hidden {
            display: none;
        }
        
        .image-preview-modal {
            display: none;
            flex-direction: column;
            align-items: center;
        }
        
        .image-preview-modal img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
        }
        
        .btn-change-photo-modal {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #F3F4F6;
            color: #1a1a1a;
            border: 2px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-change-photo-modal:hover {
            background: #E5E7EB;
            border-color: #D97706;
            color: #D97706;
        }
        
        .upload-modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        
        .btn-modal-cancel {
            flex: 1;
            padding: 14px;
            background: #F3F4F6;
            color: #1a1a1a;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-modal-cancel:hover {
            background: #E5E7EB;
        }
        
        .btn-modal-upload {
            flex: 2;
            padding: 14px;
            background: #D97706;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }
        
        .btn-modal-upload:hover:not(:disabled) {
            background: #B45309;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
        }
        
        .btn-modal-upload:disabled {
            background: #D1D5DB;
            cursor: not-allowed;
            opacity: 0.6;
            box-shadow: none;
        }
        
        body.modal-open {
            overflow: hidden;
        }
        
        @media (max-width: 640px) {
            .upload-modal-content {
                max-width: 100%;
                border-radius: 20px 20px 0 0;
                max-height: 95vh;
            }
            
            .upload-modal-header {
                padding: 20px 20px 12px;
            }
            
            .upload-modal-body {
                padding: 20px;
            }
            
            .file-upload-wrapper-modal {
                padding: 30px 15px;
            }
            
            .upload-modal-actions {
                flex-direction: column-reverse;
            }
        }
    </style>
    
    <script>
        function openUploadModal(orderId, totalAmount) {
            document.getElementById('modalOrderId').textContent = '#' + orderId;
            document.getElementById('modalOrderTotal').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            document.getElementById('uploadOrderId').value = orderId;
            
            const modal = document.getElementById('uploadPaymentModal');
            modal.style.display = ''; // Remove inline style jika ada
            modal.classList.add('active');
            document.body.classList.add('modal-open');
            
            // Reset form
            document.getElementById('uploadPaymentForm').reset();
            document.getElementById('uploadContentModal').style.display = 'flex';
            document.getElementById('imagePreviewModal').style.display = 'none';
            document.getElementById('uploadSubmitBtn').disabled = true;
        }
        
        function closeUploadModal() {
            const modal = document.getElementById('uploadPaymentModal');
            modal.classList.remove('active');
            modal.style.display = 'none'; // Force hide
            document.body.classList.remove('modal-open');
            document.body.style.overflow = ''; // Reset overflow
            
            // Reset form state
            const form = document.getElementById('uploadPaymentForm');
            if (form) {
                form.reset();
            }
            
            // Reset preview
            document.getElementById('uploadContentModal').style.display = 'flex';
            document.getElementById('imagePreviewModal').style.display = 'none';
            document.getElementById('uploadSubmitBtn').disabled = true;
        }
        
        function previewPaymentImage(input) {
            const preview = document.getElementById('previewImage');
            const uploadContent = document.getElementById('uploadContentModal');
            const imagePreview = document.getElementById('imagePreviewModal');
            const uploadBtn = document.getElementById('uploadSubmitBtn');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    toastError('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan!');
                    input.value = '';
                    return;
                }
                
                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5242880 bytes
                
                if (file.size > maxSize) {
                    const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                    toastError('Ukuran file maksimal 5MB! File Anda: ' + fileSizeMB + ' MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    uploadContent.style.display = 'none';
                    imagePreview.style.display = 'flex';
                    uploadBtn.disabled = false;
                    
                    // Show success toast
                    toastSuccess('File berhasil dipilih! Siap untuk diupload.');
                };
                reader.readAsDataURL(file);
            } else {
            }
        }
        
        function handleUploadSubmit(event) {
            
            // Prevent default form submission
            event.preventDefault();
            
            const form = document.getElementById('uploadPaymentForm');
            const fileInput = document.getElementById('paymentProofInput');
            const orderId = document.getElementById('uploadOrderId').value;
            
            // Validate file
            if (!fileInput.files || fileInput.files.length === 0) {
                toastError('Silakan pilih file terlebih dahulu!');
                return false;
            }
            
            const selectedFile = fileInput.files[0];
            
            // Validate file size (max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes = 5242880 bytes
            const fileSize = selectedFile.size;
            const fileSizeMB = (fileSize / 1024 / 1024).toFixed(2);
            
            if (fileSize > maxSize) {
                toastError('Ukuran file terlalu besar! Maksimal 5MB. File Anda: ' + fileSizeMB + ' MB');
                return false;
            }
            
            // Disable button and show loading
            const submitBtn = document.getElementById('uploadSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10"></circle></svg> Mengupload...';
            
            // Show loading overlay
            if (typeof showLoadingOverlay === 'function') {
                showLoadingOverlay();
            }
            
            // Show toast notification
            toastInfo('Sedang mengupload bukti pembayaran...', '', 0);
            
            // Create FormData object
            const formData = new FormData();
            formData.append('upload_payment', '1');
            formData.append('order_id', orderId);
            formData.append('payment_proof', fileInput.files[0]);
            
            // Send using Fetch API
            fetch('orders.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                
                if (response.ok || response.redirected) {
                    window.location.href = 'orders.php';
                } else {
                    return response.text().then(text => {
                        throw new Error('Upload failed');
                    });
                }
            })
            .catch(error => {
                toastError('Gagal mengupload! Silakan coba lagi.');
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg> Upload Bukti Pembayaran';
            });
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('uploadPaymentModal');
            if (event.target === modal) {
                closeUploadModal();
            }
        });
        
        // Add spin animation for loading
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
    
    <?php 
    // Display flash messages from session AFTER all scripts loaded
    display_message(); 
    ?>
    
    

    <?php include '../includes/footer.php'; ?>
    
