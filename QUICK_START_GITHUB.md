# 🚀 QUICK START: Upload ke GitHub

## ⚡ Langkah Cepat (5 Menit)

### 1️⃣ Export Database (Pilih salah satu)

**Cara 1 - Otomatis (Recommended):**
```powershell
powershell -ExecutionPolicy Bypass -File database/export_database.ps1
```
Pilih opsi **2** (Schema Only) untuk GitHub

**Cara 2 - Manual via phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Pilih database **retroloved**
3. Tab **Export** → Method: **Custom**
4. Uncheck **Data** (hanya struktur!)
5. Save ke database/retroloved_schema.sql

---

### 2️⃣ Inisialisasi Git
```powershell
git init
git config user.name "Nama Anda"
git config user.email "email@anda.com"
```

---

### 3️⃣ Buat Repository di GitHub
1. Buka https://github.com/new
2. Nama: **retroloved-ecommerce**
3. **Private** ✅ (penting!)
4. **JANGAN** centang "Initialize with README"
5. Create repository
6. Copy URL yang muncul (https://github.com/username/retroloved-ecommerce.git)

---

### 4️⃣ Upload Semua File
```powershell
git remote add origin https://github.com/USERNAME/REPOSITORY.git
git add .
git commit -m "Initial commit: RetroLoved E-Commerce Platform"
git branch -M main
git push -u origin main
```

**Ganti USERNAME dan REPOSITORY dengan milik Anda!**

---

## ✅ Cek Keamanan

### File yang TIDAK ikut terupload (sudah aman):
- ✅ config/database.php (kredensial DB)
- ✅ config/email.php (kredensial email)
- ✅ ssets/images/products/* (foto produk)
- ✅ ssets/images/payments/* (bukti bayar)
- ✅ database/*_full*.sql (backup dengan data)

### File yang AMAN dan HARUS diupload:
- ✅ database/retroloved_schema.sql (struktur DB)
- ✅ config/database.example.php (template)
- ✅ config/email.example.php (template)
- ✅ Semua file .php, .js, .css
- ✅ File dokumentasi (.md)

---

## 🔄 Update di Masa Depan

Setelah ada perubahan di proyek:
```powershell
git add .
git commit -m "Deskripsi perubahan"
git push
```

---

## 📥 Clone ke Komputer Lain

```powershell
# Clone dari GitHub
git clone https://github.com/USERNAME/REPOSITORY.git
cd REPOSITORY

# Setup konfigurasi
copy config\database.example.php config\database.php
copy config\email.example.php config\email.php

# Edit file config dengan kredensial yang benar
notepad config\database.php
notepad config\email.php

# Import database di phpMyAdmin
# - Buat database baru: retroloved
# - Import: database/retroloved_schema.sql
```

---

## 🆘 Troubleshooting Cepat

**❌ "git: command not found"**
→ Install Git: https://git-scm.com/download/win

**❌ "remote: Repository not found"**
→ Cek URL repository sudah benar
→ Pastikan sudah login ke GitHub

**❌ "Permission denied"**
→ Gunakan Personal Access Token (bukan password)
→ GitHub → Settings → Developer settings → Personal access tokens

**❌ "Files too large"**
→ Hapus file besar dari git cache:
```powershell
git rm --cached path/to/large/file
```

---

## 📚 Dokumentasi Lengkap

- **[GITHUB_UPLOAD_GUIDE.md](GITHUB_UPLOAD_GUIDE.md)** - Panduan lengkap
- **[DATABASE_BACKUP_GUIDE.md](DATABASE_BACKUP_GUIDE.md)** - Backup database
- **[README.md](README.md)** - Dokumentasi proyek

---

## ✨ Tips Pro

1. **Backup Lokal**: Selalu backup database lokal sebelum push
2. **Commit Sering**: Jangan tunggu banyak perubahan
3. **Pesan Jelas**: Tulis commit message yang deskriptif
4. **Test Dulu**: Test perubahan sebelum commit
5. **Private Repo**: Gunakan private untuk keamanan

---

**🎉 Selamat! Proyek Anda siap dibackup!**

Butuh bantuan? Baca dokumentasi lengkap atau buka issue di GitHub.
