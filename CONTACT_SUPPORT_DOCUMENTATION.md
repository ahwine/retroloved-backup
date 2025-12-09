# 📧 Contact Support Feature - Dokumentasi Lengkap

## Overview
Fitur Contact Support memungkinkan pengguna untuk mengirim pesan langsung ke tim support RetroLoved melalui email dengan interface modal yang modern dan user-friendly.

---

## 🎯 Fitur Utama

### 1. Modal Interaktif
- Panel yang muncul ketika button "Contact Support" diklik
- Desain modern dengan gradient purple
- Responsive untuk semua ukuran layar
- Dapat ditutup dengan:
  - Klik tombol X
  - Klik di luar modal
  - Tombol Batal

### 2. Form Contact Support
**Field yang tersedia:**
- **Nama Lengkap** (required)
  - Auto-fill jika user sudah login
  
- **Email** (required)
  - Validasi format email
  - Auto-fill jika user sudah login
  
- **Subjek** (required, dropdown)
  - Pertanyaan Produk
  - Pertanyaan Pesanan
  - Masalah Pembayaran
  - Masalah Pengiriman
  - Pengembalian/Refund
  - Masalah Akun
  - Saran & Feedback
  - Lainnya
  
- **Pesan** (required, textarea)
  - Minimal 10 karakter
  - Multi-line input

### 3. Validasi
- **Client-side**: HTML5 validation (instant feedback)
- **Server-side**: PHP validation untuk keamanan
- Error messages yang jelas dan informatif

### 4. Email Notification
- Email dikirim ke: **support@retroloved.com**
- Format HTML yang profesional dengan styling
- Reply-to otomatis ke email pengirim
- Subject format: "Contact Support: [subjek]"
- Berisi semua informasi lengkap

### 5. Database Logging
- Setiap pesan disimpan di tabel **contact_support**
- Tracking status pesan (new/in_progress/resolved)
- Menyimpan user_id jika user sudah login
- Timestamp untuk created_at dan updated_at

---

## 📁 Struktur File

\\\
retroloved/
├── process-contact-support.php          # Backend handler
├── assets/
│   ├── css/
│   │   └── style.css                    # CSS untuk modal (sudah ditambahkan)
│   └── js/
│       └── contact-support.js           # JavaScript untuk modal
├── database/
│   └── create_contact_support_table.sql # SQL untuk membuat tabel
└── includes/
    └── footer.php                       # Modal HTML (sudah ditambahkan)
\\\

---

## 🚀 Cara Menggunakan

### Untuk User (Customer)

1. **Buka halaman yang memiliki tombol Contact Support:**
   - FAQ Page (faq.php)
   - Shipping & Delivery (shipping-delivery.php)
   - Size Guide (size-guide.php)

2. **Klik tombol "Contact Support"**
   - Modal akan muncul dengan form

3. **Isi form:**
   - Nama dan email akan otomatis terisi jika sudah login
   - Pilih subjek dari dropdown
   - Tulis pesan minimal 10 karakter

4. **Klik "Kirim Pesan"**
   - Button akan menampilkan loading state
   - Email akan dikirim ke support team
   - Notifikasi sukses akan muncul
   - Modal akan tertutup otomatis

5. **Tunggu respon dari support team via email**

---

## 💻 Implementasi Teknis

### Backend (process-contact-support.php)

\\\php
// Validasi input
- Name: tidak boleh kosong
- Email: harus valid email format
- Subject: tidak boleh kosong
- Message: minimal 10 karakter

// Pengiriman Email
- To: support@retroloved.com
- From: RetroLoved <noreply@retroloved.com>
- Reply-To: [email pengirim]
- Format: HTML dengan styling

// Database
- Auto-create table jika belum ada
- Insert data ke contact_support table
- Return JSON response
\\\

### Frontend JavaScript (contact-support.js)

\\\javascript
// Functions:
- showContactSupportModal()      // Membuka modal
- closeContactSupportModal()     // Menutup modal
- Form submission handler        // Handle submit dengan AJAX
- Path detection                 // Auto-detect path untuk fetch URL

// Features:
- Auto-fill untuk logged-in users
- Loading state pada submit button
- Toast notifications
- Error handling
\\\

### CSS Styling

\\\css
// Main Classes:
- #contactSupportModal          // Modal container
- .contact-support-intro        // Header dengan gradient
- .support-form-group           // Form field wrapper
- .support-form-actions         // Button container
- .support-icon-wrapper         // Icon dengan animasi

// Responsive:
- Desktop: Max-width 600px
- Mobile: Width 95%, stacked buttons
\\\

---

## 🗄️ Database Schema

\\\sql
CREATE TABLE contact_support (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 0,                    -- 0 untuk guest
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'in_progress', 'resolved') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
\\\

---

## 🧪 Testing

