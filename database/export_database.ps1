# ============================================
# Script Export Database RetroLoved
# Untuk backup database sebelum upload ke GitHub
# ============================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  RetroLoved Database Export Tool" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Konfigurasi
$mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
$dbName = "retroloved"
$dbUser = "root"
$dbPass = ""
$outputDir = "database"
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"

# Cek apakah mysqldump ada
if (-Not (Test-Path $mysqlPath)) {
    Write-Host "❌ Error: mysqldump.exe tidak ditemukan di $mysqlPath" -ForegroundColor Red
    Write-Host "   Pastikan XAMPP sudah terinstall atau sesuaikan path di script" -ForegroundColor Yellow
    exit 1
}

# Buat folder database jika belum ada
if (-Not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
    Write-Host "✅ Folder database/ dibuat" -ForegroundColor Green
}

Write-Host "📦 Pilih jenis export:" -ForegroundColor Cyan
Write-Host "   1. Full Backup (dengan data) - JANGAN upload ke GitHub!" -ForegroundColor Yellow
Write-Host "   2. Schema Only (tanpa data) - AMAN untuk GitHub" -ForegroundColor Green
Write-Host "   3. Both (Full + Schema)" -ForegroundColor Cyan
Write-Host ""
$choice = Read-Host "Pilihan Anda (1/2/3)"

switch ($choice) {
    "1" {
        Write-Host ""
        Write-Host "📥 Exporting FULL BACKUP..." -ForegroundColor Yellow
        $outputFile = "$outputDir\retroloved_full_$timestamp.sql"
        
        if ($dbPass -eq "") {
            & $mysqlPath -u $dbUser $dbName > $outputFile 2>&1
        } else {
            & $mysqlPath -u $dbUser -p$dbPass $dbName > $outputFile 2>&1
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Full backup berhasil: $outputFile" -ForegroundColor Green
            Write-Host "⚠️  JANGAN upload file ini ke GitHub!" -ForegroundColor Red
        } else {
            Write-Host "❌ Export gagal!" -ForegroundColor Red
        }
    }
    "2" {
        Write-Host ""
        Write-Host "📥 Exporting SCHEMA ONLY..." -ForegroundColor Yellow
        $outputFile = "$outputDir\retroloved_schema.sql"
        
        if ($dbPass -eq "") {
            & $mysqlPath -u $dbUser --no-data $dbName > $outputFile 2>&1
        } else {
            & $mysqlPath -u $dbUser -p$dbPass --no-data $dbName > $outputFile 2>&1
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Schema export berhasil: $outputFile" -ForegroundColor Green
            Write-Host "✅ File ini AMAN untuk GitHub" -ForegroundColor Green
        } else {
            Write-Host "❌ Export gagal!" -ForegroundColor Red
        }
    }
    "3" {
        Write-Host ""
        Write-Host "📥 Exporting BOTH..." -ForegroundColor Yellow
        
        # Full backup
        $fullFile = "$outputDir\retroloved_full_$timestamp.sql"
        Write-Host "   → Full backup..." -ForegroundColor Gray
        if ($dbPass -eq "") {
            & $mysqlPath -u $dbUser $dbName > $fullFile 2>&1
        } else {
            & $mysqlPath -u $dbUser -p$dbPass $dbName > $fullFile 2>&1
        }
        
        # Schema only
        $schemaFile = "$outputDir\retroloved_schema.sql"
        Write-Host "   → Schema only..." -ForegroundColor Gray
        if ($dbPass -eq "") {
            & $mysqlPath -u $dbUser --no-data $dbName > $schemaFile 2>&1
        } else {
            & $mysqlPath -u $dbUser -p$dbPass --no-data $dbName > $schemaFile 2>&1
        }
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Export selesai:" -ForegroundColor Green
            Write-Host "   - Full: $fullFile (⚠️ JANGAN upload!)" -ForegroundColor Yellow
            Write-Host "   - Schema: $schemaFile (✅ AMAN)" -ForegroundColor Green
        } else {
            Write-Host "❌ Export gagal!" -ForegroundColor Red
        }
    }
    default {
        Write-Host "❌ Pilihan tidak valid!" -ForegroundColor Red
        exit 1
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "✅ Selesai!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
