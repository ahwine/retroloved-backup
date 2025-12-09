# ================================================================
# PANDUAN CEPAT: Upload SEMUA ke GitHub (Private Backup)
# ================================================================

## 🚀 CARA UPLOAD (Super Simple!)

### Step 1: Buat Repository di GitHub
1. Buka: https://github.com/new
2. Nama: **retroloved-backup** (atau nama lain)
3. **PENTING: Pilih PRIVATE** ✅
4. JANGAN centang "Initialize with README"
5. Klik **Create repository**
6. Copy URL yang muncul

### Step 2: Export Database (Optional tapi Recommended)
`powershell
# Via phpMyAdmin (Lebih mudah):
# 1. Buka http://localhost/phpmyadmin
# 2. Pilih database 'retroloved'
# 3. Tab Export → Quick → Go
# 4. Save ke folder database/ dengan nama: retroloved_backup.sql
`

### Step 3: Upload SEMUA
`powershell
# Jalankan script otomatis ini:
powershell -ExecutionPolicy Bypass -File upload_private_backup.ps1
`

Atau manual:
`powershell
git init
git config user.name "Nama Anda"
git config user.email "email@anda.com"
git remote add origin https://github.com/USERNAME/REPO.git
git add .
git commit -m "Full backup RetroLoved"
git branch -M main
git push -u origin main
`

## ✅ Yang Akan Diupload (SEMUA!)

- ✅ **Source code** - Semua file .php, .js, .css
- ✅ **Database** - File .sql jika ada
- ✅ **Gambar** - Semua foto di assets/images/
- ✅ **Konfigurasi** - config/database.php, config/email.php
- ✅ **Dependencies** - folder vendor/
- ✅ **Dokumentasi** - Semua file .md

## ⚠️ PENTING!

### Repository HARUS PRIVATE karena berisi:
- 🔑 Password database
- 🔑 Password email SMTP
- 📧 Email customer
- 📱 Nomor telepon customer
- 💰 Data pembayaran
- 📷 Foto produk & bukti bayar

### Jangan PERNAH:
- ❌ Ubah repository ke PUBLIC
- ❌ Share link repository ke orang lain
- ❌ Push ke repository orang lain

## 🔄 Update di Masa Depan

Setelah ada perubahan:
`powershell
git add .
git commit -m "Deskripsi perubahan"
git push
`

## 📥 Clone ke Komputer Lain

`powershell
# Clone
git clone https://github.com/USERNAME/REPO.git
cd REPO

# Buat database di phpMyAdmin
# Import file: database/retroloved_backup.sql (atau .sql lainnya)

# Selesai! Langsung bisa jalan
`

## 🆘 Troubleshooting

**Error: "Permission denied"**
→ Gunakan Personal Access Token:
1. GitHub → Settings → Developer settings
2. Personal access tokens → Generate new token
3. Pilih scope: repo
4. Copy token, gunakan sebagai password

**Error: "File too large"**
→ GitHub limit 100MB per file
→ Cari file besar: Get-ChildItem -Recurse | Where-Object {$_.Length -gt 100MB}
→ Compress atau hapus file besar tersebut

**Error: "Repository not found"**
→ Cek URL sudah benar
→ Pastikan repository sudah dibuat di GitHub

## ✨ Tips

1. **Backup Reguler**: Push setiap hari untuk backup terbaru
2. **Commit Message**: Tulis pesan yang jelas
3. **Branch**: Bisa buat branch untuk testing
4. **Private**: Jangan lupa cek selalu PRIVATE!

## 📞 Yang Ter-backup

Frontend:
- ✅ Semua halaman customer (shop, cart, checkout, profile, dll)
- ✅ CSS, JavaScript, gambar
- ✅ Authentication (login, register)

Backend:
- ✅ Admin dashboard
- ✅ Order management
- ✅ Product management
- ✅ Customer management
- ✅ Shipping tracking

Database:
- ✅ Semua tabel dan data
- ✅ Users (admin & customer)
- ✅ Products, orders, payments
- ✅ Notifications, addresses

Konfigurasi:
- ✅ Database config (dengan password)
- ✅ Email config (dengan SMTP password)
- ✅ Shipping config

## 🎉 Selesai!

Proyek Anda sekarang aman di GitHub (private)!
Bisa diakses dari mana saja, backup terlindungi.

**Good luck! 🚀**
