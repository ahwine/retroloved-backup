# 🚀 Panduan Upload Proyek RetroLoved ke GitHub

## 📋 Persiapan Sebelum Upload

### 1️⃣ Pastikan Git sudah terinstall
Cek dengan command:
```
git --version
```

Jika belum terinstall, download dari: https://git-scm.com/

### 2️⃣ Buat Repository di GitHub
1. Buka https://github.com dan login
2. Klik tombol **"+"** di pojok kanan atas → **"New repository"**
3. Isi nama repository: **retroloved-ecommerce** (atau nama lain)
4. Pilih **Private** (untuk menjaga keamanan data)
5. **JANGAN** centang "Initialize with README" (karena kita sudah punya)
6. Klik **"Create repository"**

---

## 🔧 Langkah-Langkah Upload

### Step 1: Inisialisasi Git Repository
Buka terminal/PowerShell di folder proyek, lalu jalankan:

```powershell
git init
```

### Step 2: Konfigurasi Git (jika belum pernah)
```powershell
git config --global user.name "Nama Anda"
git config --global user.email "email@anda.com"
```

### Step 3: Add Remote Repository
Ganti username dan epo-name dengan milik Anda:
```powershell
git remote add origin https://github.com/username/repo-name.git
```

### Step 4: Add Semua File ke Staging
```powershell
git add .
```

### Step 5: Commit Pertama
```powershell
git commit -m "Initial commit: RetroLoved E-Commerce Platform v2.0"
```

### Step 6: Push ke GitHub
```powershell
git branch -M main
git push -u origin main
```

💡 **Tips:** Jika diminta login, gunakan Personal Access Token (PAT) bukan password biasa.

---

## 🔐 Keamanan & Konfigurasi

### ⚠️ File yang TIDAK ikut terupload (sudah di .gitignore):
- ✅ config/database.php - Kredensial database
- ✅ config/email.php - Kredensial email
- ✅ ssets/images/products/* - Gambar produk (user upload)
- ✅ ssets/images/payments/* - Bukti pembayaran
- ✅ endor/ - Dependencies (akan diinstall ulang)

### 📝 File Template yang HARUS diisi saat clone/deploy:
1. **config/database.example.php** → Copy dan rename ke database.php, isi kredensial
2. **config/email.example.php** → Copy dan rename ke email.php, isi kredensial

---

## 📦 Restore Database di Server Baru

### 1. Export Database (dari XAMPP/local):
```powershell
# Via phpMyAdmin: Export → SQL → Go
# Atau via command line:
mysqldump -u root -p retroloved > database/retroloved_backup.sql
```

### 2. Import Database (di server baru):
```sql
-- Buat database baru
CREATE DATABASE retroloved CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import file SQL
SOURCE database/retroloved_backup.sql;
```

---

## 🔄 Update Proyek di GitHub (setelah ada perubahan)

```powershell
# 1. Add perubahan
git add .

# 2. Commit dengan pesan
git commit -m "Deskripsi perubahan yang dibuat"

# 3. Push ke GitHub
git push
```

---

## 📥 Clone Proyek ke Komputer/Server Lain

```powershell
# 1. Clone repository
git clone https://github.com/username/repo-name.git

# 2. Masuk ke folder proyek
cd repo-name

# 3. Install dependencies (jika ada composer)
composer install

# 4. Copy dan isi file konfigurasi
copy config/database.example.php config/database.php
copy config/email.example.php config/email.php

# 5. Edit file konfigurasi dengan kredensial yang sesuai

# 6. Import database
# - Buat database baru di MySQL
# - Import file SQL dari folder database/

# 7. Setup folder permissions (Linux/Mac)
chmod 755 assets/images/products
chmod 755 assets/images/payments
```

---

## 🛠️ Troubleshooting

### Problem: Push ditolak
**Solusi:**
```powershell
git pull origin main --rebase
git push
```

### Problem: File sensitif terupload
**Solusi:**
```powershell
# Hapus dari cache Git
git rm --cached config/database.php
git rm --cached config/email.php

# Commit dan push
git commit -m "Remove sensitive files"
git push
```

### Problem: Folder kosong tidak terupload
**Solusi:** Tambahkan file .gitkeep di folder kosong
```powershell
New-Item -Path "assets/images/profiles/.gitkeep" -ItemType File
git add assets/images/profiles/.gitkeep
git commit -m "Add .gitkeep for empty folders"
git push
```

---

## 📚 Struktur Proyek

```
retroloved/
├── admin/              # Dashboard admin
├── assets/             # CSS, JS, Images
├── auth/               # Login, Register, Logout
├── config/             # Konfigurasi database & email
├── customer/           # Fitur customer (cart, checkout, profile)
├── database/           # File SQL dan migrasi
├── includes/           # Component reusable (header, footer)
├── vendor/             # Dependencies (PHPMailer)
├── .gitignore          # File yang diabaikan Git
└── README.md           # Dokumentasi proyek
```

---

## 💾 Backup Otomatis (Opsional)

Buat script backup otomatis dengan cron job atau Task Scheduler:

```powershell
# backup.ps1
$date = Get-Date -Format "yyyyMMdd_HHmmss"
mysqldump -u root -p retroloved > "backups/retroloved_$date.sql"
git add .
git commit -m "Auto backup $date"
git push
```

---

## 📞 Support

Jika ada masalah:
1. Cek file TROUBLESHOOTING.md
2. Lihat GitHub Issues
3. Hubungi developer

---

**✅ Selamat! Proyek Anda sudah siap dibackup ke GitHub!**
