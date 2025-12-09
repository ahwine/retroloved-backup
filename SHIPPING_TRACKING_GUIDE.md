# 🚚 SHIPPING & ORDER TRACKING SYSTEM
## RetroLoved E-Commerce - Implementation Guide

---

## 📋 FITUR YANG DITAMBAHKAN

### 1. **Shipping Service Selection (Checkout)**
- ✅ Pilihan ekspedisi multiple (JNE, J&T, SiCepat, AnterAja, Pickup)
- ✅ Real-time price calculation (Subtotal + Ongkir = Total)
- ✅ Service comparison (Cepat, Hemat, Gratis Ongkir)
- ✅ Estimasi waktu pengiriman per service
- ✅ Visual card selection dengan radio button

### 2. **Order Tracking Timeline (Customer)**
- ✅ Visual timeline dengan progress bar
- ✅ 9 status tracking predefined
- ✅ Informasi ekspedisi & nomor resi
- ✅ Lokasi terkini paket
- ✅ Estimasi waktu tiba
- ✅ Info kurir (nama & telepon)
- ✅ Button konfirmasi "Pesanan Diterima"
- ✅ Relative time display (2 hours ago, etc)

### 3. **Admin Tracking Management**
- ✅ Form update tracking dengan grid status
- ✅ Auto-suggest next status
- ✅ Input lokasi, tracking number, kurir
- ✅ Auto-calculate estimated delivery
- ✅ Notifikasi otomatis ke customer
- ✅ Complete history log semua perubahan
- ✅ Prevent backward status update

---

## 🗄️ DATABASE STRUCTURE

### New Tables Created:

1. **shipping_couriers** - Master data ekspedisi
2. **shipping_services** - Layanan per ekspedisi
3. **tracking_statuses** - Predefined tracking statuses

### Modified Tables:

**orders** table - Added columns:
- shipping_service_id (INT)
- shipping_cost (DECIMAL)
- subtotal (DECIMAL)
- courier_name (VARCHAR)
- courier_phone (VARCHAR)
- current_location (VARCHAR)
- current_status_detail (VARCHAR)
- estimated_delivery_date (DATETIME)
- shipped_at (TIMESTAMP)
- delivered_at (TIMESTAMP)

**order_history** table - Added columns:
- status_detail (VARCHAR)
- location (VARCHAR)
- courier_name (VARCHAR)
- courier_phone (VARCHAR)
- estimated_arrival (DATETIME)

---

## 📦 FILES CREATED

### Database Files:
- \database/add_shipping_system.sql\ - Main migration file
- \database/run_shipping_migration.php\ - Migration runner with UI

### Configuration Files:
- \config/shipping.php\ - Helper functions for shipping

### Component Files:
- \includes/shipping-selection.php\ - Checkout shipping selector
- \includes/tracking-timeline.php\ - Customer tracking timeline
- \includes/admin-tracking-form.php\ - Admin tracking update form

### CSS Files:
- \ssets/css/tracking.css\ - Tracking styles (consistent with design)

---

## 🚀 INSTALLATION STEPS

### Step 1: Run Database Migration
\\\
1. Buka browser: http://localhost/retroloved/database/run_shipping_migration.php
2. Migration akan otomatis berjalan
3. Check hasilnya (harus 0 errors)
\\\

### Step 2: Update checkout.php
Tambahkan shipping selection setelah alamat pengiriman:

\\\php
<!-- Di customer/checkout.php, setelah section alamat -->
<?php include '../includes/shipping-selection.php'; ?>
\\\

### Step 3: Update Checkout Form Handler
Di bagian PROSES CHECKOUT, tambahkan:

\\\php
// Ambil shipping data
\ = isset(\['shipping_service_id']) ? escape(\['shipping_service_id']) : null;
\ = isset(\['shipping_cost']) ? floatval(\['shipping_cost']) : 0;
\ = isset(\['subtotal']) ? floatval(\['subtotal']) : \;
\ = \ + \;

// Validate shipping selection
if(!\) {
    \ = "Mohon pilih layanan pengiriman!";
}

