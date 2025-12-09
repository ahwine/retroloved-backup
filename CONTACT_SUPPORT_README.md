# 📧 Contact Support Feature - Quick Start Guide

## ⚡ Quick Start

### 1. Setup Database
\\\ash
# Jalankan SQL file ini di phpMyAdmin atau MySQL client
database/create_contact_support_table.sql
\\\

### 2. Test Feature
1. Buka browser dan navigasi ke: **faq.php**
2. Scroll ke bawah dan klik tombol **"Contact Support"**
3. Modal akan muncul
4. Isi form dan klik **"Kirim Pesan"**

### 3. Check Email
- Email akan dikirim ke: **support@retroloved.com**
- Data juga tersimpan di database tabel **contact_support**

---

## 📍 Dimana Tombol Contact Support Tersedia?

1. **FAQ Page** (faq.php) - Di bagian bawah
2. **Shipping & Delivery** (shipping-delivery.php) - Di bagian bawah
3. **Size Guide** (size-guide.php) - Di bagian bawah

---

## 🎯 Fitur

✅ Modal popup yang modern dan responsive
✅ Form dengan validasi lengkap
✅ Pengiriman email otomatis
✅ Database logging untuk tracking
✅ Auto-fill nama & email untuk user login
✅ 8 kategori subjek yang bisa dipilih
✅ Toast notification untuk feedback
✅ Loading state saat submit

---

## 📋 Form Fields

| Field | Type | Required | Validasi |
|-------|------|----------|----------|
| Nama Lengkap | Text | Ya | Tidak boleh kosong |
| Email | Email | Ya | Format email valid |
| Subjek | Dropdown | Ya | Pilih dari list |
| Pesan | Textarea | Ya | Min 10 karakter |

---

## 🎨 Subjek yang Tersedia

1. Pertanyaan Produk
2. Pertanyaan Pesanan
3. Masalah Pembayaran
4. Masalah Pengiriman
5. Pengembalian/Refund
6. Masalah Akun
7. Saran & Feedback
8. Lainnya

---

## 🔧 Konfigurasi Email (Localhost)

### Windows (XAMPP):

Edit **C:\xampp\php\php.ini**:
\\\ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_path = "C:\xampp\sendmail\sendmail.exe -t"
\\\

Edit **C:\xampp\sendmail\sendmail.ini**:
\\\ini
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=your-email@gmail.com
auth_password=your-app-password
force_sender=your-email@gmail.com
\\\

**Note:** Untuk Gmail, gunakan App Password, bukan password biasa.

---

## 📊 Database Query

### Lihat semua pesan:
\\\sql
SELECT * FROM contact_support ORDER BY created_at DESC;
\\\

### Lihat pesan baru saja:
\\\sql
SELECT * FROM contact_support 
WHERE status = 'new' 
ORDER BY created_at DESC;
\\\

### Update status pesan:
\\\sql
UPDATE contact_support 
SET status = 'resolved' 
WHERE id = 1;
\\\

---

## 🐛 Troubleshooting

**Modal tidak muncul?**
- Check browser console untuk error
- Pastikan contact-support.js loaded

**Email tidak terkirim?**
- Check PHP mail configuration
- Test dengan: \php -r "mail('test@test.com', 'Test', 'Test');">\
- Check spam folder

**Form tidak submit?**
- Check browser console
- Verify all required fields filled
- Check network tab untuk AJAX request

---

## 📁 File Structure

\\\
retroloved/
├── process-contact-support.php              ← Backend handler
├── assets/
│   ├── css/style.css                       ← Added styles
│   └── js/contact-support.js               ← Modal logic
├── includes/footer.php                      ← Modal HTML
├── database/create_contact_support_table.sql
├── CHANGELOG_CONTACT_SUPPORT.md            ← Change log
├── CONTACT_SUPPORT_DOCUMENTATION.md        ← Full docs
└── tmp_rovodev_test_contact_support.html   ← Test file
\\\

---

## 📚 Documentation

- **Full Documentation:** CONTACT_SUPPORT_DOCUMENTATION.md
- **Change Log:** CHANGELOG_CONTACT_SUPPORT.md
- **Test File:** tmp_rovodev_test_contact_support.html

---

## ✅ Testing Checklist

- [ ] Modal opens when button clicked
- [ ] Modal closes with X button
- [ ] Modal closes when clicking outside
- [ ] Form validation works
- [ ] Email is sent successfully
- [ ] Data saved to database
- [ ] Toast notification appears
- [ ] Form resets after submit
- [ ] Responsive on mobile
- [ ] Auto-fill works for logged-in users

---

**Created:** 08 December 2025
**Version:** 1.0.0
