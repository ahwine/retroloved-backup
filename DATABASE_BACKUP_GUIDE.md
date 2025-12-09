# 📦 Database Backup & Restore Guide

## 🔄 Export Database dari XAMPP (Backup)

### Method 1: Via phpMyAdmin (Recommended - Mudah)
1. Buka browser: http://localhost/phpmyadmin
2. Klik database **retroloved** di sidebar kiri
3. Klik tab **Export** di menu atas
4. Pilih method: **Quick** (untuk backup cepat) atau **Custom** (untuk kontrol lebih)
5. Format: **SQL**
6. Klik tombol **Go**
7. File .sql akan terdownload ke folder Downloads Anda
8. Copy file tersebut ke folder **database/** di proyek

### Method 2: Via Command Line (Advanced)
```powershell
# Buka PowerShell/CMD di folder proyek
cd C:\xampp\mysql\bin

# Export database
.\mysqldump.exe -u root -p retroloved > "C:\path\to\project\database\retroloved_backup.sql"

# Atau tanpa password (jika root tidak ada password)
.\mysqldump.exe -u root retroloved > "C:\path\to\project\database\retroloved_backup.sql"
```

---

## 📥 Import Database di Server/Komputer Baru

### Method 1: Via phpMyAdmin (Recommended - Mudah)
1. Buka phpMyAdmin di server/komputer baru
2. Klik **New** untuk buat database baru
3. Nama database: **retroloved**
4. Collation: **utf8mb4_unicode_ci**
5. Klik **Create**
6. Klik database **retroloved** yang baru dibuat
7. Klik tab **Import**
8. Klik **Choose File** → pilih file etroloved_backup.sql
9. Klik **Go** (tunggu hingga selesai)
10. ✅ Database berhasil di-restore!

### Method 2: Via Command Line (Advanced)
```powershell
# Masuk ke folder MySQL
cd C:\xampp\mysql\bin

# Import database
.\mysql.exe -u root -p retroloved < "C:\path\to\database\retroloved_backup.sql"

# Atau tanpa password
.\mysql.exe -u root retroloved < "C:\path\to\database\retroloved_backup.sql"
```

---

## 📋 Struktur Database RetroLoved

### Tabel Utama:
- **users** - Data pengguna (customer & admin)
- **products** - Data produk vintage
- **orders** - Data pesanan
- **order_items** - Detail item dalam pesanan
- **order_history** - Riwayat perubahan status pesanan
- **shipping_addresses** - Alamat pengiriman customer
- **notifications** - Notifikasi untuk customer
- **contact_support** - Pesan customer ke admin
- **email_verifications** - Token verifikasi email
- **password_resets** - Token reset password

---

## ⚠️ PENTING: Sebelum Upload ke GitHub

### ❌ JANGAN upload file database yang berisi data sensitif:
- Data customer asli
- Data pembayaran
- Email & password
- Nomor telepon
- Alamat pengiriman

### ✅ Yang AMAN diupload:
- Schema database (struktur tabel tanpa data)
- File migrasi (script untuk create/alter table)
- Data dummy/sample untuk testing

---

## 🧪 Export Schema Only (Tanpa Data)

### Via phpMyAdmin:
1. Export → Custom method
2. Scroll ke **Object creation options**
3. Centang hanya:
   - ✅ Structure
   - ❌ Data (uncheck ini!)
4. Go → Save sebagai etroloved_schema.sql

### Via Command Line:
```powershell
# Export hanya struktur (tanpa data)
.\mysqldump.exe -u root -p --no-data retroloved > "database\retroloved_schema.sql"

# Export struktur + data dari tabel tertentu (contoh: hanya products)
.\mysqldump.exe -u root -p retroloved products > "database\sample_products.sql"
```

---

## 🔐 Best Practice untuk GitHub

### File yang HARUS ada di GitHub:
```
database/
├── retroloved_schema.sql       # Struktur database (AMAN)
├── sample_data.sql             # Data dummy untuk testing (AMAN)
├── migrations/                 # File-file migrasi
│   ├── 001_create_tables.sql
│   ├── 002_add_columns.sql
│   └── ...
└── README_DATABASE.md          # Dokumentasi database
```

### File yang TIDAK boleh di GitHub:
```
database/
├── retroloved_backup.sql       # Full backup dengan data asli (❌)
├── production_backup.sql       # Backup dari server live (❌)
└── customer_data.sql           # Data customer asli (❌)
```

**Tambahkan ke .gitignore:**
```
# Database backups dengan data sensitif
database/*_backup.sql
database/production*.sql
database/*_full.sql
```

---

## 📝 Checklist Sebelum Deploy/Clone

### Di Komputer Lama (Source):
- [ ] Export database via phpMyAdmin
- [ ] Save file .sql ke folder database/
- [ ] Cek .gitignore sudah benar
- [ ] Push ke GitHub

### Di Komputer Baru (Destination):
- [ ] Clone repository dari GitHub
- [ ] Buat database baru di MySQL
- [ ] Import file .sql via phpMyAdmin
- [ ] Copy config/database.example.php → config/database.php
- [ ] Edit kredensial database di config/database.php
- [ ] Copy config/email.example.php → config/email.php
- [ ] Edit kredensial email di config/email.php
- [ ] Buat folder upload jika belum ada:
  - ssets/images/products/
  - ssets/images/payments/
  - ssets/images/profiles/
- [ ] Test website di browser

---

## 🆘 Troubleshooting

### Error: "Unknown database 'retroloved'"
**Solusi:** Database belum dibuat, buat dulu via phpMyAdmin atau:
```sql
CREATE DATABASE retroloved CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Error: "Table doesn't exist"
**Solusi:** Import file SQL belum berhasil, coba import ulang via phpMyAdmin

### Error: "Access denied for user"
**Solusi:** Cek kredensial di config/database.php sudah benar

### Database import sangat lambat
**Solusi:** 
- Gunakan phpMyAdmin untuk file < 50MB
- Untuk file besar, gunakan command line
- Atau split file SQL menjadi beberapa bagian

---

## 📞 Support

Jika ada masalah dengan database:
1. Cek error log di phpMyAdmin
2. Cek file error di C:\xampp\mysql\data\
3. Backup selalu sebelum eksperimen!

**✅ Database Anda sudah aman untuk dibackup!**
