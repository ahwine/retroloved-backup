<?php
/**
 * Halaman Manajemen Pesanan - Admin Panel
 * Mengelola semua pesanan customer: update status, tracking, dan bulk actions
 * RetroLoved E-Commerce System
 */

// Mulai session
session_start();

// Validasi: Hanya admin yang bisa akses halaman ini
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Include koneksi database
require_once '../config/database.php';

// Mark Orders page as visited - save to database for persistent tracking
$user_id = $_SESSION['user_id'];
query("INSERT INTO admin_page_visits (user_id, page_name, last_visit_at) 
       VALUES ('$user_id', 'orders', NOW()) 
       ON DUPLICATE KEY UPDATE last_visit_at = NOW()");

// Inisialisasi variabel untuk pesan
$success = '';
$error = '';

// ===== PROSES UPDATE STATUS ORDER (via AJAX untuk inline dropdown) =====
if(isset($_POST['ajax_update_status'])) {
    // Set header response JSON
    header('Content-Type: application/json');
    
    // Ambil data dari POST
    $order_id = escape($_POST['order_id']);
    $new_status = escape($_POST['status']);
    $tracking_number = isset($_POST['tracking_number']) ? escape($_POST['tracking_number']) : null;
    
    // Update status order menggunakan fungsi dari database.php
    if(update_order_status($order_id, $new_status, $tracking_number, null, $_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'message' => 'Status pesanan berhasil diupdate!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate status pesanan!']);
    }
    exit;
}

// ===== PROSES BULK UPDATE STATUS (update status banyak order sekaligus) =====
if(isset($_POST['bulk_update_status'])) {
    // Ambil array ID order yang dipilih
    $order_ids = $_POST['order_ids'] ?? [];
    $new_status = escape($_POST['bulk_status']);
    
    // Counter untuk menghitung berapa order yang berhasil diupdate
    $success_count = 0;
    
    // Loop setiap order dan update statusnya
    foreach($order_ids as $order_id) {
        $order_id = escape($order_id);
        if(update_order_status($order_id, $new_status, null, null, $_SESSION['user_id'])) {
            $success_count++;
        }
    }
    
    // Set pesan sukses jika ada yang berhasil
    if($success_count > 0) {
        $success = "$success_count pesanan berhasil diupdate!";
    } else {
        $error = "Failed to update orders!";
    }
}

// BUILD FILTERS & SEARCH QUERY
$where_conditions = ["1=1"];
$filter_status = '';
$filter_date_from = '';
$filter_date_to = '';
$search_query = '';

// Status Filter
if(isset($_GET['status']) && !empty($_GET['status'])) {
    $filter_status = escape($_GET['status']);
    $where_conditions[] = "o.status = '$filter_status'";
}

// Date Range Filter
if(isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $filter_date_from = escape($_GET['date_from']);
    $where_conditions[] = "DATE(o.created_at) >= '$filter_date_from'";
}

if(isset($_GET['date_to']) && !empty($_GET['date_to'])) {
    $filter_date_to = escape($_GET['date_to']);
    $where_conditions[] = "DATE(o.created_at) <= '$filter_date_to'";
}

// Search (Order ID or Customer Name)
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = escape($_GET['search']);
    $where_conditions[] = "(o.order_id LIKE '%$search_query%' OR u.full_name LIKE '%$search_query%' OR o.customer_name LIKE '%$search_query%')";
}

$where_clause = implode(" AND ", $where_conditions);

