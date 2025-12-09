# ✅ CHECKLIST KEAMANAN SEBELUM UPLOAD KE GITHUB

## 🔒 WAJIB DICEK SEBELUM PUSH!

### 📋 Langkah 1: Cek File Sensitif

Jalankan command ini untuk memastikan file sensitif tidak akan terupload:

```powershell
# Cek apa saja yang akan di-commit
git status

# Cek apakah ada file sensitif
git ls-files | Select-String "database.php"
git ls-files | Select-String "email.php"
git ls-files | Select-String "_backup"
git ls-files | Select-String "_full"
```

**✅ AMAN jika tidak ada output!**
**❌ BAHAYA jika muncul file-file tersebut!**

---

### 📋 Langkah 2: Validasi .gitignore

Pastikan file-file ini ADA di .gitignore:

- [ ] config/database.php
- [ ] config/email.php
- [ ] ssets/images/products/* (kecuali .gitkeep)
- [ ] ssets/images/payments/* (kecuali .gitkeep)
- [ ] database/*_full*.sql
- [ ] database/*_backup*.sql
- [ ] endor/

**Cara cek:**
```powershell
cat .gitignore
```

---

### 📋 Langkah 3: Cek Kredensial di Code

Cari kredensial yang mungkin hardcoded:

```powershell
# Cari password/email di file PHP
Get-ChildItem -Recurse -Include *.php | Select-String -Pattern "password.*=.*['\"].*@" | Where-Object {$_.Line -notmatch "example"}

# Cari API key
Get-ChildItem -Recurse -Include *.php | Select-String -Pattern "api.*key" -CaseSensitive
```

**✅ AMAN jika hanya muncul dari file example atau comment**

---

### 📋 Langkah 4: Backup Lokal Terlebih Dahulu

Sebelum upload, backup dulu:

- [ ] Export database FULL (dengan data) → Simpan di luar folder proyek
- [ ] Copy folder ssets/images/ → Simpan di luar folder proyek
- [ ] Backup file config/database.php dan config/email.php

**Lokasi backup recommended:**
```
C:\Backups\RetroLoved\
├── database_full_YYYYMMDD.sql
├── images\
│   ├── products\
│   └── payments\
└── config\
    ├── database.php
    └── email.php
```

---

### 📋 Langkah 5: Validasi File Template

Pastikan file template sudah dibuat:

- [ ] config/database.example.php - ADA & tidak ada kredensial asli
- [ ] config/email.example.php - ADA & tidak ada kredensial asli
- [ ] database/retroloved_schema.sql - ADA & hanya struktur (no data)

**Cara cek:**
```powershell
Test-Path config\database.example.php
Test-Path config\email.example.php
Test-Path database\retroloved_schema.sql
```

**Semua harus return: True**

---

### 📋 Langkah 6: Test di Local

Sebelum push, test dulu:

- [ ] Website masih berfungsi normal
- [ ] Login admin berhasil
- [ ] Customer bisa order
- [ ] Email notification berfungsi
- [ ] Upload gambar berfungsi

---

### 📋 Langkah 7: Buat Repository PRIVATE

**⚠️ PENTING: Jangan buat repository PUBLIC!**

Alasan:
- ❌ Struktur database terekspos
- ❌ Logic bisnis terlihat
- ❌ Resiko keamanan tinggi
- ❌ Kompetitor bisa copy

**✅ Selalu gunakan PRIVATE repository**

---

### 📋 Langkah 8: Clean Commit History

Pastikan tidak ada commit yang mengandung data sensitif:

```powershell
# Lihat history commit
git log --oneline

# Lihat perubahan di commit terakhir
git show HEAD

# Lihat semua file yang pernah di-track
git log --all --full-history --pretty=format:"%H" -- config/database.php
```

**Jika ada file sensitif di history lama, JANGAN push!**

---

## 🚨 JIKA ADA MASALAH KEAMANAN

### Jika file sensitif sudah masuk ke staging:

```powershell
# Remove dari staging
git reset HEAD config/database.php
git reset HEAD config/email.php

# Atau reset semua
git reset HEAD .

# Lalu add ulang dengan hati-hati
git add .
git status  # Cek ulang!
```

### Jika file sensitif sudah ter-commit (belum push):

```powershell
# Undo commit terakhir (keep changes)
git reset --soft HEAD~1

# Atau undo dan buang changes
git reset --hard HEAD~1

# Lalu commit ulang dengan benar
```

### Jika sudah terlanjur push (DARURAT!):

```powershell
# 1. Hapus repository di GitHub
# 2. Hapus folder .git lokal
Remove-Item -Recurse -Force .git

# 3. Mulai dari awal dengan checklist ini
# 4. Segera ganti semua password/API key yang terekspos!
```

---

## ✅ FINAL CHECKLIST

Centang semua sebelum push:

- [ ] ✅ File sensitif sudah ada di .gitignore
- [ ] ✅ Tidak ada kredensial hardcoded di code
- [ ] ✅ Database export hanya schema (no data)
- [ ] ✅ Backup lokal sudah dibuat
- [ ] ✅ File template sudah dibuat
- [ ] ✅ Test di local sudah OK
- [ ] ✅ Repository dibuat PRIVATE
- [ ] ✅ Commit history bersih
- [ ] ✅ Sudah baca semua panduan
- [ ] ✅ Siap untuk push!

---

## 📝 Command Akhir

Jika semua sudah ✅, jalankan:

```powershell
# Cek terakhir kali
git status
git diff --cached

# Commit
git add .
git commit -m "Initial commit: RetroLoved E-Commerce Platform v2.0"

# Push
git remote add origin https://github.com/USERNAME/REPO.git
git branch -M main
git push -u origin main
```

---

## 🎉 SELESAI!

Setelah push berhasil:

1. ✅ Cek di GitHub apakah file sensitif tidak ada
2. ✅ Buat README.md yang bagus
3. ✅ Setup GitHub Actions (optional)
4. ✅ Invite collaborators (jika ada)
5. ✅ Enable branch protection (recommended)

---

## 📞 Support

Jika ada pertanyaan atau masalah:
- Baca: GITHUB_UPLOAD_GUIDE.md
- Baca: DATABASE_BACKUP_GUIDE.md
- Check: .gitignore sudah benar

**🔒 Keamanan adalah prioritas utama!**
**Lebih baik lambat tapi aman daripada cepat tapi bahaya!**