// Update INSERT query
\ = "INSERT INTO orders (user_id, customer_name, customer_email, 
                subtotal, shipping_cost, total_amount, shipping_service_id,
                shipping_address, phone, payment_method, status, current_status_detail) 
                VALUES ('\', '\', '\', 
                '\', '\', '\', '\',
                '\', '\', '\', 'Pending', 'order_placed')";
\\\

### Step 4: Update customer/orders.php
Tambahkan tracking timeline di order detail:

\\\php
<!-- Di customer/orders.php, dalam order detail modal/section -->
<?php 
// Load shipping functions
require_once '../config/shipping.php';

// Include tracking timeline
include '../includes/tracking-timeline.php'; 
?>
\\\

### Step 5: Update admin/order-detail.php
Tambahkan tracking form dan timeline:

\\\php
<!-- Setelah section "Update Status" yang lama -->
<?php include '../includes/admin-tracking-form.php'; ?>

<!-- Untuk timeline, update section "Order Timeline" -->
<?php 
require_once '../config/shipping.php';
\ = get_order_tracking_history(\);
// Display timeline using the data
?>
\\\

### Step 6: Handle Admin Tracking Update
Di admin/order-detail.php, tambahkan handler:

\\\php
// HANDLE TRACKING UPDATE
if(isset(\['update_tracking'])) {
    \ = [
        'status_detail' => escape(\['status_detail']),
        'location' => escape(\['location']),
        'tracking_number' => escape(\['tracking_number']),
        'courier_name' => escape(\['courier_name']),
        'courier_phone' => escape(\['courier_phone']),
        'notes' => escape(\['notes']),
        'estimated_arrival' => escape(\['estimated_arrival'])
    ];
    
    if(update_order_tracking(\, \)) {
        // Send notification if checkboxes checked
        if(isset(\['send_email'])) {
            // Send email notification
        }
        if(isset(\['send_in_app'])) {
            send_order_status_notification(\, \['status']);
        }
        
        \ = "Status tracking berhasil diupdate!";
        
        // Refresh data
        \ = mysqli_fetch_assoc(query("SELECT * FROM orders WHERE order_id = '\'"));
    }
}
\\\

### Step 7: Handle Customer Delivery Confirmation
Di customer/orders.php, tambahkan:

\\\php
// CONFIRM DELIVERY
if(isset(\['confirm_delivery'])) {
    \ = escape(\['order_id']);
    
    // Update to delivered
    query("UPDATE orders SET 
           status = 'Delivered', 
           current_status_detail = 'delivered',
           delivered_at = NOW()
           WHERE order_id = '\' AND user_id = '\'");
    
    // Add to history
    \ = [
        'status_detail' => 'delivered',
        'location' => 'Customer Address',
        'notes' => 'Package confirmed received by customer'
    ];
    update_order_tracking(\, \);
    
    set_message('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');
}
\\\

### Step 8: Include CSS
Tambahkan di header files:

\\\html
<link rel="stylesheet" href="../assets/css/tracking.css">
\\\

---

## 🎨 DESIGN SYSTEM

### Colors Used (Consistent):
- Primary: #D97706 (Orange)
- Success: #10B981 (Green)
- Info: #3B82F6 (Blue)
- Warning: #F59E0B (Yellow)
- Danger: #EF4444 (Red)
- Text: #1F2937 (Dark Gray)
- Border: #E5E7EB (Light Gray)

### Components Style:
- ✅ No gradients (solid colors only)
- ✅ No emojis (SVG icons only)
- ✅ Consistent border-radius: 8px
- ✅ Consistent padding: 12px, 16px, 24px
- ✅ Font: Inter (same as index)

---

## 📊 MASTER DATA

### Ekspedisi Aktif:
1. **JNE Express**
   - JNE Regular (Rp 15.000, 3-4 hari)
   - JNE YES (Rp 25.000, 1-2 hari)
   - JNE OKE (Rp 12.000, 4-6 hari)

2. **J&T Express**
   - J&T Economy (Rp 12.000, 3-5 hari)
   - J&T Regular (Rp 15.000, 2-4 hari)

3. **SiCepat Express**
   - SiCepat REG (Rp 15.000, 2-3 hari)
   - SiCepat HALU (Rp 18.000, 1-2 hari)

4. **AnterAja**
   - AnterAja Regular (Rp 14.000, 2-4 hari)
   - AnterAja Next Day (Rp 20.000, 1-2 hari)

5. **Ambil Sendiri**
   - Ambil di Toko (Rp 0, Instant)

### Tracking Statuses (9 Steps):
1. Order Placed (Pesanan Dibuat)
2. Payment Confirmed (Pembayaran Dikonfirmasi)
3. Being Packed (Pesanan Dikemas)
4. Picked Up by Courier (Diserahkan ke Kurir)
5. At Sorting Center (Di Sorting Center)
6. In Transit (Dalam Perjalanan)
7. Arrived at Destination (Tiba di Kota Tujuan)
8. Out for Delivery (Dikirim ke Alamat)
9. Delivered (Pesanan Diterima)

---

## 🧪 TESTING CHECKLIST

### Checkout Flow:
- [ ] Pilih produk & masuk ke checkout
- [ ] Pilih alamat pengiriman
- [ ] Pilih layanan ekspedisi (cek harga update otomatis)
- [ ] Total = Subtotal + Ongkir (cek perhitungan)
- [ ] Submit order (cek data masuk database)

### Admin Tracking:
- [ ] Buka order detail di admin
- [ ] Update status tracking dengan form baru
- [ ] Cek history log tersimpan
- [ ] Cek notifikasi terkirim ke customer
- [ ] Test prevent backward status

### Customer Tracking:
- [ ] Buka My Orders sebagai customer
- [ ] Lihat tracking timeline
- [ ] Cek progress bar
- [ ] Cek info ekspedisi & resi
- [ ] Konfirmasi pesanan diterima

---

## 🔧 CUSTOMIZATION

### Menambah Ekspedisi Baru:
\\\sql
INSERT INTO shipping_couriers (courier_code, courier_name) 
VALUES ('gosend', 'GoSend');

INSERT INTO shipping_services (courier_id, service_code, service_name, base_cost, estimated_days_min, estimated_days_max)
VALUES (LAST_INSERT_ID(), 'INSTANT', 'GoSend Instant', 10000, 0, 0);
\\\

### Mengubah Harga Ongkir:
\\\sql
UPDATE shipping_services 
SET base_cost = 20000 
WHERE service_code = 'YES' AND courier_id = 1;
\\\

### Menonaktifkan Ekspedisi:
\\\sql
UPDATE shipping_couriers SET is_active = 0 WHERE courier_code = 'jnt';
\\\

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:
1. Cek console browser untuk JavaScript errors
2. Cek error_log PHP untuk backend errors
3. Pastikan semua file sudah di-include dengan benar
4. Pastikan database migration berhasil 100%

---

## 🎉 NEXT FEATURES (Future Enhancement)

- [ ] API Integration dengan RajaOngkir (real-time ongkir)
- [ ] SMS notification untuk tracking update
- [ ] Gratis ongkir voucher system
- [ ] COD (Cash on Delivery) payment
- [ ] Insurance option untuk pengiriman
- [ ] Bulk tracking update (multiple orders)
- [ ] Print shipping label
- [ ] Customer rating untuk kurir

---

**Created with ❤️ for RetroLoved E-Commerce**
**Version: 1.0**
**Date: 09 December 2025**