// READ with Filters
$orders = query("SELECT o.*, u.full_name, u.email 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.user_id 
                 WHERE $where_clause
                 ORDER BY o.created_at DESC");

// Count total and filtered
$total_orders = mysqli_num_rows(query("SELECT order_id FROM orders"));
$filtered_orders = mysqli_num_rows($orders);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - RetroLoved Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=5.4">
    <link rel="stylesheet" href="../assets/css/toast.css">
</head>
<body class="admin-body">
    <script src="../assets/js/modal.js"></script>
    <script src="../assets/js/toast.js"></script>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <?php $page_title = "Orders"; include 'includes/navbar.php'; ?>

        <div class="content-area">
            <div class="content-wrapper">
                <!-- Breadcrumbs -->
                <nav style="margin-bottom: 24px;">
                    <ol style="display: flex; align-items: center; gap: 8px; list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li>
                            <a href="dashboard.php" style="color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#D97706'" onmouseout="this.style.color='#6b7280'">Dashboard</a>
                        </li>
                        <li style="color: #d1d5db;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </li>
                        <li style="color: #D97706; font-weight: 600;">
                            Orders
                        </li>
                    </ol>
                </nav>
                
                <!-- Page Title -->
                <div style="margin-bottom: 24px;">
                    <h1 style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin: 0 0 8px 0;">Orders Management</h1>
                    <p style="font-size: 15px; color: #6b7280; margin: 0;">Track and manage customer orders</p>
                </div>
                
                <!-- Filters & Search Toolbar -->
                <div class="filter-section">
                <form method="GET" action="orders.php" class="filter-form">
                    <!-- Status Filter -->
                    <div class="filter-form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo ($filter_status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Processing" <?php echo ($filter_status == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                            <option value="Shipped" <?php echo ($filter_status == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?php echo ($filter_status == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="Completed" <?php echo ($filter_status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo ($filter_status == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Date From -->
                    <div class="filter-form-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="<?php echo $filter_date_from; ?>">
                    </div>
                    
                    <!-- Date To -->
                    <div class="filter-form-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="<?php echo $filter_date_to; ?>">
                    </div>
                    
                    <!-- Search -->
                    <div class="filter-form-group wide">
                        <label>Search</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Order ID or Customer Name...">
                    </div>
                    
                    <!-- Actions -->
                    <div class="filter-actions">
                        <button type="submit">Apply Filters</button>
                        <a href="orders.php" class="btn-clear">Clear</a>
                    </div>
                </form>
                
                <!-- Filter Summary -->
                <?php if($filter_status || $filter_date_from || $filter_date_to || $search_query): ?>
                    <div class="filter-summary">
                        <strong>Showing <?php echo $filtered_orders; ?></strong> of <?php echo $total_orders; ?> orders
                        <?php if($filter_status): ?>
                            <span class="filter-tag">Status: <strong><?php echo $filter_status; ?></strong></span>
                        <?php endif; ?>
                        <?php if($filter_date_from || $filter_date_to): ?>
                            <span class="filter-tag">
                                Date: <strong><?php echo $filter_date_from ?: 'Start'; ?></strong> to <strong><?php echo $filter_date_to ?: 'End'; ?></strong>
                            </span>
                        <?php endif; ?>
                        <?php if($search_query): ?>
                            <span class="filter-tag">Search: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Orders Table -->
            <div class="content-card">
                <div class="card-header">
                    <div class="header-with-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <div class="header-text">
                            <h3>
                                All Orders
                                <span class="count-badge"><?php echo mysqli_num_rows($orders); ?></span>
                            </h3>
                            <p>Manage customer orders and status</p>
                        </div>
                    </div>
                    
                    <!-- Bulk Actions -->
                    <div id="bulkActionsBar" style="display: none; margin-top: 16px; padding: 12px; background: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd;">
                        <form method="POST" id="bulkForm" style="display: flex; gap: 12px; align-items: center;">
                            <span style="font-weight: 600; color: #0369a1;">
                                <span id="selectedCount">0</span> order(s) selected
                            </span>
                            <select name="bulk_status" required style="padding: 8px 12px; border: 1px solid #bae6fd; border-radius: 6px;">
                                <option value="" disabled selected hidden>Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button type="submit" name="bulk_update_status" class="btn-action btn-edit">
                                Update Selected
                            </button>
                            <button type="button" onclick="clearSelection()" class="btn-action btn-delete">
                                Clear
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($order = mysqli_fetch_assoc($orders)): ?>
                                <tr class="selectable-row" data-order-id="<?php echo $order['order_id']; ?>">
                                    <td onclick="event.stopPropagation();">
                                        <input type="checkbox" class="order-checkbox" name="order_ids[]" value="<?php echo $order['order_id']; ?>" onchange="updateBulkActions()">
                                    </td>
                                    <td data-label="Order ID">
                                        <strong style="color: #3B82F6;">#<?php echo $order['order_id']; ?></strong>
                                    </td>
                                    <td data-label="Customer">
                                        <div class="user-info">
                                            <div class="avatar-circle info">
                                                <?php echo strtoupper(substr($order['full_name'], 0, 1)); ?>
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name"><?php echo $order['full_name']; ?></div>
                                                <div class="user-email"><?php echo $order['email']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Total">
                                        <strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td data-label="Payment">
                                        <?php echo $order['payment_method']; ?>
                                    </td>
                                    <td data-label="Status" onclick="event.stopPropagation();">
                                        <select class="status-dropdown" data-order-id="<?php echo $order['order_id']; ?>" onchange="updateStatusInline(this)">
                                            <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td data-label="Date">
                                        <?php echo date('d M Y', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td data-label="Action" onclick="event.stopPropagation();">
                                        <div class="table-actions">
                                            <a href="order-detail.php?id=<?php echo $order['order_id']; ?>" class="btn-action btn-view">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            
                            <?php if(mysqli_num_rows($orders) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="empty-state">
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                            </svg>
                                            <h3>No Orders Yet</h3>
                                            <p>Orders will appear here when customers make purchases</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Card View for Orders -->
                <div class="mobile-card-list">
                    <?php 
                    mysqli_data_seek($orders, 0);
                    while($order = mysqli_fetch_assoc($orders)): 
                    ?>
                        <div class="mobile-card-item" data-order-id="<?php echo $order['order_id']; ?>">
                            <div class="mobile-card-header">
                                <div class="avatar-circle info" style="width: 50px; height: 50px; font-size: 18px;">
                                    <?php echo strtoupper(substr($order['full_name'], 0, 1)); ?>
                                </div>
                                <div class="mobile-card-info">
                                    <div class="mobile-card-title">#<?php echo $order['order_id']; ?> - <?php echo $order['full_name']; ?></div>
                                    <div class="mobile-card-subtitle" style="font-weight: 700; color: #D97706; font-size: 15px;">
                                        Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
                                    </div>
                                    <div style="margin-top: 8px;">
                                        <select class="status-dropdown" data-order-id="<?php echo $order['order_id']; ?>" onchange="updateStatusInline(this)" style="font-size: 12px; padding: 6px 10px;">
                                            <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Shipped" <?php echo $order['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mobile-card-meta">
                                <div class="mobile-card-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <span><?php echo $order['payment_method']; ?></span>
                                </div>
                                <div class="mobile-card-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span><?php echo date('d M Y', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="mobile-card-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $order['email']; ?></span>
                                </div>
                            </div>
                            
                            <div class="mobile-card-actions">
                                <a href="order-detail.php?id=<?php echo $order['order_id']; ?>" 
                                   class="btn-action"
                                   style="background: #3B82F6; color: white; border: 1px solid #2563EB; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px; font-weight: 600; border-radius: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <span>View Details</span>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($orders) == 0): ?>
                        <div style="text-align: center; padding: 60px 20px; color: #999;">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 24px; opacity: 0.3;">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <h3 style="font-size: 18px; font-weight: 700; color: #6B7280; margin-bottom: 8px;">No Orders Yet</h3>
                            <p style="color: #9CA3AF;">Orders will appear here when customers make purchases</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Tracking Number Modal -->
    <div class="modal" id="trackingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Enter Tracking Number</h3>
                <button onclick="closeTrackingModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 16px; color: #6b7280;">Please enter the tracking number for this shipment:</p>
                <input type="text" id="tracking_input" class="form-input" placeholder="e.g., JNE123456789" required>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="confirmTracking()" class="btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Confirm
                </button>
                <button type="button" onclick="closeTrackingModal()" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let pendingStatusChange = null;

        // Update status inline (dropdown)
        function updateStatusInline(selectElement) {
            const orderId = selectElement.getAttribute('data-order-id');
            const newStatus = selectElement.value;
            const oldStatus = selectElement.getAttribute('data-old-status') || selectElement.value;
            
            // If changing to "Shipped", ask for tracking number
            if (newStatus === 'Shipped' && oldStatus !== 'Shipped') {
                pendingStatusChange = { orderId, newStatus, selectElement };
                document.getElementById('trackingModal').classList.add('show');
                return;
            }
            
            // Otherwise, update directly
            performStatusUpdate(orderId, newStatus, null, selectElement);
        }

        function closeTrackingModal() {
            document.getElementById('trackingModal').classList.remove('show');
            document.getElementById('tracking_input').value = '';
            
            // Reset dropdown if cancelled
            if (pendingStatusChange) {
                const select = pendingStatusChange.selectElement;
                const oldStatus = select.getAttribute('data-old-status');
                if (oldStatus) {
                    select.value = oldStatus;
                }
                pendingStatusChange = null;
            }
        }

        function confirmTracking() {
            const trackingNumber = document.getElementById('tracking_input').value.trim();
            
            if (!trackingNumber) {
                toastError('Please enter a tracking number');
                return;
            }
            
            if (pendingStatusChange) {
                performStatusUpdate(
                    pendingStatusChange.orderId, 
                    pendingStatusChange.newStatus, 
                    trackingNumber,
                    pendingStatusChange.selectElement
                );
                closeTrackingModal();
            }
        }

        function performStatusUpdate(orderId, newStatus, trackingNumber, selectElement) {
            // Show loading
            selectElement.disabled = true;
            
            // Send AJAX request
            fetch('orders.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'ajax_update_status': '1',
                    'order_id': orderId,
                    'status': newStatus,
                    'tracking_number': trackingNumber || ''
                })
            })
            .then(response => response.json())
            .then(data => {
                selectElement.disabled = false;
                
                if (data.success) {
                    toastSuccess(data.message);
                    selectElement.setAttribute('data-old-status', newStatus);
                    
                    // Apply visual styling based on status
                    selectElement.className = 'status-dropdown status-' + newStatus.toLowerCase();
                } else {
                    toastError(data.message);
                    // Reset to old status
                    const oldStatus = selectElement.getAttribute('data-old-status');
                    if (oldStatus) {
                        selectElement.value = oldStatus;
                    }
                }
            })
            .catch(error => {
                selectElement.disabled = false;
                toastError('Failed to update status');
                console.error('Error:', error);
            });
        }

        // Bulk actions
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                const row = cb.closest('tr');
                if (row) {
                    if (checkbox.checked) {
                        row.classList.add('row-selected');
                    } else {
                        row.classList.remove('row-selected');
                    }
                }
            });
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            const bulkBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            
            selectedCount.textContent = checkboxes.length;
            
            if (checkboxes.length > 0) {
                bulkBar.style.display = 'block';
            } else {
                bulkBar.style.display = 'none';
            }
        }

        function clearSelection() {
            document.getElementById('selectAll').checked = false;
            document.querySelectorAll('.order-checkbox').forEach(cb => {
                cb.checked = false;
                const row = cb.closest('tr');
                if (row) {
                    row.classList.remove('row-selected');
                }
            });
            updateBulkActions();
        }

        // Handle bulk form submission
        document.getElementById('bulkForm').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.order-checkbox:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                toastError('Please select at least one order');
                return;
            }
            
            // Add checked order IDs to form
            checkboxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = cb.value;
                this.appendChild(input);
            });
        });

        // Initialize data-old-status for all dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-dropdown').forEach(select => {
                select.setAttribute('data-old-status', select.value);
                select.className = 'status-dropdown status-' + select.value.toLowerCase();
            });
            
            // Add click event to selectable rows
            document.querySelectorAll('.selectable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on checkbox, status dropdown, or action buttons
                    if (e.target.closest('input[type="checkbox"]') || 
                        e.target.closest('.status-dropdown') || 
                        e.target.closest('.table-actions') ||
                        e.target.tagName === 'SELECT' ||
                        e.target.tagName === 'A' ||
                        e.target.tagName === 'BUTTON') {
                        return;
                    }
                    
                    // Toggle checkbox
                    const checkbox = this.querySelector('.order-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        updateBulkActions();
                        
                        // Add visual feedback with class
                        if (checkbox.checked) {
                            this.classList.add('row-selected');
                        } else {
                            this.classList.remove('row-selected');
                        }
                    }
                });
            });
            
            // Update row background on checkbox change
            document.querySelectorAll('.order-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const row = this.closest('tr');
                    if (this.checked) {
                        row.classList.add('row-selected');
                    } else {
                        row.classList.remove('row-selected');
                    }
                });
            });
        });
        
        // Show toast notifications for success/error messages
        <?php if($success): ?>
            window.addEventListener('DOMContentLoaded', function() {
                toastSuccess('<?php echo addslashes($success); ?>');
            });
        <?php endif; ?>
        
        <?php if($error): ?>
            window.addEventListener('DOMContentLoaded', function() {
                toastError('<?php echo addslashes($error); ?>');
            });
        <?php endif; ?>
    </script>
</body>
</html>
