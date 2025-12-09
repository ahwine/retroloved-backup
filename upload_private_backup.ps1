# ============================================
# SCRIPT UPLOAD LENGKAP KE GITHUB (PRIVATE)
# Untuk backup pribadi - SEMUA file akan diupload
# ============================================

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  🚀 RetroLoved - Upload LENGKAP ke GitHub (Private)" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  Mode: BACKUP LENGKAP (termasuk database & gambar)" -ForegroundColor Yellow
Write-Host "    Pastikan repository Anda PRIVATE!" -ForegroundColor Red
Write-Host ""

# Cek Git
if (-Not (Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Git belum terinstall!" -ForegroundColor Red
    Write-Host "   Download dari: https://git-scm.com/" -ForegroundColor Yellow
    exit 1
}

$gitVersion = git --version
Write-Host "✅ Git found: $gitVersion" -ForegroundColor Green
Write-Host ""

# Konfirmasi
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  ⚠️  PERINGATAN PENTING!" -ForegroundColor Red
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "File yang akan diupload TERMASUK:" -ForegroundColor Yellow
Write-Host "  • config/database.php (kredensial database)" -ForegroundColor White
Write-Host "  • config/email.php (kredensial email & password SMTP)" -ForegroundColor White
Write-Host "  • assets/images/products/ (foto produk)" -ForegroundColor White
Write-Host "  • assets/images/payments/ (bukti pembayaran)" -ForegroundColor White
Write-Host "  • vendor/ (dependencies)" -ForegroundColor White
Write-Host ""
Write-Host "Repository HARUS PRIVATE!" -ForegroundColor Red
Write-Host ""
$confirm = Read-Host "Lanjutkan? (yes/no)"

if ($confirm -ne "yes") {
    Write-Host ""
    Write-Host "❌ Upload dibatalkan" -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  📦 BACKUP DATABASE (Optional)" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Ingin export database sekarang? (yes/no)" -ForegroundColor Yellow
$exportDb = Read-Host

if ($exportDb -eq "yes") {
    if (Test-Path "database/export_database.ps1") {
        Write-Host "🔄 Menjalankan export database..." -ForegroundColor Yellow
        & powershell -ExecutionPolicy Bypass -File "database/export_database.ps1"
    } else {
        Write-Host "⚠️  Script export tidak ditemukan, lewati..." -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  ⚙️  KONFIGURASI GIT" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""

# Init Git jika belum
if (-Not (Test-Path ".git")) {
    Write-Host "🔧 Initializing Git..." -ForegroundColor Yellow
    git init
    Write-Host "✅ Git initialized" -ForegroundColor Green
}

# Config user
$gitName = git config --global user.name
$gitEmail = git config --global user.email

if (-Not $gitName) {
    Write-Host "📝 Setup Git user name:" -ForegroundColor Yellow
    $name = Read-Host "   Nama Anda"
    git config --global user.name "$name"
} else {
    Write-Host "✅ Git user: $gitName" -ForegroundColor Green
}

if (-Not $gitEmail) {
    Write-Host "📝 Setup Git email:" -ForegroundColor Yellow
    $email = Read-Host "   Email Anda"
    git config --global user.email "$email"
} else {
    Write-Host "✅ Git email: $gitEmail" -ForegroundColor Green
}

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  🌐 SETUP REPOSITORY" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📌 Contoh URL repository:" -ForegroundColor Cyan
Write-Host "   https://github.com/username/retroloved-backup.git" -ForegroundColor Gray
Write-Host ""
Write-Host "⚠️  Pastikan repository sudah dibuat dan dalam mode PRIVATE!" -ForegroundColor Yellow
Write-Host ""
$repoUrl = Read-Host "Masukkan URL GitHub repository"

# Set remote
$existingRemote = git remote get-url origin 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "⚠️  Remote sudah ada, update..." -ForegroundColor Yellow
    git remote set-url origin $repoUrl
} else {
    git remote add origin $repoUrl
}
Write-Host "✅ Remote configured" -ForegroundColor Green

Write-Host ""
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "  📤 COMMIT & PUSH" -ForegroundColor Yellow
Write-Host "================================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "📦 Adding ALL files..." -ForegroundColor Yellow
git add .

Write-Host ""
Write-Host "📊 Files yang akan di-commit:" -ForegroundColor Cyan
git status --short

Write-Host ""
$commitMsg = Read-Host "Commit message (Enter untuk default)"
if (-Not $commitMsg) {
    $commitMsg = "Full backup: RetroLoved E-Commerce - 2025-12-09 22:16"
}

Write-Host ""
Write-Host "📝 Creating commit..." -ForegroundColor Yellow
git commit -m "$commitMsg"

Write-Host ""
Write-Host "🌿 Setting main branch..." -ForegroundColor Yellow
git branch -M main

Write-Host ""
Write-Host "🚀 Pushing to GitHub..." -ForegroundColor Yellow
Write-Host "   (Ini mungkin memakan waktu jika file banyak/besar)" -ForegroundColor Gray
Write-Host ""

git push -u origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "================================================================" -ForegroundColor Cyan
    Write-Host "  ✅ BERHASIL!" -ForegroundColor Green
    Write-Host "================================================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🎉 Semua file berhasil diupload ke GitHub!" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Yang ter-upload:" -ForegroundColor Cyan
    Write-Host "   ✅ Semua source code (.php, .js, .css)" -ForegroundColor White
    Write-Host "   ✅ Database dengan data (jika ada .sql)" -ForegroundColor White
    Write-Host "   ✅ Gambar produk & payment" -ForegroundColor White
    Write-Host "   ✅ File konfigurasi (database.php, email.php)" -ForegroundColor White
    Write-Host "   ✅ Dependencies (vendor/)" -ForegroundColor White
    Write-Host ""
    Write-Host "⚠️  PENTING:" -ForegroundColor Yellow
    Write-Host "   • Pastikan repository dalam mode PRIVATE" -ForegroundColor White
    Write-Host "   • Jangan share link repository dengan orang lain" -ForegroundColor White
    Write-Host "   • Kredensial Anda ada di repository ini" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "================================================================" -ForegroundColor Cyan
    Write-Host "  ❌ ERROR!" -ForegroundColor Red
    Write-Host "================================================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Push gagal! Kemungkinan:" -ForegroundColor Yellow
    Write-Host "  1. URL repository salah" -ForegroundColor White
    Write-Host "  2. Repository belum dibuat di GitHub" -ForegroundColor White
    Write-Host "  3. Tidak ada akses (perlu Personal Access Token)" -ForegroundColor White
    Write-Host "  4. File terlalu besar (GitHub limit 100MB per file)" -ForegroundColor White
    Write-Host ""
    Write-Host "💡 Cara dapat Personal Access Token:" -ForegroundColor Cyan
    Write-Host "   1. GitHub → Settings → Developer settings" -ForegroundColor White
    Write-Host "   2. Personal access tokens → Tokens (classic)" -ForegroundColor White
    Write-Host "   3. Generate new token → Pilih 'repo' scope" -ForegroundColor White
    Write-Host "   4. Copy token dan gunakan sebagai password saat push" -ForegroundColor White
    Write-Host ""
}

Write-Host "================================================================" -ForegroundColor Cyan
