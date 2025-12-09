# 📦 RetroLoved - GitHub Backup Setup
# File ini berisi semua command yang dibutuhkan untuk upload ke GitHub

# ============================================
# PERSIAPAN
# ============================================

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  🚀 RetroLoved GitHub Upload Setup" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek apakah Git sudah terinstall
Write-Host "🔍 Checking Git installation..." -ForegroundColor Yellow
if (Get-Command git -ErrorAction SilentlyContinue) {
    $gitVersion = git --version
    Write-Host "✅ Git found: $gitVersion" -ForegroundColor Green
} else {
    Write-Host "❌ Git not found! Please install from: https://git-scm.com/" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  📋 CHECKLIST KEAMANAN" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "⚠️  PENTING: Pastikan sudah melakukan ini:" -ForegroundColor Yellow
Write-Host "   1. Backup database FULL ke luar folder proyek" -ForegroundColor White
Write-Host "   2. Copy folder assets/images/ ke luar folder proyek" -ForegroundColor White
Write-Host "   3. Backup config/database.php dan config/email.php" -ForegroundColor White
Write-Host "   4. Buat repository PRIVATE di GitHub" -ForegroundColor White
Write-Host ""

$confirm = Read-Host "Sudah backup semua? (yes/no)"
if ($confirm -ne "yes") {
    Write-Host ""
    Write-Host "⚠️  Silakan backup dulu, lalu jalankan script ini lagi!" -ForegroundColor Yellow
    Write-Host "   Panduan: Baca DATABASE_BACKUP_GUIDE.md" -ForegroundColor Cyan
    exit 0
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  🔐 CEK FILE SENSITIF" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek apakah sudah git init
if (-Not (Test-Path .git)) {
    Write-Host "🔧 Initializing Git repository..." -ForegroundColor Yellow
    git init
    Write-Host "✅ Git initialized" -ForegroundColor Green
    Write-Host ""
}

# Test gitignore
Write-Host "🔍 Testing .gitignore..." -ForegroundColor Yellow
git add -n config/database.php 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "⚠️  WARNING: config/database.php akan ter-commit!" -ForegroundColor Red
    Write-Host "   Cek file .gitignore!" -ForegroundColor Yellow
} else {
    Write-Host "✅ config/database.php blocked (aman)" -ForegroundColor Green
}

git add -n config/email.php 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "⚠️  WARNING: config/email.php akan ter-commit!" -ForegroundColor Red
    Write-Host "   Cek file .gitignore!" -ForegroundColor Yellow
} else {
    Write-Host "✅ config/email.php blocked (aman)" -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ⚙️  KONFIGURASI GIT" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek konfigurasi Git
$gitName = git config --global user.name
$gitEmail = git config --global user.email

if (-Not $gitName) {
    Write-Host "📝 Setup Git user name:" -ForegroundColor Yellow
    $name = Read-Host "   Nama Anda"
    git config --global user.name "$name"
    Write-Host "✅ User name set" -ForegroundColor Green
} else {
    Write-Host "✅ Git user: $gitName" -ForegroundColor Green
}

if (-Not $gitEmail) {
    Write-Host "📝 Setup Git email:" -ForegroundColor Yellow
    $email = Read-Host "   Email Anda"
    git config --global user.email "$email"
    Write-Host "✅ Email set" -ForegroundColor Green
} else {
    Write-Host "✅ Git email: $gitEmail" -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  🌐 SETUP REMOTE REPOSITORY" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "📌 Contoh URL:" -ForegroundColor Cyan
Write-Host "   https://github.com/username/retroloved-ecommerce.git" -ForegroundColor Gray
Write-Host ""
$repoUrl = Read-Host "Masukkan URL GitHub repository Anda"

# Cek apakah remote sudah ada
$existingRemote = git remote get-url origin 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "⚠️  Remote 'origin' sudah ada: $existingRemote" -ForegroundColor Yellow
    $updateRemote = Read-Host "Update remote? (yes/no)"
    if ($updateRemote -eq "yes") {
        git remote set-url origin $repoUrl
        Write-Host "✅ Remote updated" -ForegroundColor Green
    }
} else {
    git remote add origin $repoUrl
    Write-Host "✅ Remote added" -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  📤 COMMIT & PUSH" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "📦 Adding files..." -ForegroundColor Yellow
git add .

Write-Host "📝 Creating commit..." -ForegroundColor Yellow
git commit -m "Initial commit: RetroLoved E-Commerce Platform v2.0 - Complete system with admin dashboard, customer portal, order management, and shipping tracking"

Write-Host "🌿 Setting main branch..." -ForegroundColor Yellow
git branch -M main

Write-Host "🚀 Pushing to GitHub..." -ForegroundColor Yellow
git push -u origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  ✅ SUCCESS!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🎉 Proyek berhasil diupload ke GitHub!" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Langkah selanjutnya:" -ForegroundColor Cyan
    Write-Host "   1. Buka repository di GitHub" -ForegroundColor White
    Write-Host "   2. Pastikan repository dalam mode PRIVATE" -ForegroundColor White
    Write-Host "   3. Cek tidak ada file sensitif yang terupload" -ForegroundColor White
    Write-Host "   4. Baca README.md di repository" -ForegroundColor White
    Write-Host ""
    Write-Host "📚 Dokumentasi:" -ForegroundColor Cyan
    Write-Host "   - QUICK_START_GITHUB.md" -ForegroundColor White
    Write-Host "   - GITHUB_UPLOAD_GUIDE.md" -ForegroundColor White
    Write-Host "   - DATABASE_BACKUP_GUIDE.md" -ForegroundColor White
    Write-Host "   - SECURITY_CHECKLIST.md" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  ❌ ERROR!" -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "⚠️  Push gagal! Kemungkinan penyebab:" -ForegroundColor Yellow
    Write-Host "   1. URL repository salah" -ForegroundColor White
    Write-Host "   2. Tidak ada akses ke repository" -ForegroundColor White
    Write-Host "   3. Repository belum dibuat di GitHub" -ForegroundColor White
    Write-Host "   4. Perlu Personal Access Token" -ForegroundColor White
    Write-Host ""
    Write-Host "💡 Solusi:" -ForegroundColor Cyan
    Write-Host "   Baca GITHUB_UPLOAD_GUIDE.md bagian Troubleshooting" -ForegroundColor White
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Cyan