### Manual Testing Steps:

1. **Test Modal Functionality**
   - Buka tmp_rovodev_test_contact_support.html
   - Klik tombol "Open Contact Support"
   - Modal harus muncul dengan smooth animation

2. **Test Form Validation**
   - Coba submit tanpa mengisi field → Error
   - Coba isi email tidak valid → Error
   - Coba isi message kurang dari 10 karakter → Error

3. **Test Form Submission**
   - Isi semua field dengan benar
   - Klik "Kirim Pesan"
   - Button harus show loading state
   - Toast success/error harus muncul
   - Modal harus tertutup jika sukses

4. **Test Email Delivery**
   - Submit form
   - Cek inbox support@retroloved.com
   - Email harus masuk dengan format HTML yang rapi

5. **Test Database**
   - Query table: SELECT * FROM contact_support
   - Data harus tersimpan dengan lengkap

---

## ⚙️ Konfigurasi Email

### Untuk Development (localhost):

Jika menggunakan XAMPP, edit **php.ini**:

\\\ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = your-email@gmail.com
sendmail_path = "\"C:\\xampp\\sendmail\\sendmail.exe\" -t"
\\\

Edit **sendmail.ini**:

\\\ini
[sendmail]
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=your-email@gmail.com
auth_password=your-app-password
force_sender=your-email@gmail.com
\\\

### Untuk Production:

Email akan otomatis bekerja jika server sudah dikonfigurasi dengan benar.

---

## 🎨 Customization

### Mengubah Email Tujuan:

Edit **process-contact-support.php** line 47:
\\\php
\ = 'support@retroloved.com'; // Ganti dengan email Anda
\\\

### Menambah Subjek Baru:

Edit **includes/footer.php** di bagian select options:
\\\html
<option value="Subjek Baru">Subjek Baru</option>
\\\

### Mengubah Warna Theme:

Edit **assets/css/style.css** di bagian Contact Support Modal:
\\\css
.contact-support-intro {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Ganti dengan gradient/warna pilihan Anda */
}
\\\

---

## 🐛 Troubleshooting

### Problem: Email tidak terkirim
**Solution:**
- Cek konfigurasi PHP mail() di server
- Cek php.ini dan sendmail.ini settings
- Test dengan simple PHP mail script
- Cek spam folder

### Problem: Modal tidak muncul
**Solution:**
- Cek browser console untuk error JavaScript
- Pastikan contact-support.js sudah di-load
- Cek CSS untuk .modal class

### Problem: Form validation tidak bekerja
**Solution:**
- Pastikan browser support HTML5
- Cek JavaScript errors di console
- Test dengan browser yang berbeda

### Problem: Database error
**Solution:**
- Jalankan create_contact_support_table.sql
- Cek database connection
- Cek MySQL user permissions

---

## 📊 Monitoring & Analytics

### Queries Berguna:

**Lihat semua pesan:**
\\\sql
SELECT * FROM contact_support ORDER BY created_at DESC;
\\\

**Lihat pesan baru:**
\\\sql
SELECT * FROM contact_support WHERE status = 'new' ORDER BY created_at DESC;
\\\

**Statistik per subjek:**
\\\sql
SELECT subject, COUNT(*) as total 
FROM contact_support 
GROUP BY subject 
ORDER BY total DESC;
\\\

**Pesan dari user tertentu:**
\\\sql
SELECT * FROM contact_support 
WHERE user_id = [USER_ID] 
ORDER BY created_at DESC;
\\\

---

## 🔒 Security Features

1. **SQL Injection Prevention**
   - Menggunakan mysqli_real_escape_string()
   
2. **XSS Prevention**
   - Menggunakan htmlspecialchars() untuk output
   
3. **Email Validation**
   - Server-side validation dengan filter_var()
   
4. **CSRF Protection**
   - Bisa ditambahkan dengan token (opsional)

---

## 📝 Future Enhancements

Beberapa improvement yang bisa ditambahkan:

1. **File Upload**
   - Allow user upload screenshot
   
2. **Admin Panel**
   - Dashboard untuk manage support tickets
   - Reply langsung dari admin panel
   
3. **Email Templates**
   - Auto-reply ke customer
   - Email notification ke admin
   
4. **Priority System**
   - Urgent, High, Medium, Low
   
5. **Department Routing**
   - Route berdasarkan subjek ke department
   
6. **Chat Integration**
   - Real-time chat support
   
7. **Analytics Dashboard**
   - Response time tracking
   - Customer satisfaction rating

---

## 👥 Support

Jika ada pertanyaan atau issue:
- Email: dev@retroloved.com
- Documentation: CHANGELOG_CONTACT_SUPPORT.md

---

**Version:** 1.0.0  
**Last Updated:** 08 December 2025  
**Author:** Rovo Dev
