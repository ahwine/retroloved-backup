# 🔒 Fitur Blokir/Unblokir & Email Customer

## 📋 Deskripsi
Fitur baru pada halaman **Admin > Customers** yang memungkinkan admin untuk:
- ✅ **Blokir Customer** - Mencegah customer login ke sistem
- ✅ **Unblokir Customer** - Mengaktifkan kembali akun customer yang diblokir
- ✅ **Email Customer** - Menghubungi customer langsung melalui email (Gmail/Outlook)

---

## 🎯 Fitur Utama

### 1. Blokir Customer
- Admin dapat memblokir customer dengan satu klik
- Customer yang diblokir **tidak bisa login**
- Status berubah menjadi badge merah "Blocked"
- Konfirmasi dialog sebelum aksi
- Toast notification untuk feedback

### 2. Unblokir Customer
- Admin dapat membuka blokir customer kapan saja
- Customer bisa login kembali setelah diunblock
- Status berubah menjadi badge hijau "Active"
- Konfirmasi dialog sebelum aksi
- Toast notification untuk feedback

### 3. Email Customer
- Klik tombol "Email" untuk menghubungi customer
- Otomatis membuka Gmail/Outlook/email client default
- Email customer sudah terisi di field "To:"
- Admin tinggal menulis pesan dan kirim

---

## 🚀 Cara Menggunakan

### Blokir Customer
1. Login sebagai **Admin**
2. Buka menu **Customers** dari sidebar
3. Cari customer yang ingin diblokir
4. Klik tombol **Block** (merah)
5. Konfirmasi aksi pada dialog
6. ✅ Customer berhasil diblokir!

### Unblokir Customer
1. Login sebagai **Admin**
2. Buka menu **Customers** dari sidebar
3. Cari customer dengan status **Blocked** (badge merah)
4. Klik tombol **Unblock** (hijau)
5. Konfirmasi aksi pada dialog
6. ✅ Customer berhasil diunblock!

### Email Customer
1. Login sebagai **Admin**
2. Buka menu **Customers** dari sidebar
3. Klik tombol **Email** (biru) pada customer yang ingin dihubungi
4. Email client akan terbuka otomatis
5. Tulis pesan Anda dan klik Send

---

## 🔐 Keamanan

### Validasi Backend
- ✅ Hanya admin yang bisa blokir/unblokir
- ✅ Tidak bisa blokir akun admin
- ✅ Tidak bisa blokir diri sendiri
- ✅ Semua aktivitas tercatat di log
- ✅ SQL injection prevention

### Validasi Login
- ✅ Customer yang diblokir tidak bisa login
- ✅ Pesan error yang jelas saat login diblokir
- ✅ Session tidak dibuat untuk user yang diblokir

---

## 💻 Instalasi

### Step 1: Jalankan Migration SQL
Buka **phpMyAdmin** atau **MySQL Command Line**, kemudian jalankan:

```sql
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 COMMENT '1=Active, 0=Blocked';

ALTER TABLE users ADD INDEX IF NOT EXISTS idx_is_active (is_active);

UPDATE users SET is_active = 1 WHERE is_active IS NULL;
```

**Atau** jalankan file migration:
```bash
mysql -u root retroloved < database/ensure_user_is_active_column.sql
```

### Step 2: Verifikasi File
Pastikan file-file berikut sudah ada:
- ✅ `admin/process-customer-action.php`
- ✅ `admin/customers.php` (sudah diupdate)
- ✅ `auth/process-auth.php` (sudah diupdate)
- ✅ `database/ensure_user_is_active_column.sql`

### Step 3: Test Fitur
1. Login sebagai admin
2. Buka halaman Customers
3. Coba blokir customer
4. Logout dan coba login dengan customer yang diblokir
5. Pastikan muncul error message
6. Login kembali sebagai admin dan unblock customer

---

## 📱 Tampilan

### Desktop View
- Tabel lengkap dengan kolom Status
- Tombol View, Email, Block/Unblock dalam satu baris
- Status badge (Active/Blocked) dengan icon

### Mobile View
- Card view yang responsive
- Status badge di bagian meta info
- Tombol aksi tersusun rapi di bawah card
- Touch-friendly button size

---

## 🎨 Status Badge

| Status | Badge | Warna | Deskripsi |
|--------|-------|-------|-----------|
| Active | ✅ Active | Hijau | Customer bisa login normal |
| Blocked | ❌ Blocked | Merah | Customer tidak bisa login |

---

## 📊 Database Schema

### Tabel: `users`

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| is_active | TINYINT(1) | 1 | 1 = Active, 0 = Blocked |

**Index:** `idx_is_active` untuk performance query

---

## 🧪 Testing Checklist

- [ ] Migration SQL berhasil dijalankan
- [ ] Kolom `is_active` ada di tabel users
- [ ] Admin bisa blokir customer
- [ ] Customer yang diblokir tidak bisa login
- [ ] Admin bisa unblokir customer
- [ ] Customer yang diunblock bisa login kembali
- [ ] Tombol Email membuka email client
- [ ] Email customer terisi otomatis
- [ ] Tidak bisa blokir akun admin
- [ ] Tidak bisa blokir diri sendiri
- [ ] Toast notification muncul dengan benar
- [ ] Responsive di mobile dan desktop

---

## 📝 Log Aktivitas

Semua aktivitas blokir/unblokir tercatat di **error log**:
```
Admin #1 blocked user #5 (johndoe)
Admin #1 unblocked user #5 (johndoe)
```

Log dapat dilihat di:
- XAMPP: `C:\xampp\apache\logs\error.log`
- Linux: `/var/log/apache2/error.log`

---

## ⚠️ Troubleshooting

### Customer yang diblokir masih bisa login
**Solusi:** 
- Pastikan migration SQL sudah dijalankan
- Clear browser cache dan cookies
- Cek apakah `is_active = 0` di database

### Tombol Email tidak bekerja
**Solusi:**
- Pastikan browser tidak memblokir mailto links
- Install email client default (Gmail, Outlook, Thunderbird)
- Cek settings default apps di Windows/Mac

### Toast notification tidak muncul
**Solusi:**
- Pastikan file `toast.js` ada
- Cek console browser untuk error JavaScript
- Clear browser cache

---

## 🔄 Update Log

**Version 1.0** - December 9, 2025
- ✨ Initial release
- ✨ Fitur blokir/unblokir customer
- ✨ Fitur email customer
- ✨ Status badge Active/Blocked
- ✨ Responsive design untuk mobile

---

## 👨‍💻 Developer

**RetroLoved Development Team**

Untuk pertanyaan atau support, silakan hubungi tim development.

---

## 📄 License

© 2025 RetroLoved E-Commerce System. All rights reserved.
